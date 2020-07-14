<?php
	session_start();
	error_reporting(0);
	include("../connect/conn.php");
	$items = array();		$fix = array();		$fix_tot = array();
	$rowno=0;	$no=0;

	$sql = "select * from (
			select tipe, aging, safety_day, grade, working_day, to_char(total_order,'9,999,999,990') total_order, total_order as ori_total_order,
			to_char(order_per_day,'9,999,999,990') order_per_day, order_per_day as ori_order_per_day, to_char(average,'9,999,999,990.00') average, average as ori_average,
			nvl(to_char(heating_room,'9,999,999,990'),0) heating_room, heating_room as ori_heating_room, to_char(std_minimum,'9,999,999,990') std_minimum, std_minimum as ori_std_minimum,
			to_char(nvl(qty_aging,0) - std_minimum,'9,999,999,990') balance, nvl(qty_aging,0) - std_minimum as ori_balance, to_char(before_label,'9,999,999,990') before_label, before_label as ori_before_label, 
			to_char(after_label,'9,999,999,990') after_label, after_label as ori_after_label, to_char(suspended,'9,999,999,990') suspended, suspended as ori_suspended,
			tipe2, to_char(nvl(qty_aging,0),'9,999,999,990') qty_aging, nvl(qty_aging,0) as ori_qty_aging,
	    	case when tipe2 like 'LR03%' then order_per_day * (aging + 1) else order_per_day * (aging + 0.5) end ORI_MINIMUM_STOCK,
      		case when tipe2 like 'LR03%' then to_char(order_per_day * (aging + 1),'9,999,999,990') else to_char(order_per_day * (aging + 0.5),'9,999,999,990') end MINIMUM_STOCK,
      		case when tipe2 like 'LR03%' then order_per_day * (aging + 3) else order_per_day * (aging + 1.5) end ORI_MAXIMUM_STOCK,
      		case when tipe2 like 'LR03%' then to_char(order_per_day * (aging + 3),'9,999,999,990') else to_char(order_per_day * (aging + 1.5),'9,999,999,990') end MAXIMUM_STOCK,
	    	case substr(tipe2,0,3) when 'LR0' then '3' when 'LR1' then '1' when 'LR6' then '6' else '' end as LR,
	    	to_char( balance,'9,999,999,990') GAP, balance as ORI_GAP, CEIL(QTY_AGING / ORDER_PER_DAY) DAY
	    	from ztb_semi_bat_v2 order by tipe
		) order by lr asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$Lr_Nya = '';						$tipeNya = '';
	$grand_tot_all_order=0;				$grand_tot_order=0;				$tot_order=0;
	$grand_tot_all_order_per_day=0;		$grand_tot_order_per_day=0;		$tot_order_per_day=0;
	$grand_tot_all_average=0;			$grand_tot_average=0;			$tot_average=0;
	$grand_tot_all_std_min=0;			$grand_tot_std_min=0;			$tot_std_min=0;
	$grand_tot_all_heating_room=0;		$grand_tot_heating_room=0;		$tot_heating_room=0;
	$grand_tot_all_min_stock=0;			$grand_tot_min_stock=0;			$tot_min_stock=0;
	$grand_tot_all_max_stock=0;			$grand_tot_max_stock=0;			$tot_max_stock=0;
	$grand_tot_all_qty_aging=0;			$grand_tot_qty_aging=0;			$tot_qty_aging=0;
	$grand_tot_all_balance=0;			$grand_tot_balance=0;			$tot_balance=0;
	$grand_tot_all_before=0;			$grand_tot_before=0;			$tot_before=0;
	$grand_tot_all_after=0;				$grand_tot_after=0;				$tot_after=0;
	$grand_tot_all_suspend=0;			$grand_tot_suspend=0;			$tot_suspend=0;
	$grand_tot_all_gap=0;				$grand_tot_gap=0;				$tot_gap=0;
	$grand_tot_all_day=0;				$grand_tot_day=0;				$tot_day=0;

	while($row = oci_fetch_object($data)){
		$tp = $row->TIPE2;
		$lr = $row->LR;
		$order = floatval($row->ORI_TOTAL_ORDER);
		$order_per_day = floatval($row->ORI_ORDER_PER_DAY);
		$average = floatval($row->ORI_AVERAGE);
		$std_minimum = floatval($row->ORI_STD_MINIMUM);
		$heating_room = floatval($row->ORI_HEATING_ROOM);
		$min = floatval($row->ORI_MINIMUM_STOCK);
		$max = floatval($row->ORI_MAXIMUM_STOCK);
		$qty_aging = floatval($row->ORI_QTY_AGING);
		$balance = floatval($row->ORI_BALANCE);
		$before_label = floatval($row->ORI_BEFORE_LABEL);
		$after_label = floatval($row->ORI_AFTER_LABEL);
		$suspended = floatval($row->ORI_SUSPENDED);
		$gap = floatval($row->ORI_GAP);

		if ($no == 0){
			$tot_order+=$order;
			$tot_order_per_day+=$order_per_day;
			$tot_average+=$average;
			$tot_std_min+=$std_minimum;
			$tot_heating_room+=$heating_room;
			$tot_min_stock+=$min;
			$tot_max_stock+=$max;
			$tot_qty_aging+=$qty_aging;
			$tot_balance+=$balance;
			$tot_before+=$before_label;
			$tot_after+=$after_label;
			$tot_suspend+=$suspended;
			$tot_gap+=$gap;
		}else{
			if($tipeNya == $tp){
				$tot_order+=$order;
				$tot_order_per_day+=$order_per_day;
				$tot_average+=$average;
				$tot_std_min+=$std_minimum;
				$tot_heating_room+=$heating_room;
				$tot_min_stock+=$min;
				$tot_max_stock+=$max;
				$tot_qty_aging+=$qty_aging;
				$tot_balance+=$balance;
				$tot_before+=$before_label;
				$tot_after+=$after_label;
				$tot_suspend+=$suspended;
				$tot_gap+=$gap;
			}else{
				$grand_tot_order+=$tot_order;
				$grand_tot_order_per_day+=$tot_order_per_day;
				$grand_tot_average+=$tot_average;
				$grand_tot_std_min+=$tot_std_min;
				$grand_tot_heating_room+=$tot_heating_room;
				$grand_tot_min_stock+=$tot_min_stock;
				$grand_tot_max_stock+=$tot_max_stock;
				$grand_tot_qty_aging+=$tot_qty_aging;
				$grand_tot_balance+=$tot_balance;
				$grand_tot_before+=$tot_before;
				$grand_tot_after+=$tot_after;
				$grand_tot_suspend+=$tot_suspend;
				$grand_tot_gap+=$tot_gap;
				$tot_day = ceil($tot_qty_aging / $tot_order_per_day);
				$grand_tot_day = ceil($grand_tot_qty_aging / $grand_tot_order_per_day);

				$fix['TIPE'] = '<span style="font-size:11px;"><b>TOTAL</b></span>';
				$fix['TOTAL_ORDER'] = '<span style="font-size:11px;"><b>'.number_format($tot_order).'</b></span>';
				$fix['ORDER_PER_DAY'] = '<span style="font-size:11px;"><b>'.number_format($tot_order_per_day).'</b></span>';
				$fix['AVERAGE'] = '<span style="font-size:11px;"><b>'.number_format($tot_average).'</b></span>';
				$fix['MINIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($tot_min_stock).'</b></span>';
				$fix['STD_MINIMUM'] = '<span style="font-size:11px;"><b>'.number_format($tot_std_min).'</b></span>';
				$fix['MAXIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($tot_max_stock).'</b></span>';
				$fix['HEATING_ROOM'] = '<span style="font-size:11px;"><b>'.number_format($tot_heating_room).'</b></span>';
				$fix['QTY_AGING'] = '<span style="font-size:11px;"><b>'.number_format($tot_qty_aging).'</b></span>';
				$fix['BALANCE'] = '<span style="font-size:11px;"><b>'.number_format($tot_balance).'</b></span>';
				$fix['BEFORE_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($tot_before).'</b></span>';
				$fix['AFTER_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($tot_after).'</b></span>';
				$fix['SUSPENDED'] = '<span style="font-size:11px;"><b>'.number_format($tot_suspend).'</b></span>';
				$fix['GAP'] = '<span style="font-size:11px;"><b>'.number_format($tot_gap).'</b></span>';
				$fix['DAY'] = '<span style="font-size:11px;"><b>'.number_format($tot_day).'</b></span>';
				array_push($items, $fix);

				$tot_order=0;
				$tot_order_per_day=0;
				$tot_average=0;
				$tot_std_min=0;
				$tot_heating_room=0;
				$tot_min_stock=0;
				$tot_max_stock=0;
				$tot_qty_aging=0;
				$tot_balance=0;
				$tot_before=0;
				$tot_after=0;
				$tot_suspend=0;
				$tot_gap=0;
				$tot_day=0;

				if($Lr_Nya <> $lr){
					$no++;

					$grand_tot_all_order+=$grand_tot_order;
					$grand_tot_all_order_per_day+=$grand_tot_order_per_day;
					$grand_tot_all_average+=$grand_tot_average;
					$grand_tot_all_std_min+=$grand_tot_std_min;
					$grand_tot_all_heating_room+=$grand_tot_heating_room;
					$grand_tot_all_min_stock+=$grand_tot_min_stock;
					$grand_tot_all_max_stock+=$grand_tot_max_stock;
					$grand_tot_all_qty_aging+=$grand_tot_qty_aging;
					$grand_tot_all_balance+=$grand_tot_balance;
					$grand_tot_all_before+=$grand_tot_before;
					$grand_tot_all_after+=$grand_tot_after;
					$grand_tot_all_suspend+=$grand_tot_suspend;
					$grand_tot_all_gap+=$grand_tot_gap;
					$grand_tot_all_day+=$grand_tot_day;

					$fix_tot['TIPE'] = '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>LR-'.$Lr_Nya.'</b></span>';
					$fix_tot['TOTAL_ORDER'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_order).'</b></span>';
					$fix_tot['ORDER_PER_DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_order_per_day).'</b></span>';
					$fix_tot['AVERAGE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_average).'</b></span>';
					$fix_tot['MINIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_min_stock).'</b></span>';
					$fix_tot['STD_MINIMUM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_std_min).'</b></span>';
					$fix_tot['MAXIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_max_stock).'</b></span>';
					$fix_tot['HEATING_ROOM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_heating_room).'</b></span>';
					$fix_tot['QTY_AGING'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_qty_aging).'</b></span>';
					$fix_tot['BALANCE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_balance).'</b></span>';
					$fix_tot['BEFORE_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_before).'</b></span>';
					$fix_tot['AFTER_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_after).'</b></span>';
					$fix_tot['SUSPENDED'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_suspend).'</b></span>';
					$fix_tot['GAP'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_gap).'</b></span>';
					$fix_tot['DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_day).'</b></span>';
					array_push($items, $fix_tot);	

					$grand_tot_order=0;				
					$grand_tot_order_per_day=0;		
					$grand_tot_average=0;			
					$grand_tot_std_min=0;			
					$grand_tot_heating_room=0;	
					$grand_tot_min_stock=0;
					$grand_tot_max_stock=0;	
					$grand_tot_qty_aging=0;			
					$grand_tot_balance=0;			
					$grand_tot_before=0;			
					$grand_tot_after=0;				
					$grand_tot_suspend=0;
					$grand_tot_gap=0;
					$grand_tot_day=0;
				}

				$no++;

				$tot_order+=$order;
				$tot_order_per_day+=$order_per_day;
				$tot_average+=$average;
				$tot_std_min+=$std_minimum;
				$tot_heating_room+=$heating_room;
				$tot_min_stock+=$min;
				$tot_max_stock+=$max;
				$tot_qty_aging+=$qty_aging;
				$tot_balance+=$balance;
				$tot_before+=$before_label;
				$tot_after+=$after_label;
				$tot_suspend+=$suspended;
				$tot_gap+=$gap;
			}
		}

		$tipeNya = $row->TIPE2;
		$Lr_Nya = $row->LR;

		array_push($items, $row);

		$bb = $items[$no]->ORI_BALANCE;
		$t = "'".$items[$no]->TIPE."'";
		$b = intval($items[$no]->ORI_BALANCE);
		$a = intval($items[$no]->MINIMUM_STOCK);
		$z = intval($items[$no]->MAXIMUM_STOCK);
		$ori_h = intval($items[$no]->ORI_HEATING_ROOM);
		$h = $items[$no]->HEATING_ROOM;
		$ori_qa = intval($items[$no]->ORI_QTY_AGING);
		$qa = $items[$no]->QTY_AGING;
		$ori_bl = intval($items[$no]->ORI_BEFORE_LABEL);
		$bl = $items[$no]->BEFORE_LABEL;
		$ori_ss = intval($items[$no]->ORI_SUSPENDED);
		$ss = $items[$no]->SUSPENDED;
		

		// if ($b < $a){
		// 	$items[$no]->STS = 'SHORTAGE';
		// }elseif ($b > $z){
		// 	$items[$no]->STS = 'OVER';
		// }elseif($b>=$a AND $b<=$z){
		// 	$items[$no]->STS = 'ACHIEVED';
		// }
		if($qa < $items[$no]->MINIMUM_STOCK){
			$items[$no]->STS = 'SHORTAGE';
		}elseif ($qa > $items[$no]->MAXIMUM_STOCK){
			$items[$no]->STS = 'OVER';
		}else{
			$items[$no]->STS = 'ACHIEVED';
		}

		if ($ori_h > 0){
			$heat = "'HEAT'";
			$items[$no]->HEATING_ROOM = '<a href="javascript:void(0)" onclick="info_heat('.$t.','.$heat.')"  style="text-decoration: none; color: white;">'.$h.'</a>';
		}

		if ($ori_qa > 0){
			$age = "'AGING'";
			$items[$no]->QTY_AGING = '<a href="javascript:void(0)" onclick="info_aging('.$t.','.$age.')"  style="text-decoration: none; color: white;">'.$qa.'</a>';
		}

		if ($ori_bl > 0){
			$b_label = "'BEFORE LABEL'";
			$items[$no]->BEFORE_LABEL = '<a href="javascript:void(0)" onclick="info_b_label('.$t.','.$b_label.')"  style="text-decoration: none; color: white;">'.$bl.'</a>';
		}

		if ($ori_ss > 0){
			$susp = "'SUSPENDED'";
			$items[$no]->SUSPENDED = '<a href="javascript:void(0)" onclick="info_suspended('.$t.','.$susp.')"  style="text-decoration: none; color: white;">'.$ss.'</a>';
		}

		$no++;
	}

	$grand_tot_order+=$tot_order;
	$grand_tot_order_per_day+=$tot_order_per_day;
	$grand_tot_average+=$tot_average;
	$grand_tot_std_min+=$tot_std_min;
	$grand_tot_heating_room+=$tot_heating_room;
	$grand_tot_min_stock+=$tot_min_stock;
	$grand_tot_max_stock+=$tot_max_stock;
	$grand_tot_qty_aging+=$tot_qty_aging;
	$grand_tot_balance+=$tot_balance;
	$grand_tot_before+=$tot_before;
	$grand_tot_after+=$tot_after;
	$grand_tot_suspend+=$tot_suspend;
	$grand_tot_gap+=$tot_gap;
	$tot_day = ceil($tot_qty_aging / $tot_order_per_day);
	$grand_tot_day = ceil($grand_tot_qty_aging / $grand_tot_order_per_day);

	$grand_tot_all_order+=$grand_tot_order;
	$grand_tot_all_order_per_day+=$grand_tot_order_per_day;
	$grand_tot_all_average+=$grand_tot_average;
	$grand_tot_all_std_min+=$grand_tot_std_min;
	$grand_tot_all_heating_room+=$grand_tot_heating_room;
	$grand_tot_all_min_stock+=$grand_tot_min_stock;
	$grand_tot_all_max_stock+=$grand_tot_max_stock;
	$grand_tot_all_qty_aging+=$grand_tot_qty_aging;
	$grand_tot_all_balance+=$grand_tot_balance;
	$grand_tot_all_before+=$grand_tot_before;
	$grand_tot_all_after+=$grand_tot_after;
	$grand_tot_all_suspend+=$grand_tot_suspend;
	$grand_tot_all_gap+=$grand_tot_gap;
	$grand_tot_all_day = ceil($grand_tot_all_qty_aging / $grand_tot_all_order_per_day);

	$fix['TIPE'] = '<span style="font-size:11px;"><b>TOTAL</b></span>';
	$fix['TOTAL_ORDER'] = '<span style="font-size:11px;"><b>'.number_format($tot_order).'</b></span>';
	$fix['ORDER_PER_DAY'] = '<span style="font-size:11px;"><b>'.number_format($tot_order_per_day).'</b></span>';
	$fix['AVERAGE'] = '<span style="font-size:11px;"><b>'.number_format($tot_average).'</b></span>';
	$fix['MINIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($tot_min_stock).'</b></span>';
	$fix['STD_MINIMUM'] = '<span style="font-size:11px;"><b>'.number_format($tot_std_min).'</b></span>';
	$fix['MAXIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($tot_max_stock).'</b></span>';
	$fix['HEATING_ROOM'] = '<span style="font-size:11px;"><b>'.number_format($tot_heating_room).'</b></span>';
	$fix['QTY_AGING'] = '<span style="font-size:11px;"><b>'.number_format($tot_qty_aging).'</b></span>';
	$fix['BALANCE'] = '<span style="font-size:11px;"><b>'.number_format($tot_balance).'</b></span>';
	$fix['BEFORE_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($tot_before).'</b></span>';
	$fix['AFTER_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($tot_after).'</b></span>';
	$fix['SUSPENDED'] = '<span style="font-size:11px;"><b>'.number_format($tot_suspend).'</b></span>';
	$fix['GAP'] = '<span style="font-size:11px;"><b>'.number_format($tot_gap).'</b></span>';
	$fix['DAY'] = '<span style="font-size:11px;"><b>'.number_format($tot_day).'</b></span>';
	array_push($items, $fix);

	$fix_tot['TIPE'] = '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>LR-'.$Lr_Nya.'</b></span>';
	$fix_tot['TOTAL_ORDER'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_order).'</b></span>';
	$fix_tot['ORDER_PER_DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_order_per_day).'</b></span>';
	$fix_tot['AVERAGE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_average).'</b></span>';
	$fix_tot['MINIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_min_stock).'</b></span>';
	$fix_tot['STD_MINIMUM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_std_min).'</b></span>';
	$fix_tot['MAXIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_max_stock).'</b></span>';
	$fix_tot['HEATING_ROOM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_heating_room).'</b></span>';
	$fix_tot['QTY_AGING'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_qty_aging).'</b></span>';
	$fix_tot['BALANCE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_balance).'</b></span>';
	$fix_tot['BEFORE_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_before).'</b></span>';
	$fix_tot['AFTER_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_after).'</b></span>';
	$fix_tot['SUSPENDED'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_suspend).'</b></span>';
	$fix_tot['GAP'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_gap).'</b></span>';
	$fix_tot['DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_day).'</b></span>';
	array_push($items, $fix_tot);

	$fix_tot['TIPE'] = '<span style="font-size:11px;"><b>GRAND<br/>TOTAL<br/>ALL</b></span>';
	$fix_tot['TOTAL_ORDER'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_order).'</b></span>';
	$fix_tot['ORDER_PER_DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_order_per_day).'</b></span>';
	$fix_tot['AVERAGE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_average).'</b></span>';
	$fix_tot['MINIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_min_stock).'</b></span>';
	$fix_tot['STD_MINIMUM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_std_min).'</b></span>';
	$fix_tot['MAXIMUM_STOCK'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_max_stock).'</b></span>';
	$fix_tot['HEATING_ROOM'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_heating_room).'</b></span>';
	$fix_tot['QTY_AGING'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_qty_aging).'</b></span>';
	$fix_tot['BALANCE'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_balance).'</b></span>';
	$fix_tot['BEFORE_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_before).'</b></span>';
	$fix_tot['AFTER_LABEL'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_after).'</b></span>';
	$fix_tot['SUSPENDED'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_suspend).'</b></span>';
	$fix_tot['GAP'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_gap).'</b></span>';
	$fix_tot['DAY'] = '<span style="font-size:11px;"><b>'.number_format($grand_tot_all_day).'</b></span>';
	array_push($items, $fix_tot);

	$result["rows"] = $items;
	echo json_encode($result);
?>
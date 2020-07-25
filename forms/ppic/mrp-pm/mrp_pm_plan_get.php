<?php
	session_start();
	error_reporting(0);
	set_time_limit(0);
	include("../../../connect/conn.php");
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';
	$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

	if ($sts == 'lower'){
		if($cmb_item_no!=''){
			$it = "a.item_no = $cmb_item_no AND ";
			$it2 = "item_no = $cmb_item_no AND ";
		}else{
			$it = "";
			$it2 = "";
		}	
	}else{
		if($cmb_item_no!=''){
			$it = "a.item_no in (select distinct lower_item_no from structure where upper_item_no=$cmb_item_no
				and level_no = (select max(level_no) from structure where upper_item_no=$cmb_item_no)) AND ";
			$it2 = "item_no in (select distinct lower_item_no from structure where upper_item_no=$cmb_item_no
				and level_no = (select max(level_no) from structure where upper_item_no=$cmb_item_no)) AND ";
		}else{
			$it = "";
			$it2 = "";
		}
	}
	

	//cek item to DI
	$filename = "json/json_item_to_DI_MRP.json";
	$string = file_get_contents($filename);
	$json_a = json_decode($string, true);
	$itemNya = '';

	foreach ($json_a as $item => $item_a) {
	    if ($item_a['item_no'] == $cmb_item_no){
	    	$itemNya .= 'Y';
	    }
	}
	// end item to DI

	//cek balance
	$cek = "select isnull(sum(bal_qty),0) as balance from po_details where item_no=$cmb_item_no and eta <= cast(getdate() as date)
		and bal_qty != 0";
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	
	$dt_cek = sqlsrv_fetch_object($data_cek);
	$b = ' (BALANCE : '.number_format($dt_cek->BALANCE).')';

	$where = "where $it a.item_no is not null";
	$where2 = "where $it2 item_no is not null";

	$qry =  "select NO_ID,DESCRIPTION,N_1,N_2,N_3,N_4,N_5,N_6,N_7,N_8,N_9,N_10,N_11,N_12,N_13,N_14,N_15,N_16,N_17,N_18,N_19,N_20,N_21,N_22,N_23,N_24,N_25,N_26,N_27,N_28,N_29,N_30,N_31,N_32,N_33,N_34,N_35,N_36,N_37,N_38,N_39,N_40,N_41,N_42,N_43,N_44,N_45,N_46,N_47,N_48,N_49,N_50,N_51,N_52,N_53,N_54,N_55,N_56,N_57,N_58,N_59,N_60,N_61,N_62,N_63,N_64,N_65,N_66,N_67,N_68,N_69,N_70,N_71,N_72,N_73,N_74,N_75,N_76,N_77,N_78,N_79,N_80,N_81,N_82,N_83,N_84,N_85,N_86,N_87,N_88,N_89,N_90,aa.ITEM_NO,ITEM_DESC,item,item_description,unit_pl,purchase_leadtime from (
				select a.*, c.item, c.description as item_description,unit_pl from ztb_mrp_data_pck a 
				inner join item c on a.item_no=c.item_no
			    inner join unit u on c.uom_q = u.unit_code
				$where
			)aa
			left outer join (
	      		select ax.item_no, bx.purchase_leadtime from (
	        		select max(line_no) as line_no, item_no from itemmaker $where2
	          		group by item_no)ax
	        	inner join
	          	(select line_no,item_no, purchase_leadtime from itemmaker)bx on ax.item_no=bx.item_no AND ax.line_no=bx.line_no
	    	)ab on aa.item_no = ab.item_no
			order by aa.item_no, aa.no_id";
	$data_qry = sqlsrv_query($connect, strtoupper($qry));
	

	$items = array();		$minArr = array();			$maxArr = array();
	$rowno=0;
	$no=1;
	$nilai = 'N_';		$itm = "";

	while ($row = sqlsvr_fetch_object($data_qry)) {
		array_push($items, $row);
		$id = intval($items[$rowno]->NO_ID);
		$desc = strtoupper($items[$rowno]->DESCRIPTION);
		
		if ($desc == 'PURCHASE'){
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.$b.'</b>';
		}else{
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.'</b>';	
		}

	

		if($id == 1){												//PLAN
			
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_plan('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b><u>'.$items[$rowno]->DESCRIPTION.'</u></a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n,2)." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" onclick="info_assy('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}

		if($id == 2){												//ARRIVE
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPO('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}

		if($id == 3){
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_ost('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b>'.$items[$rowno]->DESCRIPTION.'</a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				if($n == 0 or is_null($n)){
					if($itemNya == 'Y'){
						if($i <= $items[$rowno]->PURCHASE_LEADTIME){
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="add_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;"><b>'.$nx.'<br/>OUT OF<br/>LEADTIME</b></a>';
						}else{
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="add_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
						}
					}else{
						if($i <= $items[$rowno]->PURCHASE_LEADTIME){
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;">'.$nx.'<br/>OUT OF<br/>LEADTIME</a>';
						}else{
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
						}
					}
				}else{
					if($itemNya == 'Y'){
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="info_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
					}else{
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
					}
				}
			}
		}

		if($id == 4){
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$nb = number_format($n+$dt_cek->BALANCE);
				if($n <= 0 && $nb <= 0){
					$items[$rowno]->$v = '<span style="text-decoration: none; color: #FF0000;"><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}elseif($n <= 0 && $nb > 0){
					$items[$rowno]->$v = '<span style="text-decoration: none; color: #FF7F00;"><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}else{
					$items[$rowno]->$v = '<span><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}
			}
		}

		if($itm == $items[$rowno]->ITEM_NO){
			$items[$rowno]->ITEM_DESCRIPTION = "<p><br/></p>";
		}else{
			$items[$rowno]->ITEM_DESCRIPTION = '<b>'.$items[$rowno]->ITEM_NO.'<br/>'.$items[$rowno]->ITEM_DESCRIPTION.'</b>';
			$items[$rowno]->NO = $no++;
		}

		$itm = $items[$rowno]->ITEM_NO;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);	
?>
<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];
	$st = isset($_REQUEST['st']) ? strval($_REQUEST['st']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$tgl = isset($_REQUEST['tgl']) ? strval($_REQUEST['tgl']) : '';

	$tambah_date = strtotime('+1 day',strtotime($tgl));
	$t_date = date('Y-m-d',$tambah_date);

	$a1 = $tgl.' 07:00:00'; 		$z1 = $tgl.' 14:59:59';
	$a2 = $tgl.' 15:00:00'; 		$z2 = $tgl.' 22:59:59';
	$a3 = $tgl.' 23:00:00'; 		$z3 = $t_date.' 06:59:59';

	$box1=0;		$box2=0;		$box3=0;
	$pcs1=0;		$pcs2=0;		$pcs3=0;

	include("../../connect/conn.php");

	if($st == 'R'){
		$sql = "select * from (select a.*, b.name, 
			(select count(*) from ztb_assy_trans_ng where assy_line= a.assy_line AND 
		    cell_type= a.cell_type AND pallet= a.pallet AND tanggal_produksi= a.tanggal_produksi AND worker_id= a.worker_id1) as VW_TROUBLE,
		    to_char(a.tanggal_produksi,'YYYY-MM-DD') as tgl_prod, to_char(a.tanggal_actual,'YYYY-MM-DD') as tgl_act, c.qty_box, d.position
			from ztb_assy_kanban a
			left join ztb_worker b on a.worker_id1=b.worker_id
			inner join ztb_assy_set_pallet c on a.assy_line=c.assy_line
			left join (select id_print, max(position) as position from ztb_assy_heating group by id_print) d on a.id_print = d.id_print
			where replace(a.assy_line,'#','-') = '$line' order by a.id desc) where rownum <=50";	
	}else{
		$sql = "select * from (select a.*, b.name, 
			(select count(*) from ztb_assy_trans_ng where assy_line= a.assy_line AND 
		    cell_type= a.cell_type AND pallet= a.pallet AND tanggal_produksi= a.tanggal_produksi AND worker_id= a.worker_id1) as VW_TROUBLE,
		    (select count(*) from ztb_assy_kanban where id_print= a.id_print) as VW_HASIL,
		    to_char(a.tanggal_produksi,'YYYY-MM-DD') as tgl_prod, to_char(a.tanggal_actual,'YYYY-MM-DD') as tgl_act, c.qty_box,
		    case when a.start_date >= '$a1' and start_date < '$z1' then 'SHIFT-1'
           	when a.start_date >= '$a2' and start_date < '$z2' then 'SHIFT-2'
           	when a.start_date >= '$a3' and start_date < '$z3' then 'SHIFT-3' end as shift, nvl(d.position,0) as position
			from ztb_assy_kanban a
			left join ztb_worker b on a.worker_id1=b.worker_id
			inner join ztb_assy_set_pallet c on a.assy_line=c.assy_line
			left join (select id_print, max(position) as position from ztb_assy_heating group by id_print) d on a.id_print = d.id_print
			where replace(a.assy_line,'#','-') = '$line' and to_char(tanggal_actual,'YYYY-MM-DD')= '$tgl'
			order by a.id desc) where rownum <=50";
	}

	//echo $sql;
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$foot = array();
	$tot_plt = 0 ;		$tot_box = 0 ;
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$id_p = $items[$rowno]->ID_PRINT;

		$j = "select sum(qty_act_perbox) from ztb_assy_kanban where ID_PRINT=$id_p";
		$dt = oci_parse($connect, $j);
		oci_execute($dt);
		$rdt = oci_fetch_array($dt);
		$jum = $rdt[0];

		$items[$rowno]->TOTAL_ACT = $jum;
		$qty_p = $items[$rowno]->QTY_PERPALLET;
		$qty_b = $items[$rowno]->QTY_PERBOX;
		$qty_act_p = $items[$rowno]->QTY_ACT_PERPALLET;
		$qty_act_b = $items[$rowno]->QTY_ACT_PERBOX;

		$nm = strtoupper($items[$rowno]->NAME);
		$a_date = $items[$rowno]->START_DATE;
		$z_date = $items[$rowno]->END_DATE;
		$rmain = $qty_b-$jum+$qty_act_b;
		$items[$rowno]->REMAIN = $rmain;
		$used = $qty_b - $rmain;

		if($items[$rowno]->VW_HASIL == 2){
			$items[$rowno]->USED = $used;
		}else{
			$items[$rowno]->USED = $jum;
		}

		$items[$rowno]->START_DATE = str_replace(' ', '<br/>', $a_date);
		$items[$rowno]->NAME = $items[$rowno]->WORKER_ID1."<br/>".$nm;
		$items[$rowno]->QTY_ACT_PERBOX = number_format($qty_act_b);
		$items[$rowno]->QTY_ACT_PERPALLET = number_format($qty_act_p);
		$items[$rowno]->END_DATE = str_replace(' ', '<br/>', $z_date);

		if(is_null($z_date)) {
			$items[$rowno]->END_DATE = '<span style="color:red;font-size:12px;"><b>BELUM<br/>SELESAI</b></span>';
		}

		if($st == 'H'){
			$tot_plt += $qty_act_p;
			$tot_box += $qty_act_b;
			$line = $items[$rowno]->ASSY_LINE;

			$shift = $items[$rowno]->SHIFT;
			if ($shift == 'SHIFT-1'){
				$box1 += $qty_act_b;
				$pcs1 += $qty_act_p;
			}else if ($shift == 'SHIFT-2'){
				$box2 += $qty_act_b;
				$pcs2 += $qty_act_p;
			}else if ($shift == 'SHIFT-3'){
				$box3 += $qty_act_b;
				$pcs3 += $qty_act_p;
			}
		}

		$rowno++;
	}
	$foot[0]->QTY_ACT_PERPALLET = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_plt).' pcs</b></span>';
	$foot[0]->QTY_ACT_PERBOX = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_box).' box</b></span>';
	$foot[0]->ASSY_LINE = '<span style="color:blue;font-size:12px;"><b>'.$line.'</b></span>';

	$foot[1]->QTY_ACT_PERPALLET = '<span style="color:brown;font-size:12px;"><b>'.number_format($pcs1).' pcs</b></span>';
	$foot[1]->QTY_ACT_PERBOX = '<span style="color:brown;font-size:12px;"><b>'.number_format($box1).' box</b></span>';
	$foot[1]->ASSY_LINE = '<span style="color:brown;font-size:12px;"><b>SHIFT-1</b></span>';

	$foot[2]->QTY_ACT_PERPALLET = '<span style="color:green;font-size:12px;"><b>'.number_format($pcs2).' pcs</b></span>';
	$foot[2]->QTY_ACT_PERBOX = '<span style="color:green;font-size:12px;"><b>'.number_format($box2).' box</b></span>';
	$foot[2]->ASSY_LINE = '<span style="color:green;font-size:12px;"><b>SHIFT-2</b></span>';

	$foot[3]->QTY_ACT_PERPALLET = '<span style="color:purple;font-size:12px;"><b>'.number_format($pcs3).' pcs</b></span>';
	$foot[3]->QTY_ACT_PERBOX = '<span style="color:purple;font-size:12px;"><b>'.number_format($box3).' box</b></span>';
	$foot[3]->ASSY_LINE = '<span style="color:purple;font-size:12px;"><b>SHIFT-3</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>
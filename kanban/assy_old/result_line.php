<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];
	$st = isset($_REQUEST['st']) ? strval($_REQUEST['st']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$tgl = isset($_REQUEST['tgl']) ? strval($_REQUEST['tgl']) : '';

	include("../../connect/conn.php");

	if($st == 'R'){
		$sql = "select * from (select a.*, b.name, 
			(select count(*) from ztb_assy_trans_ng where assy_line= a.assy_line AND 
		    cell_type= a.cell_type AND pallet= a.pallet AND tanggal_produksi= a.tanggal_produksi AND worker_id= a.worker_id1) as VW_TROUBLE,
		    to_char(a.tanggal_produksi,'YYYY-MM-DD') as tgl_prod, to_char(a.tanggal_actual,'YYYY-MM-DD') as tgl_act, c.qty_box
			from ztb_assy_kanban a
			left join ztb_worker b on a.worker_id1=b.worker_id
			inner join ztb_assy_set_pallet c on a.assy_line=c.assy_line
			where replace(a.assy_line,'#','-') = '$line' order by a.id desc) where rownum <=50";	
	}else{
		$sql = "select * from (select a.*, b.name, 
			(select count(*) from ztb_assy_trans_ng where assy_line= a.assy_line AND 
		    cell_type= a.cell_type AND pallet= a.pallet AND tanggal_produksi= a.tanggal_produksi AND worker_id= a.worker_id1) as VW_TROUBLE,
		    to_char(a.tanggal_produksi,'YYYY-MM-DD') as tgl_prod, to_char(a.tanggal_actual,'YYYY-MM-DD') as tgl_act, c.qty_box
			from ztb_assy_kanban a
			left join ztb_worker b on a.worker_id1=b.worker_id
			inner join ztb_assy_set_pallet c on a.assy_line=c.assy_line
			where replace(a.assy_line,'#','-') = '$line' and to_char(tanggal_actual,'YYYY-MM-DD')= '$tgl'
			order by a.id desc) where rownum <=50";
	}

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$foot = array();
	$tot_plt = 0 ;		$tot_box = 0 ;
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$qty_act_p = $items[$rowno]->QTY_ACT_PERPALLET;
		$items[$rowno]->QTY_ACT_PERPALLET = number_format($qty_act_p);

		$qty_act_b = $items[$rowno]->QTY_ACT_PERBOX;
		$items[$rowno]->QTY_ACT_PERBOX = number_format($qty_act_b);

		$nm = strtoupper($items[$rowno]->NAME);
		$items[$rowno]->NAME = $items[$rowno]->WORKER_ID1."<br/>".$nm;

		$a_date = $items[$rowno]->START_DATE;
		$items[$rowno]->START_DATE = str_replace(' ', '<br/>', $a_date);

		$z_date = $items[$rowno]->END_DATE;
		$items[$rowno]->END_DATE = str_replace(' ', '<br/>', $z_date);

		if($st == 'H'){
			$tot_plt += $qty_act_p;
			$tot_box += $qty_act_b;
		}

		$rowno++;
	}

	if($st == 'H'){
		$foot[0]->QTY_ACT_PERPALLET = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_plt).'</b></span>';
		$foot[0]->QTY_ACT_PERBOX = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_box).'</b></span>';

		$result["footer"] = $foot;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
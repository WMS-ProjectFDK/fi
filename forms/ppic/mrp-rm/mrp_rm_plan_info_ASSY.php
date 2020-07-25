<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';
	//2017-11-29
	$exp = explode('-', $tgl_plan);

	include("../../../connect/conn.php");

	$cek = "select aa.assy_line, aa.cell_type, aa.item_no, aa.item, aa.description, aa.konversi, bb.qty, bb.id_plan, (bb.qty/1000)*aa.konversi as usage from
		(
		select a.assy_line, a.cell_type, a.item_no, i.item, i.description, a.konversi 
		from ztb_material_konversi a
		inner join item i on a.item_no=i.item_no
		where a.item_no = $item_no
		)aa
		left outer join 
		(
		select assy_line, cell_type, qty, id_plan
		from ztb_assy_plan 
		where tanggal=".$exp[2]." and bulan=".$exp[1]." and tahun=".$exp[0]."
		and revisi = (select max(revisi)from ztb_assy_plan where tanggal=".$exp[2]." and bulan=".$exp[1]." and tahun= ".$exp[0].")
		)bb on aa.assy_line=bb.assy_line AND aa.cell_type=bb.cell_type
		where aa.assy_line=bb.assy_line AND aa.cell_type=bb.cell_type" ;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	
	//echo $cek;
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($Q);

		$o = $items[$rowno]->KONVERSI;
		$items[$rowno]->KONVERSI = number_format($o,5);

		$l = $items[$rowno]->USAGE;
		$items[$rowno]->USAGE = '<b>'.number_format(ceil($l)).'</b>';

		$i = $items[$rowno]->ITEM;
		$d = $items[$rowno]->DESCRIPTION;

		$items[$rowno]->DESCRIPTION = $i.'<br/>'.$d;		

		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>
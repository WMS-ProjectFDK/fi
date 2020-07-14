<?php
	ini_set('max_execution_time', -1);
	session_start();

	$tipe= isset($_REQUEST['tipe']) ? strval($_REQUEST['tipe']) : '';
	$jns= isset($_REQUEST['jns']) ? strval($_REQUEST['jns']) : '';

	include("../connect/conn.php");

	$cek = "select distinct a.id_print, b.id_plan, b.pallet, b.assy_line, b.cell_type, a.qty,b.tanggal_produksi,c.suspend_date,c.problem,c.status  from zvw_semi_bat a
		inner join ztb_assy_kanban b on a.id_print = b.id_print
		inner join ztb_qc_data c on a.id_print = c.id_print
		where a.tipe='$tipe' and a.ket='$jns' ";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	$foot = array();
	$items = array();
	$rowno=0;		$tot_qty = 0;
	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$tot_qty += intval($Q);
		$items[$rowno]->QTY = '<span style="color:blue;font-size:12px;"><b>'.number_format($Q).'</b></span>';
		$rowno++;
	}

	$foot[0]->QTY = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_qty).'</b></span>';
	
	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>
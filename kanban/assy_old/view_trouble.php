<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];

	$worker = isset($_REQUEST['worker']) ? strval($_REQUEST['worker']) : '';
	$assy_line = isset($_REQUEST['assy_line']) ? strval($_REQUEST['assy_line']) : '';
	$cell_type = isset($_REQUEST['cell_type']) ? strval($_REQUEST['cell_type']) : '';
	$pallet = isset($_REQUEST['pallet']) ? strval($_REQUEST['pallet']) : '';
	$tgl_prod = isset($_REQUEST['tgl_prod']) ? strval($_REQUEST['tgl_prod']) : '';

	include("../../connect/conn.php");

	$sql = "select a.assy_line, a.cell_type, a.pallet, a.tanggal_produksi, a.ng_qty, b.ng_name_proses, b.ng_name, a.ng_id, a.ng_id_proses, a.ng_no, a.perbaikan
		from ztb_assy_trans_ng a
		inner join ztb_assy_ng b on a.ng_id_proses= b.ng_id_proses AND a.ng_id= b.ng_id
		where replace(a.assy_line,'#','-')='$assy_line' AND a.cell_type='$cell_type' AND 
		a.pallet= $pallet AND a.tanggal_produksi = '$tgl_prod' AND worker_id = '$worker' ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
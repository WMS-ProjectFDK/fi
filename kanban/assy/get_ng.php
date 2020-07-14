<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];

	include("../../connect/conn.php");

	$sql = "select a.ng_no, a.assy_line, a.cell_type, a.pallet, a.tanggal_produksi, a.ng_id_proses, 
		coalesce(b.ng_name_proses,'-') ng_proses, a.ng_id, b.ng_name, a.ng_qty, a.perbaikan
		from ztb_assy_trans_ng a 
		inner join ztb_assy_ng b on a.ng_id_proses= b.ng_id_proses and a.ng_id= b.ng_id
		where a.worker_id='$id_kanban' AND rownum <= 3
    	order by ng_no desc";
		//AND ng_no = (select max(ng_no) from ztb_assy_trans_ng where worker_id='$id_kanban')";
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
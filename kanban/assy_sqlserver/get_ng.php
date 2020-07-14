<?php
	session_start();
	$id_kanban = $_SESSION['id_kanban'];

	include("../../connect/conn_kanbansys.php");

	$sql = "select TOP 3 a.NG_NO, a.ASSY_LINE, a.CELL_TYPE, a.PALLET, CONVERT(CHAR(10),a.tanggal_produksi,120) AS TANGGAL_PRODUKSI, a.NG_ID_PROSES, coalesce(b.ng_name_proses,'-') NG_PROSES, a.NG_ID, b.NG_NAME, a.NG_QTY, a.PERBAIKAN
		from ztb_assy_trans_ng a 
		inner join ztb_assy_ng b on a.ng_id_proses= b.ng_id_proses and a.ng_id= b.ng_id
		where a.worker_id='$id_kanban'
    	order by ng_no desc";
		//AND ng_no = (select max(ng_no) from ztb_assy_trans_ng where worker_id='$id_kanban')";
	$data = odbc_exec($connect, $sql);

	$items = array();
	$rowno=0;
	while($row = odbc_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
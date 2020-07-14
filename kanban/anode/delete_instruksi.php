<?php
	header("Content-type: application/json");
	include("../../connect/conn_kanbansys.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['ty_gel']) ? strval($_REQUEST['ty_gel']) : '';
	$kanban = isset($_REQUEST['kanban']) ? strval($_REQUEST['kanban']) : '';

	$sql = "delete from ztb_assy_anode_gel where type_gel = '$ty_gel' and kanban_no= $kanban and
		upto_date_instruksi is not null and upto_date_drymix is null AND upto_date_hasil_anode is null";
	$result = odbc_exec($connect, $sql);

	echo json_encode(array('successMsg'=>'success'));
?>
<?php
	header("Content-type: application/json");
	$id_kanbanL = strval($_REQUEST['id_kanbanL']);
	$worker = strval($_REQUEST['worker']);
	$rowid = strval($_REQUEST['rowid']);

	$arrData = array();
	$arrNo = 0;
	$msg = '';
	
	include("../../connect/conn.php");
	$sql = "delete from ztb_kanban_lbl where Worker_ID = $worker and idkanban = $id_kanbanL and rowid='$rowid'";
	echo $sql;
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$pesan = oci_error($result);
	$msg = $pesan['message'];

	if($msg != ''){
		$msg .= " Insert Query Error : $ins";
		break;
	}

	if($msg == ''){
		echo json_encode(array('success'=>true));
	}else{
		echo json_encode(array('errorMsg'=>$msg));
	}
?>
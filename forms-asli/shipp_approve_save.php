<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$user = $_SESSION['id_wms'];
	$approve_answer = htmlspecialchars($_REQUEST['approve_answer']);

	$sql = "update indication set remark='2' where answer_no = '$approve_answer' ";
	$stmt = oci_parse($connect, $sql);
	$res = oci_execute($stmt);
	echo $sql;

	echo json_encode(array('successMsg'=>$approve_answer));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
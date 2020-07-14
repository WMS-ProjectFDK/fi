<?php
session_start();
include("../connect/conn.php");
$answer_no = strval($_REQUEST['answer_no']);

if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$sql = "delete from answer where answer_no='$answer_no' ";
		$data = oci_parse($connect, $sql);
		oci_execute($data);

		echo json_encode(array('successMsg'=>'success'));
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Error'));
}
?>
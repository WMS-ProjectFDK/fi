<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	
	$qno  = htmlspecialchars($_REQUEST['qno']);
	$qdt  = htmlspecialchars($_REQUEST['qdt']);

	$now=date('Y-m-d');
	$user = $_SESSION['id_wms'];

	$query = "INSERT INTO ZTB_PRF_QUOTATION_HEADER(quotation_no, quotation_date, user_entry, last_update) 
		VALUES('$qno', TO_DATE('$qdt','yyyy-mm-dd'), '$user', TO_DATE('$now','yyyy-mm-dd'))";
	$sql = oci_parse($connect, $query);
	oci_execute($sql);

	if ($sql){
        echo json_encode("Success");
    }else{
        echo json_encode(array('errorMsg'=>'Error'));
    }
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
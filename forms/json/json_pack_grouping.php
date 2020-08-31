<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$sql = "select LABEL_TYPE_CODE, LABEL_TYPE_NAME from LABEL_TYPE order by LABEL_TYPE_CODE asc";
	$result = sqlsrv_query($connect, $sql);
	$arrNo = 0;
    $arrData = array();
    
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
    }
    
	echo json_encode($arrData);
?>
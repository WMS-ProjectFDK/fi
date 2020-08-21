<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

	$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
    $sql = "select distinct DOC_NAME from SI_DOC
        where LINE_NO=1 AND DOC_NAME IS NOT NULL
        order by DOC_NAME";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();

	while ($row=sqlsrv_fetch_array($result)){
		if ($row[0] == 'B/L'){
			$arrData[$arrNo] = array("doc_name"=>rtrim($row[0]), "selected"=>true);
		}else{
			$arrData[$arrNo] = array("doc_name"=>rtrim($row[0]));
		}
		
		$arrNo++;
	}
	echo json_encode($arrData);
?>
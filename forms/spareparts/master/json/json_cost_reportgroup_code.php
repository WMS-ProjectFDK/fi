<?php
	include("../../../../connect/conn.php");
    header("Content-type: application/json");
    
    $sql = "select REPORTGROUP_CODE as CODE, REPORTGROUP_CODE+' : '+REPORTGROUP_NAME as NAME
        from SP_REPORTGROUP
        order by REPORTGROUP_CODE";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrNo = 0;
	$arrData = array();
	while ($row=sqlsrv_fetch_array($result)){
		if ($arrNo == 0){
			$arrData[$arrNo] = array("id"=>rtrim($row[0]), "name"=>rtrim($row[1]), "selected"=>true);
		}else{
			$arrData[$arrNo] = array("id"=>rtrim($row[0]), "name"=>rtrim($row[1]));
		}
		$arrNo++;
	}
	echo json_encode($arrData);
?>
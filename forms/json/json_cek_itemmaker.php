<?php
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select count(*) as j from itemmaker where item_no = $item_no";
	$result = sqlsrv_query($connect, $sql);
	
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		if($row[0] == 0){
			$ket = "Item Maker not setting ";
		}else{
			$ket = "Item Maker OK";
		}

		$arrData[$arrNo] = array("maker"=>$ket);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
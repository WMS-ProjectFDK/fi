<?php
	$day = isset($_REQUEST['day']) ? strval($_REQUEST['day']) : '';
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select max(purchase_leadtime) lead from itemmaker where item_no = $item_no";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_array($result)){
		if($row[0] == 0){
			$ket = "Lead Time is null";
		}elseif($day > $row[0]){
			$ket = "Lead time OK";
		}else{
			$ket = "Short lead time<br/>Setting lead time (".$row[0]." Days)";
		}

		$arrData[$arrNo] = array("lead"=>$ket);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
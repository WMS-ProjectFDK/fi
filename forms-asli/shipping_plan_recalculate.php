<?php
	ini_set('max_execution_time', -1);
	session_start();
	$PPBE = isset($_REQUEST['PPBE']) ? strval($_REQUEST['PPBE']) : '';
	$ITEM_NO = isset($_REQUEST['ITEM_NO']) ? strval($_REQUEST['ITEM_NO']) : '';
	$QTY = isset($_REQUEST['QTY']) ? strval($_REQUEST['QTY']) : '';
	$WO = isset($_REQUEST['WO']) ? strval($_REQUEST['WO']) : '';
	$RD= isset($_REQUEST['RD']) ? strval($_REQUEST['RD']) : '';

	include("../connect/conn.php");

	$ins_cc = "delete from ZTB_CONTAINER_TEMP ";
	$data_cc = oci_parse($connect, $ins_cc);
	oci_execute($data_cc);

	IF($RD<>'xxx'){
		$ins_cc = "delete from ztb_shipping_detail where rowid = '$RD' ";
		$data_cc = oci_parse($connect, $ins_cc);
		oci_execute($data_cc);
	};
	IF($QTY>0){
		$field .= "PPBE,"				;	$value .= "'$PPBE',";
		$field .= "WO_NO,"			    ;	$value .= "'$WO',";
		$field .= "ITEM_NO,"			;	$value .= "'$ITEM_NO',";
		$field .= "QUANTITY"			;	$value .= "$QTY";
		chop($field);              			chop($value);
	    $ins_cc = "insert into ZTB_CONTAINER_TEMP ($field) select $value from dual";
	    echo($ins_cc);
		$data_cc = oci_parse($connect, $ins_cc);
		oci_execute($data_cc);
	}	
	

	// $sql = "select PPBE_NO,'$WO' WO_NO,ITEM_NO, QTY,PALLET,CARTON_NOT_FULL,CONTAINERS,NET,GROSS,MSM,cast(ROWID as varchar(50)) RD  from ztb_shipping_detail where ppbe_no = '$PPBE' and wo_no = '$WO'";

	// $data = oci_parse($connect, $sql);
	// oci_execute($data);

	// $items = array();
	// $rowno=0;
	// while($row = oci_fetch_object($data)){
	// 	array_push($items, $row);
		
	// 	$rowno++;
	// }
	// $result["rows"] = $items;
	// echo json_encode($result);
	


?>
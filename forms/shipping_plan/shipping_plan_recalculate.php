<?php
	ini_set('max_execution_time', -1);
	session_start();
	$PPBE = isset($_REQUEST['PPBE']) ? strval($_REQUEST['PPBE']) : '';
	$ITEM_NO = isset($_REQUEST['ITEM_NO']) ? strval($_REQUEST['ITEM_NO']) : '';
	$QTY = isset($_REQUEST['QTY']) ? strval($_REQUEST['QTY']) : '';
	$WO = isset($_REQUEST['WO']) ? strval($_REQUEST['WO']) : '';
	$RD= isset($_REQUEST['RD']) ? strval($_REQUEST['RD']) : '';

	include("../../connect/conn.php");

	$ins_cc = "delete from ZTB_CONTAINER_TEMP ";
	$data_cc = sqlsrv_query($connect, $ins_cc);

	IF($RD<>'xxx'){
		$ins_cc = "delete from ztb_shipping_detail where rowid = '$RD' ";
		$data_cc = sqlsrv_query($connect, $ins_cc);
	};
	IF($QTY>0){
		$field .= "PPBE,"				;	$value .= "'$PPBE',";
		$field .= "WO_NO,"			    ;	$value .= "'$WO',";
		$field .= "ITEM_NO,"			;	$value .= "'$ITEM_NO',";
		$field .= "QUANTITY"			;	$value .= "$QTY";
		chop($field);              			chop($value);
	    $ins_cc = "insert into ZTB_CONTAINER_TEMP ($field) select $value from dual";
	    echo($ins_cc);
		$data_cc = sqlsrv_query($connect, $ins_cc);
	}
?>
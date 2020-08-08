<?php
	ini_set('max_execution_time', -1);
	session_start();
	$user_name = $_SESSION['id_wms'];
	$PPBE = isset($_REQUEST['PPBE']) ? strval($_REQUEST['PPBE']) : '';
	$ITEM_NO = isset($_REQUEST['ITEM_NO']) ? strval($_REQUEST['ITEM_NO']) : '';
	$QTY = isset($_REQUEST['QTY']) ? strval($_REQUEST['QTY']) : '';
	$WO = isset($_REQUEST['WO']) ? strval($_REQUEST['WO']) : '';
	$SI = isset($_REQUEST['SI']) ? strval($_REQUEST['SI']) : '';

	include("../../connect/conn.php");

	$p = substr($PPBE, 0, 3);;
	$ins_cc = "insert into ztb_ppbe (DO_NO,PERSON_CODE,PPBE_NO,NO,PERIOD) values('$SI','$user_name','$PPBE','$p',2018)";
	$data_cc = sqlsrv_query($connect, $ins_cc);

	$ins_cc = "delete from ZTB_CONTAINER_TEMP ";
	$data_cc = sqlsrv_query($connect, $ins_cc);

	$ins_cc = "delete from ztb_shipping_detail where ppbe_no = '$PPBE' and wo_no = '$WO' ";
	$data_cc = sqlsrv_query($connect, $ins_cc);

	$field .= "PPBE,"				;	$value .= "'$PPBE',";
	$field .= "WO_NO,"			    ;	$value .= "'$WO',";
	$field .= "ITEM_NO,"			;	$value .= "'$ITEM_NO',";
	$field .= "QUANTITY"			;	$value .= "$QTY";
	chop($field);              			chop($value);
    $ins_cc = "insert into ZTB_CONTAINER_TEMP ($field) select $value from dual";
	$data_cc = sqlsrv_query($connect, $ins_cc);

	$sql = "select PPBE_NO,'$WO' WO_NO,ITEM_NO, QTY,PALLET,CARTON_NOT_FULL,CONTAINERS,NET,GROSS,MSM,
		ROW_NUMBER() over (order by answer_no asc) RD,CONTAINER_VALUE  
		from ztb_shipping_detail 
		where ppbe_no = '$PPBE' and wo_no = '$WO'";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
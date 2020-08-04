<?php
header("Content-type: application/json");
include("../../connect/conn.php");
$sts = strval($_REQUEST['sts']);
$wo = strval($_REQUEST['wo']);
$pltA = strval($_REQUEST['pltA']);
$pltZ = strval($_REQUEST['pltZ']);

$msg = '';

if ($sts == 'ZTB_ITEM_BOOK'){
	$sql = "DELETE ZTB_ITEM_BOOK WHERE WO_NO='$wo' AND PLT_NO >= $pltA AND PLT_NO <= $pltZ";
	$stmt1 = sqlsrv_query($connect, $sql);


	$sql = "DELETE ztb_m_plan WHERE WO_NO='$wo' AND PLT_NO >= $pltA AND PLT_NO <= $pltZ";	
	$stmt2 = sqlsrv_query($connect, $sql);
	
}else{
	$sql = "DELETE FROM $sts WHERE WO_NO='$wo' AND PLT_NO >= $pltA AND PLT_NO <= $pltZ";
	$stmt = sqlsrv_query($connect, $sql);

}

$msg .= $pesan['message'];

if($msg != ''){
	echo json_encode(array('errorMsg'=>'Kanban DELETE Process Error : '.$sql ));

}else{
	echo json_encode(array('successMsg'=>'success'));	
}
?>
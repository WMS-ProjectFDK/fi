<?php
$dn_no = strval($_REQUEST['dn_no']);
include("../../../connect/conn.php");

/*
TGL: 02/OCT/2017
ID: UENG(2)
*/

$del2 = "delete from ztb_wh_daily_needs where id_needs='".$dn_no."'";
$data_del2 = sqlsrv_query($connect, $del2);


if($data_del2){
	echo json_encode(array('success'=>true));
}else{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
<?php
session_start();
$fc_trans_code = htmlspecialchars($_REQUEST['fc_trans_code']);

include("../connect/koneksi.php");

$sql = "delete from fc_customer where fc_trans_code='$fc_trans_code'";
$result = @pg_query($sql);

$sql_fcsupp = "delete from forecast_supplier where fc_sup_transcode='$fc_trans_code'";
$result_fcsupp = @pg_query($sql_fcsupp);

if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
<?php
header("Content-type: application/json");
include("../../connect/conn.php");
$gr_no = strval($_REQUEST['gr_no']);
$msg = '';

$sql = "BEGIN ZSP_GR_DELETE(:V_GR_NO); end;";
$stmt = oci_parse($connect, $sql);
oci_bind_by_name($stmt, ':V_GR_NO', $gr_no);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg .= $pesan['message'];

if($msg != ''){
	echo json_encode(array('errorMsg'=>'GR-DELETE Process Error : '.$sql ));
	break;
}else{
	echo json_encode(array('successMsg'=>'success'));	
}
?>
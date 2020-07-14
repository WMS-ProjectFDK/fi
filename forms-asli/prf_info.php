<?php
$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';

include("../connect/conn2.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];

$sql = "select budget,req,act from ztb_prf_parameter where doc_no=to_char(SYSDATE,'YYYYMM') and departement='COMPONENT'";
$data = oci_parse($connect, $sql);
oci_execute($data);
$dt = oci_fetch_array($data);
$arrData = array();
$arrNo = 0;

if ($data) {
    $arrData[$arrNo] = array(
    					"budget"=>number_format(rtrim($dt[0]),2),
    					"req"=>number_format(rtrim($dt[1]),2),
    					"act"=>number_format(rtrim($dt[2]),2)
    				   );
}
echo json_encode($arrData);
?>
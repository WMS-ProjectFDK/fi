<?php
include("../../connect/conn3.php");
header("Content-type: application/json");
$sql = "select value from master_device where id='002'";
$rs=odbc_exec($conn_timbangan,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
    $arrData[$arrNo] = array("VALUE"=>floatval(odbc_result($rs,"VALUE")));
    $arrNo++;
}
echo json_encode($arrData);
?>
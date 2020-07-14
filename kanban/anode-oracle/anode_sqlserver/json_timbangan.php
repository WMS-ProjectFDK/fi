<?php
include("../../connect/conn_kanbansys.php");
header("Content-type: application/json");
$sql = "select value from master_device where id='002'";
$rs = odbc_exec($connect,$sql);
$arrNo = 0;
while ($row = odbc_fetch_object($rs)){
    $arrData[$arrNo] = array("VALUE"=>floatval($row->value));
    $arrNo++;
}
echo json_encode($arrData);
?>
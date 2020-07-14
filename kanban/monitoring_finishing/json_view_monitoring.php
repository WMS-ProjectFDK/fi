<?php
include("../../connect/conn_kanbansys.php");
$line = isset($_REQUEST["line"]) ? strval($_REQUEST["line"]) : "";
$items = array();

if($line == 'autoshrink'){
    $sql = "select * from vw_autoshrink";
}

$rs = odbc_exec($connect,$sql);
$row = odbc_fetch_object($rs);

array_push($items, $row);
echo json_encode($items);
?>
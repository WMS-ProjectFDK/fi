<?php
error_reporting(0);
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);

include("../../connect/conn_kanbansys.php");
$line = isset($_REQUEST["line"]) ? strval($_REQUEST["line"]) : "";
$items = array();

if($line == 'LR03-1'){
    $sql = "select * from vw_lr31";
}elseif ($line == 'LR03-2') {
    $sql = "select * from vw_lr32";
}elseif ($line == 'LR6-1') {
    $sql = "select * from vw_lr61";
}elseif ($line == 'LR6-2') {
    $sql = "select * from vw_lr62";
}elseif ($line == 'LR6-3') {
    $sql = "select * from vw_lr63";
}elseif ($line == 'LR6-4') {
    $sql = "select * from vw_lr64";
}elseif ($line == 'LR6-6') {
    $sql = "select * from vw_lr66";
}

$rs = odbc_exec($connect,$sql);
$row = odbc_fetch_object($rs);

array_push($items, $row);

$fp = fopen('result_'.$line.'.json', 'w');
fwrite($fp, json_encode($items));
fclose($fp);

echo json_encode($items);
?>
<?php
header("Content-type: application/json");
session_start();
set_time_limit(0);
ini_set('memory_limit', '-1');
include("../../connect/conn.php");

$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
$type = isset($_REQUEST['type']) ? strval($_REQUEST['type']) : '';
$user_name = $_SESSION['id_wms'];

$arrData = array();
$arrNo = 0;

$sql = "BEGIN zsp_kanban_print('$user_name','$wo_no','$type'); END;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);

$sql = "select wo_no,plt_no,tanggal,case when tanggal is null then 'BELUM DI PRINT' ELSE 'SUDAH DI PRINT' end sts 
	from ztb_kanban_print_temp 
	where wo_no='$wo_no' and user_name = '$user_name' group by wo_no,plt_no,tanggal";

$result = oci_parse($connect, $sql);
oci_execute($result);
$arrData = array();
$arrNo = 0;
while ($row=oci_fetch_array($result)){
	$arrData[$arrNo] = array(
		"WORK_ORDER"=>rtrim($row[0]), 
        "PALLET"=>rtrim($row[1]),
        "STATUS"=>rtrim($row[3]),
        "PRINT_DATE"=>rtrim($row[2]),
	);
	$arrNo++;
}
echo json_encode($arrData);
?>
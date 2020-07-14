<?php
error_reporting(0);
include("../connect/conn.php");

$sql = "select * from person";
$data = oci_parse($connect, $sql);
$r = oci_execute($data);

if (!$r) {
    $e = oci_error($data);
    echo json_encode(array('errorMsg'=>htmlentities($e['message'])));
}else{
	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
}
?>
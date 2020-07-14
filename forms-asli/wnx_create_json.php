<?php 
include("../connect/conn.php");
$sql = "select * from cell_grade"; 
$result = oci_parse($connect, $sql);
oci_execute($result);

$response = array();
$posts = array();

while($row=oci_fetch_object($result)) { 
  	array_push($response, $row);
} 

$fp = fopen('wnx_results.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
?> 
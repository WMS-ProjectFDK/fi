<?php
	set_time_limit(0); 
	include("../connect/conn.php");
	$sql = "BEGIN zsp_semi_bat(); END;";
	$stmt = oci_parse($connect, $sql);
	$res = oci_execute($stmt);
?>
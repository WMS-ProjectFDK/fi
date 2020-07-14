<?php
	include("../connect/conn.php");
	$sql = "BEGIN ZSP_SAFETY_STOCK_1(); END;";
	$stmt = oci_parse($connect, $sql);
	$res = oci_execute($stmt);
?>
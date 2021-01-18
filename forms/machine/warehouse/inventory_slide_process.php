<?php
	include("../../../connect/conn.php");
    $sql = "{call WHINVENTORY_SLIDE_SP}";
	$stmt = sqlsrv_query($connect, $sql);
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
?>
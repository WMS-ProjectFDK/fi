<?php
	include("../../../connect/conn.php");
    $sql = "{call ZSP_MRP_MATERIAL}";
	$stmt = sqlsrv_query($connect, $sql);
	if( $stmt === false ) {
		die( print_r( sqlsrv_errors(), true));
	}
?>
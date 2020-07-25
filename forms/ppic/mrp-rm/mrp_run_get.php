<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	
	include("../../../connect/conn.php");
    $sql = "{call ZSP_MRP_MATERIAL}";
	$stmt = sqlsrv_query($connect, $sql);
	// if( $stmt === false ) {
	// 	die( print_r( sqlsrv_errors(), true));
	// }
?>
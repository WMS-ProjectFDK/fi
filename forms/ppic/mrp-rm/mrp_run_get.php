<?php
	include("../../../connect/conn.php");
    $sql = "{call ZSP_MRP_MATERIAL}";
	$stmt = sqlsrv_query($connect, $sql);
	if( $stmt === false )
			{
				//die( printf("$sql") );
				//echo "Error in executing statement 3.\n";
				die( print_r( sqlsrv_errors(), true));
			}
	
?>
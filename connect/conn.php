<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	
	

	$serverName = "localhost,1433"; 
	$connectionInfo = array( "Database"=>"FDKSYS20","uid"=>"sa","pwd"=>"P@ssw0rd");
	$connect = sqlsrv_connect( $serverName, $connectionInfo);
	
	if($connect) {
		$varConn = "Y";
	}else{
		$varConn = "N";
		die(print_r(sqlsrv_errors(),true));
	}
	


?>
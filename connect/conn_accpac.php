<?php
	// error_reporting(0);
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	
	$serverName = "172.23.225.113,1433"; 
	$connectionInfo = array( "Database"=>"FDKDB","uid"=>"sa","pwd"=>"accpac","TraceOn"=>"0");

	// $serverName = "172.23.225.113,1433"; 
	// $connectionInfo = array( "Database"=>"FDKSYS20","uid"=>"sa","pwd"=>"accpac","TraceOn"=>"0");

	$connect = sqlsrv_connect( $serverName, $connectionInfo);	

	if($connect) {
		$varConn = "Y";
	}else{
		$varConn = "N";
		die(print_r(sqlsrv_errors(),true));
	};
?>
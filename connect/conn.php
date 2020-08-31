<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	// $serverName = "172.23.225.113,1433";
	// $connectionInfo = array( "Database"=>"FDKSYS20","uid"=>"sa","pwd"=>"accpac","TraceOn"=>"0");

	// $serverName = "localhost,1433";
	// $connectionInfo = array( "Database"=>"FDKSYS20","uid"=>"sa","pwd"=>"P@ssw0rd","TraceOn"=>"0");

	$serverName = "localhost,1433";
	$connectionInfo = array( "Database"=>"FDKSYS20","uid"=>"sa","pwd"=>"P@ssw0rd","TraceOn"=>"0");
	$connect = sqlsrv_connect( $serverName, $connectionInfo);


	// $connectionInfo = array("UID" => "BUMIKAYA", "pwd" => "AdminFDKI4212", "Database" => "FDKSYS20", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    // $serverName = "tcp:fdksys12.database.windows.net,1433";
    // $connect = sqlsrv_connect($serverName, $connectionInfo);

	if($connect) {
		$varConn = "Y";
	}else{
		$varConn = "N";
		die(print_r(sqlsrv_errors(),true));
	};
?>


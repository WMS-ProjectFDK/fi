<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');

	$serverName = "localhost";
	$uid = "sa";
	$pwd = "P@ssw0rd";
	$connect = new PDO( "sqlsrv:server=$serverName ; Database = FDKSYS20", "$uid", "$pwd");
	
	if($connect) {
		$varConn = "Y";
	}else{
		$varConn = "N";
	}
?>
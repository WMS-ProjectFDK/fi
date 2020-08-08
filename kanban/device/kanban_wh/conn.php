<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	
	$db = "(DESCRIPTION =(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.23.206.21)(PORT = 1521)))(CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = sysfi01.world)))";
  	$connect = oci_connect("porder", "porder", $db);

	if(!$connect){
		$varConn = "N";
	}else{
		$varConn = "Y";
		$s = "ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MON-RR'";
		$d = oci_parse($connect, $s);
		oci_execute($d);

		$s1 = "ALTER SESSION SET NLS_DATE_LANGUAGE = 'AMERICAN'";
		$d1 = oci_parse($connect, $s1);
		oci_execute($d1);
	
		$s2 = "alter session set NLS_NUMERIC_CHARACTERS = '.,'";
		$d2 = oci_parse($connect, $s2);
		oci_execute($d2);
	}
?>
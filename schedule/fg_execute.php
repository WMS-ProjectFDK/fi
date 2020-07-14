<?php

	
	//ini_set('memory_limit', '-1');
	set_time_limit(0); 

	include("../connect/conn.php");
	$sql = "BEGIN ZSP_FG_CREATE_FIFO(); END;";

	$stmt = oci_parse($connect, $sql);
	
	$res = oci_execute($stmt);


?>
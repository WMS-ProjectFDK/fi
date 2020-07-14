<?php
	set_time_limit(0); 
	include("../connect/conn.php");
	$sql = "BEGIN zsp_semi_bat_v2(); END;";
	$stmt = oci_parse($connect, $sql);
	$res = oci_execute($stmt);
	$pesan = oci_error($stmt);
	$msg = $pesan['message'];
	if($msg == ''){
		header("Location: http://localhost/wms/forms/stock_semi_battery_v2.php");
		die();
	}
	else{
		$msg .= " Error pada proses refresh data dengan Query $ins";
		
	}

	// fopen('stock_semi_battery.php',"r");
	echo "System Will redirect,please wait";
	
die();

?>


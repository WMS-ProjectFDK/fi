<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");




	$ins = "select '123' test,sysdate1,'123' from dual";
	$data_ins = oci_parse($connect, $ins);
	oci_execute($data_ins);	
	
	$pesan = oci_error($data_ins);
	$msg = $pesan['sqltext'];
	if($msg == ''){
		echo("sukses");
	}
	else{
		echo $msg;
		break;
	}

?>
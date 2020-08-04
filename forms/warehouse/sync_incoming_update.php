<?php
	include("../../connect/conn.php");
	$id =  isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$rack = isset($_REQUEST['rack']) ? strval($_REQUEST['rack']) : '';
	$user = isset($_REQUEST['userid']) ? strval($_REQUEST['userid']) : '';

	$dt = date('Y-m-d H:i:s');

	$cek ="select rack from ztb_wh_in_det where id=$id ";
	$cekNya = sqlsrv_query($connect, strtoupper($cek));
	
	$row = sqlsrv_fetch_array($cekNya);

	if(!is_null($row[0]) ||$row[0]!=''){
		echo "<p style='color:red;font-size:25px;'><b>GAGAL,Rack terpakai</b></p>";
	}else{
		$qry = "update ztb_wh_in_det set rack='$rack', daterecord='$dt', userid='$user' where id=$id ";
		$result = sqlsrv_query($connect, $qry);
	  
	
		if($result){
  			echo "<p style='color:blue;font-size:30px;'><b>SIMPAN BERHASIL</b></p>";	
	  	}  	
	}	
?>
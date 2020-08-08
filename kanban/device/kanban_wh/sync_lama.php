<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");

include("conn.php");
$device = "172.23.225.209";

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpexcel/');
include 'PHPExcel/IOFactory.php';

// define some variables
$local_file = 'C:\Users\Public\Documents\kanban_wh.csv';
$server_file = 'Program/Transfer.csv';

// set up basic connection
$conn_id = ftp_connect($device);

// login with username and password
$login_result = ftp_login($conn_id , 'keyence', 'keyence');

if($login_result){
	if(ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		$file = 'Program/Transfer.csv';
		$inputFileName = "../../../../Users/Public/Documents/kanban_wh.csv";

		try {
		    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
		    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		$nom = 0;
		$result = array();
		$items = array();

		for($i=1;$i<=$arrayCount;$i++){
			$id = trim($allDataInSheet[$i]["A"]);
			$item = trim($allDataInSheet[$i]["B"]);
			$qty = trim($allDataInSheet[$i]["C"]);
			$plt = trim($allDataInSheet[$i]["E"]);
			$wo = trim($allDataInSheet[$i]["F"]);
			
			$sql ="insert into ztb_wh_kanban_trans (id,item_no,qty,flag,wo_no,plt_no) VALUES ($id,'$item',$qty,0,'$wo','$plt')";
			$sqlNya = oci_parse($connect, $sql);
			oci_execute($sqlNya);			
		}

		/*INSERT LOG*/
		$qry = "insert into ztb_wh_sync_log VALUES ('kanban_user',TO_DATE('".date('Y-m-d H:i:s')."','yyyy/mm/dd hh24:mi:ss'),'syncronize barcode ip: ".$device."')";
		$sql_ins = oci_parse($connect, $qry);
		oci_execute($sql_ins);

		if(file_exists($inputFileName)) {
			$file_baru = '../../../../Users/Public/Documents/kanban_wh_'.date("YmdHis").'.csv';
			rename($inputFileName,$file_baru);

			ftp_delete($conn_id, $file);
		}else{
			echo "DATA TIDAK ADA DI SERVER..!!";
		}
		//include ("save_proses.php");
		// close the connection
		ftp_close($conn_id);
		echo "SINKRON SUKSES..!!<br/>";
	}else{
		echo "DATA DI ALAT SUDAH DI SINKRON..!!<br/>";
	}
}else{
	echo "KONEKSI TERPUTUS..!!<br/>";
}
include ("save_proses.php");
/*$cek_1 = "select count(*) as JUM1 from ztb_m_plan where wo_no='$wo' and plt_no='$plt' and upload=1";
$data_cek1 = oci_parse($connect, $cek_1);
oci_execute($data_cek1);
$dt_cek1 = oci_fetch_object($data_cek1);
$jum1 = intval($dt_cek1->JUM1);

if($jum1 == 0){
	$cek = "select count(*) as jum from ztb_wh_kanban_trans where wo_no='$wo' and plt_no='$plt' and item_no='$item'";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);
	$jum = intval($dt_cek->JUM);

	if($jum == 0){
		$sql ="insert into ztb_wh_kanban_trans (id,item_no,qty,flag,wo_no,plt_no) VALUES ($id,'$item',$qty,0,'$wo','$plt')";
		$sqlNya = oci_parse($connect, $sql);
		oci_execute($sqlNya);
	}
}*/
?>
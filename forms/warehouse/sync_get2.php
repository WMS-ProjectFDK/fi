<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
$user = $_SESSION['id_wms'];

include("../../connect/conn.php");

$device = isset($_REQUEST['device']) ? strval($_REQUEST['device']) : '';


set_include_path(get_include_path() . PATH_SEPARATOR . 'phpexcel/');
include '../../class/PHPExcel/PHPExcel/IOFactory.php';


/* COPY FILE DARI 172.23.206.90/Program/Transfer.csv KE WAREHOUSE/FORMS/Transfer.csv */
/* DELETE FILE DI 172.23.206.90/Program/Transfer.csv*/

// define some variables
$local_file = 'C:\Users\Public\Documents\Trans.csv';
$server_file = 'Program/Transfer.csv';

// set up basic connection
$conn_id = ftp_connect($device);
// login with username and password
$login_result = ftp_login($conn_id , 'keyence', 'keyence');
// try to download $server_file and save to $local_file

if(ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
	$file = 'Program/Transfer.csv';
	// This is the file path to be uploaded.

	$inputFileName = "C:\\Users\Public\Documents\Trans.csv";
	try {
	    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	} catch(Exception $e) {
	    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	 
	$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

	$user_name = $_SESSION['id_wms'];
	$nom = 0;
	$result = array();
	$items = array();

	for($i=1;$i<=$arrayCount;$i++){
		$tanggal = trim($allDataInSheet[$i]["A"]);
		$type = trim($allDataInSheet[$i]["B"]);
		$id = trim($allDataInSheet[$i]["C"]);
		$document = trim($allDataInSheet[$i]["D"]);
		$line = trim($allDataInSheet[$i]["E"]);
		$item = trim($allDataInSheet[$i]["F"]);
		$rack =  trim($allDataInSheet[$i]["G"]);
		$pallet = trim($allDataInSheet[$i]["H"]);
		$qty =  trim($allDataInSheet[$i]["I"]);

		$sql ="insert into ztb_wh_trans (tanggal,type,id_no,document,line,item,rack,pallet,qty,flag) VALUES ('$tanggal','$type','$id','$document','$line','$item','$rack','$pallet','$qty',0)";
		$sqlNya = sqlsrv_query($connect, $sql);
		if( $sqlNya === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $sql;
				}
			}
		}
		
	}

	/*INSERT LOG*/
	$qry = "insert into ztb_wh_sync_log VALUES ('$user','".date('Y-m-d H:i:s')."','syncronize barcode ip: ".$device."')";
	$sql_ins = sqlsrv_query($connect, $qry);
	
	
	if(file_exists($inputFileName)) {
		$file_baru = 'C:\\Users\Public\Documents\Trans_'.date("Ymdhis").'.csv';
		rename($inputFileName,$file_baru);

		ftp_delete($conn_id, $file);	
	}else{
		echo "Data tidak ada di server..!!";	
	}

	// close the connection
	ftp_close($conn_id);
	echo "SYNCRONIZED..";
}else{
	echo "DATA DI BARCODE SUDAH DI SYNCRONIZED";
}
?>
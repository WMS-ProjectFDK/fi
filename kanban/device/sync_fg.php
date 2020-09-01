<?php
// error_reporting(0);
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

include("conn.php");
$device = "172.23.225.214";

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpexcel/');
include '../../class/PHPExcel/PHPExcel/IOFactory.php';

// define some variables
$local_file = 'C:\Users\Public\Documents\kanban_fg.csv';
$server_file = 'Program/kuraire.csv';

// set up basic connection
$conn_id = ftp_connect($device);

// login with username and password
$login_result = ftp_login($conn_id , 'keyence', 'keyence');

if($login_result){
	if(ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		$file = 'Program/kuraire.csv';
		$inputFileName = "C:\Users\Public\Documents\kanban_fg.csv";

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

		$hr = date('Y-m-d');

		for($i=1;$i<=$arrayCount;$i++){
			$id = trim($allDataInSheet[$i]["A"]);
			
			$sql ="insert into ztb_wh_kanban_trans_fg (slip_no,date_in,flag) select $id,$hr,0 from dual
				where not exists (select * from ztb_wh_kanban_trans_fg where slip_no = '$id')";
			$sqlNya = sqlsrv_query($connect, $sql);
		}

		/*INSERT LOG*/
		$qry = "insert into ztb_wh_sync_log VALUES ('KANBAN-FG',SYSDATETIME(),'syncronize barcode ip: ".$device."')";
		$sql_ins = sqlsrv_query($connect, $qry);

		if(file_exists($inputFileName)) {
			$file_baru = 'C:\Users\Public\Documents\kanban_wh_fg'.date("YmdHis").'.csv';
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
?>
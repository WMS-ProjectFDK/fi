<?php
error_reporting(0);
set_time_limit(0);
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
		$response = array();

		if(intval(date('H')) < 7){
			$hr = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		}else{
			$hr = date('Y-m-d');
		}

		$ln = '';
		for($i=1;$i<=$arrayCount;$i++){
			// $id0 = trim($allDataInSheet[$i]["A"]);
			// $key = explode(';', $id0);

			// $id=$key[0];
			// $kode = "MT-PCK-".$key[0];//$id;		//25/10/2018
			// $item = trim($key[1]);//$allDataInSheet[$i]["B"]);
			// $qty = trim($key[2]);//$allDataInSheet[$i]["C"]);
			// $dt = trim($key[3]);//$allDataInSheet[$i]["D"]);	//	5/23/2017 12:53
			// $plt = trim($key[4]);//$allDataInSheet[$i]["E"]);
			// $wo = trim($key[5]);//$allDataInSheet[$i]["F"]);
			//$id_new = trim($id);

			$id = trim($allDataInSheet[$i]["A"]);

			// $id=$key[0];
			$kode = "MT-PCK-".$id;		//25/10/2018
			$item = trim($allDataInSheet[$i]["B"]);
			$qty = trim($allDataInSheet[$i]["C"]);
			$dt = trim($allDataInSheet[$i]["D"]);	//	5/23/2017 12:53
			$plt = trim($allDataInSheet[$i]["E"]);
			$wo = trim($allDataInSheet[$i]["F"]);

			if($ln != trim($id)){
				$line=1;
			}
			
			$sql ="insert into ztb_wh_kanban_trans (id,item_no,qty,flag,wo_no,plt_no,line_no,date_in,slip_no)
				select top 1 $id, $item, $qty, 0, '$wo', $plt, $line, '".$hr."','$kode' from item
				where not exists (select * from ztb_wh_kanban_trans where id=$id and item_no='$item')";
			$sqlNya = sqlsrv_query($connect, $sql);
			if($sqlNya === false ) {
				if(($errors = sqlsrv_errors() ) != null) {  
					foreach( $errors as $error){  
						$msg .= "message: ".$error[ 'message']."<br/>";  
					}  
				}
			}

			// UPDATE UPLOAD ZTB_M_PLAN
			$upd2 = "update ztb_m_plan set upload=1 where wo_no='".$wo."' and plt_no=".$plt."";
			$data_upd2 = sqlsrv_query($connect, $upd2);
			if($data_upd2 === false ) {
				if(($errors = sqlsrv_errors() ) != null) {  
					foreach( $errors as $error){  
						$msg .= "message: ".$error[ 'message']."<br/>";  
					}  
				}
			}

			$response[] = array('id'=>$id,
								'kode'=>$kode,
								'item'=>$item,
								'qty'=>$qty,
								'dt'=>$dt,
								'plt'=>$plt,
								'wo'=>$wo,
								'sql'=>$sql
							);

			$ln = trim($id);
			$line++;
		}

		/*INSERT LOG*/
		$qry = "insert into ztb_wh_sync_log VALUES ('kanban_user',getdate(),'syncronize barcode ip: ".$device."')";
		$sql_ins = sqlsrv_query($connect, $qry);

		if(file_exists($inputFileName)) {
			$file_baru = '../../../../Users/Public/Documents/kanban_wh_'.date("YmdHis").'.csv';

			$fp = fopen('../../../../Users/Public/Documents/kanban_wh_'.date("YmdHis").'.json', 'w');
			fwrite($fp, json_encode($response));
			fclose($fp);

			rename($inputFileName,$file_baru);
			ftp_delete($conn_id, $file);
		}else{
			echo "DATA TIDAK ADA DI SERVER..!!";
		}
		//include ("save_proses.php");
		// close the connection
		ftp_close($conn_id);
		echo "SINKRON SUKSES..!!<br/>";
		// echo json_encode($response);
	}else{
		echo "DATA DI ALAT SUDAH DI SINKRON..!!<br/>";
	}
}else{
	echo "KONEKSI TERPUTUS..!!<br/>";
}
//include ("save_proses.php");
?>
<?php
session_start();
include("../connect/koneksi.php");

set_include_path(get_include_path() . PATH_SEPARATOR . '../class/phpexcel/');
include 'PHPExcel/IOFactory.php';
 
// This is the file path to be uploaded.
$file = $_FILES['file1']['name'];
$inputFileName = $file;
// $inputFileName = 'forcast_template.xlsx';

 
try {
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}
 
$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

$userentry = $_SESSION['sita_user_name'];
$success = 0;
$failed = 0;
for($i=2;$i<=$arrayCount;$i++){
$kode_customer = trim($allDataInSheet[$i]["A"]);
$brg_codebarang = trim($allDataInSheet[$i]["B"]);
$fc_bulan1 = trim($allDataInSheet[$i]["C"]);
$fc_bulan2 = trim($allDataInSheet[$i]["D"]);
$fc_bulan3 = trim($allDataInSheet[$i]["E"]);
$fc_bulan4 = trim($allDataInSheet[$i]["F"]);
$fc_bulan5 = trim($allDataInSheet[$i]["G"]);
$fc_bulan6 = trim($allDataInSheet[$i]["H"]);
$fc_keterangan = trim($allDataInSheet[$i]["I"]);

$sql_id = "select to_char(now(), 'YYYYMM') || coalesce(lpad(cast(cast(substr(max(fc_trans_code), 7, 4) as integer)+1 as varchar(4)), 4, '0'),  '0001') as code 
			from fc_customer where extract(year from fc_datecreate) = extract(year from now())
			and extract(month from fc_datecreate) = extract(month from now())";
$query_id = pg_query($koneksi, $sql_id);
$row_id = pg_fetch_array($query_id);
$fc_trans_code = $row_id[0];


$sql =  "insert into fc_customer(fc_trans_code, fc_datecreate, kode_customer, brg_codebarang, 
		fc_bulan1, fc_bulan2, fc_bulan3, fc_bulan4, fc_bulan5, fc_bulan6, fc_keterangan, fc_user_create, fc_last_update) 
		values('$fc_trans_code', '".date('Y-m-d')."', '$kode_customer', '$brg_codebarang', 
		'$fc_bulan1', '$fc_bulan2', '$fc_bulan3', '$fc_bulan4', '$fc_bulan5', '$fc_bulan6', '$fc_keterangan', '$userentry', '".date('Y-m-d')."')";

$result = @pg_query($sql);
if ($result){
	$success++;
} else {
	$failed++;
}
}

echo json_encode("Success = ".$success.", Failed = ".$failed);
?>
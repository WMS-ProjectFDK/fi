<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn_kanbansys.php");

// Create By : Reza Vebrian
// Date : 26-April-2017
// Description : Penambahan Field "Used" di revisi untuk permudah Pencarian
// ID = 1

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$bln_H = trim($data->val(3,3));
$thn_H = trim($data->val(3,4));

$user = $_SESSION['id_wms'];
$success = 0;
$failed = 0;

// Reza start ID 1
	$IU  = "update ztb_assy_plan set USED = 0 where bulan = '$bln_H' AND tahun = '$thn_H' ";
	$data_IU = odbc_exec($connect, $IU);
//Reza End ID 1

$cek = "select coalesce(cast(cast(max(revisi) as integer)+1 as char(2)),0) as max from ztb_assy_plan where tahun='$thn_H' and bulan='$bln_H' ";
$data_cek = odbc_exec($connect, $cek);
$row = odbc_fetch_object($data_cek);

$rev = intval($row->max);
$now=date('Y-m-d H:i:s');

#cek jumlah hari dalam bulan - UENG ID=2, date: 27-02-2018
$jum_hari = cal_days_in_month(CAL_GREGORIAN, $bln_H, $thn_H);

if($jum_hari == 31){
	$hariNya = 35;
}elseif($jum_hari == 30){
	$hariNya = 34;
}else{
	$hariNya = 32;
}

for($i=3;$i<=$hasildata;$i++){
	$line = trim($data->val($i,1));
	$type = trim($data->val($i,2));
	$bln = trim($data->val($i,3));
	$thn = trim($data->val($i,4));
	$tgl=1;

	for ($j=5; $j<=$hariNya; $j++) {
		$qty = intval(trim($data->val($i,$j)))*1000;
		if($qty!=0){
			#kode otomatis - UENG ID=2, date: 21-08-2017
			$kode = "select 'PLAN-' +  convert(char(4), getdate(), 12) + '-' +
				coalesce(right(replicate('0',4)+CAST(CAST(SUBSTRING(max(id_plan),11,4) as int)+1 as varchar(4)),4),'0001') as id_plan
				from ZTB_ASSY_PLAN
				where convert(char(4), getdate(), 12) = SUBSTRING(id_plan,6,4)";
			$data_kd = odbc_exec($connect, $kode);
			$dt_kode = odbc_fetch_object($data_kd);
			$code = $dt_kode->ID_PLAN;

			#-------------------------------------------
			$field  = "cell_type,"  ; 	$value  = "'$type',"	;
			$field .= "assy_line,"  ; 	$value .= "'$line',"	;
			$field .= "tanggal,"    ; 	$value .= "'$tgl'," 	;
			$field .= "bulan,"      ; 	$value .= "'$bln'," 	;
			$field .= "tahun,"      ; 	$value .= "'$thn'," 	;
			$field .= "qty,"        ; 	$value .= "$qty,"		;
			//REZA START ID = 1
			$field .= "revisi,"     ; 	$value .= "$rev,"		;
			$field .= "USED,"       ; 	$value .= "1,"			;
			//REZA END ID = 1
			$field .= "UPLOAD_TIME,"; 	$value .= "'$now',"		;
			$field .= "ID_PLAN"     ; 	$value .= "'$code'"		;
			chop($field);				chop($value);

			$ins  = "insert into ztb_assy_plan ($field) VALUES ($value)" ;
			$data_ins = odbc_exec($connect, $ins);

			if($data_ins){
				$success++;	
			}else{
				$failed++;
			}
		}
		$tgl++;
	}
}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>
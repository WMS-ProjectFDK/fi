<?php
//error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../../class/excel_reader.php";
include("../../../connect/conn.php");

// Create By : Ueng hernama
// Date : 29-Sept-2017
// ID = 2

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$bln_H = trim($data->val(2,3));
$thn_H = trim($data->val(2,2));
$per = $thn_H.$bln_H;

$IU  = "delete from ztb_wh_daily_needs where period = '$per' ";
$data_IU = sqlsrv_query($connect, $IU);


$user = $_SESSION['id_wms'];
$success = 0;
$failed = 0;

for($i=2;$i<=$hasildata;$i++){
	$type = trim($data->val($i,1));
	$thn = trim($data->val($i,2));
	$bln = trim($data->val($i,3));
	$qty = trim($data->val($i,4));
	$period = $thn.$bln;

	if($qty!=0){
		#kode otomatis - UENG ID=2, date: 29-09-2017
		$kode = "select 'DN-' || 
			to_char(sysdate,'yymm') || '-' || 
			nvl(lpad(cast(cast(substr(max(id_needs),9) as integer)+1 as varchar(4)), 4,'0'),'0001') as ID_DN
			from ztb_wh_daily_needs where to_char(sysdate,'yymm')=substr(id_needs,4,4)";
		$data_kd = sqlsrv_query($connect, $kode);
		
		$dt_kode = sqlsrv_fetch_object($data_kd);
		$code = $dt_kode->ID_DN;
		#-------------------------------------------

		$field  = "id_needs,"  ; 	$value  = "'$code',"	;
		$field .= "tipe,"  	   ; 	$value .= "'$type',"	;
		$field .= "period,"    ; 	$value .= "'$period'," 	;
		$field .= "qty_needs"  ; 	$value .= "$qty" 		;
		chop($field);				chop($value);

		$ins  = "insert into ztb_wh_daily_needs ($field) 
			select $value from dual where not exists (select * from ztb_wh_daily_needs where tipe='".$type."' and period='".$period."')" ;
		$data_ins = sqlsrv_query($connect, $ins);
	

		if($data_ins){
			$success++;	
		}else{
			$failed++;
		}
	}
		$tgl++;
}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>
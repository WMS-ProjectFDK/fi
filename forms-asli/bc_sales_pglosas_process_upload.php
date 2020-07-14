<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount();

$success = 0;
$failed = 0;
$msg = '';

for ($k=2; $k<=$hasildata; $k++) {
	$do = trim($data->val($k,10));
	$bl_no = trim($data->val($k,2));
	$bl_date = trim($data->val($k,3));
	$emkl = trim($data->val($k,4));
	$forwarder = trim($data->val($k,5));
	$peb = trim(str_replace("'", "''", $data->val($k,6)));
	$pe = trim($data->val($k,7));
	$s = trim($data->val($k,12));

	if ($s == "UPDATE"){
		$upd = "update emkl set peb_no = '$peb', pe_no = '$pe' where do_no = '$do'";
		$dataNya = oci_parse($connect, $upd);
		oci_execute($dataNya);	
		$pesan = oci_error($dataNya);
		$msg = $pesan['message'];
		if($msg != ''){
			$msg .= " Error pada proses update EMKL Item Query $upd";
			break;
		}else{
			$success++;
		}
	}elseif($s == "INSERT"){
		$field  = " do_no,"     ;  $values  = "'$do',"  	;
		$field .= " bl_no,"     ;  $values .= "'$bl_no',"  	;
		$field .= " emkl,"      ;  $values .= "'$emkl',"   	;
		$field .= " peb_no,"    ;  $values .= "'$peb'," 	;
		$field .= " pe_no,"     ;  $values .= "'$pe'," 	;
		$field .= " upto_date," ;  $values .= "sysdate," 	;
		$field .= " reg_date,"  ;  $values .= "sysdate," 	;
		$field .= " bl_date,"   ;  $values .= "to_date('$bl_date','yyyy-mm-dd'),"  ;
		$field .= " forwarder" ;   $values .= "'$forwarder'"  	;
		chop($field)            ;  chop($values)           	; 
		$ins  = "insert into emkl ($field) select $values from dual";
		$dataNya = oci_parse($connect, $ins);
		oci_execute($dataNya);	
		$pesan = oci_error($dataNya);
		$msg = $pesan['message'];
		if($msg != ''){
			$msg .= " Error pada proses insert EMKL Item Query $ins";
			break;
		}else{
			$success++;
		}
	}
}

if($msg == ''){
	echo json_encode("Success = ".$success." data uploaded");
}else{
	echo json_encode("ErrorMsg = ".$msg."");
}
?>
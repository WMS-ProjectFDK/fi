<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount();

$success = 0;
$failed = 0;
$msg = '';

for ($k=2; $k<=$hasildata; $k++) {
	$do = trim($data->val($k,6));
	$peb = trim(str_replace("'", "''", $data->val($k,2)));
	$pe = trim($data->val($k,3));
	$s = trim($data->val($k,8));

	if ($s == "UPDATE"){
		$upd = "update emkl set peb_no = '$peb', pe_no = '$pe' where do_no = '$do'";
        $dataNya = sqlsrv_query($connect, $upd);
        
        if($dataNya === false ){
            $msg .= sqlsrv_errors();
        }else{
            $success++;
        }
	}elseif($s == "INSERT"){
		$field  = " do_no,"     ;  $values  = "'$do',"  		;
		$field .= " peb_no,"    ;  $values .= "'$peb'," 		;
		$field .= " pe_no,"     ;  $values .= "'$pe'," 			;
		$field .= " upto_date," ;  $values .= "getdate()," 		;
		$field .= " reg_date"  ;  $values .= "getdate()" 		;
		chop($field)            ;  chop($values)           		; 
		$ins  = "insert into emkl ($field) values($values)";
        $dataNya = sqlsrv_query($connect, $ins);
        
        if($dataNya === false ){
            $msg .= sqlsrv_errors();
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
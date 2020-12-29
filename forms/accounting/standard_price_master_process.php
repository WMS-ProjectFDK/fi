<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../../class/excel_reader.php";
include("../../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount(0);

$success = 0;
$failed = 0;

$Arr_k = array();
for ($k=2; $k<=$hasildata; $k++) {
	$it = trim($data->val($k,1,0));
	$sp = trim($data->val($k,2,0));

	if ($it != ''){
		$str = "update item set standard_price= ".$sp." where item_no = ".$it;
		$data_str = sqlsrv_query($connect, $str);

		if($data_str === false ) {
			if(($errors = sqlsrv_errors()) != null) {  
		        foreach( $errors as $error){  
		            $msg .= $error[ 'message']."<br/>";  
		        }
		    }
		}

		if($msg != ''){
			$msg .= " Error pada proses update Master Item Query $str";
			$failed++;
			// break;
		}else{
			$success++;
		}
	}
}

if($msg == ''){
	echo json_encode("Success = ".$success."");
}else{
	echo json_encode("".$msg."");
}
?>
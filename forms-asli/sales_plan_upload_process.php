<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
//session_start();
include "../class/excel_reader.php";
include("../connect/conn.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$user = htmlspecialchars($_REQUEST['user_name']);
$success = 0;
$failed = 0;

oci_execute($data_ins);

	for($i=4;$i<=$hasildata;$i++){
		$customer = trim($data->val($i,1));
		$item_no = trim($data->val($i,2));
		$class = trim($data->val($i,4));
		$price1 = trim($data->val($i,6));
		$price2 = trim($data->val($i,13));

		$price1 = trim(str_replace('*', '', $price1)) ;
		$price2 = trim(str_replace('*', '', $price2)) ;

		$customerID = '';
		if ($customer == 'FDK CORP'){
			$customerID = '996130';
		}
		if ($customer == 'MAXELL ASIA'){
			$customerID = '300029';
		}

		if ($customer == 'MAXELL USA'){
			$customerID = '300007';
		}

		if ($customer == 'OSIMO'){
			$customerID = '300008';
		}

		$ins = "delete from sales_plan where item_no = '$item_no' and customer_code = '$customerID' ";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);

		for($i1=3;$i1<=8;$i1++){
			
			$tgl = trim($data->val(3,$i1));
			$tgl .= "01";
			$qty = trim($data->val($i,$i1));

			if ($qty != '' OR $qty != 0){
				$qty = trim(str_replace(',', '', $qty)) ;
				
				$ins = "insert into sales_plan 
				(person_code,customer_code,item_no,data_Date,qty,UNIT_CODE,curr_code,ex_rate,sp,operation_date,origin_code,cost,cost_curr_code) 
				select '$user','$customerID','$item_no',to_date('$tgl','yyyy-mm-dd'),$qty,10,1,1,U_PRICE,sysdate,118,0.000001,1 from sp_ref
				where item_no =  '$item_no' and customer_code = '$customerID'";

				$data_ins = oci_parse($connect, $ins);
				oci_execute($data_ins);
				$pesan = oci_error($data_ins);
				$msg = $pesan['message'];

				if($msg == ''){
					$success++;
				}else{
					$msg .= " Error pada proses upload data $ins";
					$failed++;
					break;
				}

				if($data_ins){
					$success++;	
				}else{
					$failed++;
				}
			};
		}
	}

if($msg == ''){
	echo json_encode("Success = ".$success.", Failed = ".$failed);
}else{
	echo json_encode("".$msg."");
}
?>
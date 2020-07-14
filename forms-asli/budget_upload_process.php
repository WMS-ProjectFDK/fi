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
$tgldelete = trim($data->val(3,7));
$tgldelete .= "01";
$ins = "delete from budget where sales_Date >= to_date('$tgldelete','yyyy-mm-dd') ";
$data_ins = oci_parse($connect, $ins);
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
		if ($customer == 'MAXELL'){
			$customerID = '300029';
		}
		if ($customer == 'OSIMO'){
			$customerID = '300008';
		}


		for($i1=7;$i1<=12;$i1++){
			
			$tgl = trim($data->val(3,$i1));
			$tgl .= "01";
			// $ins = "delete from budget where customer_code = '$customerID' and sales_Date = to_date('$tgl','yyyy-mm-dd') and item_no = '$item_no'";
			// $data_ins = oci_parse($connect, $ins);
			// oci_execute($data_ins);

			$qty = trim($data->val($i,$i1));
			if ($qty != ''){
				
				$qty = trim(str_replace(',', '', $qty)) ;
				
				$ins = "insert into budget (person_code,customer_code,item_no,sales_Date,qty,uom_q,curr_code,ex_rate,sp,operation_date,origin_code,cost,cost_curr_code) 
						select '$user','$customerID','$item_no',to_date('$tgl','yyyy-mm-dd'),$qty,10,1,1,$price1,sysdate,118,0.000001,1 from dual ";
				$data_ins = oci_parse($connect, $ins);
				oci_execute($data_ins);

				if($data_ins){
					$success++;	
				}else{
					$failed++;
				}

			};
			
		}

		for($i2=14;$i2<=19;$i2++){
			$tgl = trim($data->val(3,$i2));
			$tgl .= "01";
			// $ins = "delete from budget where customer_code = '$customerID' and sales_Date = to_date('$tgl','yyyy-mm-dd')";
			// $data_ins = oci_parse($connect, $ins);
			// oci_execute($data_ins);



			$qty = trim($data->val($i,$i2));
			if ($qty != ''){
				
				$qty = trim(str_replace(',', '', $qty)) ;
				
				$ins = "insert into budget (person_code,customer_code,item_no,sales_Date,qty,uom_q,curr_code,ex_rate,sp,operation_date,origin_code,cost,cost_curr_code) 
						select '$user','$customerID','$item_no',to_date('$tgl','yyyy-mm-dd'),$qty,10,1,1,$price2,sysdate,118,0.000001,1 from item where item_no = ".$item_no."";
				$data_ins = oci_parse($connect, $ins);
				oci_execute($data_ins);

				if($data_ins){
					$success++;	
				}else{
					$failed++;
				}

			};
			if($data_ins){
				$success++;	
			}else{
				$failed++;
			}
		}


	}
echo json_encode("Success = ".$success.", Failed = ".$failed);
?>
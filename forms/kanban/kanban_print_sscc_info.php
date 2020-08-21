<?php
    error_reporting(0);
	ini_set('max_execution_time', -1);
	session_start();
	$work_order = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$jumPlt = isset($_REQUEST['jumPlt']) ? strval($_REQUEST['jumPlt']) : '';
	$items = array();
	$result = array();
	$rowno=0;

	include("../../connect/conn.php");

	for ($i=1; $i <=$jumPlt ; $i++) { 
		array_push($items, $row);

		$qry = "select isnull(cast(upto_date as varchar(10)),'BELUM PRINT') as upto_date, isnull(user_id,'-') as USERS
			from ztb_sscc_print_history
			where wo_no='$work_order' and plt_no=$i 
			order by upto_date desc";
		$data = sqlsrv_query($connect, strtoupper($qry));
		$row = sqlsrv_fetch_object($data);

		if($row->UPTO_DATE == '' OR is_null($row->UPTO_DATE)){
			$sts = 'BELUM DI PRINT';
			$reprint = '';
		}else{
			$sts = 'SUDAH DI PRINT';
			$w = "'".$work_order."'";
			$reprint = '<a href="javascript:void(0)" onclick="print_ulang_sscc_percarton('.$w.','.$i.')">
							Print Ulang
						</a>';
		}

		$items[$i-1] = array('WO_NO' => $work_order,
							 'PLT_NO' => $i,
							 'STATUS' => $sts,
							 'PRINT_DATE' => $row->UPTO_DATE,
							 'USER' => $row->USERS,
							 'PRINT_ULANG' => $reprint
					   );
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
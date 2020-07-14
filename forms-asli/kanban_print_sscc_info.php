<?php
	ini_set('max_execution_time', -1);
	session_start();
	$work_order = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$jumPlt = isset($_REQUEST['jumPlt']) ? strval($_REQUEST['jumPlt']) : '';
	$items = array();
	$rowno=0;

	include("../connect/conn.php");

	for ($i=1; $i <=$jumPlt ; $i++) { 
		array_push($items, $row);

		$qry = "select nvl(upto_date,'BELUM PRINT') as upto_date, nvl(user_id,'-') as user_id
			from ztb_sscc_print_history
			where wo_no='$work_order' and plt_no=$i and rownum = 1
			order by to_date(upto_date,'YYYY-MM-DD HH24:MI:SS') desc";
		$data = oci_parse($connect, $qry);
		oci_execute($data);
		$row = oci_fetch_object($data);

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
							 'USER' => $row->USER_ID,
							 'PRINT_ULANG' => $reprint
					   );
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
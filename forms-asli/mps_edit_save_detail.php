<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");
$user_name = $_SESSION['id_wms'];

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];
	$msg = '';

	foreach($queries as $query){
		$po_no = $query->po_no;
		$po_line_no = $query->po_line_no;
		$mps_date = $query->mps_date;
		$old_mps_date = $query->old_mps_date;
		$mps_qty = $query->mps_qty;
		$edit_type = $query->edit_type;
		$newDate = date("d-M-Y", strtotime($mps_date));
		$oldDate = date("d-M-Y", strtotime($old_mps_date));

		if($edit_type == 0){
			$cek = "update mps_details set mps_qty = '$mps_qty',mps_date = '$newDate' where mps_date = '$oldDate' and po_no = '$po_no' and po_line_no = '$po_line_no' " ;
			$data = oci_parse($connect, $cek);
			$res = oci_execute($data);
			$pesan = oci_error($data);
			$msg = $pesan['message'];
			if ($msg != '') {
				break;
			};

			$cek = "update mps_details set mps_qty = $mps_qty,mps_date = $newDate where mps_date = $oldDate and po_no = $po_no and po_line_no = $po_line_no " ;
			$cek = "insert into ztb_log_mps (query,query_date,user_login) values ('$cek',(select TO_CHAR(SYSDATE, 'DD-MM-YYYY HH24:MI:SS') from dual),'$user_name')" ;
			$data = oci_parse($connect, $cek);
			$res = oci_execute($data);
			$pesan = oci_error($data);
			$msg = $pesan['message'];
			if ($msg != '') {
				break;
			};
		}else{
			$cek = "insert into mps_details (po_no,po_line_no,mps_date,mps_qty,upload_date) values ('$po_no' ,'$po_line_no','$newDate','$mps_qty',(select sysdate from dual))  " ;
			$data = oci_parse($connect, $cek);
			$res = oci_execute($data);
			$pesan = oci_error($data);
			$msg = $pesan['message'];
			if ($msg != '') {
				break;
			};

			$cek = "insert into mps_details (po_no,po_line_no,mps_date,mps_qty,upload_date) values ($po_no ,$po_line_no,$newDate,$mps_qty,(select sysdate from dual))  " ;
			$cek = "insert into ztb_log_mps (query,query_date,user_login) values ('$cek',(select TO_CHAR(SYSDATE, 'DD-MM-YYYY HH24:MI:SS') from dual),'$user_name')" ;
			$data = oci_parse($connect, $cek);
			$res = oci_execute($data);
			$pesan = oci_error($data);
			$msg = $pesan['message'];
			if ($msg != '') {
				break;
			};
		};
		
	}	
}else{
	$msg .= 'Session Expired';
}


if($msg != ''){
	echo json_encode($cek);
}else{
	echo json_encode('success');
}





// $data_cek = oci_parse($connect, $cek);
// oci_execute($data_cek);

// echo $cek;

// $result["rows"] = $items;
// echo json_encode($result);
?>
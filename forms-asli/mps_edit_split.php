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
	$flag = 0;

	foreach($queries as $query){
		

		$percent= $query->percent;
		$new_wo= $query->new_wo;
		$new_qty= $query->new_qty;
		$old_wo= $query->old_wo;
		$po_line_no= $query->po_line_no;
		$old_po =  $query->old_po;
		$old_po_line_no =  $query->old_po_line_no;

		$sql = "select count(*) as jum from ztb_kanban_print where wo_no='$old_wo'";
		$data = oci_parse($connect, $sql);
		oci_execute($data);
		$row = oci_fetch_object($data);
		
		if (intval($row->JUM) != 0 ){
			$msg = ("This work order $old_wo already printed !!, please check kuraire and material DO first.");
			$flag++;
			break;
		}
		

		$sql0 = "insert into mps_header (ITEM_no,
										ITEM_NAME,
										BATERY_TYPE,
										cell_grade,
										po_no,
										po_line_no,
										WORK_ORDER,
										CONSIGNEE,
										PACKAGING_TYPE,
										DATE_CODE,
										CR_DATE,
										REQUESTED_ETD,
										STATUS,
										LABEL_ITEM_NUMBER,
										LABEL_NAME,
										QTY,
										MAN_POWER,
										OPERATEION_TIME,
										LABEL_TYPE,
										CAPACITY,
										UPLOAD_DATE,
										BOM_LEVEL,
										BOM_EDIT_STAT) 
				select 				ITEM_no,
									ITEM_NAME,
									BATERY_TYPE,
									cell_grade,
									po_no,
									po_line_no || '$po_line_no',
									'$new_wo',
									CONSIGNEE,
									PACKAGING_TYPE,
									DATE_CODE,
									CR_DATE,
									REQUESTED_ETD,
									STATUS,
									LABEL_ITEM_NUMBER,
									LABEL_NAME,
									'$new_qty',
									MAN_POWER,
									OPERATEION_TIME,
									LABEL_TYPE,
									CAPACITY,
									UPLOAD_DATE,
									BOM_LEVEL,
									BOM_EDIT_STAT
									from mps_header where po_no = '$old_po' and po_line_no = '$old_po_line_no'";
		$data = oci_parse($connect, $sql0);
		$res = oci_execute($data);
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};		
		

		$sql1 = "	insert into mps_details (po_no,po_line_no,mps_date,mps_qty,upload_date)
					select s.po_no, 
						s.po_line_no || '$po_line_no',
						s.mps_date, 
						s.mps_qty * ($percent / 100), 
						s.upload_date 
					from mps_details s
					where s.po_no = '$old_po' and s.po_line_no = '$old_po_line_no' ";
		$data = oci_parse($connect, $sql1);
		$res = oci_execute($data);
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};
		
		
	};	

	if($flag==0){
		$sql = " delete from mps_header where s.po_no = '$old_po' and s.po_line_no = '$old_po_line_no'" 
		$data = oci_parse($connect, $sql);
		$res = oci_execute($data);
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};

		$sql = " delete from mps_details where s.po_no = '$old_po' and s.po_line_no = '$old_po_line_no'"
		$data = oci_parse($connect, $sql);
		$res = oci_execute($data);
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		}; 
	}
	


}else{
	$msg .= 'Session Expired';
}


if($msg != ''){
	echo $msg;
}else{
	echo  json_encode('success');
}





// $data_cek = oci_parse($connect, $cek);
// oci_execute($data_cek);

// echo $cek;

// $result["rows"] = $items;
// echo json_encode($result);
?>
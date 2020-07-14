<?php
header('Content-Type: text/plain; charset="UTF-8"');
session_start();
error_reporting(0);
set_time_limit(0);
ini_set('memory_limit', '-1');
include("../connect/conn.php");

$user_name = $_SESSION['id_wms'];

$sql = "delete from ztb_plan_L where user_id = '$user_name'";
$data = oci_parse($connect, $sql);
$res = oci_execute($data);

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];
	$msg = '';

	foreach($queries as $query){
		$wo_no = $query->wo_no;
		$item_no = $query->item_no;
		$brand = $query->brand;
		$date_code= $query->date_code;
		$item_type= $query->item_type;
		$grade= $query->grade;
		$qty_order= intval($query->qty_order);
		$cr_date= $query->cr_date;
		$qty_pallet= intval($query->qty_pallet);
		$qty_prod= intval($query->qty_prod);
		$plt_no= $query->plt_no;
		$plt_tot= $query->plt_tot;

		$cek = "insert into ztb_kanban_print_label (wo_no,plt_no,tanggal,IP) 
				select '$wo_no','$plt_no',TO_CHAR(SYSDATE, 'DD-MON-YYYY HH24:MI:SS'),'$user_name' from dual";
		$data = oci_parse($connect, $cek);
		$res = oci_execute($data);
		$pesan = oci_error($data);
		$msg = $pesan['message'];
		if ($msg != '') {
			break;
		};

		$cek = "insert into ztb_plan_L (Wo_No,Item_No,Brand,Date_Code,Type_item,Grade,Qty_Order,Cr_Date,Qty_Pallet,Date_Prod,Qty_Prod,PLT_No,Plt_Tot,user_id) 
				select '$wo_no',
					  '$item_no',
					  '$brand',
					  '$date_code',
					  '$item_type',
					  '$grade',
					  '$qty_order',
					  '$cr_date',
					  '$qty_pallet',
					  TO_CHAR(SYSDATE, 'DD-MON-YYYY'),
					  '$qty_prod',
					  '$plt_no',
					  '$plt_tot', 
					  '$user_name'
					  from dual";
		$data = oci_parse($connect, $cek);
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
	echo $cek;
}else{
	//TARUH PRINT DISINI
	$sql = "insert into ztb_l_plan select * from ztb_plan_L where user_id = '$user_name'";
	$data = oci_parse($connect, $sql);
	$res = oci_execute($data);
	echo json_encode('success');
}
?>
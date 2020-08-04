<?php
header('Content-Type: text/plain; charset="UTF-8"');
session_start();
error_reporting(0);
set_time_limit(0);
ini_set('memory_limit', '-1');
include("../../connect/conn.php");

$user_name = $_SESSION['id_wms'];

$sql = "delete from ztb_plan where user_id = '$user_name'";
$data = sqlsrv_query($connect, $sql);
if( $data === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
		foreach( $errors as $error ) {
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
			echo "code: ".$error[ 'code']."<br />";
			echo "message: ".$error[ 'message']."<br />";
			echo $sql;
		}
	}
}

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

		$cek = "insert into ztb_kanban_print (wo_no,plt_no,tanggal,user_id) 
				select '$wo_no','$plt_no',cast(getdate() as datetime),'$user_name' ";
		$data = sqlsrv_query($connect, $cek);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $cek;
				}
			}
		}
		

		$cek = "insert into ztb_plan (Wo_No,Item_No,Brand,Date_Code,Type_item,Grade,Qty_Order,Cr_Date,Qty_Pallet,Date_Prod,Qty_Prod,PLT_No,Plt_Tot,user_id) 
				select '$wo_no',
					  '$item_no',
					  '$brand',
					  '$date_code',
					  '$item_type',
					  '$grade',
					  '$qty_order',
					  '$cr_date',
					  '$qty_pallet',
					  getdate(),
					  '$qty_prod',
					  '$plt_no',
					  '$plt_tot',
					  '$user_name'";
		$data = sqlsrv_query($connect, $cek);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $cek;
				}
			}
		}
	
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo ($cek);
}else{
	//TARUH PRINT DISINI
	$sql = "insert into ztb_p_plan  select * from ztb_plan where user_id = '$user_name'";
	$data = sqlsrv_query($connect, $sql);


	echo json_encode('success');
}





?>
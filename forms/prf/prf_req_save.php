<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	
	$req_no = htmlspecialchars($_REQUEST['req_no']);
	$req_date = htmlspecialchars($_REQUEST['req_date']);
	$item = htmlspecialchars($_REQUEST['item']);
	$price = htmlspecialchars($_REQUEST['price']);
	$qty = htmlspecialchars($_REQUEST['qty']);
	$unit = htmlspecialchars($_REQUEST['unit']);
	$tot = htmlspecialchars($_REQUEST['tot']);
	$rmk = htmlspecialchars($_REQUEST['rmk']);
	$bdg = htmlspecialchars($_REQUEST['bdg']);
	$cmplt = htmlspecialchars($_REQUEST['cmplt']);
	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$id0=date('Ymd');

	$cek = "select count(*) from ztb_prf_req_details where substr(id,1,8)='$id0'";
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_array($data);

	if($dt[0]==0){
		$code = $id0."0001";
	}else{
		$cek_code = "select coalesce(lpad(cast(cast(substr(max(id),9,4) as integer)+1 as varchar2(4)),4,'0'),'0001') as code from ztb_prf_req_details where substr(id,1,8) = '$id0'";
		$data_code = oci_parse($connect, $cek_code);
		oci_execute($data_code);
		$dt_code = oci_fetch_array($data_code);
		$code = $id0.$dt_code[0];
	}

	/* CEK HEADER */
	$cek_h = "select count(*) from ztb_prf_req_header where req_no='$req_no'";
	$data_h = oci_parse($connect, $cek_h);
	oci_execute($data_h);
	$dt_h = oci_fetch_array($data_h);

	if($dt_h[0] == 0) {
		$ins1 = "insert into ztb_prf_req_header(req_no,req_date,sts_approval,sts_po,user_entry,last_update,total,type_budget,remarks,type_complete)
			values ('$req_no',TO_DATE('$req_date','YYYY-MM-DD'),'0','0','$user',TO_DATE('$now','YYYY-MM-DD HH24:MI:SS'),$tot,'$bdg','$rmk','$cmplt')";
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);
	}

	/* INSERT DETAILS */
	$ins2 = "insert into ztb_prf_req_details(id,req_no,item_no,price,qty,unit_code,sts_approval,sts_po) 
		values ('$code','$req_no','$item',$price,$qty,'$unit','0','0')";
	$data_ins2 = oci_parse($connect, $ins2);
	oci_execute($data_ins2);

	if ($data_ins2){
        echo json_encode("Success");
    }else{
        echo json_encode(array('errorMsg'=>'Error'));
    }
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
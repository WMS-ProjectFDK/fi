<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");

	$qtno = htmlspecialchars($_REQUEST['qtno']);
	$sts = htmlspecialchars($_REQUEST['sts']);
	$brg = htmlspecialchars($_REQUEST['brg']);
	$vnd = htmlspecialchars($_REQUEST['vnd']);
	$appr = htmlspecialchars($_REQUEST['appr']);
	$note = htmlspecialchars($_REQUEST['note']);
		
	$now=date('Y-m-d');
	$user = $_SESSION['id_wms'];

	if(trim($sts)=='t'){
		$flag = '1';
	}else{
		$flag = '';
	}

	$upd = "update ZTB_PRF_QUOTATION_DETAIL_COMP set flag_approved='$flag' where quotation_no='$qtno' and item_no='$brg' and vendor='$vnd'";
	$qry = oci_parse($connect, $upd);
	oci_execute($qry);

	if ($qry){
		$ins = "update ztb_prf_quotation_header set quotation_approved='1', quotation_note='$note', user_approval='$user' where quotation_no='$qtno'";
	    $sql = oci_parse($connect, $ins);
	  	oci_execute($sql);	

	  	if ($sql){
		  	echo json_encode("Success");
	    }else{
	        echo json_encode(array('errorMsg'=>'Error'));
	    }
	}else{
		echo json_encode(array('errorMsg'=>'Error'));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");

	$qtno = htmlspecialchars($_REQUEST['qtno']);
	$itno = htmlspecialchars($_REQUEST['itno']);
	$vndr = htmlspecialchars($_REQUEST['vndr']);
	$prce = htmlspecialchars($_REQUEST['prce']);
	//$appr = htmlspecialchars($_REQUEST['appr']);
	//$note = htmlspecialchars($_REQUEST['note']);
	$curr = htmlspecialchars($_REQUEST['curr']);
		
	$now=date('Y-m-d');
	$user = $_SESSION['id_wms'];

	/*$cek = "select count(*) from ztb_prf_quotation_detail_comp where quotation_no='$qtno' and item_no='$itno'";
    $cekNya = oci_parse($connect, $cek);
  	oci_execute($cekNya);
  	$dt = oci_fetch_array($cekNya);

  	if($dt[0]==0 ){*/
  		$ins = "insert into ztb_prf_quotation_detail_comp (quotation_no,item_no,vendor,price,curr_code) VALUES ('$qtno','$itno','$vndr',$prce,'$curr')";
	    $sql = oci_parse($connect, $ins);
	  	oci_execute($sql);	

	  	if ($sql){
	        /*$upd = "update ztb_prf_quotation_header set quotation_approved='1', quotation_note='$note', user_approval='$user' where quotation_no='$qtno'";
		    $sql2 = oci_parse($connect, $upd);
		  	oci_execute($sql2);

		  	if($sql2){*/
		  	echo json_encode("Success");	
		  	/*}else{
		  		echo json_encode(array('errorMsg'=>'Error'));	
		  	}*/
	    }else{
	        echo json_encode(array('errorMsg'=>'Error'));
	    }
  	/*}*/
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
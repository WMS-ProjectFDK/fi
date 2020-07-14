<?php
include("../connect/conn.php");
session_start();
if (isset($_SESSION['id_wms'])){
	$ppbe = htmlspecialchars($_REQUEST['ppbe']);
	$si = htmlspecialchars($_REQUEST['si']);
	
	//delete ztb_answer
	$sql0  = " delete from answer " ;
    $sql0 .= " where si_no = '$si' AND crs_remark = '$ppbe' " ;
    $data_del0 = oci_parse($connect, $sql0);
	oci_execute($data_del0);

	// delete ztb_shipping_plan
	$sql1  = " delete from  ztb_shipping_plan " ;
	$sql1 .= " where si_no='$si' and inv_no='$ppbe' ";
	$data_del1 = oci_parse($connect, $sql1);
	oci_execute($data_del1);
	
	// delete grpans_out
	$sql2  = " delete from grpans_out " ;
 	$sql2 .= " where  (customer_po_no,customer_line_no)= ";
 	$sql2 .= " (select customer_po_no,customer_po_line_no from answer where si_no = '$si' and crs_remark = '$ppbe') " ;
 	$data_del2 = oci_parse($connect, $sql2);
	oci_execute($data_del2);

	echo json_encode(array('successMsg'=>'success'));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	
	$pu_item = htmlspecialchars($_REQUEST['pu_item']);

	//UPDATE MRP
	$sql = "BEGIN ZSP_MRP_MATERIAL_ITEM(:v_item_no); END;";
	$stmt = oci_parse($connect, $sql);
	oci_bind_by_name($stmt,':v_item_no',$pu_item);
	$res = oci_execute($stmt);
	print_r($res, true);
	
	if ($res){
		echo json_encode(array('successMsg'=>'success MRP'));	
	}else{
		echo json_encode(array('errorMsg'=>'Error MRP'));	
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../../../connect/conn.php");
	
	$pu_item = htmlspecialchars($_REQUEST['pu_item']);

	//UPDATE MRP
	// $sql = "BEGIN ZSP_MRP_MATERIAL_ITEM(:v_item_no); END;";
	// $stmt = sqlsrv_query($connect, $sql);
	// oci_bind_by_name($stmt,':v_item_no',$pu_item);
	// $res = oci_execute($stmt);
    // print_r($res, true);
    
    $sql = "{call ZSP_MRP_MATERIAL_ITEM(?)}";
    $params = array(  
        array(trim($pu_item), SQLSRV_PARAM_IN)
    );
    
    $stmt = sqlsrv_query($connect, $sql,$params);
	
	if ($stmt){
		echo json_encode(array('successMsg'=>'success MRP'));	
	}else{
		echo json_encode(array('errorMsg'=>'Error MRP'));	
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
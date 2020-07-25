<?php
session_start();
error_reporting(0);
header("Content-type: application/json");
include("../../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$user = $_SESSION['id_wms'];
	$item_no = htmlspecialchars($_REQUEST['item_no']);
	$data = explode(',', $item_no);
	$success = 0;		$failed=0;		$arrData = array();		$msg='';
	for($i=0;$i<count($data);$i++){
		$del1 = "delete from ztb_config_rm where item_no = $data[$i]";
		$data_del1 = sqlsrv_query($connect, $del1);

		if($data_del1 === false ){
			$msg .= "Delete Configuration Item Error<br/>$data[$i]";
			break;
		}

		$del2 = "delete from ztb_material_konversi where item_no = $data[$i]";
		$data_del2 = sqlsrv_query($connect, $del2);
		if($data_del2 === false){
			$msg .= "Delete material Item Error<br/>$data[$i]";
			break;
		}

		if($msg == ''){
			$success++;	
		}else{
			$failed++;
			$msg .= "Delete Material Konversi Error<br/>$msg";
			break;
		}
	}

	if($msg == ''){
		$arrData[0] = array("kode"=>"success");
	}else{
		$arrData[0] = array("kode"=>$msg);
	}
}else{
	$arrData = array("kode"=>"Session Expired");
}
echo json_encode($arrData);
?>
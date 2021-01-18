<?php
session_start();
// error_reporting(0);
header("Content-type: application/json");
$msg = '';

if (isset($_SESSION['id_wms'])){
	include("../../../connect/conn.php");
	$user = $_SESSION['id_wms'];
	$approve_slip = htmlspecialchars($_REQUEST['approve_slip']);
	$data = explode(',', $approve_slip);
	$success = 0;		$failed=0;		$arrData = array();
	for($i=0;$i<count($data);$i++){
		$sql = "{call ZSP_SP_INSERT_MT(?,?)}";
		$params = array(  
			array(  $data[$i] , SQLSRV_PARAM_IN),
			array(  $user  , SQLSRV_PARAM_IN)
		);

		$stmt = sqlsrv_query($connect, $sql,$params);
		if( $stmt === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $ins1;
				}
			}
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
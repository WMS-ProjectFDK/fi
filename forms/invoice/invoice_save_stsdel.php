<?php
session_start();
include("../../connect/conn.php");
$msg = '';

if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$stsdel_dono = htmlspecialchars($_REQUEST['stsdel_dono']); 
		$stsdel_etd = htmlspecialchars($_REQUEST['stsdel_etd']);
		$stsdel_eta = htmlspecialchars($_REQUEST['stsdel_eta']);
		$stsdel_vsl = htmlspecialchars($_REQUEST['stsdel_vsl']);
		$stsdel_rmk = htmlspecialchars($_REQUEST['stsdel_rmk']);
		$user = $_SESSION['id_wms'];
		$field = '';		$value = '';

		/*REMARK*/
		if (str_replace(' ', '', $stsdel_rmk) == ''){
			$field .= "REMARK=, ";
		}else{
			$rmk_s0 = explode('&lt;br&gt;', $stsdel_rmk);
			$rmk_f0 = '';
			for($f0=0;$f0<count($rmk_s0);$f0++){
				if($rmk_s0[$f0] != ''  || ! is_null($rmk_s0[$f0])) {
					if($f0 == count($rmk_s0)-1){
						$rmk_f0 .= "'".str_replace("'","''",$rmk_s0[$f0])."'";
					}else{
						$rmk_f0 .= "'".str_replace("'","''",$rmk_s0[$f0])."' + char(13) + ";
					}
				}
			}
			$rmk_fix0 = str_replace("&amp;", "&", $rmk_f0);
			$field .= "REMARK=$rmk_fix0, ";
		}

		/*VESSEL*/
		if (str_replace(' ', '', $stsdel_vsl) == ''){
			$field .= "SHIP_NAME=, ";
			$value .= "VESSEL=, ";
		}else{
			$vsl_s = explode('&lt;br&gt;', $stsdel_vsl);
			$vsl_f = '';
			for($v=0;$v<count($vsl_s);$v++){
				if($vsl_s[$v] != '' || ! is_null($vsl_s[$v])) {
					if($v == count($vsl_s)-1){
						$vsl_f .= "'".str_replace("'","''",$vsl_s[$v])."'";
					}else{
						$vsl_f .= "'".str_replace("'","''",$vsl_s[$v])."' + char(13) + ";
					}
				}
			}
			$vsl_fix = str_replace("&amp;", "&", $vsl_f);
			$field .= "SHIP_NAME=$vsl_fix, ";
			$value .= "VESSEL=$vsl_fix, ";
		}

		$field .= "ETD = '$stsdel_etd', ";		$value .= "ETD = '$stsdel_etd',";
		$field .= "ETA = '$stsdel_eta' ";		$value .= "ETA = '$stsdel_eta'";

		$upd = "update ztb_do_temp set $field where do_no = '$stsdel_dono' ";
		$data_upd = sqlsrv_query($connect, $upd);
		
		if( $data_upd === false ){
			die( print_r( sqlsrv_errors(), true));
		}

		$upd2 = "update answer set $value where answer_no in (select answer_no1 from do_details where do_no='$stsdel_dono') ";
		$data_upd2 = sqlsrv_query($connect, $upd2);
		// echo $upd2;

		if( $data_upd2 === false ){
			die( print_r( sqlsrv_errors(), true));
		}

		if($msg == ''){
			echo json_encode(array('successMsg'=>'UPDATE DATA SUCCCESS'));
		}else{
			echo json_encode(array('errorMsg'=>$msg));
		}
	}else{
		echo json_encode(array('errorMsg'=>'Connection Failed'));	
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
<?php
	header("Content-type: application/json");
	$id_kanbanL = isset($_REQUEST['id_kanbanL']) ? strval($_REQUEST['id_kanbanL']) : '';
	$arrData = array();
	$arrNo = 0;
	session_start();
	include("../../connect/conn.php");

	if (isset($_SESSION['user_labelAfter'])){
		$user = $_SESSION['user_labelAfter'];	
		//CEK
		$cek = "select count(*) as jum from (select * from ztb_kanban_lbl  
			where idkanban = $id_kanbanL and Worker_ID = $user and enddate is null)";
		//echo $cek;
		$result_cek = oci_parse($connect, $cek);
		oci_execute($result_cek);
		$row_cek=oci_fetch_object($result_cek);

		if ($row_cek->JUM == 0){
			$arrData[$arrNo] = array("kode"=>'YES');
		}else{
			$arrData[$arrNo] = array("kode"=>'NO');
		}
	}else{
		$arrData[$arrNo] = array("kode"=>'DISCONNECT');
	}
	echo json_encode($arrData);
?>
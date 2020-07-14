<?php
	session_start();
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$id_kanbanL = isset($_REQUEST['id_kanbanL']) ? strval($_REQUEST['id_kanbanL']) : '';
	$grade = isset($_REQUEST['grade']) ? strval($_REQUEST['grade']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	
	if (isset($_SESSION['user_labelAfter'])){
		$line = $_SESSION['line_labelAfter'];
		//CEK
		$cek = "select labelline,grade, sum(case when qty_in_antrian = 0 then to_number(qty) else qty_in_antrian end) as qty
			from ztb_lbl_trans
			where remark='RESULT' and replace(labelline,'#','-') ='$line' and grade = '$grade'
			group by labelline, grade";
		//echo $cek;

		$result_cek = oci_parse($connect, $cek);
		oci_execute($result_cek);
		$row_cek=oci_fetch_object($result_cek);
		
		if ($row_cek->QTY >= $qty){
			$arrData[$arrNo] = array("kode"=>'YES');
		}else{
			$arrData[$arrNo] = array("kode"=>'NO');
		}
	}else{
		$arrData[$arrNo] = array("kode"=>'DISCONNECT');
	}

	echo json_encode($arrData);
?>
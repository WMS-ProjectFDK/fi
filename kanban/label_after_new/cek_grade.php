<?php
	session_start();
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$id_kanbanL = isset($_REQUEST['id_kanbanL']) ? strval($_REQUEST['id_kanbanL']) : '';
	$grade = isset($_REQUEST['grade']) ? strval($_REQUEST['grade']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';

	if($grade == 'C1') {
		$grd = "('C01','C01NC')";
	}elseif ($grade == 'C1NC'){
		$grd = "('C01','C01NC')";
	}elseif ($grade == 'G06NC') {
		$grd = "('G06','G06NC')";
	}elseif ($grade == 'G07NC') {
		$grd = "('G07','G07NC')";
	}elseif ($grade == 'G08NC') {
		$grd = "('G08','G08NC','G08E')";
	}elseif ($grade == 'G08E') {
		$grd = "('G08','G08NC','G08E')";
	}else{
		$grd = "('".$grade."')";
	}

	$total_qty = 0;
	
	if (isset($_SESSION['user_labelAfter'])){
		$line = $_SESSION['line_labelAfter'];
		//CEK
		$cek = "select labelline,grade, sum(case when qty_in_antrian = 0 then to_number(qty-(qty_terpakai+ng_qty)) else to_number(qty-(qty_in_antrian+ng_qty)) end) as qty
			from ztb_lbl_trans
			where remark='FINISH' and replace(labelline,'#','-') ='$line' and grade in $grd
			group by labelline, grade";
		// echo $cek;

		$result_cek = oci_parse($connect, $cek);
		oci_execute($result_cek);

		while($row_cek=oci_fetch_object($result_cek)){
			$total_qty += $row_cek->QTY; 
		}
		
		if ($total_qty >= $qty){
			$arrData[$arrNo] = array("kode"=>'YES', "qty"=>$total_qty);
		}else{
			$arrData[$arrNo] = array("kode"=>'NO', "qty"=>$total_qty);
		}
	}else{
		$arrData[$arrNo] = array("kode"=>'DISCONNECT');
	}

	echo json_encode($arrData);
?>
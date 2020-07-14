<?php
	header("Content-type: application/json");
	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$pcs = isset($_REQUEST['pcs']) ? strval($_REQUEST['pcs']) : '';
	$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';
	$arrData = array();
	$arrNo = 0;
	$msg = '';
	
	include("../../connect/conn.php");
	
	if ($sts == 'selesaikan'){
		$sql = "update ztb_lbl_trans set remark='FINISH' where id_print='".$id_print."' and QTY_IN_ANTRIAN = 0";
	}elseif($sts == 'keluarAntrian'){
		$sql = "update ztb_lbl_trans set status=2, remark='FINISH', QTY_IN_ANTRIAN = $pcs where id_print='".$id_print."'";
	}

	$result = oci_parse($connect, $sql);
	oci_execute($result);

	$pesan = oci_error($result);
	$msg = $pesan['message'];

	if($msg != ''){
		$msg .= " Insert Query Error : $ins";
		break;
	}
	
	if($msg == ''){
		$arrData[$arrNo] = array("kode"=>'success');
	}else{
		$arrData[$arrNo] = array("kode"=>$upd);
	}

	echo json_encode($arrData);
?>
<?php
session_start();
error_reporting(0);
header("Content-type: application/json");
include("../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$approve_slip = htmlspecialchars($_REQUEST['approve_slip']);
	$data = explode(',', $approve_slip);
	$user = $_SESSION['id_wms'];
	$msg = '';	$arrData = array();

	for($i=0;$i<count($data);$i++){
		//update mte_header
		$upd = "update PRF_HEADER set APPROVAL_DATE = sysdate , APPROVAL_PERSON_CODE = '$user'
			 where PRF_NO = '".$data[$i]."'";
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);
		$pesan = oci_error($data_upd);
		$msg = $pesan['message'];

		if($msg != ''){
			$msg .= "Header Approve Error<br/>$upd";
			break;
		}

		$cek = "select b.item_no, b.line_no, b.qty, c.SUPPLIER_CODE, c.CURR_CODE, c.ESTIMATE_PRICE from PRF_HEADER a
			inner join prf_details b on a.prf_no=b.prf_no
			inner join ITEMMAKER c on b.item_no=c.item_no
			where a.prf_no= '".$data[$i]."' and
			(c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = b.ITEM_NO))";
		$data_cek = oci_parse($connect, $cek);
		oci_execute($data_cek);

		while ($row = oci_fetch_object($data_cek)){
			//update mte_details
			$qry = "update prf_details set remainder_qty=".$row->QTY.",SUPPLIER_CODE='".$row->SUPPLIER_CODE."',
				curr_code=".$row->CURR_CODE.",u_price=".$row->ESTIMATE_PRICE."
			where prf_no='".$data[$i]."' and item_no='".$row->ITEM_NO."' and line_no='".$row->LINE_NO."' ";
			$dt_qry = oci_parse($connect, $qry);
			oci_execute($dt_qry);
			$pesan = oci_error($dt_qry);
			$msg = $pesan['message'];

			if($msg != ''){
				$msg .= "Details Approve Error<br/>$qry";
				break;
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
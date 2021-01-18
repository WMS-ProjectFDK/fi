<?php
$po_no = strval($_REQUEST['po_no']);
include("../../connect/conn.php");

/*
TGL: 16/JUN/2017
DSC : CEK Goods Receive QTY
ID: REZA
*/


$cek = "select sum(gr_qty) as GR from po_details where po_no='".$po_no."'";
$data_cek = sqlsrv_query($connect, $cek);

while ($dt_cek = sqlsrv_fetch_object($data_cek)) {
	$Total = $dt_cek->GR;
}

if($Total == 0) {
	
	$del = "delete from po_header where po_no='".$po_no."'";
	$data_del = sqlsrv_query($connect, $del);
	

	if($data_del){
		$cek = "select item_no, qty, prf_no, prf_line_no from po_details where po_no='".$po_no."'";
		$data_cek = sqlsrv_query($connect, strtoupper($cek));
		
		while ($dt_cek = sqlsrv_fetch_object($data_cek)) {
			$upd = "update PRF_DETAILS
				    set REMAINDER_QTY = REMAINDER_QTY + (".$dt_cek->QTY.")
				    , UPTO_DATE = getdate()
				 	where PRF_NO = '".$dt_cek->PRF_NO."'
				   	and LINE_NO = ".$dt_cek->PRF_LINE_NO."";
		
					   $data_upd = sqlsrv_query($connect, $upd);
			

			//update MRP
			/*$sql = "BEGIN ZSP_MRP_MATERIAL_ITEM(:v_item_no); END;";
			$stmt = sqlsrv_query($connect, $sql);
			oci_bind_by_name($stmt,':v_item_no',$dt_cek->ITEM_NO);
			$res = oci_execute($stmt);
			print_r($res, true);*/
		}

		$del2 = "delete from po_details where po_no='".$po_no."'";
		$data_del2 = sqlsrv_query($connect, $del2);
	
		
		if($data_del2){
			echo json_encode(array('success'=>true));
		}else{
			echo json_encode(array('errorMsg'=>'Some errors occured.'));
		}
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}else{
	echo json_encode(array('errorMsg'=>'PO already haave to receive'));
}
?>
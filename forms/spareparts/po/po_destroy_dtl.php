<?php
$po = strval($_REQUEST['po']);
$item = strval($_REQUEST['item']);
$line = strval($_REQUEST['line']);
$Total = 0;
include("../../../connect/conn.php");

/*
TGL: 26/apr/2017
DSC : TAMBAH UPPDATE PRF 
ID: UENG
*/

/*
TGL: 16/JUN/2017
DSC : CEK Goods Receive QTY
ID: REZA
*/


$cek = "select  sum(gr_qty) as GR from sp_po_details where po_no='".$po."' and line_no=".$line;
$data_cek = sqlsrv_query($connect, $cek);
// echo $cek;
while ($dt_cek = sqlsrv_fetch_object($data_cek)) {
	$Total = $dt_cek->GR;
}

if ($Total <= 0 ) {

	$cek = "select a.po_no, a.line_no, a.item_no, a.qty, a.amt_o, a.amt_l, a.prf_no, a.prf_line_no, 
		b.amt_o-a.amt_o as amt_o_new, b.amt_l-a.amt_l as amt_l_new
		from sp_po_details a 
		left join sp_po_header b on a.po_no=b.po_no
		where a.po_no='".$po."' and a.item_no='".$item."' and a.line_no=".$line;
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	$dt_cek = sqlsrv_fetch_object($data_cek);

	//update amt_l & amt_o
	$upd_amt = "update sp_po_header set amt_o=".$dt_cek->AMT_O_NEW.", amt_l=".$dt_cek->AMT_L_NEW." where po_no='$po'";
	$data_upd_amt = sqlsrv_query($connect, $upd_amt);

	// echo $upd_amt;

	// if($dt_cek->PRF_NO!='-'){
	// 	$upd = "update prf_details set remainder_qty= remainder_qty + ".intval($dt_cek->QTY)." where prf_no='".$dt_cek->PRF_NO."' and line_no='".$dt_cek->PRF_LINE_NO."' ";
	// 	$data_upd = sqlsrv_query($connect, $del);
	// }

	$del = "delete from sp_po_details 
		where po_no='".$po."' and item_no='".$item."' and line_no=".$line;
	$data_del = sqlsrv_query($connect, $del);

	// echo $del;

	if($data_del){
		echo json_encode(array('success'=>true));	
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}else{
	echo json_encode(array('errorMsg'=>'PO already haave to receive'));
}
?>
<?php
$po = strval($_REQUEST['po']);
$item = strval($_REQUEST['item']);
$line = strval($_REQUEST['line']);

include("../connect/conn.php");

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


$cek = "select  gr_qty  as GR from po_details where po_no='".$po_no."' and a.line_no='".$line."'";
$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);
while ($dt_cek = oci_fetch_object($data_cek)) {
	$Total = $dt_cek->GR;
}

if ($Total <= 0 ) {

	$cek = "select a.po_no, a.line_no, a.item_no, a.qty, a.amt_o, a.amt_l, a.prf_no, a.prf_line_no, 
		b.amt_o-a.amt_o as amt_o_new, b.amt_l-a.amt_l as amt_l_new
		from po_details a 
		inner join po_header b on a.po_no=b.po_no
		where a.po_no='".$po."' and a.item_no=".$item." and a.line_no='".$line."'";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);

	//update amt_l & amt_o
	$upd_amt = "update po_header set amt_o=".$dt_cek->AMT_O_NEW.", amt_l=".$dt_cek->AMT_L_NEW." where po_no='$po'";
	$data_upd_amt = oci_parse($connect, $upd_amt);
	oci_execute($data_upd_amt);

	if($dt_cek->PRF_NO!='-'){
		$upd = "update prf_details set remainder_qty= remainder_qty + ".intval($dt_cek->QTY)." where prf_no='".$dt_cek->PRF_NO."' and line_no='".$dt_cek->PRF_LINE_NO."' ";
		$data_upd = oci_parse($connect, $del);
		oci_execute($data_del);	
	}

	$del = "delete from po_details where po_no='".$po."' and item_no=".$item." and line_no='".$line."'";
	$data_del = oci_parse($connect, $del);
	oci_execute($data_del);

	if($data_del){
		echo json_encode(array('success'=>true));	
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));
	}
}
?>
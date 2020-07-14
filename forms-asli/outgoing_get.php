<?php
	session_start();
	$result = array();

	$pd_periode_awal = isset($_REQUEST['pd_periode_awal']) ? strval($_REQUEST['pd_periode_awal']) : '';
	$type = isset($_REQUEST['type']) ? strval($_REQUEST['type']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.slip_no, b.line_no,b.item_no, c.description,b.qty,b.uom_q,d.unit,0 as sts from mte_header a
		left join mte_details b on a.slip_no=b.slip_no left join item c on b.item_no=c.item_no
		left join unit d on b.uom_q=d.unit_code where TO_char(a.slip_date,'YYYY-MM-DD')='$pd_periode_awal' and c.stock_subject_code=$type 
		and c.item_no not between 1200000 and 1299999";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($q);
		
		$do = $items[$rowno]->SLIP_NO;
		$line = $items[$rowno]->LINE_NO;
		$item = $items[$rowno]->ITEM_NO;

		$cek = "select count(*) as jum from ztb_wh_do_det where do_no='$do' and line_no='$line' and item_no='$item'";
		$dt = oci_parse($connect, $cek);
		oci_execute($dt);
		$cekNya = oci_fetch_array($dt);
		if($cekNya[0]==0){
			$items[$rowno]->STS = 0;
			$items[$rowno]->STS_NAME = '<span style="color:blue;font-size:11px;"><b>Belum di Print</b></span>';
		}else{
			$items[$rowno]->STS = 1;
			$items[$rowno]->STS_NAME = '<span style="color:red;font-size:11px;"><b>Sudah di Print</b></span>';
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
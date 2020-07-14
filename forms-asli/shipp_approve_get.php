<?php
	session_start();
	include("../connect/conn.php");
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_ppbe = isset($_REQUEST['cmb_ppbe']) ? strval($_REQUEST['cmb_ppbe']) : '';
	$ck_ppbe_no = isset($_REQUEST['ck_ppbe_no']) ? strval($_REQUEST['ck_ppbe_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

	if ($ck_date != "true"){
		$date = "to_char(ind.ex_factory,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' and ";
	}else{
		$date = "";
	}

	if ($ck_ppbe_no != "true"){
		$ppbe = "a.crs_remark = '$cmb_ppbe' and ";
	}else{
		 $ppbe = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "a.item_no = '$cmb_item_no' and ";
	}else{
		$item_no = "";
	}
	
	$where ="where $date $ppbe $item_no  ind.remark = '1'";

	$sql = "select * from (
		select ind.ex_factory, a.etd, a.eta, a.crs_remark, a.item_no, b.description, a.so_no, a.so_line_no, a.work_no, a.answer_no, a.customer_po_no,
				nvl(c.qty,0) qty_order, nvl(sum(d.slip_quantity),0) qty_Produksi, a.qty as qty_plan,
		    ind.remark
				from answer a
				inner join item b on a.item_no = b.item_no
		    inner join indication ind on a.answer_no = ind.answer_no
				left join mps_header c on a.work_no = c.work_order
				left join production_income d on a.work_no = d.wo_no
				$where
				group by ind.ex_factory, a.etd, a.eta, a.crs_remark, a.item_no, b.description, a.so_no, a.so_line_no, a.work_no, a.answer_no, a.qty, a.customer_po_no, c.qty, ind.remark
		)
		where (qty_order <> qty_produksi OR qty_produksi < qty_order)
		order by crs_remark";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->QTY_ORDER = number_format($items[$rowno]->QTY_ORDER);
		$items[$rowno]->QTY_PRODUKSI = number_format($items[$rowno]->QTY_PRODUKSI);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
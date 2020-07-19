<?php
	session_start();
	include("../../connect/conn.php");
	$result = array();
	$items = array();
	$rowno=0;

	$pd_periode_awal = isset($_REQUEST['pd_periode_awal']) ? strval($_REQUEST['pd_periode_awal']) : '';

	$where ="where a.gr_date='$pd_periode_awal' and a.gr_no is not null";

	$rs = "select distinct top 200  a.gr_no,cast(a.gr_date as varchar(10)) as gr_date,b.line_no,a.supplier_code,c.company,b.item_no,d.description, b.qty, e.unit,
		coalesce((select max(pallet) from ztb_wh_in_det where gr_no= a.gr_no and item_no=b.item_no and line_no=b.line_no), 0) as pallet,
		coalesce((select distinct qty from ztb_wh_in_det where gr_no=a.gr_no and item_no=b.item_no and line_no=b.line_no and pallet=1),0) as qtypallet from gr_header a
		left join gr_details b on a.gr_no=b.gr_no left join company c on a.supplier_code=c.company_code
		left join item d on b.item_no=d.item_no left join unit e on b.uom_q=e.unit_code
		$where
		order by a.supplier_code,a.gr_no, b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);
		
		$qtypallet = $items[$rowno]->QTYPALLET;
		$items[$rowno]->QTYPALLET = number_format($qtypallet);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
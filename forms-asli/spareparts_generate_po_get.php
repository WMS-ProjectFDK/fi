<?php
	session_start();
	include("../connect/conn2.php");

	$sql = "select a.company_code, com.company, a.item_no, b.description, b.uom_q, un.unit, a.qty, a.u_price, a.pdays,a.pdesc from ztb_temp_po a
		left join company com on a.company_code=com.company_code
		left join item b on a.item_no=b.item_no
		left join unit un on b.uom_q=un.unit_code
		order by a.company_code asc, a.item_no desc";
	$data_sql = oci_parse($connect, $sql);
	oci_execute($data_sql);
	$items = array();
	$rowno = 0;
	while($row = oci_fetch_object($data_sql)){
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);
		
		$prc = $items[$rowno]->U_PRICE;
		$items[$rowno]->U_PRICE = number_format($prc);

		$items[$rowno]->COMPANY = $items[$rowno]->COMPANY_CODE."<br/>".$items[$rowno]->COMPANY;
		$items[$rowno]->DESCRIPTION = $items[$rowno]->ITEM_NO."<br/>".$items[$rowno]->DESCRIPTION;
		$items[$rowno]->PDESC = $items[$rowno]->PDAYS." ".$items[$rowno]->PDESC;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
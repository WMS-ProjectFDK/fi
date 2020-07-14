<?php
	session_start();
	$qtt = isset($_REQUEST['quotation']) ? strval($_REQUEST['quotation']) : '';
	
	include("../connect/conn2.php");

	$sql = "select a.quotation_no, a.item_no, b.description, c.company, a.vendor as SUPPLIER_CODE, a.price, a.curr_code, d.curr_short as curr from ztb_prf_quotation_detail_comp a 
		left join item b on a.item_no=b.item_no left join company c on a.vendor=c.company_code left join currency d on a.curr_code=d.curr_code
		where a.quotation_no='$qtt' order by b.description asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$items = array();
	$rowno=0;
	$itm ='';
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$item = $items[$rowno]->ITEM_NO;
		if($itm!=$item){
			$items[$rowno]->ITEM_NO_TAMPIL = $item;
		}else{
			$items[$rowno]->ITEM_NO_TAMPIL = '';
			$items[$rowno]->DESCRIPTION = '';
		}

		$p = $items[$rowno]->PRICE;
		$items[$rowno]->PRICE = number_format($p);
		$itm = $items[$rowno]->ITEM_NO_TAMPIL;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
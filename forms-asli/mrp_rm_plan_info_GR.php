<?php
	ini_set('max_execution_time', -1);
	session_start();
	//item_no=1170117&tgl_plan=2017-11-26
	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl_plan = isset($_REQUEST['tgl_plan']) ? strval($_REQUEST['tgl_plan']) : '';

	include("../connect/conn.php");

	$cek = "select a.gr_no, a.gr_date, b.item_no, i.item, i.description, b.qty, b.uom_q, u.unit, 
		b.po_no, b.po_line_no, d.supplier_code, com.company, c.eta
		from gr_header a
		inner join gr_details b on a.gr_no=b.gr_no
		inner join po_details c on b.po_no=c.po_no AND b.po_line_no=c.line_no
		inner join po_header d on c.po_no=d.po_no
		INNER JOIN ITEM i ON b.ITEM_NO=I.ITEM_NO
		INNER JOIN UNIT u ON b.uom_q = u.unit_code
		inner join company com on d.supplier_code=com.company_code
		where to_char(a.gr_date,'yyyy-mm-dd')='$tgl_plan' AND b.item_no= $item_no";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	$items = array();
	$rowno=0;

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$Q = $items[$rowno]->QTY;
		$items[$rowno]->QTY = '<b>'.number_format($Q).'</b>';

		$prf = $items[$rowno]->PRF_NO;
		$prf_line = $items[$rowno]->PRF_LINE_NO;
		if($prf == '' OR is_null($prf)){
			$items[$rowno]->PRF_NO = 'NON-PRF';
		}else{
			$items[$rowno]->PRF_NO = $prf.' ('.$prf_line.')';	
		}

		$i = $items[$rowno]->ITEM_NO;
		$it = $items[$rowno]->ITEM;
		$iDesc = $items[$rowno]->DESCRIPTION;
		$items[$rowno]->ITEM_NO = $i.' - '.$it.'<br/>'.$iDesc;

		$po = $items[$rowno]->PO_NO;
		$pol = $items[$rowno]->PO_LINE_NO;
		$items[$rowno]->PO_NO = $po.' ('.$pol.')';


		$co_id = $items[$rowno]->SUPPLIER_CODE;
		$co_name = $items[$rowno]->COMPANY;
		$items[$rowno]->COMPANY = $co_id.'<br/>'.$co_name;
		


		
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>
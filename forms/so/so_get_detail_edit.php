<?php
	error_reporting(0);
	session_start();
	$result = array();
	
	$so_no = isset($_REQUEST['so_no']) ? strval($_REQUEST['so_no']) : '';
	$sts = "'EDIT'";
	$items = array();
	$rowno = 0;

	include("../../connect/conn.php");

	$rs = "select a.so_no, a.line_no, a.customer_part_no, a.ITEM_NO, it.ITEM, it.DESCRIPTION, a.qty as ACT_QTY_RESULT, a.uom_q, un.UNIT,
		a.U_PRICE, a.AMT_O, a.AMT_L, b.curr_code, curr.CURR_MARK, CAST(a.CUSTOMER_REQ_DATE as varchar(10)) as REQ_DATE,
		CAST(a.ETD as varchar(10)) as EXFACT_DATE, a.DATE_CODE, a.BAL_QTY, a.AGING_DAY, a.ASIN, a.AMAZON_PO_NO as AMZ_PO,
		case when a.PALLET_MARK_1 IS NOT NULL then a.PALLET_MARK_1+'<br/>' else '' end + 
		case when a.PALLET_MARK_2 IS NOT NULL then a.PALLET_MARK_2+'<br/>' else '' end +
		case when a.PALLET_MARK_3 IS NOT NULL then a.PALLET_MARK_3+'<br/>' else '' end +
		case when a.PALLET_MARK_4 IS NOT NULL then a.PALLET_MARK_4+'<br/>' else '' end +
		case when a.PALLET_MARK_5 IS NOT NULL then a.PALLET_MARK_5+'<br/>' else '' end +
		case when a.PALLET_MARK_6 IS NOT NULL then a.PALLET_MARK_6+'<br/>' else '' end +
		case when a.PALLET_MARK_7 IS NOT NULL then a.PALLET_MARK_7+'<br/>' else '' end +
		case when a.PALLET_MARK_8 IS NOT NULL then a.PALLET_MARK_8+'<br/>' else '' end +
		case when a.PALLET_MARK_9 IS NOT NULL then a.PALLET_MARK_9+'<br/>' else '' end +
		case when a.PALLET_MARK_10 IS NOT NULL then a.PALLET_MARK_10 else '' end as P_MARK_RESULT,
		case when a.CASE_MARK_1 is not null then a.CASE_MARK_1+'<br/>' else '' end +
		case when a.CASE_MARK_2 is not null then a.CASE_MARK_2+'<br/>' else '' end +
		case when a.CASE_MARK_3 is not null then a.CASE_MARK_3+'<br/>' else '' end +
		case when a.CASE_MARK_4 is not null then a.CASE_MARK_4+'<br/>' else '' end +
		case when a.CASE_MARK_5 is not null then a.CASE_MARK_5+'<br/>' else '' end +
		case when a.CASE_MARK_6 is not null then a.CASE_MARK_6+'<br/>' else '' end +
		case when a.CASE_MARK_7 is not null then a.CASE_MARK_7+'<br/>' else '' end +
		case when a.CASE_MARK_8 is not null then a.CASE_MARK_8+'<br/>' else '' end +
		case when a.CASE_MARK_9 is not null then a.CASE_MARK_9+'<br/>' else '' end +
		case when a.CASE_MARK_10 is not null then a.CASE_MARK_10 else '' end as C_MARK_RESULT
		from SO_DETAILS a
		inner join so_header b on a.so_no = b.SO_NO
		inner join item it on a.ITEM_NO=it.ITEM_NO
		inner join unit un on a.UOM_Q = un.UNIT_CODE
		inner join CURRENCY curr on b.CURR_CODE = curr.CURR_CODE 
		where a.so_no='$so_no'
		order by line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);

		$it = "'".$items[$rowno]->ITEM_NO."'";
		$ln = "'".$items[$rowno]->LINE_NO."'";
		$pr = "'".$items[$rowno]->U_PRICE."'";

		$items[$rowno]->P_MARK = '<a href="javascript:void(0)" onclick="input_pallet('.$sts.','.$it.','.$ln.')">SET</a>';
		$items[$rowno]->C_MARK = '<a href="javascript:void(0)" onclick="input_case('.$sts.','.$it.','.$ln.')">SET</a>';
		$items[$rowno]->ACT_QTY = '<a href="javascript:void(0)" onclick="input_qty('.$it.','.$ln.','.$pr.','.$sts.')">SET</a>';

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
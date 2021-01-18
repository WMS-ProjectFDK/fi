<?php
	error_reporting(0);
	session_start();
	$result = array();
	
	$so_no = isset($_REQUEST['so_no']) ? strval($_REQUEST['so_no']) : '';
	$sts = "'EDIT'";
	$items = array();
	$rowno = 0;

	include("../../connect/conn.php");

	$rs = "select a.so_no, a.line_no, a.customer_part_no, a.ITEM_NO, it.ITEM, it.DESCRIPTION, a.qty as ACT_QTY_RESULT, un.UNIT AS uom_q, un.UNIT,
		a.U_PRICE, a.AMT_O as AMOUNT_RESULT, a.AMT_L, b.curr_code, curr.CURR_MARK, CAST(a.CUSTOMER_REQ_DATE as varchar(10)) as REQ_DATE,
		CAST(a.ETD as varchar(10)) as EXFACT_DATE, a.DATE_CODE, a.BAL_QTY,a.CUSTOMER_PO_LINE_NO, a.AGING_DAY, a.ASIN, a.AMAZON_PO_NO as AMZ_PO,
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
		a.PALLET_MARK_1,
		a.PALLET_MARK_2,
		a.PALLET_MARK_3,
		a.PALLET_MARK_4,
		a.PALLET_MARK_5,
		a.PALLET_MARK_6,
		a.PALLET_MARK_7,
		a.PALLET_MARK_8,
		a.PALLET_MARK_9,
		a.PALLET_MARK_10,
		case when a.CASE_MARK_1 is not null then a.CASE_MARK_1+'<br/>' else '' end +
		case when a.CASE_MARK_2 is not null then a.CASE_MARK_2+'<br/>' else '' end +
		case when a.CASE_MARK_3 is not null then a.CASE_MARK_3+'<br/>' else '' end +
		case when a.CASE_MARK_4 is not null then a.CASE_MARK_4+'<br/>' else '' end +
		case when a.CASE_MARK_5 is not null then a.CASE_MARK_5+'<br/>' else '' end +
		case when a.CASE_MARK_6 is not null then a.CASE_MARK_6+'<br/>' else '' end +
		case when a.CASE_MARK_7 is not null then a.CASE_MARK_7+'<br/>' else '' end +
		case when a.CASE_MARK_8 is not null then a.CASE_MARK_8+'<br/>' else '' end +
		case when a.CASE_MARK_9 is not null then a.CASE_MARK_9+'<br/>' else '' end +
		case when a.CASE_MARK_10 is not null then a.CASE_MARK_10 else '' end as C_MARK_RESULT,
		a.CASE_MARK_1,
		a.CASE_MARK_2,
		a.CASE_MARK_3,
		a.CASE_MARK_4,
		a.CASE_MARK_5,
		a.CASE_MARK_6,
		a.CASE_MARK_7,
		a.CASE_MARK_8,
		a.CASE_MARK_9,
		a.CASE_MARK_10,
		isnull(it.DATE_CODE_TYPE,'MM-YYYY') as DATE_CODE_TYPE, isnull(it.DATE_CODE_MONTH,0) as DATE_CODE_MONTH,
		isnull(zi.PALLET_CTN,0) as PALLET_CTN, isnull(zi.PALLET_PCS,0) as PALLET_PCS, 
		CAST(isnull(zi.PALLET_PCS,0)/isnull(zi.PALLET_CTN,0) as int) as CARTON_PCS
		from SO_DETAILS a
		inner join so_header b on a.so_no = b.SO_NO
		inner join item it on a.ITEM_NO=it.ITEM_NO
		left join unit un on a.UOM_Q = un.UNIT_CODE
		left join CURRENCY curr on b.CURR_CODE = curr.CURR_CODE
		left join ztb_item zi on a.item_no = zi.ITEM_NO
		where a.so_no='$so_no'
		order by line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);

		$it = "'".$items[$rowno]->ITEM_NO."'";
		$ln = "'".$items[$rowno]->LINE_NO."'";
		$pr = "'".$items[$rowno]->U_PRICE."'";
		$so = "'".$items[$rowno]->SO_NO."'";
		$dc = "'".$items[$rowno]->DATE_CODE_TYPE."'";
		$dm = "'".$items[$rowno]->DATE_CODE_MONTH."'";
		$pc = "'".$items[$rowno]->PALLET_CTN."'";
		$pp = "'".$items[$rowno]->PALLET_PCS."'";
		$cp = "'".$items[$rowno]->CARTON_PCS."'";

		$items[$rowno]->P_MARK = '<a href="javascript:void(0)" onclick="input_pallet('.$sts.','.$it.','.$ln.','.$so.')">SET</a>';
		$items[$rowno]->C_MARK = '<a href="javascript:void(0)" onclick="input_case('.$sts.','.$it.','.$ln.','.$so.')">SET</a>';
		$items[$rowno]->ACT_QTY = '<a href="javascript:void(0)" onclick="input_qty('.$sts.','.$it.','.$ln.','.$so.','.$dc.','.$dm.','.$cp.')">SET</a>';

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
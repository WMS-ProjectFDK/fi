<?php
	session_start();
	include("../../connect/conn.php");
	$result = array();
	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	$rowno=0;

	$rs = "select b.PRF_NO as PRF_NOMOR, b.LINE_NO, b.ITEM_NO, t.ITEM, t.DESCRIPTION, b.QTY, b.UOM_Q, u.UNIT, c.ESTIMATE_PRICE, 
	b.AMT, b.REQUIRE_DATE as REQUIRE_DATE, b.UPTO_DATE as UPTO_DATE, 
	b.REG_DATE as REG_DATE, b.OHSAS, b.REMAINDER_QTY, isnull(b.SUPPLIER_CODE,c.SUPPLIER_CODE) as SUPPLIER_CODE, s.COMPANY as SUPPLIER_NAME, 
	isnull(b.CURR_CODE,c.CURR_CODE) as CURR_CODE, v.CURR_MARK, v.CURR_SHORT, b.CONFIRM_DATE as CONFIRM_DATE, b.CONFIRM_PERSON_CODE, 
	b.DELETE_DATE as DELETE_DATE, b.DELETE_PERSON_CODE, a.prf_date as prf_date,
	case when b.SUPPLIER_CODE is null then -1 when b.CONFIRM_DATE  is null then 0 else 1 end as CONFIRMED,t.origin_code,cou.country 
	from PRF_DETAILS b , PRF_HEADER a, COMPANY s, ITEM t, UNIT u, CURRENCY v, country cou, ITEMMAKER c
	where b.PRF_NO = a.PRF_NO and b.ITEM_NO = t.ITEM_NO  and c.SUPPLIER_CODE = s.COMPANY_CODE  and b.UOM_Q = u.UNIT_CODE  and 
	t.item_no = c.item_no  and c.CURR_CODE = v.CURR_CODE  and t.origin_code = cou.country_code  and
	a.APPROVAL_DATE is not null and b.REMAINDER_QTY > 0  and c.SUPPLIER_CODE = '$supp' and b.DELETE_DATE is null and
	b.require_date >= (select getdate()-14 ) and (c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = t.ITEM_NO ))
	order by b.ITEM_NO, b.REQUIRE_DATE, b.PRF_NO, b.LINE_NO, b.SUPPLIER_CODE";
	$data = sqlsrv_query($connect, $rs);
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty,2);
		$rmd = $items[$rowno]->REMAINDER_QTY;
		$items[$rowno]->REMAINDER_QTY = number_format($rmd,2);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
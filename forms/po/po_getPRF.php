<?php
	session_start();
	include("../../connect/conn.php");
	$result = array();
	$supp = isset($_REQUEST['supp']) ? strval($_REQUEST['supp']) : '';
	$rowno=0;

	$rs = "select b.PRF_NO as PRF_NOMOR, b.LINE_NO, b.ITEM_NO, t.ITEM, t.DESCRIPTION, b.QTY, b.UOM_Q, u.UNIT, c.ESTIMATE_PRICE, b.AMT, 
		format(b.REQUIRE_DATE, 'yyyy-MM-dd') as REQUIRE_DATE, format(b.UPTO_DATE, 'hh:mm:ss yyyy-MM-dd') as UPTO_DATE, 
		format(b.REG_DATE, 'hh:mm:ss yyyy-MM-dd') as REG_DATE, b.OHSAS, b.REMAINDER_QTY, coalesce(b.SUPPLIER_CODE,c.SUPPLIER_CODE) as SUPPLIER_CODE, 
		s.COMPANY as SUPPLIER_NAME, coalesce(b.CURR_CODE,c.CURR_CODE) as CURR_CODE, v.CURR_MARK, v.CURR_SHORT, 
		format(b.CONFIRM_DATE, 'yyyy-MM-dd') as CONFIRM_DATE, b.CONFIRM_PERSON_CODE, 
		format(b.DELETE_DATE, 'yyyy-MM-dd') as DELETE_DATE, b.DELETE_PERSON_CODE, format(a.prf_date, 'yyyy-MM-dd') as prf_date,
		case when b.SUPPLIER_CODE is null then -1 when b.CONFIRM_DATE  is null then 0 else 1 end as CONFIRMED,t.origin_code,cou.country 
		from PRF_DETAILS b
		inner join PRF_HEADER a on a.PRF_NO=b.prf_no
		left join ITEM t on b.item_no=t.ITEM_NO 
		left join ITEMMAKER c on t.ITEM_NO=c.ITEM_NO
		left join COMPANY s on c.SUPPLIER_CODE=s.COMPANY_CODE
		left join UNIT u on b.uom_q=u.UNIT_CODE 
		left join CURRENCY v on c.CURR_CODE=v.CURR_CODE 
		left join country cou on t.ORIGIN_CODE=cou.COUNTRY_CODE
		--where a.APPROVAL_DATE is not null and b.REMAINDER_QTY > 0 
		--and c.SUPPLIER_CODE = $supp and b.DELETE_DATE is null 
		--and b.require_date >= (select getdate()-14) 
		--and (c.ITEM_NO is null or c.ALTER_PROCEDURE = (select min(ALTER_PROCEDURE) from ITEMMAKER where ITEM_NO = t.ITEM_NO ))
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
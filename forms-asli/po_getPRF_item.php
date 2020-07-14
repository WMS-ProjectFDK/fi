<?php
	session_start();
	$result = array();

	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select * from (select b.PRF_NO, b.LINE_NO as PRF_LINE_NO, b.ITEM_NO, t.ITEM as ITEM_NAME, t.ITEM as ITEM_DESCRIPTION, b.REMAINDER_QTY, 
					to_char(b.REQUIRE_DATE, 'ddmmyyyy') as REQUIRE_DATE, case when b.DELETE_DATE is not null  then 'DELETED' when b.CONFIRM_DATE is not null then 'CONFIRMED'
					else '' end as STATUS
		  			from PRF_DETAILS b
			 		inner join PRF_HEADER a on b.prf_no=a.prf_no
			 		left join ITEM t on b.item_no=t.ITEM_NO
		 			where b.ITEM_NO = $item and a.APPROVAL_DATE is not null and b.REMAINDER_QTY > 0
		 			order by PRF_NO, PRF_LINE_NO
   				)
			where rownum <= 100";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rmd = $items[$rowno]->REMAINDER_QTY;
		$items[$rowno]->REMAINDER_QTY = number_format($rmd);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
<?php
	session_start();
	$result = array();

	$cutoff_date = isset($_REQUEST['cutoff_date']) ? strval($_REQUEST['cutoff_date']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select distinct a.item_no, b.description, sum (a.qty-a.qty_out) as total, c.unit from ztb_wh_in_det a 
		inner join item b on a.item_no=b.item_no 
		inner join unit c on b.uom_q=c.unit_code
		inner join gr_header d on a.gr_no=d.gr_no
		where a.rack is not null and a.qty-a.qty_out > 0 and to_date(d.gr_date,'YYYY-MM-DD') <= to_date('$cutoff_date','YYYY-MM-DD')
		group by a.item_no, b.description, c.unit
		order by b.description asc";
	/*,to_char(to_date(a.tanggal,'YYYYMMDD'),'YYYY-MM-DD') as RECEIVE_DATE*/
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$TOTAL = $items[$rowno]->TOTAL;
		$items[$rowno]->TOTAL = number_format($TOTAL);
		$items[$rowno]->CUTOFF = $cutoff_date;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
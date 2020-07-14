<?php
	session_start();
	$result = array();

	$item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
	$tgl = isset($_REQUEST['tgl']) ? strval($_REQUEST['tgl']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select a.id, a.gr_no, a.line_no,coalesce(a.rack,'-') as rack, a.pallet, a.qty-a.qty_out as qty, a.id,b.description, 
		coalesce(c.warehouse,'-') as warehouse, d.gr_date, a.item_no from ztb_wh_in_det a 
		left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack 
		left join gr_header d on a.gr_no=d.gr_no
		where a.qty - a.qty_out > 0 and rack is not null and a.item_no='$item_no' and to_date(substr(a.tanggal, 0, 8),'YYYY=MM-DD') <= to_date('$tgl','YYYY-MM-DD')
		order by d.gr_date asc, a.pallet asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$TOTAL = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($TOTAL);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
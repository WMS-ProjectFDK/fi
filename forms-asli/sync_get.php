<?php
	include("../connect/conn.php");
	ini_set('max_execution_time', -1);
	session_start();
	$result = array();
	$items = array();
	$rowno=0;

	$rs = "select aa.*, it.description from (
		select a.id_no,to_date(a.tanggal,'yyyymmdd') as tanggal, a.type,
		case when a.type = '1' then 'INCOMING' when a.type = '2' THEN 'OUTGOING' else 'CHANGE RACK' end as name_type,
		a.id, a.flag, a.rack,
		case when a.document is null or a.document = 'x' then b.gr_no else a.document end as doc,
		case when a.line is null or a.line = 'x' then b.line_no else a.line end as line,
		case when a.item is null or a.item = 'x' then b.item_no else a.item end as item,
		case when a.pallet is null or a.pallet = 'x' then to_char(b.pallet) else a.pallet end as pallet,
		case when a.qty is null or a.qty = 'x' then to_char(b.qty) else a.qty end as qty
		from ztb_wh_trans a
		left join ztb_wh_in_det b on a.id_no = b.id
		where flag=0
		) aa 
		left join item it on aa.item = to_char(it.item_no)
		order by aa.type asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
<?php
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");

$cmb_week = isset($_REQUEST['cmb_week']) ? strval($_REQUEST['cmb_week']) : '';
$ck_week = isset($_REQUEST['ck_week']) ? strval($_REQUEST['ck_week']) : '';
$cmb_fg = isset($_REQUEST['cmb_fg']) ? strval($_REQUEST['cmb_fg']) : '';
$ck_fg = isset($_REQUEST['ck_fg']) ? strval($_REQUEST['ck_fg']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_week != "true"){
	$jumN = $cmb_week * 7 ;
	$week = " item_no in (select distinct item_no from mps_header r
		inner join mps_details s on r.po_no = s.po_no and r.po_line_no = s.po_line_no
		where mps_date between (select sysdate from dual) and (select sysdate + $jumN from dual) and mps_qty > 0
		) and ";
}else{
	$jumN='';	$week='';
}

if ($ck_fg != "true"){
	$fg = "item_no = $cmb_fg and ";
}else{
	$fg = " ";
}

if($ck_item != "true") {
	$item = "item_no in (select distinct upper_item_no from structure where lower_item_no=$cmb_item) and ";
}else{
	$item = " ";
}

$where = " where $fg $item $week level_no is not null";
$cek = "
	select * from (
		select distinct a.item_no, i.description as item_name, nvl(wh.this_inventory,0) as this_inventory, st.level_no
		from mps_header a
		inner join (select distinct po_no, po_line_no, nvl(sum(mps_qty),0) as qty from ztb_mps_details group by po_no, po_line_no) b 
		on a.po_no=b.po_no and a.po_line_no = b.po_line_no
		left outer join whinventory wh on a.item_no = wh.item_no
		left join (select upper_item_no, max(level_no) as level_no from structure group by upper_item_no) st on a.item_no = st.upper_item_no
		left join item i on a.item_no = i.item_no
	)
	$where
	order by item_name asc" ;

$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);

$items = array();
$rowno=0;

while($row = oci_fetch_object($data_cek)){
	array_push($items, $row);
	$inv = $items[$rowno]->THIS_INVENTORY;
	$items[$rowno]->THIS_INVENTORY = number_format($inv);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
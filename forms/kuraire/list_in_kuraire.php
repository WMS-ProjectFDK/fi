<?php
error_reporting(0);
set_time_limit(0);
ini_set('memory_limit', '-1');
include("../../connect/conn.php");
 
$sql = "select slip_no, slip_date, to_char(to_date(remark2, 'FmMM/DD/YYYY HH:MI:SS AM'),'YYYY-MM-DD HH24:MI:SS') as scan_time, 
	item_no, item_name, item_description, approval_date, slip_quantity, wo_no, plt_no, scan_sts
	from
	    (
	      select slip_no, slip_date, remark2, item_no, item_name, item_description, approval_date, 
	      slip_quantity, wo_no, plt_no, scan_sts
	      from
	          (
	            select a.slip_no, a.slip_date, a.remark2, a.item_no, a.item_name, a.item_description, 
	            a.approval_date, a.slip_quantity, a.wo_no, c.plt_no, nvl(to_char(b.date_in),'BELUM DI SCAN') as scan_sts
	            from production_income a
	            left join (select slip_no, date_in from ztb_wh_kanban_trans_fg) b on a.slip_no = b.slip_no
	            left join (select to_char(id) as id, wo_no, plt_no from ztb_p_plan) c on a.slip_no = c.id
	            order by slip_date DESC
	          )
	          where rownum <= 20
	    ) 
	order by to_date(remark2, 'FmMM/DD/YYYY HH:MI:SS AM') desc";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->SLIP_QUANTITY;
	$items[$rowno]->SLIP_QUANTITY = number_format($q);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
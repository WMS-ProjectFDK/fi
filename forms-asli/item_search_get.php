<?php
	session_start();
	$result = array();
	
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

	if($item=="ALL"){
		$where = "where a.qty - a.qty_out > 0 and rack is not null";
	}elseif($item=="ALL_WHCC"){
		$wh = "WH-CATHODE CAN";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}elseif($item=="ALL_WHRM"){
		$wh = "WH-RAW MATERIAL";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}elseif($item=="ALL_WHSP"){
		$wh = "WH-SEPARATOR";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}elseif($item=="ALL_WHFM"){
		$wh = "WH-FLAMMABLE";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}elseif($item=="ALL_WHNP"){
		$wh = "WH-NPS";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}elseif($item=="ALL_WHCR"){
		$wh = "WH-AREA CORIDOR";
		$where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
	}else{
		$where = "where a.qty - a.qty_out > 0 and a.item_no = '$item' and rack is not null";
	}

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select a.gr_no, a.line_no,coalesce(a.rack,'-') as rack, a.pallet, a.qty-a.qty_out as qty, a.id,b.description, coalesce(c.warehouse,'-') as warehouse, d.gr_date, a.id, a.item_no  from ztb_wh_in_det a 
		left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack left join gr_header d on a.gr_no = d.gr_no 
		$where order by d.gr_date asc, a.pallet asc, rack asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		$qty = intval($row->QTY);
		array_push($items, $row);
		$items[$rowno]->QTY = number_format($qty);
		/*$items[$rowno]->RACK = 'C04.1';*/
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
<?php
	session_start();
	$result = array();
	
	$slip = isset($_REQUEST['slip']) ? strval($_REQUEST['slip']) : '';
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$total = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	$ln = isset($_REQUEST['ln']) ? strval($_REQUEST['ln']) : '';

	include("../connect/conn.php");

	$cek = "select distinct rack from ztb_wh_in_det where item_no='$item' and rack is not null";
	$cek_rack = oci_parse($connect, $cek);
	oci_execute($cek_rack);
	$row_rack = oci_fetch_array($cek_rack);

	$r = substr($row_rack[0],0,4);

	if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
		$rowno=0;
		$rs1 = "select a.gr_no, a.line_no, a.rack, a.item_no, a.tanggal, a.pallet, a.qty - a.qty_out as QTY, a.id, a.item_no
			from ztb_wh_in_det a inner join gr_header b on a.gr_no= b.gr_no 
			where a.item_no = '$item' and a.qty - a.qty_out > 0 and a.rack is not null 
			order by b.gr_date, a.pallet asc";
		$data1 = oci_parse($connect, $rs1);
		oci_execute($data1);
		$items = array();
		while($row1 = oci_fetch_object($data1)) {	
			$qty = intval($row1->QTY);
			if($total>0){
				$t = $total - $qty;
				$plt = $row1->PALLET;
				if($t<0){
					array_push($items, $row1);
					$items[$rowno]->QTY = number_format($total);
					$items[$rowno]->PALLET = $pll;
				}else{
					array_push($items, $row1);
					//$items[$rowno]->RACK = $rak;
					$items[$rowno]->PALLET = $pll;
					$forQTY = $items[$rowno]->QTY;
					$items[$rowno]->QTY = number_format($forQTY);
				}
			}
			$total -= $qty;
			$rowno++;	
		}
	}else{
		$rowno=0;
		$rs = "select a.gr_no, a.line_no, a.rack, a.item_no, a.tanggal, a.pallet, a.qty- a.qty_RESERVE as QTY, a.id, a.item_no
			from ztb_wh_in_det a inner join gr_header b on a.gr_no= b.gr_no
			where a.item_no = '$item' and a.qty - a.qty_RESERVE > 0 and a.rack is not null
			order by b.gr_date, a.pallet asc";
		$data = oci_parse($connect, $rs);
		oci_execute($data);
		$items = array();
		while($row = oci_fetch_object($data)) {
			$qty = intval($row->QTY);
			if($total>0){
				$t = $total - $qty;
				
				if($t<0){
					array_push($items, $row);
					$items[$rowno]->QTY = number_format($total);
				}else{
					array_push($items, $row);
					$forQTY = $items[$rowno]->QTY;
					$items[$rowno]->QTY = number_format($forQTY);
				}
			}
			$total -= $qty;
			$rowno++;
		}
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
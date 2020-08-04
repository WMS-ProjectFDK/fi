<?php
	session_start();
	$result = array();

	$req = isset($_REQUEST['req']) ? strval($_REQUEST['req']) : '';

	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select a.slip_no, b.line_no, b.item_no, c.description,b.qty,b.uom_q,d.unit,0 as sts, e.this_inventory as stock from mte_header a
		inner join mte_details b on a.slip_no=b.slip_no 
		left join item c on b.item_no=c.item_no
		left join unit d on b.uom_q=d.unit_code 
		left join whinventory e on b.item_no = e.item_no
		where a.slip_no='$req' order by b.line_no asc";
	$data = sqlsrv_query($connect, strtoupper($rs));
	
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);

		$stock = $items[$rowno]->STOCK;
		$items[$rowno]->STOCK = number_format($stock);

		$kumpul_rack= array();
		$no_rack = 0;

		$hsl_rack= array();
		$pallet_rack = 0;

		$slip = $items[$rowno]->SLIP_NO;
		$item = $items[$rowno]->ITEM_NO;

		$cek_rack = "select distinct RACK from ztb_wh_out_det where slip_no='$slip' and item_no='$item'";
		$data_cek_rack = sqlsrv_query($connect, $cek_rack);
		
		while($dt_rack = sqlsrv_fetch_object($data_cek_rack)) {
			$rack = $dt_rack->RACK;
			
			$cek_pallet = "select distinct PALLET from ztb_wh_out_det where slip_no='$slip' and item_no='$item' and rack='$rack'";
			$data_cek_pallet = sqlsrv_query($connect, $cek_pallet);
			
			while ($dt_pallet = sqlsrv_fetch_object($data_cek_pallet)){
				array_push($kumpul_rack, $dt_pallet);
				$plt = $kumpul_rack[$no_rack]->PALLET;
				$hsl = $rack. " (pallet: ".$plt.")<br/>";
				array_push($hsl_rack, $hsl);
				$no_rack++;
			}
		}
		$items[$rowno]->RACK = str_replace(',','', $hsl_rack);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
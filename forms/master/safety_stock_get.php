<?php
	session_start();
	include("../../connect/conn.php");

	$ss_bulan = isset($_REQUEST['ss_bulan']) ? strval($_REQUEST['ss_bulan']) : '';
	$ss_tahun = isset($_REQUEST['ss_tahun']) ? strval($_REQUEST['ss_tahun']) : '';
	$ss_cdate = isset($_REQUEST['ss_cdate']) ? strval($_REQUEST['ss_cdate']) : '';
	$ss_nitem = isset($_REQUEST['ss_nitem']) ? strval($_REQUEST['ss_nitem']) : '';
	$ss_citem = isset($_REQUEST['ss_citem']) ? strval($_REQUEST['ss_citem']) : '';
	$ss_nstat = isset($_REQUEST['ss_nstat']) ? strval($_REQUEST['ss_nstat']) : '';
	$ss_cstat = isset($_REQUEST['ss_cstat']) ? strval($_REQUEST['ss_cstat']) : '';
	
	if ($ss_citem != "true"){
		$item_no = "a.item_no = $ss_nitem and ";
	}else{
		$item_no = "";
	}

	if ($ss_cdate != "true"){
		$date = "a.period = $ss_bulan AND year = '$ss_tahun' and ";
	}else{
		$date = "";
	}

	if ($ss_cstat != "true"){
		if ($ss_nstat==1){
			$state = 'a.qty-nvl(c.this_inventory,0) < 0 and ';
		}else{
			$state = 'a.qty-nvl(c.this_inventory,0) >= 0 and ';
		}
	}else{
		$state = "";
	}

	$where = "where $item_no $date $state a.YEAR <> 'MSTR' and a.item_no is not null";

	$sql = "select top 200 a.item_no, b.item, b.description, a.period, a.year, a.qty, 
		coalesce(c.this_inventory,0) as this_inventory, a.qty-coalesce(c.this_inventory,0) as balance,
		case when a.qty-coalesce(c.this_inventory,0) < 0 then 'SAFETY STOCK' else 'ORDERED' end sts,
		case when a.sts_bundle = 'N' then 'NOT BUNDLE' else 'BUNDLE (@'+CAST(a.BUNDLE_QTY as varchar(15))+' pcs)' end sts_bundle
		from ztb_safety_stock a
		left join item b on a.item_no=b.item_no
		left outer join whinventory c on a.item_no = c. item_no
		$where
		order by cast(a.year as int) desc, a.period desc, upload desc";
	$data_sql = sqlsrv_query($connect, $sql);
	$bln = array('-','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DES');
	$items = array();
	$rowno = 0;
	while($row = sqlsrv_fetch_object($data_sql)){
		array_push($items, $row);

		$qty = $items[$rowno]->qty;
		$items[$rowno]->qty = number_format($qty);

		$inv = $items[$rowno]->this_inventory;
		$items[$rowno]->this_inventory = number_format($inv);

		$p = intval($items[$rowno]->period);
		$items[$rowno]->period = $bln[$p];

		$s = $items[$rowno]->sts; 
		if($s == 'SAFETY STOCK'){
			$items[$rowno]->sts = '<span style="color:blue;font-size:11px;"><b>SAFETY STOCK</b></span>';
		}else{
			$items[$rowno]->sts = '<span style="color:red;font-size:11px;"><b>ORDERED</b></span>';
		}

		$sb = $items[$rowno]->sts_bundle;
		$items[$rowno]->sts_bundle = '<span style="color:black;font-size:11px;"><b>'.$sb.'</b></span>';

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
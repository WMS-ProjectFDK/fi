<?php
	session_start();
	include("../connect/conn.php");

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

	$where = "where $date $item_no $state a.item_no is not null";

	$sql = "select item_no, item, description, period, year, qty, this_inventory, balance,
		case when balance < 0 then 'SAFETY STOCK' else 'ORDERED' end sts,
		case when sts_bundle = 'N' then 'NOT BUNDLE' else 'BUNDLE (@ '|| bundle_qty || ' pcs)' end sts_bundle
		from (
		select a.item_no, b.item, b.description, a.period, a.year, a.qty, 
			nvl(c.this_inventory,0) as this_inventory, a.qty-nvl(c.this_inventory,0) as balance,
			a.sts_bundle, a.bundle_qty
		    from ztb_safety_stock a
			inner join item b on a.item_no=b.item_no
		    left outer join whinventory c on a.item_no = c. item_no
		    $where
		    order by a.period desc, upload desc
		)where rownum <=200";
	$data_sql = oci_parse($connect, $sql);
	oci_execute($data_sql);
	$bln = array('-','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DES');
	$items = array();
	$rowno = 0;
	while($row = oci_fetch_object($data_sql)){
		array_push($items, $row);

		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);

		$inv = $items[$rowno]->THIS_INVENTORY;
		$items[$rowno]->THIS_INVENTORY = number_format($inv);

		$p = intval($items[$rowno]->PERIOD);
		$items[$rowno]->PERIOD = $bln[$p];

		$s = $items[$rowno]->STS; 
		if($s == 'SAFETY STOCK'){
			$items[$rowno]->STS = '<span style="color:blue;font-size:11px;"><b>SAFETY STOCK</b></span>';
		}else{
			$items[$rowno]->STS = '<span style="color:red;font-size:11px;"><b>ORDERED</b></span>';
		}

		$sb = $items[$rowno]->STS_BUNDLE;
		$items[$rowno]->STS_BUNDLE = '<span style="color:black;font-size:11px;"><b>'.$sb.'</b></span>';

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
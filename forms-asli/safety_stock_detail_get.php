<?php
	session_start();
	$Bulan = isset($_REQUEST['Bulan']) ? strval($_REQUEST['Bulan']) : '';
	$Tahun = isset($_REQUEST['Tahun']) ? strval($_REQUEST['Tahun']) : '';
	

	
	
	include("../connect/conn.php");

	$sql = "select z.item_no,
	  			   item.description,
	  			   period,
	  			   qty,
	  			   qty_2,
	  			   qty_3,
	  			   upload,
	  			   ceil(qty_2/qty * 100) persen2,
	  			   ceil(qty_3/qty_2 * 100) persen3,
	  			   w.this_inventory, 
	  			   ceil((qty - this_inventory)/bundle_qty)*bundle_qty PurchaseQty,
	  			   ceil((qty - this_inventory)/bundle_qty)*bundle_qty PurchaseQty2
	  			   from ztb_safety_stock_1 z
	  			   	   ,item
	  			   	   ,whinventory w
	  			   	   ,(select qty qty_2,item_no from ztb_safety_stock_1 where period = $Bulan + 1) z2
	  			   	   , (select qty qty_3,item_no from ztb_safety_stock_1 where period = $Bulan + 2) z3
	  			   where z.item_no = item.item_no  and z.item_no= w.item_no  and z.item_no = z2.item_no and z.item_no = z3.item_no
	  			         and upload = '1000' and period = $Bulan ";

	
    //echo $sql;

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);

		

		 $q = $items[$rowno]->QTY;
		 $items[$rowno]->QTY = number_format($q);

		 $q2 = $items[$rowno]->QTY_2;
		 if ($q < $q2){
		 	 $items[$rowno]->QTY_2 = '<p>'.number_format($q2).' <sub style="color:Tomato;"> ( &uarr; '. $items[$rowno]->PERSEN2 .'% )</sub></p>';//number_format($q2);
		 }else{
		 	 $items[$rowno]->QTY_2 = '<p>'.number_format($q2).' <sub style="color:DodgerBlue;"> ( &darr; '. $items[$rowno]->PERSEN2 .'% )</sub></p>';//number_format($q2);
		 };

		  $q3 = $items[$rowno]->QTY_3;
		 if ($q2 < $q3){
		 	 $items[$rowno]->QTY_3 = '<p>'.number_format($q3).' <sub style="color:Tomato;"> ( &uarr; '. $items[$rowno]->PERSEN3 .'% )</sub></p>';//number_format($q2);
		 }else{
		 	 $items[$rowno]->QTY_3 = '<p>'.number_format($q3).' <sub style="color:DodgerBlue;"> ( &darr; '. $items[$rowno]->PERSEN3 .'% )</sub></p>';//number_format($q2);
		 };
		

		 
		 
		 
		 $t = $items[$rowno]->THIS_INVENTORY;
		 $items[$rowno]->THIS_INVENTORY = number_format($t);


		 $s = $items[$rowno]->PURCHASEQTY *-1;
		 if ($s > 0) {
		 		$items[$rowno]->PURCHASEQTY = '<p style="color:DodgerBlue;"> '.number_format($s).' <sub style="color:DodgerBlue;"> (Safe) </sub></p>';

		 }else {
		 		$items[$rowno]->PURCHASEQTY = '<p style="color:Tomato;">'.number_format($s).'<sub style="color:Tomato;"> (Purchase) </sub></p></p>';
		 };
		 
		//$items[$rowno]->QTY = $q;
		//$e = $items[$rowno]->QTY_PRODUKSI;
		//$items[$rowno]->QTY_PRODUKSI = $e;//'<a href="javascript:void(0)" title="'.$e.'"   style="text-decoration: none; color: black;">'number_format($e).'</a>';

		// $e = $items[$rowno]->QTY_PRODUKSI;
		// $items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; color: black;">'.number_format($e).'</a>';

		// $f = $items[$rowno]->QTY_INVOICED;
		// $items[$rowno]->QTY_INVOICED = '<a href="javascript:void(0)" title="'.$f.'" onclick="info_invoiced('.$w.')"  style="text-decoration: none; color: black;">'.number_format($f).'</a>';
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
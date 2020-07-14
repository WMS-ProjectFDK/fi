<?php
	session_start();
	include("../connect/conn.php");

	$cmbR = isset($_REQUEST['cmbR']) ? strval($_REQUEST['cmbR']) : '';
	$rdJn = isset($_REQUEST['rdJn']) ? strval($_REQUEST['rdJn']) : '';
	$dt_A = isset($_REQUEST['dt_A']) ? strval($_REQUEST['dt_A']) : '';
	$dt_Z = isset($_REQUEST['dt_Z']) ? strval($_REQUEST['dt_Z']) : '';
	$ck_s = isset($_REQUEST['ck_s']) ? strval($_REQUEST['ck_s']) : '';
	$ck_m = isset($_REQUEST['ck_m']) ? strval($_REQUEST['ck_m']) : '';

	//?cmbR=1&rdJn=check_bl&dt_A=2018-01-01&dt_Z=2018-01-31&ck_s=true

	//?cmbR=1
	//&rdJn=check_bl
	//&dt_A=2018-01-01
	//&dt_Z=2018-01-31
	//&ck_s=true

	if ($ck_s != "true"){
		$sample = "nvl(customer,'xx')  <> '14. ITEM SAMPLE' and ";
	}

	if ($ck_m != "true"){
		$material = "nvl(customer,'xx')  <> '15. MATERIAL SELLING' and ";
	}

	if ($rdJn == 'check_eta'){
		$dt = "to_char(etd,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
	}elseif($rdJn == 'check_bl'){
		$dt = "to_char(bl_date,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
	}elseif ($rdJn == 'check_xfact'){
		$dt = "to_char(ex_fact,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
	}elseif ($rdJn == 'check_sales'){
		$dt = "(to_char( case when trade_term like '%LOCAL%' or trade_term like '%FCA%' then ex_fact else etd end,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' or to_char(bl_date,'YYYY-MM-DD') between '$dt_A' and '$dt_Z') and ";
	}
	
	if ($cmbR == 1){
		$where =" where $dt $sample $material qty is not null ";
		$sql = "select type_batery, sum(qty) as Quantity, sum(qty*u_price) as amount 
		        from zvw_sales_report $where
				group by type_batery";
	}elseif($cmbR == 2){
		$where =" where $dt $sample $material qty is not null ";	
		$sql = "select customer, sum(qty) as Quantity, sum(qty*u_price) as amount
				from zvw_sales_report $where
				group by customer";
	}elseif($cmbR == 3){
		$where =" where $dt $sample $material qty is not null ";
		$sql = "select * from Zvw_SALES_REPORT $where";
	}

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$foot = array();
	$rowno=0;
	$TOT_Q = 0 ;		$TOT_A = 0 ;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		if ($cmbR == 1){
			$q = $items[$rowno]->QUANTITY;
			$items[$rowno]->QUANTITY = number_format($q);
			$TOT_Q += floatval($q);
			$a = $items[$rowno]->AMOUNT;
			$items[$rowno]->AMOUNT = number_format($a,2);
			$TOT_A += floatval($a);
		}elseif($cmbR == 2){
			$q = $items[$rowno]->QUANTITY;
			$items[$rowno]->QUANTITY = number_format($q);
			$TOT_Q += floatval($q);
			$a = $items[$rowno]->AMOUNT;
			$items[$rowno]->AMOUNT = number_format($a,2);
			$TOT_A += floatval($a);
		}elseif($cmbR == 3){
			$q = $items[$rowno]->QTY;
			$items[$rowno]->QTY = number_format($q);
			$TOT_Q += floatval($q);
			$a = $items[$rowno]->AMOUNT;
			$items[$rowno]->AMOUNT = number_format($a,2);
			$TOT_A += floatval($a);
		}		
		$rowno++;
	}

	$foot[0]->TYPE_BATERY = '<span style="color:blue;font-size:12px;"><b>TOTAL</b></span>';
	$foot[0]->CUSTOMER = '<span style="color:blue;font-size:12px;"><b>TOTAL</b></span>';
	$foot[0]->QTY = '<span style="color:blue;font-size:12px;"><b>'.number_format($TOT_Q).'</b></span>';
	$foot[0]->QUANTITY = '<span style="color:blue;font-size:12px;"><b>'.number_format($TOT_Q).'</b></span>';
	$foot[0]->AMOUNT = '<span style="color:blue;font-size:12px;"><b>'.number_format($TOT_A,2).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;

	/*if ($cmbR == 1){
		$result["footer"] = $foot;
	}elseif($cmbR == 2){
		$result["footer"] = $foot;
	}elseif($cmbR == 3){

	}*/
	
	echo json_encode($result);
?>
<?php
    error_reporting(0);
	session_start();
	include("../../connect/conn.php");

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
    $sample = '';
    $material = '';

	if ($ck_s != "true"){
		$sample = "isnull(customer,'xx')  <> '14. ITEM SAMPLE' and ";
	}

	if ($ck_m != "true"){
		$material = "isnull(customer,'xx')  <> '15. MATERIAL SELLING' and ";
	}

	if ($rdJn == 'check_eta'){
		$dt = "etd between '$dt_A' and '$dt_Z' and ";
	}elseif($rdJn == 'check_bl'){
		$dt = "bl_date between '$dt_A' and '$dt_Z' and ";
	}elseif ($rdJn == 'check_xfact'){
		$dt = "ex_fact between '$dt_A' and '$dt_Z' and ";
	}elseif ($rdJn == 'check_sales'){
		$dt = "case when trade_term like '%LOCAL%' or trade_term like '%FCA%' then ex_fact else etd end between '$dt_A' and '$dt_Z' or bl_date between '$dt_A' and '$dt_Z') and ";
	}
	
	if ($cmbR == 1){
		$where =" where $dt $sample $material qty is not null ";
		$sql = "select type_batery, sum(qty) as Quantity, sum(qty*u_price) as amount 
		        from ZVW_SALES_REPORT $where
				group by type_batery";
	}elseif($cmbR == 2){
		$where =" where $dt $sample $material qty is not null ";	
		$sql = "select customer, sum(qty) as Quantity, sum(qty*u_price) as amount
				from ZVW_SALES_REPORT $where
				group by customer";
	}elseif($cmbR == 3){
		$where =" where $dt $sample $material qty is not null ";
        $sql = "select so_no, do_no, customer_po_no, item_no, item, description, type_Batery, qty, unit_pl, 
            curr_mark, u_price, amount, standard_price, final_destination, trade_term, port_loading, 
            CAST(etd as varchar(10)) as etd, CAST(eta as varchar(10)) as eta, Customer, company,
            CAST(bl_Date as varchar(10)) as bl_date, CAST(ex_fact as varchar(10)) as ex_fact
            from zvw_sales_report
            $where";
	}
    // echo $sql;
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$foot = array();
	$rowno=0;
	$TOT_Q = 0 ;		$TOT_A = 0 ;
	while($row = sqlsrv_fetch_object($data)){
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
    
	echo json_encode($result);
?>
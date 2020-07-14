<?php
	session_start();
	$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
	$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	include("../connect/conn.php");

	$cek = "select distinct(this_month) as month from whinventory";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);

	if($dt_cek->MONTH == $cmbBln){
		$sql = "select * from zvw_material_item_view_this";
	}else{
		$sql = "select * from zvw_material_item_view_last";
	}

	$target_qty = $arrayName = array("ANODE DISK" => "9200000",
								     "CATHODE CAN" => "18000000",
								     "CC ROD" => "23000000",
								     "CHEMICAL - OTHERS" => "0",
								     "EMD" => "200000",
								     "GASKET" => "23000000",
								     "GRAPHITE" => "22000",
								     "MAGIC INK" => "0",
								     "MAGIC PEN" => "0",
								     "METAL LABEL" => "0",
								     "NPS" => "9200",
								     "SEPARATOR" => "3800000",
								     "WASHER" => "9200000",
								     "ZINC POWDER" => "80000",
								     "SCRAP GEL LR" => "0",
								     "CCR AFTER PLATING" => "0",
								     "BATTERY AFTER WEIGHT CHECKER" => "0",
								     "SCRAP BLACK MIX LR" => "0",
								     "SCRAP NPS" => "0",
								     "A. DISK AFT DEGREASE" => "0"
								);

	$target_amt = $arrayName = array("ANODE DISK" => "28566",
								     "CATHODE CAN" => "264825",
								     "CC ROD" => "87400",
								     "CHEMICAL - OTHERS" => "0",
								     "EMD" => "380540",
								     "GASKET" => "115742",
								     "GRAPHITE" => "137206",
								     "MAGIC INK" => "0",
								     "MAGIC PEN" => "0",
								     "METAL LABEL" => "0",
								     "NPS" => "20286",
								     "SEPARATOR" => "112465",
								     "WASHER" => "7575",
								     "ZINC POWDER" => "326969",
								     "SCRAP GEL LR" => "0",
								     "CCR AFTER PLATING" => "0",
								     "BATTERY AFTER WEIGHT CHECKER" => "0",
								     "SCRAP BLACK MIX LR" => "0",
								     "SCRAP NPS" => "0",
								     "A. DISK AFT DEGREASE" => "0"
								);

	$m = $dt_cek->MONTH;

	$data = oci_parse($connect, $sql);
	oci_execute($data);	

	$items = array();
	$rowno=0;
	
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->MONTH = $cmbBln_txt;
		$item = $items[$rowno]->ITEM1;
		$last_month = $items[$rowno]->LASTMONTH;
		$last_month_amt = $items[$rowno]->LASTMONTHAMOUNT;
		$this_month = $items[$rowno]->THISMONTH;
		$this_month_amt = $items[$rowno]->THISMONTHAMOUNT;
		$act_month_amt = $items[$rowno]->ACTUALAMOUNT;

		if($target_amt[$item] == 0){
			$items[$rowno]->TARGET_QTY = number_format($target_qty[$item]);
			$items[$rowno]->TARGET_AMT = number_format($target_amt[$item]);
			$items[$rowno]->LASTMONTH = number_format($last_month,2);
			$items[$rowno]->LASTMONTHAMOUNT = number_format($last_month_amt,5);
			$items[$rowno]->THISMONTH = number_format($this_month,2);
			$items[$rowno]->THISMONTHAMOUNT = number_format($this_month_amt,5);
			$items[$rowno]->ACTUALAMOUNT = number_format($act_month_amt,5);
			$q1  = $items[$rowno]->TANGGAL1;				$items[$rowno]->TANGGAL1 = number_format($q1);
			$q2  = $items[$rowno]->TANGGAL2;				$items[$rowno]->TANGGAL2 = number_format($q2);
			$q3  = $items[$rowno]->TANGGAL3;				$items[$rowno]->TANGGAL3 = number_format($q3);
			$q4  = $items[$rowno]->TANGGAL4;				$items[$rowno]->TANGGAL4 = number_format($q4);
			$q5  = $items[$rowno]->TANGGAL5;				$items[$rowno]->TANGGAL5 = number_format($q5);
			$q6  = $items[$rowno]->TANGGAL6;				$items[$rowno]->TANGGAL6 = number_format($q6);
			$q7  = $items[$rowno]->TANGGAL7;				$items[$rowno]->TANGGAL7 = number_format($q7);
			$q8  = $items[$rowno]->TANGGAL8;				$items[$rowno]->TANGGAL8 = number_format($q8);
			$q9  = $items[$rowno]->TANGGAL9;				$items[$rowno]->TANGGAL9 = number_format($q9);
			$q10 = $items[$rowno]->TANGGALL0;				$items[$rowno]->TANGGALL0 = number_format($q10);
			$q11 = $items[$rowno]->TANGGALL1;				$items[$rowno]->TANGGALL1 = number_format($q11);
			$q12 = $items[$rowno]->TANGGALL2;				$items[$rowno]->TANGGALL2 = number_format($q12);
			$q13 = $items[$rowno]->TANGGALL3;				$items[$rowno]->TANGGALL3 = number_format($q13);
			$q14 = $items[$rowno]->TANGGALL4;				$items[$rowno]->TANGGALL4 = number_format($q14);
			$q15 = $items[$rowno]->TANGGALL5;				$items[$rowno]->TANGGALL5 = number_format($q15);
			$q16 = $items[$rowno]->TANGGALL6;				$items[$rowno]->TANGGALL6 = number_format($q16);
			$q17 = $items[$rowno]->TANGGALL7;				$items[$rowno]->TANGGALL7 = number_format($q17);
			$q18 = $items[$rowno]->TANGGALL8;				$items[$rowno]->TANGGALL8 = number_format($q18);
			$q19 = $items[$rowno]->TANGGALL9;				$items[$rowno]->TANGGALL9 = number_format($q19);
			$q20 = $items[$rowno]->TANGGAL20;				$items[$rowno]->TANGGAL20 = number_format($q20);
			$q21 = $items[$rowno]->TANGGAL21;				$items[$rowno]->TANGGAL21 = number_format($q21);
			$q22 = $items[$rowno]->TANGGAL22;				$items[$rowno]->TANGGAL22 = number_format($q22);
			$q23 = $items[$rowno]->TANGGAL23;				$items[$rowno]->TANGGAL23 = number_format($q23);
			$q24 = $items[$rowno]->TANGGAL24;				$items[$rowno]->TANGGAL24 = number_format($q24);
			$q25 = $items[$rowno]->TANGGAL25;				$items[$rowno]->TANGGAL25 = number_format($q25);
			$q26 = $items[$rowno]->TANGGAL26;				$items[$rowno]->TANGGAL26 = number_format($q26);
			$q27 = $items[$rowno]->TANGGAL27;				$items[$rowno]->TANGGAL27 = number_format($q27);
			$q28 = $items[$rowno]->TANGGAL28;				$items[$rowno]->TANGGAL28 = number_format($q28);
			$q29 = $items[$rowno]->TANGGAL29;				$items[$rowno]->TANGGAL29 = number_format($q29);
			$q30 = $items[$rowno]->TANGGAL30;				$items[$rowno]->TANGGAL30 = number_format($q30);
			$q31 = $items[$rowno]->TANGGAL31;				$items[$rowno]->TANGGAL31 = number_format($q31);
			$items[$rowno]->STS = "0";
		}elseif (floatval($act_month_amt)>floatval($target_amt[$item])){
			$items[$rowno]->TARGET_QTY = '<span style="color:red;font-size:12px;">'.number_format($target_qty[$item]).'</span>';
			$items[$rowno]->TARGET_AMT = '<span style="color:red;font-size:12px;">'.number_format($target_amt[$item]).'</span>';
			$items[$rowno]->LASTMONTH = '<span style="color:red;font-size:12px;">'.number_format($last_month,2).'</span>';
			$items[$rowno]->LASTMONTHAMOUNT = '<span style="color:red;font-size:12px;">'.number_format($last_month_amt,5).'</span>';
			$items[$rowno]->THISMONTH = '<span style="color:red;font-size:12px;">'.number_format($this_month,2).'</span>';
			$items[$rowno]->THISMONTHAMOUNT = '<span style="color:red;font-size:12px;">'.number_format($this_month_amt,5).'</span>';
			$items[$rowno]->ACTUALAMOUNT = '<span style="color:red;font-size:12px;">'.number_format($act_month_amt,5).'</span>';
			$q1  = $items[$rowno]->TANGGAL1;				$items[$rowno]->TANGGAL1 = '<span style="color:red;font-size:12px;">'.number_format($q1).'</span>';
			$q2  = $items[$rowno]->TANGGAL2;				$items[$rowno]->TANGGAL2 = '<span style="color:red;font-size:12px;">'.number_format($q2).'</span>';
			$q3  = $items[$rowno]->TANGGAL3;				$items[$rowno]->TANGGAL3 = '<span style="color:red;font-size:12px;">'.number_format($q3).'</span>';
			$q4  = $items[$rowno]->TANGGAL4;				$items[$rowno]->TANGGAL4 = '<span style="color:red;font-size:12px;">'.number_format($q4).'</span>';
			$q5  = $items[$rowno]->TANGGAL5;				$items[$rowno]->TANGGAL5 = '<span style="color:red;font-size:12px;">'.number_format($q5).'</span>';
			$q6  = $items[$rowno]->TANGGAL6;				$items[$rowno]->TANGGAL6 = '<span style="color:red;font-size:12px;">'.number_format($q6).'</span>';
			$q7  = $items[$rowno]->TANGGAL7;				$items[$rowno]->TANGGAL7 = '<span style="color:red;font-size:12px;">'.number_format($q7).'</span>';
			$q8  = $items[$rowno]->TANGGAL8;				$items[$rowno]->TANGGAL8 = '<span style="color:red;font-size:12px;">'.number_format($q8).'</span>';
			$q9  = $items[$rowno]->TANGGAL9;				$items[$rowno]->TANGGAL9 = '<span style="color:red;font-size:12px;">'.number_format($q9).'</span>';
			$q10 = $items[$rowno]->TANGGALL0;				$items[$rowno]->TANGGALL0 = '<span style="color:red;font-size:12px;">'.number_format($q10).'</span>';
			$q11 = $items[$rowno]->TANGGALL1;				$items[$rowno]->TANGGALL1 = '<span style="color:red;font-size:12px;">'.number_format($q11).'</span>';
			$q12 = $items[$rowno]->TANGGALL2;				$items[$rowno]->TANGGALL2 = '<span style="color:red;font-size:12px;">'.number_format($q12).'</span>';
			$q13 = $items[$rowno]->TANGGALL3;				$items[$rowno]->TANGGALL3 = '<span style="color:red;font-size:12px;">'.number_format($q13).'</span>';
			$q14 = $items[$rowno]->TANGGALL4;				$items[$rowno]->TANGGALL4 = '<span style="color:red;font-size:12px;">'.number_format($q14).'</span>';
			$q15 = $items[$rowno]->TANGGALL5;				$items[$rowno]->TANGGALL5 = '<span style="color:red;font-size:12px;">'.number_format($q15).'</span>';
			$q16 = $items[$rowno]->TANGGALL6;				$items[$rowno]->TANGGALL6 = '<span style="color:red;font-size:12px;">'.number_format($q16).'</span>';
			$q17 = $items[$rowno]->TANGGALL7;				$items[$rowno]->TANGGALL7 = '<span style="color:red;font-size:12px;">'.number_format($q17).'</span>';
			$q18 = $items[$rowno]->TANGGALL8;				$items[$rowno]->TANGGALL8 = '<span style="color:red;font-size:12px;">'.number_format($q18).'</span>';
			$q19 = $items[$rowno]->TANGGALL9;				$items[$rowno]->TANGGALL9 = '<span style="color:red;font-size:12px;">'.number_format($q19).'</span>';
			$q20 = $items[$rowno]->TANGGAL20;				$items[$rowno]->TANGGAL20 = '<span style="color:red;font-size:12px;">'.number_format($q20).'</span>';
			$q21 = $items[$rowno]->TANGGAL21;				$items[$rowno]->TANGGAL21 = '<span style="color:red;font-size:12px;">'.number_format($q21).'</span>';
			$q22 = $items[$rowno]->TANGGAL22;				$items[$rowno]->TANGGAL22 = '<span style="color:red;font-size:12px;">'.number_format($q22).'</span>';
			$q23 = $items[$rowno]->TANGGAL23;				$items[$rowno]->TANGGAL23 = '<span style="color:red;font-size:12px;">'.number_format($q23).'</span>';
			$q24 = $items[$rowno]->TANGGAL24;				$items[$rowno]->TANGGAL24 = '<span style="color:red;font-size:12px;">'.number_format($q24).'</span>';
			$q25 = $items[$rowno]->TANGGAL25;				$items[$rowno]->TANGGAL25 = '<span style="color:red;font-size:12px;">'.number_format($q25).'</span>';
			$q26 = $items[$rowno]->TANGGAL26;				$items[$rowno]->TANGGAL26 = '<span style="color:red;font-size:12px;">'.number_format($q26).'</span>';
			$q27 = $items[$rowno]->TANGGAL27;				$items[$rowno]->TANGGAL27 = '<span style="color:red;font-size:12px;">'.number_format($q27).'</span>';
			$q28 = $items[$rowno]->TANGGAL28;				$items[$rowno]->TANGGAL28 = '<span style="color:red;font-size:12px;">'.number_format($q28).'</span>';
			$q29 = $items[$rowno]->TANGGAL29;				$items[$rowno]->TANGGAL29 = '<span style="color:red;font-size:12px;">'.number_format($q29).'</span>';
			$q30 = $items[$rowno]->TANGGAL30;				$items[$rowno]->TANGGAL30 = '<span style="color:red;font-size:12px;">'.number_format($q30).'</span>';
			$q31 = $items[$rowno]->TANGGAL31;				$items[$rowno]->TANGGAL31 = '<span style="color:red;font-size:12px;">'.number_format($q31).'</span>';
			$items[$rowno]->STS = '<span style="color:red;font-size:14px;"><b>BAD</b></span>';
		}else{
			$items[$rowno]->TARGET_QTY = '<span style="color:blue;font-size:12px;">'.number_format($target_qty[$item]).'</span>';
			$items[$rowno]->TARGET_AMT = '<span style="color:blue;font-size:12px;">'.number_format($target_amt[$item]).'</span>';
			$items[$rowno]->LASTMONTH = '<span style="color:blue;font-size:12px;">'.number_format($last_month,2).'</span>';
			$items[$rowno]->LASTMONTHAMOUNT = '<span style="color:blue;font-size:12px;">'.number_format($last_month_amt,5).'</span>';
			$items[$rowno]->THISMONTH = '<span style="color:blue;font-size:12px;">'.number_format($this_month,2).'</span>';
			$items[$rowno]->THISMONTHAMOUNT = '<span style="color:blue;font-size:12px;">'.number_format($this_month_amt,5).'</span>';
			$items[$rowno]->ACTUALAMOUNT = '<span style="color:blue;font-size:12px;">'.number_format($act_month_amt,5).'</span>';
			$q1  = $items[$rowno]->TANGGAL1;				$items[$rowno]->TANGGAL1 = '<span style="color:blue;font-size:12px;">'.number_format($q1).'</span>';
			$q2  = $items[$rowno]->TANGGAL2;				$items[$rowno]->TANGGAL2 = '<span style="color:blue;font-size:12px;">'.number_format($q2).'</span>';
			$q3  = $items[$rowno]->TANGGAL3;				$items[$rowno]->TANGGAL3 = '<span style="color:blue;font-size:12px;">'.number_format($q3).'</span>';
			$q4  = $items[$rowno]->TANGGAL4;				$items[$rowno]->TANGGAL4 = '<span style="color:blue;font-size:12px;">'.number_format($q4).'</span>';
			$q5  = $items[$rowno]->TANGGAL5;				$items[$rowno]->TANGGAL5 = '<span style="color:blue;font-size:12px;">'.number_format($q5).'</span>';
			$q6  = $items[$rowno]->TANGGAL6;				$items[$rowno]->TANGGAL6 = '<span style="color:blue;font-size:12px;">'.number_format($q6).'</span>';
			$q7  = $items[$rowno]->TANGGAL7;				$items[$rowno]->TANGGAL7 = '<span style="color:blue;font-size:12px;">'.number_format($q7).'</span>';
			$q8  = $items[$rowno]->TANGGAL8;				$items[$rowno]->TANGGAL8 = '<span style="color:blue;font-size:12px;">'.number_format($q8).'</span>';
			$q9  = $items[$rowno]->TANGGAL9;				$items[$rowno]->TANGGAL9 = '<span style="color:blue;font-size:12px;">'.number_format($q9).'</span>';
			$q10 = $items[$rowno]->TANGGALL0;				$items[$rowno]->TANGGALL0 = '<span style="color:blue;font-size:12px;">'.number_format($q10).'</span>';
			$q11 = $items[$rowno]->TANGGALL1;				$items[$rowno]->TANGGALL1 = '<span style="color:blue;font-size:12px;">'.number_format($q11).'</span>';
			$q12 = $items[$rowno]->TANGGALL2;				$items[$rowno]->TANGGALL2 = '<span style="color:blue;font-size:12px;">'.number_format($q12).'</span>';
			$q13 = $items[$rowno]->TANGGALL3;				$items[$rowno]->TANGGALL3 = '<span style="color:blue;font-size:12px;">'.number_format($q13).'</span>';
			$q14 = $items[$rowno]->TANGGALL4;				$items[$rowno]->TANGGALL4 = '<span style="color:blue;font-size:12px;">'.number_format($q14).'</span>';
			$q15 = $items[$rowno]->TANGGALL5;				$items[$rowno]->TANGGALL5 = '<span style="color:blue;font-size:12px;">'.number_format($q15).'</span>';
			$q16 = $items[$rowno]->TANGGALL6;				$items[$rowno]->TANGGALL6 = '<span style="color:blue;font-size:12px;">'.number_format($q16).'</span>';
			$q17 = $items[$rowno]->TANGGALL7;				$items[$rowno]->TANGGALL7 = '<span style="color:blue;font-size:12px;">'.number_format($q17).'</span>';
			$q18 = $items[$rowno]->TANGGALL8;				$items[$rowno]->TANGGALL8 = '<span style="color:blue;font-size:12px;">'.number_format($q18).'</span>';
			$q19 = $items[$rowno]->TANGGALL9;				$items[$rowno]->TANGGALL9 = '<span style="color:blue;font-size:12px;">'.number_format($q19).'</span>';
			$q20 = $items[$rowno]->TANGGAL20;				$items[$rowno]->TANGGAL20 = '<span style="color:blue;font-size:12px;">'.number_format($q20).'</span>';
			$q21 = $items[$rowno]->TANGGAL21;				$items[$rowno]->TANGGAL21 = '<span style="color:blue;font-size:12px;">'.number_format($q21).'</span>';
			$q22 = $items[$rowno]->TANGGAL22;				$items[$rowno]->TANGGAL22 = '<span style="color:blue;font-size:12px;">'.number_format($q22).'</span>';
			$q23 = $items[$rowno]->TANGGAL23;				$items[$rowno]->TANGGAL23 = '<span style="color:blue;font-size:12px;">'.number_format($q23).'</span>';
			$q24 = $items[$rowno]->TANGGAL24;				$items[$rowno]->TANGGAL24 = '<span style="color:blue;font-size:12px;">'.number_format($q24).'</span>';
			$q25 = $items[$rowno]->TANGGAL25;				$items[$rowno]->TANGGAL25 = '<span style="color:blue;font-size:12px;">'.number_format($q25).'</span>';
			$q26 = $items[$rowno]->TANGGAL26;				$items[$rowno]->TANGGAL26 = '<span style="color:blue;font-size:12px;">'.number_format($q26).'</span>';
			$q27 = $items[$rowno]->TANGGAL27;				$items[$rowno]->TANGGAL27 = '<span style="color:blue;font-size:12px;">'.number_format($q27).'</span>';
			$q28 = $items[$rowno]->TANGGAL28;				$items[$rowno]->TANGGAL28 = '<span style="color:blue;font-size:12px;">'.number_format($q28).'</span>';
			$q29 = $items[$rowno]->TANGGAL29;				$items[$rowno]->TANGGAL29 = '<span style="color:blue;font-size:12px;">'.number_format($q29).'</span>';
			$q30 = $items[$rowno]->TANGGAL30;				$items[$rowno]->TANGGAL30 = '<span style="color:blue;font-size:12px;">'.number_format($q30).'</span>';
			$q31 = $items[$rowno]->TANGGAL31;				$items[$rowno]->TANGGAL31 = '<span style="color:blue;font-size:12px;">'.number_format($q31).'</span>';
			$items[$rowno]->STS = '<span style="color:blue;font-size:14px;"><b>GOOD</b></span>';
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
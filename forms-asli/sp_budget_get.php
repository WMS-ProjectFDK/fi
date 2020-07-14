<?php
	session_start();
	include("../connect/conn2.php");
	$tahun = isset($_REQUEST['tahun']) ? strval($_REQUEST['tahun']) : '';
	$items = array();
	$foot = array();
	$rowno=0;

	$cek = "select count(*) as j from (
		select DEPARTMENT,JANUARY,FEBRUARY,MARCH,APRIL,MAY,JUNE,JULY,AUGUST,AUGUST,SEPTEMBER,OCTOBER,NOVEMBER,DECEMBER 
		from ztb_sp_budget 
		where tahun = $tahun)";
	$dataJum = oci_parse($connect, $cek);
	oci_execute($dataJum);
	$rowJum = oci_fetch_object($dataJum);

	if (intval($rowJum->J) != 0) {	
		$sql = "select 'EDIT' as STS, DEPARTMENT,JANUARY,FEBRUARY,MARCH,APRIL,MAY,JUNE,JULY,AUGUST,AUGUST,SEPTEMBER,OCTOBER,NOVEMBER,DECEMBER from ztb_sp_budget where tahun = $tahun
			order by DEPARTMENT asc";
		$data = oci_parse($connect, $sql);
		oci_execute($data);

		$items = array();
		$foot = array();
		$rowno=0;

		$a_tot = 0;		$g_tot = 0;
		$b_tot = 0;		$h_tot = 0;
		$c_tot = 0;		$i_tot = 0;
		$d_tot = 0;		$j_tot = 0;
		$e_tot = 0;		$k_tot = 0;
		$f_tot = 0;		$l_tot = 0;

		while($row = oci_fetch_object($data)){
			array_push($items, $row);

			$a = $items[$rowno]->JANUARY;
			$a_tot += $a;
			$items[$rowno]->JANUARY = number_format($a);

			$b = $items[$rowno]->FEBRUARY;
			$b_tot += $b;
			$items[$rowno]->FEBRUARY = number_format($b);

			$c = $items[$rowno]->MARCH;
			$c_tot += $c;
			$items[$rowno]->MARCH = number_format($c);

			$d = $items[$rowno]->APRIL;
			$d_tot += $d;
			$items[$rowno]->APRIL = number_format($d);

			$e = $items[$rowno]->MAY;
			$e_tot += $e;
			$items[$rowno]->MAY = number_format($e);

			$f = $items[$rowno]->JUNE;
			$f_tot += $f;
			$items[$rowno]->JUNE = number_format($f);

			$g = $items[$rowno]->JULY;
			$g_tot += $g;
			$items[$rowno]->JULY = number_format($g);

			$h = $items[$rowno]->AUGUST;
			$h_tot += $h;
			$items[$rowno]->AUGUST = number_format($h);

			$i = $items[$rowno]->SEPTEMBER;
			$i_tot += $i;
			$items[$rowno]->SEPTEMBER = number_format($i);

			$j = $items[$rowno]->OCTOBER;
			$j_tot += $j;
			$items[$rowno]->OCTOBER = number_format($j);

			$k = $items[$rowno]->NOVEMBER;
			$k_tot += $k;
			$items[$rowno]->NOVEMBER = number_format($k);

			$l = $items[$rowno]->DECEMBER;
			$l_tot += $l;
			$items[$rowno]->DECEMBER = number_format($l);

			$rowno++;
		}
	}else {
		$d = array('ASSEMBLING','COMPONENT','PE','FINISHING','QC');
		for ($i=0; $i < count($d) ; $i++) { 
			$ArrNew[$i] = array ( 'STS' => 'NEW',
								  'DEPARTMENT' => $d[$i],
								  'JANUARY' => 0,
								  'FEBRUARY' => 0,
								  'MARCH' => 0,
								  'APRIL' => 0,
								  'MAY' => 0,
								  'JUNE' => 0,
								  'JULY' => 0,
								  'AUGUST' => 0,
								  'SEPTEMBER' => 0, 
								  'OCTOBER' => 0,
								  'NOVEMBER' => 0,
								  'DECEMBER' => 0
								 );
		}
		$items = $ArrNew ;
	}

	$foot[0]->DEPARTMENT = '<span style="color:blue;font-size:12px;"><b>TOTAL</b></span>';
	$foot[0]->JANUARY = '<span style="color:blue;font-size:12px;"><b>'.number_format($a_tot).' </b></span>';
	$foot[0]->FEBRUARY = '<span style="color:blue;font-size:12px;"><b>'.number_format($b_tot).' </b></span>';
	$foot[0]->MARCH = '<span style="color:blue;font-size:12px;"><b>'.number_format($c_tot).' </b></span>';
	$foot[0]->APRIL = '<span style="color:blue;font-size:12px;"><b>'.number_format($d_tot).' </b></span>';
	$foot[0]->MAY = '<span style="color:blue;font-size:12px;"><b>'.number_format($e_tot).' </b></span>';
	$foot[0]->JUNE = '<span style="color:blue;font-size:12px;"><b>'.number_format($f_tot).' </b></span>';
	$foot[0]->JULY = '<span style="color:blue;font-size:12px;"><b>'.number_format($g_tot).' </b></span>';
	$foot[0]->AUGUST = '<span style="color:blue;font-size:12px;"><b>'.number_format($h_tot).' </b></span>';
	$foot[0]->SEPTEMBER = '<span style="color:blue;font-size:12px;"><b>'.number_format($i_tot).' </b></span>';
	$foot[0]->OCTOBER = '<span style="color:blue;font-size:12px;"><b>'.number_format($j_tot).' </b></span>';
	$foot[0]->NOVEMBER = '<span style="color:blue;font-size:12px;"><b>'.number_format($k_tot).' </b></span>';
	$foot[0]->DECEMBER = '<span style="color:blue;font-size:12px;"><b>'.number_format($l_tot).' </b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>
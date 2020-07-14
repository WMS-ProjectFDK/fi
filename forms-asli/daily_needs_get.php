<?php
/*// Create By : Ueng hernama
// Date : 29-Sept-2017
// ID = 2*/
	session_start();
	$bln = isset($_REQUEST['bln']) ? strval($_REQUEST['bln']) : '';
	$thn = isset($_REQUEST['thn']) ? strval($_REQUEST['thn']) : '';
	$ck_per = isset($_REQUEST['ck_per']) ? strval($_REQUEST['ck_per']) : '';
	$typ = isset($_REQUEST['typ']) ? strval($_REQUEST['typ']) : '';
	$ck_typ = isset($_REQUEST['ck_typ']) ? strval($_REQUEST['ck_typ']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_per != "true"){
		$p = $thn.$bln;
		$prd = "period ='$p' and ";
	}else{
		$prd = "";
	}

	if ($ck_typ != "true"){
		$tp = "tipe='$typ' and ";
	}else{
		$tp = "";
	}

	if ($src !='') {
		$where="where tipe like '%".strtoupper($src)."%'";
	}else{
		$where ="where $prd $tp tipe is not null";
	}
	
	include("../connect/conn.php");

	$sql = "select * from (
		select id_needs,tipe,period,qty_needs, substr(period, 0, 4) as tahun, 
		to_number(substr(period, 5, 2)) as bulan
		from ztb_wh_daily_needs
		$where order by id_needs asc) where rownum<=50";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	$ArrBln = array('-','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');

	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$b = $items[$rowno]->BULAN;
		$items[$rowno]->BULAN = $ArrBln[$b];
		$q = $items[$rowno]->QTY_NEEDS;
		$items[$rowno]->QTY_NEEDS = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
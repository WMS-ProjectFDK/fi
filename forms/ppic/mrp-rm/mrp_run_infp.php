<?php
	include("../connect/conn.php");
	$arrBulan = array('1' => 'JAN',
					  '2' => 'FEB',
					  '3' => 'MAR',
					  '4' => 'APR',
					  '5' => 'MAY', 
					  '6' => 'JUN',
             		  '7' => 'JUL',
             		  '8' => 'AUG',
             		  '9' => 'SEP',
             		  '10' => 'OCT',
             		  '11' => 'NOV',
             		  '12' => 'DEC');
	$b1 = intval(date('m'));
	$t1 = intval(date('Y'));
	if($b1 == 12){
		$b2 = 1;		$b3=2;
		$t2 = $t1+1;	$t3=$t1+1;
	}else{
		$b2 = $b1+1;	$b3=$b1+2;
		$t2 = $t1;		$t3=$t1;
	}

	$q= "select tahun||bulan as period,bulan, tahun, tot, upload_time from (
		SELECT aa.bulan, aa.tahun, bb.tot, aa.upload_time  FROM (
		select distinct bulan, tahun, upload_time from ztb_assy_plan
		where ((bulan = $b1 and tahun=$t1) OR (bulan = $b2 and tahun=$t2) OR (bulan = $b3 and tahun=$t3))
		) aa
		left outer join
		(select bulan, tahun, count(distinct tanggal) as tot from ztb_assy_plan where used=1 
		group by bulan, tahun) bb 
		on aa.bulan=bb.bulan AND aa.tahun=bb.tahun
		) where rownum<=3
		order by tahun";

	$data = oci_parse($connect, $q);
	oci_execute($data);
	$rowno=0;
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$items[$rowno]->MONTH = $arrBulan[$items[$rowno]->BULAN];
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
	/*
	select tahun||bulan as period,bulan, tahun, tot, upload_time from (
	SELECT aa.bulan, aa.tahun, bb.tot, aa.upload_time  FROM (
	select distinct bulan, tahun, upload_time from ztb_assy_plan
	where ((bulan = 12 and tahun=2017) OR (bulan = 1 and tahun=2018) OR (bulan = 2 and tahun=2018))
	) aa
	left outer join
	(select bulan, tahun, count(distinct tanggal) as tot from ztb_assy_plan where used=1 
	group by bulan, tahun) bb 
	on aa.bulan=bb.bulan AND aa.tahun=bb.tahun
	) where rownum<=3
	order by tahun

	$now=date('Y-m-d H:i:s');
	*/	
?>
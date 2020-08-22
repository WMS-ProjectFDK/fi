<?php
	session_start();
	include("../connect/conn_kanbansys.php");

	$pl_bulan = isset($_REQUEST['pl_bulan']) ? strval($_REQUEST['pl_bulan']) : '';
	$pl_tahun = isset($_REQUEST['pl_tahun']) ? strval($_REQUEST['pl_tahun']) : '';
	$pl_cdate = isset($_REQUEST['pl_cdate']) ? strval($_REQUEST['pl_cdate']) : '';
	$pl_aline = isset($_REQUEST['pl_aline']) ? strval($_REQUEST['pl_aline']) : '';
	$pl_cline = isset($_REQUEST['pl_cline']) ? strval($_REQUEST['pl_cline']) : '';
	$pl_cltyp = isset($_REQUEST['pl_cltyp']) ? strval($_REQUEST['pl_cltyp']) : '';
	$pl_cktyp = isset($_REQUEST['pl_cktyp']) ? strval($_REQUEST['pl_cktyp']) : '';
	$pl_revis = isset($_REQUEST['pl_revis']) ? strval($_REQUEST['pl_revis']) : '';
	$pl_crev = isset($_REQUEST['pl_crev']) ? strval($_REQUEST['pl_crev']) : '';
	$pl_day = isset($_REQUEST['pl_day']) ? strval($_REQUEST['pl_day']) : '';
	$pl_cday = isset($_REQUEST['pl_cday']) ? strval($_REQUEST['pl_cday']) : '';
	//$pl_cuse = isset($_REQUEST['pl_cuse']) ? strval($_REQUEST['pl_cuse']) : '';

	if($pl_cdate != "true"){
		$date = "bulan = '$pl_bulan' and tahun = '$pl_tahun' and ";
	}else{
		$date = "";
	}

	if($pl_cline != "true"){
		$line = "assy_line = '$pl_aline' and ";
	}else{
		$line = "";
	}

	if($pl_cktyp != "true"){
		$type = "cell_type = '$pl_cltyp' and ";
	}else{
		$type = "";
	}

	if($pl_crev != "true"){
		if($pl_revis == "USED"){
			$rev = "used = 1 and ";
		}else{
			$rev = "revisi = $pl_revis and ";
		}
	}else{
		$rev = "";
	}

	if($pl_cday != "true"){
		$days = "tanggal = '$pl_day' and ";
	}else{
		$days = "";
	}

	/*if($pl_cuse == "true"){
		$use = "used = 1 and ";
	}else{
		$use = " ";
	}*/	

	$where = " where $date $line $type $rev $days qty!= 0 ";

	if($pl_cdate != "true" OR $pl_cline != "true" OR $pl_cktyp != "true" OR $pl_crev != "true" OR $pl_cday != "true"){
		$sql = "select * from ztb_assy_plan $where ";
	}else{
		$sql = "select * from 
				(select * from ztb_assy_plan $where) 
				where rownum <= 450";
	}
	
	$data_sql = odbc_exec($connect, $sql);

	$bln = array('-','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DES');
	$items = array();
	$rowno = 0;

	while($row = odbc_fetch_object($data_sql)){
		array_push($items, $row);
		$qty = $items[$rowno]->QTY;
		$items[$rowno]->QTY = number_format($qty);
		$p = intval($items[$rowno]->BULAN);
		$items[$rowno]->BULAN = $bln[$p];
		$r = $items[$rowno]->REVISI;
		$items[$rowno]->REVISI = number_format($r);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
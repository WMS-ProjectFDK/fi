<?php
	set_time_limit(0);
	include("../connect/conn.php");
	$arrData = array();
	$arrNo = 0;
	$msg = '';

	$sql = "select distinct lower_item_no as item from structure 
		where upper_item_no||'-'|| level_no in (
		  select distinct item_no||'-'|| bom_level from mps_header where cr_date >= (select sysdate from dual)
		)
		and lower_item_no not in (
		  select distinct item_no from whinventory
		)";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$pesan = oci_error($data);
	$msg .= $pesan['message'];

	if($msg != ''){
		$arrData[$arrNo] = array("kode-1"=>"SELECT ERROR: ".$msg);
	}else{
		while($row = oci_fetch_object($data)){
			$itm = $row->ITEM;
			$ins = "insert into whinventory
				select sysdate, 100, $itm, '-', to_number(to_char(sysdate,'YYYYMM')),
				0,0,0,0,0,0,
				to_number(to_char(add_months(sysdate, -1),'YYYYMM')),0,0,0,0,0,0,0,'-','-'
				from dual";
			$dataIns = oci_parse($connect, $ins);
			oci_execute($dataIns);
			$pesan = oci_error($dataIns);
			$msg .= $pesan['message'];

			if($msg != ''){
				$arrData[$arrNo] = array("kode-2"=>"INSERT ERROR: ".$msg);
			}
		}
	}

	if($msg != ''){
		$arrData[$arrNo] = array("kode1"=>$msg);
	}else{
		$arrData[$arrNo] = array("Proses Delivery Update Data"=>'SUCCESS');
	}

	echo date("Y-m-d H:i:s").' - '.$arrData[$arrNo].'<br/>';
?>
<?php
	set_time_limit(0);
	include("../connect/conn.php");
	$arrData = array();
	$arrNo = 0;
	$msg = '';

	$sql = "update indication set remark='' where answer_no in 
		(select distinct a.answer_no from indication a
		inner join answer b on a.answer_no = b.answer_no
		left join production_income c on b.work_no = c.wo_no
		where a.commit_date is null and a.remark = 1
		 and to_char(a.ex_factory,'yyyymm') <= (select max(this_month) from whinventory)
		 and to_char(a.ex_factory,'yyyymm') >= (select max(last_month) last_month from whinventory) 
		 and a.ex_factory <= sysdate
		 and (c.slip_type = 80 OR a.slip_type is null)
		)";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$pesan = oci_error($data);
	$msg .= $pesan['message'];

	if($msg != ''){
		$arrData[$arrNo] = array("kode1"=>$msg);
	}else{
		$arrData[$arrNo] = array("Proses Delivery Update Data"=>'SUCCESS');
	}

	echo date("Y-m-d H:i:s").' - '.$arrData[$arrNo].'<br/>';
?>
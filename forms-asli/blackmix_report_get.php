<?php
	session_start();
	include("../connect/conn3.php");
	$items = array();		$foot = array();
	$rowno = 0;
	$t_zn = 0;		$t_el = 0;		$t_hvc = 0;
	$t_aq = 0;		$t_ai = 0;		$t_hve = 0;
	$t_pw = 0;		$t_to = 0;		$t_tha = 0;
	$t_th = 0;
	
	$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
	$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';
	
	$where ="where convert(varchar, tgl_pemakaian, 20) between '$date_awal' AND '$date_akhir' AND jenis <> 'RECLAIMED' ";

	$sql = "select assy_line, tag_no, type_graphite, 
		REPLACE(CONVERT(VARCHAR, time_out, 120), ' ', '<br>') as time_out, type_out, 
		REPLACE(petugas_penuangan, '-', '<br>') as petugas_penuangan, keterangan, 
		REPLACE(CONVERT(VARCHAR, tgl_pemakaian, 120), ' ', '<br>') as tgl_pemakaian,
		resistivity, density, moisture, minisive
		from bm_output_mesin
		$where
		order by row_id asc";
	$rs = odbc_exec($conn_timbangan,$sql);

	while ($row = odbc_fetch_object($rs)){
		array_push($items, $row);
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
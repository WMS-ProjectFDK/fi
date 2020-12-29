<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../../connect/conn.php");
	$user = $_SESSION['id_wms'];

	$date_gr = "gh.gr_date between '$date_awal' and '$date_akhir'  ";

	$sql = "select gh.bc_doc, gh.bc_no, CAST(CONVERT(date, gh.gr_date,102) as varchar(10)) as gr_date, gh.gr_no, c.company, cc.curr_mark, sum(gh.amt_o) as amt_o 
        from sp_gr_header gh 
        left join sp_company c on gh.supplier_code = c.company_code 
        left join currency cc on gh.curr_code = cc.curr_code
		where $date_gr
		group by gh.bc_doc, gh.bc_no, gh.gr_date, gh.gr_no, c.company, cc.curr_mark
		order by company, bc_no, gr_no";
	$data = sqlsrv_query($connect, strtoupper($sql));
	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
			array_push($items, $row);
			$c = $items[$rowno]->CURR_MARK;
			$a = $items[$rowno]->AMT_O;
			$items[$rowno]->AMT = $c.' '.number_format($a,5);
			$rowno++;
	}
	
	$fp = fopen('bc_gr_spareparts_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["rows"] = $items;
	echo json_encode($result);	
?>
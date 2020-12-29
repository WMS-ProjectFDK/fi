<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../../connect/conn.php");
	$user = $_SESSION['id_wms'];

    $sql = "select CAST(isnull(b.bc_doc,a.bc_doc) as decimal(12,1)) as bc_doc, a.bc_no, cast(a.gr_date as varchar(11)) as gr_date, 
        a.supplier_code, b.company, a.gr_no, c.curr_mark,  a.amt_o
        from gr_header a
        inner join company b on a.supplier_code = b.company_code
        inner join currency c on a.curr_code = c.curr_code
        where a.gr_date BETWEEN '$date_awal' and '$date_akhir'
		order by a.supplier_code, a.gr_date, a.gr_no";
	$data_sql = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data_sql)){
		array_push($items, $row);
		$c = $items[$rowno]->CURR_MARK;
		$a = $items[$rowno]->AMT_O;
		$items[$rowno]->AMT = $c.' '.number_format($a);
		$rowno++;
	}
	
	$fp = fopen('bc_gr_pglosas_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["rows"] = $items;
	echo json_encode($result);	
?>
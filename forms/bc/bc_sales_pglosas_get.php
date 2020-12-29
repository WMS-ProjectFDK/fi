<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../../connect/conn.php");
	$user = $_SESSION['id_wms'];

	$date_bc = "where x.do_date between '$date_awal' and '$date_akhir' ";

	$sql = "select a.bl_no,
        cast(a.bl_date as varchar(11)) bl_date,
        a.emkl,
        a.forwarder,
        a.peb_no,
        a.pe_no,
        cast(x.inv_date as varchar(11)) as inv_date,
        x.customer_code,
        x.do_no,
        x.curr_code,
        b.company customer,
        c.curr_mark,
        x.amt_o,
        case when a.do_no is null then 'INSERT' else 'UPDATE' end as STS
    from do_header x 
    left join emkl a on x.do_no= a.do_no
    left join company b on x.customer_code = b.company_code
    left join currency c on x.curr_code = c.curr_code
    ".$date_bc."
    order by x.customer_code,
           x.inv_date,
           x.inv_no";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$c = $items[$rowno]->CURR_MARK;
		$a = $items[$rowno]->AMT_O;
		$items[$rowno]->AMT = $c.' '.$a;
		$rowno++;
	}
	
	$fp = fopen('bc_sales_pglosas_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["rows"] = $items;
	echo json_encode($result);	
?>
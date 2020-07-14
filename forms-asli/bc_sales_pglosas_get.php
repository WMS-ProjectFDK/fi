<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../connect/conn.php");
	$user = $_SESSION['id_wms'];

	$date_bc = "and to_char(x.do_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' ";

	$sql = "select a.bl_no,
       to_char(a.bl_date,'dd/mm/yyyy') bl_date,
       a.emkl,
       a.forwarder,
       a.peb_no,
       a.pe_no,
       x.inv_date,
       x.customer_code,
       x.do_no,
       x.curr_code,
       b.company customer,
       c.curr_mark,
       to_char(x.amt_o,'999,999,999,990.00') amt_o,
       case when a.do_no is null then 'INSERT' else 'UPDATE' end as STS
  from emkl a,
       company b,
       currency c,
       do_header x
  where x.do_no= a.do_no(+)
    and x.customer_code = b.company_code(+)
    and x.curr_code = c.curr_code(+)
    ".$date_bc."
  order by x.customer_code,
           x.inv_date,
           x.inv_no";
	$data_sql = oci_parse($connect, $sql);
	oci_execute($data_sql);

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;

	while($row = oci_fetch_object($data)){
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
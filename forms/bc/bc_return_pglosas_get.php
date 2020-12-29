<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../../connect/conn.php");
	$user = $_SESSION['id_wms'];

	$where = "where t.slip_date between '$date_awal' and '$date_akhir' ";

	$sql = "select c.BC_DOC_REVERSE as BC_DOC, t.bc_no, CAST(t.slip_date as varchar(10)) as slip_date, t.slip_no, c.company customer, cu.curr_mark, sum(t.slip_amount) as slip_amount
		from [transaction] t
		inner join company c on t.company_code = c.company_code
		inner join currency cu on t.curr_code = cu.curr_code
		inner join (select slip_type
		            from sliptype
		            where stock_type  = 'M'
		            and in_out_flag = 'O'
		            and slip_type	in ('41','42')) sl on t.slip_type = sl.slip_type
		$where 
		group by c.BC_DOC_REVERSE, t.bc_no, t.slip_date, t.slip_no, c.company, cu.curr_mark";
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$c = $items[$rowno]->CURR_MARK;
		$a = $items[$rowno]->SLIP_AMOUNT;
		$items[$rowno]->AMT = $c.' '.number_format($a,5);
		$rowno++;
	}
	
	$fp = fopen('bc_return_pglosas_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["rows"] = $items;
	echo json_encode($result);	
?>
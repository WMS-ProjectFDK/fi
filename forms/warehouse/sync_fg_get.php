<?php
	// error_reporting(0);
	ini_set('max_execution_time', -1);
	session_start();
	$result = array();

	include("../../connect/conn.php");
	$rowno=0;

	$rs = "select cast(t.slip_date as varchar(10)) slip_date, t.section_code, sc.section, t.item_no, i.item , i.description , 
		i.stock_subject_code , st.stock_subject , t.slip_type , sl.description slip_description , sl.slip_name , a.slip_no, t.slip_quantity , 
		case sl.in_out_flag when 'I' then t.slip_quantity end IN_QTY, case sl.in_out_flag when 'O' then  t.slip_quantity end as OUT_QTY, t.slip_price, t.slip_amount , 
		t.curr_code , cu.curr_mark , t.standard_price, t.standard_amount , t.suppliers_price , t.cost_process_code, t.cost_subject_code, 
		t.unit_stock, u.unit , t.ex_rate, t.company_code, t.order_number, c.company 
		from ztb_wh_kanban_trans_fg a
	    inner join production_income t on a.slip_no=t.slip_no
	    left join item i on t.item_no = i.item_no
	    left join section sc on t.section_code = sc.section_code
	    left join unit u on t.unit_stock = u.unit_code
	    left join stock_subject st on t.stock_subject_code = st.stock_subject_code
	    left join sliptype sl on t.slip_type = sl.slip_type
	    left join company c on t.company_code = c.company_code
	    left join currency cu on t.curr_code = cu.curr_code
		where a.flag=0";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$q = $items[$rowno]->SLIP_QUANTITY;
		$items[$rowno]->SLIP_QUANTITY = number_format($q);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
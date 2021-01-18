<?php
	error_reporting(0);
	session_start();
	$result = array();
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$month = isset($_REQUEST['month']) ? strval($_REQUEST['month']) : '';
	$l_inv = isset($_REQUEST['l_inv']) ? strval($_REQUEST['l_inv']) : '';

	include("../../../connect/conn.php");

	$rowno=0;
	$rs = "
		  
			
			
			select cast(t.operation_date as varchar(10)) operation_date, t.section_code,  i.stock_subject_code, 
		  cast(t.slip_date as varchar(10))  slip_date, t.slip_type, sl.slip_name slip_name, t.slip_no, sl.in_out_flag,
			case sl.table_position when 1 then t.slip_quantity end  receive,
			case sl.table_position when 2 then t.slip_quantity end other_receive,
			case sl.table_position when 3 then t.slip_quantity end issue,
			case sl.table_position when 4 then t.slip_quantity end  other_issue,
			case sl.in_out_flag
				  when 'I' then isnull(t.slip_quantity,0)
				  when 'O' then isnull(-t.slip_quantity,0)
			   end qty,
			t.cost_process_code, t.cost_subject_code,  t.unit_stock, t.company_code,sc.company
			from [sp_transaction] t
			left outer join sp_item i on t.item_no = i.item_no  and i.delete_type  is null    
			left outer join sp_unit u on  t.unit_stock = u.unit_code
			left outer join sliptype sl on t.slip_type = sl.slip_type   
			left outer join sp_company sc on  t.company_code = sc.company_code
			where t.item_no = '$id' and  t.accounting_month = '$month' 
			order by t.slip_date,t.slip_type,t.SLIP_NO";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	$foot = array();
	$tot_rec = 0;	$tot_o_rec = 0;	
	$tot_iss = 0;	$tot_o_iss = 0;	
	$tot_inv=0;		$total = 0;
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);

		$ty = $items[$rowno]->SLIP_TYPE;
		$nm = $items[$rowno]->SLIP_NAME;
		$items[$rowno]->SLIP_TYPE = '['.$ty.'] '.$nm;

		$flagIO = $items[$rowno]->IN_OUT_FLAG;

		$REC = $items[$rowno]->RECEIVE;
		$O_REC = $items[$rowno]->OTHER_RECEIVE;
		$ISS = $items[$rowno]->ISSUE;
		$O_ISS = $items[$rowno]->OTHER_ISSUE;
		$QTY = intval($items[$rowno]->QTY);

		$total = intval($l_inv) + $QTY;

		$tot_rec += intval($REC);
		$tot_o_rec += intval($O_REC);
		$tot_iss += intval($ISS);
		$tot_o_iss += intval($O_ISS);

		$items[$rowno]->RECEIVE = number_format($REC);
		$items[$rowno]->OTHER_RECEIVE = number_format($O_REC);
		$items[$rowno]->ISSUE = number_format($ISS);
		$items[$rowno]->OTHER_ISSUE = number_format($O_ISS);
		$items[$rowno]->TOTAL = number_format($total);

		$l_inv = $total;
		$rowno++;
	}

	$foot[0]->RECEIVE = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_rec).'</b></span>';
	$foot[0]->OTHER_RECEIVE = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_o_rec).'</b></span>';
	$foot[0]->ISSUE = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_iss).'</b></span>';
	$foot[0]->OTHER_ISSUE = '<span style="color:blue;font-size:12px;"><b>'.number_format($tot_o_iss).'</b></span>';
	$foot[0]->TOTAL = '<span style="color:blue;font-size:12px;"><b>'.number_format($total).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>
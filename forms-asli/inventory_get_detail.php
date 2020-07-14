<?php
	session_start();
	$result = array();
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$month = isset($_REQUEST['month']) ? strval($_REQUEST['month']) : '';
	$l_inv = isset($_REQUEST['l_inv']) ? strval($_REQUEST['l_inv']) : '';

	include("../connect/conn.php");

	$rowno=0;
	$rs = "select to_char(t.operation_date,'dd/mm/yyyy') operation_date, t.section_code, sc.section, i.stock_subject_code, st.stock_subject, 
		to_char(t.slip_date,'dd/mm/yyyy')  slip_date, t.slip_type, sl.slip_name slip_name, t.slip_no, sl.in_out_flag,
		  decode(sl.table_position,1,t.slip_quantity) receive,
		  decode(sl.table_position,2,t.slip_quantity) other_receive,
		  decode(sl.table_position,3,t.slip_quantity) issue,
		  decode(sl.table_position,4,t.slip_quantity) other_issue,
		  trunc(decode(sl.in_out_flag,'I',nvl(t.slip_quantity,0),'O',nvl(-t.slip_quantity,0)),4) qty,
		  t.cost_process_code, t.cost_subject_code, t.remark1, t.remark2, t.unit_stock, t.company_code, c.company, t.ex_rate--, prfd.ohsas
		  from transaction t, item i, section sc, unit u, stock_subject st,sliptype sl, company c, currency cu--, gr_details grd, po_details pod, prf_details prfd
		  where t.item_no = i.item_no (+) and i.delete_type  is null and t.section_code = sc.section_code (+) and t.unit_stock = u.unit_code (+)
		  and t.section_code = sc.section_code (+) and t.slip_type = sl.slip_type (+) and t.company_code = c.company_code (+) 
		  and t.stock_subject_code = st.stock_subject_code (+) and t.curr_code = cu.curr_code (+) 
		  --and t.slip_no = grd.gr_no (+) and t.item_no = grd.item_no (+) 
	      --and  grd.po_no = pod.po_no (+) and grd.po_line_no = pod.line_no (+) 
	      --and pod.prf_no = prfd.prf_no (+) and pod.prf_line_no = prfd.line_no (+)
		  and t.section_code = '100' and t.item_no = '$id' and  t.accounting_month = '$month' 
		  order by t.slip_date,t.slip_type,t.SLIP_NO ";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	$foot = array();
	$tot_rec = 0;	$tot_o_rec = 0;	
	$tot_iss = 0;	$tot_o_iss = 0;	
	$tot_inv=0;
	while($row = oci_fetch_object($data)) {
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
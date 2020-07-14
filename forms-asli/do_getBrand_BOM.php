<?php
	session_start();
	$result = array();

	$brand = isset($_REQUEST['brand']) ? strval($_REQUEST['brand']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	//23560-ENERGIZER COR EU LR6 BULK-0
	$split_brand = split('@',$brand);

	if ($split_brand[4] == ''){
		$datecode = '-';
	}else{
		$datecode = $split_brand[4];
	}

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select st.lower_item_no as item_no,i.item,i.description,i.cost_process_code,nvl(i.item_type2, w.rack_addr) as lower_item_type2, 
		i.item_type2 as i_item_type2, 
		Trim(to_char(Trunc(nvl(st.quantity,0) / nvl(st.quantity_base,0)+ 0.009,3),'99,999,990.000')) ref_qty,
		ceil( nvl(st.quantity,0) / nvl(st.quantity_base,0) * 
		  ( 1 + (nvl(i_u.manufact_fail_rate,0)/100) +
		    (nvl(i.manufact_fail_rate,0)/100) +
		    (nvl(st.failure_rate,0)/100)
		  ) * ".$qty."
		) as SLIP_QTY, nvl(w.this_inventory,0) as STOCK, u.unit_pl, '".$split_brand[3]."' as WO_NO, '".$datecode."' as DATE_CODE, '-' as REMARK,i.uom_q
		from structure st
		left join item i on st.lower_item_no=i.item_no
		left join item i_u on st.upper_item_no=i_u.item_no
		left join unit u on i.uom_q=u.unit_code
		left join whinventory w on i.item_no=w.item_no
		where st.upper_item_no = '".$split_brand[0]."' 
		and st.level_no = '".$split_brand[2]."' 
		and st.lower_item_no = i.item_no 
		order by st.line_no";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
<?php
	session_start();
	$result = array();
	$cust = isset($_REQUEST['cust']) ? strval($_REQUEST['cust']) : '';
	$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';
	include("../../connect/conn.php");

	$shipment = "";
	$forwarder = "";
	$emkl = "";	

    $sqlx = "select case when si.shipping_type = 'FCL' then CAST(si.shipping_type as varchar) + ' ' +
		case when sum(container_value) = 0 then '1' else CAST(CEILING(sum(container_value)) as varchar)   end 
		+ 'x' + containers + ' SHIPMENT ' else 
		case when si.shipping_type in ('BY AIR') then 'AIR SHIPMENT' else 'LCL SHIPMENT' end end SHIPMENT,   	
		case when si.shipping_type in ('FCL','LCL') then 'OCEAN' ELSE 
		case when si.shipping_type in ('BY AIR') then 'AIR' end end  st,
		si.emkl_name EMKL,si.forwarder_name FORWARDER
		from answer aa 
		inner join si_header si on aa.si_no = si.si_no 
		left outer join ztb_shipping_detail cc on aa.answer_no = cc.answer_no
		where aa.si_no = '$si'
		group by containers,si.shipping_type,si.emkl_name,si.forwarder_name";

	$datax = sqlsrv_query($connect, strtoupper($sqlx));
	
	while($rowx = sqlsrv_fetch_object($datax)) {
		$shipment = $shipment.$rowx->SHIPMENT;
		$forwarder = $rowx->FORWARDER;
		$emkl =  $rowx->EMKL;
		$st =  $rowx->ST;
	}
	
	$rowno=0;
	$rs = "select main.*, mh.date_code ,'' as  rownumm,'' SHIPPING_SET
		from (
			select CAST(id.ex_factory as varchar(10)) as ex_factory, id.REMARK, soh.customer_po_no, sod.so_no, sod.line_no, CAST(sod.u_price as varchar) u_price, 
			CAST(sod.etd as varchar) etd, id.answer_no, id.qty delivery_qty, i.description ,ans.customer_po_line_no,
			soh.customer_code, sod.item_no, isnull(sod.customer_part_no,substring(i.description,1,20)) customer_part_no, isnull(stk.stk_qty,0) stk_qty, 
			i.external_unit_number, c.country_code origin_code, c.country origin, 
			cl.description classify, u1.unit_pl uom_q, u2.unit uom_w,
			case when sod.pallet_mark_1 is not null then  sod.pallet_mark_1 +' <br>' else '' end +
			case when sod.pallet_mark_2 is not null then  sod.pallet_mark_2 +' <br>' else '' end +
			case when sod.pallet_mark_3 is not null then sod.pallet_mark_3 +' <br>' else '' end +
			case when sod.pallet_mark_4 is not null then sod.pallet_mark_4 +' <br>' else '' end +
			case when sod.pallet_mark_5 is not null then sod.pallet_mark_5 +' <br>' else '' end +
			case when sod.pallet_mark_6 is not null then sod.pallet_mark_6 +' <br>' else '' end +
			case when sod.pallet_mark_7 is not null then sod.pallet_mark_7 +' <br>' else '' end +
			case when sod.pallet_mark_8 is not null then sod.pallet_mark_8 +' <br>' else '' end +
			case when sod.pallet_mark_9 is not null then sod.pallet_mark_9 +' <br>' else '' end +
			case when sod.pallet_mark_10 is not null then sod.pallet_mark_10 +' <br>'else '' end 
			as REMARK_SHIPPING, sod.pallet_mark_1,
			ans.work_no, sins.carton, sins.box_pcs, sins.start_box, sins.end_box, sins.pallet, ans.si_no
			from  so_details sod
			left join so_header soh on sod.so_no=soh.so_no 
			left join indication id on sod.so_no=id.so_no and sod.line_no = id.so_line_no
			left join answer ans on id.answer_no=ans.answer_no and sod.line_no = ans.so_line_no
			left join ztb_shipping_ins sins on id.answer_no=sins.answer_no
			left join (select sum(isnull(w.this_inventory,0)) stk_qty, w.item_no from whinventory w 
					inner join item i on w.item_no = i.item_no 
					group by w.item_no) stk on sod.item_no = stk.item_no
			left join item i on sod.item_no = i.item_no
			left join country c on i.origin_code = c.country_code
			left join class cl on i.class_code = cl.class_code
			left join unit u1 on i.uom_q = CAST(u1.unit_code as varchar)
			left join unit u2 on i.uom_w = CAST(u2.unit_code as varchar)
			where id.inv_no is null and soh.customer_code = '$cust' and ans.si_no='$si' --and sod.bal_qty > 0 
		) main
		left join mps_header mh on main.customer_po_no= mh.po_no and mh.po_line_no = CAST(main.customer_po_line_no as int)
		order by main.line_no, main.ex_factory";
	$add = "'add'";
	$data = sqlsrv_query($connect, strtoupper($rs));
    $items = array();	
    
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$ans = "'".$items[$rowno]->ANSWER_NO."'";
		$ri = $items[$rowno]->REMARK_SHIPPING;
		$plt = $items[$rowno]->PALLET;
		$items[$rowno]->REMARK_SHIPPING = str_replace('<BR>', '<br>', str_replace("1-UP","1-".ceil($plt),$ri));
		$idxfield = $rowno; //$items[$rowno]->ROWNUMM;
		$stock_qty = $items[$rowno]->STK_QTY;
		$items[$rowno]->STK_QTY = number_format($stock_qty);
		// $plt = $items[$rowno]->PALLET;
		// $ctr = $items[$rowno]->CARTON;
		$d_qty = $items[$rowno]->DELIVERY_QTY;
		$items[$rowno]->DELIVERY_QTY = number_format($d_qty);
		$items[$rowno]->SHIPMENT = $shipment;
		$items[$rowno]->FORWARDER = $forwarder;
		$items[$rowno]->EMKL = $emkl;
		$items[$rowno]->ST = $st;
		$items[$rowno]->SHIPPING_SET = '<a href="javascript:void(0)" onclick="sett_shipping_mark('.$add.','.$ans.','.$idxfield.')">EDIT</a>';
		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
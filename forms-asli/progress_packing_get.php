<?php
	set_time_limit(0);
	session_start();
	header("Content-type: application/json");
	include("../connect/conn.php");
	$items = array();		
	$arrData = array();
	$arrNo = 0;

	$sql = "select work_order, item_no, 
		case when length(item_name) <= 20 then '('||item_no||') -'||'<br/>'||item_name
			 else '('||item_no||') - '||'<br/>'||substr(item_name, 1, 20)||'<br/>'||substr(item_name, 21, length(item_name)) end as item_name, 
		cr_date, packaging_type,
		to_char(qty, '999,999,999,990') as qty,
		to_char(label_qty,'999,999,999,990') as label_qty, 
		qty-label_qty as label_gap_o,
		(label_qty/qty)*100 as label_gap_persen_o,
		to_char(qty-label_qty,'999,999,999,990')||'<br/>['||trim(to_char((label_qty/qty)*100,'999,999,999,990.00'))||'%]' as label_gap,
		to_char(packing_qty*carton,'999,999,999,990') as pack_qty,
	 	qty-(packing_qty*carton) as pack_gap_o,
	 	((packing_qty*carton)/qty)*100 as pack_gap_persen_o,
	 	to_char(qty-(packing_qty*carton),'999,999,999,990')||'<br/>['||trim(to_char(((packing_qty*carton)/qty)*100,'999,999,999,990.00'))||'%]' as pack_gap
	 	from
		(
		select mh.work_order, mh.item_no, mh.item_name, mh.cr_date, mh.packaging_type, it.pi_no, pi.pallet_unit_number/pi.pallet_ctn_number as carton,
		mh.qty, nvl(pi.qty_prod,0) as prod_qty, nvl(lbl.lbl_qty,0) label_qty, nvl(pck.packing_qty,0) packing_qty
		from mps_header mh 
		left outer join (select wo_no,sum(case when slip_type = 80 then slip_quantity else slip_quantity*-1 end) qty_prod 
		from production_income group by wo_no)pi on mh.work_order = pi.wo_no
		left outer join (select wo_no, case when nvl(sum(qty),0) < 0 then nvl(sum(qty)*-1,0) else nvl(sum(qty),0) end as lbl_qty from ztb_lbl_trans_det group by wo_no) lbl on mh.work_order = lbl.wo_no
		left OUTER join (select b.wo_no, sum(a.no_outer_box) as packing_qty 
		            from ztb_kanban_pck a 
		            inner join ztb_p_plan b on a.id = b.id
		            where a.enddate is not null
		            group by b.wo_no
		) pck on mh.work_order=pck.wo_no
		left outer join item it on mh.item_no = it.item_no 
		left outer join packing_information pi on it.pi_no = pi.pi_no
		where mh.cr_date >= sysdate and rownum <= 10 and nvl(pi.qty_prod,0) < mh.qty and mh.work_order is not null
		order by mh.cr_date asc
		) ";

	// select * from
	// 	(
	// 	select mh.work_order, mh.item_no, 
	// 	case when length(mh.item_name) <= 20 then '('||mh.item_no||') -'||'<br/>'||mh.item_name
	// 		 else '('||mh.item_no||') - '||'<br/>'||substr(mh.item_name, 1, 20)||'<br/>'||substr(mh.item_name, 21, length(mh.item_name)) end as item_name,
	// 	mh.cr_date, mh.packaging_type,
	// 	to_char(mh.qty, '999,999,999,990') as qty,
	// 	to_char(lbl.lbl_qty,'999,999,999,990') as label_qty,
	// 	mh.qty-lbl.lbl_qty as label_gap_o,
	// 	(lbl.lbl_qty/mh.qty)*100 as label_gap_persen_o,  
	// 	to_char(mh.qty-lbl.lbl_qty,'999,999,999,990')||'<br/>['||trim(to_char((lbl.lbl_qty/mh.qty)*100,'999,999,999,990.00'))||'%]' as label_gap,
	// 	to_char(pck.packing_qty,'999,999,999,990') as pack_qty,
	// 	mh.qty-pck.packing_qty as pack_gap_o,
	// 	(pck.packing_qty/mh.qty)*100 as pack_gap_persen_o,
	// 	to_char(mh.qty-pck.packing_qty,'999,999,999,990')||'<br/>['||trim(to_char((pck.packing_qty/mh.qty)*100,'999,999,999,990.00'))||'%]' as pack_gap
	// 	from mps_header mh 
	// 	inner join (select wo_no, nvl(sum(qty),0) as lbl_qty from ztb_lbl_trans_det group by wo_no) lbl on mh.work_order= lbl.wo_no
	// 	inner join (select wo_no, nvl(sum(j1)+sum(j2)+sum(j3),0) as packing_qty from
	// 	            (
	// 	              select a.wo_no, a.id, nvl(sum(b.label_qty1),0) as j1, nvl(sum(b.label_qty2),0) as j2, nvl(sum(b.label_qty3),0) as j3
	// 	              from ztb_p_plan a
	// 	              inner join ztb_kanban_pck b on a.id=b.id
	// 	              group by a.wo_no, a.id
	// 	            ) group by wo_no
	// 	           )pck on mh.work_order=pck.wo_no
	// 	where mh.cr_date > sysdate
	// 	order by mh.cr_date asc
	// 	)
	// 	where label_gap_o != 0 and pack_gap_o != 0 and rownum<=20";

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($row = oci_fetch_object($data)){
		array_push($items, $row);
	}

	$fp = fopen('progress_packing_result.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	if ($fp){
	    $arrData[$arrNo] = array('kode' => 'SUCCESS');
	}else{
		$arrData[$arrNo] = array('kode' => 'save to json file error');	
	}

	echo json_encode($arrData);

	// $result["rows"] = $fix;
	// echo json_encode($result);
?>
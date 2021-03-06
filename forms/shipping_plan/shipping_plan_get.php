<?php
	// error_reporting(0);
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	$cmb_wo_no = isset($_REQUEST['cmb_wo_no']) ? strval($_REQUEST['cmb_wo_no']) : '';
	$ck_wo_no = isset($_REQUEST['ck_wo_no']) ? strval($_REQUEST['ck_wo_no']) : '';
	$cmb_po_no = isset($_REQUEST['cmb_po_no']) ? strval($_REQUEST['cmb_po_no']) : '';
	$ck_po_no = isset($_REQUEST['ck_po_no']) ? strval($_REQUEST['ck_po_no']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_ppbe = isset($_REQUEST['cmb_ppbe']) ? strval($_REQUEST['cmb_ppbe']) : '';
	$ck_ppbe = isset($_REQUEST['ck_ppbe']) ? strval($_REQUEST['ck_ppbe']) : '';
	$flag = isset($_REQUEST['flag']) ? strval($_REQUEST['flag']) : '';
	$ck_si = isset($_REQUEST['ck_si']) ? strval($_REQUEST['ck_si']) : '';
	$cmb_si_no = isset($_REQUEST['cmb_si_no']) ? strval($_REQUEST['cmb_si_no']) : '';
	$ck_sample = isset($_REQUEST['ck_sample']) ? strval($_REQUEST['ck_sample']) : '';


	// http://localhost:8088/fi/forms/shipping_plan/shipping_plan_get.php?date_awal=2020-12-23&date_akhir=2020-12-23&ck_cr_date=false&cmb_wo_no=&ck_wo_no=true&cmb_po_no=0016660&ck_po_no=true&cmb_item_no=&ck_item_no=true&cmb_ppbe=&ck_ppbe=true&flag=15&ck_si=true&ck_sample=false&cmb_si_no=

	if ($ck_cr_date != "true"){
		$date = "mh.cr_date between '$date_awal' and '$date_akhir' AND ";
	}else{
		$date = "";
	}

	if ($ck_wo_no != "true"){
		$prf = "work_order = '$cmb_wo_no' and ";
	}else{
		$prf = "";
	}

	if ($ck_item_no != "true"){
		$pieces = explode('-', $cmb_item_no);
		$part1 = implode('-', array_slice($pieces, 0, 1));
		$item_no = "item_no='$part1' and ";
	}else{
		$item_no = "";
	}

	if($ck_po_no!='true'){
		$supp = " po_no  = '$cmb_po_no' and ";
	}else{
		$supp = "";
	}

	if($ck_ppbe!='true'){
		$ppbe = " inv.crs_remark  = '$cmb_ppbe' and ";
	}else{
		$ppbe = "";
	}
	// $date $prf $item_no $supp $ppbe
	$where =" where $date  $prf $item_no $supp $ppbe
		status = 'FM' 
		--and case when ds.customer_po_no is not null then substring(po_line_no, 1, len(po_line_no)-1) else po_line_no end = line_no and (isnull(substring(po_line_no, 1, len(po_line_no)-1), PO_LINE_NO) = LINE_NO OR po_line_no = line_no)";
	
	include("../../connect/conn.php");
	$s=0;

	// echo $ck_si;
	if($ck_sample == "true"){
		$sql = "
		select top 150 isnull(ship,1) SHIPPING,
                       r.CUSTOMER_PO_NO + '-' +cast(s.LINE_NO as varchar(5)) as work_order,
                       r.CUSTOMER_PO_NO as po_no,
                       s.LINE_NO as po_line_no,
                       '' as cr_date,
                       '' as battery_type,
                       i.GRADE_CODE as cell_grade,
                       s.ITEM_NO,
                       i.item as item_name,
                       isnull(s.QTY,0) Qty_order,
                       0 Qty_Produksi,
                       0 qty_plan, 
                       0 qty_invoiced,
				       '' si_no,
                       r.so_no,
                       s.line_no,
                       cast(s.ETD as varchar(20)) ETD, 
                       cast(s.ETD as varchar(20)) stuffy_date, 
                       cast(s.ETD as varchar(20)) as etd_ans, 
                       cast(s.ETD as varchar(20)) eta, 
                       sh.shipping_type, 
                       '' containers,
			           customer_code, 
                       r.curr_code, 
                       s.u_price, 
                       'Y' item_set, 
                       '' crs_remark
           

        from SO_HEADER r
        inner join SO_DETAILS s 
        on r.SO_NO = s.SO_NO
        inner join item i 
        on s.ITEM_NO = i.ITEM_NO
        left outer join ZTB_SHIPPING_PLAN zz 
        on zz.WO_NO = r.CUSTOMER_PO_NO + '-' +cast(s.LINE_NO as varchar(5)) 
        left outer join (select wo_no,sum(CAST(Qty as int)) qty_plan,isnull(count(isnull(wo_no,1)),1) ship from ztb_shipping_plan group by wo_no) zt on zt.wo_no = r.CUSTOMER_PO_NO + '-' +cast(s.LINE_NO as varchar(5)) 
        left outer join (select sum(isnull(do_so.qty,0)) qty_invoiced,work_no, crs_remark, si_no, stuffy_date, etd, eta from answer 
		                    left join do_so on do_so.answer_no = answer.answer_no 
		                    group by work_no, crs_remark, si_no, stuffy_date, etd, eta)inv on inv.work_no = r.CUSTOMER_PO_NO + '-' +cast(s.LINE_NO as varchar(5)) 
        left outer join (select max(si_no) si_no,cust_si_no, shipping_type from  si_header group by cust_si_no, shipping_type) sh on sh.cust_si_no = r.CUSTOMER_PO_NO
		where $item_no $prf s.item_no < 9999  
		and not exists (select * from ANSWER where so_no + cast(SO_LINE_NO as varchar(5)) = r.SO_NO  +cast(s.LINE_NO as varchar(5)) )

		";
	}
	else{
			if ($ck_si != "true"){
				$sql = "select distinct top 150 isnull(ship,1) SHIPPING ,work_order,po_no,po_line_no,cr_date,batery_type,cell_grade,mh.item_no,item_name,
						isnull(qty,0) Qty_order,isnull(qty_prod,0) Qty_Produksi,isnull(qty_plan,0) qty_plan, isnull(qty_invoiced,0) qty_invoiced,
						inv.si_no,so_no,so.line_no,requested_etd etd, stuffy_date, inv.etd as etd_ans, eta, sh.shipping_type, 
					customer_code, so.curr_code, so.u_price, case when mh.item_no=zi.item_no then 'Y' else 'N' end as item_set, inv.crs_remark
					from mps_header mh
						inner join (select max(si_no) si_no,cust_si_no, shipping_type from si_header group by cust_si_no, shipping_type) sh on sh.si_no = '$cmb_si_no'  and sh.cust_si_no like '%'+mh.po_no+'%'
						left join ztb_item zi on mh.item_no = zi.item_no
						left outer join (select wo_no,sum(slip_quantity) qty_prod from production_income group by wo_no) pi on mh.work_order = pi.wo_no
						left outer join (select sum(isnull(do_so.qty,0)) qty_invoiced,work_no, crs_remark, si_no, stuffy_date, etd, eta from answer 
									left join do_so on do_so.answer_no = answer.answer_no 
									group by work_no, crs_remark, si_no, stuffy_date, etd, eta)inv on mh.work_order = inv.work_no 
						left join (select soh.customer_code, soh.so_no, soh.customer_po_no, soh.curr_code, sod.u_price, sod.line_no,sod.item_no from so_header soh
								inner join so_details sod on soh.so_no=sod.so_no)so on mh.po_no = so.customer_po_no and mh.item_no = so.item_no
						left outer join (select wo_no,sum(CAST(Qty as int)) qty_plan,isnull(count(isnull(wo_no,1)),1) ship from ztb_shipping_plan group by wo_no) zt on zt.wo_no = mh.work_order
						left outer join (
									select distinct s.customer_po_no  from so_header s
									inner join so_Details d on s.so_no = d.so_no
									where s.customer_po_no in (
									select distinct po_no from mps_header where po_line_no > 9 and status = 'FM')
									group by s.so_no, s.customer_po_no
									having count(line_no) < 10
					)ds on mh.po_no = ds.customer_po_no
					left outer join (select wo_no, CAST(
													case when j=0 then '-' else CAST(j as char(2))+' x '+containers end 
												   as varchar(20)) as containers 
									 from (select wo_no, count(distinct container_no) as j, containers as containers 
									 from ztb_shipping_detail group by wo_no, containers) a
					) shipp_d on mh.work_order = shipp_d.wo_no
					$where
					order by so.line_no,cr_date";
			}else{
				$sql = "select distinct top 150 isnull(ship,1) SHIPPING ,work_order,po_no,po_line_no,CAST(mh.cr_date as varchar(10)) as cr_date,batery_type,cell_grade,mh.item_no,item_name,
						isnull(qty,0) Qty_order,isnull(qty_prod,0) Qty_Produksi,isnull(qty_plan,0) qty_plan, isnull(qty_invoiced,0) qty_invoiced,
						inv.si_no,so_no,so.line_no, 
						case when requested_etd = '1900-01-01' then '' else ISNULL(CAST(requested_etd as varchar(10)), '') end etd, 
						CAST(stuffy_date as varchar(10)) as stuffy_date, CAST(inv.etd as varchar(10)) as etd_ans, CAST(eta as varchar(10)) as eta, sh.shipping_type, 
					customer_code, so.curr_code, so.u_price, case when mh.item_no=zi.item_no then 'Y' else 'N' end as item_set, inv.crs_remark	
					from mps_header mh
						left join ztb_item zi on mh.item_no = zi.item_no
						left outer join (select wo_no,sum(slip_quantity) qty_prod from production_income group by wo_no) pi on mh.work_order = pi.wo_no
						left outer join (select sum(isnull(do_so.qty,0)) qty_invoiced,work_no, crs_remark, si_no, stuffy_date, etd, eta from answer 
									left join do_so on do_so.answer_no = answer.answer_no 
									group by work_no, crs_remark, si_no, stuffy_date, etd, eta)inv on mh.work_order = inv.work_no
						left outer join (select max(si_no) si_no,cust_si_no, shipping_type from  si_header group by cust_si_no, shipping_type) sh on sh.cust_si_no = mh.po_no
						left join (select soh.customer_code, soh.so_no, soh.customer_po_no, soh.curr_code, sod.u_price, sod.line_no,sod.item_no from so_header soh
								inner join so_details sod on soh.so_no=sod.so_no)so on mh.po_no = so.customer_po_no and mh.item_no = so.item_no
						left outer join (select wo_no,sum(CAST(Qty as int)) qty_plan,isnull(count(isnull(wo_no,1)),1) ship from ztb_shipping_plan group by wo_no) zt on zt.wo_no = mh.work_order
						left outer join (
									select distinct s.customer_po_no  from so_header s
									inner join so_Details d on s.so_no = d.so_no
									where s.customer_po_no in (
									select distinct po_no from mps_header where po_line_no > 9 and status = 'FM')
									group by s.so_no, s.customer_po_no
									having count(line_no) < 10
					)ds on mh.po_no = ds.customer_po_no
					left outer join (select wo_no, CAST(
													case when j=0 then '-' else CAST(j as char(2))+' x '+containers end as varchar(20)) as containers 
									 from (select wo_no, count(distinct container_no) as j, containers as containers 
										   from ztb_shipping_detail group by wo_no, containers) a
					) shipp_d on mh.work_order = shipp_d.wo_no
					$where
					order by so.line_no,cr_date";
			}
	}


	// echo $sql.'<br/>';
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);

		$w = "'".$items[$rowno]->WORK_ORDER."'";
		$p = "'".$items[$rowno]->CRS_REMARK."'";

		$q = $items[$rowno]->QTY_ORDER;
		$items[$rowno]->QTY_ORDER = number_format($q);
		
		$e = $items[$rowno]->QTY_PRODUKSI;
		$items[$rowno]->QTY_PRODUKSI = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_kuraire('.$w.')"  style="text-decoration: none; color: black;">'.number_format($e).'</a>';
		$items[$rowno]->QTY_PRODUKSI_VALUE = $e;

		$f = $items[$rowno]->QTY_INVOICED;
		$items[$rowno]->QTY_INVOICED = '<a href="javascript:void(0)" title="'.$f.'" onclick="info_invoiced('.$w.')"  style="text-decoration: none; color: black;">'.number_format($f).'</a>';
		$items[$rowno]->QTY_INVOICED_VALUE = $f;

		$g = $items[$rowno]->QTY_PLAN;
		$items[$rowno]->QTY_PLAN = '<a href="javascript:void(0)" title="'.$g.'" onclick="info_plan('.$w.','.$p.')"  style="text-decoration: none; color: black;">'.number_format($g).'</a>';
		$items[$rowno]->QTY_PLAN_VALUE = $g;

        $items[$rowno]->ACTION_ADD = 'T';
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
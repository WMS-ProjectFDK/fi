<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$cmb_so_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_so_no = isset($_REQUEST['ck_so_no']) ? strval($_REQUEST['ck_so_no']) : '';
	$cr_date = isset($_REQUEST['cr_date']) ? strval($_REQUEST['cr_date']) : '';
	$ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	$src = trim($srce);

	if ($ck_so_no != "true"){
		$so_no = "sod.so_no = '$cmb_so_no' and ";
	}else{
		$so_no = "";
	}

	if ($ck_cr_date != "true"){
		$CRD = "to_char(mpsh.cr_date,'yyyy-mm-dd') = '".trim($cr_date)."' and ";
	}else{
		$CRD = "";
	}

	if ($src !='') {
		$where ="where (sod.so_no like '%$src%') and sod.bal_qty > 0 ";
	}else{
		$where ="where $so_no $CRD sod.etd between to_date('$date_awal','YYYY-MM-DD') and to_date('$date_akhir','YYYY-MM-DD') and sod.bal_qty > 0 ";
	}
	
	include("../connect/conn.php");

	#PRF 
  	$sql  = "select sod.so_no, sod.line_no, soh.customer_code, soh.customer_po_no, c.company as customer_dtl, sod.item_no,i.item, i.description, 
		sod.origin_code, cou.country_code, to_char(sod.etd, 'yyyy-mm-dd') as etd_date, 
		mpsh.po_no, mpsh.po_line_no,sod.line_no, mpsh.work_order, nvl(to_char(mpsh.cr_date, 'yyyy-mm-dd'),to_char(ans.cr_date, 'yyyy-mm-dd')) as cr_date, sod.qty as qty_so, 
		ans.vessel, to_char(ans.data_date,'yyyy-mm-dd') as data_date, ans.answer_no, sod.u_price, soh.curr_code, ans.si_no, ans.qty as plan_qty,
		(select sum(qty) from answer where so_no=ans.so_no and item_no=ans.item_no) as qty_answer, to_char(ans.eta, 'yyyy-mm-dd') as eta_date
		from so_details sod
		inner join so_header soh on sod.so_no=soh.so_no
		left join mps_header mpsh on soh.customer_po_no= mpsh.po_no AND sod.line_no= mpsh.po_line_no
		left join company c on soh.customer_code = c.company_code
		left join item i on sod.item_no = i.item_no AND sod.origin_code = i.origin_code
		left join country cou on sod.origin_code=cou.country_code
		left join answer ans on sod.so_no=ans.so_no AND sod.line_no=ans.so_line_no
		$where
		order by i.description,sod.item_no,sod.origin_code,sod.so_no desc ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$item_no = $items[$rowno]->ITEM_NO;
		$itm = $items[$rowno]->ITEM;
		$items[$rowno]->ITEM = $item_no." - ".$itm."<br/>".$items[$rowno]->DESCRIPTION;
		$QTY_SO = $items[$rowno]->QTY_SO;
		$QTY_ANS = $items[$rowno]->QTY_ANSWER;
		$QTY_PLAN = $items[$rowno]->PLAN_QTY;

		if($QTY_SO == $QTY_ANS){
			$items[$rowno]->FLAG_QTY = 'CLOSE';
		}else{
			$items[$rowno]->FLAG_QTY = 'OPEN';	
		}

		$items[$rowno]->QTY_SO = number_format($QTY_SO);
		$items[$rowno]->QTY_ANSWER = number_format($QTY_ANS);
		$items[$rowno]->PLAN_QTY = number_format($QTY_PLAN);

		$answ = $items[$rowno]->ANSWER_NO;
		if($answ!=''){
			$items[$rowno]->FLAG_ANS = 'Y';	
		}else{
			$items[$rowno]->FLAG_ANS = 'N';
		}
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
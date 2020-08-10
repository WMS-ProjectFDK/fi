<?php
	include("../../connect/conn.php");
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	
	$sql = "select sum(SO) as so, sum(available) as available, sum([plan]) as [plan], sum(invoice) as invoice from (
		select distinct 
		CAST(aa.so as int) as SO,
		CAST(bb.slip_qty as int) as available,
		CAST(aa.[plan] as int) [plan],
		CAST(aa.invoice as int) invoice
		from (
		select distinct wo_no, sum(CAST(q_order as int)) as so, 
		sum(CAST(q_plan as int)) as [plan], sum(CAST(q_invoice as int)) as invoice  from
		( 
		select distinct b.wo_no, d.answer_no, mh.qty as q_order, b.q_plan, d.qty as q_invoice from do_so d
		inner join (select ans.answer_no, isnull(zp.qty,0) as q_plan, isnull(zp.wo_no,ans.work_no) as wo_no from answer ans 
					left join ztb_shipping_plan zp on ans.work_no = zp.wo_no) b on d.answer_no = b.answer_no
		inner join mps_header mh on b.wo_no = mh.work_order
		where d.do_no='$id'
		) ax
		GROUP by wo_no
		)aa
		left outer join (select pi.wo_no, isnull(sum(pi.slip_quantity),0) as slip_qty from production_income pi group by pi.wo_no ) bb on aa.wo_no = bb.wo_no
	) az";
		
	$data = sqlsrv_query($connect, strtoupper($sql));
	$rowno=0;
	$result = array();		$arrData = array();
	while ($row=sqlsrv_fetch_object($data)){
		array_push($arrData, $row);
		$s = $arrData[$rowno]->SO;
		$a = $arrData[$rowno]->AVAILABLE;
		$p = $arrData[$rowno]->PLAN;
		$i = $arrData[$rowno]->INVOICE;

		if(floatval($a) >= floatval($s)) {
			$arrData[0]->STS = 'Y';
		}else{
			$arrData[0]->STS = 'N';
		}

		$arrData[$rowno]->SO = number_format($s);
		$arrData[$rowno]->AVAILABLE = number_format($a);
		$arrData[$rowno]->PLAN = number_format($p);
		$arrData[$rowno]->INVOICE = number_format($i);

		$rowno++;
	}
	$result["rows"] = $arrData;
	echo json_encode($result);
?>
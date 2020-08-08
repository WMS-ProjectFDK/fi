<?php
	header("Content-type: application/json");
	
	$id_kanban = isset($_REQUEST['id_kanban']) ? strval($_REQUEST['id_kanban']) : '';
	$items = array();
	$total = 0;
	$sts = '';
	$rowno = 0;
	
	include("../../connect/conn.php");

	$cek = "select count(*) AS JUM from (select * from ztb_p_plan 
		inner join (select po_no, po_line_no,work_order 
		            from mps_header
		)ss on ztb_p_plan.wo_no = ss.work_order 
		where id= '$id_kanban' and date_prod > '01-DEC-19')";
	$result_cek = oci_parse($connect, $cek);
	oci_execute($result_cek);
	$row_cek=oci_fetch_object($result_cek);
	if ($row_cek->JUM > 0){
		$total = $row_cek->JUM;
		$sql= "select * from ztb_p_plan 
			inner join (select po_no, po_line_no,work_order 
			            from mps_header
			)ss on ztb_p_plan.wo_no = ss.work_order 
			where id= '$id_kanban' and date_prod > '01-DEC-19'";
		$result_sql = oci_parse($connect, $sql);
		oci_execute($result_sql);
		
		while($row_sql=oci_fetch_object($result_sql)) {
			array_push($items, $row_sql);

			$q = $items[$rowno]->QTY_PROD;
			$items[$rowno]->QTY_PROD = number_format($q);

			$wo = $items[$rowno]->WO_NO;
			$plt = $items[$rowno]->PLT_NO;

			$cek_sts = "select  zfn_fact('$wo','$plt','$id_kanban') as status from dual";
			$result_cek_sts = oci_parse($connect, $cek_sts);
			oci_execute($result_cek_sts);
			$row_cek_sts = oci_fetch_object($result_cek_sts);
			$sts = $row_cek_sts->STATUS;
		}
	}

	$result["total"] = $total;
	$result["state"] = $sts;
	$result["rows"] = $items;

	echo json_encode($result);
?>
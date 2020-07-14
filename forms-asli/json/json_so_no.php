<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select  distinct
			soh.customer_code,
			c.company customer,
			sod.so_no,
			soh.customer_po_no,
			to_char(sod.upto_date,'dd/mm/yyyy') so_ope_date,
			soh.customer_po_no,
			to_char(a.operation_date,'dd/mm/yyyy') ans_ope_date
			from so_header  soh,
			( select so_no,max(s.upto_date) upto_date
			  from so_details s,item i
			  where i.item_no = s.item_no and bal_qty >0
			--and i.section_code='$section_code'
			  group by so_no
			) sod,
			company c,
			(select so_no,max(operation_date) operation_date from answer group by so_no,so_line_no) a
			where sod.so_no = soh.so_no (+)
			and sod.so_no = a.so_no (+)
			and soh.customer_code = c.company_code (+)
			--and  soh.customer_code  = '$customer_code'
			order by soh.customer_code";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		$arrData[$arrNo] = array(
			"so_no"=>rtrim($row->SO_NO),
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
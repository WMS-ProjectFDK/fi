<?php


include("../../connect/conn.php");
$sql = "BEGIN ZSP_VALIDATE_DATECODE(); END;";
// $sql = "Insert into mte_header
// 							    (Slip_no, 
// 							     slip_date, 
// 							     company_code, 
// 							     slip_type, 
// 							     display_type, 
// 							     section_code, 
// 							     person_code)
// 							select distinct    s.slip_no, 
// 								   z.date_in,
// 								   '100001',
// 								   '21',
// 								   'C',
// 								   100,
// 								   'KANBAN'
// 							from mte_details s
// 							inner join ztb_wh_kanban_trans z
// 								on s.slip_no = z.slip_no
// 							left outer join mte_header r
// 								on s.slip_no = r.slip_no
// 							where  r.slip_no is null and s.reg_date > '01-NOV-18'
// 							group by s.slip_no, z.date_in
// 							having count(s.slip_no) = count(z.slip_no)";
			$stmt = oci_parse($connect, $sql);
			$res = oci_execute($stmt);
			$pesan = oci_error($stmt);
		// if($rowno == 100){
		// 	break;
		// };
	
	
	// $result["rows"] = $items;
	// echo json_encode($result);
?>
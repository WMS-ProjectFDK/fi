<?php
	session_start();
	include("../../connect/conn.php");

	$sql = "select i.item_no, description, i.pi_no, drawing_no, plt_spec_no, p.pallet_size_type_name, GW_pallet,NW_Pallet,carton_height pallet_height,
       	step, two_feet, four_feet, pallet_pcs, pallet_ctn
		from item i
		inner join packing_information pi on i.pi_no = pi.pi_no
		inner join ztb_item z on i.item_no = z.item_no
		inner join pallet_size_type p on pi.pallet_size_type = p.pallet_size_type_code";
	$data_sql = sqlsrv_query($connect, $sql);	

	$items = array();
	
	while($row = sqlsrv_fetch_object($data_sql)){
		array_push($items, $row);
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>
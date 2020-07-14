<?php
	include("../../connect/conn.php");
	// header("Content-type: application/json");

	$si_no = isset($_REQUEST['si_no']) ? strval($_REQUEST['si_no']) : '';

	$sql = "select case when si.shipping_type = 'FCL' then si.shipping_type || ' ' ||
	case when sum(container_value) = 0 then 1 else ceil(sum(container_value))  end 
	|| 'x' || containers || ' SHIPMENT ' else 'LCL SHIPMENT' end
	SHIPMENT ,case when si.shipping_type in ('FCL','LCL') then 'OCEAN' ELSE 
				case when si.shipping_type in ('BY AIR') then 'AIR' end end  st,si.emkl_name,si.forwarder_name
	from answer aa 
	inner join si_header si on aa.si_no = si.si_no 
	left outer join ztb_shipping_detail cc on aa.answer_no = cc.answer_no
	where aa.si_no = '$si_no'
	group by containers,si.shipping_type,si.emkl_name,si.forwarder_name";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	$shipment = "";
	$forwarder = "";
	$emkl = "";	
	while($row = oci_fetch_object($data)){
		// $shipment = $shipment.$row->SHIPPING.'';
		// $shipping_type = $row->ST;
		// $EMKL = $row->EMKL_NAME;
		// $FORWARDER = $row->FORWARDER_NAME;
		array_push($items, $row);
		$rowno++;
	}

	// $items = array(
	// 	"SHIPMENT"=>$shipment,
	// 	"SHIPMENT_TYPE"=>$shipping_type
	// );

	//$result["rows"] = $items;
	echo json_encode($items);
?>
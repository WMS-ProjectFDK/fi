<?php
session_start();
include("../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;
$arrData = array();	

$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';
$plt = isset($_REQUEST['plt']) ? strval($_REQUEST['plt']) : '';

$sql = "select wo,z.item, start_carton+(($plt-1)*pallet_ctn_number) as StartCarton, 
	total_carton, ceil(quantity/external_unit_number/pi.pallet_ctn_number) as TotalPallet,
	pi.pallet_ctn_number,external_unit_number/package_unit_number  as Units,start_carton as STRCTN 
	from ztb_amazon_wo  z
	inner join item i on z.item = i.item_no
	left outer join packing_information pi on i.pi_no = pi.pi_no 
	where wo = '$wo'";
$data = oci_parse($connect, $sql);
oci_execute($data);

while ($row=oci_fetch_object($data)){
	array_push($arrData, $row);
	$arrNo++;
}
echo json_encode($arrData);
?>
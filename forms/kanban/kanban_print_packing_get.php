<?php
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");

$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';

$cek = "select  r.work_order,
r.qty,
ceil(r.qty/ pi.pallet_unit_number) PALLET, 
case when (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number)) = 0
 then pi.pallet_unit_number 
  else (qty - (floor(r.qty/ pi.pallet_unit_number) * pi.pallet_unit_number)) end QtyPltAkhir
from mps_header r
inner join item i on r.item_no = i.item_no
inner join packing_information pi on pi.pi_no = i.pi_no 
where r.work_order = '$wo_no'  " ;

// $array = oci_parse($connect, $cek);

// oci_execute($array);
// $items = array();
// $i= 1;


// while($row=oci_fetch_array($array))

// {
// 	array_push($items, $row);
// 	// while($i <= $row["PALLET"]){
		
		
// 	// 	$i++;
// 	// 	array_push($items,$array);
// 	// 	// array_push($items, $row);
// 	// }
// 	$rowno++;

// }



// $result["rows"] = $items;
// echo json_encode($result);


$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);


$items = array();
$rowno=0;

while($row = oci_fetch_object($data_cek)){
	array_push($items, $row);

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);

?>
<!-- 
$result = mysqli_query($con,"SELECT * FROM wp_marketcatagories");
    $data =array();
    while($row = mysqli_fetch_array($result))
                 {
                 $data[] = array_push($data, array('id' => $row['id']));
                 }
    $json = json_encode($data);
    echo $json; -->
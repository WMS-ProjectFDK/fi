<?php 
include("../../connect/conn.php"); 
$sql = "select * from (select * from zvw_suspend_list where qty_suspend <> 0 order by suspend_date_1 desc)
	where rownum <= 20";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	
	$q = $items[$rowno]->QTY_SUSPEND;
	$items[$rowno]->QTY_SUSPEND = number_format($q);

	$id = $items[$rowno]->ID_PRINT;
	$items[$rowno]->ACTION = '<a href="javascript:void(0)" onclick="view_details('.$id.')"  style="text-decoration: none; ">VIEW</a>';
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
<?php
$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : ''; 
include("../../connect/conn.php"); 

$sql = "select (select name from ztb_worker where ztb_worker.worker_id = ztb_kanban_lbl.Worker_ID) as worker,
	  StartDate,LotNumber,asyline,labelline,nvl(battery_IN,0)  + nvl(qtysisaproduksi,0) as qty, mulai, plt_no  
	  from ztb_kanban_lbl 
	  inner join ztb_l_plan on  ztb_kanban_lbl.idkanban = ztb_l_plan.id  
	  where wo_no = '$wo'  and battery_IN > 0 
	  order by plt_no";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY; 	
	$items[$rowno]->QTY = number_format($q);	

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
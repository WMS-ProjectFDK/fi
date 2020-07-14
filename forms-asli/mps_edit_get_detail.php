<?php
session_start();
ini_set('max_execution_time', -1);
include("../connect/conn.php");

	$po_no = isset($_REQUEST['po_no']) ? strval($_REQUEST['po_no']) : '';
	$po_line_no = isset($_REQUEST['po_line_no']) ? strval($_REQUEST['po_line_no']) : '';

    

$cek = "select '0' EDIT_TYPE,s.po_no,r.qty,s.po_line_no,TO_CHAR(mps_date, 'YYYY-MM-DD') mps_date,mps_qty,TO_CHAR(mps_date, 'YYYY-MM-DD') OLD_MPS_DATE
from mps_details s
inner join mps_header r
on r.po_no = s.po_no and r.po_line_no = s.po_line_no 
where s.po_no = '$po_no' and s.po_line_no = '$po_line_no' " ;

$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);


$items = array();
$rowno=0;

while($row = oci_fetch_object($data_cek)){
	array_push($items, $row);
	$q = $items[$rowno]->MPS_QTY;
	$items[$rowno]->MPS_QTY = number_format($q);

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
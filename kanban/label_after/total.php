<?php
header("Content-type: application/json");
$label = isset($_REQUEST['label']) ? strval($_REQUEST['label']) : '';
$arrData = array();
$arrNo = 0;
$q=0;

include("../../connect/conn.php");

$sql = "select a.id_print, sum(a.qty-(a.qty_terpakai+ a.ng_qty)-b.qty_antri) as total from ztb_lbl_trans a
	inner join (select id_print, sum(qty_in_antrian) as qty_antri from ztb_lbl_trans group by id_print)b on a.id_print = b.id_print
	where a.remark='RESULT' and a.status=0 and replace(a.labelline,'#','-') = '$label'
	group by a.id_print";
$result = oci_parse($connect, $sql);
oci_execute($result);

while($row = oci_fetch_object($result)){
	$q += $row->TOTAL;
}

$sql2 = "select sum(qty_in_antrian-(qty_terpakai+ ng_qty)) as total from ztb_lbl_trans
	where remark='RESULT' and status=2 and replace(labelline,'#','-') = '$label'";

$result2 = oci_parse($connect, $sql2);
oci_execute($result2);

while($row2 = oci_fetch_object($result2)){
	$q += $row2->TOTAL;
}

$qnum = number_format($q);
array_push($arrData, array('TOTAL'=>$qnum));
echo json_encode($arrData);
?>
<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");

$arrData = array();
$arrNo = 0;

$in = "select sum(qty_act_perpallet) as pcs, sum(qty_act_perbox) as box from ztb_assy_kanban
	where id_print in
	(select id_print from (
	select distinct id_print, id_plan, max(position) as position from ztb_assy_heating
	group by id_print, id_plan)
	where position=1)";
$data_in = oci_parse($connect, $in);
oci_execute($data_in);
$row_in = oci_fetch_object($data_in);

$out = "select sum(qty_act_perpallet) as pcs, sum(qty_act_perbox) as box from ztb_assy_kanban
	where id_print in
	(select id_print from (
	select distinct id_print, id_plan, max(position) as position from ztb_assy_heating
	group by id_print, id_plan)
	where position=2)";
$data_out = oci_parse($connect, $out);
oci_execute($data_out);
$row_out = oci_fetch_object($data_out);

$arrData[$arrNo] = array("in"=>number_format($row_in->PCS).' pcs<br/>'.number_format($row_in->BOX).' box', 
						 "out"=>number_format($row_out->PCS).' pcs<br/>'.number_format($row_out->BOX).' box');
echo json_encode($arrData);
?>
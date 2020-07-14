<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$slip = isset($_REQUEST['slip']) ? strval($_REQUEST['slip']) : '';

if($slip=='21' || $slip=='25'){
	$kode='MT-'.date('y')."-";
}elseif ($slip=='20' || $slip=='05') {
	$kode="RMT-".date('y')."-";
}else{
	$kode='';
}
$arrData = array();
$arrNo = 0;

if($kode!=''){
	$sql = "select '$kode'||coalesce(lpad(cast(cast(max(substr(slip_no,-5)) as integer)+1 as varchar(5)),5,'0'),'00001') as no_urut 
		from mte_header where slip_no like '$kode%'";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$row = oci_fetch_object($data);

	$kd =  $row->NO_URUT;

	if ($data) {
	    $arrData[$arrNo] = array("kode"=>$kd);
	}
	echo json_encode($arrData);	
}else {
	$arrData[$arrNo] = array('kode'=>'UNDEFINED');
	echo json_encode($arrData);	
}

?>
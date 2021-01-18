<?php
session_start();
include("../../../../connect/conn.php");
header("Content-type: application/json");
$slip = isset($_REQUEST['slip']) ? strval($_REQUEST['slip']) : '';

if($slip=='21' || $slip=='25'){
	$kode='SP-MT-'.date('y')."-";
}elseif ($slip=='20' || $slip=='05') {
	$kode="SP-RMT-".date('y')."-";
}elseif ($slip=='98') {
	$kode="SP-DISP-".date('y')."-";	
}elseif ($slip=='80') {
	$kode="SP-FT-".date('y')."-";	
}else{
	$kode="SP-OTHER-".date('y')."-";	
}
$arrData = array();
$arrNo = 0;
if($kode!=''){
	$sql = "select '$kode'
	+ isnull(
		case LEN(max(right(slip_no,5))+1)
			when '1' then '0000' + cast(max(right(slip_no,5)) +1 as varchar(10))
			when '2' then '000' + cast(max(right(slip_no,5)) +1 as varchar(10))
			when '3' then '00' + cast(max(right(slip_no,5)) +1 as varchar(10))
			when '4' then '0' + cast(max(right(slip_no,5)) +1 as varchar(10))
			when '5' then  cast(max(right(slip_no,5)) +1 as varchar(10))
		end
   ,'00001')   as no_urut 
		from sp_mte_header where slip_no like '$kode%'";

	$data = sqlsrv_query($connect, strtoupper($sql));
	$row = sqlsrv_fetch_object($data);

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
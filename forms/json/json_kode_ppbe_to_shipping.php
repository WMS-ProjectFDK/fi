<?php
session_start();
$user = $_SESSION['id_wms'];
include("../../connect/conn.php");
header("Content-type: application/json");
$arrNo = 0;

// $user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';

$cek_user = "select substring(person,1,1) as initi from person where person_code='$user' ";
$data_user = sqlsrv_query($connect, strtoupper($cek_user));
$rowArr = sqlsrv_fetch_array($data_user);
// echo $cek_user;
// echo '<br/>';
// echo $rowArr[0];
/*update ueng (2018-12-13)*/
if(date('Y') != '2018'){
	$sql = "select coalesce(REPLACE(STR(CAST(max(substring(CRS_REMARK,1,3)) as integer)+1,3),' ','0'),'001')
	+ '/' 
	+ '".$rowArr[0]."'
	+ '/'
	+ RIGHT(CONVERT(varchar(4), GETDATE(),102),2) as ppbe_no
	from answer
	where CONVERT(varchar(4), data_date,102) >= '2019'
	and LEN(crs_remark) = 8
	and RIGHT(crs_remark,2) = RIGHT(CONVERT(varchar(4), GETDATE(),102),2)
	and substring(crs_remark,5,1) = '".$rowArr[0]."'";
}

// echo $sql;
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);
$kd =  $row->PPBE_NO;
$arrData[$arrNo] = array("kode"=>$kd);
echo json_encode($arrData);
?>
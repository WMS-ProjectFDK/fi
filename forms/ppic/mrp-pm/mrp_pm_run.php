<?php
set_time_limit(0);
include("../../../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$arrData = array();
$arrNo = 0;
$msg = '';

echo date("Y-m-d H:i:s").' - START<br/>';
$sql = "{call zsp_mrp_pm}";
$stmt = sqlsrv_query($connect, $sql);

if( $stmt === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}

echo date("Y-m-d H:i:s").' - '.$arrData[$arrNo].'<br>';
$arrNo++;

//echo json_encode($arrData);
echo date("Y-m-d H:i:s").' - '.$arrData[$arrNo].'<br/>';
echo "<br/>".date("Y-m-d H:i:s").' - FINISH<br/>';
?>
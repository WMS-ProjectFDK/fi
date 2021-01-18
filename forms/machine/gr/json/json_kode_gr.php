<?php
session_start();
include("../../../../connect/conn.php");
header("Content-type: application/json");
$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
$gr_date = isset($_REQUEST['gr_date']) ? strval($_REQUEST['gr_date']) : '';
$gr_sts = isset($_REQUEST['gr_date']) ? strval($_REQUEST['gr_date']) : '';

$arrData = array();
$arrNo = 0;

$sql = "select count(*) as jum from sp_gr_header where gr_no='$gr'";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);

if($gr_sts=='NEW'){
	if (intval($row->JUM) == 0){
		$sql2 = "select count(*) as j from sp_whinventory 
			where this_month = (SELECT CONVERT(nvarchar(6), cast(GETDATE() as date), 112))";
	
		$data2 = sqlsrv_query($connect, strtoupper($sql2));
		$row2 = sqlsrv_fetch_object($data2);
		if (intval($row2->J) > 0){
			$sql3 = "select count(*) as tot from  sp_whinventory where CONVERT(nvarchar(6), cast('$gr_date' as date), 112) between last_month and this_month";
			$data3 = sqlsrv_query($connect, strtoupper($sql3));
			$row3 = sqlsrv_fetch_object($data3);
			if (intval($row3->TOT) > 0){
				$arrData[$arrNo] = array("kode"=>'SUCCESS');
			}else{
				$arrData[$arrNo] = array("kode"=>'Period Inventory not Found');	
			}
		}else{
			$arrData[$arrNo] = array("kode"=>'Period Inventory not Found');	
		}
	}else{
		$arrData[$arrNo] = array("kode"=>'Goods Receive No. Already exist');
	}
}else{
	$sql3 = "select count(*) as total from  sp_whinventory where CONVERT(nvarchar(6), cast('$gr_date' as date), 112) between last_month and this_month";
	$data3 = sqlsrv_query($connect, strtoupper($sql3));
	$row3 = sqlsrv_fetch_object($data3);
	if (intval($row3->TOTAL) > 0){
		$arrData[$arrNo] = array("kode"=>'SUCCESS');
	}else{
		$arrData[$arrNo] = array("kode"=>'Period Inventory not Found');	
	}

}

echo json_encode($arrData);
?>
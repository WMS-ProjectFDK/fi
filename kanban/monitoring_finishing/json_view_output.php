<?php
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn_kanbansys.php");
$items = array();
$rowno = 0;

$tgl = date('Y-m-d');
$tambah_date = strtotime('+1 day',strtotime($tgl));
$kurang_date = strtotime('-1 day',strtotime($tgl));
$t_date = date('Y-m-d',$tambah_date);
$k_date = date('Y-m-d',$kurang_date);

if(intval(date('H')) < 7){
	$a1 = $k_date.' 07:00:00'; 		$z1 = $k_date.' 14:59:59';
	$a2 = $k_date.' 15:00:00'; 		$z2 = $k_date.' 22:59:59';
	$a3 = $k_date.' 23:00:00'; 		$z3 = $tgl.' 06:59:59';
	$at = $k_date.' 07:00:00'; 		$zt = $tgl.' 06:59:59';
}else{
	$a1 = $tgl.' 07:00:00'; 		$z1 = $tgl.' 14:59:59';
	$a2 = $tgl.' 15:00:00'; 		$z2 = $tgl.' 22:59:59';
	$a3 = $tgl.' 23:00:00'; 		$z3 = $t_date.' 06:59:59';
	$at = $tgl.' 07:00:00'; 		$zt = $t_date.' 06:59:59';	
}

$sql = "select 
	ISNULL((select sum(qty) qty from finishing_qty_trans where id_machine=3 and time between '$a1' and '$z1'),0) as shift_1,
	ISNULL((select sum(qty) qty from finishing_qty_trans where id_machine=3 and time between '$a2' and '$z2'),0) as shift_2,
	ISNULL((select sum(qty) qty from finishing_qty_trans where id_machine=3 and time between '$a3' and '$z3'),0) as shift_3,
	ISNULL((select sum(qty) qty from finishing_qty_trans where id_machine=3 and time between '$at' and '$zt'),0) as total
";
$rs = odbc_exec($connect,$sql);

while ($row = odbc_fetch_object($rs)){
	array_push($items, $row);

	$q1 = $items[$rowno]->shift_1;
	$items[$rowno]->shift_1 = number_format($q1);

	$q2 = $items[$rowno]->shift_2;
	$items[$rowno]->shift_2 = number_format($q2);

	$q3 = $items[$rowno]->shift_3;
	$items[$rowno]->shift_3 = number_format($q3);

	$qt = $items[$rowno]->total;
	$items[$rowno]->total = number_format($qt);
	$rowno++;
}

echo json_encode($items);
?>
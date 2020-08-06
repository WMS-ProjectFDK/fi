<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$s = $query->s;
		$g = $query->g;
		$n = $query->n;
		$m = $query->m;
		$d = $query->d;

		if($s == 'BREAKDOWN CONTAINER'){
			$sql = "update ztb_shipping_detail set gross=$g, net=$n, msm=$m where ppbe_no='$d' ";
		}elseif($s == 'PACKING LIST'){
			$sql = "update ztb_shipping_ins set gw=$g, nw=$n, msm=$m where remarks='$d' ";
		}elseif($s == 'INVOICE'){
			$sql = "update do_pl_header set gross=$g, net=$n, measurement=$m where do_no='$d' ";
		}

		$data = sqlsrv_query($connect, $sql);

		$pesan = oci_error($data);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " Procedure Insert - GR Process Error : $sql";
			break;
		}
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}

?>
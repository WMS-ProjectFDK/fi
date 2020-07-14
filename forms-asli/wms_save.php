<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../connect/conn.php");

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$grn = $query->wh_grn;
		$line = $query->wh_line;
		$qty = $query->wh_qty;
		$item = $query->wh_item;
		$pallet = $query->wh_pallet;
		$qtypallet = $query->wh_qtypallet;

		$p = intval($pallet);	$q=intval($qty);
		$j=intval($qtypallet);
		$time =microtime(true);
	    $micro_time=sprintf("%d",($time - floor($time)) * 1000000);
	    $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
	    $dtime = $date->format("YmdHisu");

	    $ins = "INSERT INTO ztb_wh_in_det(gr_no,line_no,qty,item_no,pallet,tanggal) VALUES ('$grn','$line',$qtypallet,'$item',$pallet,'$dtime')";
		$result = oci_parse($connect, $ins);
	  	oci_execute($result);
	  	$pesan = oci_error($result);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " Incoming Process Error : $ins";
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

	//include("../connect/conn.php");
	
	//$dt = date('YmdHis');	

	
	//if($p != 0 AND $qtypallet != 0){
		/* cari max dan sum(qty) untuk disamakan dg $qty dan $pallet */
		/*$cek_pallet = "select coalesce(max(a.pallet),0) as pallet from ztb_wh_in_det a where a.gr_no='$grn' and a.item_no='$item' and a.line_no='$line' ";
		$cek1 = oci_parse($connect, $cek_pallet);
		oci_execute($cek1);
		$row1 = oci_fetch_array($cek1);*/
		//cek qty
		/*$cek_qty = "select coalesce(sum(a.qty),0) as qty from ztb_wh_in_det a where a.gr_no='$grn' and a.item_no='$item' and a.line_no='$line' ";
		$cek2 = oci_parse($connect, $cek_qty);
		oci_execute($cek2);
		$row2 = oci_fetch_array($cek2);*/

		//cek line
		/*$cek_qty = "select distinct a.line_no from ztb_wh_in_det a where a.gr_no='$grn' and a.item_no='$item' ";
		$cek3 = oci_parse($connect, $cek_qty);
		oci_execute($cek3);
		$row3 = oci_fetch_array($cek3);		*/

		/*if($row1[0] != $pallet AND $row2[0] != $qty){
			$del = "delete from ztb_wh_in_det where gr_no='$grn' and item_no='$item' and tanggal='$dt' and line_no='$line' ";
			$delNya = oci_parse($connect, $del);
		 	oci_execute($delNya);
		 	for($i=1;$i<=$p;$i++){
				if($i==$p){
					$jum_ulang = $j*$p;
					$jum_sisa = $q - $jum_ulang;
					$sisa = $j+$jum_sisa;
					$ins = "INSERT INTO ztb_wh_in_det(gr_no,line_no,qty,item_no,pallet,tanggal) VALUES ('$grn','$line',$sisa,'$item',$i,'$dt')";
					$result = oci_parse($connect, $ins);
				  	oci_execute($result);
				}else{*/
	
				/*}
			}
		}
	}*/
?>
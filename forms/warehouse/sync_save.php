<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
session_start();
include("../../connect/conn.php");

$user = $_SESSION['id_wms'];
$r = substr($rack,0,4);
$dtgl = date('Y-m-d H:i:s');
$success = 0;		$failed = 0;

$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$queries = json_decode($str);
$msg = '';

foreach($queries as $query){
	$idno = $query->sync_idno;
	$id = $query->sync_id;
	$type = $query->sync_type;
	$rack = $query->sync_rack;
	$qty = $query->sync_qty;
	$doc = $query->sync_doc;
	$item = $query->sync_item;
	$line = $query->sync_line;
	$pallet = $query->sync_pallet;

	
	if ($qty == '' OR $qty == 0){
		$msg .= " Qty not found";
		break;
	}else{
		if ($type == "INCOMING"){
			$qry_in = "update ztb_wh_in_det set rack='$rack', daterecord='$dtgl', userid='$user' where id=$idno";
			$result_in = sqlsrv_query($connect, $qry_in);
		

			if($result_in === false){
				$msg .= "Update Rack Process Error : $qry_in";
				break;
			}

	  		$qry1 = "update ztb_wh_trans set flag=1 where ID=$id";
			$result1 = sqlsrv_query($connect, $qry1);
		  

		  	if($result1 === false){
				$msg .= "Update ID Upload Process Error : $qry1";
				break;
			}
		}elseif ($type == "OUTGOING"){
			$cek = "select isnull(qty,0) as qty, isnull(qty_out,0) as qty_out from ztb_wh_in_det where id=$idno";
			$ceknya = sqlsrv_query($connect, strtoupper($cek));
		  	$cek_out = sqlsrv_fetch_array($ceknya);

		  	$q = $qty + intval($cek_out[1]);
		  	if(intval($cek_out[0]) == $q){
				if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
					$qry = "update ztb_wh_do_det set sticker_id='$idno' where do_no='$doc' and item_no='$item' and line_no='$line'";
					$result = sqlsrv_query($connect, $qry);
					if($result === false){
						$msg .= "1 Update DO Process Error : $qry";
						break;
					}
				}

				$qryy = "update ztb_wh_in_det set qty_out=$q, rack='' where id=$idno";
				$resultt = sqlsrv_query($connect, $qryy);
				if($resultt === false){
					$msg .= "2 Update DO Process Error : $qryy";
					break;
				}
			}else{
		  		if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
					$qry = "update ztb_wh_do_det set sticker_id='$idno' where do_no='$doc' and item_no='$item' and line_no='$line'";
					$result = sqlsrv_query($connect, $qry);
					if(!$result){
						$msg .= "3a Update DO Process Error : $qry";
						break;
					}
				  	
				}
		  		$qryyy = "update ztb_wh_in_det set qty_out=$q where id=$idno";
				$resulttt = sqlsrv_query($connect, $qryyy);
				if(!$resulttt){
					$msg .= "3b Update DO Process Error : $qryyy";
					break;
				}
		  	}
		  
	  		$ins = "update ztb_wh_out_det set [print]='1' where slip_no='$doc' ";
			$ins_Nya = sqlsrv_query($connect, $ins);
			if($ins_Nya === false){
				$msg .= "4 Update DO Process Error : $ins";
				break;
			}

	  		$qry2 = "update ztb_wh_trans set flag=1 where ID='$id'";
			$result2 = sqlsrv_query($connect, $qry2);
			if($result2 === false){
				$msg .= "5 Update DO Process Error : $qry2";
				break;
			}
		}elseif ($type == "CHANGE RACK"){
			$qry_change = "update ztb_wh_in_det set rack='$rack', daterecord='$dtgl', userid='$user' where id=$idno";
			$result_change = sqlsrv_query($connect, $qry_change);
			if($result_change === false){
				$msg .= "6 Update DO Process Error : $qry_change";
				break;
			}

	  		$qry_c = "update ztb_wh_trans set flag=1 where ID='$id'";
			$result_c = sqlsrv_query($connect, $qry_c);
			if($result_c === false){
				$msg .= "7 Update DO Process Error : $qry_c";
				break;
			}
		}
	}
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}	
?>
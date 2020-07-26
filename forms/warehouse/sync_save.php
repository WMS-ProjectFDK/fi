<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
session_start();
include("../connect/conn.php");

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
			$result_in = oci_parse($connect, $qry_in);
		  	$r_in = oci_execute($result_in);
		  	$pesan = oci_error($result_in);
			$msg = $pesan['message'];

			if($msg != ''){
				$msg .= "Update Rack Process Error : $qry_in";
				break;
			}

	  		$qry1 = "update ztb_wh_trans set flag=1 where ID=$id";
			$result1 = oci_parse($connect, $qry1);
		  	$r1 = oci_execute($result1);
		  	$pesan = oci_error($result1);
			$msg = $pesan['message'];

		  	if($msg != ''){
				$msg .= "Update ID Upload Process Error : $qry1";
				break;
			}
		}elseif ($type == "OUTGOING"){
			$cek = "select coalesce(qty,0) as qty, coalesce(qty_out,0) as qty_out from ztb_wh_in_det where id=$idno";
			$ceknya = oci_parse($connect, $cek);
		  	oci_execute($ceknya);
		  	$cek_out = oci_fetch_array($ceknya);

		  	$q = $qty + intval($cek_out[1]);
		  	if(intval($cek_out[0]) == $q){
				if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
					$qry = "update ztb_wh_do_det set sticker_id='$idno' where do_no='$doc' and item_no='$item' and line_no='$line'";
					$result = oci_parse($connect, $qry);
				  	oci_execute($result);
				  	$pesan = oci_error($result);
					$msg = $pesan['message'];
					if($msg != ''){
						$msg .= " Update DO Process Error : $qry";
						break;
					}
				}

				$qryy = "update ztb_wh_in_det set qty_out=$q, rack='' where id=$idno";
				$resultt = oci_parse($connect, $qryy);
			  	$r = oci_execute($resultt);
			  	$pesan = oci_error($resultt);
				$msg = $pesan['message'];
			  	if($msg != ''){
					$msg .= " Update Qty Out Process Error : $qryy";
					break;
				}
			}else{
		  		if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
					$qry = "update ztb_wh_do_det set sticker_id='$idno' where do_no='$doc' and item_no='$item' and line_no='$line'";
					$result = oci_parse($connect, $qry);
				  	oci_execute($result);
				}
		  		$qryyy = "update ztb_wh_in_det set qty_out=$q where id=$idno";
				$resulttt = oci_parse($connect, $qryyy);
			  	$r = oci_execute($resulttt);
			  	$pesan = oci_error($resulttt);
				$msg = $pesan['message'];
			  	if($msg != ''){
					$msg .= " Update Qty Out Process Error : $qryyy";
					break;
				}
		  	}
		  
	  		$ins = "update ztb_wh_out_det set print='1' where slip_no='$doc' ";
			$ins_Nya = oci_parse($connect, $ins);
		  	$r_Nya = oci_execute($ins_Nya);
		  	$pesan = oci_error($ins_Nya);
			$msg = $pesan['message'];
		  	if($msg != ''){
				$msg .= " Update Slip No. Process Error : $ins";
				break;
			}

	  		$qry2 = "update ztb_wh_trans set flag=1 where ID='$id'";
			$result2 = oci_parse($connect, $qry2);
		  	$r2 = oci_execute($result2);
		  	$pesan = oci_error($result2);
			$msg = $pesan['message'];
		  	if($msg != ''){
				$msg .= " Update ID upload Process Error : $qry2";
				break;
			}
		}elseif ($type == "CHANGE RACK"){
			$qry_change = "update ztb_wh_in_det set rack='$rack', daterecord='$dtgl', userid='$user' where id=$idno";
			$result_change = oci_parse($connect, $qry_change);
		  	$r_change = oci_execute($result_change);
		  	$pesan = oci_error($result_change);
			$msg = $pesan['message'];
		  	if($msg != ''){
				$msg .= " Update Rack Process Error : $qry_change";
				break;
			}

	  		$qry_c = "update ztb_wh_trans set flag=1 where ID='$id'";
			$result_c = oci_parse($connect, $qry_c);
		  	$r_c = oci_execute($result_c);
		  	$pesan = oci_error($result_c);
			$msg = $pesan['message'];
		  	if($msg != ''){
				$msg .= " Update ID upload Process Error : $qry_c";
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
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
		$pu_sts = $query->pu_sts;		//status approved
		$pu_jumPO = $query->pu_jumPO;	//jumlah PO
		$pu_prf = $query->pu_prf;
		$pu_line = $query->pu_line;
		$pu_date = $query->pu_date;
		$pu_cust_po_no = $query->pu_cust_po_no;
		$pu_ck_new = $query->pu_ck_new;
		$pu_rmark = $query->pu_rmark;
		$pu_item = $query->pu_item;
		$pu_line_ada = $query->pu_line_ada;
		$pu_unit = $query->pu_unit;
		$pu_s_price = $query->pu_s_price;
		$pu_require = $query->pu_require;
		$pu_qty = $query->pu_qty;
		$pu_amt = $query->pu_amt;
		$pu_ohsas = $query->pu_ohsas;

		$now=date('Y-m-d H:i:s');
		$user = $_SESSION['id_wms'];
		$now2=date('Y-m-d');

		if($pu_ck_new == 'true'){
			$sts = 1;
		}else{
			$sts = 0;
		}

		if ($pu_rmark == ''){
			$pu_rmark_fix = "'-'";
		}else{
			$rmk_s0 = explode('<br>', $pu_rmark);
			$rmk_f0 = '';
			for($f0=0;$f0<count($rmk_s0);$f0++){
				if($rmk_s0[$f0] != ''  || ! is_null($rmk_s0[$f0])) {
					if($f0 == count($rmk_s0)-1){
						$rmk_f0 .= "'".$rmk_s0[$f0]."'";
					}else{
						$rmk_f0 .= "'".$rmk_s0[$f0]."' || chr(13) || chr(10) || ";
					}
				}
			}
			$rmk_fix0 = str_replace("&amp;", "&", $rmk_f0);
			$pu_rmark_fix = "$rmk_fix0";
		}

		if($pu_line_ada == 'NEW'){
			//INSERT PRF DETAILS
			$q_max = "select isnull(cast(max(line_no) as int),0)+ as line_no from prf_details where prf_no='$pu_prf'";
			$data_max = sqlsrv_query($connect, strtoupper($q_max));
			$rowMax = sqlsrv_fetch_object($data_max);

			$pu_line = $rowMax->LINE_NO;

			$field_dtl  = "prf_no,"             ; $value_dtl  = "'$pu_prf',"							;
			$field_dtl .= "line_no,"            ; $value_dtl .= "$pu_line,"								;
			$field_dtl .= "item_no,"            ; $value_dtl .= "$pu_item,"								;
			$field_dtl .= "qty,"                ; $value_dtl .= "$pu_qty,"								;
			$field_dtl .= "uom_q,"              ; $value_dtl .= "$pu_unit,"								;
			$field_dtl .= "estimate_price,"     ; $value_dtl .= "$pu_s_price,"							;
			$field_dtl .= "amt,"                ; $value_dtl .= "round($pu_qty * $pu_s_price,2),"		;
			$field_dtl .= "require_date,"       ; $value_dtl .= "'$pu_require',"	;
			$field_dtl .= "upto_date,"          ; $value_dtl .= "getdate(),"								;
			$field_dtl .= "reg_date,"           ; $value_dtl .= "getdate(),"								;
			$msg1 .= " Add New Item Process Error  : $ins2";
			if($pu_sts=='1'){
				$field_dtl .= "remainder_qty,"     ; $value_dtl .= "$pu_qty,"							;		
			}

			$field_dtl .= "ohsas"               ; $value_dtl .= "'$pu_ohsas'"							;
			chop($field_dtl) ;                  chop($value_dtl) ;

			$ins2 = "insert into prf_details ($field_dtl) VALUES ($value_dtl)";

			$data_ins2 = sqlsrv_query($connect, strtoupper($ins2));
			
			$pesan = sqlsrv_errors($data_ins2);
			$msg .= $pesan['message'];
			if($msg != ''){
				$msg .= " Add New Item Process Error  : $ins2";
				break;
			}
		}else{
			$field_upd  = "qty=$pu_qty,"; 
			$field_upd .= "estimate_price=$pu_s_price,"; 
			$field_upd .= "amt=round($pu_qty * $pu_s_price,2),";
			$field_upd .= "require_date='$pu_require',"; 
			$field_upd .= "upto_date=getdate(),";
			$field_upd .= "reg_date=getdate(),";
			
			if($pu_sts=='1'){
				$field_upd .= "remainder_qty=$pu_qty,";	
			}

			$field_upd .= "ohsas='$pu_ohsas'";

			$upd2 = "update prf_details set $field_upd where prf_no='$pu_prf' and line_no=$pu_line_ada and item_no = $pu_item";
			$data_upd2 = sqlsrv_query($connect, strtoupprt($upd2));
			
			
			$pesan = sqlsrv_errors($data_upd2);
			$msg .= $pesan['message'];
			if($msg != ''){
				$msg .= " Update Item Process Error  : $upd2";
				break;
			}
		}
	}

	//UPDATE GR_HEADERS
	$upd = "update prf_header set 
		prf_date='$pu_date', customer_po_no = '$pu_cust_po_no', remark = $pu_rmark_fix, require_person_code = '$user'
		where prf_no='$pu_prf'";
	$data_upd = sqlsrv_query($connect, strtoupper($upd));
	$pesan = sqlsrv_errors($data_upd);
	$msg .= $pesan['message'];
	if($msg != ''){
		$msg .= " Update Header Process Error  : $upd";
	
	}

	$upd3 = "update ztb_prf_sts set status=$sts where prf_no='$pu_prf'";
	$data_upd3 = sqlsrv_query($connect, strtoupper($upd3));
	$pesan = sqlsrv_errors($upd3);
	$msg .= $pesan['message'];
	if($msg != ''){
		$msg .= " Update PRF Status Process Error  : $upd3";
		
	}
}else{
	$msg .= 'Session Expired';
}


if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode($upd2 );
}
?>
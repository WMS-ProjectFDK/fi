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
		$pu_prf = $query->pu_prf;
		$pu_line = $query->pu_line;
		$pu_date = $query->pu_date;
		$pu_cust_po_no = $query->pu_cust_po_no;
		$pu_ck_new = $query->pu_ck_new;
		$pu_rmark = $query->pu_rmark;
		$pu_item = $query->pu_item;
		$pu_unit = $query->pu_unit;
		$pu_s_price = $query->pu_s_price;
		$pu_require = $query->pu_require;
		$pu_qty = $query->pu_qty;
		$pu_amt = $query->pu_amt;
		$pu_ohsas = $query->pu_ohsas;
		$pu_sts = $query->pu_sts;

		if($pu_ck_new == 'true'){
			$sts = 1;
		}else{
			$sts = 0;
		}
		
		if($pu_sts=='MRP'){
			$pu_cust = $pu_sts;
		}else{
			$pu_cust = $pu_cust_po_no;
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

		//CEK GR_HEADERS
		$cek = "select count(*) as jum_prf from prf_header where prf_no='$pu_prf'";

		$data = sqlsrv_query($connect, $cek);
		
		$dt = sqlsrv_fetch_object($data);

		if(intval($dt->JUM_PRF) == 0){
			# INSERT PRF HEADER
			$field_prf .= "prf_no,"               ; $value_prf .= "'$pu_prf',"                          ;

			$field_prf .= "prf_date,"             ; $value_prf .= "'$pu_date',"   ;

			$field_prf .= "section_code,"         ; $value_prf .= "100,"                    			;
			$field_prf .= "customer_po_no,"       ; $value_prf .= "'$pu_cust',"                  		;
			$field_prf .= "remark,"               ; $value_prf .= "$pu_rmark_fix,"                      ;
			$field_prf .= "require_person_code,"  ; $value_prf .= "'$user',"                     		;

			$field_prf .= "upto_date,"            ; $value_prf .= "getdate(),"                            ;
			$field_prf .= "reg_date"              ; $value_prf .= "getdate()"                             ;
			chop($field_prf) ;              	  chop($value_prf) ;

			$ins1  = "insert into prf_header ($field_prf) values ($value_prf)";
			$data_ins1 = sqlsrv_query($connect, $ins1);
			$pesan = sqlsrv_errors($data_ins1);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " PRF-Header Process Error  : $ins1";
				break;
			}

			$ins3 = "insert into ztb_prf_sts (prf_no,status) VALUES ('$pu_prf',$sts)";
			//echo $ins3."<br/>";

			$data_ins3 = sqlsrv_query($connect, $ins3);
			
			$pesan = sqlsrv_errors($data_ins3);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " PRF-STATUS Process Error : $ins3";
				break;
			}

		}

		//INSERT PRF DETAILS
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

		$field_dtl .= "ohsas"               ; $value_dtl .= "'$pu_ohsas'"							;
		chop($field_dtl) ;                  chop($value_dtl) ;

		$ins2 = "insert into prf_details ($field_dtl) VALUES ($value_dtl)";
		//echo $ins2."<br/>";

		$data_ins2 = sqlsrv_query($connect, $ins2);
		
		$pesan = sqlsrv_errors($data_ins2);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " PRF-Details Process Error : $ins2";
			break;
		}

		if($pu_sts == 'MRP'){

			$sql = "{call ZSP_MRP_MATERIAL_ITEM(?)}";
			
			$params = array(
				array($pu_item, SQLSRV_PARAM_IN)
			);
			$stmt = sqlsrv_query($connect, $sqlx,$paramss);

			if($stmt === false){
				$msg .= " Procedure I - MRP Process Error : $sql";
				break;
			}	


		// 	$sqlx = "{call ZSP_MRP_PRF(?,?)}";
		// 	$paramsx = array(
		// 		array($pu_item, SQLSRV_PARAM_IN),
		// 		array($pu_prf, SQLSRV_PARAM_IN)
		// 	);
		// 	$stmt = sqlsrv_query($connect, $sqlx,$paramss);
			
			
		// 	$pesan = sqlsrv_errors($stmt);
		// 	$msg .= $pesan['message'];

		// 	if($msg != ''){
		// 		$msg .= " Procedure II - MRP Process Error : $sqlx";
		// 		break;
		// 	}
		}
	};

}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode("success");
}

/*
	$pu_sts = htmlspecialchars($_REQUEST['pu_sts']);
	$pu_prf = htmlspecialchars($_REQUEST['pu_prf']);
	$pu_line = htmlspecialchars($_REQUEST['pu_line']);
	$pu_date = htmlspecialchars($_REQUEST['pu_date']);
	$pu_cust_po_no = htmlspecialchars($_REQUEST['pu_cust_po_no']);
	$pu_ck_new = htmlspecialchars($_REQUEST['pu_ck_new']);
	$pu_rmark = htmlspecialchars($_REQUEST['pu_rmark']);
	$pu_item = htmlspecialchars($_REQUEST['pu_item']);
	$pu_unit = htmlspecialchars($_REQUEST['pu_unit']);
	$pu_s_price = htmlspecialchars($_REQUEST['pu_s_price']);
	$pu_require = htmlspecialchars($_REQUEST['pu_require']);
	$pu_qty = htmlspecialchars($_REQUEST['pu_qty']);
	$pu_amt = htmlspecialchars($_REQUEST['pu_amt']);
	$pu_ohsas = htmlspecialchars($_REQUEST['pu_ohsas']);

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$now2=date('Y-m-d');

	if($pu_ck_new == 'true'){
		$sts = 1;
	}else{
		$sts = 0;
	}

	if($pu_sts=='MRP'){
		$pu_cust = $pu_sts;
	}else{
		$pu_cust = $pu_cust_po_no;
	}

	//CEK GR_HEADERS
	$cek = "select count(*) as jum_prf from prf_header where prf_no='$pu_prf'";

	$data = sqlsrv_query($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_object($data);

	if(intval($dt->JUM_PRF) == 0){
		# INSERT PRF HEADER
		$field_prf .= "prf_no,"               ; $value_prf .= "'$pu_prf',"                          ;
		$field_prf .= "prf_date,"             ; $value_prf .= "to_date('$pu_date','yyyy-mm-dd'),"   ;
		$field_prf .= "section_code,"         ; $value_prf .= "100,"                    			;
		$field_prf .= "customer_po_no,"       ; $value_prf .= "'$pu_cust',"                  		;
		$field_prf .= "remark,"               ; $value_prf .= "'$pu_rmark',"                        ;
		$field_prf .= "require_person_code,"  ; $value_prf .= "'$user',"                     		;

		$field_prf .= "upto_date,"            ; $value_prf .= "getdate(),"                            ;
		$field_prf .= "reg_date"              ; $value_prf .= "getdate()"                             ;

		chop($field_prf) ;              	  chop($value_prf) ;

		$ins1  = "insert into prf_header ($field_prf) values ($value_prf)";
		//echo $ins1."<br/>";

		$data_ins1 = sqlsrv_query($connect, $ins1);
		oci_execute($data_ins1);

		$ins3 = "insert into ztb_prf_sts (prf_no,status) VALUES ('$pu_prf',$sts)";
		//echo $ins3."<br/>";

		$data_ins3 = sqlsrv_query($connect, $ins3);
		
	}

	//INSERT PRF DETAILS
	$field_dtl  = "prf_no,"             ; $value_dtl  = "'$pu_prf',"							;
	$field_dtl .= "line_no,"            ; $value_dtl .= "$pu_line,"								;
	$field_dtl .= "item_no,"            ; $value_dtl .= "$pu_item,"								;
	$field_dtl .= "qty,"                ; $value_dtl .= "$pu_qty,"								;
	$field_dtl .= "uom_q,"              ; $value_dtl .= "$pu_unit,"								;
	$field_dtl .= "estimate_price,"     ; $value_dtl .= "$pu_s_price,"							;
	$field_dtl .= "amt,"                ; $value_dtl .= "round($pu_qty * $pu_s_price,2),"		;
	$field_dtl .= "require_date,"       ; $value_dtl .= "to_date('$pu_require','yyyy-mm-dd'),"	;

	$field_dtl .= "upto_date,"          ; $value_dtl .= "getdate(),"								;
	$field_dtl .= "reg_date,"           ; $value_dtl .= "getdate(),"								;

	$field_dtl .= "ohsas"               ; $value_dtl .= "'$pu_ohsas'"							;
	chop($field_dtl) ;                  chop($value_dtl) ;

	$ins2 = "insert into prf_details ($field_dtl) VALUES ($value_dtl)";
	//echo $ins2."<br/>";
	$data_ins2 = sqlsrv_query($connect, $ins2);
	oci_execute($data_ins2);



	if($pu_cust_po_no == 'MRP'){

		$sql = "BEGIN ZSP_MRP_MATERIAL_ITEM(:V_ITEM_NO); END;";

		$stmt = sqlsrv_query($connect, $sql);
		oci_bind_by_name($stmt, ':V_ITEM_NO', $pu_item);
		$res = oci_execute($stmt);

		


		$sqlx = "BEGIN ZSP_MRP_PRF(:V_ITEM_NO,:V_PRF_NO); END;";

		$stmt = sqlsrv_query($connect, $sqlx);

		oci_bind_by_name($stmt, ':V_ITEM_NO', $pu_item);
		oci_bind_by_name($stmt, ':V_PRF_NO', $pu_prf);

		$res = oci_execute($stmt);
	}*/
?>
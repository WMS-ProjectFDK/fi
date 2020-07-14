<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	// $pu_sts = htmlspecialchars($_REQUEST['pu_sts']);
	// $pu_prf = htmlspecialchars($_REQUEST['pu_prf']);
	// $pu_line = htmlspecialchars($_REQUEST['pu_line']);
	// $pu_date = htmlspecialchars($_REQUEST['pu_date']);
	// $pu_cust_po_no = htmlspecialchars($_REQUEST['pu_cust_po_no']);
	// $pu_ck_new = htmlspecialchars($_REQUEST['pu_ck_new']);
	// $pu_rmark = htmlspecialchars($_REQUEST['pu_rmark']);
	// $pu_item = htmlspecialchars($_REQUEST['pu_item']);
	// $pu_unit = htmlspecialchars($_REQUEST['pu_unit']);
	// $pu_s_price = htmlspecialchars($_REQUEST['pu_s_price']);
	// $pu_require = htmlspecialchars($_REQUEST['pu_require']);
	// $pu_qty = htmlspecialchars($_REQUEST['pu_qty']);
	// $pu_amt = htmlspecialchars($_REQUEST['pu_amt']);
	// $pu_ohsas = htmlspecialchars($_REQUEST['pu_ohsas']);

	// $now=date('Y-m-d H:i:s');
	// $user = $_SESSION['id_wms'];
	// $now2=date('Y-m-d');

	// if($pu_ck_new == 'true'){
	// 	$sts = 1;
	// }else{
	// 	$sts = 0;
	// }

	// if($pu_sts=='MRP'){
	// 	$pu_cust = $pu_sts;
	// }else{
	// 	$pu_cust = $pu_cust_po_no;
	// }

	// //CEK GR_HEADERS
	// $cek = "select count(*) as jum_prf from prf_header where prf_no='$pu_prf'";
	// $data = oci_parse($connect, $cek);
	// oci_execute($data);
	// $dt = oci_fetch_object($data);

	// if(intval($dt->JUM_PRF) == 0){
	// 	# INSERT PRF HEADER
	// 	$field_prf .= "prf_no,"               ; $value_prf .= "'$pu_prf',"                          ;
	// 	$field_prf .= "prf_date,"             ; $value_prf .= "to_date('$pu_date','yyyy-mm-dd'),"   ;
	// 	$field_prf .= "section_code,"         ; $value_prf .= "100,"                    			;
	// 	$field_prf .= "customer_po_no,"       ; $value_prf .= "'$pu_cust',"                  		;
	// 	$field_prf .= "remark,"               ; $value_prf .= "'$pu_rmark',"                        ;
	// 	$field_prf .= "require_person_code,"  ; $value_prf .= "'$user',"                     		;
	// 	$field_prf .= "upto_date,"            ; $value_prf .= "sysdate,"                            ;
	// 	$field_prf .= "reg_date"              ; $value_prf .= "sysdate"                             ;
	// 	chop($field_prf) ;              	  chop($value_prf) ;

	// 	$ins1  = "insert into prf_header ($field_prf) values ($value_prf)";
	// 	//echo $ins1."<br/>";
	// 	$data_ins1 = oci_parse($connect, $ins1);
	// 	oci_execute($data_ins1);

	// 	$ins3 = "insert into ztb_prf_sts (prf_no,status) VALUES ('$pu_prf',$sts)";
	// 	//echo $ins3."<br/>";
	// 	$data_ins3 = oci_parse($connect, $ins3);
	// 	oci_execute($data_ins3);
	// }

	// //INSERT PRF DETAILS
	// $field_dtl  = "prf_no,"             ; $value_dtl  = "'$pu_prf',"							;
	// $field_dtl .= "line_no,"            ; $value_dtl .= "$pu_line,"								;
	// $field_dtl .= "item_no,"            ; $value_dtl .= "$pu_item,"								;
	// $field_dtl .= "qty,"                ; $value_dtl .= "$pu_qty,"								;
	// $field_dtl .= "uom_q,"              ; $value_dtl .= "$pu_unit,"								;
	// $field_dtl .= "estimate_price,"     ; $value_dtl .= "$pu_s_price,"							;
	// $field_dtl .= "amt,"                ; $value_dtl .= "round($pu_qty * $pu_s_price,2),"		;
	// $field_dtl .= "require_date,"       ; $value_dtl .= "to_date('$pu_require','yyyy-mm-dd'),"	;
	// $field_dtl .= "upto_date,"          ; $value_dtl .= "sysdate,"								;
	// $field_dtl .= "reg_date,"           ; $value_dtl .= "sysdate,"								;
	// $field_dtl .= "ohsas"               ; $value_dtl .= "'$pu_ohsas'"							;
	// chop($field_dtl) ;                  chop($value_dtl) ;

	// $ins2 = "insert into prf_details ($field_dtl) VALUES ($value_dtl)";
	// //echo $ins2."<br/>";
	// $data_ins2 = oci_parse($connect, $ins2);
	// oci_execute($data_ins2);

$sql = "	BEGIN	 ZSP_MRP_PRF('1170120','O.PRF-18-01582');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170120','O.PRF-18-01583');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170120','O.PRF-18-01584');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170120','O.PRF-18-01585');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170146','O.PRF-18-01590');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170146','O.PRF-18-01591');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170146','O.PRF-18-01592');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1120028','O.PRF-18-01596');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1120028','O.PRF-18-01597');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1130031','O.PRF-18-01599');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1130031','O.PRF-18-01600');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170037','O.PRF-18-01602');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170037','O.PRF-18-01603');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170037','O.PRF-18-01604');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170037','O.PRF-18-01605');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1170037','O.PRF-18-01606');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01607');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01608');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01609');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01610');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01611');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01612');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01613');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01614');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01615');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01616');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);
$sql = "	BEGIN	 ZSP_MRP_PRF('1110053','O.PRF-18-01617');	END;";	$stmt = oci_parse($connect, $sql);		$res = oci_execute($stmt);








	// if ($data_ins2){
	// 	echo json_encode(array('successMsg'=>'success'));
	// }else{
	// 	echo json_encode(array('errorMsg'=>'Error'));
	// }
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
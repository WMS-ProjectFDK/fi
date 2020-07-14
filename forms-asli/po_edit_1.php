<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$v_from = htmlspecialchars($_REQUEST['v_from']);
	$v_to = htmlspecialchars($_REQUEST['v_to']);
	$po_sts = htmlspecialchars($_REQUEST['po_sts']);
	$po_supp = htmlspecialchars($_REQUEST['po_supp']);
	$po_no = htmlspecialchars($_REQUEST['po_no']);
	$po_pterm = htmlspecialchars($_REQUEST['po_pterm']);
	$po_line = htmlspecialchars($_REQUEST['po_line']);
	$po_date = htmlspecialchars($_REQUEST['po_date']);
	$po_rate = htmlspecialchars($_REQUEST['po_rate']);
	$po_tterm = htmlspecialchars($_REQUEST['po_tterm']);
	$po_attn= htmlspecialchars($_REQUEST['po_attn']);
	$po_di_type = htmlspecialchars($_REQUEST['po_di_type']);
	$po_trans = htmlspecialchars($_REQUEST['po_trans']);
	$po_remark = htmlspecialchars($_REQUEST['po_remark']);
	$po_ship_mark = htmlspecialchars($_REQUEST['po_ship_mark']);
	$po_item = htmlspecialchars($_REQUEST['po_item']);
	$po_unit = htmlspecialchars($_REQUEST['po_unit']);
	$po_orign = htmlspecialchars($_REQUEST['po_orign']);
	$po_price = htmlspecialchars($_REQUEST['po_price']);
	$po_curr = htmlspecialchars($_REQUEST['po_curr']);
	$po_curr_item = htmlspecialchars($_REQUEST['po_curr_item']);
	$po_qty = htmlspecialchars($_REQUEST['po_qty']);
	$po_eta = htmlspecialchars($_REQUEST['po_eta']);
	$po_prf = htmlspecialchars($_REQUEST['po_prf']);
	$po_prf_line = htmlspecialchars($_REQUEST['po_prf_line']);
	$po_dt_code = htmlspecialchars($_REQUEST['po_dt_code']);
	$amt_o = htmlspecialchars($_REQUEST['amt_o']);
	$amt_l = htmlspecialchars($_REQUEST['amt_l']);
	$po_rev = htmlspecialchars($_REQUEST['po_rev']);
	$po_rev_res = htmlspecialchars($_REQUEST['po_rev_res']);
	$po_line_new = htmlspecialchars($_REQUEST['po_line_new']);
	

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$now2=date('Y-m-d');

	/*if($po_rev=='Y'){
		$po_revisi = $po_rev;
	}else{
		$po_revisi = '';
	}*/

	if($po_prf == '' and $po_prf_line == ''){
		$prf='-';
		$prf_line='0';
	}else{
		$prf=$po_prf;
		$prf_line=$po_prf_line;
	}

	$bal = intval($po_qty - $po_gr_qty);

	if($po_line != "NEW"){

		$sql = "BEGIN ZSP_UPDATE_PO(:V_PO_NO,:V_SUPPLIER_CODE,:V_PO_DATE,:V_CURR_CODE,:V_EX_RATE,:V_TTERM,:V_PDAYS,:V_PDESC,:V_REQ,:V_REMARK1,:V_MARKS1,:V_ATTN,:V_PERSON_CODE,:V_ITEM_NO,:V_PBY,:V_SHIPTO_CODE,:V_TRANSPORT,:V_DI_OUTPUT_TYPE,:V_PRF_NO,:V_PRF_LINE_NO,:V_ORIGIN_CODE,:V_QTY,:V_UOM_Q,:V_U_PRICE,:V_D_AMT_O,:V_D_AMT_L,:V_ETA,:V_SCHEDULE,:V_BAL_QTY,:V_CARVED_STAMP,:V_PO_REV,:V_PO_REV_RES,:V_PO_LINE_NEW,:V_PO_LINE); end;";

		$stmt = oci_parse($connect, $sql);

		 /*Binding Parameters */
		oci_bind_by_name($stmt, ':V_PO_NO' , $po_no);
		oci_bind_by_name($stmt, ':V_SUPPLIER_CODE', $po_supp);
		$newDate = date("d-M-Y", strtotime($po_date));
		oci_bind_by_name($stmt, ':V_PO_DATE', $newDate);
		oci_bind_by_name($stmt, ':V_CURR_CODE', $po_curr);
		oci_bind_by_name($stmt, ':V_EX_RATE', $po_rate);
		oci_bind_by_name($stmt, ':V_TTERM', $po_tterm);
		oci_bind_by_name($stmt, ':V_PDAYS', $pday);
		oci_bind_by_name($stmt, ':V_PDESC', $pdes);
		oci_bind_by_name($stmt, ':V_REQ', $user);
		oci_bind_by_name($stmt, ':V_ATTN', $po_attn);
		oci_bind_by_name($stmt, ':V_PERSON_CODE', $user);
		oci_bind_by_name($stmt, ':V_PBY', $po_tterm);
		oci_bind_by_name($stmt, ':V_ITEM_NO', $po_item);
		oci_bind_by_name($stmt, ':V_SHIPTO_CODE', $po_shipto);
		oci_bind_by_name($stmt, ':V_TRANSPORT', $po_trans);
		oci_bind_by_name($stmt, ':V_DI_OUTPUT_TYPE', $po_di_type);
		oci_bind_by_name($stmt, ':V_PRF_NO', $po_prf);
		oci_bind_by_name($stmt, ':V_PRF_LINE_NO', $po_prf_line);
		oci_bind_by_name($stmt, ':V_MARKS1', $po_remark);
		oci_bind_by_name($stmt, ':V_REMARK1', $po_ship_mark);
		oci_bind_by_name($stmt, ':V_ORIGIN_CODE', $po_orign);
		oci_bind_by_name($stmt, ':V_QTY', $po_qty);
		oci_bind_by_name($stmt, ':V_UOM_Q', $po_unit);
		oci_bind_by_name($stmt, ':V_U_PRICE', $po_price);
		oci_bind_by_name($stmt, ':V_D_AMT_O', $amt_o);
		oci_bind_by_name($stmt, ':V_D_AMT_L', $amt_l);
		$newDate = date("d-M-Y", strtotime($po_eta));
		oci_bind_by_name($stmt, ':V_ETA', $newDate);
		oci_bind_by_name($stmt, ':V_SCHEDULE', $newDate);
		oci_bind_by_name($stmt, ':V_BAL_QTY', $bal);
		oci_bind_by_name($stmt, ':V_CARVED_STAMP', $po_dt_code);
		oci_bind_by_name($stmt, ':V_PO_REV', $po_rev);
		oci_bind_by_name($stmt, ':V_PO_REV_RES', $po_rev_res);
		oci_bind_by_name($stmt, ':V_PO_LINE_NEW', $po_line_new);
		oci_bind_by_name($stmt, ':V_PO_LINE', $po_line);



		/* Execute */
		$res = oci_execute($stmt);

		/* Get the output on the screen */
		print_r($res, true);

		echo json_encode(array('successMsg'=>$po_line));

	;

	// if($po_sts == 'HEADER'){
	// 	$upd  = "update po_header set " 						  ;
	//     $upd .= " po_date        = to_date('$po_date','yyyy-mm-dd')," ;
	//     $upd .= " amt_o 		 = $amt_o," 					  	  ;
	//     $upd .= " amt_l 		 = $amt_l,"						      ;
	//     $upd .= " di_output_type = '$po_di_type',"          		  ;
	//     $upd .= " transport      = '$po_trans',"            		  ;
	//     $upd .= " remark1        = '$po_remark',"           		  ;
	//     $upd .= " marks1         = '$po_ship_mark',"        		  ;
	//     $upd .= " revise         = '$po_rev',"              		  ;
	//     $upd .= " reason1        = '$po_rev_res',"          		  ;
	//     $upd .= " req            = '$user',"                		  ;
	//     $upd .= " fdk_person_code = '$user',"                		  ;
	//     $upd .= " upto_date       = sysdate"                		  ;
	//     chop($upd) ;
	//     $upd .= " where po_no = '$po_no'"							  ;

	//     $data_upd = oci_parse($connect, $upd);
	// 	oci_execute($data_upd);
	// 	echo $upd;
	// }else{
	// 	if($po_line != "NEW"){
	// 		//update po_details
	// 		$upd_dtl = "pdate po_details set "								;
	// 		$upd_dtl .= "qty 			=$po_qty,"							;
	// 		$upd_dtl .= "bal_qty 		=$bal,"								;
	// 		$upd_dtl .= "u_price		=$po_price,"						;
	// 		$upd_dtl .= "amt_o 			=$amt_o,"							;
	// 		$upd_dtl .= "amt_l 			=$amt_l,"							;
	// 		$upd_dtl .= "eta 			=to_date('$po_eta','yyyy-mm-dd'),"	;
	// 		$upd_dtl .= "schedule 		=to_date('$po_eta','yyyy-mm-dd'),"	;
	// 		$upd_dtl .= "upto_date 		=to_date('$now2','yyyy-mm-dd'),"	;
	// 		$upd_dtl .= "reg_date 		=to_date('$now2','yyyy-mm-dd'),"	;
	// 		$upd_dtl .= "carved_stamp 	='$po_dt_code' "					;
	// 		chop($upd_dtl);
	// 		$upd_dtl .= " where po_no='$po_no' and line_no=$po_line "	;
			
	// 		$data_upd_dtl = oci_parse($connect, $upd_dtl);
	// 		oci_execute($data_upd_dtl);
	// 		echo $upd_dtl;
	// 	}else{
	// 		//INSERT DETAILS 
	// 		$field_dtl  = "po_no,"              ; $value_dtl  = "'$po_no',"								;
	// 		$field_dtl .= "line_no,"            ; $value_dtl .= "$po_line_new,"							;
	// 		$field_dtl .= "prf_no,"             ; $value_dtl .= "'$prf',"								;
	// 		$field_dtl .= "prf_line_no,"        ; $value_dtl .= "$prf_line,"							;
	// 		$field_dtl .= "item_no,"            ; $value_dtl .= "$po_item,"								;
	// 		$field_dtl .= "origin_code,"        ; $value_dtl .= "'$po_orign',"							;
	// 		$field_dtl .= "qty,"                ; $value_dtl .= "$po_qty,"								;
	// 		$field_dtl .= "uom_q,"              ; $value_dtl .= "'$po_unit',"							;
	// 		$field_dtl .= "u_price,"            ; $value_dtl .= "$po_price,"							;
	// 		$field_dtl .= "amt_o,"            	; $value_dtl .= "$amt_o,"								;
	// 		$field_dtl .= "amt_l,"            	; $value_dtl .= "$amt_l,"								;
	// 		$field_dtl .= "eta,"            	; $value_dtl .= "to_date('$po_eta','yyyy-mm-dd'),"		;
	// 		$field_dtl .= "schedule,"           ; $value_dtl .= "to_date('$po_eta','yyyy-mm-dd'),"		;
	// 		$field_dtl .= "gr_qty,"             ; $value_dtl .= "0,"									;
	// 		$field_dtl .= "sh_qty,"             ; $value_dtl .= "0,"									;
	// 		$field_dtl .= "pret_qty,"           ; $value_dtl .= "0,"									;
	// 		$field_dtl .= "bal_qty,"            ; $value_dtl .= "$po_qty,"								;
	// 		$field_dtl .= "upto_date,"          ; $value_dtl .= "TO_DATE('$now2','yyyy-mm-dd'),"		;
	// 		$field_dtl .= "reg_date"            ; $value_dtl .= "TO_DATE('$now2','yyyy-mm-dd')"			;
	// 		chop($field_dtl) ;                 	chop($value_dtl) ;

	// 		$ins = "insert into po_details ($field_dtl) VALUES ($value_dtl)" ;

	// 		$data_ins = oci_parse($connect, $ins);
	// 		oci_execute($data_ins);
	// 		echo $ins2;
	// 	}

	// 	$upd_prf = "update PRF_DETAILS set REMAINDER_QTY = QTY - $po_qty, UPTO_DATE = sysdate
	// 		 	where PRF_NO = '$prf' and LINE_NO = $prf_line";
		
	// 	$data_upd_prf = oci_parse($connect, $upd_prf);
	// 	oci_execute($data_upd_prf);
	// 	echo $upd_prf;
	//}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
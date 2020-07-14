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
		$gr_sts = $query->gr_sts;
		$gr_no = $query->gr_no;
		$gr_line = $query->gr_line;
		$gr_date = $query->gr_date;
		$gr_supp = $query->gr_supp;
		$gr_supp_name = $query->gr_supp_name;
		$gr_remark = $query->gr_remark;
		$gr_sts_wh = $query->gr_sts_wh;
		$gr_curr = $query->gr_curr;
		$gr_rate = $query->gr_rate;
		$gr_pday = $query->gr_pday;
		$gr_pdes = $query->gr_pdes;
		$amt_o = $query->gr_amto;
		$amt_l = $query->gr_amtl;
		$gr_slip = $query->gr_slip;
		$gr_item = $query->gr_item;
		$gr_item_name = $query->gr_item_name;

		$gr_stock_subject_code = $query->gr_stock_subject_code;
		$gr_class_code = $query->gr_class_code;
		$gr_country_code = $query->gr_country_code;
		$gr_cost_process_code = $query->gr_cost_process_code;
		$gr_cost_subject_code = $query->gr_cost_subject_code;
		$gr_standard_price = $query->gr_standard_price;
		$gr_suppliers_price = $query->gr_suppliers_price;

		$gr_desc = $query->gr_desc;
		$gr_orig = $query->gr_orig;
		$gr_pono = $query->gr_pono;
		$gr_po_date = $query->gr_po_date;
		$gr_po_line = $query->gr_po_line;
		$gr_qty = $query->gr_qty;
		$gr_uomq = $query->gr_uomq;
		$gr_price  = $query->gr_price;
		$gr_qty_act = $query->gr_qty_act;
		
		$now = date('Y-m-d H:i:s');			$user = $_SESSION['id_wms'];			$now2=date('Y-m-d');

		// HITUNG DUEDATE
		$plusDay = "+".intval($gr_pday)." day";
		$tambah_date = strtotime($plusDay,strtotime($gr_date));
		$due_date = date('Y-m-d',$tambah_date);	
	
		$cc_field  = "GR_STS,"				;	$cc_value  =  "'$gr_sts',"									;
		$cc_field .= "GR_NO,"				;	$cc_value .=  "'$gr_no',"									;
		$cc_field .= "LINE_NO,"				;	$cc_value .=  "$gr_line,"									;
		$cc_field .= "GR_DATE,"				;	$cc_value .=  "TO_DATE('$gr_date','yyyy-mm-dd'),"			;
		$cc_field .= "GR_QTY,"				;	$cc_value .=  "$gr_qty_act,"								;
		$cc_field .= "GR_UOM_Q,"			;	$cc_value .=  "$gr_uomq,"									;
		$cc_field .= "GR_U_PRICE,"			;	$cc_value .=  "$gr_price,"									;
		$cc_field .= "SUPPLIER_CODE,"		;	$cc_value .=  "$gr_supp,"									;
		$cc_field .= "SUPPLIER_NAME,"		;	$cc_value .=  "'".str_replace("'", "", $gr_supp_name)."',"	;
		$cc_field .= "REMARK,"				;	$cc_value .=  "'$gr_remark',"								;
		$cc_field .= "WH_STS,"				;	$cc_value .=  "$gr_sts_wh,"									;
		$cc_field .= "CURR_CODE,"			;	$cc_value .=  "$gr_curr,"									;
		$cc_field .= "EX_RATE,"				;	$cc_value .=  "$gr_rate,"									;
		$cc_field .= "PDAYS,"				;	$cc_value .=  "$gr_pday,"									;
		$cc_field .= "PDESC,"				;	$cc_value .=  "'$gr_pdes',"									;
		$cc_field .= "AMT_O,"				;	$cc_value .=  "$amt_o,"										;
		$cc_field .= "AMT_L,"				;	$cc_value .=  "$amt_l,"										;
		$cc_field .= "SLIP_TYPE,"			;	$cc_value .=  "'$gr_slip',"									;
		$cc_field .= "ITEM_NO,"				;	$cc_value .=  "$gr_item,"									;
		$cc_field .= "ITEM_NAME,"			;	$cc_value .=  "'$gr_item_name',"							;
		$cc_field .= "DESCRIPTION,"			;	$cc_value .=  "'".substr($gr_desc,0,29)."',"				;
		$cc_field .= "ITEM_TYPE,"			;	$cc_value .=  "$gr_stock_subject_code,"						;
		$cc_field .= "SRC_CLASS_CODE,"		;	$cc_value .=  "'$gr_class_code',"							;
		$cc_field .= "BUY_COUNTRY_CODE,"	;	$cc_value .=  "'$gr_country_code',"							;
		$cc_field .= "COST_PROCESS_CODE,"	;	$cc_value .=  "'$gr_cost_process_code',"					;
		$cc_field .= "COST_SUBJECT_CODE,"	;	$cc_value .=  "'$gr_cost_subject_code',"					;
		$cc_field .= "STANDARD_PRICE,"		;	$cc_value .=  "$gr_standard_price,"							;
		$cc_field .= "SUPPLIERS_PRICE,"		;	$cc_value .=  "$gr_suppliers_price,"						;
		$cc_field .= "ORIGIN_CODE,"			;	$cc_value .=  "'$gr_orig',"									;
		$cc_field .= "PO_NO,"				;	$cc_value .=  "'$gr_pono',"									;
		$cc_field .= "PO_DATE,"				;	$cc_value .=  "'$gr_po_date',"								;
		$cc_field .= "PO_LINE_NO,"			;	$cc_value .=  "$gr_po_line,"								;
		$cc_field .= "PERSON_CODE,"			;	$cc_value .=  "'$user',"									;
		$cc_field .= "DUE_DATE"				;	$cc_value .=  "to_date('$due_date','yyyy-mm-dd')"			;
		chop($cc_field);              			chop($cc_value);

		$ins_cc = "insert into ztb_gr_temp ($cc_field) select $cc_value from dual";
		$data_cc = oci_parse($connect, $ins_cc);
		oci_execute($data_cc);
		$pesan = oci_error($stmt);
		$msg .= $pesan['message'];

		if($msg != ''){
			$msg .= " GR-TEMP Process Error : $ins_cc";
			break;
		}
	}

	$sql = "BEGIN ZSP_INSERT_GR(:V_USER); END;";
	$stmt = oci_parse($connect, $sql);
	oci_bind_by_name($stmt, ':V_USER' , trim($user));
	$res = oci_execute($stmt);
	$pesan = oci_error($stmt);
	$msg .= $pesan['message'];

	if($msg != ''){
		$msg .= " Procedure Insert - GR Process Error : $sql";
		break;
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}

	//if($gr_sts=='HEADER'){
	//	// INSERT KE GR_HEADER
	//	$field_grh   = "gr_no,"  		; $value_grh   = "'$gr_no',"							;
	//	$field_grh  .= "gr_date,"  		; $value_grh  .= "TO_DATE('$gr_date','yyyy-mm-dd'),"	;
	//	$field_grh  .= "inv_date,"  	; $value_grh  .= "TO_DATE('$gr_date','yyyy-mm-dd'),"	;
	//	$field_grh  .= "supplier_code," ; $value_grh  .= "$gr_supp,"							;
	//	$field_grh  .= "inv_no,"  		; $value_grh  .= "'$gr_no',"							;
	//	$field_grh  .= "curr_code,"  	; $value_grh  .= "'$gr_curr',"							;
	//	$field_grh  .= "ex_rate,"  		; $value_grh  .= "'$gr_rate',"							;
	//	$field_grh  .= "pdays,"  		; $value_grh  .= "'$gr_pday',"							;
	//	$field_grh  .= "pdesc,"  		; $value_grh  .= "'$gr_pdes',"							;
	//	$field_grh  .= "due_date,"  	; $value_grh  .= "TO_DATE('$due_date','yyyy-mm-dd'),"	;
	//	$field_grh  .= "remark,"  		; $value_grh  .= "'$gr_remark',"						;
	//	$field_grh  .= "amt_o,"  		; $value_grh  .= "$amt_o,"									;
	//	$field_grh  .= "amt_l,"  		; $value_grh  .= "$amt_l,"									;
	//	$field_grh  .= "person_code,"  	; $value_grh  .= "'$user',"								;
	//	$field_grh  .= "slip_type"  	; $value_grh  .= "'$gr_slip'"							;
	//	chop($field_grh);              chop($value_grh);
	//	$ins1 = "insert into gr_header($field_grh) 
	//		select $value_grh from dual where not exists (select * from gr_header where gr_no='$gr_no')";
	//	echo $ins1."<br/>";
	//	$data_ins1 = oci_parse($connect, $ins1);
	//	oci_execute($data_ins1);
//
	//	// INSERT KE ACCOUNT PAYABLE
	//	$field_ap  = "customer_code,"  ; $value_ap  = "'$gr_supp',"                  	   ;
	//	$field_ap .= "bl_no,"          ; $value_ap .= "'$gr_no',"                          ;
	//	$field_ap .= "type,"           ; $value_ap .= "'1',"                               ;
	//	$field_ap .= "pr_no,"          ; $value_ap .= "null,"                              ;
	//	$field_ap .= "payment_date,"   ; $value_ap .= "to_date('$gr_date','yyyy-mm-dd'),"  ;
	//	$field_ap .= "amt,"            ; $value_ap .= "$amt_l,"							   ;
	//	$field_ap .= "bank,"           ; $value_ap .= "null,"                              ;
	//	$field_ap .= "bl_date,"        ; $value_ap .= "to_date('$due_date','yyyy-mm-dd')," ;
	//	$field_ap .= "curr_code,"      ; $value_ap .= "'$gr_curr',"                        ;
	//	$field_ap .= "rate,"           ; $value_ap .= "'$gr_rate',"                        ;
	//	$field_ap .= "amt_f,"          ; $value_ap .= "$amt_o,"  						   ;
	//	$field_ap .= "reg_date,"       ; $value_ap .= "TO_DATE('$now2','yyyy-mm-dd'),"     ;
	//	$field_ap .= "upto_date,"      ; $value_ap .= "TO_DATE('$now2','yyyy-mm-dd'),"	   ;
	//	$field_ap .= "pdays,"          ; $value_ap .= "'$gr_pday',"                        ;
	//	$field_ap .= "pdesc,"          ; $value_ap .= "'$gr_pdes',"                        ;
	//	$field_ap .= "gr_no"           ; $value_ap .= "'$gr_no'"                           ;
	//	chop($field_ap) ;              chop($value_ap) ;
	//	$ins4  = "insert into account_payable ($field_ap) select $value_ap from dual 
	//		where not exists (select * from account_payable where gr_no='$gr_no')" ;
	//	echo $ins4."<br/>";
	//	$data_ins4 = oci_parse($connect, $ins4);
	//	oci_execute($data_ins4);
	//	echo json_encode(array('successMsg'=>'success'));
	//}else{
	//	//INSERT KE GR_DETAILS 
	//	$field_grd  = "gr_no,"			;	$value_grd  = "'$gr_no',"						;
	//	$field_grd .= "line_no,"		;	$value_grd .= "$gr_line,"						;
	//	$field_grd .= "item_no,"		;	$value_grd .= "$gr_item,"						;
	//	$field_grd .= "origin_code,"	;	$value_grd .= "'$gr_orig',"						;
	//	$field_grd .= "po_no,"			;	$value_grd .= "'$gr_pono',"						;
	//	$field_grd .= "po_line_no,"		;	$value_grd .= "$gr_po_line,"					;
	//	$field_grd .= "qty,"			;	$value_grd .= "$gr_qty_act,"					;
	//	$field_grd .= "uom_q,"			;	$value_grd .= "$gr_uomq,"						;
	//	$field_grd .= "u_price,"		;	$value_grd .= "$gr_price,"						;
	//	$field_grd .= "amt_o,"			;	$value_grd .= "$amt_o,"							;
	//	$field_grd .= "amt_l,"			;	$value_grd .= "$amt_l,"							;
	//	$field_grd .= "loc1,"			;	$value_grd .= "88475,"							;
	//	$field_grd .= "loc_qty1,"		;	$value_grd .= "$gr_qty_act,"					;
	//	$field_grd .= "upto_date,"		;	$value_grd .= "TO_DATE('$now2','yyyy-mm-dd'),"	;
	//	$field_grd .= "reg_date"		;	$value_grd .= "TO_DATE('$now2','yyyy-mm-dd')"	;
	//	chop($field_grd) ;              chop($value_grd);
	//	$ins2 = "insert into gr_details($field_grd) select $value_grd from dual 
	//		where not exists(select * from gr_details where gr_no='$gr_no' and line_no=$gr_line)";
	//	echo $ins2."<br/>";
	//	$data_ins2 = oci_parse($connect, $ins2);
	//	oci_execute($data_ins2);
//
	//	// INSERT KE FDAC_PURCHASE_TRN
	//	$field_fdac  = "DATA_TYPE,"				; $value_fdac  = "130," 								;
	//	$field_fdac .= "COMPANY_CODE,"			; $value_fdac .= "88475," 								;
	//	$field_fdac .= "VENDOR_CODE,"			; $value_fdac .= "'$gr_supp'," 							;
	//	$field_fdac .= "ITEM_NO,"				; $value_fdac .= "'$gr_item'," 							;
	//	$field_fdac .= "DATA_DATE,"				; $value_fdac .= "to_date('$gr_date','yyyy-mm-dd')," 	;
	//	$field_fdac .= "QUANTITY,"				; $value_fdac .= "'$gr_qty_act'," 						;
	//	$field_fdac .= "PP,"					; $value_fdac .= "'$gr_price'," 						;
	//	$field_fdac .= "PP_CURR_CODE,"			; $value_fdac .= "'$gr_curr'," 							;
	//	$field_fdac .= "PURCHASE_AMOUNT,"		; $value_fdac .= "'$amt_o'," 							;
	//	$field_fdac .= "ITEM_TYPE,"				; $value_fdac .= "$gr_stock_subject_code," 				;
	//	$field_fdac .= "DATA_SOURCE_TYPE,"		; $value_fdac .= "'PGL-FI'," 							;
	//	$field_fdac .= "CONSUMPTION_TAX,"		; $value_fdac .= "0," 									;
	//	$field_fdac .= "NEW_PP,"				; $value_fdac .= "0,"									;
	//	$field_fdac .= "LAST_PP,"				; $value_fdac .= "0,"									;
	//	$field_fdac .= "CHECK_NO,"				; $value_fdac .= "'$gr_no',"							;
	//	$field_fdac .= "INVOICE_NO,"			; $value_fdac .= "'$gr_no',"							;
	//	$field_fdac .= "OPERATION_DATE,"		; $value_fdac .= "TO_DATE('$now2','yyyy-mm-dd'),"		;
	//	$field_fdac .= "INFO_TYPE,"				; $value_fdac .= "0,"									;
	//	$field_fdac .= "PURCHASE_DATE,"			; $value_fdac .= "to_date('$gr_po_date','yyyy-mm-dd'),"	;
	//	$field_fdac .= "PO_DATE,"				; $value_fdac .= "to_date('$gr_po_date','yyyy-mm-dd'),"	;
	//	$field_fdac .= "PO_NO,"					; $value_fdac .= "'$gr_pono',"							;
	//	$field_fdac .= "LINE_NO,"				; $value_fdac .= "'$gr_po_line',"						;
	//	$field_fdac .= "OPERATION_TYPE,"		; $value_fdac .= "0,"									;
	//	$field_fdac .= "ITEM,"					; $value_fdac .= "'$gr_desc',"							;
	//	$field_fdac .= "SRC_CLASS_CODE,"		; $value_fdac .= "'$gr_class_code',"					;
	//	$field_fdac .= "SECTION_CODE,"			; $value_fdac .= "88475,"								;
	//	$field_fdac .= "PERSON_CODE,"			; $value_fdac .= "'FI99999',"							;
	//	$field_fdac .= "VENDOR,"				; $value_fdac .= "'$gr_supp_name',"						;
	//	$field_fdac .= "BUY_COUNTRY_CODE"		; $value_fdac .= "'$gr_country_code'"					;
	//	chop($field_fdac);						chop($value_fdac);
	//	$ins5  = "insert into fdac_purchase_trn ($field_fdac) select $value_fdac from dual
	//		where not exists (select * from fdac_purchase_trn where invoice_no='$gr_no' and item_no='$gr_item')";
	//	echo $ins5."<br/>";
	//	$data_ins5 = oci_parse($connect, $ins5);
	//	oci_execute($data_ins5);
	//	
	//	//UPDATE WHINVENTORY
	//	$split = split('-', $gr_date);
	//	$now_month = $split[0].$split[1];
	//	$last_month = intval($now_month)-1;
//
	//	if($gr_sts_wh == 0){
	//		//insert whinvwntory
	//		$field_whi  = "operation_date,"		;	$value_whi  = "TO_DATE('$now2','yyyy-mm-dd'),"	;
	//		$field_whi .= "section_code,"		;	$value_whi .= "100,"							;
	//		$field_whi .= "item_no,"			;	$value_whi .= "$gr_item,"						;
	//		$field_whi .= "this_month,"			;	$value_whi .= "$now_month,"						;
	//		$field_whi .= "receive1,"			;	$value_whi .= "$gr_qty_act,"					;
	//		$field_whi .= "other_receive1,"		;	$value_whi .= "0,"								;
	//		$field_whi .= "issue1,"				;	$value_whi .= "0,"								;
	//		$field_whi .= "other_issue1,"		;	$value_whi .= "0,"								;
	//		$field_whi .= "stocktaking_adjust1,";	$value_whi .= "0,"								;
	//		$field_whi .= "this_inventory,"		;	$value_whi .= "$gr_qty_act,"					;
	//		$field_whi .= "last_month,"			;	$value_whi .= "$last_month,"					;
	//		$field_whi .= "receive2,"			;	$value_whi .= "0,"								;
	//		$field_whi .= "other_receive2,"		;	$value_whi .= "0,"								;
	//		$field_whi .= "issue2,"				;	$value_whi .= "0,"								;
	//		$field_whi .= "other_issue2,"		;	$value_whi .= "0,"								;
	//		$field_whi .= "stocktaking_adjust2,";	$value_whi .= "0,"								;
	//		$field_whi .= "last_inventory,"		;	$value_whi .= "0,"								;
	//		$field_whi .= "last2_inventory"		;	$value_whi .= "0"								;
	//		chop($field_whi);              			chop($value_whi);
	//		$ins_item = "insert into whinventory ($field_whi) select $value_whi from dual
	//				where not exists (select * from whinventory where item_no='$gr_item') ";
	//		echo $ins_item."<br/>";
	//		$data_ins_item = oci_parse($connect, $ins_item);
	//		oci_execute($data_ins_item);
	//	}else{
	//		$cek_inv = "select coalesce(this_month,0) as month, receive1, this_inventory, last_month, receive2, last_inventory from whinventory where item_no = $gr_item";
	//		$data_inv = oci_parse($connect, $cek_inv);
	//		oci_execute($data_inv);
	//		$dt_inv = oci_fetch_object($data_inv);
	//		$nm = $dt_inv->MONTH;
//
	//		if($nm == $now_month){
	//			$receive_new = floatval($dt_inv->RECEIVE1) + floatval($gr_qty_act);
	//			$inventory_new = floatval($dt_inv->THIS_INVENTORY) + floatval($gr_qty_act);
	//			$upd_inv = "update whinventory set receive1=$receive_new, this_inventory=$inventory_new where item_no = $gr_item ";
	//			echo $ins1."<br/>";
	//			$data_upd_inv = oci_parse($connect, $upd_inv);
	//			oci_execute($data_upd_inv);
	//		}else{		//if(($dt_inv->MONTH) == intval($now_month)-1)
	//			$receive2_new = floatval($dt_inv->RECEIVE2) + floatval($gr_qty_act);
	//			$inventory_new = floatval($dt_inv->THIS_INVENTORY) + floatval($gr_qty_act);
	//			$inventory2_new = floatval($dt_inv->LAST_INVENTORY) + floatval($gr_qty_act);
	//			$upd_inv = "update whinventory set receive2 = $receive2_new  , last_inventory = $inventory2_new, this_inventory = $inventory_new where item_no=$gr_item ";
	//			echo $upd_inv;
	//			$data_upd_inv = oci_parse($connect, $upd_inv);
	//			oci_execute($data_upd_inv);
	//		}
	//	}
//
	//	//INSERT KE TRANSACTION
	//	$splt = split('-', $gr_date);
	//	$month_acc = intval($splt[0].$splt[1]);
//
	//	$field_tr  = "operation_date,"		;	$value_tr  = "TO_DATE('$now2','yyyy-mm-dd'),"	;
	//	$field_tr .= "section_code,"		;	$value_tr .= "100,"								;
	//	$field_tr .= "item_no,"				;	$value_tr .= "$gr_item,"						;
	//	$field_tr .= "item_name,"			;	$value_tr .= "'$gr_item_name',"					;
	//	$field_tr .= "item_description,"	;	$value_tr .= "'$gr_desc',"						;
	//	$field_tr .= "stock_subject_code,"	;	$value_tr .= "'$gr_stock_subject_code',"		;
	//	$field_tr .= "accounting_month,"	;	$value_tr .= "$month_acc,"						;
	//	$field_tr .= "slip_date,"			;	$value_tr .= "TO_DATE('$gr_date','yyyy-mm-dd'),";
	//	$field_tr .= "slip_type,"			;	$value_tr .= "'$gr_slip',"						;
	//	$field_tr .= "slip_no,"				;	$value_tr .= "'$gr_no',"						;
	//	$field_tr .= "slip_quantity,"		;	$value_tr .= "$gr_qty_act,"						;
	//	$field_tr .= "slip_price,"			;	$value_tr .= "$gr_price,"						;
	//	$field_tr .= "slip_amount,"			;	$value_tr .= "$amt_l,"							;
	//	$field_tr .= "curr_code,"			;	$value_tr .= "$gr_curr,"						;
	//	$field_tr .= "standard_price,"		;	$value_tr .= "$gr_standard_price,"				;
	//	$field_tr .= "standard_amount,"		;	$value_tr .= "$amt_l,"							;
	//	$field_tr .= "suppliers_price,"		;	$value_tr .= "$gr_suppliers_price,"				;
	//	$field_tr .= "company_code,"		;	$value_tr .= "$gr_supp,"						;
	//	$field_tr .= "order_number,"		;	$value_tr .= "'$gr_pono',"						;
	//	$field_tr .= "line_no,"				;	$value_tr .= "$gr_po_line,"						;
	//	$field_tr .= "cost_process_code,"	;	$value_tr .= "'$gr_cost_process_code',"			;
	//	$field_tr .= "cost_subject_code,"	;	$value_tr .= "'$gr_cost_subject_code',"			;
	//	$field_tr .= "purchase_quantity,"	;	$value_tr .= "$gr_qty_act,"						;
	//	$field_tr .= "purchase_price,"		;	$value_tr .= "$gr_price,"						;
	//	$field_tr .= "purchase_amount,"		;	$value_tr .= "$amt_l,"							;
	//	$field_tr .= "purchase_unit,"		;	$value_tr .= "'$gr_uomq',"						;
	//	$field_tr .= "unit_stock,"			;	$value_tr .= "'$gr_uomq',"						;
	//	$field_tr .= "ex_rate"				;	$value_tr .= "$gr_rate"							;
	//	chop($field_tr);              			chop($value_tr);
	//	$ins3 = "insert into transaction ($field_tr) select $value_tr from dual 
	//		where not exists (
	//			select * from transaction 
	//			where slip_no='$gr_no' and item_no=$gr_item and line_no=$gr_po_line and 
	//			slip_date=TO_DATE('$gr_date','yyyy-mm-dd') and order_number='$gr_pono' ) ";
	//	echo $ins3."<br/>";
	//	$data_ins3 = oci_parse($connect, $ins3);
	//	oci_execute($data_ins3);
//
	//	//UPDATE GR_QTY(PO_DETAILS) --belum masuk
	//	$upd =  "update po_details set gr_qty = gr_qty + $gr_qty_act, bal_qty = bal_qty - $gr_qty_act 
	//		where po_no='$gr_pono' and item_no=$gr_item and line_no=$gr_po_line";
	//	echo $upd."<br/>";
	//	$data_upd = oci_parse($connect, $upd);
	//	oci_execute($data_upd);
//
	//	/* --START-- NEW INSERT ID=2, NAME: UENG, DATE: 30/08/2017*/
	//	$psdr = "BEGIN ZSP_WH_ITEM_FIFO (:v_item_no); end;";
	//	$stmt = oci_parse($connect, $psdr);
	//	oci_bind_by_name($stmt, ':v_item_no', $gr_item);
	//	$res = oci_execute($stmt);
	//	/*END ----------------*/
	//	
	//	echo json_encode(array('successMsg'=>'success'));
	//}
?>
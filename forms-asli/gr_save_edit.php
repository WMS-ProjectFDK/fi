<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$gr_sts = htmlspecialchars($_REQUEST['gr_sts']);
	$gr_line = htmlspecialchars($_REQUEST['gr_line']);
	$gr_line_no_gr = htmlspecialchars($_REQUEST['gr_line_no_gr']); 
	$gr_line_no_po = htmlspecialchars($_REQUEST['gr_line_no_po']);
	$gr_no = htmlspecialchars($_REQUEST['gr_no']);
	$gr_date = htmlspecialchars($_REQUEST['gr_date']);
	$gr_supp = htmlspecialchars($_REQUEST['gr_supp']);
	$gr_supp_name = htmlspecialchars($_REQUEST['gr_supp_name']);
	$gr_remark = htmlspecialchars($_REQUEST['gr_remark']);
	$gr_curr = htmlspecialchars($_REQUEST['gr_curr']);
	$gr_rate = htmlspecialchars($_REQUEST['gr_rate']);
	$gr_pday = htmlspecialchars($_REQUEST['gr_pday']);
	$gr_pdes = htmlspecialchars($_REQUEST['gr_pdes']);
	$gr_amto = htmlspecialchars($_REQUEST['gr_amto']);
	$gr_amtl = htmlspecialchars($_REQUEST['gr_amtl']);
	$gr_slip = htmlspecialchars($_REQUEST['gr_slip']);
	$gr_item = htmlspecialchars($_REQUEST['gr_item']);
	$gr_item_name = htmlspecialchars($_REQUEST['gr_item_name']);

	$gr_stock_subject_code = htmlspecialchars($_REQUEST['gr_stock_subject_code']);
	$gr_class_code = htmlspecialchars($_REQUEST['gr_class_code']);
	$gr_country_code = htmlspecialchars($_REQUEST['gr_country_code']);
	$gr_cost_process_code = htmlspecialchars($_REQUEST['gr_cost_process_code']);
	$gr_cost_subject_code = htmlspecialchars($_REQUEST['gr_cost_subject_code']);
	$gr_standard_price = htmlspecialchars($_REQUEST['gr_standard_price']);
	$gr_suppliers_price = htmlspecialchars($_REQUEST['gr_suppliers_price']);

	$gr_desc = htmlspecialchars($_REQUEST['gr_desc']);
	$gr_orig = htmlspecialchars($_REQUEST['gr_orig']);
	$gr_pono = htmlspecialchars($_REQUEST['gr_pono']);
	$gr_po_date = htmlspecialchars($_REQUEST['gr_po_date']);
	$gr_po_line = htmlspecialchars($_REQUEST['gr_po_line']);
	$gr_qty = htmlspecialchars($_REQUEST['gr_qty']);
	$gr_uomq = htmlspecialchars($_REQUEST['gr_uomq']);
	$gr_price  = htmlspecialchars($_REQUEST['gr_price']);
	$gr_qty_act = htmlspecialchars($_REQUEST['gr_qty_act']);
	$gr_sts_wh = htmlspecialchars($_REQUEST['gr_sts_wh']);
	
	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$now2=date('Y-m-d');

	if($gr_sts == 'HEADER'){
		$upd_amt = "update gr_header set amt_o = $gr_amto, amt_l = $gr_amtl, gr_date = TO_DATE('$gr_date','yyyy-mm-dd')
			where gr_no = '$gr_no'";
		echo $upd_amt;
		$data_upd_amt = oci_parse($connect, $upd_amt);
		oci_execute($data_upd_amt);

		$upd_amt_fdac = "update account_payable set amt = $gr_amtl, amt_f = $gr_amto
			payment_date = TO_DATE('$gr_date','yyyy-mm-dd'), reg_date = TO_DATE('$gr_date','yyyy-mm-dd'), upto_date = TO_DATE('$now2','yyyy-mm-dd')
			where gr_no='$gr_no'";
		echo $upd_amt_fdac;
		$data_upd_amt_fdac = oci_parse($connect, $upd_amt_fdac);
		oci_execute($data_upd_amt_fdac);
	}elseif ($gr_sts == 'DETAILS'){
		if($gr_line_no_gr == 'NEW' ){
			//INSERT KE GR_DETAILS 
			$field_grd  = "gr_no,"			;	$value_grd  = "'$gr_no',"						;
			$field_grd .= "line_no,"		;	$value_grd .= "$gr_line,"						;
			$field_grd .= "item_no,"		;	$value_grd .= "$gr_item,"						;
			$field_grd .= "origin_code,"	;	$value_grd .= "'$gr_orig',"						;
			$field_grd .= "po_no,"			;	$value_grd .= "'$gr_pono',"						;
			$field_grd .= "po_line_no,"		;	$value_grd .= "$gr_line_no_po,"					;
			$field_grd .= "qty,"			;	$value_grd .= "$gr_qty_act,"					;
			$field_grd .= "uom_q,"			;	$value_grd .= "$gr_uomq,"						;
			$field_grd .= "u_price,"		;	$value_grd .= "$gr_price,"						;
			$field_grd .= "amt_o,"			;	$value_grd .= "$gr_amto,"						;
			$field_grd .= "amt_l,"			;	$value_grd .= "$gr_amto,"						;
			$field_grd .= "loc1,"			;	$value_grd .= "88475,"							;
			$field_grd .= "loc_qty1,"		;	$value_grd .= "$gr_qty_act,"					;
			$field_grd .= "upto_date,"		;	$value_grd .= "TO_DATE('$now2','yyyy-mm-dd'),"	;
			$field_grd .= "reg_date"		;	$value_grd .= "TO_DATE('$now2','yyyy-mm-dd')"	;
			chop($field_grd) ;              chop($value_grd);
			$ins2 = "insert into gr_details ($field_grd) select $value_grd from dual 
				where not exists(select * from gr_details where gr_no = '$gr_no' and line_no = $gr_line)";
			echo $ins2."<br/>";
			$data_ins2 = oci_parse($connect, $ins2);
			oci_execute($data_ins2);

			// INSERT KE FDAC_PURCHASE_TRN
			$field_fdac  = "DATA_TYPE,"				; $value_fdac  = "130," 								;
			$field_fdac .= "COMPANY_CODE,"			; $value_fdac .= "88475," 								;
			$field_fdac .= "VENDOR_CODE,"			; $value_fdac .= "'$gr_supp'," 							;
			$field_fdac .= "ITEM_NO,"				; $value_fdac .= "'$gr_item'," 							;
			$field_fdac .= "DATA_DATE,"				; $value_fdac .= "to_date('$gr_date','yyyy-mm-dd')," 	;
			$field_fdac .= "QUANTITY,"				; $value_fdac .= "'$gr_qty_act'," 						;
			$field_fdac .= "PP,"					; $value_fdac .= "'$gr_price'," 						;
			$field_fdac .= "PP_CURR_CODE,"			; $value_fdac .= "'$gr_curr'," 							;
			$field_fdac .= "PURCHASE_AMOUNT,"		; $value_fdac .= "'$gr_amto'," 							;
			$field_fdac .= "ITEM_TYPE,"				; $value_fdac .= "$gr_stock_subject_code," 				;
			$field_fdac .= "DATA_SOURCE_TYPE,"		; $value_fdac .= "'PGL-FI'," 							;
			$field_fdac .= "CONSUMPTION_TAX,"		; $value_fdac .= "0," 									;
			$field_fdac .= "NEW_PP,"				; $value_fdac .= "0,"									;
			$field_fdac .= "LAST_PP,"				; $value_fdac .= "0,"									;
			$field_fdac .= "CHECK_NO,"				; $value_fdac .= "'$gr_no',"							;
			$field_fdac .= "INVOICE_NO,"			; $value_fdac .= "'$gr_no',"							;
			$field_fdac .= "OPERATION_DATE,"		; $value_fdac .= "TO_DATE('$now2','yyyy-mm-dd'),"		;
			$field_fdac .= "INFO_TYPE,"				; $value_fdac .= "0,"									;
			$field_fdac .= "PURCHASE_DATE,"			; $value_fdac .= "'$gr_po_date',"						;
			$field_fdac .= "PO_DATE,"				; $value_fdac .= "'$gr_po_date',"						;
			$field_fdac .= "PO_NO,"					; $value_fdac .= "'$gr_pono',"							;
			$field_fdac .= "LINE_NO,"				; $value_fdac .= "'$gr_line_no_po',"					;
			$field_fdac .= "OPERATION_TYPE,"		; $value_fdac .= "0,"									;
			$field_fdac .= "ITEM,"					; $value_fdac .= "'$gr_desc',"							;
			$field_fdac .= "SRC_CLASS_CODE,"		; $value_fdac .= "'$gr_class_code',"					;
			$field_fdac .= "SECTION_CODE,"			; $value_fdac .= "88475,"								;
			$field_fdac .= "PERSON_CODE,"			; $value_fdac .= "'FI99999',"							;
			$field_fdac .= "VENDOR,"				; $value_fdac .= "'$gr_supp_name',"						;
			$field_fdac .= "BUY_COUNTRY_CODE"		; $value_fdac .= "'$gr_country_code'"					;
			chop($field_fdac);						chop($value_fdac);

			$ins5  = "insert into fdac_purchase_trn ($field_fdac) select $value_fdac from dual
				where not exists (select * from fdac_purchase_trn where invoice_no='$gr_no' AND line_no = $gr_line_no_po )";
			echo $ins5."<br/>";
			$data_ins5 = oci_parse($connect, $ins5);
			oci_execute($data_ins5);

			//UPDATE WHINVENTORY
			$split = split('-', $gr_date);
			$now_month = $split[0].$split[1];
			$last_month = intval($now_month)-1;

			if($gr_sts_wh == '0'){
				//insert whinvwntory
				$field_whi  = "operation_date,"		;	$value_whi  = "TO_DATE('$now2','yyyy-mm-dd'),"	;
				$field_whi .= "section_code,"		;	$value_whi .= "100,"							;
				$field_whi .= "item_no,"			;	$value_whi .= "$gr_item,"						;
				$field_whi .= "this_month,"			;	$value_whi .= "$now_month,"						;
				$field_whi .= "receive1,"			;	$value_whi .= "$gr_qty_act,"					;
				$field_whi .= "other_receive1,"		;	$value_whi .= "0,"								;
				$field_whi .= "issue1,"				;	$value_whi .= "0,"								;
				$field_whi .= "other_issue1,"		;	$value_whi .= "0,"								;
				$field_whi .= "stocktaking_adjust1,";	$value_whi .= "0,"								;
				$field_whi .= "this_inventory,"		;	$value_whi .= "$gr_qty_act,"					;
				$field_whi .= "last_month,"			;	$value_whi .= "$last_month,"					;
				$field_whi .= "receive2,"			;	$value_whi .= "0,"								;
				$field_whi .= "other_receive2,"		;	$value_whi .= "0,"								;
				$field_whi .= "issue2,"				;	$value_whi .= "0,"								;
				$field_whi .= "other_issue2,"		;	$value_whi .= "0,"								;
				$field_whi .= "stocktaking_adjust2,";	$value_whi .= "0,"								;
				$field_whi .= "last_inventory,"		;	$value_whi .= "0,"								;
				$field_whi .= "last2_inventory"		;	$value_whi .= "0"								;
				chop($field_whi);              			chop($value_whi);
				$ins_item = "insert into whinventory ($field_whi) select $value_whi from dual
						where not exists (select * from whinventory where item_no = $gr_item) ";
				echo $ins_item."<br/>";
				$data_ins_item = oci_parse($connect, $ins_item);
				oci_execute($data_ins_item);
			}else{
				$cek_inv = "select coalesce(this_month,0) as month, receive1, this_inventory, last_month, receive2, last_inventory from whinventory where item_no = $gr_item";
				$data_inv = oci_parse($connect, $cek_inv);
				oci_execute($data_inv);
				$dt_inv = oci_fetch_object($data_inv);
				$nm = $dt_inv->MONTH;

				if($nm == $now_month){
					$receive_new = floatval($dt_inv->RECEIVE1) + floatval($gr_qty_act);
					$inventory_new = floatval($dt_inv->THIS_INVENTORY) + floatval($gr_qty_act);
					$upd_inv = "update whinventory set receive1 = $receive_new, this_inventory = $inventory_new where item_no = $gr_item ";
					echo $ins1."<br/>";
					$data_upd_inv = oci_parse($connect, $upd_inv);
					oci_execute($data_upd_inv);
				}else{		//if(($dt_inv->MONTH) == intval($now_month)-1)
					$receive2_new = floatval($dt_inv->RECEIVE2) + floatval($gr_qty_act);
					$inventory_new = floatval($dt_inv->THIS_INVENTORY) + floatval($gr_qty_act);
					$inventory2_new = floatval($dt_inv->LAST_INVENTORY) + floatval($gr_qty_act);
					$upd_inv = "update whinventory set receive2 = $receive2_new  , last_inventory = $inventory2_new, this_inventory = $inventory_new where item_no = $gr_item ";
					echo $upd_inv;
					$data_upd_inv = oci_parse($connect, $upd_inv);
					oci_execute($data_upd_inv);
				}
			}

			//INSERT KE TRANSACTION
			$splt = split('-', $gr_date);
			$month_acc = intval($splt[0].$splt[1]);

			$field_tr  = "operation_date,"		;	$value_tr  = "TO_DATE('$now2','yyyy-mm-dd'),"	;
			$field_tr .= "section_code,"		;	$value_tr .= "100,"								;
			$field_tr .= "item_no,"				;	$value_tr .= "$gr_item,"						;
			$field_tr .= "item_name,"			;	$value_tr .= "'$gr_item_name',"					;
			$field_tr .= "item_description,"	;	$value_tr .= "'$gr_desc',"						;
			$field_tr .= "stock_subject_code,"	;	$value_tr .= "'$gr_stock_subject_code',"		;
			$field_tr .= "accounting_month,"	;	$value_tr .= "$month_acc,"						;
			$field_tr .= "slip_date,"			;	$value_tr .= "TO_DATE('$gr_date','yyyy-mm-dd'),";
			$field_tr .= "slip_type,"			;	$value_tr .= "'$gr_slip',"						;
			$field_tr .= "slip_no,"				;	$value_tr .= "'$gr_no',"						;
			$field_tr .= "slip_quantity,"		;	$value_tr .= "$gr_qty_act,"						;
			$field_tr .= "slip_price,"			;	$value_tr .= "$gr_price,"						;
			$field_tr .= "slip_amount,"			;	$value_tr .= "$gr_amtl,"						;
			$field_tr .= "curr_code,"			;	$value_tr .= "$gr_curr,"						;
			$field_tr .= "standard_price,"		;	$value_tr .= "$gr_standard_price,"				;
			$field_tr .= "standard_amount,"		;	$value_tr .= "$gr_amtl,"						;
			$field_tr .= "suppliers_price,"		;	$value_tr .= "$gr_suppliers_price,"				;
			$field_tr .= "company_code,"		;	$value_tr .= "$gr_supp,"						;
			$field_tr .= "order_number,"		;	$value_tr .= "'$gr_pono',"						;
			$field_tr .= "line_no,"				;	$value_tr .= "$gr_line_no_po,"					;
			$field_tr .= "cost_process_code,"	;	$value_tr .= "'$gr_cost_process_code',"			;
			$field_tr .= "cost_subject_code,"	;	$value_tr .= "'$gr_cost_subject_code',"			;
			$field_tr .= "purchase_quantity,"	;	$value_tr .= "$gr_qty_act,"						;
			$field_tr .= "purchase_price,"		;	$value_tr .= "$gr_price,"						;
			$field_tr .= "purchase_amount,"		;	$value_tr .= "$gr_amtl,"						;
			$field_tr .= "purchase_unit,"		;	$value_tr .= "'$gr_uomq',"						;
			$field_tr .= "unit_stock,"			;	$value_tr .= "'$gr_uomq',"						;
			$field_tr .= "ex_rate"				;	$value_tr .= "$gr_rate"							;
			chop($field_tr);              			chop($value_tr);
			$ins3 = "insert into transaction ($field_tr) select $value_tr from dual 
				where not exists (select * from transaction where slip_no = '$gr_no' and item_no = $gr_item and line_no = $gr_line_no_po)";
			echo $ins3."<br/>";
			$data_ins3 = oci_parse($connect, $ins3);
			oci_execute($data_ins3);

			//UPDATE GR_QTY(PO_DETAILS) --belum masuk
			$upd =  "update po_details set gr_qty = gr_qty + $gr_qty_act, bal_qty = bal_qty - $gr_qty_act 
				where po_no = '$gr_pono' and item_no = $gr_item and line_no = $gr_line_no_po";
			echo $upd."<br/>";
			$data_upd = oci_parse($connect, $upd);
			oci_execute($data_upd);

			/* --START-- NEW INSERT ID=2, NAME: UENG, DATE: 30/08/2017*/
			$psdr = "BEGIN ZSP_WH_ITEM_FIFO (:v_item_no); end;";
			$stmt = oci_parse($connect, $psdr);
			oci_bind_by_name($stmt, ':v_item_no', $gr_item);
			$res = oci_execute($stmt);
			/*END ----------------*/

			echo json_encode(array('successMsg'=>'success'));	
		}else{

			//update po_details set gr_qty,bal_qty	
			$upd =  "update po_details set gr_qty = (gr_qty - (select qty from gr_details where gr_no='$gr_no' and line_no=$gr_line_no_gr)) + $gr_qty_act, 
				bal_qty = (bal_qty + (select qty from gr_details where gr_no = '$gr_no' and line_no = $gr_line_no_gr)) - $gr_qty_act
				where po_no = '$gr_pono' and item_no = $gr_item and line_no = $gr_line_no_po";
			echo $upd.'<br/>';
			$data_upd = oci_parse($connect, $upd);
			oci_execute($data_upd);

			//UPDATE WHINVENTORY
			$split = split('-', $gr_date);
			$now_month = $split[0].$split[1];
			$last_month = intval($now_month)-1;

			$cek_inv = "select coalesce(this_month,0) as month, receive1, this_inventory, last_month, receive2, last_inventory from whinventory where item_no = $gr_item";
			$data_inv = oci_parse($connect, $cek_inv);
			oci_execute($data_inv);
			$dt_inv = oci_fetch_object($data_inv);
			$nm = $dt_inv->MONTH;

			if($nm == $now_month){
				$upd_inv = "update whinventory set receive1 = (receive1 - (select qty from gr_details where gr_no='$gr_no' and line_no=$gr_line_no_gr)) + $gr_qty_act,
						this_inventory = (this_inventory - (select qty from gr_details where gr_no='$gr_no' and line_no=$gr_line_no_gr)) + $gr_qty_act 
						where item_no = $gr_item ";
				echo $ins1."<br/>";
				$data_upd_inv = oci_parse($connect, $upd_inv);
				oci_execute($data_upd_inv);
			}else{
				$upd_inv = "update whinventory set receive2 = receive2 - (select qty from gr_details where gr_no = '$gr_no' and line_no = $gr_line_no_gr)) + $gr_qty_act,
						last_inventory = (last_inventory - (select qty from gr_details where gr_no = '$gr_no' and line_no = $gr_line_no_gr)) + $gr_qty_act,
						this_inventory = this_inventory - (select qty from gr_details where gr_no = '$gr_no' and line_no = $gr_line_no_gr)) + $gr_qty_act,
						where item_no = $gr_item ";
				echo $upd_inv;
				$data_upd_inv = oci_parse($connect, $upd_inv);
				oci_execute($data_upd_inv);
			}

			//update gr_details set qty, amt_o, amt_l.loc_qty1, upto_date
			$upd1 = "update gr_details set qty = $gr_qty_act ,amt_o = $gr_amto, amt_l = $gr_amtl
				where gr_no = '$gr_no' and line_no = $gr_line_no_gr ";
			echo $upd1.'<br/>';
			$data_upd1 = oci_parse($connect, $upd1);
			oci_execute($data_upd1);

			//update FDAC_PURCHASE_TRN quantity, purchase_amount, 
			$upd2 = "update fdac_purchase_trn set quantity = $gr_qty_act, purchase_amount = $gr_amto
				data_date = TO_DATE('$gr_date','yyyy-mm-dd'), operation_date = TO_DATE('$gr_date','yyyy-mm-dd')
				where invoice_no = '$gr_no' and line_no = $gr_line_no_po ";
			echo $upd2.'<br/>';
			$data_upd2 = oci_parse($connect, $upd2);
			oci_execute($data_upd2);

			//update transaction set slip_quntity, slip_amount, standard_amount, purchase quantity, purchase_amount
			$upd3 = "update transaction set slip_quantity = $gr_qty_act, slip_amount = $gr_amtl, standard_amount = $gr_amtl, purchase_quantity = $gr_qty_act, 
				purchase_amount = $gr_amtl, slip_date = TO_DATE('$gr_date','yyyy-mm-dd'), accounting_month = to_char('$gr_date','yyyymm')
				where slip_no = '$gr_no' and line_no = $gr_line_no_po ";
			echo $upd3.'<br/>';
			$data_upd3 = oci_parse($connect, $upd3);
			oci_execute($data_upd3);

			/* --START-- NEW INSERT ID=2, NAME: UENG, DATE: 30/08/2017*/
			$psdr = "BEGIN ZSP_WH_ITEM_FIFO (:v_item_no); end;";
			$stmt = oci_parse($connect, $psdr);
			oci_bind_by_name($stmt, ':v_item_no', $gr_item);
			$res = oci_execute($stmt);
			/*END ----------------*/
			
		}
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
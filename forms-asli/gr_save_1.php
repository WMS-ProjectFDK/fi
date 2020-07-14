<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	
	$gr_no = htmlspecialchars($_REQUEST['gr_no']);
	$gr_line = htmlspecialchars($_REQUEST['gr_line']);
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
	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$now2=date('Y-m-d');

	//amt_o = qty_actual*u_price
	$amt_o = floatval($gr_qty_act)*floatval($gr_price);

	//amt_l = qty_actual*u_price*ex_rate
	$amt_l = floatval($gr_qty_act)*floatval($gr_price)*floatval($gr_rate);

	// HITUNG DUEDATE
	$plusDay = "+".intval($gr_pday)." day";
	$tambah_date = strtotime($plusDay,strtotime($gr_date));
	$due_date = date('Y-m-d',$tambah_date);

	//echo $gr_line;

	//CEK GR_HEADERS
	$cek = "select count(*) as jum_gr from gr_header where gr_no='$gr_no'";
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_object($data);

	if(intval($dt->JUM_GR) == 0){
		// INSERT GR_HEADER
		$ins1 = "insert into gr_header(gr_no,
									  gr_date,
									  inv_date,
									  supplier_code,
									  inv_no,
									  curr_code,
									  ex_rate,
									  pdays,
									  pdesc,
									  due_date,
									  remark,
									  amt_o,
									  amt_l,
									  person_code,
									  slip_type)
							values('$gr_no',
								   TO_DATE('$gr_date','yyyy-mm-dd'),
								   TO_DATE('$gr_date','yyyy-mm-dd'),
								   $gr_supp,
								   '$gr_no',
								   '$gr_curr',
								   '$gr_rate',
								   '$gr_pday',
								   '$gr_pdes',
								   TO_DATE('$due_date','yyyy-mm-dd'),
								   '$gr_remark',
								   $amt_o,
								   $amt_l,
								   '$user',
								   '$gr_slip')";
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);

		// insert ke account_payable
		$field_ap  = "customer_code,"  ; $value_ap  = "'$gr_supp',"                  	   ;
		$field_ap .= "bl_no,"          ; $value_ap .= "'$gr_no',"                          ;
		$field_ap .= "type,"           ; $value_ap .= "'1',"                               ;
		$field_ap .= "pr_no,"          ; $value_ap .= "null,"                              ;
		$field_ap .= "payment_date,"   ; $value_ap .= "to_date('$gr_date','yyyy-mm-dd'),"  ;
		$field_ap .= "amt,"            ; $value_ap .= "$amt_l," 						   ;
		$field_ap .= "bank,"           ; $value_ap .= "null,"                              ;
		$field_ap .= "bl_date,"        ; $value_ap .= "to_date('$due_date','yyyy-mm-dd')," ;
		$field_ap .= "curr_code,"      ; $value_ap .= "'$gr_curr',"                        ;
		$field_ap .= "rate,"           ; $value_ap .= "'$gr_rate',"                        ;
		$field_ap .= "amt_f,"          ; $value_ap .= "$amt_o,"  						   ;
		$field_ap .= "reg_date,"       ; $value_ap .= "TO_DATE('$now2','yyyy-mm-dd'),"     ;
		$field_ap .= "upto_date,"      ; $value_ap .= "TO_DATE('$now2','yyyy-mm-dd'),"	   ;
		$field_ap .= "pdays,"          ; $value_ap .= "'$gr_pday',"                        ;
		$field_ap .= "pdesc,"          ; $value_ap .= "'$gr_pdes',"                        ;
		$field_ap .= "gr_no"           ; $value_ap .= "'$gr_no'"                           ;
		chop($field_ap) ;              chop($value_ap) ;

		$ins4  = "insert into account_payable ($field_ap) VALUES ($value_ap)" ;
		//echo $ins4;
		$data_ins4 = oci_parse($connect, $ins4);
		oci_execute($data_ins4);
	}else{
		//cek
		$cek_amt = "select a.amt_o, a.amt_l, b.amt_f, b.amt from gr_header a
			inner join account_payable b on a.gr_no=b.bl_no
			where a.gr_no='$gr_no'";
		$data_cek_amt = oci_parse($connect, $cek_amt);
		oci_execute($data_cek_amt);
		$dt_AMT = oci_fetch_object($data_cek_amt);
		$amt_o_new_hd = floatval($dt_AMT->AMT_O) + floatval($amt_o);
		$amt_l_new_hd = floatval($dt_AMT->AMT_L) + floatval($amt_l);

		$amt_f_new_hd = floatval($dt_AMT->AMT_F) + floatval($amt_o);
		$amt_new_hd = floatval($dt_AMT->AMT) + floatval($amt_l);
		
		//update amt_l & amt_o
		$upd_amt = "update gr_header set amt_o=$amt_o_new_hd, amt_l=$amt_l_new_hd where gr_no='$gr_no'";
		$data_upd_amt = oci_parse($connect, $upd_amt);
		oci_execute($data_upd_amt);

		$upd_amt_fdac = "update account_payable set amt=$amt_new_hd, amt_f=$amt_f_new_hd where bl_no='$gr_no'";
		$data_upd_amt_fdac = oci_parse($connect, $upd_amt_fdac);
		oci_execute($data_upd_amt_fdac);
	}

	//INSERT DETAILS 
	$ins2 = "insert into gr_details(gr_no,
								 line_no,
								 item_no,
								 origin_code,
								 po_no,
								 po_line_no,
								 qty,
								 uom_q,
								 u_price,
								 amt_o,
								 amt_l,
								 loc1,
								 loc_qty1,
								 upto_date,
								 reg_date)
						values('$gr_no',
							   $gr_line,
							   $gr_item,
							   '$gr_orig',
							   '$gr_pono',
							   $gr_po_line,
							   $gr_qty_act,
							   $gr_uomq,
							   $gr_price,
							   $amt_o,
							   $amt_l,
							   88475,
							   $gr_qty_act,
							   TO_DATE('$now2','yyyy-mm-dd'),
							   TO_DATE('$now2','yyyy-mm-dd'))";
	$data_ins2 = oci_parse($connect, $ins2);
	oci_execute($data_ins2);

	if ($data_ins2){ 
		//echo "select gr_qty from po_details where po_no='$gr_pono' and item_no=$gr_item and line_no=$gr_po_line";
		$cek_qty = "select gr_qty,bal_qty from po_details where po_no='$gr_pono' and item_no=$gr_item and line_no=$gr_po_line ";
		$data_cekQ = oci_parse($connect, $cek_qty);
		oci_execute($data_cekQ);
		$dt_cekQ = oci_fetch_object($data_cekQ);
		$gr_qty_new = floatval($dt_cekQ->GR_QTY) + floatval($gr_qty_act);
		$bal_qty_new = floatval($dt_cekQ->BAL_QTY) - floatval($gr_qty_act);

		if($data_cekQ){
			//UPDATE GR_QTY(PO_DETAILS)
			$upd =  "update po_details set gr_qty=$gr_qty_new, bal_qty=$bal_qty_new where po_no='$gr_pono' and item_no=$gr_item and line_no=$gr_po_line";
	    	$data_upd = oci_parse($connect, $upd);
			oci_execute($data_upd);

			//UPDATE WHINVENTORY
			$split = split('-', $gr_date);
			$now_month = $split[0].$split[1];
			$last_month = intval($now_month)-1;

			//cek item di inventory
			$cek_item_inv = "select count(*) jumitem from whinventory where item_no=$gr_item";
			$data_item_inv = oci_parse($connect, $cek_item_inv);
			oci_execute($data_item_inv);
			$dt_item_inv = oci_fetch_object($data_item_inv);

			if(intval($dt_item_inv->JUMITEM) == 0){
				//insert whinvwntory
				$ins_item = "insert into whinventory (operation_date,
													   section_code,
													   item_no,
													   this_month,
													   receive1,
													   other_receive1,
													   issue1,
													   other_issue1,
													   stocktaking_adjust1,
													   this_inventory,
													   last_month,
													   receive2,
													   other_receive2,
													   issue2,
													   other_issue2,
													   stocktaking_adjust2,
													   last_inventory,
													   last2_inventory)
											values(TO_DATE('$now2','yyyy-mm-dd'),
												   100,
												   $gr_item,
												   $now_month,
												   $gr_qty_act,
												   0,
												   0,
												   0,
												   0,
												   $gr_qty_act,
												   $last_month,
												   0,
												   0,
												   0,
												   0,
												   0,
												   0,
												   0)";
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
					$upd_inv = "update whinventory set receive1=$receive_new, this_inventory=$inventory_new where item_no = $gr_item ";
					$data_upd_inv = oci_parse($connect, $upd_inv);
					oci_execute($data_upd_inv);
				}else{		//if(($dt_inv->MONTH) == intval($now_month)-1)
					$receive2_new = floatval($dt_inv->RECEIVE2) + floatval($gr_qty_act);
					$inventory_new = floatval($dt_inv->THIS_INVENTORY) + floatval($gr_qty_act);
					$inventory2_new = floatval($dt_inv->LAST_INVENTORY) + floatval($gr_qty_act);
					$upd_inv = "update hinventory set receive2 = $receive2_new  , last_inventory = $inventory2_new, this_inventory = $inventory_new where item_no=$gr_item ";
					$data_upd_inv = oci_parse($connect, $upd_inv);
					oci_execute($data_upd_inv);
				}
			}

			//INSERT KE TRANSACTION
			$splt = split('-', $now2);
			$month_acc = intval($splt[0].$splt[1]);
			$ins3 = "insert into transaction (operation_date,
											 section_code,
											 item_no,
											 item_name,
											 item_description,
											 stock_subject_code,
											 accounting_month,
											 slip_date,
											 slip_type,
											 slip_no,
											 slip_quantity,
											 slip_price,
											 slip_amount,
											 curr_code,
											 standard_price,
											 standard_amount,
											 suppliers_price,
											 company_code,
											 order_number,
											 line_no,
											 cost_process_code,
											 cost_subject_code,
											 purchase_quantity,
											 purchase_price,
											 purchase_amount,
											 purchase_unit,
											 unit_stock,
											 ex_rate)
									values(TO_DATE('$now2','yyyy-mm-dd'),
										   100,
										   $gr_item,
										   '$gr_item_name',
										   '$gr_desc',
										   '$gr_stock_subject_code',
										   $month_acc,
										   TO_DATE('$gr_date','yyyy-mm-dd'),
										   '$gr_slip',
										   '$gr_no',
										   $gr_qty_act,
										   $gr_price,
										   $amt_l,
										   $gr_curr,
										   $gr_standard_price,
										   $amt_l,
										   $gr_suppliers_price,
										   $gr_supp,
										   '$gr_pono',
										   $gr_po_line,
										   '$gr_cost_process_code',
										   '$gr_cost_subject_code',
										   $gr_qty_act,
										   $gr_price,
										   $amt_l,
										   '$gr_uomq',
										   '$gr_uomq',
										   $gr_rate
										   )";
			$data_ins3 = oci_parse($connect, $ins3);
			oci_execute($data_ins3);

			if($data_ins3){
				// insert ke fdac_purchase_trn
				$field_fdac  = "DATA_TYPE,"				; $value_fdac  = "130," 								;
				$field_fdac .= "COMPANY_CODE,"			; $value_fdac .= "88475," 								;
				$field_fdac .= "VENDOR_CODE,"			; $value_fdac .= "'$gr_supp'," 							;
				$field_fdac .= "ITEM_NO,"				; $value_fdac .= "'$gr_item'," 							;
				$field_fdac .= "DATA_DATE,"				; $value_fdac .= "to_date('$gr_date','yyyy-mm-dd')," 	;
				$field_fdac .= "QUANTITY,"				; $value_fdac .= "'$gr_qty_act'," 						;
				$field_fdac .= "PP,"					; $value_fdac .= "'$gr_price'," 						;
				$field_fdac .= "PP_CURR_CODE,"			; $value_fdac .= "'$gr_curr'," 							;
				$field_fdac .= "PURCHASE_AMOUNT,"		; $value_fdac .= "'$amt_o'," 							;
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
				$field_fdac .= "LINE_NO,"				; $value_fdac .= "'$gr_po_line',"						;
				$field_fdac .= "OPERATION_TYPE,"		; $value_fdac .= "0,"									;
				$field_fdac .= "ITEM,"					; $value_fdac .= "'$gr_desc',"							;
				$field_fdac .= "SRC_CLASS_CODE,"		; $value_fdac .= "'$gr_class_code',"					;
				$field_fdac .= "SECTION_CODE,"			; $value_fdac .= "88475,"								;
				$field_fdac .= "PERSON_CODE,"			; $value_fdac .= "'FI99999',"							;
				$field_fdac .= "VENDOR,"				; $value_fdac .= "'$gr_supp_name',"						;
				$field_fdac .= "BUY_COUNTRY_CODE"		; $value_fdac .= "'$gr_country_code'"					;
				chop($field_fdac) ;						chop($value_fdac) ;

				$ins5  = "INSERT INTO fdac_purchase_trn ($field_fdac) VALUES ($value_fdac)";
				//echo $ins5;
				$data_ins5 = oci_parse($connect, $ins5);
				oci_execute($data_ins5);
			}
		    echo json_encode(array('successMsg'=>'success'));
		}
    }else{
        echo json_encode(array('errorMsg'=>'Error'));
    }
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../connect/conn.php");
if (isset($_SESSION['id_wms'])) {
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$msg = '';

	foreach($queries as $query){
		$WORK_ORDER = $query->WORK_ORDER;
		$ITEM_NO = $query->ITEM_NO;
		$ITEM_NAME = $query->ITEM_NAME;
		$SI_NO = $query->SI_NO;
		$QUANTITY = $query->QUANTITY;
		$CR_DATE = $query->CR_DATE;
		$EX_FAC_DATE = $query->EX_FAC_DATE;
		$ETD_DATE = $query->ETD_DATE;
		$ETA_DATE = $query->ETA_DATE;
		$SO_NO = $query->SO_NO;
		$PO_NO = $query->PO_NO;
		$PO_LINE_NO = $query->PO_LINE_NO;
		$LINE_NO = $query->LINE_NO;
		$VESSEL = $query->VESSEL;
		$CUST_CODE = $query->CUST_CODE;
		$QTY_ORDER = $query->QTY_ORDER;
		$CR_REMARK = $query->CR_REMARK;
		$CURR_CODE = $query->CURR_CODE;
		$U_PRICE = $query->U_PRICE; 
		$PPBE = $query->PPBE;

		$user = $_SESSION['id_wms'];
		$real_integer = filter_var($QUANTITY, FILTER_SANITIZE_NUMBER_INT);

		//cari ANSWER_NO
		$kode = "select 'S' || 
			substr('0000000' || FST_SEQ.nextval,-7,7) || 
			(substr(10 - (
			substr((substr('0000000' || FST_SEQ.nextval,-7,1) * 1),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-6,1) * 3),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-5,1) * 7),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-4,1) * 1),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-3,1) * 3),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-2,1) * 7),-1,1) + 
			substr((substr('0000000' || FST_SEQ.nextval,-1,1) * 1),-1,1)
			),-1,1)) as answer_no
			from dual " ;
		//echo $kode;
		$data_kode = oci_parse($connect, $kode);
		oci_execute($data_kode);
		$dt_kode = oci_fetch_object($data_kode);
		$ans_no = $dt_kode->ANSWER_NO;
		
		// INSERT ANSWER
		$sql  = "insert into answer (" ;
	    $sql .= " customer_code,"        ; $value  = "$CUST_CODE," ;
	    $sql .= " item_no,"              ; $value .= "i.item_no," ;
	    $sql .= " origin_code,"          ; $value .= "i.origin_code," ;
	    $sql .= " so_no,"                ; $value .= "'$SO_NO'," ;
	    $sql .= " so_line_no,"           ; $value .= "'$LINE_NO'," ;
	    $sql .= " qty,"                  ; $value .= "$real_integer," ;
	    $sql .= " bal_qty,"              ; $value .= "$QTY_ORDER," ;
	    $sql .= " unit_code,"            ; $value .= "i.uom_q," ;
	    $sql .= " data_date,"            ; $value .= "sysdate," ;
	    $sql .= " duein_date,"           ; $value .= "sysdate," ;
	    $sql .= " answer_no,"            ; $value .= "'$ans_no'," ;
	    $sql .= " curr_code,"            ; $value .= "'$CURR_CODE'," ;
	    $sql .= " u_price,"              ; $value .= "'$U_PRICE'," ;
	    $sql .= " operation_date,"       ; $value .= "sysdate," ;
	    $sql .= " work_no,"              ; $value .= "'$WORK_ORDER'," ;
	    $sql .= " remark,"               ; $value .= "'$CR_REMARK'," ;
	    $sql .= " customer_po_no,"       ; $value .= "'$PO_NO'," ;
	    $sql .= " cr_date,"              ; $value .= "'$CR_DATE'," ;
	    $sql .= " stuffy_date,"          ; $value .= "TO_DATE('$EX_FAC_DATE','yyyy-mm-dd'),";
	    $sql .= " etd,"                  ; $value .= "to_date('$ETD_DATE','yyyy-mm-dd')," ;
	    $sql .= " eta,"                  ; $value .= "to_date('$ETA_DATE','yyyy-mm-dd')," ;
	    $sql .= " vessel,"               ; $value .= "'$VESSEL'," ;
	    $sql .= " crs_remark,"           ; $value .= "'$PPBE'," ;
	    $sql .= " customer_po_line_no,"  ; $value .= "'$PO_LINE_NO'," ;
	    $sql .= " si_no "                ; $value .= "'$SI_NO' " ;
	    chop($sql) ;                       chop($value) ;
	    $sql .= ")" ;
	    $sql .= " select $value " ;
	    $sql .= " from item  i " ;
	    $sql .= " where i.item_no = '$ITEM_NO'" ;
	    $sql .= " and not exists (select * from answer where answer_no='$ans_no')" ;
	    // echo $sql;
	    $data_ins = oci_parse($connect, $sql);
		oci_execute($data_ins);
		// echo $sql.'<br/>';

		// INSERT ZTB_SHIPPING PLAN
		$field  = "WO_NO," 		; $value_po  = "'$WORK_ORDER',"						 	;
		$field .= "ITEM_NO,"    ; $value_po .= "'$ITEM_NO',"						 	;
		$field .= "ITEM_NAME,"  ; $value_po .= "'$ITEM_NAME',"						 	;
		$field .= "CR_DATE,"    ; $value_po .= "'$CR_DATE'," 							;
		$field .= "EX_FACT,"    ; $value_po .= "TO_DATE('$EX_FAC_DATE','yyyy-mm-dd'),"	;
		$field .= "ETD,"   	 	; $value_po .= "TO_DATE('$ETD_DATE','yyyy-mm-dd'),"		;
		$field .= "ETA,"        ; $value_po .= "TO_DATE('$ETA_DATE','yyyy-mm-dd'),"		;
		$field .= "SI_NO,"      ; $value_po .= "$SI_NO,"							 	;
		$field .= "SO_NO,"      ; $value_po .= "'$SO_NO',"							 	;
		$field .= "PO_NO,"      ; $value_po .= "'$PO_NO',"							 	;
		$field .= "LINE_NO,"    ; $value_po .= "'$LINE_NO',"							;
		$field .= "QTY,"      	; $value_po .= "'$real_integer', "						;
		$field .= "VESSEL,"      ; $value_po .= "'$VESSEL', "							;
		$field .= "INV_NO"      ; $value_po .= "'$PPBE' "							 	;
		$ins2 = "insert into ztb_shipping_plan ($field) select $value_po from dual";

		$data_ins2 = oci_parse($connect, $ins2);
		// echo $ins2;
		oci_execute($data_ins2);
		// echo $ins2.'<br/>';

		// INSERT GRPANS_OUT
	   	$qry_f  = "company_code," 		;	$qry_v  = "c0.company_code," 					; 
	   	$qry_f .= "customer_code," 		;	$qry_v .= "soh.customer_code," 					; 
	   	$qry_f .= "customer_po_no," 	;	$qry_v .= "soh.customer_po_no," 				; 
	   	$qry_f .= "customer_line_no," 	;	$qry_v .= "nvl(sod.customer_po_line_no,'0') ," 	; 
	   	$qry_f .= "error_flag," 		;	$qry_v .= "null," 								; 
	   	$qry_f .= "error_comment," 		;	$qry_v .= "null," 								; 
	   	$qry_f .= "item_no," 			;	$qry_v .= "sod.item_no," 						; 
	   	$qry_f .= "quantity," 			;	$qry_v .= "ans.qty," 							; 
	   	$qry_f .= "eta," 				;	$qry_v .= "null eta," 							; 
	   	$qry_f .= "etd," 				;	$qry_v .= "ans.data_date etd," 					; 
	   	$qry_f .= "operation_date," 	;	$qry_v .= "sysdate," 							; 
	   	$qry_f .= "item," 				;	$qry_v .= "i.item," 							; 
	   	$qry_f .= "company," 			;	$qry_v .= "c0.company," 						; 
	   	$qry_f .= "customer" 			;	$qry_v .= "c1.company customer" 				;
	   	chop($qry_f) ;                      chop($qry_v) ;

	   	$qry = "insert into grpans_out ($qry_f) select $qry_v " ;
	   	$qry .= " from  ztb_answer ans, " ; 
	   	$qry .= " so_header soh, " ; 
	   	$qry .= " so_details sod, " ; 
	   	$qry .= " (select * from company where company_type=0) c0, " ;
	   	$qry .= " company c1, " ; 
	   	$qry .= " fdkgroup gp, " ; 
	   	$qry .= " item i " ; 
	   	$qry .= " where ans.so_no = soh.so_no (+) " ; 
	   	$qry .= " and ans.so_no = sod.so_no (+) " ; 
	   	$qry .= " and ans.so_line_no = sod.line_no (+) " ; 
	   	$qry .= " and soh.customer_code = c1.company_code (+) " ;
	   	$qry .= " and soh.customer_code = gp.company_code " ; 
	   	$qry .= " and ans.item_no = i.item_no (+) " ; 
	   	$qry .= " and ans.so_no ='$SO_NO' " ; 
	   	$qry .= " and ans.so_line_no ='$LINE_NO' " ;
	   	// echo $qry;
	   	$data_ins2 = oci_parse($connect, $qry);
		oci_execute($data_ins2);
		// echo $qry.'<br/>';

		echo json_encode('success');
	// 	// auto run (trigger: ZTR_ANSWER)
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
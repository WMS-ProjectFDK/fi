<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$user = $_SESSION['id_wms'];

	$sp_SO_NO = htmlspecialchars($_REQUEST['sp_SO_NO']);
	$sp_LINE_NO = htmlspecialchars($_REQUEST['sp_LINE_NO']);
	$sp_CUSTOMER_CODE = htmlspecialchars($_REQUEST['sp_CUSTOMER_CODE']);
	$sp_CUSTOMER_DTL = htmlspecialchars($_REQUEST['sp_CUSTOMER_DTL']);
	$sp_ITEM_NO = htmlspecialchars($_REQUEST['sp_ITEM_NO']);
	$sp_ITEM = htmlspecialchars($_REQUEST['sp_ITEM']);
	$sp_ORIGIN_CODE = htmlspecialchars($_REQUEST['sp_ORIGIN_CODE']);
	$sp_COUNTRY_CODE = htmlspecialchars($_REQUEST['sp_COUNTRY_CODE']);
	$sp_CR_DATE = htmlspecialchars($_REQUEST['sp_CR_DATE']);
	$sp_DATA_DATE = htmlspecialchars($_REQUEST['sp_DATA_DATE']);
	$sp_ETD_DATE = htmlspecialchars($_REQUEST['sp_ETD_DATE']);
	$sp_ETA_DATE = htmlspecialchars($_REQUEST['sp_ETA_DATE']);
	$sp_QTY_SO = htmlspecialchars($_REQUEST['sp_QTY_SO']);
	$sp_PLAN_QTY = htmlspecialchars($_REQUEST['sp_PLAN_QTY']);
	$sp_SI_NO = htmlspecialchars($_REQUEST['sp_SI_NO']);
	$sp_VESSEL = htmlspecialchars($_REQUEST['sp_VESSEL']);
	$sp_PALLET_NO = htmlspecialchars($_REQUEST['sp_PALLET_NO']);
	$sp_CR_REMARK = htmlspecialchars($_REQUEST['sp_CR_REMARK']);
	$sp_FLAG_QTY = htmlspecialchars($_REQUEST['sp_FLAG_QTY']);
	$sp_ANSWER_NO = htmlspecialchars($_REQUEST['sp_ANSWER_NO']);
	$sp_FLAG_ANS = htmlspecialchars($_REQUEST['sp_FLAG_ANS']);
	$sp_PO_NO = htmlspecialchars($_REQUEST['sp_PO_NO']);
	$sp_PO_LINE_NO = htmlspecialchars($_REQUEST['sp_PO_LINE_NO']);
	$sp_WORK_ORDER = htmlspecialchars($_REQUEST['sp_WORK_ORDER']);
	$sp_U_PRICE = htmlspecialchars($_REQUEST['sp_U_PRICE']);
	$sp_CURR_CODE = htmlspecialchars($_REQUEST['sp_CURR_CODE']);

	if($sp_FLAG_ANS == 'Y' AND $sp_ANSWER_NO != ''){
		echo "UPD";

		$sql  = " update  answer set " ;
		$sql .= "  qty = $quantity," ;
		$sql .= "  bal_qty = $bal_qty," ;
		$sql .= "  data_date = to_date('$sp_DATA_DATE','yyyy-mm-dd')," ;
		$sql .= "  duein_date = to_date('$sp_DATA_DATE','ddmmyyyy')," ;
		$sql .= "  so_no = '$sp_SO_NO'," ;
		$sql .= "  so_line_no = '$sp_LINE_NO'," ;
		$sql .= "  operation_date = sysdate," ;
		$sql .= "  work_no        = '$sp_WORK_ORDER'," ;
		$sql .= "  remark         = '$sp_CR_REMARK'," ;
		$sql .= "  customer_po_no = '$sp_c'," ;
		$sql .= "  cr_date        = to_date('$CRG_DATE','ddmmyyyy')," ;
		$sql .= "  stuffy_date    = to_date('$DAT_DATE','ddmmyyyy')," ;
		$sql .= "  etd            = to_date('$ETD_DATE','ddmmyyyy')," ;
		$sql .= "  eta            = to_date('$ETA_DATE','ddmmyyyy')," ;
		$sql .= "  vessel         = '$sp_vssl'," ;
		$sql .= "  crs_remark     = '$sp_crrm'," ;
		$sql .= "  customer_po_line_no = '$customer_po_line_no'," ;
		$sql .= "  si_no          = '$si_no' " ;
		$sql .= "  where answer_no = '$sp_ANSWER_NO' " ;

		echo $sql."<br/>";
	}else{
		echo "INS";

		//cari ANSWER_NO
		$kode = "select 'S' || substr('0000000' || FST_SEQ.nextval,-7,7) || (substr(10 - (substr((substr('0000000' || FST_SEQ.nextval,-7,1) * 1),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-6,1) * 3),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-5,1) * 7),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-4,1) * 1),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-3,1) * 3),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-2,1) * 7),-1,1) + 
		substr((substr('0000000' || FST_SEQ.nextval,-1,1) * 1),-1,1)),-1,1)) as answer_no
		from dual " ;
		$data_kode = oci_parse($connect, $kode);
		oci_execute($data_kode);
		$dt_kode = oci_fetch_object($data_kode);
		$ans_no = $dt_kode->ANSWER_NO;
		echo $ans_no;
		
		$sql  = " insert into answer (" ;
	    $sql .= "  customer_code,"        ; $value  = "$sp_cust," ;
	    $sql .= "  item_no,"              ; $value .= "i.item_no," ;
	    $sql .= "  origin_code,"          ; $value .= "i.origin_code," ;
	    $sql .= "  so_no,"                ; $value .= "'$sp_sono'," ;
	    $sql .= "  so_line_no,"           ; $value .= "'$sp_line'," ;
	    $sql .= "  qty,"                  ; $value .= "$sp_qqty," ;
	    $sql .= "  bal_qty,"              ; $value .= "$sp_pqty," ;
	    $sql .= "  unit_code,"            ; $value .= "i.uom_q," ;
	    $sql .= "  data_date,"            ; $value .= "to_date('$DAT_DATE','yyyy-mm-dd')," ;
	    $sql .= "  duein_date,"           ; $value .= "to_date('$DAT_DATE','yyyy-mm-dd')," ;
	    $sql .= "  answer_no,"            ; $value .= "'$ans_no'," ;
	    $sql .= "  curr_code,"            ; $value .= "'$CURR_CODE'," ;
	    $sql .= "  u_price,"              ; $value .= "'$U_PRICE'," ;
	    $sql .= "  operation_date,"       ; $value .= "sysdate," ;
	    $sql .= "  work_no,"              ; $value .= "'$WORK_ORDER'," ;
	    $sql .= "  remark,"               ; $value .= "'$sp_crrm'," ;
	    $sql .= "  customer_po_no,"       ; $value .= "'$PO_NO'," ;
	    $sql .= "  cr_date,"              ; $value .= "to_date('$CRG_DATE','yyyy-mm-dd')," ;
	    $sql .= "  stuffy_date,"          ; $value .= "to_date('$DAT_DATE','yyyy-mm-dd')," ;
	    $sql .= "  etd,"                  ; $value .= "to_date('$ETD_DATE','yyyy-mm-dd')," ;
	    $sql .= "  eta,"                  ; $value .= "to_date('$ETA_DATE','yyyy-mm-dd')," ;
	    $sql .= "  vessel,"               ; $value .= "'$sp_vssl'," ;
	    $sql .= "  crs_remark,"           ; $value .= "'$sp_crrm'," ;
	    $sql .= "  customer_po_line_no,"  ; $value .= "'$PO_LINE_NO'," ;
	    $sql .= "  si_no "                ; $value .= "'$si_no' " ;
	    chop($sql) ;                        chop($value) ;
	    $sql .= ")" ;
	    $sql .= " select $value " ;
	    $sql .= " from item  i " ;
	    $sql .= " where i.item_no = '$sp_item'" ;
	    $sql .= "  and i.origin_code = '$sp_orgn'" ;
	    $sql .= "  and not exists (select * from answer where answer_no='$ans_no')" ;

	    echo $sql."<br/>";
	}
	//echo json_encode(array('successMsg'=>$po_line));
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
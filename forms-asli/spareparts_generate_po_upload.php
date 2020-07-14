<?php
//error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
session_start();
include "../class/excel_reader.php";
include("../connect/conn2.php");

$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
$hasildata = $data->rowcount($sheet_index=0);

$user = $_SESSION['id_wms'];
$success = 0;
$failed = 0;

for($i=2;$i<=$hasildata;$i++){
	$SUPP = trim($data->val($i,1));
	$ITEM = trim($data->val($i,2));
	$QTY = intval($data->val($i,3));
	$PRICE = intval($data->val($i,4));
	$ETA = trim($data->val($i,5));
	$PDAY = trim($data->val($i,6));
	$PDESC = trim($data->val($i,7));

	
	/*if($QTY == 0){*/
		// insert ke po_header
		$f_po  = "company_code," ; 	$v_po  = "".$SUPP.", "	;
		$f_po .= "item_no,"      ; 	$v_po .= "'".$ITEM."',"	;
		$f_po .= "qty,"        	 ; 	$v_po .= "".$QTY.","	;
		$f_po .= "u_price,"      ; 	$v_po .= "".$PRICE.","	;
		$f_po .= "pdays,"   	 ; 	$v_po .= "".$PDAY.","	;
		$f_po .= "pdesc,"        ; 	$v_po .= "'".$PDESC."',";
		$f_po .= "flag_in_po"    ; 	$v_po .= "0"			;
		chop($f_po) ;              	chop($v_po) ;

		$ins = "insert into ztb_temp_po ($f_po) VALUES ($v_po)";
		$data_ins = oci_parse($connect, $ins);
		oci_execute($data_ins);
		
		if($data_ins){
			$success++;	
		}else{
			$failed++;
		}
	/*}*/
}

if  ($success == $hasildata-1) {
  $sql = "BEGIN ZSP_INSERT_PO(); end;";
  $stmt = oci_parse($connect, $sql);
  $res = oci_execute($stmt);
}

echo json_encode("Success = ".$success.", Failed = ".$failed);
//GENERATE PO

// $now2=date('Y-m-d');

// /*PO HEADER*/
// $f_po = "SUPPLIER_CODE,"	;	$v_po = "";
// $f_po .= "PO_NO,"			;	$v_po = "";
// $f_po .= "PO_DATE,"			;	$v_po = "TO_DATE('$now2','yyyy-mm-dd'),"	 ;
// $f_po .= "CURR_CODE,"		;	$v_po = "";
// $f_po .= "EX_RATE,"			;	$v_po = "";
// $f_po .= "TTERM,"			;	$v_po = "";
// $f_po .= "PBY,"				;	$v_po = "";
// $f_po .= "PDAYS,"			;	$v_po = "";
// $f_po .= "PDESC,"			;	$v_po = "";
// $f_po .= "REQ,"				;	$v_po = "";
// $f_po .= "ETA,"				;	$v_po = "null";
// $f_po .= "REMARK1,"			;	$v_po = "";
// $f_po .= "MARKS1,"			;	$v_po = "";
// $f_po .= "REVISE,"			;	$v_po = "null";
// $f_po .= "REASON1,"			;	$v_po = "null";
// $f_po .= "AMT_O,"			;	$v_po = "";
// $f_po .= "AMT_L,"			;	$v_po = "";
// $f_po .= "FDK_PERSON_CODE,"	;	$v_po = "";
// $f_po .= "ATTN,"			;	$v_po = "";
// $f_po .= "PERSON_CODE,"		;	$v_po = "";
// $f_po .= "CC,"				;	$v_po = "";
// $f_po .= "DATE_TYPE,"		;	$v_po = "";
// $f_po .= "UPTO_DATE,"		;	$v_po = "TO_DATE('$now2','yyyy-mm-dd'),"	 ;
// $f_po .= "REG_DATE,"		;	$v_po = "TO_DATE('$now2','yyyy-mm-dd'),"	 ;
// $f_po .= "SECTION_CODE,"	;	$v_po = "";
// $f_po .= "SHIPTO_CODE,"		;	$v_po = "";
// $f_po .= "TRANSPORT,"		;	$v_po = "";
// $f_po .= "DI_OUTPUT_TYPE,"	;	$v_po = "";
// $f_po .= "FREIGHT,"			;	$v_po = "null,";
// $f_po .= "VAT,"				;	$v_po = "null,";
// $f_po .= "BTT,"				;	$v_po = "null,";
// $f_po .= "NBT"				;	$v_po = "null";
// chop($f_po);					chop($v_po);

// $sql ="insert into po_header ($f_vo) select $v_po from dual where not exist(select * from po_header where po_no='$po_no')";

// /*PO DETAILS*/
// $f_d  = "PO_NO, "				;	$v_d  = "	";
// $f_d .= "LINE_NO, "				;	$v_d .= "	";
// $f_d .= "SO_NO, "				;	$v_d .= "null,"	;
// $f_d .= "SO_LINE_NO, "			;	$v_d .= "null,"	;
// $f_d .= "CUSTOMER_PO_NO, "		;	$v_d .= "null,"	;
// $f_d .= "CUSTOMER_PART_NO, "	;	$v_d .= "null,"	;
// $f_d .= "ITEM_NO, "				;	$v_d .= "	";
// $f_d .= "ORIGIN_CODE, "			;	$v_d .= "null,"	;
// $f_d .= "QTY, "					;	$v_d .= "	";
// $f_d .= "UOM_Q, "				;	$v_d .= "	";
// $f_d .= "U_PRICE, "				;	$v_d .= "	";
// $f_d .= "AMT_O, "				;	$v_d .= "	";
// $f_d .= "AMT_L, "				;	$v_d .= "	";
// $f_d .= "ETA, "					;	$v_d .= "	";
// $f_d .= "SCHEDULE, "			;	$v_d .= "	";
// $f_d .= "GR_QTY, "				;	$v_d .= "0,	";
// $f_d .= "SH_QTY, "				;	$v_d .= "0,	";
// $f_d .= "PRET_QTY, "			;	$v_d .= "0,	";
// $f_d .= "BAL_QTY, "				;	$v_d .= "	";
// $f_d .= "DO_COUNT, "			;	$v_d .= "null,";
// $f_d .= "CUSTOMER_CODE, "		;	$v_d .= "null,";
// $f_d .= "UPTO_DATE, "			;	$v_d .= "TO_DATE('$now2','yyyy-mm-dd'),"	 ;
// $f_d .= "REG_DATE, "			;	$v_d .= "TO_DATE('$now2','yyyy-mm-dd'),"	 ;
// $f_d .= "ATTN, "				;	$v_d .= "null,";
// $f_d .= "LINE_DESC, "			;	$v_d .= "	";
// $f_d .= "STOCK_SUBJECT_CODE, "	;	$v_d .= "null,";
// $f_d .= "CARVED_STAMP, "		;	$v_d .= "null,";
// $f_d .= "MPR_NO, "				;	$v_d .= "	";
// $f_d .= "UNIT_PURCHASE_RATE, "	;	$v_d .= "null,";
// $f_d .= "PURCHASE_QUANTITY, "	;	$v_d .= "null,";
// $f_d .= "PURCHASE_UNIT, "		;	$v_d .= "null,";
// $f_d .= "PURCHASE_PRICE, "		;	$v_d .= "null,";
// $f_d .= "UNIT_STOCK_RATE, "		;	$v_d .= "null,";
// $f_d .= "PRF_NO, "				;	$v_d .= "null,";
// $f_d .= "PRF_LINE_NO "			;	$v_d .= "null,";
// chop($f_d);					chop($v_d);

// $sql ="insert into po_details ($f_vo) select $v_po from dual where not exist(select * from po_header where po_no='$po_no')";

?>
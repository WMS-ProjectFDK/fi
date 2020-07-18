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
		$cc_field .= "GR_DATE,"				;	$cc_value .=  "CAST('$gr_date' as varchar(10)),"			;
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
		$cc_field .= "DUE_DATE"				;	$cc_value .=  "CAST('$due_date' as varchar(10))"			;
		chop($cc_field);              			chop($cc_value);

		$ins_cc = "insert into ztb_gr_temp ($cc_field) select $cc_value from dual";
		$data_cc = sqlsrv_query($connect, strtoupper($ins_cc));
		
		if($data_cc === false ) {
			if(($errors = sqlsrv_errors() ) != null) {  
		         foreach( $errors as $error){  
		            $msg .= "message: ".$error[ 'message']."<br/>";  
		         }  
		    }
		}

		if($msg != ''){
			$msg .= " GR-TEMP Process Error : $ins_cc";
			break;
		}
	}

	$sql = "BEGIN ZSP_INSERT_GR(:V_USER); END;";
	$stmt = sqlsrv_query($connect, $sql);
	oci_bind_by_name($stmt, ':V_USER' , trim($user));
	$pesan = oci_error($stmt);
	$msg .= $pesan['message'];

	if($stmt === false ) {
		if(($errors = sqlsrv_errors() ) != null) {  
	         foreach( $errors as $error){  
	            $msg .= "message: ".$error[ 'message']."<br/>";  
	         }  
	    }
	}
	
	if($msg != ''){
		$msg .= " Procedure Insert - GR Process Error : $sql";
		
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>
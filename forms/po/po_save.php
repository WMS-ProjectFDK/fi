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
	$user = $_SESSION['id_wms'];
	$msg = '';
    
  
	foreach($queries as $query){
		
		$v_from = $query->v_from;
		
		$v_to = $query->v_to;
		$po_sts = $query->po_sts;
		$po_supp = $query->po_supp;
		$po_no = $query->po_no;
		$po_pterm = $query->po_pterm;
		$po_line = $query->po_line;
		$po_date = $query->po_date;
		$po_rate = $query->po_rate;
		$po_tterm = $query->po_tterm;
		$po_attn= $query->po_attn;
		$po_di_type = $query->po_di_type;
		$po_trans = $query->po_trans;
		$po_remark = $query->po_remark;
		$po_ship_mark = $query->po_ship_mark;
		$po_item = $query->po_item;
		
		$po_unit = $query->po_unit;
		$po_orign = $query->po_orign;
		$po_price = $query->po_price;
		$po_curr = $query->po_curr;
		$po_curr_item = $query->po_curr_item;
		$po_qty = $query->po_qty;
		$po_eta = $query->po_eta;
		$po_prf = $query->po_prf;
		$po_prf_line = $query->po_prf_line;
		$po_dt_code = $query->po_dt_code;
		$amt_o = $query->amt_o;
		$amt_l = $query->amt_l;
		
		$split_payterm = explode('-', $po_pterm);
		$pday = $split_payterm[0];
		$pdes = $split_payterm[1];


		if ($po_remark == ''){
			$po_remark = "-";
		}else{
			$rmk_s0 = explode('<br>', $po_remark);
			$rmk_f0 = '';
			for($f0=0;$f0<count($rmk_s0);$f0++){
				if($rmk_s0[$f0] != ''  + ! is_null($rmk_s0[$f0])) {
					if($f0 == count($rmk_s0)-1){
						$rmk_f0 .= $rmk_s0[$f0];
					}else{
						$rmk_f0 .= $rmk_s0[$f0]."CHAR(13)+CHAR(10)";
					}
				}
			}
			$rmk_fix0 = $rmk_f0;//str_replace("&amp;", "&", $rmk_f0);
			$po_remark = "$rmk_fix0";
		}

		if ($po_ship_mark == ''){
			$po_ship_mark = "-";
		}else{
			$rmk_s1 = explode('<br>', $po_ship_mark);
			$rmk_f1 = '';
			for($f1=0;$f1<count($rmk_s1);$f1++){
				if($rmk_s1[$f1] != ''  + ! is_null($rmk_s1[$f1])) {
					if($f1 == count($rmk_s1)-1){
						$rmk_f1 .= $rmk_s1[$f1];
					}else{
						$rmk_f1 .= $rmk_s1[$f1].'CHAR(13)+CHAR(10)' ;
					}
				}
			}
			$rmk_fix1 = str_replace("&amp;", "&", $rmk_f1);
			$po_ship_mark = "$rmk_fix1";
		}
		if ($po_sts == 'DETAILS') {
			$sql = "{call ZSP_INSERT_PO(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
			
			
			$params = array(  
				array(  trim($po_no)  , SQLSRV_PARAM_IN),
				array(  $po_supp  , SQLSRV_PARAM_IN),
				array(  $po_date  , SQLSRV_PARAM_IN),
				array(  $po_curr  , SQLSRV_PARAM_IN),
				array(  $po_rate  , SQLSRV_PARAM_IN),
				array(  $po_tterm  , SQLSRV_PARAM_IN),
				array(  $pday  , SQLSRV_PARAM_IN),
				array(  $pdes  , SQLSRV_PARAM_IN),
				array(  $user  , SQLSRV_PARAM_IN),
				array(  $po_ship_mark  , SQLSRV_PARAM_IN),
				array(  $po_remark  , SQLSRV_PARAM_IN),
				array(  $po_attn  , SQLSRV_PARAM_IN),
				array(  $user  , SQLSRV_PARAM_IN),
				array(  str_replace('&amp;', '&', $po_pterm)  , SQLSRV_PARAM_IN),
				array(  $po_item  , SQLSRV_PARAM_IN),
				array(  $po_shipto  , SQLSRV_PARAM_IN),
				array(  $po_trans  , SQLSRV_PARAM_IN),
				array(  $po_di_type  , SQLSRV_PARAM_IN),
				array(  $po_prf  , SQLSRV_PARAM_IN),
				array(  $po_prf_line  , SQLSRV_PARAM_IN),
				array(  $po_orign  , SQLSRV_PARAM_IN),
				array(  $po_qty  , SQLSRV_PARAM_IN),
				array(  $po_unit  , SQLSRV_PARAM_IN),
				array(  $po_price  , SQLSRV_PARAM_IN),
				array(  $amt_o  , SQLSRV_PARAM_IN),
				array(  $amt_l  , SQLSRV_PARAM_IN),
				array(  $po_eta  , SQLSRV_PARAM_IN),
				array(  $po_eta  , SQLSRV_PARAM_IN),
				array(  $po_qty  , SQLSRV_PARAM_IN),
				array(  $po_dt_code  , SQLSRV_PARAM_IN),
				array(  $v_from  , SQLSRV_PARAM_IN),
				array(  $v_to  , SQLSRV_PARAM_IN)
			);

            //print_r ($params);
			$stmt = sqlsrv_query($connect, $sql,$params);
			
			if( $stmt === false )
			{
				//die( printf("$sql") );
				//echo "Error in executing statement 3.\n";
				die( print_r( sqlsrv_errors(), true));
			}
			//die( print_r( sqlsrv_errors(), true));
			
			// $stmt = oci_parse($connect, $sql);

			//  /*Binding Parameters */
			
			// oci_bind_by_name($stmt, ':V_SUPPLIER_CODE', $po_supp);
			// $newDate = date("d-M-Y", strtotime($po_date));
			// oci_bind_by_name($stmt, ':V_PO_DATE', $newDate);
			// //$po_no = 'REZA-1x123xx9';
	
			// oci_bind_by_name($stmt, ':V_CURR_CODE', $po_curr);
			// oci_bind_by_name($stmt, ':V_EX_RATE', $po_rate);
			// oci_bind_by_name($stmt, ':V_TTERM', str_replace('&amp;', '&', $po_tterm));
			// oci_bind_by_name($stmt, ':V_PDAYS', $pday);
			// oci_bind_by_name($stmt, ':V_PDESC', $pdes);
			// oci_bind_by_name($stmt, ':V_REQ', $user);
			// oci_bind_by_name($stmt, ':V_MARKS1', $po_remark);
			// oci_bind_by_name($stmt, ':V_REMARK1', $po_ship_mark);
			// oci_bind_by_name($stmt, ':V_ATTN', $po_attn);
			// oci_bind_by_name($stmt, ':V_PERSON_CODE', $user);
			// oci_bind_by_name($stmt, ':V_PBY', str_replace('&amp;', '&', $po_tterm));
			// oci_bind_by_name($stmt, ':V_ITEM_NO', $po_item);
			// oci_bind_by_name($stmt, ':V_SHIPTO_CODE', $po_shipto);
			// oci_bind_by_name($stmt, ':V_TRANSPORT', $po_trans);
			// oci_bind_by_name($stmt, ':V_DI_OUTPUT_TYPE', $po_di_type);
			// oci_bind_by_name($stmt, ':V_PRF_NO', $po_prf);
			// oci_bind_by_name($stmt, ':V_PRF_LINE_NO', $po_prf_line);
			// oci_bind_by_name($stmt, ':V_ORIGIN_CODE', $po_orign);
			// oci_bind_by_name($stmt, ':V_QTY', $po_qty);
			// oci_bind_by_name($stmt, ':V_UOM_Q', $po_unit);
			// oci_bind_by_name($stmt, ':V_U_PRICE', $po_price);
			// oci_bind_by_name($stmt, ':V_D_AMT_O', $amt_o);
			// oci_bind_by_name($stmt, ':V_D_AMT_L', $amt_l);
			// $newDate1 = date("d-M-Y", strtotime($po_eta));
			// oci_bind_by_name($stmt, ':V_ETA', $newDate1);
			// oci_bind_by_name($stmt, ':V_SCHEDULE', $newDate1);
			// oci_bind_by_name($stmt, ':V_BAL_QTY', $po_qty);
			// oci_bind_by_name($stmt, ':V_CARVED_STAMP', $po_dt_code);
			// oci_bind_by_name($stmt, ':V_FROM', $v_from);
			// oci_bind_by_name($stmt, ':V_TO', $v_to);

			// /* Execute */
			// $res = oci_execute($stmt);
			// $pesan = oci_error($stmt);
			// $msg = $pesan['message'];

			// if($msg != ''){
			// 	$msg .= " PO-Procedure Process Error : $sql";
			// 	break;
			// }
			
			// $sql = "update po_header set remark1= $po_remark, marks1= $po_ship_mark where po_no='$po_no' ";
			// $data = oci_parse($connect, $sql);
			// oci_execute($data);
		}
	
		// $sql2 = "BEGIN ZSP_MRP_MATERIAL_ITEM($po_item); END;";
		// $stmt2 = oci_parse($connect, $sql2);
		// $res2 = oci_execute($stmt2);




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
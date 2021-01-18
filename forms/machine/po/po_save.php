<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../../connect/conn.php");

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
		$po_shipto =$query->po_shipto;
		
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
			$sql = "{call ZSP_SP_INSERT_PO(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
			
			
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

            // print_r ($params);
			$stmt = sqlsrv_query($connect, $sql,$params);
			
			if( $stmt === false )
			{
				die( print_r( sqlsrv_errors(), true));
			}
			
		}
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
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
		$po_no = $query->po_no;
		$po_date = $query->po_date;
		$po_di_type = $query->po_di_type;
		$po_trans = $query->po_trans;
		$po_remark = $query->po_remark;
		$po_ship_mark = $query->po_ship_mark;
		$po_rev = $query->po_rev;
		$po_rev_res = $query->po_rev_res;
		$po_rate = $query->po_rate;
		$po_line_new = $query->po_line_new;
		$po_item = $query->po_item;
		$po_line = $query->po_line;
		$po_unit = $query->po_unit;
		$po_orign = $query->po_orign;
		$po_price = $query->po_price;
		$po_curr = $query->po_curr;
		$po_curr_item = $query->po_curr_item;
		$po_qty = $query->po_qty;
		$po_gr_qty = $query->po_gr_qty;
		$po_bal_qty = $query->po_bal_qty;
		$po_eta = $query->po_eta;
		$po_prf = $query->po_prf;
		$po_prf_line = $query->po_prf_line;
		$po_dt_code = $query->po_dt_code;
		$amt_o = $query->amt_o;
		$amt_l = $query->amt_l;

		$now=date('Y-m-d H:i:s');
		
		$now2=date('Y-m-d');

		if($po_prf == '' and $po_prf_line == ''){
			$prf='-';
			$prf_line='0';
		}else{
			$prf=$po_prf;
			$prf_line=$po_prf_line;
		}

		$bal = intval($po_qty - $po_gr_qty);

		$cek_jum = "select count(u_price) as jum from po_details
					where po_no = '$po_no' AND line_no = $po_line
					AND u_price != '$po_price'
				";
		$data_jum = sqlsrv_query($connect, $cek_jum);
		$dt_jum = sqlsrv_fetch_object($data_jum);
		//echo $cek_jum."<br/>";

		if($dt_jum->jum >= 1){
			$price_ubah = "UPD";
		}else{
			$price_ubah = "NON";
		}

		/*update : 13-03-2019 id:wnx*/
		if ($po_line == 'NEW'){
			$q_max = "select cast(max(line_no) as number)+1 as line_no from po_details where po_no='$po_no'";
		}else{
			$q_max = "select cast(max(line_no) as number)+2 as line_no from po_details where po_no='$po_no'";
		}
		
		$data_max = sqlsrv_query($connect, strtoupper($q_max));
		$rowMax = sqlsrv_fetch_object($data_max);

		$po_line_new = $rowMax->LINE_NO;
		/*------------FINISH-------------------*/

		if ($po_remark == ''){
			$po_remark = "'-'";
		}else{
			$rmk_s0 = explode('<br>', $po_remark);
			$rmk_f0 = '';
			for($f0=0;$f0<count($rmk_s0);$f0++){
				if($rmk_s0[$f0] != ''  || ! is_null($rmk_s0[$f0])) {
					if($f0 == count($rmk_s0)-1){
						$rmk_f0 .= $rmk_s0[$f0];
					}else{
						$rmk_f0 .= $rmk_s0[$f0]." char(10)";
					}
				}
			}
			$rmk_fix0 = str_replace("&amp;", "&", $rmk_f0);
			$po_remark = "$rmk_fix0";
		}

		if ($po_ship_mark == ''){
			$po_ship_mark = "'-'";
		}else{
			$rmk_s1 = explode('<br>', $po_ship_mark);
			$rmk_f1 = '';
			for($f1=0;$f1<count($rmk_s1);$f1++){
				if($rmk_s1[$f1] != ''  || ! is_null($rmk_s1[$f1])) {
					if($f1 == count($rmk_s1)-1){
						$rmk_f1 .= $rmk_s1[$f1];
					}else{
						$rmk_f1 .= $rmk_s1[$f1]." char(10)";
					}
				}
			}
			$rmk_fix1 = str_replace("&amp;", "&", $rmk_f1);
			$po_ship_mark = "$rmk_fix1";
		}

		// $sql = "BEGIN ZSP_Insert_PO_1(:V_PO_NO,:V_SUPPLIER_CODE,:V_PO_DATE,:V_CURR_CODE,:V_EX_RATE,:V_TTERM,:V_PDAYS,:V_PDESC,:V_REQ,:V_REMARK1,:V_MARKS1,:V_ATTN,:V_PERSON_CODE,:V_ITEM_NO,:V_PBY,:V_SHIPTO_CODE,:V_TRANSPORT,:V_DI_OUTPUT_TYPE,:V_PRF_NO,:V_PRF_LINE_NO,:V_ORIGIN_CODE,:V_QTY,:V_UOM_Q,:V_U_PRICE,:V_D_AMT_O,:V_D_AMT_L,:V_ETA,:V_SCHEDULE,:V_BAL_QTY,:V_CARVED_STAMP,:V_FROM,:V_TO); end;";
			$sql = "{call ZSP_UPDATE_PO(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
			
			$params = array(
				array($v_from, SQLSRV_PARAM_IN),
				array($v_to, SQLSRV_PARAM_IN),
				array(trim($po_no), SQLSRV_PARAM_IN),
				array($po_date, SQLSRV_PARAM_IN),
				array($po_di_type, SQLSRV_PARAM_IN),
				array($po_trans, SQLSRV_PARAM_IN),
				array($po_remark, SQLSRV_PARAM_IN),
				array($po_ship_mark, SQLSRV_PARAM_IN),
				array($po_rev, SQLSRV_PARAM_IN),
				array($po_rev_res, SQLSRV_PARAM_IN),
				array($po_rate, SQLSRV_PARAM_IN),
				array($po_line_new, SQLSRV_PARAM_IN),
				array($po_item, SQLSRV_PARAM_IN),
				array($po_line, SQLSRV_PARAM_IN),
				array($po_unit, SQLSRV_PARAM_IN),
				array($po_orign, SQLSRV_PARAM_IN),
				array($po_price, SQLSRV_PARAM_IN),
				array($po_curr, SQLSRV_PARAM_IN),
				array($po_curr_item, SQLSRV_PARAM_IN),
				array($po_qty, SQLSRV_PARAM_IN),
				array($po_gr_qty, SQLSRV_PARAM_IN),
				array($bal, SQLSRV_PARAM_IN),
				array($po_eta, SQLSRV_PARAM_IN),
				array($po_prf, SQLSRV_PARAM_IN),
				array($po_prf_line, SQLSRV_PARAM_IN),
				array($po_dt_code, SQLSRV_PARAM_IN),
				array($amt_o, SQLSRV_PARAM_IN),
				array($amt_l, SQLSRV_PARAM_IN),
				array($price_ubah, SQLSRV_PARAM_IN)
				
			);
		/* Execute */
		if ($po_sts == "DETAILS") {
			
			$stmt = sqlsrv_query($connect, $sql, $params);
			if( $stmt === false )
			{
				echo "Error in executing statement 3.\n";
				die( print_r( sqlsrv_errors(), true));
			}

			
		}

		// $sql = "update po_header set remark1 = $po_remark, marks1 = $po_ship_mark where po_no='$po_no' ";
		// $data = sqlsrv_query($connect, $sql);
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
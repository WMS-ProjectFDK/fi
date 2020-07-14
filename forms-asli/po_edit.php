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

		$cek_jum = "select count(*) as jum from
				(select to_char(u_price,'99,999,990.0000')from po_details
					where po_no = '$po_no' AND line_no = $po_line
					AND u_price != '$po_price'
				)";
		$data_jum = oci_parse($connect, $cek_jum);
		oci_execute($data_jum);
		$dt_jum = oci_fetch_object($data_jum);
		//echo $cek_jum."<br/>";

		if($dt_jum->JUM >= 1){
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
		
		$data_max = oci_parse($connect, $q_max);
		oci_execute($data_max);
		$rowMax = oci_fetch_object($data_max);

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
						$rmk_f0 .= "'".$rmk_s0[$f0]."'";
					}else{
						$rmk_f0 .= "'".$rmk_s0[$f0]."' || chr(13) || chr(10) || ";
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
						$rmk_f1 .= "'".$rmk_s1[$f1]."'";
					}else{
						$rmk_f1 .= "'".$rmk_s1[$f1]."' || chr(13) || chr(10) || ";
					}
				}
			}
			$rmk_fix1 = str_replace("&amp;", "&", $rmk_f1);
			$po_ship_mark = "$rmk_fix1";
		}

		$sql = "BEGIN ZSP_UPDATE_PO(:V_FROM,:V_TO,:V_PO_NO,:V_PO_DATE,:V_DI_OUTPUT_TYPE,:V_TRANSPORT,:V_REMARK1,:V_MARKS1,:V_PO_REV,:V_PO_REV_RES,:V_EX_RATE,:V_PO_LINE_NEW,:V_ITEM_NO,:V_PO_LINE,:V_UOM_Q,:V_ORIGIN_CODE,:V_U_PRICE,:V_PO_CURR,:V_PO_CURR_ITEM,:V_QTY,:V_GR_QTY,:V_BAL_QTY,:V_ETA,:V_PRF_NO,:V_PRF_LINE_NO,:V_PO_DT_CODE,:V_D_AMT_O,:V_D_AMT_L,:V_PRC_UBAH); end;";
		
		$stmt = oci_parse($connect, $sql);

		 /*Binding Parameters */
		oci_bind_by_name($stmt, ':V_FROM' , $v_from);
		oci_bind_by_name($stmt, ':V_TO' , $v_to); 
		oci_bind_by_name($stmt, ':V_PO_NO' , $po_no);
		$newDate1 = date("d-M-Y", strtotime($po_date));
		oci_bind_by_name($stmt, ':V_PO_DATE', $newDate1);
		oci_bind_by_name($stmt, ':V_DI_OUTPUT_TYPE', $po_di_type);
		oci_bind_by_name($stmt, ':V_TRANSPORT', $po_trans);
		oci_bind_by_name($stmt, ':V_REMARK1', $po_remark );
		oci_bind_by_name($stmt, ':V_MARKS1', $po_ship_mark);
		oci_bind_by_name($stmt, ':V_PO_REV', $po_rev);
		oci_bind_by_name($stmt, ':V_PO_REV_RES', $po_rev_res);
		oci_bind_by_name($stmt, ':V_EX_RATE', $po_rate);
		oci_bind_by_name($stmt, ':V_PO_LINE_NEW', $po_line_new);
		oci_bind_by_name($stmt, ':V_ITEM_NO', $po_item);
		oci_bind_by_name($stmt, ':V_PO_LINE', $po_line);
		oci_bind_by_name($stmt, ':V_UOM_Q', $po_unit);
		oci_bind_by_name($stmt, ':V_ORIGIN_CODE', $po_orign);
		oci_bind_by_name($stmt, ':V_U_PRICE', $po_price);
		oci_bind_by_name($stmt, ':V_PO_CURR', $po_curr);
		oci_bind_by_name($stmt, ':V_PO_CURR_ITEM', $po_curr_item);
		oci_bind_by_name($stmt, ':V_QTY', $po_qty);
		oci_bind_by_name($stmt, ':V_GR_QTY', $po_gr_qty);
		oci_bind_by_name($stmt, ':V_BAL_QTY', $bal);
		$newDate = date("d-M-Y", strtotime($po_eta));
		oci_bind_by_name($stmt, ':V_ETA', $newDate);
		oci_bind_by_name($stmt, ':V_PRF_NO', $po_prf);
		oci_bind_by_name($stmt, ':V_PRF_LINE_NO', $po_prf_line);
		oci_bind_by_name($stmt, ':V_PO_DT_CODE', $po_dt_code);
		oci_bind_by_name($stmt, ':V_D_AMT_O', $amt_o);
		oci_bind_by_name($stmt, ':V_D_AMT_L', $amt_l);
		oci_bind_by_name($stmt, ':V_PRC_UBAH', $price_ubah);

		/* Execute */
		if ($po_sts == "DETAILS") {
			$res = oci_execute($stmt);
			print_r($res, true);
			$pesan = oci_error($stmt);
			$msg .= $pesan['message'];

			if($msg != ''){
				$msg .= " PO-Procedure Update Process Error : $sql";
				break;
			}
		}

		$sql = "update po_header set remark1 = $po_remark, marks1 = $po_ship_mark where po_no='$po_no' ";
		$data = oci_parse($connect, $sql);
		oci_execute($data);
	}
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}


//session_start();
//if (isset($_SESSION['id_wms'])){
//	include("../connect/conn.php");
//	$v_from = htmlspecialchars($_REQUEST['v_from']);
//	$v_to = htmlspecialchars($_REQUEST['v_to']);
//	$po_sts = htmlspecialchars($_REQUEST['po_sts']);
//	$po_no = htmlspecialchars($_REQUEST['po_no']);
//	$po_date = htmlspecialchars($_REQUEST['po_date']);
//	$po_di_type = htmlspecialchars($_REQUEST['po_di_type']);
//	$po_trans = htmlspecialchars($_REQUEST['po_trans']);
//	$po_remark = htmlspecialchars($_REQUEST['po_remark']);
//	$po_ship_mark = htmlspecialchars($_REQUEST['po_ship_mark']);
//	$po_rev = htmlspecialchars($_REQUEST['po_rev']);
//	$po_rev_res = htmlspecialchars($_REQUEST['po_rev_res']);
//	$po_rate = htmlspecialchars($_REQUEST['po_rate']);
//	$po_line_new = htmlspecialchars($_REQUEST['po_line_new']);
//	$po_item = htmlspecialchars($_REQUEST['po_item']);
//	$po_line = htmlspecialchars($_REQUEST['po_line']);
//	$po_unit = htmlspecialchars($_REQUEST['po_unit']);
//	$po_orign = htmlspecialchars($_REQUEST['po_orign']);
//	$po_price = htmlspecialchars($_REQUEST['po_price']);
//	$po_curr = htmlspecialchars($_REQUEST['po_curr']);
//	$po_curr_item = htmlspecialchars($_REQUEST['po_curr_item']);
//	$po_qty = htmlspecialchars($_REQUEST['po_qty']);
//	$po_gr_qty = htmlspecialchars($_REQUEST['po_gr_qty']);
//	$po_bal_qty = htmlspecialchars($_REQUEST['po_bal_qty']);
//	$po_eta = htmlspecialchars($_REQUEST['po_eta']);
//	$po_prf = htmlspecialchars($_REQUEST['po_prf']);
//	$po_prf_line = htmlspecialchars($_REQUEST['po_prf_line']);
//	$po_dt_code = htmlspecialchars($_REQUEST['po_dt_code']);
//	$amt_o = htmlspecialchars($_REQUEST['amt_o']);
//	$amt_l = htmlspecialchars($_REQUEST['amt_l']);
//
//	$now=date('Y-m-d H:i:s');
//	$user = $_SESSION['id_wms'];
//	$now2=date('Y-m-d');
//
//	if($po_prf == '' and $po_prf_line == ''){
//		$prf='-';
//		$prf_line='0';
//	}else{
//		$prf=$po_prf;
//		$prf_line=$po_prf_line;
//	}
//
//	$bal = intval($po_qty - $po_gr_qty);
//
//	$cek_jum = "select count(*) as jum from
//			(select to_char(u_price,'99,999,990.0000')from po_details
//				where po_no = '$po_no' AND line_no = $po_line
//				AND u_price != '$po_price'
//			)";
//	$data_jum = oci_parse($connect, $cek_jum);
//	oci_execute($data_jum);
//	$dt_jum = oci_fetch_object($data_jum);
//	echo $cek_jum."<br/>";
//
//	if($dt_jum->JUM >= 1){
//		$price_ubah = "UPD";
//	}else{
//		$price_ubah = "NON";
//	}
//
//	//echo $price_ubah."<br/>";
//
//	$sql = "BEGIN ZSP_UPDATE_PO(:V_FROM,:V_TO,:V_PO_NO,:V_PO_DATE,:V_DI_OUTPUT_TYPE,:V_TRANSPORT,:V_REMARK1,:V_MARKS1,:V_PO_REV,:V_PO_REV_RES,:V_EX_RATE,:V//_PO_LINE_NEW,:V_ITEM_NO,:V_PO_LINE,:V_UOM_Q,:V_ORIGIN_CODE,:V_U_PRICE,:V_PO_CURR,:V_PO_CURR_ITEM,:V_QTY,:V_GR_QTY,:V_B//AL_QTY,:V_ETA,:V_PRF_NO,:V_PRF_LINE_NO,:V_PO_DT_CODE,:V_D_AMT_O,:V_D_AMT_L,:V_PRC_UBAH); end;";
//	
//
//	$stmt = oci_parse($connect, $sql);
//
//	 /*Binding Parameters */
//	oci_bind_by_name($stmt, ':V_FROM' , $v_from);
//	oci_bind_by_name($stmt, ':V_TO' , $v_to); 
//	oci_bind_by_name($stmt, ':V_PO_NO' , $po_no);
//	$newDate1 = date("d-M-Y", strtotime($po_date));
//	oci_bind_by_name($stmt, ':V_PO_DATE', $newDate1);
//	oci_bind_by_name($stmt, ':V_DI_OUTPUT_TYPE', $po_di_type);
//	oci_bind_by_name($stmt, ':V_TRANSPORT', $po_trans);
//	oci_bind_by_name($stmt, ':V_REMARK1', $po_remark );
//	oci_bind_by_name($stmt, ':V_MARKS1', $po_ship_mark);
//	oci_bind_by_name($stmt, ':V_PO_REV', $po_rev);
//	oci_bind_by_name($stmt, ':V_PO_REV_RES', $po_rev_res);
//	oci_bind_by_name($stmt, ':V_EX_RATE', $po_rate);
//	oci_bind_by_name($stmt, ':V_PO_LINE_NEW', $po_line_new);
//	oci_bind_by_name($stmt, ':V_ITEM_NO', $po_item);
//	oci_bind_by_name($stmt, ':V_PO_LINE', $po_line);
//	oci_bind_by_name($stmt, ':V_UOM_Q', $po_unit);
//	oci_bind_by_name($stmt, ':V_ORIGIN_CODE', $po_orign);
//	oci_bind_by_name($stmt, ':V_U_PRICE', $po_price);
//	oci_bind_by_name($stmt, ':V_PO_CURR', $po_curr);
//	oci_bind_by_name($stmt, ':V_PO_CURR_ITEM', $po_curr_item);
//	oci_bind_by_name($stmt, ':V_QTY', $po_qty);
//	oci_bind_by_name($stmt, ':V_GR_QTY', $po_gr_qty);
//	oci_bind_by_name($stmt, ':V_BAL_QTY', $bal);
//	$newDate = date("d-M-Y", strtotime($po_eta));
//	oci_bind_by_name($stmt, ':V_ETA', $newDate);
//	oci_bind_by_name($stmt, ':V_PRF_NO', $po_prf);
//	oci_bind_by_name($stmt, ':V_PRF_LINE_NO', $po_prf_line);
//	oci_bind_by_name($stmt, ':V_PO_DT_CODE', $po_dt_code);
//	oci_bind_by_name($stmt, ':V_D_AMT_O', $amt_o);
//	oci_bind_by_name($stmt, ':V_D_AMT_L', $amt_l);
//	oci_bind_by_name($stmt, ':V_PRC_UBAH', $price_ubah);
//
//	/* Execute */
//	if ($po_sts == "DETAILS") {
//		$res = oci_execute($stmt);
//		print_r($res, true);
//		echo json_encode(array('successMsg'=>$po_line));
//	}
//}else{
//	echo json_encode(array('errorMsg'=>'Session Expired'));
//}
?>
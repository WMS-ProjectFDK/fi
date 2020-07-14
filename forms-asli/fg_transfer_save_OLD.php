<?php
session_start();
include("../connect/conn.php");
if (isset($_SESSION['id_wms'])){
	if($varConn == "Y"){
		$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
		$dt = json_decode(json_encode($data));
		$str = preg_replace('/\\\\\"/',"\"", $dt);
		$queries = json_decode($str);
		$user = $_SESSION['id_wms'];
		$msg = '';

		foreach($queries as $query){
			$fgTrans_wono = $query->fgTrans_wono;
			$fgTrans_qtyA = $query->fgTrans_qtyA;
			$fgTrans_woTm = $query->fgTrans_woTm;
			$fgTrans_qtyB = $query->fgTrans_qtyB;
		}

		$user = $_SESSION['id_wms'];

		$k = "select TO_CHAR(SYSDATE,'YYYYMMDD') || '-' || 
			nvl(lpad(cast(cast(max(substr(trans_code,10,4)) as integer)+1 as varchar(4)),4,0),'0001')
			as kode
			from ztb_fg_transfer
			where substr(trans_code,0,8) = TO_CHAR(SYSDATE,'YYYYMMDD')";
		$data_k = oci_parse($connect, $k);
		oci_execute($data_k);
		$dt_k = oci_fetch_array($data_k);

		$field .= "TRANS_CODE,"		;	$value .= "'$dt_k[0]',";
		$field .= "WO_NO,"			;	$value .= "'$fgTrans_wono',";
		$field .= "QTY,"			;	$value .= "$fgTrans_qtyA,";
		$field .= "WO_NO_TEMP,"		;	$value .= "'$fgTrans_woTm',";
		$field .= "QTY_TEMP,"		;	$value .= "$fgTrans_qtyB,";
		$field .= "TRANS_DATE"		;	$value .= "SYSDATE";
		chop($field);              				chop($value);
		$ins_cc = "insert into ztb_fg_transfer ($field) select $value from dual";
		$data_cc = oci_parse($connect, $ins_cc);
		oci_execute($data_cc);

		/*CEK ke PI*/
		$cek_PI = "select slip_no, slip_quantity from ztb_production_income 
			where wo_no = '$fgTrans_woTm' AND ANSWER_NO IS NULL
			order by slip_no asc";
		$data_cekPI = oci_parse($connect, $cek_PI);
		oci_execute($data_cekPI);
		$pesan = oci_error($data_cekPI);
		$msg = $pesan['message'];

		if($msg != ''){
			$msg .= " select query error : $cek_PI";
			break;
		}

		while ($dt_cekPI = oci_fetch_object($data_cekPI)){
			if($fgTrans_qtyA <> 0){
				if ($fgTrans_qtyA - $dt_cekPI->SLIP_QUANTITY > 0){
					//update PI qty = fgTrans_qtyA dan answer_no=''
					$upd = "update ztb_production_income set 
						OPERATION_DATE = SYSDATE,
						ACCOUNTING_MONTH = TO_CHAR(SYSDATE,'YYYYMM'),
						slip_date = sysdate,
						wo_no='$fgTrans_wono',
						ANSWER_NO = '1'
						where slip_no='$dt_cekPI->SLIP_NO' ";
					$data_upd = oci_parse($connect, $upd);
					oci_execute($data_upd);
					$pesan = oci_error($data_upd);
					$msg = $pesan['message'];

					if($msg != ''){
						$msg .= " update query error : $upd";
						break;
					}

					//$fgTrans_qtyA -= $dt_cekPI->SLIP_QUANTITY;
					$fgTrans_qtyA -= $dt_cekPI->SLIP_QUANTITY;
				}elseif ($fgTrans_qtyA - $dt_cekPI->SLIP_QUANTITY == 0){
					//update PI qty = fgTrans_qtyA dan answer_no=''
					$upd = "update ztb_production_income set 
						OPERATION_DATE = SYSDATE,
						ACCOUNTING_MONTH = TO_CHAR(SYSDATE,'YYYYMM'),
						slip_date = sysdate,
						wo_no='$fgTrans_wono',
						ANSWER_NO = '1'
						where slip_no='$dt_cekPI->SLIP_NO' ";
					$data_upd = oci_parse($connect, $upd);
					oci_execute($data_upd);
					$pesan = oci_error($data_upd);
					$msg = $pesan['message'];

					if($msg != ''){
						$msg .= " update query error : $upd";
						break;
					}

					$fgTrans_qtyA = 0;
				}else{
					$q = "insert into ztb_production_income_temp
						select * from ztb_production_income where slip_no='$dt_cekPI->SLIP_NO' ";
					$dataQ = oci_parse($connect, $q);
					oci_execute($dataQ);

					//update PI qty = fgTrans_qtyA dan answer_no=''
					$upd = "update ztb_production_income set 
						OPERATION_DATE = SYSDATE,
						ACCOUNTING_MONTH = TO_CHAR(SYSDATE,'YYYYMM'),
						slip_date = sysdate,
						wo_no='$fgTrans_wono',
						slip_quantity=$fgTrans_qtyA,
						slip_amount=slip_price*$fgTrans_qtyA,
						standard_amount=slip_price*$fgTrans_qtyA,
						pc_quantity=$fgTrans_qtyA,
						ANSWER_NO = '1'
						where slip_no='$dt_cekPI->SLIP_NO' ";
					$data_upd = oci_parse($connect, $upd);
					oci_execute($data_upd);
					$pesan = oci_error($data_upd);
					$msg = $pesan['message'];

					if($msg != ''){
						$msg .= " update query error : $upd";
						break;
					}

					$q_sisa = ($fgTrans_qtyA - $dt_cekPI->SLIP_QUANTITY)*-1;

					//insert pi qty = ($dt_cekPI->SLIP_QUANTITY-$fgTrans_qtyA) && answer_no=1
					$ins = "insert into ztb_production_income 
						select 
						OPERATION_DATE, 
						SECTION_CODE, 
						ITEM_NO,
						ITEM_CODE,
						ITEM_NAME,
						ITEM_DESCRIPTION,
						STOCK_SUBJECT_CODE,
						ACCOUNTING_MONTH,
						SLIP_DATE,
						SLIP_TYPE,
						SLIP_NO,
						$q_sisa, 
						SLIP_PRICE,
						$q_sisa*SLIP_PRICE,
						CURR_CODE,
						STANDARD_PRICE,
						$q_sisa*SLIP_PRICE,	
						SUPPLIERS_PRICE,
						COMPANY_CODE,
						ORDER_NUMBER,
						LINE_NO,
						COST_PROCESS_CODE,
						COST_SUBJECT_CODE,
						PRODUCT_LOT_NUMBER,
						PURCHASE_QUANTITY,
						PURCHASE_PRICE,
						PURCHASE_AMOUNT,
						PURCHASE_UNIT,
						UNIT_STOCK,
						EX_RATE,
						ANSWER_NO, 
						REMARK1,
						REMARK2,
						APPROVAL_DATE,
						APPROVAL_PERSON_CODE,
						DELETE_TYPE,
						CUSTOMER_PO_NO,
						WO_NO,
						DATE_CODE,
						REMARK3,
						BOX_QUANTITY,
						$q_sisa
						from ztb_production_income_temp ";
					$data_ins = oci_parse($connect, $ins);
					oci_execute($data_ins);

					$pesan = oci_error($data_ins);
					$msg = $pesan['message'];

					if($msg != ''){
						$msg .= " insert query error : $ins";
						break;
					}

					$del = "delete from ztb_production_income_temp";
					$data_del = oci_parse($connect, $del);
					oci_execute($data_del);

					$fgTrans_qtyA = 0;
				}
			}
		}
	}else{
		$msg .= 'Connection Failed';	
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
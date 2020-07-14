<?php
session_start();
error_reporting(0);
header("Content-type: application/json");
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");
	$user = $_SESSION['id_wms'];
	$approve_slip = htmlspecialchars($_REQUEST['approve_slip']);
	$data = explode(',', $approve_slip);
	$success = 0;		$failed=0;		$arrData = array();
	for($i=0;$i<count($data);$i++){
		$sql = "BEGIN ZSP_Insert_MT(:V_SLIP_NO,:V_PERSON_CODE); end;";
		$stmt = oci_parse($connect, $sql);
		 /*Binding Parameters */
		oci_bind_by_name($stmt, ':V_SLIP_NO', $data[$i]);
		oci_bind_by_name($stmt, ':V_PERSON_CODE', $user);
		 /* Execute */
		$res = oci_execute($stmt);
		$pesan = oci_error($stmt);
		$msg = $pesan['message'];

		if($msg == ''){
			$success++;	
		}else{
			$failed++;
			$msg .= "Approve Error<br/>$msg";
			break;
		}	

	}

	if($msg == ''){
		$arrData[0] = array("kode"=>"success");
	}else{
		$arrData[0] = array("kode"=>$msg);
	}	
	

















	/*if($approve_ck=="TRUE"){
		//update mte_header
		$upd = "update ztb_wh_mte_header set approval_date = TO_DATE('$now2','yyyy-mm-dd'), approval_person_code='$user'
			where slip_no='$approve_slip'";
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);

		if($data_upd){
			$cek = "select a.slip_no, c.slip_date, c.slip_type, a.item_no, b.item, b.description, b.stock_subject_code, a.qty, c.company_code, 
				b.standard_price,(a.qty*b.standard_price) as amount, b.curr_code, b.cost_subject_code, b.cost_process_code, b.unit_stock, a.wo_no
				from ztb_wh_mte_details a
				inner join item b on a.item_no = b.item_no
				inner join ztb_wh_mte_header c on a.slip_no=c.slip_no
				where a.slip_no='$approve_slip'
				order by a.line_no asc";

			$data_cek = oci_parse($connect, $cek);
			oci_execute($data_cek);

			while($row = oci_fetch_object($data_cek)){
				// insert ke transaction
				$ins = "insert into ztb_wh_transaction (operation_date,
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
													 company_code,
													 cost_process_code,
													 cost_subject_code,
													 unit_stock,
													 wo_no)
										values(TO_DATE('$now2','yyyy-mm-dd'),
											   100,
											   ".$row->ITEM_NO.",
											   '".$row->ITEM."',
											   '".$row->DESCRIPTION."',
											   '".$row->STOCK_SUBJECT_CODE."',
											   $month_acc,
											   TO_DATE('$approve_date','yyyy-mm-dd'),
											   '".$row->SLIP_TYPE."',
											   '".$row->SLIP_NO."',
											   ".$row->QTY.",
											   ".$row->STANDARD_PRICE.",
											   ".$row->AMOUNT.",
											   ".$row->CURR_CODE.",
											   ".$row->STANDARD_PRICE.",
											   ".$row->AMOUNT.",
											   ".$row->COMPANY_CODE.",
											   '".$row->COST_PROCESS_CODE."',
											   '".$row->COST_SUBJECT_CODE."',
											   '".$row->UNIT_STOCK."',
											   '".$row->WO_NO."'
											   )";
				$data_ins = oci_parse($connect, $ins);
				oci_execute($data_ins);

				if($data_ins){

					//cek item di inventory
					$cek_item_inv = "select count(*) jumitem from ztb_wh_whinventory where item_no=".$row->ITEM_NO." and this_month=$month_acc";
					$data_item_inv = oci_parse($connect, $cek_item_inv);
					oci_execute($data_item_inv);
					$dt_item_inv = oci_fetch_object($data_item_inv);

					if(($dt_item_inv->JUMITEM)==0){
						//insert whinvwntory
						$ins_item = "insert into ztb_wh_whinventory (operation_date,
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
														   ".$row->ITEM_NO.",
														   $month_acc,
														   0,
														   0,
														   ".$row->QTY.",
														   ".$row->QTY.",
														   0,
														   ".$row->QTY.",
														   0,
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
						$cek_inv = "select coalesce(this_month,0) as month, issue1, this_inventory, last_month, issue2, last_inventory from ztb_wh_whinventory 
							where item_no = ".$row->ITEM_NO." and this_month = $month_acc ";
						$data_inv = oci_parse($connect, $cek_inv);
						oci_execute($data_inv);
						$dt_inv = oci_fetch_object($data_inv);

						if(($dt_inv->MONTH)==$month_acc){
							$issue_new = floatval($dt_inv->ISSUE1) + $row->QTY;
							$inventory_new = floatval($dt_inv->THIS_INVENTORY) - floatval($row->QTY);
							$upd_inv = "update ztb_wh_whinventory set issue1=$issue_new  , this_inventory=$inventory_new 
								where item_no=".$row->ITEM_NO." and this_month=$month_acc ";
							$data_upd_inv = oci_parse($connect, $upd_inv);
							oci_execute($data_upd_inv);
						}else{		//if(($dt_inv->MONTH) == intval($now_month)-1)
							$issue2_new = floatval($dt_inv->issue2) + floatval($row->QTY);
							$inventory_new = floatval($dt_inv->THIS_INVENTORY) - floatval($row->QTY);
							$inventory2_new = floatval($dt_inv->LAST_INVENTORY) - floatval($row->QTY);
							$upd_inv = "update ztb_wh_whinventory set issue2 = $issue2_new  , last_inventory = $inventory2_new, this_inventory = $inventory_new 
								where item_no=".$row->ITEM_NO." and this_month=$month_acc ";
							$data_upd_inv = oci_parse($connect, $upd_inv);
							oci_execute($data_upd_inv);
						}
					}
				}
			}
			echo json_encode("Success");
		}else{
			echo json_encode(array('errorMsg'=>'SQL Error'));
		}
	}*/
}else{
	$arrData = array("kode"=>"Session Expired");
}
echo json_encode($arrData);
?>
<?php
include("conn.php");

if(intval(date('H')) < 7){
	$hr = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
}else{
	$hr = date('Y-m-d');
}

//echo $hr."<br/>";

$cek = "select distinct id from ztb_wh_kanban_trans where flag=0 and slip_no is null";
$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);
while($dt_cek = oci_fetch_object($data_cek)){
	/* KODE MT */
	$k = "KANBAN-".date('y')."-";
	$kd = "select '$k'||coalesce(lpad(cast(cast(max(substr(slip_no,-5)) as integer)+1 as varchar(5)),5,'0'),'00001') as kode
		from mte_header where substr(slip_no, 0, 10)='$k'";
	$data_kd = oci_parse($connect, $kd);
	oci_execute($data_kd);
	$row_kd = oci_fetch_object($data_kd);
	//echo $row_kd->KODE;

	/* INSERT MTE_HEADER */
	$ins1 = "insert into mte_header(slip_no,
								   slip_date,
								   company_code,
								   slip_type,
								   display_type,
								   section_code)
							values('".$row_kd->KODE."',
							   TO_DATE('$hr','yyyy-mm-dd'),
							   100001,
							   '21',
							   'A',
							   100)";
	$data_ins1 = oci_parse($connect, $ins1);
	oci_execute($data_ins1);

	if($data_ins1){
		$cek_brg = "select a.*, b.uom_q, d.wo_no, b.item, b.description, b.STOCK_SUBJECT_CODE, b.standard_price,
			(a.qty*b.standard_price) as amount, b.curr_code, b.cost_subject_code, b.cost_process_code, b.unit_stock from ztb_wh_kanban_trans a 
			inner join item b on a.item_no=b.item_no
			inner join unit c on b.uom_q = c.unit_code
			inner join ztb_m_plan d on a.id = d.id
			where a.id=".$dt_cek->ID." and a.flag=0 and a.slip_no is null";

		$data_cek_brg = oci_parse($connect, $cek_brg);
		oci_execute($data_cek_brg);
		$line=1;
		while($dt_brg = oci_fetch_object($data_cek_brg)){
			$ins2 = "insert into mte_details (slip_no,
													 line_no,
													 item_no,
													 qty,
													 uom_q,
													 cost_process_code,
													 reg_date,
													 upto_date,
													 wo_no)
											values('".$row_kd->KODE."',
													".$line.",
													".$dt_brg->ITEM_NO.",
													".$dt_brg->QTY.",
													'".$dt_brg->UOM_Q."',
													".$dt_brg->COST_PROCESS_CODE.",
													TO_DATE('$hr','yyyy-mm-dd'),
													TO_DATE('$hr','yyyy-mm-dd'),
													'".$dt_brg->WO_NO."'
													)";
			$data_ins2 = oci_parse($connect, $ins2);
			oci_execute($data_ins2);

			$split = split('-', $hr);
			$now_month = intval($split[0].$split[1]);
			$month_acc = intval($split[0].$split[1]);

			if($data_ins2){
				$upd = "update ztb_wh_kanban_trans set flag=1, slip_no='".$row_kd->KODE."' where id=".$dt_cek->ID." and item_no='".$dt_brg->ITEM_NO."'";
				$data_upd = oci_parse($connect, $upd);
				oci_execute($data_upd);
				//echo "SYNCRONIZED..<br/>";
				//echo $upd."<br/>";
			}
			
			// insert ke transaction
			/*$ins = "insert into ztb_wh_transaction (operation_date,
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
									values(TO_DATE('$hr','yyyy-mm-dd'),
										   100,
										   ".$dt_brg->ITEM_NO.",
										   '".$dt_brg->ITEM."',
										   '".$dt_brg->DESCRIPTION."',
										   '".$dt_brg->STOCK_SUBJECT_CODE."',
										   $now_month,
										   TO_DATE('$approve_date','yyyy-mm-dd'),
										   '21',
										   '".$row_kd->KODE."',
										   ".$dt_brg->QTY.",
										   ".$dt_brg->STANDARD_PRICE.",
										   ".$dt_brg->AMOUNT.",
										   ".$dt_brg->CURR_CODE.",
										   ".$dt_brg->STANDARD_PRICE.",
										   ".$dt_brg->AMOUNT.",
										   100001,
										   '".$dt_brg->COST_PROCESS_CODE."',
										   '".$dt_brg->COST_SUBJECT_CODE."',
										   '".$dt_brg->UNIT_STOCK."',
										   '".$dt_brg->WO_NO."'
										   )";
			$data_ins = oci_parse($connect, $ins);
			oci_execute($data_ins);

			if($data_ins){
				//cek item di inventory
				$cek_item_inv = "select count(*) jumitem from ztb_wh_whinventory where item_no=".$dt_brg->ITEM_NO." and this_month=$month_acc";
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
													   ".$dt_brg->ITEM_NO.",
													   $month_acc,
													   0,
													   0,
													   ".$dt_brg->QTY.",
													   ".$dt_brg->QTY.",
													   0,
													   ".$dt_brg->QTY.",
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
						where item_no = ".$dt_brg->ITEM_NO." and this_month = $month_acc ";
					$data_inv = oci_parse($connect, $cek_inv);
					oci_execute($data_inv);
					$dt_inv = oci_fetch_object($data_inv);

					if(($dt_inv->MONTH)==$month_acc){
						$issue_new = floatval($dt_inv->ISSUE1) + floatval($dt_brg->QTY);
						$inventory_new = floatval($dt_inv->THIS_INVENTORY) - floatval($dt_brg->QTY);
						$upd_inv = "update ztb_wh_whinventory set issue1=$issue_new  , this_inventory=$inventory_new 
							where item_no=".$dt_brg->ITEM_NO." and this_month=$month_acc ";
						$data_upd_inv = oci_parse($connect, $upd_inv);
						oci_execute($data_upd_inv);
					}else{		//if(($dt_inv->MONTH) == intval($now_month)-1)
						$issue2_new = floatval($dt_inv->issue2) + floatval($dt_brg->QTY);
						$inventory_new = floatval($dt_inv->THIS_INVENTORY) - floatval($dt_brg->QTY);
						$inventory2_new = floatval($dt_inv->LAST_INVENTORY) - floatval($dt_brg->QTY);
						$upd_inv = "update ztb_wh_whinventory set issue2 = $issue2_new  , last_inventory = $inventory2_new, this_inventory = $inventory_new 
							where item_no=".$dt_brg->ITEM_NO." and this_month=$month_acc ";
						$data_upd_inv = oci_parse($connect, $upd_inv);
						oci_execute($data_upd_inv);
					}
				}
			}*/
			$line++;
		}
	}
	// UPDATE UPLOAD ZTB_M_PLAN
	$upd2 = "update ztb_m_plan set upload=1 where id=".$dt_cek->ID."";
	$data_upd2 = oci_parse($connect, $upd2);
	oci_execute($data_upd2);

	/*  UPDATE WHINVENTORY */

	/* INSERT TRANSACTION */

}
?>
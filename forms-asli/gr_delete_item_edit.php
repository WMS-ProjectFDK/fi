<?php
$gr_no = strval($_REQUEST['gr_no']);
$item_no = strval($_REQUEST['item_no']);
include("../connect/conn.php");

$sql = "select a.gr_no, TO_CHAR(a.gr_date,'yyyy-mm-dd') as gr_date, b.po_no, b.item_no, b.qty, a.ex_rate, b.u_price from gr_header a 
	inner join gr_details b on a.gr_no= b.gr_no
	where a.gr_no='$gr_no' and b.item_no='$item_no'";
$result = oci_parse($connect, $sql);
oci_execute($result);
while ($dt_result = oci_fetch_object($result)){
	$upd = "update po_details set gr_qty = gr_qty-".$dt_result->QTY.", bal_qty = bal_qty+".$dt_result->QTY." where po_no='".$dt_result->PO_NO."' and item_no='".$dt_result->ITEM_NO."'";
	$data_upd = oci_parse($connect, $upd);
	oci_execute($data_upd);
	
	if($data_upd){
		$split = split('-',$dt_result->GR_DATE);
		$thisM = trim($split[0].$split[1]);

		$upd2 = "update whinventory set receive1 = receive1-".$dt_result->QTY.", this_inventory = this_inventory-".$dt_result->QTY." 
			where item_no = '".$dt_result->ITEM_NO."' and this_month = ".$thisM."";
		$data_upd2 = oci_parse($connect, $upd2);
		oci_execute($data_upd2);

		if($data_upd2){
			$del = "delete from transaction where item_no=".$dt_result->ITEM_NO." and slip_no='".$dt_result->GR_NO."' 
				and TO_CHAR(operation_date,'yyyy-mm-dd')='".$dt_result->GR_DATE."'";
			$data_del = oci_parse($connect, $del);
			oci_execute($data_del);

			if($data_del){
				//amt_o = qty*u_price
				$amt_o = floatval($dt_result->QTY)*floatval($dt_result->U_PRICE);
				//amt_l = qty_*u_price*ex_rate
				$amt_l = floatval($dt_result->QTY)*floatval($dt_result->U_PRICE)*floatval($dt_result->EX_RATE);

				$del2 = "update gr_header set amt_o = amt_o-".$amt_o.", amt_l=amt_l-".$amt_l."  where gr_no='".$dt_result->GR_NO."' ";
				$data_del2 = oci_parse($connect, $del2);
				oci_execute($data_del2);

				if($data_del2){
					$del3 = "delete from gr_details where gr_no='".$dt_result->GR_NO."' and item_no=".$dt_result->ITEM_NO."";
					$data_del3 = oci_parse($connect, $del3);
					oci_execute($data_del3);

					$del4 = "delete from account_payable where gr_no='".$dt_result->GR_NO."' ";
					$data_del4 = oci_parse($connect, $del4);
					oci_execute($data_del4);

					$del5 = "delete from fdac_purchase_trn where INVOICE_NO='".$dt_result->GR_NO."' ";
					$data_del5 = oci_parse($connect, $del5);
					oci_execute($data_del5);
					
					echo json_encode(array('success'=>true));	
					
				}					
			}	
		}
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));	
	}
}
?>
<?php
$gr_no = strval($_REQUEST['gr_no']);
$item_no = strval($_REQUEST['item_no']);
include("../../connect/conn.php");

$sql = "select a.gr_no, CAST(a.gr_date as varchar(10)) as gr_date, b.po_no, b.item_no, b.qty, a.ex_rate, b.u_price from gr_header a 
	inner join gr_details b on a.gr_no= b.gr_no
	where a.gr_no='$gr_no' and b.item_no='$item_no'";
$result = sqlsrv_query($connect, strtoupper($sql));
while ($dt_result = oci_fetch_object($result)){
	$upd = "update po_details set gr_qty = gr_qty-".$dt_result->QTY.", bal_qty = bal_qty+".$dt_result->QTY." where po_no='".$dt_result->PO_NO."' and item_no='".$dt_result->ITEM_NO."'";
	$data_upd = sqlsrv_query($connect, strtoupper($upd));
	
	if($data_upd){
		$split = explode('-', $dt_result->GR_DATE)
		$thisM = trim($split[0].$split[1]);

		$upd2 = "update whinventory set receive1 = receive1-".$dt_result->QTY.", this_inventory = this_inventory-".$dt_result->QTY." 
			where item_no = '".$dt_result->ITEM_NO."' and this_month = ".$thisM."";
		$data_upd2 = sqlsrv_query($connect, strtoupper($upd2));

		if($data_upd2){
			$del = "delete from transaction where item_no=".$dt_result->ITEM_NO." and slip_no='".$dt_result->GR_NO."' 
				and CAST(operation_date as varchar(10))='".$dt_result->GR_DATE."'";
			$data_del = sqlsrv_query($connect, strtoupper($del));

			if($data_del){
				//amt_o = qty*u_price
				$amt_o = floatval($dt_result->QTY)*floatval($dt_result->U_PRICE);
				//amt_l = qty_*u_price*ex_rate
				$amt_l = floatval($dt_result->QTY)*floatval($dt_result->U_PRICE)*floatval($dt_result->EX_RATE);

				$del2 = "update gr_header set amt_o = amt_o-".$amt_o.", amt_l=amt_l-".$amt_l."  where gr_no='".$dt_result->GR_NO."' ";
				$data_del2 = sqlsrv_query($connect, strtoupper($del2));

				if($data_del2){
					$del3 = "delete from gr_details where gr_no='".$dt_result->GR_NO."' and item_no=".$dt_result->ITEM_NO."";
					$data_del3 = sqlsrv_query($connect, strtoupper($del3));

					$del4 = "delete from account_payable where gr_no='".$dt_result->GR_NO."' ";
					$data_del4 = sqlsrv_query($connect, strtoupper($del4));

					$del5 = "delete from fdac_purchase_trn where INVOICE_NO='".$dt_result->GR_NO."' ";
					$data_del5 = sqlsrv_query($connect, strtoupper($del5));
					
					echo json_encode(array('success'=>true));	
					
				}					
			}	
		}
	}else{
		echo json_encode(array('errorMsg'=>'Some errors occured.'));	
	}
}
?>
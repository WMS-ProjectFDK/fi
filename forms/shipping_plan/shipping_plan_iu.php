<?php
error_reporting(0);
include("../../connect/conn.php");
session_start();
if (isset($_SESSION['id_wms'])){
	$user = $_SESSION['id_wms'];

	$type = htmlspecialchars($_REQUEST['type']);
	$id = htmlspecialchars($_REQUEST['ID']);
	$CR_DATE = htmlspecialchars($_REQUEST['CR_DATE']);
	$ETA = htmlspecialchars($_REQUEST['ETA']);
	$ETD = htmlspecialchars($_REQUEST['ETD']);
	$EX_FACT = htmlspecialchars($_REQUEST['EX_FACT']);
	$QTY = htmlspecialchars($_REQUEST['QTY']);
	$SO_NO = htmlspecialchars($_REQUEST['SO_NO']);
	$LINE_NO = htmlspecialchars($_REQUEST['LINE_NO']);
	$ANSWER_NO = htmlspecialchars($_REQUEST['ANSWER_NO']);
	$CRS_REMARK = htmlspecialchars($_REQUEST['CRS_REMARK']);
	$SI_NO = htmlspecialchars($_REQUEST['SI_NO']);
	$VESSEL = htmlspecialchars($_REQUEST['VESSEL']);
	$LN = filter_var($LINE_NO, FILTER_SANITIZE_NUMBER_INT);
	
	if($type == "DEL"){
		//delete ztb_answer
		$sql1  = "
		IF EXISTS(select * from INDICATION where answer_no = '$ANSWER_NO' and INV_NO is null) BEGIN
			delete from answer where answer_no = '$ANSWER_NO'; 
			
			delete from ztb_shipping_plan where row_id = '$id';

			delete from grpans_out
			where (customer_po_no + cast(customer_line_no as varchar(20))) 
					in (select customer_po_no + cast(customer_po_line_no as varchar(10))
						from so_header soh,so_details sod where sod.so_no = soh.so_no 
						and sod.so_no   = '$SO_NO'
						and sod.line_no = '$LN' 
			)
		END ELSE BEGIN RAISERROR('INVOICE EXISTS, PLEASE DELETE INVOICE FIRST',16,1) END" ;
		$data_del = sqlsrv_query($connect, $sql1);
		if($data_del === false ) {
			if(($errors = sqlsrv_errors()) != null) {  
				foreach( $errors as $error){  
				 $msg .= $error[ 'message']."<br/>";  
				 //$msg .= $sql1;
				}
			}
		}	

		//delete ztb_shipping_plan
		// $sql  = "delete from ztb_shipping_plan where row_id = '$id' ";
		// $data_del = sqlsrv_query($connect, $sql);
		
		// $sql2  = " delete from grpans_out " ;
	  	// $sql2 .= " where  (customer_po_no,customer_line_no)  = " ;
		// $sql2 .= "	(select customer_po_no,customer_po_line_no " ;
		// $sql2 .= "	 from so_header soh,so_details sod " ;
		// $sql2 .= "	 where sod.so_no   = soh.so_no " ;
		// $sql2 .= "	   and sod.so_no   = '$SO_NO' " ;
		// $sql2 .= "	   and sod.line_no = '$LN' " ;
		// $sql2 .= "	 ) " ;
	  	// $data_del2 = sqlsrv_query($connect, $sql2);
		if($msg != ''){
			echo json_encode($msg);
		}else{
			echo json_encode(array('successMsg'=>$type));
		}
		
	}else{
		$real_integer = filter_var($QTY, FILTER_SANITIZE_NUMBER_INT);
		//update ztb_answer
		$sql1  = "update  answer set " ;
	    $sql1 .= " qty = $real_integer" ;
	    $sql1 .= " where answer_no = '$ANSWER_NO' " ;
	    $data_upd1 = sqlsrv_query($connect, $sql1);

		//ini untuk edit keselurahan si dan ppbe_no.
		$sql2  = "update  answer set " ;
	    $sql2 .= " data_date = '$EX_FACT',";
	    $sql2 .= " duein_date = '$EX_FACT',";
	    $sql2 .= " eta = '$ETA',";
	    $sql2 .= " etd = '$ETD',";
	    $sql2 .= " stuffy_date =  '$EX_FACT'," ;
	    $sql2 .= " operation_date = sysdate,";
	    $sql2 .= " vessel='$VESSEL'";
	    $sql2 .= " where crs_remark = '$CRS_REMARK' AND si_no = '$SI_NO' " ;
	    $data_upd2 = sqlsrv_query($connect, $sql2);

		//update ztb_shipping_plan
	    $sql .= "  update  ztb_shipping_plan set" ;
	    $sql .= "  QTY =  $real_integer" ;
	    $sql .= "  where row_id = '$id'" ;
    	$data_upd = sqlsrv_query($connect, $sql);

		//ini untuk edit keselurahan si dan ppbe_no.
		$sql0 .= "  update  ztb_shipping_plan set" ;
	    $sql0 .= "  cr_Date =  '$CR_DATE'," ;
	    $sql0 .= "  ETA =  '$ETA'," ;
	    $sql0 .= "  ETD =  '$ETD'," ;
	    $sql0 .= "  EX_FACT =  '$EX_FACT'," ;
	    $sql0 .= "  inv_no =  '$CRS_REMARK'," ;
	    $sql0 .= "  vessel =  '$VESSEL'" ;
	    $sql0 .= "  where si_no = '$SI_NO' AND inv_no = '$CRS_REMARK'" ;
    	$data_upd0 = sqlsrv_query($connect, $sql0);
		
		echo json_encode(array('successMsg'=>$type));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
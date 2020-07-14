<?php
include("../connect/conn.php");
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
		$sql1  = " delete from answer " ;
	    $sql1 .= " where answer_no = '$ANSWER_NO' " ;
	    $data_del1 = oci_parse($connect, $sql1);
		oci_execute($data_del1);

		//delete ztb_shipping_plan
		$sql  = " delete from  ztb_shipping_plan " ;
		$sql .= " where rowid = '$id' ";
		$data_del = oci_parse($connect, $sql);
		oci_execute($data_del);
		
		$sql2  = " delete from grpans_out " ;
	  	$sql2 .= " where  (customer_po_no,customer_line_no)  = " ;
		$sql2 .= "	(select customer_po_no,customer_po_line_no " ;
		$sql2 .= "	 from so_header soh,so_details sod " ;
		$sql2 .= "	 where sod.so_no   = soh.so_no " ;
		$sql2 .= "	   and sod.so_no   = '$SO_NO' " ;
		$sql2 .= "	   and sod.line_no = '$LN' " ;
		$sql2 .= "	 ) " ;
	  	$data_del2 = oci_parse($connect, $sql2);
		oci_execute($data_del2);

		echo json_encode(array('successMsg'=>$type));
	}else{
		$real_integer = filter_var($QTY, FILTER_SANITIZE_NUMBER_INT);
		//update ztb_answer
		$sql1  = "update  answer set " ;
	    $sql1 .= " qty = $real_integer" ;
	    $sql1 .= " where answer_no = '$ANSWER_NO' " ;
	    $data_upd1 = oci_parse($connect, $sql1);
		oci_execute($data_upd1);

		//ini untuk edit keselurahan si dan ppbe_no.
		$sql2  = "update  answer set " ;
	    $sql2 .= " data_date = to_date('$EX_FACT','YYYY-MM-DD'),";
	    $sql2 .= " duein_date = to_date('$EX_FACT','YYYY-MM-DD'),";
	    $sql2 .= " eta = to_date('$ETA','YYYY-MM-DD'),";
	    $sql2 .= " etd = to_date('$ETD','YYYY-MM-DD'),";
	    $sql2 .= " stuffy_date =  TO_DATE('$EX_FACT','yyyy-mm-dd')," ;
	    $sql2 .= " operation_date = sysdate,";
	    $sql2 .= " vessel='$VESSEL'";
	    $sql2 .= " where crs_remark = '$CRS_REMARK' AND si_no = '$SI_NO' " ;
	    $data_upd2 = oci_parse($connect, $sql2);
		oci_execute($data_upd2);

		//update ztb_shipping_plan
	    $sql .= "  update  ztb_shipping_plan set" ;
	    $sql .= "  QTY =  $real_integer" ;
	    $sql .= "  where rowid = '$id'" ;
    	$data_upd = oci_parse($connect, $sql);
		oci_execute($data_upd);

		//ini untuk edit keselurahan si dan ppbe_no.
		$sql0 .= "  update  ztb_shipping_plan set" ;
	    $sql0 .= "  cr_Date =  '$CR_DATE'," ;
	    $sql0 .= "  ETA =  TO_DATE('$ETA','yyyy-mm-dd')," ;
	    $sql0 .= "  ETD =  TO_DATE('$ETD','yyyy-mm-dd')," ;
	    $sql0 .= "  EX_FACT =  TO_DATE('$EX_FACT','yyyy-mm-dd')," ;
	    $sql0 .= "  inv_no =  '$CRS_REMARK'," ;
	    $sql0 .= "  vessel =  '$VESSEL'" ;
	    $sql0 .= "  where si_no = '$SI_NO' AND inv_no = '$CRS_REMARK'" ;
    	$data_upd0 = oci_parse($connect, $sql0);
		oci_execute($data_upd0);
		
		echo json_encode(array('successMsg'=>$type));
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
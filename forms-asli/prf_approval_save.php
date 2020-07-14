<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn2.php");
	$rq = htmlspecialchars($_REQUEST['rq']); 
	$id = htmlspecialchars($_REQUEST['id']); 
	$vn = htmlspecialchars($_REQUEST['vn']); 
	$st = htmlspecialchars($_REQUEST['st']);
	$to = htmlspecialchars($_REQUEST['to']);
	$cr = htmlspecialchars($_REQUEST['cr']);
	$pr1 = htmlspecialchars($_REQUEST['pr1']);
	$pr2 = htmlspecialchars($_REQUEST['pr2']);
	/*$py = htmlspecialchars($_REQUEST['py']);
	$va = htmlspecialchars($_REQUEST['va']);*/

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$id0=date('Ym');

	if($vn!='' and $st!='0'){
	  if(intval($pr1)==intval($pr2)){
	  	$upd = "update ztb_prf_req_details set vendor='$vn', sts_approval='$st' where id='$id'";//, payment_terms='$py', shipment_via='$va'
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);
	  }elseif(intval($pr2) < intval($pr1)){
	  	$price = intval($pr2);
	  	$upd = "update ztb_prf_req_details set vendor='$vn', price=$price, sts_approval='$st' where id='$id'";//, payment_terms='$py', shipment_via='$va'
		$data_upd = oci_parse($connect, $upd);
		oci_execute($data_upd);
	  }
	  
		if ($data_upd){
			$upd_h = "update ztb_prf_req_header set total=(select  sum(price*qty) as total from ztb_prf_req_details where req_no='$rq') where req_no='$rq'";
			$data_upd_h = oci_parse($connect, $upd_h);
			oci_execute($data_upd_h);

	        $cek = "select * from ztb_prf_parameter where doc_no='$id0' and departement='COMPONENT'";
			$data_cek = oci_parse($connect, $cek);
			oci_execute($data_cek);
			$row = oci_fetch_array($data_cek);
			if(intval($row[2])!=0 AND intval($row[3])!=0){
				if($cr=='IDR'){
					$us = floatval($to) / floatval($row[3]);
					$budget = floatval($row[2]) - $us;
					$upd2 = "update ztb_prf_parameter set req=$budget where doc_no='$id0' and departement='COMPONENT'";
					$data_upd2 = oci_parse($connect, $upd2);
					oci_execute($data_upd2);
					if($data_upd2){
						echo json_encode("Success");		
					}else{
						echo json_encode(array('errorMsg'=>'Error'));		
					}
				}elseif($cr=='JPY'){
					$us = floatval($to) / floatval($row[4]);
					$budget = floatval($row[2]) - $us;

					$upd2 = "update ztb_prf_parameter set req=$budget where doc_no='$id0' and departement='COMPONENT'";
					$data_upd2 = oci_parse($connect, $upd2);
					oci_execute($data_upd2);
					if($data_upd2){
						echo json_encode("Success");		
					}else{
						echo json_encode(array('errorMsg'=>'Error'));		
					}
				}elseif($cr=='SGD'){
					$us = floatval($to) / floatval($row[5]);
					$budget = floatval($row[2]) - $us;

					$upd2 = "update ztb_prf_parameter set req=$budget where doc_no='$id0' and departement='COMPONENT'";
					$data_upd2 = oci_parse($connect, $upd2);
					oci_execute($data_upd2);
					if($data_upd2){
						echo json_encode("Success");		
					}else{
						echo json_encode(array('errorMsg'=>'Error'));		
					}
				}elseif($cr=='USD'){
					$us = floatval($to);
					$budget = $us;

					$upd2 = "update ztb_prf_parameter set req=$budget where doc_no='$id0' and departement='COMPONENT'";
					$data_upd2 = oci_parse($connect, $upd2);
					oci_execute($data_upd2);
					if($data_upd2){
						echo json_encode("Success");		
					}else{
						echo json_encode(array('errorMsg'=>'Error'));		
					}
				}
			}
	    }else{
	        echo json_encode(array('errorMsg'=>'Error'));
	    }
	}
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>
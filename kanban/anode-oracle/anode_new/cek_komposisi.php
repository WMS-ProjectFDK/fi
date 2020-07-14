<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['ty_gel']) ? strval($_REQUEST['ty_gel']) : '';
	$kanban = isset($_REQUEST['kanban']) ? strval($_REQUEST['kanban']) : '';

	$sql = "select type_zn,zn,aquapec,pw150,th175b,electrolyte,air, 
		zn+aquapec+ pw150+ th175b+electrolyte+air as total from ztb_assy_anode_komposisi
		where type_gel='$ty_gel'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);

	$cek = "select count(*) from (select flag, pemakaian_assy from ztb_assy_anode_gel_sts where type_gel='$ty_gel' AND kanban_no=$kanban)";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);	
	$row_cek = oci_fetch_array($data_cek);

	if($row_cek[0] > 0){
		$qry = "select flag from ztb_assy_anode_gel_sts where type_gel='$ty_gel' AND kanban_no=$kanban";
		$data_qry = oci_parse($connect, $qry);
		oci_execute($data_qry);	
		$row_qry = oci_fetch_array($data_qry);
		$flg = $row_qry[0];
	}else{
		$flg = "NO";
	}
	
	while ($row=oci_fetch_object($result)){
		$arrData[$arrNo] = array(
			"type_zn"=>$row->TYPE_ZN, 
			"zn"=>$row->ZN, 
			"aquapec"=>$row->AQUAPEC,
			"pw150"=>$row->PW150,
			"th175b"=>$row->TH175B,
			"electrolyte"=>$row->ELECTROLYTE,
			"air"=>$row->AIR,
			"total"=>$row->TOTAL,
			"flag"=>$flg
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
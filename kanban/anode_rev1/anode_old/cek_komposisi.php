<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['ty_gel']) ? strval($_REQUEST['ty_gel']) : '';
	$typ_zn = isset($_REQUEST['typ_zn']) ? strval($_REQUEST['typ_zn']) : '';

	$sql = "select zn,aquapec,pw150,th175b,electrolyte,air, 
		zn+aquapec+ pw150+ th175b+electrolyte+air as total from ztb_assy_anode_komposisi
		where type_gel='$ty_gel' AND type_zn='$typ_zn'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	
	while ($row=oci_fetch_object($result)){
		$arrData[$arrNo] = array(
			"zn"=>$row->ZN, 
			"aquapec"=>$row->AQUAPEC,
			"pw150"=>$row->PW150,
			"th175b"=>$row->TH175B,
			"electrolyte"=>$row->ELECTROLYTE,
			"air"=>$row->AIR,
			"total"=>$row->TOTAL
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
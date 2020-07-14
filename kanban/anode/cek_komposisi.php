<?php
	header("Content-type: application/json");
	include("../../connect/conn_kanbansys.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['ty_gel']) ? strval($_REQUEST['ty_gel']) : '';
	$kanban = isset($_REQUEST['kanban']) ? strval($_REQUEST['kanban']) : '';

	$sql = "select type_zn,zn,aquapec,pw150,th175b,electrolyte,air, aqupec_2,
		zn+aquapec+ pw150+ th175b+electrolyte+air+aqupec_2 as total from ztb_assy_anode_komposisi
		where type_gel='$ty_gel'";
	$result = odbc_exec($connect, $sql);

	$cek = "select count(*) from ztb_assy_anode_gel
		where type_gel = '$ty_gel' AND kanban_no = $kanban AND density is null";
	$data_cek = odbc_exec($connect, $cek);
	$row_cek = odbc_fetch_array($data_cek);

	if($row_cek[0] > 0){
		$flg = 1;
	}else{
		$flg = "NO";
	}
	
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"type_zn"=>$row->type_zn,
			"zn"=>number_format($row->zn),
			"aquapec"=>number_format($row->aquapec,1),
			"pw150"=>number_format($row->pw150,1),
			"th175b"=>number_format($row->th175b,1),
			"electrolyte"=>number_format($row->electrolyte,1),
			"air"=>number_format($row->air,1),
			"aqupec2"=>number_format($row->aqupec_2,1),
			"total"=>number_format($row->total,1),
			"flag"=>$flg
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
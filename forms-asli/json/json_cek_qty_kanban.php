<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$plan = isset($_REQUEST['plan']) ? strval($_REQUEST['plan']) : '';
	$arrData = array();
	$arrNo = 0;

	if($plan == 'LEBIH'){
		$arrData[$arrNo] = array("QTY"=>0);
	}else{
		/*CEK*/
		$cek = "select count(qty_act_perpallet) qty_perpallet from ztb_assy_kanban where id_print='$id_print'";
		$result_cek = oci_parse($connect, $cek);
		oci_execute($result_cek);
		$row_cek=oci_fetch_array($result_cek);

		if($row_cek[0] != 0){
			$sql = "select sum(qty_act_perpallet) qty_perpallet from ztb_assy_kanban
				where id_print = $id_print";
			$result = oci_parse($connect, $sql);
			oci_execute($result);

			while ($row=oci_fetch_array($result)){
				$arrData[$arrNo] = array(
					"QTY"=>rtrim($row[0])
				);
				$arrNo++;
			}
		}else{
			$arrData[$arrNo] = array("QTY"=>0);
		}
	}
	echo json_encode($arrData);
?>
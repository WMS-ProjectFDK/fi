<?php
	$plt = isset($_REQUEST['plt']) ? strval($_REQUEST['plt']) : '';
	$tgl_pro = isset($_REQUEST['tgl_pro']) ? strval($_REQUEST['tgl_pro']) : '';
	$plan = isset($_REQUEST['plan']) ? strval($_REQUEST['plan']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	
	include("../../connect/conn.php");
	header("Content-type: application/json");

	/*CEK*/
	if($plan == 'LEBIH' OR $plan == 'QC'){
		$cek = "select count(qty_act_perpallet) qty_perpallet from ztb_assy_kanban
			where id_plan='$plan' and pallet=$plt and replace(assy_line,'#','-')='$line' AND end_date is null ";	
	}else{
		$cek = "select count(qty_act_perpallet) qty_perpallet from ztb_assy_kanban
				where id_plan='$plan' and pallet=$plt and to_char(tanggal_produksi,'yyyy-mm-dd') = '$tgl_pro' ";
	}

	$result_cek = oci_parse($connect, $cek);
	oci_execute($result_cek);
	$row_cek=oci_fetch_array($result_cek);

	$arrData = array();
	$arrNo = 0;

	if($row_cek[0] != 0){
		$sql = "select start_date from ztb_assy_kanban 
			where id_plan = '$plan' and pallet = $plt AND end_date is NULL AND replace (assy_line,'#','-') = '$line' ";
		$result = oci_parse($connect, $sql);
		oci_execute($result);

		while ($row=oci_fetch_array($result)){
			$arrData[$arrNo] = array(
				"START"=>rtrim($row[0])
			);
			$arrNo++;
		}
	}else{
		$arrData[$arrNo] = array("START"=>'');
	}
	
	echo json_encode($arrData);
?>
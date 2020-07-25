<?php
	session_start();
	include("../../../connect/conn.php");

	$conf_aline = isset($_REQUEST['conf_aline']) ? strval($_REQUEST['conf_aline']) : '';
	$conf_cline = isset($_REQUEST['conf_cline']) ? strval($_REQUEST['conf_cline']) : '';
	$conf_cltyp = isset($_REQUEST['conf_cltyp']) ? strval($_REQUEST['conf_cltyp']) : '';
	$conf_cktyp = isset($_REQUEST['conf_cktyp']) ? strval($_REQUEST['conf_cktyp']) : '';
	$conf_nitem = isset($_REQUEST['conf_nitem']) ? strval($_REQUEST['conf_nitem']) : '';
	$conf_citem = isset($_REQUEST['conf_citem']) ? strval($_REQUEST['conf_citem']) : '';

	if($conf_cline != "true"){
		$line = "a.assy_line = '$conf_aline' and ";
	}else{
		$line = "";
	}

	if($conf_cktyp != "true"){
		$type = "a.cell_type = '$conf_cltyp' and ";
	}else{
		$type = "";
	}

	if ($conf_citem != "true"){
		$item_no = "a.item_no = $conf_nitem and ";
	}else{
		$item_no = "";
	}

	$where = "where $line $type $item_no a.item_no is not null";

	$sql = "select top 200 a.assy_line, a.cell_type, a.item_no, c.description, a.konversi, b.min_days, b.average, b.max_days from ztb_material_konversi a
		left join ztb_config_rm b on a.item_no=b.item_no
		left join item c on a.item_no= c.item_no
		$where
		order by a.item_no, a.assy_line, a.cell_type asc";
	
	$data = sqlsrv_query($connect, strtoupper($sql));


	$items = array();
	$rowno=0;

	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
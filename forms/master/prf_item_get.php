<?php
	session_start();
	$find = isset($_REQUEST['find']) ? strtoupper(strval($_REQUEST['find'])) : '';
	$result = array();

	include("../connect/conn2.php");

	if($find != ''){
		$where= "where a.item_no like '%$find%' or a.description like '%$find%' or a.description_org like '%$find%'";
	}else{
		$where= "where rownum <= 200";
	}

	$rowno=0;
	$rs = "select distinct a.item_no, a.description as ITEM_NAME,a.description_org as ITEM_SPEC, a.machine_code, a.item_type1, a.uom_q as ITEM_UOM, b.unit, a.curr_Code as item_curr, 'upload/'||c.name_image as item_upload, a.stock_subject_code as item_asset, d.item_group as ITEM_GRP, e.curr_short from item a 
		left join unit b on a.uom_q = b.unit_code left join ztb_prf_item c on a.item_no=c.item_no left join ztb_prf_item d on a.item_no=d.item_no
		left join currency e on a.curr_Code=e.curr_Code
		$where order by a.description asc";
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();

	while($row = oci_fetch_object($data)) {
		array_push($items, $row);
		/*$grp = $items[$rowno]->ITEM_GRP;
		if($grp=='GP'){
			$items[$rowno]->ITEM_GRP='General Parts';
		}elseif($grp=='MP'){
			$items[$rowno]->ITEM_GRP='Making Parts';
		}*/
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
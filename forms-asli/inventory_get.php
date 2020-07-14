<?php
	session_start();
	$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
	$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
	$rdo_sts = isset($_REQUEST['rdo_sts']) ? strval($_REQUEST['rdo_sts']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if($rdo_sts=='check_all'){
		$stock = "b.stock_subject_code is null and ";
	}elseif($rdo_sts=='check_PM'){
		$stock = "b.stock_subject_code='2' and ";
	}elseif($rdo_sts=='check_FG'){
		$stock = "b.stock_subject_code='5' and ";
	}elseif($rdo_sts=='check_WP'){
		$stock = "b.stock_subject_code='0' and ";
	}elseif($rdo_sts=='check_WIP'){
		$stock = "b.stock_subject_code='3' and ";
	}elseif($rdo_sts=='check_CSP'){
		$stock = "b.stock_subject_code='6' and ";
	}elseif($rdo_sts=='check_RM'){
		$stock = "b.stock_subject_code='1' and ";
	}elseif($rdo_sts=='check_semiFG'){
		$stock = "b.stock_subject_code='4' and ";
	}elseif($rdo_sts=='check_material2'){
		$stock = "b.stock_subject_code='7' and ";
	}elseif($rdo_sts==''){
		$stock = "b.stock_subject_code is null and ";
	}

	if ($src !='') {
		$where="where a.item_no='$src' AND (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
	}else{
		$where ="where $stock (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
	}
	
	
	include("../connect/conn.php");

	$sql = "select distinct max(this_month) as this_month, max(last_month) as last_month from whinventory";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$dt_result = oci_fetch_object($data);

	if($dt_result->THIS_MONTH == $cmbBln){
		$sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
			a.this_inventory, 
			a.receive1,
		    a.other_receive1,
		    a.issue1,
		    a.other_issue1,
		    a.last_inventory
		    from whinventory a
			inner join item b on a.item_no=b.item_no
			inner join unit c on b.uom_q=c.unit_code
			$where order by b.item asc, b.description asc";	
		$data = oci_parse($connect, $sql);
		oci_execute($data);
	}else{
		$sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
			a.last_inventory as this_inventory, 
			receive2 as receive1,
		    other_receive2 as other_receive1,
		    issue2 as issue1,
		    other_issue2 as other_issue1,
		    last2_inventory as last_inventory
		    from whinventory a
			inner join item b on a.item_no=b.item_no
			inner join unit c on b.uom_q=c.unit_code
			$where order by b.item asc, b.description asc";	
		$data = oci_parse($connect, $sql);
		oci_execute($data);	
	}

	$items = array();
	$rowno=0;
	
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$invn = $items[$rowno]->THIS_INVENTORY;
		$items[$rowno]->THIS_INVENTORY = number_format($invn);
		$recv = $items[$rowno]->RECEIVE1;
		$items[$rowno]->RECEIVE1 = number_format($recv);
		$orec = $items[$rowno]->OTHER_RECEIVE1;
		$items[$rowno]->OTHER_RECEIVE1 = number_format($orec);
		$issu = $items[$rowno]->ISSUE1;
		$items[$rowno]->ISSUE1 = number_format($issu);
		$oisu = $items[$rowno]->OTHER_ISSUE1;
		$items[$rowno]->OTHER_ISSUE1 = number_format($oisu);
		$linv = $items[$rowno]->LAST_INVENTORY;
		$items[$rowno]->LAST_INVENTORY = number_format($linv);
		$items[$rowno]->L_INVENTORY = $linv;

		$items[$rowno]->PRD = str_replace('-',' ',$cmbBln_txt);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
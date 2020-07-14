<?php
	session_start();
	$user = $_SESSION['id_wms'];
	// error_reporting(0);
	ini_set('memory_limit', '-1');
	$items = array();
	$rowno=0;
	include("../connect/conn.php");
	$rdo_sts = isset($_REQUEST['rdo_sts']) ? strval($_REQUEST['rdo_sts']) : '';

	if($rdo_sts != 0){
		$where="where cut_off > $rdo_sts";
		$sql = "select * from zvw_slow_move $where";
		$data = oci_parse($connect, $sql);
		oci_execute($data);
		
		while($row = oci_fetch_object($data)){
			array_push($items, $row);

			$itm = $items[$rowno]->ITEM;
			$desc = $items[$rowno]->DESCRIPTION;
			$items[$rowno]->DESC = $itm.'<br/>'.$desc;

			$sup = $items[$rowno]->SUPPLIER_CODE;
			$com = $items[$rowno]->COMPANY;
			$items[$rowno]->SUPPLIER = $sup.' - '.$com;

			$q = $items[$rowno]->QTY;
			$items[$rowno]->QTY = number_format($q);

			$slip = $items[$rowno]->TRANSACTION_QTY;
			$items[$rowno]->TRANSACTION_QTY = number_format($slip);

			$li = $items[$rowno]->LAST_INVENTORY;
			$items[$rowno]->LAST_INVENTORY = number_format($li);

			$rowno++;
		}
	}

	$fp = fopen('slow_moving_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["total"] = count($items);
	$result["rows"] = $items;
	echo json_encode($result);
?>
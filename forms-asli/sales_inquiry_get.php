<?php
	include("../connect/conn.php");
	session_start();

	$sales_inq = isset($_REQUEST['sales_inq']) ? strval($_REQUEST['sales_inq']) : '';
	$ck_inquiry = isset($_REQUEST['ck_inquiry']) ? strval($_REQUEST['ck_inquiry']) : '';
	$sales_no = isset($_REQUEST['sales_no']) ? strval($_REQUEST['sales_no']) : '';
	$ck_sales = isset($_REQUEST['ck_sales']) ? strval($_REQUEST['ck_sales']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$inc_email_date = isset($_REQUEST['inc_email_date']) ? strval($_REQUEST['inc_email_date']) : '';
	$ck_date_incoming = isset($_REQUEST['ck_date_incoming']) ? strval($_REQUEST['ck_date_incoming']) : '';
	$sales_dest = isset($_REQUEST['sales_dest']) ? strval($_REQUEST['sales_dest']) : '';
	$ck_dest = isset($_REQUEST['ck_dest']) ? strval($_REQUEST['ck_dest']) : '';
	$sales_po = isset($_REQUEST['sales_po']) ? strval($_REQUEST['sales_po']) : '';
	$ck_po_no = isset($_REQUEST['ck_po_no']) ? strval($_REQUEST['ck_po_no']) : '';
	$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

	if ($ck_inquiry != "true"){

	}else{

	}

	if ($ck_sales != "true"){

	}else{

	}

	if ($ck_item_no != "true"){

	}else{

	}

	if ($ck_date_incoming != "true"){

	}else{

	}

	if ($ck_dest != "true"){

	}else{

	}

	if ($ck_po_no != "true"){

	}else{

	}

	$sql = "";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){

		$rowno++;
	}

	$result["rows"] = $items;
	echo json_encode($result);
?>
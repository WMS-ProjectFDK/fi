<?php
	error_reporting(0);
	include("../../connect/conn_accpac.php");
	header("Content-type: application/json");
	$tipe = isset($_REQUEST['tipe']) ? strval($_REQUEST['tipe']) : '';
	$category = isset($_REQUEST['category']) ? strval($_REQUEST['category']) : '';
	$ast = isset($_REQUEST['ast']) ? strval($_REQUEST['ast']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$location = isset($_REQUEST['location']) ? strval($_REQUEST['location']) : '';
	$group = isset($_REQUEST['group']) ? strval($_REQUEST['group']) : '';

	if ($tipe=="DETAIL"){
		$sql = "select CATEGORY, GRP, GRPDESC, ASTNO, DESC_NAME, INVOICE, VENDOR, LOCATION, LINE, CAST(ACQDATE as varchar(10)) as ACQDATE, 
			ACQ_VALUE, ACCUMULATED_DEP, BOOK_VALUE
			from zvw_ast_detail where 1=1 ";
	}else if ($tipe=="SUMMARY"){
		$sql = "select CATEGORY, GRP, GRPDESC, LOCATION, LINE, CAST(ACQDATE as varchar(10)) as ACQDATE, ACQ_VALUE, ACCUMULATED_DEP, BOOK_VALUE
			from zvw_ast_summary where 1=1 ";
	}

	$line = str_replace("*","#",$line);
	if($category!="")$sql.=" and grpdesc like '%$category%'";
	if($group!="")$sql.=" and CATEGORY like '%$group%'";
	if($ast!="")$sql.=" and astno like '%$ast%'";
	if($line!="")$sql.=" and line like '%$line%'";
	if($location!="")$sql.=" and location like '%$location%'";
	
	$sql .= " order by CATEGORY,grp";
	// echo $sql;
	$rs=sqlsrv_query($connect,strtoupper($sql));

	$arrNo = 0;
	$items = array();
	$foot = array();

	$arr    = array();
	$x      = 1;
	$rowno = 0;
	$book = 0;

	$r = 0;		$s = 0;		$q = 0;
	$a = 0;		$b = 0;		$c = 0;

	while($row= sqlsrv_fetch_object($rs)) { 
		array_push($items, $row);

		$a = $items[$rowno]->ACQ_VALUE;
		$items[$rowno]->ACQ_VALUE = number_format($a);

		$b = $items[$rowno]->ACCUMULATED_DEP;
		$items[$rowno]->ACCUMULATED_DEP = number_format($b);

		$c = $items[$rowno]->BOOK_VALUE;
		$items[$rowno]->BOOK_VALUE = number_format($c);
		
		$r +=$a;
		$s +=$b;
		$q +=$c;
		
		$rowno++;
	}

	$foot[0]->LOCATION = '<span style="color:blue;font-size:12px;"><b>GRAND TOTAL (USD)</b></span>';
	$foot[0]->ACQ_VALUE = '<span style="color:blue;font-size:12px;"><b>$ '.number_format($r).'</b></span>';
	$foot[0]->ACCUMULATED_DEP = '<span style="color:blue;font-size:12px;"><b>$ '.number_format($s).'</b></span>';
	$foot[0]->BOOK_VALUE = '<span style="color:blue;font-size:12px;"><b>$ '.number_format($q).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>
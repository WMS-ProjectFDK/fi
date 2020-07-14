<?php
	session_start();
	// $date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	// $date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	// $cmb_so_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	// $ck_so_no = isset($_REQUEST['ck_so_no']) ? strval($_REQUEST['ck_so_no']) : '';
	// $cr_date = isset($_REQUEST['cr_date']) ? strval($_REQUEST['cr_date']) : '';
	// $ck_cr_date = isset($_REQUEST['ck_cr_date']) ? strval($_REQUEST['ck_cr_date']) : '';
	// $src = trim($srce);

	$page = isset($_POST['page']) ? intval($_POST['page']) : 3;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 30;


	
	include("../connect/conn.php");

	#PRF 
	

	$sql  = " select count(*) total from so_header where so_date > '01-JAN-17'  order by so_date DESC";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$result["total"] = $items[$rowno]->TOTAL;
		
		$rowno++;
	}
	



	if ($page == 1) {
		$sql  = "select * from (
  select rownum rec,bb.* FROM ( 
    SELECT rownum,a.* 
      FROM(
        SELECT so_header.* from so_header  where so_date > '01-JAN-17'  order by so_date DESC
        ) a 
      WHERE rownum <=$page*($rows) 
  )bb
)cc
WHERE cc.rec <= $rows ";

	}else{

		$startrow = ($page*($rows))-$rows ;
		$endrows = ($page*($rows));
		$sql  = "select * from (
  select rownum rec,bb.* FROM ( 
    SELECT rownum,a.* 
      FROM(
        SELECT so_header.* from so_header  where so_date > '01-JAN-17'  order by so_date DESC
        ) a 
      WHERE rownum <=$page*($rows) 
  )bb
)cc
WHERE cc.rec between $startrow and $endrows  ";
	}
  	
	

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
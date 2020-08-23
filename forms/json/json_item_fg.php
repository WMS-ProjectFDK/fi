<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$sql = "select distinct item_no,
							description,
							(select max(level_no)  as lvl from structure where upper_item_no = item.item_no ) as level_no 
	        from item 
	        where stock_subject_code = 5 order by description asc";
	$result = sqlsrv_query($connect, $sql);
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
			"id_item"=>rtrim($row[0]), 
			"name_item"=>rtrim($row[1]),
			"id_name_item"=>rtrim($row[0])." - ".rtrim($row[1]),
			"level_no"=>rtrim($row[2])
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
<?php
	session_start();
	$result = array();
	include("../../connect/conn.php");
	$rs = "select a.id, a.menu_parent, a.menu_sub_parent as menu_sub_parent_real,
		a.menu_sub_parent + ' [' + id_sub_parent + ']' as menu_sub_parent, 
		a.menu_name, a.link, id_parent, id_sub_parent, id_menu 
		from ztb_menu a order by id asc";
	$data_rs = sqlsrv_query($connect, $rs);
	
	$items = array();		$rowno=0;
	while($row = sqlsrv_fetch_object($data_rs)){
		array_push($items, $row);
		
		$sub = $items[$rowno]->menu_sub_parent;
		$j = strlen($sub);
		if($j<=3){
			$items[$rowno]->menu_sub_parent = '-';
		}else{
			$items[$rowno]->menu_sub_parent = $sub;
		}
		
		$rowno++;
	}
	sqlsrv_close($connect);

	$result["rows"] = $items;
	echo json_encode($result);
?>
<?php
	include("../../connect/conn.php");
	session_start();
	
	$nik = isset($_REQUEST['nik']) ? strval($_REQUEST['nik']) : '';
	$result = array();
	
	$sql = "select distinct b.person_code, b.menu_id, a.id, a.menu_parent, a.menu_sub_parent, a.menu_name,
		case when b.access_view = 'T' then 'TRUE' else 'FALSE' end as a_view,
		case when b.access_add = 'T' then 'TRUE' else 'FALSE' end as a_add,
		case when b.access_update = 'T' then 'TRUE' else 'FALSE' end as a_edit,
		case when b.access_delete = 'T' then 'TRUE' else 'FALSE' end as a_del,
		case when b.access_print = 'T' then 'TRUE' else 'FALSE' end as a_print
		from ztb_menu a
		left join ztb_user_access b on a.id=b.menu_id and b.person_code='$nik' order by b.menu_id asc";

	$query = sqlsrv_query($connect, $sql);
	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($query)){
		array_push($items, $row);
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
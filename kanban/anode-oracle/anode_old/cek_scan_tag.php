<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['type_gel']) ? strval($_REQUEST['type_gel']) : '';
	$typ_zn = isset($_REQUEST['type_zn']) ? strval($_REQUEST['type_zn']) : '';
	$ty_kanban = isset($_REQUEST['kanban_no']) ? strval($_REQUEST['kanban_no']) : '';

	$sql = "select a.kanban_no, a.type_gel, a.type_zn, a.id, a.density, a.qty_zn, a.lot_zn, a.qty_aquapec, a.lot_aquapec, a.qty_pw150, a.lot_pw150, a.qty_th175b, a.lot_th175b,
		a.worker_id_drymix, a.worker_id_gel, b.electrolyte, b.air, (a.qty_zn+a.qty_aquapec+a.qty_pw150+a.qty_th175b+b.electrolyte+b.air) as total
		from ztb_assy_anode_gel a
		inner join ztb_assy_anode_komposisi b on a.type_gel = b.type_gel
		where a.density is null and a.worker_id_drymix is not null and a.worker_id_gel is null AND
		a.type_gel = '$ty_gel' AND a.type_zn = '$typ_zn' AND a.kanban_no = $ty_kanban AND
		id = (select min(id) from ztb_assy_anode_gel
          where density is null and worker_id_drymix is not null and worker_id_gel is null)
				order by a.id asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);

	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
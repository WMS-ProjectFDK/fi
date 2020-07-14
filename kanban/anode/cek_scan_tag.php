<?php
	header("Content-type: application/json");
	include("../../connect/conn_kanbansys.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['type_gel']) ? strval($_REQUEST['type_gel']) : '';
	$ty_kanban = isset($_REQUEST['kanban_no']) ? strval($_REQUEST['kanban_no']) : '';

	$sql = "select cast(a.kanban_no as int) as kanban_no, a.type_gel, a.type_zn, a.id, a.density, 
		a.qty_zn, a.lot_zn, a.qty_aquapec, a.lot_aquapec, a.qty_pw150, a.lot_pw150, a.qty_th175b, a.lot_th175b, a.qty_aqupec2, a.lot_aqupec2,
		a.worker_id_drymix, a.worker_id_gel, b.electrolyte, b.air, 
		cast((a.qty_zn+a.qty_aquapec+a.qty_pw150+a.qty_th175b+a.qty_aqupec2+b.electrolyte+b.air) as decimal(18,2)) as total
		from ztb_assy_anode_gel a
		inner join ztb_assy_anode_komposisi b on a.type_gel = b.type_gel
		where a.density is null AND a.type_gel = '$ty_gel' AND a.kanban_no = $ty_kanban AND
		id = (select min(id) from ztb_assy_anode_gel
          where upto_date_instruksi is not null and upto_date_drymix is not null AND upto_date_hasil_anode is null AND
          density is null and worker_id_drymix is not null and worker_id_gel is null AND
          type_gel = '$ty_gel' AND kanban_no = $ty_kanban)
		order by a.id asc";
	$result = odbc_exec($connect, $sql);

	while ($row = odbc_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
<?php
	include("../../connect/conn_kanbansys.php");
	$items = array();
	$rowno=0;

	$sql = "select top 7 a.ID_PRINT, a.ID_PLAN, b.ASSY_LINE, b.CELL_TYPE, a.UPTO_DATE, a.REMARK,
		sum(b.qty_act_perpallet) QTY, sum(b.qty_act_perbox) BOX, MAX(A.position) POSITION
		from ztb_assy_heating a
		inner join ztb_assy_kanban b on a.id_print=b.id_print
		where position in (1,2)
		group by a.id_print, a.id_plan, b.assy_line, b.cell_type, a.upto_date, a.remark
		order by a.upto_date desc";
	$data = odbc_exec($connect, $sql);
	$i = 0;
	while($row = odbc_fetch_object($data)){
		array_push($items, $row);
		$a_date = $items[$rowno]->UPTO_DATE;
		$items[$rowno]->UPTO_DATE = str_replace(' ', '<br/>', $a_date);
		$rowno++;
		$i++;
		if($i == 7){
			break;
		};
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
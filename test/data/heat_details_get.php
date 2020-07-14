<?php
	include("../../connect/conn.php");
	$items = array();
	$rowno=0;

	$sql = "select * from (
		select a.id_print, a.id_plan, b.assy_line, b.cell_type, a.upto_date, a.remark,
		sum(b.qty_act_perpallet) QTY, sum(b.qty_act_perbox) BOX, MAX(A.position) position
		from ztb_assy_heating a
		inner join ztb_assy_kanban b on a.id_print=b.id_print
		group by a.id_print, a.id_plan, b.assy_line, b.cell_type, a.upto_date, a.remark
		order by to_date(a.upto_date,'YYYY-MM-DD HH24:MI:SS') desc
		)
		where position in (1,2) and rownum < 8";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$i = 0;
	while($row = oci_fetch_object($data)){
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
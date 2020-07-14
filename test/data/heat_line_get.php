<?php
	include("../../connect/conn.php");
	$items = array();
	$rowno=0;

	$sql = "select assy_line, cell_type, sum(qty_in) as QTY_IN, sum(box_in) as BOX_IN, sum(qty_out) as QTY_OUT, sum(box_out) as BOX_OUT
		from(
		select distinct assy_line, cell_type,
		case when position=1 then qty else null end as qty_in,
		case when position=1 then box else null end as box_in,
		case when position=2 then qty else null end as qty_out,
		case when position=2 then box else null end as box_out
		from (
		select distinct assy_line, cell_type, position,
		sum(qty) QTY, sum(box) BOX
		from (
		select a.id_print, a.id_plan, b.assy_line, b.cell_type, max(a.position) position,
		sum(b.qty_act_perpallet) QTY, sum(b.qty_act_perbox) BOX
		from ztb_assy_heating a
		inner join ztb_assy_kanban b on a.id_print=b.id_print
		group by a.id_print, a.id_plan, b.assy_line, b.cell_type
		)
		group by assy_line, cell_type, position
		)
		)group by assy_line, cell_type";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$qty_in = $items[$rowno]->QTY_IN;
		$items[$rowno]->QTY_IN = number_format($qty_in);

		$box_in = $items[$rowno]->BOX_IN;
		$items[$rowno]->BOX_IN = number_format($box_in);

		$qty_out = $items[$rowno]->QTY_OUT;
		$items[$rowno]->QTY_OUT = number_format($qty_out);

		$box_out = $items[$rowno]->BOX_OUT;
		$items[$rowno]->BOX_OUT = number_format($box_out);
		
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
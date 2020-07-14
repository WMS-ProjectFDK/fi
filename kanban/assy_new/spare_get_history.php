<?php
	include("../../connect/conn_kanbansys.php");

	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$machine = isset($_REQUEST['machine']) ? strval($_REQUEST['machine']) : '';

	$sql = "select a.id_part, a.tgl_ganti, a.id_machine, b.machine, a.line, a.nama_part, a.lifetime, a.aktual_pc, a.pic
		from assembly_part_history a
		left outer join assembly_line_master b on a.id_machine = b.id_machine and a.line = b.line
		where replace(a.line,'#','-') = '$line' and a.id_machine=$machine
		order by a.tgl_ganti asc";
	$rs = odbc_exec($connect,$sql);

	$items = array();
	$rowno=0;
	while($row = odbc_fetch_object($rs)){
		array_push($items, $row);

		$m = $items[$rowno]->machine;
		$items[$rowno]->machine = strtoupper($m);

		$p = $items[$rowno]->nama_part;
		$items[$rowno]->nama_part = strtoupper($p);

		$pic = $items[$rowno]->pic;
		$items[$rowno]->pic = strtoupper($pic);
    
	    $lifetime = $items[$rowno]->lifetime;
    	$items[$rowno]->lifetime = number_format($lifetime);

    	$act_pcs = $items[$rowno]->aktual_pc;
	    $items[$rowno]->aktual_pc = number_format($act_pcs);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>
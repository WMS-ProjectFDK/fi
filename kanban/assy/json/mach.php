<?php
	header("Content-type: application/json");
	include("../../../connect/conn_kanbansys.php");
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$sql = "select id_machine, machine from assembly_line_master 
		where replace(line,'#','-')='$line' and proporsi is not null
		order by id_machine asc";
	$result = odbc_exec($connect, $sql);
	$arrNo = 0;
	$arrData = array();
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"id_m"=>$row->id_machine,
			"mach"=>strtoupper($row->machine)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
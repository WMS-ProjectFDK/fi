<?php
set_time_limit(0);
ini_set('memory_limit', '-1');
include("../connect/conn.php");
$msg = '';

$sql = "select * from mps_header";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);
$pesan = oci_error($stmt);
$msg += $pesan['message'];

$sqld = "select * from mps_details";
$stmtd = oci_parse($connect, $sqld);
$resd = oci_execute($stmtd);
$pesand = oci_error($stmtd);
$msg += $pesand['message'];

if($msg == ''){
	include("../connect/conn_kanbansys.php");
	
	$del0 = "delete from mps_header";
	$res0 = odbc_exec($connect, $del0);
	
	$del1 = "delete from mps_details";
	$res1 = odbc_exec($connect, $del1);

	while($row = oci_fetch_array($stmt)){
		include("../connect/conn_kanbansys.php");
		
		$sqlsvr = "insert into mps_header values ($row[0],
						'$row[1]',
						'$row[2]',
						'$row[3]',
						'$row[4]',
						'$row[5]',
						'$row[6]',
						'$row[7]',
						'$row[8]',
						'$row[9]',
						".(is_null($row[10]) ? ($row[10] === '' ? NULL : 'NULL') : ($row[10] === '' ? $row[10] : 'convert(date,'."'".$row[10]."'".',5)')).",
						".(is_null($row[11]) ? ($row[11] === '' ? NULL : 'NULL') : ($row[11] === '' ? $row[11] : 'convert(date,'."'".$row[11]."'".',5)')).",
						'$row[12]',
						".(is_null($row[13]) ? ($row[13] === '' ? NULL : 'NULL') : ($row[13] === '' ? $row[13] : $row[13])).",
						'$row[14]',
						".(is_null($row[15]) ? ($row[15] === '' ? NULL : 'NULL') : ($row[15] === '' ? $row[15] : $row[15])).",
						".(is_null($row[16]) ? ($row[16] === '' ? NULL : 'NULL') : ($row[16] === '' ? $row[16] : floatval($row[16]))).",
						".(is_null($row[17]) ? ($row[17] === '' ? NULL : 'NULL') : ($row[17] === '' ? $row[17] : floatval($row[17]))).",
						'$row[18]',
						".(is_null($row[19]) ? ($row[19] === '' ? NULL : 'NULL') : ($row[19] === '' ? $row[19] : floatval($row[19]))).",
						GETDATE(),
						'$row[21]',
						'$row[22]',
						'$row[23]')";
		//echo $sqlsvr.'<br/>';
		$result = odbc_exec($connect, $sqlsvr);
	}

	while($rowd = oci_fetch_array($stmtd)){
		include("../connect/conn_kanbansys.php");
		$sqlsvrd = "insert into mps_details values ('$rowd[0]',$rowd[1],convert(date,'$rowd[2]',5),
													$rowd[3],GETDATE())";
		//echo $sqlsvrd;
		$resultd = odbc_exec($connect, $sqlsvrd);
	}
	echo json_encode('Create MPS success');
}else{
	echo json_encode($msg);
}
?>
<?php
	header("Content-type: application/json");
	include("../../connect/conn_kanbansys.php");
	$arrData = array();
	$arrNo = 0;

	$sql = "select type_gel, type_zn, kanban_no, id, 
		qty_aqupec2, qty_zn, qty_aquapec, qty_pw150, qty_th175b, remark
		from ztb_assy_anode_gel
		where id = (select min(id) from ztb_assy_anode_gel
		            where upto_date_instruksi is not null and upto_date_drymix is null AND upto_date_hasil_anode is null)";
	$result = odbc_exec($connect, $sql);

	$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
		where DATE_PROD between 
		(SELECT CONVERT(VARCHAR(10), SYSDATETIME(),121) + ' 07:00:00')
		and
		(SELECT CONVERT(VARCHAR(10),CAST(DATEADD(d,+1,GETDATE()) AS DATE)) + ' 07:00:00')";
	$data_jum = odbc_exec($connect, $jum_adukan);

	$row_jum=odbc_fetch_object($data_jum);
	$jumlah_aduk = $row_jum->jumlah_adukan;
	
	while ($row=odbc_fetch_object($result)){
		$arrData[$arrNo] = array(
			"type_gel"=>$row->type_gel,
			"type_zn"=>$row->type_zn,
			"kanban_no"=>$row->kanban_no,
			"process_no"=>$row->id,
			"zn"=>$row->qty_zn,
			"aquapec"=>$row->qty_aquapec,
			"pw150"=>$row->qty_pw150,
			"th175b"=>$row->qty_th175b,
			"aqupec2"=>$row->qty_aqupec2,
			"remark"=>$row->remark,
			"adukan_ke"=>$jumlah_aduk
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
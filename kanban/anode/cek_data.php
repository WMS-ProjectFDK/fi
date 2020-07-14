<?php
	header("Content-type: application/json");
	include("../../connect/conn_kanbansys.php");
	$arrData = array();
	$arrNo = 0;

	$ty_gel = isset($_REQUEST['ty_gel']) ? strval($_REQUEST['ty_gel']) : '';
	$no_tag = isset($_REQUEST['no_tag']) ? strval($_REQUEST['no_tag']) : '';

	$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
		where DATE_PROD between 
		(SELECT CONVERT(VARCHAR(10), SYSDATETIME(),121) + ' 07:00:00')
		and
		(SELECT CONVERT(VARCHAR(10),CAST(DATEADD(d,+1,GETDATE()) AS DATE)) + ' 07:00:00')";
	$data_jum = odbc_exec($connect, $jum_adukan);
	$row_jum=odbc_fetch_object($data_jum);
	$jumlah_aduk = $row_jum->jumlah_adukan;

	$cek = "select count(*) as j from
		(
		select id, density, no_tag from ztb_assy_anode_gel
		where no_tag=$no_tag AND type_gel='$ty_gel'
		AND density is not null AND assy_line is null AND date_use is null
		) a";
	$data_cek = odbc_exec($connect, $cek);
	$row_cek=odbc_fetch_object($data_cek);

	if($row_cek->j == 0){
		$arrData[$arrNo] = array(
			'id'=>'ERROR',
			'density'=>'ANODE GEL SUDAH DI SCAN',
			'jum'=>$jumlah_aduk

		);
	}else{
		$sql = "select id, cast(density as decimal(18,2)) as density, cast(no_tag as int) as no_tag from ztb_assy_anode_gel
			where no_tag = $no_tag AND type_gel = '$ty_gel'
			AND density is not null AND assy_line is null AND date_use is null
			and id = (select max(id) from ztb_assy_anode_gel
			where no_tag = $no_tag AND type_gel = '$ty_gel'
			AND density is not null AND assy_line is null AND date_use is null)";
		$result = odbc_exec($connect, $sql);
		
		while ($row=odbc_fetch_object($result)){
			$arrData[$arrNo] = array(
				"id"=>rtrim($row->id), 
				"density"=>rtrim($row->density),
				"notag"=>rtrim($row->no_tag),
				"jum"=>$jumlah_aduk
			);
			$arrNo++;
		}	
	}
	echo json_encode($arrData);
?>
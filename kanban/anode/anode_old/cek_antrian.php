<?php
	header("Content-type: application/json");
	include("../../connect/conn.php");
	$arrData = array();
	$arrNo = 0;

	$sql = "select a.*
		from ztb_assy_anode_gel a
		inner join ztb_assy_anode_gel_sts b on a.kanban_no= b.kanban_no AND a.type_gel= b.type_gel AND a.type_zn= b.type_zn
		where b.flag=0 and 
		id = (select min(c.id) from ztb_assy_anode_gel c 
          	  inner join ztb_assy_anode_gel_sts d on c.kanban_no= d.kanban_no AND c.type_gel= d.type_gel AND c.type_zn= d.type_zn
          	  where flag=0)
		order by a.id asc";
	$result = oci_parse($connect, $sql);
	oci_execute($result);

	$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
		where to_date(to_char(date_prod, 'yyyy-mm-dd hh24:mi:ss'),'yyyy-mm-dd hh24:mi:ss') between 
		(select to_date(to_char(sysdate, 'yyyy-mm-dd')||' 07:00:00', 'yyyy-mm-dd hh24:mi:ss') from dual) AND
		(select to_date(to_char(sysdate+1, 'yyyy-mm-dd')||' 07:00:00', 'yyyy-mm-dd hh24:mi:ss') from dual)";
	$data_jum = oci_parse($connect, $jum_adukan);
	oci_execute($data_jum);
	$row_jum=oci_fetch_object($data_jum);
	$jumlah_aduk = $row_jum->JUMLAH_ADUKAN;
	
	while ($row=oci_fetch_object($result)){
		$arrData[$arrNo] = array(
			"type_gel"=>$row->TYPE_GEL,
			"type_zn"=>$row->TYPE_ZN,
			"kanban_no"=>$row->KANBAN_NO,
			"process_no"=>$row->ID,
			"zn"=>$row->QTY_ZN,
			"aquapec"=>$row->QTY_AQUAPEC,
			"pw150"=>$row->QTY_PW150,
			"th175b"=>$row->QTY_TH175B,
			"remark"=>$row->REMARK,
			"adukan_ke"=>$jumlah_aduk
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>
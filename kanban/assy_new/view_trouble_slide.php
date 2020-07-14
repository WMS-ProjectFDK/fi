<?php
	include("../../connect/conn.php");

	$sql = "select 'TROUBLE-'||a.ng_no||': '||b.ng_name_proses||' ('|| b.ng_name||' : '||a.ng_qty||' menit)'||
		' user: '||c.name||' - '|| a.assy_line||' / '||a.cell_type||' ('|| a.tanggal_produksi||')' as NG
		from ztb_assy_trans_ng a
		inner join ztb_assy_ng b on a.ng_id_proses= b.ng_id_proses AND a.ng_id= b.ng_id
		inner join ztb_worker c on a.worker_id=c.worker_id
		where a.ng_no = (select max(ng_no) from ztb_assy_trans_ng)";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$arrNo = 0;
	$arrData = array();

	$items = array();
	while($row = oci_fetch_object($data)){
		$arrData[$arrNo] = array("ng_slide"=>$row->NG);
	}
	echo json_encode($arrData);
?>
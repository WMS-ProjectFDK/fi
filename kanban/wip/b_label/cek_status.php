<?php
	header("Content-type: application/json");
	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$arrData = array();
	$arrNo = 0;
	
	include("../../../connect/conn.php");
	/*CEK*/
	$cek = "select count(*) AS JUM,nvl(max(position),0) AS POS from ZTB_ASSY_HEATING where id_print='".$id_print."'";
	$result_cek = oci_parse($connect, $cek);
	oci_execute($result_cek);
	$row_cek=oci_fetch_object($result_cek);
	if ($row_cek->JUM == 0){
		$sql= "select count(*) j from 
			(select count(start_date) jum_start,id_print id_start from ztb_assy_kanban where id_print = '".$id_print."' group by id_print) aa
			left outer join
			(select count(end_Date) jum_end,id_print id_end from ztb_assy_kanban where id_print = '".$id_print."' group by id_print) bb
			on aa.id_start = id_end
      		where jum_start = jum_end";
		$result_sql = oci_parse($connect, $sql);
		oci_execute($result_sql);
		$row_sql=oci_fetch_object($result_sql);

		if($row_sql->J > 0){
			$arrData[$arrNo] = array("kode"=>'IN');
		}else{
			$arrData[$arrNo] = array("kode"=>'ASSEMBLY');
		}
	}else{
		if($row_cek->POS == 1){
			$arrData[$arrNo] = array("kode"=>'OUT');
		}elseif($row_cek->POS == 2){
			$arrData[$arrNo] = array("kode"=>'BEFORE LABEL');
		}elseif($row_cek->POS == 7){
			$arrData[$arrNo] = array("kode"=>'BEFORE LABEL');
		}else{
			$arrData[$arrNo] = array("kode"=>'EXISTS');	
		}
	}
	
	echo json_encode($arrData);
?>
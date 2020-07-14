<?php
	header("Content-type: application/json");
	$id_pallet = isset($_REQUEST['id_pallet']) ? strval($_REQUEST['id_pallet']) : '';
	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$arrData = array();
	$arrNo = 0;
	
	include("../../connect/conn_kanbansys.php");
	/*CEK*/
	$cek = "select count(*) AS JUM,coalesce(max(position),0) AS POS from ZTB_ASSY_HEATING where id_print='".$id_print."'";
	$result_cek = odbc_exec($connect, $cek);
	$row_cek=odbc_fetch_object($result_cek);

	if ($row_cek->JUM == 0){
		/*CEK KE ZTB_ASSY_KANBAN SUDAH TERPENUHI APA BELUM???. */
		$sql= "select count(*) j from 
			(select count(start_date) jum_start,id_print id_start from ztb_assy_kanban where id_print = '".$id_print."' group by id_print) aa
			left outer join
			(select count(end_Date) jum_end,id_print id_end from ztb_assy_kanban where id_print = '".$id_print."' group by id_print) bb
			on aa.id_start = id_end
      		where jum_start = jum_end";
		$result_sql = odbc_exec($connect, $sql);
		$row_sql = odbc_fetch_object($result_sql);

		if($row_sql->j > 0){
			$arrData[$arrNo] = array("kode"=>'IN');
		}else{
			$arrData[$arrNo] = array("kode"=>'ASSEMBLY');
		}
	}else{
		// $qry = "select count(*) JML from ZTB_ASSY_HEATING where id_pallet='".$id_pallet."' AND position=1";
		// $result_qry = oci_parse($connect, $qry);
		// oci_execute($result_qry);
		// $row_qry=oci_fetch_object($result_qry);

		// if($row_qry->JML == 1){
		// 	$cek2 = "select count(*) JML2 from ZTB_ASSY_HEATING where id_pallet='".$id_pallet."' AND position=2";
		// 	$result_cek2 = oci_parse($connect, $cek2);
		// 	oci_execute($result_cek2);
		// 	$row_cek2=oci_fetch_object($result_cek2);

			if($row_cek->POS == 1){
				$arrData[$arrNo] = array("kode"=>'OUT');
			}else{
				$arrData[$arrNo] = array("kode"=>'EXISTS');	
			}
		
	}
	
	echo json_encode($arrData);
?>
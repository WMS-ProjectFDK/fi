<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");
	$arrData = array();
	$arrNo = 0;

	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';

	$cek = "select count(*) as j from (
		select * from ztb_assy_kanban where id_print = $id_print AND id=(select max(id) from ztb_assy_kanban where id_print = $id_print))";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$jum = oci_fetch_object($data_cek);

	if($jum->J == 1){
		$jumlah = "select sum(qty_act_perbox) as selisih_box, sum(qty_act_perpallet) as selisih_pcs
			from ztb_assy_kanban 
			where id_print = $id_print";
		$data_jumlah = oci_parse($connect, $jumlah);
		oci_execute($data_jumlah);
		$hasil_jum = oci_fetch_object($data_jumlah);
		$box = $hasil_jum->SELISIH_BOX;
		$plt = $hasil_jum->SELISIH_PCS;

		$sql = "select id, worker_id1, id_plan, start_date, end_date, assy_line, cell_type, pallet, 
			to_char(tanggal_produksi, 'YYYY-MM-DD') as tanggal_produksi, qty_perpallet, 
	      	qty_act_perpallet, qty_perbox, qty_act_perbox, id_print
			from ztb_assy_kanban 
			where id_print = $id_print AND id=(select max(id) from ztb_assy_kanban where id_print = $id_print)";
		$data = oci_parse($connect, $sql);
		oci_execute($data);

		while ($row = oci_fetch_object($data)){
			array_push($arrData, $row);

			if($box != $arrData[$arrNo]->QTY_PERBOX AND $plt != $arrData[$arrNo]->QTY_PERPALLET AND ! is_null($arrData[$arrNo]->END_DATE)) {
				$arrData[$arrNo]->START_DATE = '';
			}

			$arrData[$arrNo]->QTY_ACT_PERPALLET = $plt;
			$arrData[$arrNo]->QTY_ACT_PERBOX = $box;

			$perpallet = $arrData[$arrNo]->QTY_PERPALLET;
			$perbox = $arrData[$arrNo]->QTY_PERBOX;

			$arrData[$arrNo]->SISA_PCS = $perpallet - $plt;
			$arrData[$arrNo]->SISA_BOX = $perbox - $box;
			
			$arrNo++;
		}
	}else{
		$arrData[$arrNo] = array("START_DATE"=>'', "END_DATE"=>'', "QTY_ACT_PERPALLET"=>0, "SISA_PCS"=>0, "SISA_BOX"=>0);
	}
	echo json_encode($arrData);
?>
<?php
	include("../../connect/conn_kanbansys.php");
	header("Content-type: application/json");
	$arrData = array();
	$arrNo = 0;

	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';

	$cek = "select count(*) as j from ZTB_ASSY_KANBAN w
		where id_print = $id_print AND 
		id=(select max(id) from ztb_assy_kanban where id_print = $id_print)";
	$data_cek = odbc_exec($connect, $cek);
	$jum = odbc_fetch_object($data_cek);

	if($jum->j == 1){
		$jumlah = "select sum(qty_act_perbox) as selisih_box, sum(qty_act_perpallet) as selisih_pcs
			from ztb_assy_kanban 
			where id_print = $id_print";
		$data_jumlah = odbc_exec($connect, $jumlah);
		$hasil_jum = odbc_fetch_object($data_jumlah);
		$box = $hasil_jum->selisih_box;
		$plt = $hasil_jum->selisih_pcs;

		$sql = "select ID, WORKER_ID1, ID_PLAN, START_DATE, END_DATE, ASSY_LINE, CELL_TYPE, PALLET,
			TANGGAL_PRODUKSI, QTY_PERPALLET, QTY_ACT_PERPALLET, QTY_PERBOX, QTY_ACT_PERBOX, ID_PRINT
			from ztb_assy_kanban 
			where id_print = $id_print AND id=(select max(id) from ztb_assy_kanban where id_print = $id_print)";
		$data = odbc_exec($connect, $sql);
		while ($row = odbc_fetch_object($data)){
			array_push($arrData, $row);

			if($box != $arrData[$arrNo]->QTY_PERBOX AND $plt != $arrData[$arrNo]->QTY_PERPALLET AND ! is_null($arrData[$arrNo]->END_DATE)) {
				$arrData[$arrNo]->START_DATE = '';
			}

			$arrData[$arrNo]->QTY_ACT_PERPALLET = $plt;
			$arrData[$arrNo]->QTY_ACT_PERBOX = $box;

			$perpallet = $arrData[$arrNo]->QTY_PERPALLET;
			$perbox = $arrData[$arrNo]->QTY_PERBOX;

			$arrData[$arrNo]->sisa_pcs = $perpallet - $plt;
			$arrData[$arrNo]->sisa_box = $perbox - $box;
			
			$arrNo++;
		}
	}else{
		$arrData[$arrNo] = array("START_DATE"=>'', "END_DATE"=>'', "QTY_ACT_PERPALLET"=>0, "SISA_PCS"=>0, "SISA_BOX"=>0);
	}
	echo json_encode($arrData);
?>
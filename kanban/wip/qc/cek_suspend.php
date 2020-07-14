<?php
	header("Content-type: application/json");
	$id_print = isset($_REQUEST['id_print']) ? strval($_REQUEST['id_print']) : '';
	$arrData = array();
	$arrNo = 0;
	
	include("../../../connect/conn.php");
	/*CEK*/
	$cek = "select count(*) AS JUM from (select aa.assy_line,cell_type,nvl(bb.qty_label+bb.qty_aging+qty_after_lbl,qty_act_perpallet) qty,
		tanggal_produksi lot_number,nvl(bb.qty_label,0),nvl(bb.qty_aging,0),nvl(qty_after_lbl,0) 
		from  (select  max(tanggal_produksi) tanggal_produksi , id_print, assy_line, cell_type,qty_act_perpallet 
		       from ztb_assy_kanban 
		       group by id_print, assy_line, cell_type,qty_act_perpallet) aa 
		left outer join ztb_wip_bat bb on aa.id_print = bb.id_print 
		where aa.id_print = ".$id_print.")";
	$result_cek = oci_parse($connect, $cek);
	oci_execute($result_cek);
	$row_cek=oci_fetch_object($result_cek);

	if ($row_cek->JUM == 0){
		$sql= "select aa.assy_line,cell_type,nvl(bb.qty_label+bb.qty_aging+qty_after_lbl,qty_act_perpallet) qty,
			tanggal_produksi lot_number,nvl(bb.qty_label,0),nvl(bb.qty_aging,0),nvl(qty_after_lbl,0) 
			from  (select  max(tanggal_produksi) tanggal_produksi , id_print, assy_line, cell_type,qty_act_perpallet 
			       from ztb_assy_kanban 
			       group by id_print, assy_line, cell_type,qty_act_perpallet) aa 
			left outer join ztb_wip_bat bb on aa.id_print = bb.id_print 
			where aa.id_print =".$id_print;
		$result_sql = oci_parse($connect, $sql);
		oci_execute($result_sql);
		while ($row_sql=oci_fetch_object($result_sql)){
			$arrData[$arrNo] = array("kode"=>'OK',"QTY"=>$row_sql->qty,"LOT"=>$row_sql->lot_number);	
		}
	}else{
		if($row_cek->POS == 1){
			$arrData[$arrNo] = array("kode"=>'OUT');
		}else{
			$arrData[$arrNo] = array("kode"=>'EXISTS');	
		}
	}
	
	echo json_encode($arrData);
?>
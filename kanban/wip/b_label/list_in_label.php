<?php 
include("../../../connect/conn.php"); 
$sql = "select * from (
	select aa.id_print,aa.assy_line,cell_type,pallet,sum(bb.qty_label) as qty,tanggal_produksi lot_number,replace(upto_date,' ','<br/>') as upto_date 
	from  (select  max(tanggal_produksi) tanggal_produksi , id_print, assy_line, cell_type,pallet 
	       from ztb_assy_kanban 
	       group by id_print, assy_line, cell_type,pallet) aa 
	inner join ztb_wip_bat bb on aa.id_print = bb.id_print
	inner join ztb_assy_heating cc on aa.id_print = cc.id_print
	where position = 3 and (bb.qty_label > -1 AND bb.qty_label <> 0)
	group by aa.id_print,aa.assy_line,cell_type,tanggal_produksi,pallet,upto_date 
	order by upto_date desc
	) where rownum  <= 20";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY;
	$items[$rowno]->QTY = number_format($q);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
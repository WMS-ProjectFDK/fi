<?php 
include("../../connect/conn.php"); 
$sql = "select aa.id_print,aa.assy_line,cell_type,pallet,sum(bb.qty_label) as qty,tanggal_produksi lot_number,aa.id_print, 
	replace(upto_date,' ','<br/>') as upto_date,
	case when bb.qty_label <> 0 then 'NOT USED' else
	  case when bb.qty_suspend <> 0 then 'SUSPEND' else
	    case when bb.qty_after_lbl <> 0 then 'USED' end
	  end
	end as STS, bb.qty_after_lbl
	from  (select  max(tanggal_produksi) tanggal_produksi , id_print, assy_line, cell_type,pallet from ztb_assy_kanban group by id_print, assy_line, cell_type,pallet) aa 
	inner join ztb_wip_bat bb on aa.id_print = bb.id_print
	inner join ztb_assy_heating cc on aa.id_print = cc.id_print
	where position = 3 and bb.qty_label <> 0  
	group by aa.id_print,aa.assy_line,cell_type,tanggal_produksi,pallet,upto_date, bb.qty_label, bb.qty_suspend, bb.qty_after_lbl 
	order by upto_date desc";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY;
	$items[$rowno]->QTY = number_format($q);

	if($items[$rowno]->STS == 'USED'){
		$items[$rowno]->STS = '<span style="color:blue;font-size:12px;"><b>USED ('.number_format($items[$rowno]->QTY_AFTER_LBL).')</b></span>';
	}elseif($items[$rowno]->STS == 'NOT USED'){
		$items[$rowno]->STS = '<span style="color:green;font-size:12px;"><b>NOT USED</b></span>';
	}elseif($items[$rowno]->STS == 'SUSPEND'){
		$items[$rowno]->STS = '<span style="color:red;font-size:12px;"><b>SUSPEND</b></span>';
	}

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
<?php 
include("../../connect/conn.php"); 
$sql = "select a.id_print, a.asy_line as assy_line, b.cell_type, b.pallet, b.qty, b.tanggal_produksi as lot_number,
	replace(a.recorddate,' ','<br/>') as upto_date,
	case when c.qty_label <> 0 then 'NOT USED' else
		  case when c.qty_suspend <> 0 then 'SUSPEND' else
		    case when c.qty_after_lbl <> 0 then 'USED' else 'USED' end
		  end
	end as STS,
	c.qty_after_lbl, a.status, a.remark, ef.qty_box, ef.qty_box_pallet,
	case when a.remark = 'START' OR a.remark is null then 'SELESAIKAN' else 'SUDAH SELESAI' end as action
	from ztb_lbl_trans a
	left join (select max(tanggal_actual) tanggal_produksi, sum(qty_act_perpallet) as qty, id_print, assy_line, cell_type,pallet 
	           from ztb_assy_kanban 
	           group by id_print, assy_line, cell_type,pallet) b on a.id_print = b.id_print
	left join ztb_wip_bat c on a.id_print = c.id_print
	left join ztb_assy_set_pallet ef on a.asy_line = ef.assy_line
	where a.id_print = c.id_print
	and to_date(tanggal,'YYYY-MM-DD') >= (select sysdate-30 from dual)
	order by to_timestamp(a.recorddate, 'YYYY-MM-DD HH24:MI:SS') desc";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY;
	$items[$rowno]->QTY = number_format($q);

	if($items[$rowno]->STS == 'USED'){
		$items[$rowno]->STS = '<span style="color:blue;font-size:11px;"><b>TERPAKAI<br>('.number_format($items[$rowno]->QTY_AFTER_LBL).')</b></span>';
		$items[$rowno]->ACTION = '-';
	}elseif($items[$rowno]->STS == 'NOT USED'){
		$items[$rowno]->STS = '<span style="color:green;font-size:11px;"><b>BELUM<br>TERPAKAI</b></span>';
		if ($items[$rowno]->ACTION == 'SUDAH SELESAI'){
			$items[$rowno]->ACTION = '<span style="color:blue;font-size:11px;"><b>SUDAH<br>SELESAI</b></span>';
		}else{
			$items[$rowno]->ACTION = '<a href="javascript:void(0)" onclick="finish('.$items[$rowno]->ID_PRINT.')" style="color: red;">SELESAIKAN</a>';	
		}
	}elseif($items[$rowno]->STS == 'SUSPEND'){
		$items[$rowno]->STS = '<span style="color:red;font-size:11px;"><b>SUSPEND</b></span>';
		$items[$rowno]->ACTION = '-';
	}

	$action = $items[$rowno]->ACTION;
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
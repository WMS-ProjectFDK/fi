<?php
$labelline = isset($_REQUEST['labelline']) ? strval($_REQUEST['labelline']) : ''; 
include("../../connect/conn.php"); 
$sql = "select * from (
		select a.labelline,shift,a.id_print,
		to_number(case when a.status = '2' then to_char(a.qty_in_antrian) else to_char(a.qty-b.qty_antri) end) as qty,
	  a.recorddate, a.tanggal, a.qty_terpakai, a.asy_line,a.grade, a.ng_qty, a.status, a.lotdate, a.remark,
		case when a.status = '0' and a.remark = 'FINISH' then 'RESULT OF BATTERY' 
		     when a.status = '2' and to_char(a.qty_in_antrian) <> '0' then 'RESULT OF BATTERY'
		     else 'VIEW RESULT' 
		end as result, cast(a.ROWID as varchar(50)) ROW_ID
		from ztb_lbl_trans a
    inner join (select id_print, sum(qty_in_antrian) as qty_antri from ztb_lbl_trans group by id_print) b on a.id_print = b.id_print
		where (a.status=0 or a.status=2) 
		and replace(a.labelline,'#','-') = '$labelline'
		and a.remark = 'FINISH'
		order by TO_TIMESTAMP(a.recorddate,'YYYY-MM-DD HH24:MI:SS') asc
	)
	where qty > qty_terpakai + ng_qty and rownum <= 3";
/*select * from (
		select labelline,shift,id_print,
		to_number(case when status = '2' then to_char(qty_in_antrian) else to_char(qty) end) as qty,
	  recorddate, tanggal, qty_terpakai, asy_line,grade, ng_qty, status, lotdate, remark,
		case when status = '0' and remark = 'FINISH' then 'RESULT OF BATTERY' 
		     when status = '2' and to_char(qty_in_antrian) <> '0' then 'RESULT OF BATTERY'
		     else 'VIEW RESULT' 
		end as result--, rowid as r_id
		from ztb_lbl_trans
		where (status=0 or status=2) 
		and replace(labelline,'#','-') = '$labelline'
		and remark = 'FINISH'
		order by TO_TIMESTAMP(recorddate,'YYYY-MM-DD HH24:MI:SS') asc
	)
	where qty > qty_terpakai+ng_qty and rownum <= 3";*/

$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$q = $items[$rowno]->QTY;
	$items[$rowno]->QTY = number_format($q);

	$action = $items[$rowno]->RESULT;

	if($action == 'RESULT OF BATTERY'){
		$items[$rowno]->RESULT = '<a href="javascript:void(0)" onclick="hasil_bat('.$items[$rowno]->ID_PRINT.','.$q.')" style="color: green;">RESULT</a>';
	}else{
		$items[$rowno]->RESULT = $action;
	}
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
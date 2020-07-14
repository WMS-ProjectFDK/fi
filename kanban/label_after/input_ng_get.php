<?php
$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$shift = isset($_REQUEST['shift']) ? strval($_REQUEST['shift']) : '';

include("../../connect/conn.php"); 

$sql = "select a.labelline, a.shift, a.id_print, a.qty, a.recorddate, a.tanggal, a.asy_line, a.grade, cast(a.ROWID as varchar(50)) ROW_ID,
	case when b.jum >= 1 then 1 else 0 end as sts
	from ztb_lbl_trans a
  	left join (select distinct id_print, labelline, count(*) as jum from ztb_lbl_trans_ng group by id_print, labelline) b 
  	on a.id_print=b.id_print and a.labelline=b.labelline
	where replace(a.labelline,'#','-') = '$line' 
	and to_date(a.tanggal,'YYYY-MM-DD') = to_date('$date_akhir','YYYY-MM-DD') 
	and a.shift = '$shift'
	order by to_date(a.recorddate,'YYYY-MM-DD HH24:MI:SS')";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
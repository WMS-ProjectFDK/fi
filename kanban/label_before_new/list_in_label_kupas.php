<?php 
include("../../connect/conn.php"); 
$sql = "select grade, qty, replace(recorddate,' ','<br/>') as UPTO_DATE, shift, LABELLINE, lotdate,
	case when remark = 'START' OR remark is null then 'SELESAIKAN' else 'SUDAH SELESAI' end as action,
  	to_char(to_date(recorddate,'YYYY-MM-DD HH24:MI:SS'),'YYYYMMDDHH24MISS') as id
	from ztb_lbl_trans
	where id_print=0
	order by to_timestamp(recorddate, 'YYYY-MM-DD HH24:MI:SS') desc";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);

	$row = $items[$rowno]->ID;
	$a = "'".$row."'";

	$q = $items[$rowno]->QTY;
	$items[$rowno]->QTY = number_format($q);

	if ($items[$rowno]->ACTION == 'SUDAH SELESAI'){
		$items[$rowno]->ACTION = '<span style="color:blue;font-size:11px;"><b>SUDAH<br>SELESAI</b></span>';
	}else{
		$items[$rowno]->ACTION = '<a href="javascript:void(0)" onclick="finish_kupas('.$a.')" style="color: red;">SELESAIKAN</a>';
	}

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
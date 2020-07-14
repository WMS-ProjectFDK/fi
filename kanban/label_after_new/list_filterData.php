<?php
$date = isset($_REQUEST['date']) ? strval($_REQUEST['date']) : ''; 
$ckend = isset($_REQUEST['ckend']) ? strval($_REQUEST['ckend']) : ''; 
include("../../connect/conn.php"); 

if ($ckend != "true"){
	$where = "where c.mulai <= to_date('$date','YYYY-MM-DD') and c.enddate is not null";
}else{
	$where = "where c.mulai <= to_date('$date','YYYY-MM-DD') and c.enddate is null";
}

$sql = "select * from 
			(select c.id as IDRecord,z.type_item,z.id,z.wo_no,z.item_no,z.brand,z.date_code,z.plt_no, z.grade,
			 case when c.startdate is null then 'START' else replace(c.startdate,' ','<br>') end as startdate,
			 c.battery_in, z.qty_prod,
			 case when c.enddate is null then '-' else replace(c.enddate,' ','<br>') end enddate,
			 mulai,nvl(z_qty,0) z_qty, cast(c.ROWID as varchar(50)) ROW_ID
			 from ztb_l_plan z  
			 inner join ztb_kanban_lbl c on z.id = c.idkanban 
			 $where
			 order by c.id desc)c 
		WHERE ROWNUM <= 150";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$a = $items[$rowno]->ID;
	$b = "'".$items[$rowno]->WO_NO."'";
	$c = $items[$rowno]->PLT_NO;
	$d = $items[$rowno]->QTY_PROD;
	$z = $items[$rowno]->ENDDATE;
	$e = "'".$items[$rowno]->ROW_ID."'";
	
	$q_prod = $items[$rowno]->QTY_PROD;
	$q_in = $items[$rowno]->BATTERY_IN;
	
	if($z == 'FINISH'){
		$a = $items[$rowno]->ID;
		$b = "'".$items[$rowno]->WO_NO."'";
		$c = $items[$rowno]->PLT_NO;
		$d = $q_prod;
		$e = $items[$rowno]->IDRECORD;
		$f = "'".$items[$rowno]->GRADE."'";

		if ($f == "'C1'"){
			$f = "'C01'";
		}
	}

	$items[$rowno]->QTY_PROD = number_format($q_prod);
	$items[$rowno]->BATTERY_IN = number_format($q_in);
	$items[$rowno]->QTY_S = number_format($q_prod).' / '.number_format($q_in);	

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
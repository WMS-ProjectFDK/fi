<?php
$worker = isset($_REQUEST['worker']) ? strval($_REQUEST['worker']) : ''; 
include("../../connect/conn.php"); 

$sql = "select * from 
			(select c.id as IDRecord,z.type_item,z.id,z.wo_no,z.item_no,z.brand,z.date_code,z.plt_no, z.grade,
			 case when c.startdate is null then 'START' else replace(c.startdate,' ','<br>') end as startdate,
			 c.battery_in, z.qty_prod,
			 case when c.enddate is null then 'FINISH' else replace(c.enddate,' ','<br>') end enddate,
			 mulai,nvl(z_qty,0) z_qty, cast(c.ROWID as varchar(50)) ROW_ID
			 from ztb_l_plan z  
			 inner join ztb_kanban_lbl c on z.id = c.idkanban 
			 where c.worker_id = '$worker'
			 order by c.id desc)c 
		WHERE ROWNUM <= 30";
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

	if($z == 'FINISH'){
		$items[$rowno]->DELETE = '<a href="javascript:void(0)" onclick="deleteRow('.$a.','.$b.','.$c.','.$d.','.$e.')" style="color: red;">DELETE WO</a>';
	}else{
		$items[$rowno]->DELETE = '-';
	}
	
	$q_prod = $items[$rowno]->QTY_PROD;
	$q_in = $items[$rowno]->BATTERY_IN;

	/*$a = $items[$rowno]->STARTDATE;
	if($a == 'START'){
		$items[$rowno]->STARTDATE = '<a href="javascript:void(0)" onclick="start('.$items[$rowno]->ID.')" style="color: blue;"><b>'.$a.'</b></a>'; 
	}*/

	
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

		$items[$rowno]->ENDDATE = '<a href="javascript:void(0)" onclick="finish('.$a.','.$b.','.$c.','.$d.','.$e.','.$f.')" style="color: blue;"><b>'.$z.'</b></a>'; 
	}

	$items[$rowno]->QTY_PROD = number_format($q_prod);
	$items[$rowno]->BATTERY_IN = number_format($q_in);
	$items[$rowno]->QTY_S = number_format($q_prod).' / '.number_format($q_in);	

	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);
?>
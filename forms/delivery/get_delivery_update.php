<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$ex_factory = isset($_REQUEST['ex_factory']) ? strval($_REQUEST['ex_factory']) : '';
$ex_factory_z = isset($_REQUEST['ex_factory_z']) ? strval($_REQUEST['ex_factory_z']) : '';


$sql_upd = "update indication set remark='' where answer_no in 
	(select distinct a.answer_no from indication a
	 inner join answer b on a.answer_no = b.answer_no
	 left join production_income c on b.work_no = c.wo_no
	 where a.commit_date is null and a.remark = 1
	 and convert(varchar(6),a.ex_factory,112) <= (select max(this_month) from whinventory)
	 and convert(varchar(6),a.ex_factory,112) >= (select max(last_month) last_month from whinventory) 
	 and a.ex_factory <= getdate()
	 and (c.slip_type = 80 OR a.slip_type is null)
	)";
$data_upd = sqlsrv_query($connect, strtoupper($sql_upd));

$sql  = " select" ;
$sql .= "  dos.do_no," ;
$sql .= "  cast(doh.do_date as varchar(10)) do_date," ;
$sql .= "  ind.answer_no," ;
$sql .= "  ind.so_no," ;
$sql .= "  soh.customer_po_no," ;
$sql .= "  cast(ind.ex_factory as varchar(10)) ex_factory," ;
$sql .= "  sod.item_no," ;
$sql .= "    i.item," ;
$sql .= "    i.description," ;
$sql .= "  ind.qty," ;
$sql .= "  ind.container_no," ; #(add Ver1.0)
$sql .= "  ind.seal_no," ;      #(add Ver1.0)
$sql .= "  un.unit," ;

$sql .= "  wh.this_inventory," ;
$sql .= "  case when wh.this_inventory < ind.qty then 'BELUM BISA DI PROSES' else 'SUDAH BISA DIPROSES' END STS_INV," ;
$sql .= "  case when wh.this_inventory < ind.qty then 0 else 1 END V_INV," ;

$sql .= "  isnull(trn.kuraire_perWO,0) as kuraire_perWO," ;
$sql .= "  case when isnull(trn.kuraire_perWO,0) < ind.qty then 'BELUM BISA DI PROSES' else 'SUDAH BISA DIPROSES' END STS_KUR," ;
$sql .= "  case when isnull(trn.kuraire_perWO,0) < ind.qty then 0 else 1 END V_KUR," ;

$sql .= "  soh.customer_code," ;
$sql .= "  c.company customer " ;
$sql .= "  from indication ind " ;
$sql .= "  left join     do_so      dos on ind.answer_no = dos.answer_no " ;
$sql .= "  left join     do_header  doh on dos.do_no = doh.do_no " ;
$sql .= "  left join     so_details sod on ind.so_no = sod.so_no and ind.so_line_no = sod.line_no " ;
$sql .= "  left join     so_header  soh on sod.so_no = soh.so_no " ;
$sql .= "  left join     company  c on soh.customer_code = c.company_code " ;
$sql .= "  left join     item     i on sod.item_no = i.item_no " ;
$sql .= "  left join     unit     un on i.uom_q = un.unit_code " ;
$sql .= "  left join     whinventory     wh on wh.item_no = i.item_no " ;
$sql .= "  left join	   answer ans on ind.ANSWER_NO = ans.ANSWER_NO " ;
$sql .= "  left join	   (select item_no, wo_no, isnull(sum(slip_quantity),0) as kuraire_perWO from [TRANSACTION] " ;
$sql .= "  				    where SLIP_TYPE=80 " ;
$sql .= "  				    group by item_no, wo_no) trn on ans.WORK_NO=trn.WO_NO and sod.item_no=trn.item_no " ;
$sql .= "  where ind.commit_date is null and dos.do_no is not null " ;
$sql .= "  and ind.ex_factory BETWEEN '$ex_factory' AND '$ex_factory_z' ";
$sql .= "  order by c.company,dos.do_no,ind.answer_no" ;
//echo $sql;

$data_cek = sqlsrv_query($connect, strtoupper($sql));

$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);

	$q = $items[$rowno]->QTY; 
	$items[$rowno]->QTY = number_format($q);

	$wh = $items[$rowno]->THIS_INVENTORY; 
	$items[$rowno]->THIS_INVENTORY = number_format($wh);

	$kr = $items[$rowno]->KURAIRE_PERWO; 
	$items[$rowno]->KURAIRE_PERWO = number_format($kr);

	$it = "'".$items[$rowno]->ANSWER_NO."'";
	$items[$rowno]->INPUT = '<a href="javascript:void(0)" onclick="input_container('.$it.')">SET</a>';

	$a = $items[$rowno]->STS_KUR;

	$kur = $items[$rowno]->V_KUR;

	if ($kur != '0'){
		$items[$rowno]->INPUT = '<a href="javascript:void(0)" onclick="input_container('.$it.')">SET</a>';
		$items[$rowno]->STS = '<span style="color:blue;font-size:11px;"><b>'.$a.'</b></span>';
	}else{
		$items[$rowno]->INPUT = '';
		$items[$rowno]->STS = '<span style="color:red;font-size:11px;"><b>'.$a.'</b></span>';
	}


	// if($a=='SUDAH BISA DIPROSES'){
	// 		$items[$rowno]->STS = '<span style="color:blue;font-size:11px;"><b>'.$a.'</b></span>';
	// }else{
	// 		$items[$rowno]->STS = '<span style="color:red;font-size:11px;"><b>'.$a.'</b></span>';
	// }
	
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);

?>

<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$ex_factory = isset($_REQUEST['ex_factory']) ? strval($_REQUEST['ex_factory']) : '';
$ex_factory_z = isset($_REQUEST['ex_factory_z']) ? strval($_REQUEST['ex_factory_z']) : '';

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
$sql .= "  soh.customer_code," ;
$sql .= "  wh.this_inventory," ;
$sql .= "  case when wh.this_inventory < ind.qty then 'BELUM BISA DI PROSES' else 'SUDAH BISA DIPROSES' END STS," ;
$sql .= "  c.company customer " ;
$sql .= " from indication ind," ;
$sql .= "      do_so      dos," ;
$sql .= "      do_header  doh," ;
$sql .= "      so_header  soh," ;
$sql .= "      so_details sod," ;
$sql .= "      company  c, " ;
$sql .= "      unit     un, " ;
$sql .= "      item     i,  " ;
$sql .= "      whinventory     wh  " ;
$sql .= " where ind.commit_date is not null " ;
$sql .= "   and ind.answer_no = dos.answer_no  " ;
$sql .= "   and dos.do_no = doh.do_no  " ;
$sql .= "   and ind.so_no = sod.so_no  " ;
$sql .= "   and ind.so_line_no = sod.line_no  " ;
$sql .= "   and sod.so_no = soh.so_no  " ;
$sql .= "   and sod.item_no = i.item_no  " ;
$sql .= "   and i.uom_q = un.unit_code  " ;
$sql .= "   and i.item_no = wh.item_no  " ;
$sql .= "   and soh.customer_code = c.company_code  " ;
// $sql .= "   and ind.ex_factory = '$ex_factory'";
$sql .= "   and ind.ex_factory BETWEEN '$ex_factory' AND '$ex_factory_z' ";
#$sql .= " order by c.company,dos.do_no" ;
$sql .= " order by c.company,dos.do_no,ind.answer_no" ; #(mod Ver1.0)

$data_cek = sqlsrv_query($connect, strtoupper($sql));

// echo $sql;

$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$it = "'".$items[$rowno]->ANSWER_NO."'";
	$items[$rowno]->INPUT = '<a href="javascript:void(0)" onclick="input_container('.$it.')">SET</a>';
	$a = $items[$rowno]->STS;
	if($a=='SUDAH BISA DIPROSES'){
			$items[$rowno]->STS = '<span style="color:blue;font-size:11px;"><b>'.$a.'</b></span>';
	}else{
			$items[$rowno]->STS = '<span style="color:red;font-size:11px;"><b>'.$a.'</b></span>';
	}
	
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);

?>

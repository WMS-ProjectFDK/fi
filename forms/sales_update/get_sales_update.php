<?php
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");

$do_date = isset($_REQUEST['do_date']) ? strval($_REQUEST['do_date']) : '';


$sql  = " select" ;
$sql .= "  doh.do_type," ;
$sql .= "  doh.do_no," ;
$sql .= "  doh.customer_code," ;
$sql .= "  c.company customer," ;
$sql .= "  cast(doh.do_date as varchar(20)) do_date," ;
$sql .= "  case doh.do_type when 0 then cast(doh.do_date as varchar(10)) else cast(doh.etd as varchar(10))  end bl_Date, " ;
$sql .= "  cu.curr_mark," ;
$sql .= "  dod.itm_num," ;
$sql .= "  doh.amt_o amt_o," ;
$sql .= "  doh.amt_l amt_l " ;
$sql .= " from do_header doh," ;
$sql .= "      (select do_no,count(*) itm_num from do_details group by do_no) dod," ;
$sql .= "      company  c, " ;
$sql .= "      currency cu  " ;
$sql .= " where doh.do_no = dod.do_no  " ;
$sql .= "   and doh.customer_code = c.company_code  " ;
$sql .= "   and doh.curr_code = cu.curr_code  " ;
$sql .= "   and doh.ship_end_flg is null " ;
$sql .= "   and do_date = '$do_date'" ;
$sql .= " order by do_type,c.company,doh.do_no" ;

$data_cek = sqlsrv_query($connect, strtoupper($sql));

//echo $sql;


$items = array();
$rowno=0;

while($row = sqlsrv_fetch_object($data_cek)){
	array_push($items, $row);
	$it = "'".$items[$rowno]->DO_NO."'";
	$items[$rowno]->INPUT = '<a href="javascript:void(0)" onclick="input_bl_date('.$it.')">SET</a>';
	$rowno++;
}

$result["rows"] = $items;
echo json_encode($result);

?>

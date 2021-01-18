<?php

//http://localhost:8088/fi/forms/warehouse/inventory_excel.php?cmbBln=202008&cmbBln_txt=08-2020&src=&rdo_sts=check_WP

$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
$rdo_sts = isset($_REQUEST['rdo_sts']) ? strval($_REQUEST['rdo_sts']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
$item = '';

session_start();
include("../../../connect/conn.php");

// if($rdo_sts=='check_all'){
//     $stock = "";
// }elseif($rdo_sts=='check_PM'){
//     $stock = "b.stock_subject_code='2' and ";
// }elseif($rdo_sts=='check_FG'){
//     $stock = "b.stock_subject_code='5' and ";
// }elseif($rdo_sts=='check_WP'){
//     $stock = "b.stock_subject_code='0' and ";
// }elseif($rdo_sts=='check_WIP'){
//     $stock = "b.stock_subject_code='3' and ";
// }elseif($rdo_sts=='check_CSP'){
//     $stock = "b.stock_subject_code='6' and ";
// }elseif($rdo_sts=='check_RM'){
//     $stock = "b.stock_subject_code='1' and ";
// }elseif($rdo_sts=='check_semiFG'){
//     $stock = "b.stock_subject_code='4' and ";
// }elseif($rdo_sts=='check_material2'){
//     $stock = "b.stock_subject_code='7' and ";
// }elseif($rdo_sts==''){
//     $stock = "b.stock_subject_code is null and ";
// }
//L-DB-00170-00
if ($src !='') {
    $where="where a.item_no like '%$src%' AND (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}else{
    $where ="where  (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}
//$where="where a.item_no like '%L-DB-00170-00%' AND (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";

$sql = "select distinct max(this_month) as this_month, max(last_month) as last_month from whinventory";
$data = sqlsrv_query($connect, strtoupper($sql));
$dt_result = sqlsrv_fetch_object($data);


if($dt_result->THIS_MONTH == $cmbBln){
    $sql = "select a.item_no,  DESCRIPTION , c.unit, a.this_month, 
        cast(a.this_inventory as bigint) this_inventory, 
        cast(a.receive1 as bigint) receive1,
        cast(a.other_receive1 as bigint)other_receive1,
        cast(a.issue1 as bigint) as issue1,
        cast(a.other_issue1 as bigint) other_issue1,
        
       (select cast(sum(slip_quantity) as bigint) from [sp_transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act,
       cast(a.last_inventory as bigint) last_inventory
       from sp_whinventory a
        inner join sp_item b on a.item_no=b.item_no
        inner join sp_unit c on b.uom_q=c.unit_code
        $where order by b.item asc, b.description asc"; 
    
}else{
    $sql = "select a.item_no, DESCRIPTION, c.unit, a.this_month, 
    cast(a.last_inventory as bigint)as this_inventory, 
    cast(receive2 as bigint)as receive1,
    cast(other_receive2 as bigint)as other_receive1,
    cast(issue2 as bigint)as issue1,
    cast(other_issue2 as bigint)as other_issue1,
    (select  cast(sum(slip_quantity) as bigint) from [sp_transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act,
    cast(last2_inventory as bigint)as last_inventory,
    
        from sp_whinventory a
        inner join sp_item b on a.item_no=b.item_no
        inner join sp_unit c on b.uom_q=c.unit_code
        $where order by b.item asc, b.description asc"; 
  
}

$out = "ITEM NO,DESCRIPTION, UNIT, ACCOUNT MONTH, THIS_INVENTORY, RECEIVE1,OTHER_RECEIVE1,ISSUE1,OTHER_ISSUE1,TOTAL TRANSACTION,LAST_INVENTORY" ;
$out .= "\n";
$results = sqlsrv_query($connect, strtoupper($sql));
    while ($l = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
        
        foreach($l AS $key => $value){
            
            $pos = strpos(strval($value), '"');
            if ($pos !== false) {
                $value = str_replace('"', '\"', $value);
            }
            $out .= '"'.$value.'",';
        }
        $item =  $l['ITEM_NO'];
        $out .= "\n";
     
     
        
        // if( $l['RECEIVE1'] > 0 || $l['OTHER_RECEIVE1'] > 0 || $l['ISSUE1'] > 0 || $l['OTHER_ISSUE1'] > 0){
        //     $sql2 = "
        //     select '-',',[DETAIL]',cast(t.slip_date as varchar(11))  slip_date,slip_no, t.slip_type,
        //     case sl.table_position when 1 then cast(t.slip_quantity as bigint) else 0 end  receive,
        //     case sl.table_position when 2 then cast(t.slip_quantity as bigint) else 0 end other_receive,
        //     case sl.table_position when 3 then cast(t.slip_quantity as bigint) else 0 end issue,
        //     case sl.table_position when 4 then cast(t.slip_quantity as bigint) else 0 end  other_issue
        //     from [sp_transaction] t, sp_item i, section sc, sp_unit u, stock_subject st,sliptype sl, currency cu
        //     where t.item_no = i.item_no  and i.delete_type  is null and t.section_code = sc.section_code  and t.unit_stock = u.unit_code 
        //     and t.section_code = sc.section_code  and t.slip_type = sl.slip_type 
        //     and t.stock_subject_code = st.stock_subject_code  and t.curr_code = cu.curr_code  
        //         and t.section_code = '100' and t.item_no = '".$l['ITEM_NO']."' and  t.accounting_month = '".$cmbBln."' 
        //         order by t.slip_date,t.slip_type,t.SLIP_NO ";
        
                
        //     $out .= ",[DETAIL],SLIP DATE,SLIP_NO, SLIP_TYPE, RECEIVE, OTHER RECEIVE, ISSUE,OTHER_ISSUE" ;
        //     $out .= "\n";
            
        //     $results2 = sqlsrv_query($connect, strtoupper($sql2));
        //     while ($l2 = sqlsrv_fetch_array($results2, SQLSRV_FETCH_ASSOC)) {
                
        //         foreach($l2 AS $key2 => $value2){
                   
        //             $pos2 = strpos(strval($value2), '"');
        //             if ($pos2 !== false) {
        //                 $value2 = str_replace('"', '\"', $value2);
        //             }
        //             $out .= '"'.$value2.'",';
                    
                    
        //         }
               
        //         $out .= "\n";
        //     }
        //     $out .= "\n";


        // }
       


        

        
        
        
    }
   // echo $sql2;
  header("Content-type: text/x-csv");
  header("Content-Disposition: attachment; filename=inventory_report_summary.csv");
  echo $out;
?>
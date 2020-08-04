<?php
include('../class/phpqrcode/phpqrcode.php'); 
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';     
ob_start("callback"); 
     
// here DB request or some processing 
$query = "select ID,wo_no,st.lower_item_no as item_no, plt_no,
CEIL((round((quantity * qty_prod) / quantity_base,0) + 
Ceil((quantity * qty_prod) / quantity_base  * failure_rate/100)) / nvl(bundle_qty,1)) * nvl(bundle_qty,1) 
as QTY_REQ
from structure st
inner join  (select ID, wo_no, plt_no, aa.item_no, qty_prod,bb.bom_level from ztb_m_plan aa
inner join mps_header bb on aa.wo_no = bb.work_order) r on st.upper_item_no = r.item_no
left join item i on st.lower_item_no=i.item_no
left join item i_u on st.upper_item_no=i_u.item_no
left join unit u on i.uom_q=u.unit_code
left join whinventory w on i.item_no=w.item_no
left join (select item_no,sum(qty) qty from ztb_item_book group by item_no) zz on i.item_no = zz.item_no
left outer join ztb_safety_stock c on st.lower_item_no = c.item_no and period = '2' and year = 2019
where st.level_no = r.bom_level and id = '$id'
and st.lower_item_no = i.item_no 
and lower_item_no < '9999999' 
and lower_item_no > 2000000
and lower_item_no not in  ('2116095' ,'2116102' ,'2126023' ,'2126036' ,'2126040' ,'2126041' ,'2126052' ,'2145001' ,'2145008' ,'2150002'
                          ,'2150003' ,'2150007' ,'2150009' ,'2150010' ,'2216120' ,'2216215' ,'2226017' ,'2226079' ,'2226080' ,'2245006' 
                          ,'2245012' ,'2245013' ,'2245036' ,'2250001' ,'2250002' ,'2250012' ,'2250022' ,'2250023' ,'2250024' ,'2250025'
                          ,'2250026' ,'2250027' ,'2317002' ,'2322002' ,'2600001' ,'2600006' ,'2600007' ,'2600009' ,'2600053' ,'2600068' 
                          ,'2600164' ,'2600183' ,'12600165','2150012' ,'2150013' ,'2150010' ,'2116013' ,'2216048')
order by lower_item_no";

$wo = "";
$plt_no ="";

$result = oci_parse($connect, $query);
oci_execute($result);

while ($data=oci_fetch_object($result)){
    $qty = 1;
    if($data->QTY_REQ != 0){
        $qty = $data->QTY_REQ;
    };
    $wo = $data->WO_NO;
    $plt_no = $data->PLT_NO;

    $string .= ' , '.$data->ITEM_NO;
    $string .= ' , '.$qty;

}

$codeText = $id.' , '.$wo.' , '.$plt_no.$string; 
 

// end of processing here 
$debugLog = ob_get_contents(); 
ob_end_clean(); 
 
// outputs image directly into browser, as PNG stream 
// QRcode::png($codeText);
QRcode::png($codeText, $tempDir, QR_ECLEVEL_L, 2);
?>
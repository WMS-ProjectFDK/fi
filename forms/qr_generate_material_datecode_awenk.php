<?php
include('../class/phpqrcode/phpqrcode.php'); 
ini_set('memory_limit','-1');
include("../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';     
ob_start("callback"); 
     
// here DB request or some processing 
$query = "select rpad(cast(lower_item_no as varchar(10)),10,' ') || 
    rpad(cast(date_code as varchar(10)),10,' ') ||
    lpad(cast(wo_no as varchar(28)),28,' ') ||
    rpad(cast(item_no as varchar(10)),10,' ') as qr_code
    from (
    select distinct ID, wo_no, date_code, lower_item_no, b.item_no,
    CEIL((round((quantity * qty_prod) / quantity_base,0) + Ceil((quantity * qty_prod) / quantity_base  * failure_rate/100)) / nvl(bundle_qty,1)) * nvl(bundle_qty,1) as Qty,
    (select case when item like '%TOP%TR%' or item like '%OUTER%' then item_no else 1 end from item where item_no = a.lower_item_no) as item
    from structure a 
    inner join (Select ID,WO_No ,ztb_m_plan.Item_No,Qty_Prod,date_prod,date_code from ztb_m_plan) b on a.upper_item_no = b.item_no 
    left outer join ztb_safety_stock c on a.lower_item_no = c.item_no and year = 'MSTR'
    where level_no = (select max(level_no) from structure where upper_item_no = b.item_no) 
    and id = '$id' 
    and lower_item_no < '9999999' and lower_item_no > 2000000 
    and lower_item_no not in ('2116095' ,'2116102' ,'2126023' ,'2126036' ,'2126040' ,'2126041' ,'2126052' ,'2145001' ,'2145008' ,'2150002'
                             ,'2150003' ,'2150007' ,'2150009' ,'2150010' ,'2216120' ,'2216215' ,'2226017' ,'2226079' ,'2226080' ,'2245006' 
                             ,'2245012' ,'2245013' ,'2245036' ,'2250001' ,'2250002' ,'2250012' ,'2250022' ,'2250023' ,'2250024' ,'2250025'
                             ,'2250026' ,'2250027' ,'2317002' ,'2322002' ,'2600001' ,'2600006' ,'2600007' ,'2600009' ,'2600053' ,'2600068' 
                             ,'2600164' ,'2600183' ,'12600165','2150012' ,'2150013' ,'2150010' ,'2116013' ,'2216048')
    ) where item <> 1";

$id='';

$result = oci_parse($connect, $query);
oci_execute($result);

while ($data=oci_fetch_object($result)){
    $id = $data->QR_CODE;
}

$codeText = $id;
 
// end of processing here 
$debugLog = ob_get_contents(); 
ob_end_clean(); 
 
// outputs image directly into browser, as PNG stream 
// QRcode::png($codeText);
QRcode::png($codeText, $tempDir, QR_ECLEVEL_L, 2);
?>
<?php
$filename = "json/json_item_to_DI_MRP.json";
$string = file_get_contents($filename);
$json_a = json_decode($string, true);
$n = '';

foreach ($json_a as $person_name => $person_a) {
    $n.=$person_a['item_no'].',';
}

$na = substr($n,0,-1);
$sql = "select * from item where item_no in ($na)";
echo json_encode($sql);
?>
<?php
$line = isset($_REQUEST["line"]) ? strval($_REQUEST["line"]) : "";

$items = array();
$rowno = 0;
include("../../connect/conn_kanbansys.php");
$sql = "select a.id_part, a.id_machine, b.machine, a.line, a.nama_part, a.unit_qty, a.tgl_ganti, a.lifetime,
    a.current_lifetime, a.estimation_tgl_ganti as estimation_replacment, a.status 
    from assembly_part a
    left outer join assembly_line_master b on a.id_machine = b.id_machine and a.line = b.line
    where replace(a.line,'#','-') = '$line' and a.status <> 'OK'
    order by id_machine asc";

$rs = odbc_exec($connect,$sql);
while($row = odbc_fetch_object($rs)){
    array_push($items, $row);

    $current_lifetime = $items[$rowno]->current_lifetime;
    $items[$rowno]->lifetime_c = number_format($current_lifetime);
    
    $lifetime = $items[$rowno]->lifetime;
    $items[$rowno]->lifetime_r = number_format($lifetime);

    $rowno++;
}
odbc_close($connect);
echo json_encode($items);
?>
<?php
$line = isset($_REQUEST["line"]) ? strval($_REQUEST["line"]) : "";
if($line == 'LR03-1'){
    $lineAssy = "LR03#1";
}elseif ($line == 'LR03-2') {
    $lineAssy = "LR03#2";
}elseif ($line == 'LR6-1') {
    $lineAssy = "LR06#1";
}elseif ($line == 'LR6-2') {
    $lineAssy = "LR06#2";
}elseif ($line == 'LR6-3') {
    $lineAssy = "LR06#3";
}elseif ($line == 'LR6-4') {
    $lineAssy = "LR06#4(T)";
}elseif ($line == 'LR6-6') {
    $lineAssy = "LR06#6";
}

$items = array();
$rowno = 0;
include("../../connect/conn_kanbansys.php");
$sql = "select a.id_part, a.id_machine, b.machine, a.line, a.nama_part, a.unit_qty, a.tgl_ganti, a.lifetime, a.leadtime_week, b.proporsi
    from assembly_part a
    left outer join assembly_line_master b on a.id_machine = b.id_machine and a.line = b.line
    where replace(a.line,'#','-') = '$line'
    order by id_machine asc";

$rs = odbc_exec($connect,$sql);
while($row = odbc_fetch_object($rs)){
    array_push($items, $row);
    $rowno++;
}
odbc_close($connect);

include("../../connect/conn.php");
for ($i=0; $i< count($items) ; $i++) { 
    $tgl_ganti = $items[$i]->tgl_ganti;
    $proporsi = $items[$i]->proporsi;
    $lifetime_o = $items[$i]->lifetime;
    $leadtime_week = $items[$i]->leadtime_week;

    $qry = "select nvl(sum(qty_act_perpallet),1) as QTY FROM ztb_assy_kanban
        WHERE assy_line='$lineAssy'
        AND tanggal_produksi BETWEEN TO_DATE('$tgl_ganti','YYYY-MM-DD') AND (SELECT SYSDATE FROM DUAL)";
    //echo $qry;
    $data = oci_parse($connect, $qry);
    oci_execute($data);
    $r = oci_fetch_object($data);

    $lifetime_c = $r->QTY * $proporsi;

    //START estimation_date
    $selisih = ((abs(strtotime ($tgl_ganti) - strtotime (date('d-m-Y'))))/(60*60*24)) / 7;
    $jum_hari = ceil(($lifetime_o / ($lifetime_c/$selisih))*7);
    $days = '+'.$jum_hari.' days';
    $estimation_date = date("Y-m-d", strtotime("+".$jum_hari." days", strtotime("$tgl_ganti")));
    //END estimation_date

    $lead = $lifetime_o - ($leadtime_week*($lifetime_c/$selisih));
    $cek_lead = ceil($leadtime_week*($lifetime_c/$selisih));
    $leadFix = $lifetime_c+$cek_lead;
    $cek_n = $lifetime_o-$cek_lead;

    if ($lifetime_c < $cek_n){
        $status = 'OK';
        $items[$i]->status = 'OK';
    }else if($lifetime_c >= $lifetime_o){
        $status = 'REPLACE';
        $items[$i]->status = 'REPLACE';
    }else if($leadFix >= $lifetime_o){
        $status = 'ORDER';
        $items[$i]->status = 'ORDER';
    }

    $items[$i]->estimation_replacment = $estimation_date;
    $items[$i]->lifetime_c_o = $lifetime_c;
    $mach = $items[$i]->machine;
    $part = $items[$i]->nama_part;
    $lt = $items[$i]->lifetime;
}
oci_close($connect);

include("../../connect/conn_kanbansys.php");
for ($j=0; $j<count($items); $j++) { 
    $id_part = $items[$j]->id_part;
    $a = $items[$j]->lifetime_c_o;
    $b = $items[$j]->estimation_replacment;
    $c = $items[$j]->status;
    $upd = "update assembly_part set current_lifetime=$a, estimation_tgl_ganti='$b', status='$c', update_estimation = GETDATE()
        where id_part=$id_part and replace(line,'#','-') = '$line'";
    $rsUpd = odbc_exec($connect,$upd);
}
?>
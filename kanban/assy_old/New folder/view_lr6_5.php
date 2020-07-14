<?php
include("../../connect/conn.php");

if(intval(date('H')) < 7){
    $start = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))." 07:00:00";
    $end = date('Y-m-d')." 07:00:00";
}else{
    $start = date('Y-m-d')." 07:00:00";
    $end = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")))." 07:00:00";
}

$sql = "select * from (
        select a.id, a.worker_id, b.name, a.assy_line, a.cell_type, a.pallet, a.tanggal_produksi, a.start_date, coalesce(c.end_date,'0') as end_date,
		coalesce(c.qty_act_perbox,a.qty_perbox) as qty_perbox, coalesce(c.qty_act_perpallet,a.qty_perpallet) as qty_perpallet,
        to_char(a.tanggal_produksi,'yyyy-mm-dd') as tgl_pro, '-' as trouble
		from ztb_assy_kanban_start a
		inner join ztb_worker b on a.worker_id= b.worker_id
        left join ztb_assy_kanban c on a.pallet= c.pallet AND a.id_plan=c.id_plan
        where a.id= (select max(id) from ztb_assy_kanban_start where assy_line='LR06#5') OR 
        c.id = (select max(id) from ztb_assy_kanban where assy_line='LR06#5')
        ) where rownum=1";
$result = oci_parse($connect, $sql);
oci_execute($result);
$arrNo = 0;
$arrData = array();

$rowno = 0;
$arrNG = array();

$arrHSL = array();

while ($data_cek=oci_fetch_object($result)){
    $ln = $data_cek->ASSY_LINE;
    $ty = $data_cek->CELL_TYPE;
    $pl = $data_cek->PALLET;
    $tg = $data_cek->TGL_PRO;

    $ng = "select ngp.ng_id_proses, ng.ng_name_proses, ngp.ng_id, ng.ng_name, ngp.ng_id, ngp.ng_qty from ztb_assy_trans_ng ngp
        inner join ztb_assy_ng ng on ngp.ng_id_proses= ng.ng_id_proses AND ngp.ng_id= ng.ng_id
        where ngp.assy_line='$ln' AND ngp.cell_type='$ty' AND ngp.pallet=$pl AND ngp.tanggal_produksi=to_date('$tg','yyyy-mm-dd')";
    $result_ng = oci_parse($connect, $ng);
    oci_execute($result_ng);

    while($data_ng=oci_fetch_object($result_ng)){
        array_push($arrNG, $data_ng);
        $ng_name_proses = $arrNG[$rowno]->NG_NAME_PROSES;
        $ng_name = $arrNG[$rowno]->NG_NAME;
        $ng_qty = $arrNG[$rowno]->NG_QTY;
        $items[$rowno]->NG_QTY = number_format($ng_qty);
        $ngNya = $ng_name_proses.' ('.$ng_name.' - '.$ng_qty.' Menit)<br/>';
        array_push($arrHSL,$ngNya);
        $rowno++;
    }
    
    /*QTY HARIAN*/
    $hari = "select coalesce(sum(qty_act_perpallet),0) as qty_today from ztb_assy_kanban
        where assy_line='$ln' AND to_char(tanggal_Actual,'yyyy-mm-dd')=(select to_char(sysdate,'yyyy-mm-dd') from dual)";
    $result_hari = oci_parse($connect, $hari);
    oci_execute($result_hari);
    $data_hari=oci_fetch_object($result_hari);

    /*QTY BULANAN*/
    $bulan = "select coalesce(sum(qty_act_perpallet),0) as qty_monthly from ztb_assy_kanban
        where assy_line='$ln' AND to_char(tanggal_Actual,'yyyymm')=(select to_char(sysdate,'yyyymm') from dual)";
    $result_bulan = oci_parse($connect, $bulan);
    oci_execute($result_bulan);
    $data_bulan=oci_fetch_object($result_bulan);
    
    $arrData[$arrNo] = array("worker_id"=>rtrim($data_cek->WORKER_ID),
                             "nama"=>rtrim($data_cek->NAME),
                             "assy_line"=>rtrim($data_cek->ASSY_LINE),
                             "cell_type"=>rtrim($data_cek->CELL_TYPE),
                             "pallet"=>rtrim($data_cek->PALLET),
                             "tanggal_produksi"=>rtrim($data_cek->TANGGAL_PRODUKSI),
                             "start_date"=>rtrim($data_cek->START_DATE),
                             "end_date"=>rtrim($data_cek->END_DATE),
                             "qty_perbox"=>number_format(rtrim($data_cek->QTY_PERBOX)),
                             "qty_perpallet"=>number_format(rtrim($data_cek->QTY_PERPALLET)),
                             "trouble"=>$arrHSL,
                             "qty_harian"=>number_format($data_hari->QTY_TODAY/1000,1),
                             "qty_bulanan"=>number_format($data_bulan->QTY_MONTHLY/1000,1)
                    );
}
echo json_encode($arrData);
?>
<?php
include("../../connect/conn.php");
$sql = "select * from
        (
        select a.id, a.worker_id1 as worker_id, b.name, a.assy_line, a.cell_type, a.pallet,
        a.tanggal_produksi, a.start_date, nvl(a.end_date, 'BELUM SELESAI') as end_date, a.qty_act_perbox as qty_perbox,
        a.qty_act_perpallet as qty_perpallet, to_char(a.tanggal_produksi,'yyyy-mm-dd') as tgl_pro
        from ztb_assy_kanban a
        inner join ztb_worker b on a.worker_id1 = b.worker_id
        where a.assy_line='LR06#1' and
        a.id = (select max(id) from ztb_assy_kanban where assy_line='LR06#1')
        ) aa
        left outer join 
        (
        select 'LR06#1' as line, 
        nvl(sum(qty_act_perpallet),0) as qty_today from ztb_assy_kanban
        where assy_line='LR06#1' AND to_char(tanggal_Actual,'yyyy-mm-dd')=(select to_char(sysdate,'yyyy-mm-dd') from dual)
        )bb on aa.assy_line = bb.line
        left outer join
        (
        select 'LR06#1' as line2, 
        coalesce(sum(qty_act_perpallet),0) as qty_monthly from ztb_assy_kanban
        where assy_line='LR06#1' AND to_char(tanggal_Actual,'yyyymm')=(select to_char(sysdate,'yyyymm') from dual)
        )cc on aa.assy_line = cc.line2";

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

    if($data_cek->END_DATE == 'BELUM SELESAI'){
        $end = 'BELUM SELESAI';
    }else{
        $end = $data_cek->END_DATE.' (SELESAI)';
    }

    $arrData[$arrNo] = array("worker_id"=>rtrim($data_cek->WORKER_ID),
                             "nama"=>rtrim($data_cek->NAME),
                             "assy_line"=>rtrim($data_cek->ASSY_LINE),
                             "cell_type"=>rtrim($data_cek->CELL_TYPE),
                             "pallet"=>rtrim($data_cek->PALLET),
                             "tanggal_produksi"=>rtrim($data_cek->TANGGAL_PRODUKSI),
                             "start_date"=>rtrim($data_cek->START_DATE),
                             "end_date"=>rtrim($end),
                             "qty_perbox"=>number_format(rtrim($data_cek->QTY_PERBOX)),
                             "qty_perpallet"=>number_format(rtrim($data_cek->QTY_PERPALLET)),
                             "trouble"=>$arrHSL,
                             "qty_harian"=>number_format($data_cek->QTY_TODAY),
                             "qty_bulanan"=>number_format($data_cek->QTY_MONTHLY)
                    );
}
echo json_encode($arrData);
?>
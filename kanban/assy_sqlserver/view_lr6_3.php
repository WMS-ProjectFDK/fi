<?php
include("../../connect/conn_kanbansys.php");
$sql = "select * from
        (
        select a.id, a.worker_id1 as worker_id, b.name, a.assy_line, a.cell_type, a.pallet,
        a.tanggal_produksi, a.start_date, coalesce(a.end_date, 'BELUM SELESAI') as end_date, a.qty_act_perbox as qty_perbox,
        a.qty_act_perpallet as qty_perpallet, a.tanggal_produksi as tgl_pro
        from ztb_assy_kanban a
        inner join ztb_worker b on a.worker_id1 = b.worker_id
        where a.assy_line='LR06#3' and
        a.id = (select max(id) from ztb_assy_kanban where assy_line='LR06#3')
        ) aa
        left outer join 
        (
        select 'LR06#3' as line, 
        coalesce(sum(qty_act_perpallet),0) as qty_today from ztb_assy_kanban
        where assy_line='LR06#3' AND tanggal_Actual=GETDATE()
        )bb on aa.assy_line = bb.line
        left outer join
        (
        select 'LR06#3' as line2, 
        coalesce(sum(qty_act_perpallet),0) as qty_monthly from ztb_assy_kanban
        where assy_line='LR06#3' AND tanggal_Actual=GETDATE()
        )cc on aa.assy_line = cc.line2";

$result = odbc_exec($connect, $sql);
$arrNo = 0;
$arrData = array();

$rowno = 0;
$arrNG = array();

$arrHSL = array();

while ($data_cek=odbc_fetch_object($result)){
    $ln = $data_cek->assy_line;
    $ty = $data_cek->cell_type;
    $pl = $data_cek->pallet;
    $tg = $data_cek->tgl_pro;

    $ng = "select ngp.ng_id_proses, ng.ng_name_proses, ngp.ng_id, ng.ng_name, ngp.ng_id, ngp.ng_qty from ztb_assy_trans_ng ngp
        inner join ztb_assy_ng ng on ngp.ng_id_proses= ng.ng_id_proses AND ngp.ng_id= ng.ng_id
        where ngp.assy_line='$ln' AND ngp.cell_type='$ty' AND ngp.pallet=$pl AND ngp.tanggal_produksi='$tg'";
    $result_ng = odbc_exec($connect, $ng);

    while($data_ng=odbc_fetch_object($result_ng)){
        array_push($arrNG, $data_ng);
        $ng_name_proses = $arrNG[$rowno]->ng_name_proses;
        $ng_name = $arrNG[$rowno]->ng_name;
        $ng_qty = $arrNG[$rowno]->ng_qty;
        $items[$rowno]->ng_qty = number_format($ng_qty);
        $ngNya = $ng_name_proses.' ('.$ng_name.' - '.$ng_qty.' Menit)<br/>';
        array_push($arrHSL,$ngNya);
        $rowno++;
    }

    if($data_cek->end_date == 'BELUM SELESAI'){
        $end = 'BELUM SELESAI';
    }else{
        $end = $data_cek->end_date.' (SELESAI)';
    }

    $arrData[$arrNo] = array("worker_id"=>rtrim($data_cek->worker_id),
                             "nama"=>rtrim($data_cek->name),
                             "assy_line"=>rtrim($data_cek->assy_line),
                             "cell_type"=>rtrim($data_cek->cell_type),
                             "pallet"=>rtrim($data_cek->pallet),
                             "tanggal_produksi"=>rtrim($data_cek->tanggal_produksi),
                             "start_date"=>rtrim($data_cek->start_date),
                             "end_date"=>rtrim($end),
                             "qty_perbox"=>number_format(rtrim($data_cek->qty_perbox)),
                             "qty_perpallet"=>number_format(rtrim($data_cek->qty_perpallet)),
                             "trouble"=>$arrHSL,
                             "qty_harian"=>number_format($data_cek->qty_today),
                             "qty_bulanan"=>number_format($data_cek->qty_monthly)
                    );
}
echo json_encode($arrData);
?>
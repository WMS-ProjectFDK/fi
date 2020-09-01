<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$cmb_gr_no = isset($_REQUEST['cmb_gr_no']) ? strval($_REQUEST['cmb_gr_no']) : '';
$ck_gr_no = isset($_REQUEST['ck_gr_no']) ? strval($_REQUEST['ck_gr_no']) : '';
$cmb_supp = isset($_REQUEST['cmb_supp']) ? strval($_REQUEST['cmb_supp']) : '';
$nm_supp = isset($_REQUEST['nm_supp']) ? strval($_REQUEST['nm_supp']) : '';
$ck_supp = isset($_REQUEST['ck_supp']) ? strval($_REQUEST['ck_supp']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_date != "true"){
    $gr_date = "grh.gr_date >= '$date_awal' and grh.gr_date <= '$date_akhir' and ";
}else{
    $gr_date = "";
}

if ($ck_gr_no != "true"){
    $gr = "grh.gr_no = '$cmb_gr_no' and ";
}else{
    $gr = "";
}

if ($ck_supp != "true"){
    $supp = "grh.supplier_code = '$cmb_supp' and ";
}else{
    $supp = "";
}

if ($ck_po != "true"){
    $po = "grd.po_no='$cmb_po' and ";
}else{
    $po = "";
}

if ($ck_item != "true"){
    $item = "grd.item_no='$cmb_item' and ";
}else{
    $item = "";
}

$where ="where grd.gr_no = grh.gr_no
    and grh.supplier_code = c.company_code
    and c.country_code = cou2.country_code
    and grh.curr_code = cu.curr_code
    and grd.item_no = i.item_no
    and grd.origin_code = i.origin_code
    and grd.origin_code = cou.country_code
    and i.curr_code = cu2.curr_code
    and i.uom_q = un.unit_code
    and grh.slip_type = x.slip_type
    and grd.po_no = poh.po_no
    and i.class_code = cls.class_code
    and i.cost_subject_code = cs.cost_subject_code
    and $gr_date 
    $gr 
    $supp 
    $po 
    $item 
    grh.gr_no is not null";

$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $ip_res = '';
    if ($IP != ''){
        $ip_res = "and z.package_type in ('$IP')";
    }

    $sql = "select 
        grh.gr_no,
        grd.line_no gr_line_no,
        grh.inv_no,
        grd.po_no,
        grd.po_line_no po_line_no,
        CONVERT(varchar,grh.gr_date, 103) gr_date,
        CONVERT(varchar,grh.inv_date, 103) inv_date,
        CONVERT(varchar,grd.reg_date, 103) reg_date,
        CONVERT(varchar,grd.upto_date, 103) upto_date,
        grh.supplier_code,
        c.company supplier,
        cou2.country country_supplier,
        grd.customer_part_no,
        grd.item_no,
        grd.origin_code,
        i.description item,
        cou.country,
        grd.qty qty,
        case grd.qty when 0 then un.unit
                    when 1 then un.unit
                    else un.unit_pl end as unit,
        grd.u_price as u_price,
        grh.curr_code,
        grd.line_remark,
        i.standard_price as standard_price,
        cu.curr_mark,
        cu2.curr_mark  curr_mark_sp,
        grh.ex_rate as ex_rate,
        grd.amt_o as amt_o,
        (grd.amt_o * grh.ex_rate) as amt_l,
        grh.slip_type,
        x.slip_name,
        grh.bc_no,
        CONVERT(varchar,poh.po_date,103) po_date,
        grh.bc_doc,
        cls.class_1 + cls.class_2 class,
        cs.cost_subject_code cost_subject_code,
        cs.cost_subject_name cost_subject_name,
        grh.bc_doc,
        grh.bc_no,
        grh.tax_inv_no,
        CONVERT(varchar,grh.tax_inv_date, 103) tax_inv_date
        
        from gr_details grd,
            gr_header  grh,
            item i,
            currency cu,
            currency cu2,
            country cou,
            unit un,
            company c,
            sliptype x,
            po_header poh,
            class cls,
            costsubject cs,
            country cou2
            $where 
            order by c.company,grh.gr_no,grd.line_no";

    // echo $sql;
    $data = sqlsrv_query($connect, strtoupper($sql));

    if($data === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= $error[ 'message']."<br/>";  
            }  
        }
    }

    while($dt = sqlsrv_fetch_object($data)){
        array_push($response, $dt);
    }

    $fp = fopen('gr_download_result.json', 'w');
	fwrite($fp, json_encode($response));
    fclose($fp);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
    echo json_encode($msg);
}else{
    echo json_encode('success');
	// echo json_encode('SuccessMsg');
}
?>
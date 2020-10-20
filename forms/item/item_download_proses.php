<?php
// header('Content-Type: text/plain; charset="UTF-8"');
header("Content-type: application/json");
error_reporting(0);
session_start();
include("../../connect/conn.php");

$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
$cmb_subject_code = isset($_REQUEST['cmb_subject_code']) ? strval($_REQUEST['cmb_subject_code']) : '';
$ck_subject_code = isset($_REQUEST['ck_subject_code']) ? strval($_REQUEST['ck_subject_code']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

if ($ck_item_no != "true"){
    $item_no = "i.item_no = '$cmb_item_no' and ";
}else{
    $item_no = "";
}

if ($ck_subject_code != "true"){
    $subject_code = "i.stock_subject_code = '$cmb_subject_code' and ";
}else{
    $subject_code = "";
}

if($item_no =='' AND $subject_code ==''){
    $top = 'top 200';
}else{
    $top ='';
}

if ($src != '') {
    $where = "where (1=0
                OR i.item like '%$src%' 
                OR i.description like '%$src%'
                OR i.item_no like '%$src%'
            ) and i.item_no is not null and a.item_no != 0 and i.delete_type is null and i.section_code = 100";
}else{
    $where = "where $item_no $subject_code i.item_no is not null and i.item_no != 0 and i.delete_type is null and i.section_code = 100";
}


$response = array();        $response2 = array();
$rowno=0;

if (isset($_SESSION['id_wms'])){
    $ip_res = '';
    if ($IP != ''){
        $ip_res = "and z.package_type in ('$IP')";
    }

    $sql = "select $top
        i.item_no                    ,
        i.item_code                  ,
        st.stock_subject             ,
        i.item                       ,
        s.section                    ,
        i.mak            maker       ,
        u.country origin             ,
        i.item_flag                  ,
        itf.flag_name item_flag_name ,
        i.description                ,
        c.class_code                 ,
        c.CLASS_1+c.CLASS_2+c.CLASS_3 as CLASS,
        i.drawing_no                 ,
        i.drawing_rev                ,
        i.catalog_no                 ,
        i.applicable_model           ,
        i.external_unit_number,
        u1.unit qty_unit             ,
        u4.unit stock_unit  ,
        i.unit_stock_rate  stock_rate  ,
        i.weight                     ,
        u2.unit weight_unit          ,
        u3.unit length_unit          ,
        cu.curr_mark currency ,
        i.standard_price             ,
        i.next_term_price            ,
        i.suppliers_price            ,
        i.cost_subject_code          ,
        i.cost_process_code          ,
        isnull(i.manufact_leadtime,0)  manufact_leadtime    ,
        isnull(i.purchase_leadtime,0)  purchase_leadtime    ,
        isnull(i.adjustment_leadtime,0) adjustment_leadtime  ,
        --get_llc(i.item_no) llc       ,
        i.reorder_point              ,
        i.level_cont_key             ,
        i.manufact_fail_rate         ,
        i.maker_flag                 ,
        i.issue_policy               ,
        i.issue_lot                  ,
        i.order_policy               ,
        i.safety_stock               ,
        i.item_type1                ,
        i.item_type2                , 
        convert(varchar(10),i.upto_date,103) upto_date,
        convert(varchar(10),i.reg_date,103) reg_date,
        convert(varchar(10),i.receive_date,103) last_receive_date,
        convert(varchar(10),i.issue_date,103)   last_issue_date,
        i.package_unit_number        ,
        u5.unit      unit_package    ,
        i.unit_price_o unit_price_org ,
        i.unit_price_rate ,
        cu2.curr_mark      unit_currency,
        isnull(i.LABELING_TO_PACK_DAY,0) as LABELING_TO_PACK_DAY,
		isnull(i.ASSEMBLING_TO_LAB_DAY,0) as ASSEMBLING_TO_LAB_DAY
        from class c,
        item i,
        section s,
        country u,
        currency cu,
        unit u1,
        unit u2,
        unit u3,
        unit u4,
        unit u5,
        stock_subject st,
        currency cu2,
        itemflag itf
        $where 
        and i.origin_code = u.country_code
        and i.class_code = c.class_code
        and i.section_code = s.section_code
        and i.curr_code = cu.curr_code
        and i.uom_q = u1.unit_code
        and i.uom_w = u2.unit_code
        and i.uom_l = u3.unit_code
        and i.unit_stock = u4.unit_code
        and i.unit_package = u5.unit_code
        and i.stock_subject_code = st.stock_subject_code
        and i.unit_curr_code = cu2.curr_code
        and i.item_flag = itf.item_flag
        
        order by i.item asc";

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

    $fp = fopen('item_download_result.json', 'w');
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
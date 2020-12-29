<?php
// header('Content-Type: text/plain; charset="UTF-8"');
// header("Content-type: application/json");
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
    $sql = "select 
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
        CASE WHEN CLASS_1 is null then '' else CLASS_1 end+' '+CASE WHEN CLASS_2 is null then '' else CLASS_2 end+' '+CASE WHEN CLASS_3 is null then '' else CLASS_3 end as CLASS,
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
		isnull(i.ASSEMBLING_TO_LAB_DAY,0) as ASSEMBLING_TO_LAB_DAY,
        i.UPTO_PERSON_CODE,
        i.GRADE_CODE,
        i.CUSTOMER_TYPE,
        i.PACKAGE_TYPE,
        i.CAPACITY,
        i.DATE_CODE_TYPE,
        i.DATE_CODE_MONTH,
        --i.LABEL_TYPE_NAME,
        i.MEASUREMENT,
        i.INNER_BOX_HEIGHT,
        i.INNER_BOX_WIDTH,
        i.INNER_BOX_DEPTH,
        i.INNER_BOX_UNIT_NUMBER,
        i.MEDIUM_BOX_HEIGHT,
        i.MEDIUM_BOX_WIDTH,
        i.MEDIUM_BOX_DEPTH,
        i.MEDIUM_BOX_UNIT_NUMBER,
        i.OUTER_BOX_HEIGHT,
        i.OUTER_BOX_WIDTH,
        i.OUTER_BOX_DEPTH,
        --i.PACKING_INFORMATION_NO,
        --i.PLT_SPEC_NO,
        --i.PALLET_SIZE_TYPE_NAME,
        --i.PALLET_HEIGHT,
        --i.PALLET_WIDTH,
        --i.PALLET_DEPTH,
        --i.PALLET_UNIT_NUMBER,
        --i.PALLET_CTN_NUMBER,
        --i.PALLET_STEP_CTN_NUMBER,
        i.OPERATION_TIME,
        i.MAN_POWER,
        i.AGING_DAY
        from class c
        inner join item i on c.class_code = i.class_code
        left join section s on i.section_code = s.section_code
        left join country u on i.origin_code = u.country_code
        left join currency cu on i.curr_code = cu.curr_code
        left join unit u1 on i.uom_q = u1.unit_code
        left join unit u2 on i.uom_w = u2.unit_code
        left join unit u3 on i.uom_l = u3.unit_code
        left join unit u4 on i.unit_stock = u4.unit_code 
        left join unit u5 on i.unit_package = u5.unit_code
        left join stock_subject st on i.stock_subject_code = st.stock_subject_code
        left join currency cu2 on i.unit_curr_code = cu2.curr_code
        left join itemflag itf on i.item_flag = itf.item_flag
        $where 
        order by i.item asc";

    // echo $sql;

    $out = "ITEM_NO,ITEM_CODE,STOCK_SUBJECT,ITEM,SECTION,MAKER,ORIGIN,ITEM_FLAG,ITEM_FLAG_NAME,DESCRIPTION,CLASS_CODE,CLASS,DRAWING_NO,DRAWING_REV,CATALOG_NO,APPLICABLE_MODEL,EXTERNAL_UNIT_NUMBER,QTY_UNIT,STOCK_UNIT,STOCK_RATE,ENGINEER_UNIT,ENGINEER_RATE,WEIGHT,WEIGHT_UNIT,LENGTH_UNIT,CURRENCY,STANDARD_PRICE,NEXT_TERM_PRICE,SUPPLIERS_PRICE,COST_SUBJECT_CODE,COST_PROCESS_CODE,MANUFACT_LEADTIME,PURCHASE_LEADTIME,ADJUSTMENT_LEADTIME,LABELING_TO_PACKAGING_DAY,ASSEMBLING_TO_LABELING_DAY,CUSTOMER_ITEM_CODE,CUSTOMER_ITEM_NAME,LLC,REORDER_POINT,LEVEL_CONT_KEY,MANUFACT_FAIL_RATE,MAKER_FLAG,ISSUE_POLICY,ISSUE_LOT,ORDER_POLICY,SAFETY_STOCK,STOCK_ISSUE,ITEM_TYPE1,ITEM_TYPE2,UPTO_DATE,REG_DATE,LAST_RECEIVE_DATE,LAST_ISSUE_DATE,PACKAGE_UNIT_NUMBER,UNIT_PACKAGE,UNIT_PRICE_ORG,UNIT_PRICE_RATE,UNIT_CURRENCY,UPTO_PERSON_NAME,GRADE_CODE,CUSTOMER_TYPE,PACKAGE_TYPE,CAPACITY,DATE_CODE_TYPE,DATE_CODE_MONTH,LABEL_TYPE_NAME,MEASUREMENT,INNER_BOX_HEIGHT,INNER_BOX_WIDTH,INNER_BOX_DEPTH,INNER_BOX_UNIT_NUMBER,MEDIUM_BOX_HEIGHT,MEDIUM_BOX_WIDTH,MEDIUM_BOX_DEPTH,MEDIUM_BOX_UNIT_NUMBER,OUTER_BOX_HEIGHT,OUTER_BOX_WIDTH,OUTER_BOX_DEPTH,PACKING_INFORMATION_NO,PLT_SPEC_NO,PALLET_SIZE_TYPE_NAME,PALLET_HEIGHT,PALLET_WIDTH,PALLET_DEPTH,PALLET_UNIT_NUMBER,PALLET_CTN_NUMBER,PALLET_STEP_CTN_NUMBER,OPERATION_TIME,MAN_POWER,AGING_DAY";
    $out .= "\n";

    $data = sqlsrv_query($connect, strtoupper($sql));

    if($data === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {  
            foreach( $errors as $error){  
                $msg .= $error[ 'message']."<br/>";  
            }  
        }
    }

    while ($l = sqlsrv_fetch_array($data, SQLSRV_FETCH_ASSOC)) {
        foreach($l AS $key => $value){
            
            $pos = strpos(strval($value), '"');
            if ($pos !== false) {
                $value = str_replace('"', '\"', $value);
            }
            $out .= '"'.$value.'",';
        }
        $out .= "\n";
    }


    // while($dt = sqlsrv_fetch_object($data)){
    //     array_push($response, $dt);
    // }

    // $fp = fopen('item_download_result.json', 'w');
	// fwrite($fp, json_encode($response));
    // fclose($fp);
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
    echo json_encode($msg);
}else{
    // echo json_encode('success');
    // echo json_encode('SuccessMsg');
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=ITEM_MASTER.csv");
    echo $out;
}
?>
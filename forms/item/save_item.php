<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");
$msg = '';
$field = '';

if (isset($_SESSION['id_wms'])){
    $item_no = htmlspecialchars($_REQUEST['item_no']);
    $item_code = htmlspecialchars($_REQUEST['item_code']);
    $item = htmlspecialchars($_REQUEST['item']);
    $item_flag = htmlspecialchars($_REQUEST['item_flag']);
    $description = htmlspecialchars($_REQUEST['description']);
    $class_code = htmlspecialchars($_REQUEST['class_code']);
    $origin_code = htmlspecialchars($_REQUEST['origin_code']);
    $curr_code = htmlspecialchars($_REQUEST['curr_code']);
    $external_unit_number = htmlspecialchars($_REQUEST['external_unit_number']);
    $safety_stock = htmlspecialchars($_REQUEST['safety_stock']);
    $uom_q = htmlspecialchars($_REQUEST['uom_q']);
    $unit_engineering = htmlspecialchars($_REQUEST['unit_engineering']);
    $unit_stock_rate = htmlspecialchars($_REQUEST['unit_stock_rate']);
    $unit_engineer_rate = htmlspecialchars($_REQUEST['unit_engineer_rate']);
    $weight = htmlspecialchars($_REQUEST['weight']);
    $uom_w = htmlspecialchars($_REQUEST['uom_w']);
    $uom_l = htmlspecialchars($_REQUEST['uom_l']);
    $drawing_no = htmlspecialchars($_REQUEST['drawing_no']);
    $drawing_rev = htmlspecialchars($_REQUEST['drawing_rev']);
    $applicable_model = htmlspecialchars($_REQUEST['applicable_model']);
    $catalog_no = htmlspecialchars($_REQUEST['catalog_no']);
    $standard_price = htmlspecialchars($_REQUEST['standard_price']);
    $next_term_price = htmlspecialchars($_REQUEST['next_term_price']);
    $suppliers_price = htmlspecialchars($_REQUEST['suppliers_price']);
    $manufact_leadtime = htmlspecialchars($_REQUEST['manufact_leadtime']);
    $purchase_leadtime = htmlspecialchars($_REQUEST['purchase_leadtime']);
    $adjustment_leadtime = htmlspecialchars($_REQUEST['adjustment_leadtime']);
    $labeling_to_pack_day = htmlspecialchars($_REQUEST['labeling_to_pack_day']);
    $assembling_to_lab_day = htmlspecialchars($_REQUEST['assembling_to_lab_day']);
    $issue_policy = htmlspecialchars($_REQUEST['issue_policy']);
    $issue_lot = htmlspecialchars($_REQUEST['issue_lot']);
    $manufact_fail_rate = htmlspecialchars($_REQUEST['manufact_fail_rate']);
    $section_code = htmlspecialchars($_REQUEST['section_code']);
    $stock_subject_code = htmlspecialchars($_REQUEST['stock_subject_code']);
    $cost_process_code = htmlspecialchars($_REQUEST['cost_process_code']);
    $cost_subject_code = htmlspecialchars($_REQUEST['cost_subject_code']);
    $customer_item_no = htmlspecialchars($_REQUEST['customer_item_no']);
    $customer_item_name = htmlspecialchars($_REQUEST['customer_item_name']);
    $order_policy = htmlspecialchars($_REQUEST['order_policy']);
    $maker_flag = htmlspecialchars($_REQUEST['maker_flag']);
    $mak = htmlspecialchars($_REQUEST['mak']);
    $item_type1 = htmlspecialchars($_REQUEST['item_type1']);
    $item_type2 = htmlspecialchars($_REQUEST['item_type2']);
    $package_unit_number = htmlspecialchars($_REQUEST['package_unit_number']);
    $unit_price_o = htmlspecialchars($_REQUEST['unit_price_o']);
    $unit_price_rate = htmlspecialchars($_REQUEST['unit_price_rate']);
    $unit_curr_code = htmlspecialchars($_REQUEST['unit_curr_code']);
    $customer_type = htmlspecialchars($_REQUEST['customer_type']);
    $package_type = htmlspecialchars($_REQUEST['package_type']);
    $capacity = htmlspecialchars($_REQUEST['capacity']);
    $date_code_type = htmlspecialchars($_REQUEST['date_code_type']);
    $date_code_month = htmlspecialchars($_REQUEST['date_code_month']);
    $label_type = htmlspecialchars($_REQUEST['label_type']);
    $measurement = htmlspecialchars($_REQUEST['measurement']);
    $inner_box_height = htmlspecialchars($_REQUEST['inner_box_height']);
    $inner_box_width = htmlspecialchars($_REQUEST['inner_box_width']);
    $inner_box_depth = htmlspecialchars($_REQUEST['inner_box_depth']);
    $inner_box_unit_number = htmlspecialchars($_REQUEST['inner_box_unit_number']);
    $medium_box_unit_height = htmlspecialchars($_REQUEST['medium_box_unit_height']);
    $edium_box_unit_width = htmlspecialchars($_REQUEST['edium_box_unit_width']);
    $edium_box_unit_depth = htmlspecialchars($_REQUEST['edium_box_unit_depth']);
    $medium_box_unit_number = htmlspecialchars($_REQUEST['medium_box_unit_number']);
    $outer_box_unit_height = htmlspecialchars($_REQUEST['outer_box_unit_height']);
    $outer_box_unit_width = htmlspecialchars($_REQUEST['outer_box_unit_width']);
    $outer_box_unit_depth = htmlspecialchars($_REQUEST['outer_box_unit_depth']);
    $ctn_gross_weight = htmlspecialchars($_REQUEST['ctn_gross_weight']);
    $pi_no = htmlspecialchars($_REQUEST['pi_no']);
    $plt_spec_no = htmlspecialchars($_REQUEST['plt_spec_no']);
    $pallet_size_type = htmlspecialchars($_REQUEST['pallet_size_type']);
    $pallet_ctn_number = htmlspecialchars($_REQUEST['pallet_ctn_number']);
    $pallet_step_ctn_number = htmlspecialchars($_REQUEST['pallet_step_ctn_number']);
    $pallet_height = htmlspecialchars($_REQUEST['pallet_height']);
    $pallet_width = htmlspecialchars($_REQUEST['pallet_width']);
    $pallet_depth = htmlspecialchars($_REQUEST['pallet_depth']);
    $pallet_unit_number = htmlspecialchars($_REQUEST['pallet_unit_number']);
    $opertation_time = htmlspecialchars($_REQUEST['opertation_time']);
    $man_power = htmlspecialchars($_REQUEST['man_power']);
    $aging_day = htmlspecialchars($_REQUEST['aging_day']);

    $field .= "item_no                    ,";               $value .= "$item_no,";
    $field .= "item_code                  ,";               $value .= "'$item_code',";
    $field .= "item                       ,";               $value .= "'$item',";
    $field .= "item_flag                  ,";               $value .= "'$item_flag',";
    $field .= "description                ,";               $value .= "'$description',";
    $field .= "class_code                 ,";               $value .= "$class_code,";
    $field .= "origin_code                ,";               $value .= "$origin_code,";
    $field .= "curr_code                  ,";               $value .= "$curr_code,";
    $field .= "external_unit_number       ,";               $value .= "$external_unit_number,";
    $field .= "safety_stock               ,";               $value .= "$safety_stock,";
    $field .= "uom_q                      ,";               $value .= "$uom_q,";
    $field .= "unit_engineering           ,";               $value .= "$unit_engineering,";
    $field .= "unit_stock_rate            ,";               $value .= "$unit_stock_rate,";
    $field .= "unit_engineer_rate         ,";               $value .= "$unit_engineer_rate,";

    $field .= "weight                     ,";               $value .= "$weight,";
    $field .= "uom_w                      ,";               $value .= "$uom_w,";
    $field .= "uom_l                      ,";               $value .= "$uom_l,";
    $field .= "drawing_no                 ,";               $value .= "'$drawing_no',";
    $field .= "drawing_rev                ,";               $value .= "'$drawing_rev',";
    $field .= "applicable_model           ,";               $value .= "'$applicable_model',";
    $field .= "catalog_no                 ,";               $value .= "'$catalog_no',";

    $field .= "standard_price             ,";               $value .= "$standard_price,";
    $field .= "next_term_price            ,";               $value .= "$next_term_price,";
    $field .= "suppliers_price            ,";               $value .= "$suppliers_price,";
    $field .= "manufact_leadtime          ,";               $value .= "$manufact_leadtime,";
    $field .= "purchase_leadtime          ,";               $value .= "$purchase_leadtime,";
    $field .= "adjustment_leadtime        ,";               $value .= "$adjustment_leadtime,";
    $field .= "labeling_to_pack_day       ,";               $value .= "$labeling_to_pack_day,";
    $field .= "assembling_to_lab_day      ,";               $value .= "$assembling_to_lab_day,";
    $field .= "issue_policy               ,";               $value .= "'$issue_policy',";
    $field .= "issue_lot                  ,";               $value .= "$issue_lot,";
    $field .= "manufact_fail_rate         ,";               $value .= "$manufact_fail_rate,";

    $field .= "section_code               ,";               $value .= "$section_code,";
    $field .= "stock_subject_code         ,";               $value .= "'$stock_subject_code',";
    $field .= "cost_process_code          ,";               $value .= "'$cost_process_code',";
    $field .= "cost_subject_code          ,";               $value .= "'$cost_subject_code',";
    $field .= "customer_item_no           ,";               $value .= "'$customer_item_no',";
    $field .= "customer_item_name         ,";               $value .= "'$customer_item_name',";
    $field .= "order_policy               ,";               $value .= "'$order_policy',";
    $field .= "maker_flag                 ,";               $value .= "'$maker_flag',";
    $field .= "mak                        ,";               $value .= "'$mak',";
    $field .= "item_type1                 ,";               $value .= "'$item_type1',";
    $field .= "item_type2                 ,";               $value .= "'$item_type2',";
    $field .= "package_unit_number        ,";               $value .= "'$package_unit_number',";

    $field .= "unit_price_o               ,";               $value .= "$unit_price_o,";
    $field .= "unit_price_rate            ,";               $value .= "$unit_price_rate,";
    $field .= "unit_curr_code             ,";               $value .= "$unit_curr_code,";
    $field .= "customer_type              ,";               $value .= "'$customer_type',";
    $field .= "package_type               ,";               $value .= "'$package_type',";
    $field .= "capacity                   ,";               $value .= "$capacity,";
    $field .= "date_code_type             ,";               $value .= "'$date_code_type',";
    $field .= "date_code_month            ,";               $value .= "$date_code_month,";
    $field .= "label_type                 ,";               $value .= "$label_type,";
    $field .= "measurement                ,";               $value .= "$measurement,";


    $field .= "inner_box_height           ,";               $value .= "$inner_box_height,";
    $field .= "inner_box_width            ,";               $value .= "$inner_box_width,";
    $field .= "inner_box_depth            ,";               $value .= "$inner_box_depth,";
    $field .= "inner_box_unit_number      ,";               $value .= "$inner_box_unit_number,";
    $field .= "medium_box_unit_height     ,";               $value .= "$medium_box_unit_height,";
    $field .= "edium_box_unit_width       ,";               $value .= "$edium_box_unit_width,";
    $field .= "edium_box_unit_depth       ,";               $value .= "$edium_box_unit_depth,";
    $field .= "medium_box_unit_number     ,";               $value .= "$medium_box_unit_number,";
    $field .= "outer_box_unit_height      ,";               $value .= "$outer_box_unit_height,";
    $field .= "outer_box_unit_width       ,";               $value .= "$outer_box_unit_width,";
    $field .= "outer_box_unit_depth       ,";               $value .= "$outer_box_unit_depth,";
    $field .= "ctn_gross_weight           ,";               $value .= "$ctn_gross_weight,";


    $field .= "pi_no                      ,";               $value .= "'$pi_no',";
    // $field .= "plt_spec_no                ,";               $value .= "$plt_spec_no,";
    // $field .= "pallet_size_type           ,";               $value .= "$pallet_size_type,";
    // $field .= "pallet_ctn_number          ,";               $value .= "$pallet_ctn_number,";
    // $field .= "pallet_step_ctn_number     ,";               $value .= "$pallet_step_ctn_number,";
    // $field .= "pallet_height              ,";               $value .= "$pallet_height,";
    // $field .= "pallet_width               ,";               $value .= "$pallet_width,";
    // $field .= "pallet_depth               ,";               $value .= "$pallet_depth,";
    // $field .= "pallet_unit_number         ,";               $value .= "$pallet_unit_number,";


    $field .= "opertation_time            ,";               $value .= "$opertation_time,";
    $field .= "man_power                  ,";               $value .= "$man_power,";
    $field .= "aging_day                   ";               $value .= "$aging_day";

    trim($field);
    trim($value);

    $ins = "insert into item ($field) values ($value)";
    $data = sqlsrv_query($connect, $ins);
    echo $ins;
    if($data === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
        }
    }
}else{
    $msg .= 'Session Expired';
}

if($msg != ''){
	echo json_encode($msg);
}else{
	echo json_encode('success');
}
?>
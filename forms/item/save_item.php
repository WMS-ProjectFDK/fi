<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");
$msg = '';
$field = '';

if (isset($_SESSION['id_wms'])){
    $item_no = (strlen(htmlspecialchars($_REQUEST['item_no'])) == 0) ? 'NULL' : htmlspecialchars($_REQUEST['item_no']);
    $item_code = (strlen(htmlspecialchars($_REQUEST['item_code'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['item_code'])."'";
    $item = (strlen(htmlspecialchars($_REQUEST['item'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['item'])."'";
    $item_flag = (strlen(htmlspecialchars($_REQUEST['item_flag'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['item_flag'])."'";
    $description = (strlen(htmlspecialchars($_REQUEST['description'])) == 0) ? 'NULL' : "'".htmlspecialchars($_REQUEST['description'])."'";
    $class_code = (strlen(htmlspecialchars($_REQUEST['class_code'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['class_code']);
    $origin_code = (strlen(htmlspecialchars($_REQUEST['origin_code'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['origin_code']);
    $curr_code = (strlen(htmlspecialchars($_REQUEST['curr_code'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['curr_code']);
    $external_unit_number = (strlen(htmlspecialchars($_REQUEST['external_unit_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['external_unit_number']);
    $safety_stock = (strlen(htmlspecialchars($_REQUEST['safety_stock'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['safety_stock']);
    $uom_q = (strlen(htmlspecialchars($_REQUEST['uom_q'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['uom_q']);
    $unit_engineering =(strlen(htmlspecialchars($_REQUEST['unit_engineering'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['unit_engineering']);
    $unit_stock_rate = (strlen(htmlspecialchars($_REQUEST['unit_stock_rate']))== 0)? 'NULL': htmlspecialchars($_REQUEST['unit_stock_rate']);
    $unit_engineer_rate = (strlen(htmlspecialchars($_REQUEST['unit_engineer_rate'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['unit_engineer_rate']);

    $weight = (strlen(htmlspecialchars($_REQUEST['weight'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['weight']);
    $uom_w = (strlen(htmlspecialchars($_REQUEST['uom_w'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['uom_w']);
    $uom_l = (strlen(htmlspecialchars($_REQUEST['uom_l'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['uom_l']);
    $drawing_no = (strlen(htmlspecialchars($_REQUEST['drawing_no'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['drawing_no'])."'";
    $drawing_rev = (strlen(htmlspecialchars($_REQUEST['drawing_rev'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['drawing_rev'])."'";
    $applicable_model = (strlen(htmlspecialchars($_REQUEST['applicable_model'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['applicable_model'])."'";
    $catalog_no = (strlen(htmlspecialchars($_REQUEST['catalog_no'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['catalog_no'])."'";

    $standard_price = (strlen(htmlspecialchars($_REQUEST['standard_price'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['standard_price']);
    $next_term_price = (strlen(htmlspecialchars($_REQUEST['next_term_price'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['next_term_price']);
    $suppliers_price = (strlen(htmlspecialchars($_REQUEST['suppliers_price'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['suppliers_price']);
    $manufact_leadtime = (strlen(htmlspecialchars($_REQUEST['manufact_leadtime'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['manufact_leadtime']);
    $purchase_leadtime = (strlen(htmlspecialchars($_REQUEST['purchase_leadtime'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['purchase_leadtime']);
    $adjustment_leadtime = (strlen(htmlspecialchars($_REQUEST['adjustment_leadtime'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['adjustment_leadtime']);
    $labeling_to_pack_day = (strlen(htmlspecialchars($_REQUEST['labeling_to_pack_day'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['labeling_to_pack_day']);
    $assembling_to_lab_day = (strlen(htmlspecialchars($_REQUEST['assembling_to_lab_day'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['assembling_to_lab_day']);
    $issue_policy = (strlen(htmlspecialchars($_REQUEST['issue_policy'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['issue_policy'])."'";
    $issue_lot = (strlen(htmlspecialchars($_REQUEST['issue_lot'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['issue_lot']);
    $manufact_fail_rate = (strlen(htmlspecialchars($_REQUEST['manufact_fail_rate'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['manufact_fail_rate']);

    $section_code = (strlen(htmlspecialchars($_REQUEST['section_code'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['section_code']);
    $stock_subject_code = (strlen(htmlspecialchars($_REQUEST['stock_subject_code'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['stock_subject_code'])."'";
    $cost_process_code = (strlen(htmlspecialchars($_REQUEST['cost_process_code'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['cost_process_code'])."'";
    $cost_subject_code = (strlen(htmlspecialchars($_REQUEST['cost_subject_code'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['cost_subject_code'])."'";
    $customer_item_no = (strlen(htmlspecialchars($_REQUEST['customer_item_no'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['customer_item_no'])."'";
    $customer_item_name = (strlen(htmlspecialchars($_REQUEST['customer_item_name'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['customer_item_name'])."'";
    $order_policy = (strlen(htmlspecialchars($_REQUEST['order_policy'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['order_policy'])."'";
    $maker_flag = (strlen(htmlspecialchars($_REQUEST['maker_flag'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['maker_flag'])."'";
    $mak = (strlen(htmlspecialchars($_REQUEST['mak'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['mak'])."'";
    $item_type1 = (strlen(htmlspecialchars($_REQUEST['item_type1'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['item_type1'])."'";
    $item_type2 = (strlen(htmlspecialchars($_REQUEST['item_type2'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['item_type2'])."'";
    $package_unit_number = (strlen(htmlspecialchars($_REQUEST['package_unit_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['package_unit_number']);

    $unit_price_o = (strlen(htmlspecialchars($_REQUEST['unit_price_o'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['unit_price_o']);
    $unit_price_rate = (strlen(htmlspecialchars($_REQUEST['unit_price_rate'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['unit_price_rate']);
    $unit_curr_code = (strlen(htmlspecialchars($_REQUEST['unit_curr_code'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['unit_curr_code']);
    $customer_type = (strlen(htmlspecialchars($_REQUEST['customer_type'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['customer_type'])."'";
    $package_type = (strlen(htmlspecialchars($_REQUEST['package_type'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['package_type'])."'";
    $capacity = (strlen(htmlspecialchars($_REQUEST['capacity'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['capacity']);
    $date_code_type = (strlen(htmlspecialchars($_REQUEST['date_code_type'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['date_code_type'])."'";
    $date_code_month = (strlen(htmlspecialchars($_REQUEST['date_code_month'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['date_code_month']);
    $label_type = (strlen(htmlspecialchars($_REQUEST['label_type'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['label_type']);
    $measurement = (strlen(htmlspecialchars($_REQUEST['measurement'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['measurement']);

    $inner_box_height = (strlen(htmlspecialchars($_REQUEST['inner_box_height'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['inner_box_height']);
    $inner_box_width = (strlen(htmlspecialchars($_REQUEST['inner_box_width'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['inner_box_width']);
    $inner_box_depth = (strlen(htmlspecialchars($_REQUEST['inner_box_depth'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['inner_box_depth']);
    $inner_box_unit_number = (strlen(htmlspecialchars($_REQUEST['inner_box_unit_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['inner_box_unit_number']);
    $medium_box_height = (strlen(htmlspecialchars($_REQUEST['medium_box_height'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['medium_box_height']);
    $edium_box_width = (strlen(htmlspecialchars($_REQUEST['edium_box_width'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['edium_box_width']);
    $edium_box_depth = (strlen(htmlspecialchars($_REQUEST['edium_box_depth'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['edium_box_depth']);
    $medium_box_unit_number = (strlen(htmlspecialchars($_REQUEST['medium_box_unit_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['medium_box_unit_number']);
    $outer_box_height = (strlen(htmlspecialchars($_REQUEST['outer_box_height'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['outer_box_height']);
    $outer_box_width = (strlen(htmlspecialchars($_REQUEST['outer_box_width'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['outer_box_width']);
    $outer_box_depth = (strlen(htmlspecialchars($_REQUEST['outer_box_depth'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['outer_box_depth']);
    $ctn_gross_weight = (strlen(htmlspecialchars($_REQUEST['ctn_gross_weight'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['ctn_gross_weight']);

    $pi_no = (strlen(htmlspecialchars($_REQUEST['pi_no'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['pi_no'])."'";
    $plt_spec_no = (strlen(htmlspecialchars($_REQUEST['plt_spec_no'])) == 0)? 'NULL' : "'".htmlspecialchars($_REQUEST['plt_spec_no'])."'";
    $pallet_size_type = (strlen(htmlspecialchars($_REQUEST['pallet_size_type'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_size_type']);
    $pallet_ctn_number = (strlen(htmlspecialchars($_REQUEST['pallet_ctn_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_ctn_number']);
    $pallet_step_ctn_number = (strlen(htmlspecialchars($_REQUEST['pallet_step_ctn_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_step_ctn_number']);
    $pallet_height = (strlen(htmlspecialchars($_REQUEST['pallet_height'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_height']);
    $pallet_width = (strlen(htmlspecialchars($_REQUEST['pallet_width'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_width']);
    $pallet_depth = (strlen(htmlspecialchars($_REQUEST['pallet_depth'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_depth']);
    $pallet_unit_number = (strlen(htmlspecialchars($_REQUEST['pallet_unit_number'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['pallet_unit_number']);

    $opertation_time = (strlen(htmlspecialchars($_REQUEST['opertation_time'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['opertation_time']);
    $man_power = (strlen(htmlspecialchars($_REQUEST['man_power'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['man_power']);
    $aging_day = (strlen(htmlspecialchars($_REQUEST['aging_day'])) == 0)? 'NULL' : htmlspecialchars($_REQUEST['aging_day']);

    $field .= "item_no                    ,";               $value .= "$item_no,";
    $field .= "item_code                  ,";               $value .= "$item_code,";
    $field .= "item                       ,";               $value .= "$item,";
    $field .= "item_flag                  ,";               $value .= "$item_flag,";
    $field .= "description                ,";               $value .= "$description,";
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
    $field .= "drawing_no                 ,";               $value .= "$drawing_no,";
    $field .= "drawing_rev                ,";               $value .= "$drawing_rev,";
    $field .= "applicable_model           ,";               $value .= "$applicable_model,";
    $field .= "catalog_no                 ,";               $value .= "$catalog_no,";

    $field .= "standard_price             ,";               $value .= "$standard_price,";
    $field .= "next_term_price            ,";               $value .= "$next_term_price,";
    $field .= "suppliers_price            ,";               $value .= "$suppliers_price,";
    $field .= "manufact_leadtime          ,";               $value .= "$manufact_leadtime,";
    $field .= "purchase_leadtime          ,";               $value .= "$purchase_leadtime,";
    $field .= "adjustment_leadtime        ,";               $value .= "$adjustment_leadtime,";
    $field .= "labeling_to_pack_day       ,";               $value .= "$labeling_to_pack_day,";
    $field .= "assembling_to_lab_day      ,";               $value .= "$assembling_to_lab_day,";
    $field .= "issue_policy               ,";               $value .= "$issue_policy,";
    $field .= "issue_lot                  ,";               $value .= "$issue_lot,";
    $field .= "manufact_fail_rate         ,";               $value .= "$manufact_fail_rate,";

    $field .= "section_code               ,";               $value .= "$section_code,";
    $field .= "stock_subject_code         ,";               $value .= "$stock_subject_code,";
    $field .= "cost_process_code          ,";               $value .= "$cost_process_code,";
    $field .= "cost_subject_code          ,";               $value .= "$cost_subject_code,";
    $field .= "customer_item_no           ,";               $value .= "$customer_item_no,";
    $field .= "customer_item_name         ,";               $value .= "$customer_item_name,";
    $field .= "order_policy               ,";               $value .= "$order_policy,";
    $field .= "maker_flag                 ,";               $value .= "$maker_flag,";
    $field .= "mak                        ,";               $value .= "$mak,";
    $field .= "item_type1                 ,";               $value .= "$item_type1,";
    $field .= "item_type2                 ,";               $value .= "$item_type2,";
    $field .= "package_unit_number        ,";               $value .= "$package_unit_number,";

    $field .= "unit_price_o               ,";               $value .= "$unit_price_o,";
    $field .= "unit_price_rate            ,";               $value .= "$unit_price_rate,";
    $field .= "unit_curr_code             ,";               $value .= "$unit_curr_code,";
    $field .= "customer_type              ,";               $value .= "$customer_type,";
    $field .= "package_type               ,";               $value .= "$package_type,";
    $field .= "capacity                   ,";               $value .= "$capacity,";
    $field .= "date_code_type             ,";               $value .= "$date_code_type,";
    $field .= "date_code_month            ,";               $value .= "$date_code_month,";
    $field .= "label_type                 ,";               $value .= "$label_type,";
    $field .= "measurement                ,";               $value .= "$measurement,";


    $field .= "inner_box_height           ,";               $value .= "$inner_box_height,";
    $field .= "inner_box_width            ,";               $value .= "$inner_box_width,";
    $field .= "inner_box_depth            ,";               $value .= "$inner_box_depth,";
    $field .= "inner_box_unit_number      ,";               $value .= "$inner_box_unit_number,";
    $field .= "medium_box_height          ,";               $value .= "$medium_box_height,";
    $field .= "medium_box_width           ,";               $value .= "$edium_box_width,";
    $field .= "medium_box_depth           ,";               $value .= "$edium_box_depth,";
    $field .= "medium_box_unit_number     ,";               $value .= "$medium_box_unit_number,";
    $field .= "outer_box_height           ,";               $value .= "$outer_box_height,";
    $field .= "outer_box_width            ,";               $value .= "$outer_box_width,";
    $field .= "outer_box_depth            ,";               $value .= "$outer_box_depth,";
    $field .= "ctn_gross_weight           ,";               $value .= "$ctn_gross_weight,";


    $field .= "pi_no                      ,";               $value .= "$pi_no,";
    $field .= "operation_time             ,";               $value .= "$opertation_time,";
    $field .= "man_power                  ,";               $value .= "$man_power,";
    $field .= "aging_day                  ,";               $value .= "$aging_day,";
    $field .= "upto_date                  ,";               $value .= "GETDATE(),";
    $field .= "reg_date                    ";               $value .= "GETDATE()";

    trim($field);
    trim($value);

    $ins = "insert into item ($field) values ($value)";
    $data = sqlsrv_query($connect, $ins);
    // echo $ins."<br/>";
    if($data === false ) {
        if(($errors = sqlsrv_errors() ) != null) {  
                foreach( $errors as $error){  
                $msg .= "message: ".$error[ 'message']."<br/>";  
                }  
        }
    }

    $cek = "select pi_no from packing_information 
        where pi_no=$pi_no ";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

    $stmt = sqlsrv_query($connect, $cek , $params, $options );
    $row_count = sqlsrv_num_rows($stmt);

    // echo $cek."<br/>";
    
    if ($row_count == 0){
        $field1 .= "pi_no                      ,";               $value1 .= "$pi_no,";
        $field1 .= "plt_spec_no                ,";               $value1 .= "$plt_spec_no,";
        $field1 .= "pallet_size_type           ,";               $value1 .= "$pallet_size_type,";
        $field1 .= "pallet_ctn_number          ,";               $value1 .= "$pallet_ctn_number,";
        $field1 .= "pallet_step_ctn_number     ,";               $value1 .= "$pallet_step_ctn_number,";
        $field1 .= "pallet_height              ,";               $value1 .= "$pallet_height,";
        $field1 .= "pallet_width               ,";               $value1 .= "$pallet_width,";
        $field1 .= "pallet_depth               ,";               $value1 .= "$pallet_depth,";
        $field1 .= "pallet_unit_number         ";               $value1 .= "$pallet_unit_number";
        trim($field1);
        trim($value1);

        $ins1 = "insert into PACKING_INFORMATION ($field1) values ($value1)";
        $data1 = sqlsrv_query($connect, $ins1);
        // echo $ins1."<br/>";
        if($data1 === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                    }  
            }
        }
    }else{
        $field2 .= "plt_spec_no           = $plt_spec_no,";
        $field2 .= "pallet_size_type      = $pallet_size_type,";
        $field2 .= "pallet_ctn_number     = $pallet_ctn_number,";
        $field2 .= "pallet_step_ctn_number= $pallet_step_ctn_number,";
        $field2 .= "pallet_height         = $pallet_height,";
        $field2 .= "pallet_width          = $pallet_width,";
        $field2 .= "pallet_depth          = $pallet_depth,";
        $field2 .= "pallet_unit_number    = $pallet_unit_number";
        trim($field2);

        $upd = "update PACKING_INFORMATION SET $field2
            where pi_no=$pi_no";
        $data2 = sqlsrv_query($connect, $upd);
        // echo $upd."<br/>";
        if($data2 === false ) {
            if(($errors = sqlsrv_errors() ) != null) {  
                    foreach( $errors as $error){  
                    $msg .= "message: ".$error[ 'message']."<br/>";  
                    }  
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
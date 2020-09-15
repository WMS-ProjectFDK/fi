<?php
// include("../connect/conn.php");
// $msg = 'success';

// $sqlx = "{call zsp_mrp_material}";
// $params = array(
//     array(2225326, SQLSRV_PARAM_IN),
//     array('O.PRF-20-00660', SQLSRV_PARAM_IN)
// );

// $item=1110064;
// $sqlx = "{call zsp_mrp_pm_item(?)}";
// $params2 = array(
//                 array('2225326', SQLSRV_PARAM_IN)
// );
// $stmt = sqlsrv_query($connect, $sqlx,$params2);

// $stmt = sqlsrv_query($connect, $sqlx);//,$params);
// if($stmt === false){
//     $msg = " Procedure I - MRP Process Error : $sql";
//     // break;
// }

echo strtolower('item_no                    ')."<br/>";
echo strtolower('item_code                  ')."<br/>";
echo strtolower('item                       ')."<br/>";
echo strtolower('item_flag                  ')."<br/>";
echo strtolower('description                ')."<br/>";
echo strtolower('class_code                 ')."<br/>";
echo strtolower('origin_code                ')."<br/>";
echo strtolower('curr_code                  ')."<br/>";
echo strtolower('external_unit_number       ')."<br/>";
echo strtolower('safety_stock               ')."<br/>";
echo strtolower('uom_q                      ')."<br/>";
echo strtolower('unit_engineering           ')."<br/>";
echo strtolower('unit_stock_rate            ')."<br/>";
echo strtolower('unit_engineer_rate         ')."<br/>";
echo strtolower('weight                     ')."<br/>";
echo strtolower('uom_w                      ')."<br/>";
echo strtolower('uom_l                      ')."<br/>";
echo strtolower('drawing_no                 ')."<br/>";
echo strtolower('drawing_rev                ')."<br/>";
echo strtolower('applicable_model           ')."<br/>";
echo strtolower('catalog_no                 ')."<br/>";
echo strtolower('standard_price             ')."<br/>";
echo strtolower('next_term_price            ')."<br/>";
echo strtolower('suppliers_price            ')."<br/>";
echo strtolower('manufact_leadtime          ')."<br/>";
echo strtolower('purchase_leadtime          ')."<br/>";
echo strtolower('adjustment_leadtime        ')."<br/>";
echo strtolower('labeling_to_pack_day       ')."<br/>";
echo strtolower('assembling_to_lab_day      ')."<br/>";
echo strtolower('issue_policy               ')."<br/>";
echo strtolower('issue_lot                  ')."<br/>";
echo strtolower('manufact_fail_rate         ')."<br/>";
echo strtolower('section_code               ')."<br/>";
echo strtolower('stock_subject_code         ')."<br/>";
echo strtolower('cost_process_code          ')."<br/>";
echo strtolower('cost_subject_code          ')."<br/>";
echo strtolower('customer_item_no           ')."<br/>";
echo strtolower('customer_item_name         ')."<br/>";
echo strtolower('order_policy               ')."<br/>";
echo strtolower('maker_flag                 ')."<br/>";
echo strtolower('mak                        ')."<br/>";
echo strtolower('item_type1                 ')."<br/>";
echo strtolower('item_type2                 ')."<br/>";
echo strtolower('package_unit_number        ')."<br/>";
echo strtolower('unit_price_o               ')."<br/>";
echo strtolower('unit_price_rate            ')."<br/>";
echo strtolower('unit_curr_code             ')."<br/>";
echo strtolower('customer_type              ')."<br/>";
echo strtolower('package_type               ')."<br/>";
echo strtolower('capacity                   ')."<br/>";
echo strtolower('date_code_type             ')."<br/>";
echo strtolower('date_code_month            ')."<br/>";
echo strtolower('label_type                 ')."<br/>";
echo strtolower('measurement                ')."<br/>";
echo strtolower('inner_box_height           ')."<br/>";
echo strtolower('inner_box_width            ')."<br/>";
echo strtolower('inner_box_depth            ')."<br/>";
echo strtolower('inner_box_unit_number      ')."<br/>";
echo strtolower('medium_box_height          ')."<br/>";
echo strtolower('medium_box_width           ')."<br/>";
echo strtolower('medium_box_depth           ')."<br/>";
echo strtolower('medium_box_unit_number     ')."<br/>";
echo strtolower('outer_box_height           ')."<br/>";
echo strtolower('outer_box_width            ')."<br/>";
echo strtolower('outer_box_depth            ')."<br/>";
echo strtolower('ctn_gross_weight           ')."<br/>";
echo strtolower('pi_no                      ')."<br/>";
echo strtolower('operation_time             ')."<br/>";
echo strtolower('man_power                  ')."<br/>";
echo strtolower('aging_day                  ')."<br/>";

// echo json_encode($msg);
?>

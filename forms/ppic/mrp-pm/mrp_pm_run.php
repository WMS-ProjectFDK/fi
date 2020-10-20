<?php
set_time_limit(0);
include("../../../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
$arrData = array();
$arrNo = 0;
$msg = '';

$msg .= date("Y-m-d H:i:s").' - START<br/>';
// ==================================================================================
// $ins = "insert into ztb_mrp_data_pck_delete (del_date, no_Id, description,
// 		n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
// 		n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
// 		n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
// 		n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
// 		n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
// 		n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
// 		n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70,
// 		n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80,
// 		n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90, 
// 		ITEM_NO, ITEM_DESC)
// 	select '".date('Y-m-d H:i:s')."', no_Id, description, 
// 	n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
// 	n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
// 	n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
// 	n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
// 	n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
// 	n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
// 	n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70,
// 	n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80,
// 	n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90, 
// 	ITEM_NO, ITEM_DESC
// 	from ztb_mrp_data_pck";
// // echo $ins;
// $data_ins = sqlsrv_query($connect, $ins);
// if( $data_ins === false ){
// 	die( print_r( sqlsrv_errors(), true));
// }
// $msg .= date("Y-m-d H:i:s").' - DELETE<br/>';
// ==================================================================================
$sql = "{call zsp_mrp_pm}";
$stmt = sqlsrv_query($connect, $sql);
$msg .= date("Y-m-d H:i:s").'PROCESS<br>';
if( $stmt === false ){
	die( print_r( sqlsrv_errors(), true));
}
$msg .= date("Y-m-d H:i:s").' - FINISH<br/>';
// ==================================================================================
echo json_encode('success');
?>
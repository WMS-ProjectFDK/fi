<?php
include("../connect/conn.php");
$msg = 'success';

// $sqlx = "{call zsp_mrp_material}";
// $params = array(
//     array(2225326, SQLSRV_PARAM_IN),
//     array('O.PRF-20-00660', SQLSRV_PARAM_IN)
// );

$item=1110064;
$sqlx = "{call zsp_mrp_pm_item(?)}";
$params2 = array(
                array('2225326', SQLSRV_PARAM_IN)
);
$stmt = sqlsrv_query($connect, $sqlx,$params2);

// $stmt = sqlsrv_query($connect, $sqlx);//,$params);
if($stmt === false){
    $msg = " Procedure I - MRP Process Error : $sql";
    // break;
}

echo json_encode($msg);
?>

<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
include("../../connect/conn.php");


$sql = "{call t_sp(?)}";

$params = array(
	array('FI-SL/20-083', SQLSRV_PARAM_IN)
);
$stmt = sqlsrv_query($connect, $sql,$params);
if( $stmt === false )
{
    echo "Error in executing statement 3.\n";
    die( print_r( sqlsrv_errors(), true));
}

?>
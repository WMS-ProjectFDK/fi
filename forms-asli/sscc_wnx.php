<?php
$n = isset($_REQUEST['n']) ? strval($_REQUEST['n']) : '';
include ("sscc_func.php");
$sscc = sscc_kode_print($n);
echo $sscc;
?>

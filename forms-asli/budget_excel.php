<?php
include "../class/excel_reader.php";
include "../class/PHPExcel/PHPExcel.php";


$excel = new PHPExcel();
$xlsFile = "BudgetTemplate.xls";
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="' . $xlsFile . '"');
readfile($xlsFile);
exit();
?>
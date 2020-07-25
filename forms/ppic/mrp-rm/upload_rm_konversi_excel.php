<?php
require_once '../../../class/phpexcel/PHPExcel.php';
$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LINE')
            ->setCellValue('B1', 'GRADE')
            ->setCellValue('C1', 'ITEM NO.')
            ->setCellValue('D1', 'MATERIAL NAME')
            ->setCellValue('E1', 'CONVERSION')
            ->setCellValue('F1', '%')
            ->setCellValue('G1', 'UPLOAD')
            ->setCellValue('H1', 'MIN')
            ->setCellValue('I1', 'STD')
            ->setCellValue('J1', 'MAX')
            ;
foreach(range('A','J') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('RM CONVERSION');

/*FORMAT LINE*/
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'LINE');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A2', 'LR01#1');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A3', 'LR03#1');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A4', 'LR03#2');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A5', 'LR06#1');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A6', 'LR06#2');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A7', 'LR06#3');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A8', 'LR06#4(T)');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A9', 'LR06#5');

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A10', 'LR06#6');
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('FORMAT LINE');

/*FORMAT CELL TYPE*/
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A1', 'CELL TYPE');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A2', 'C01');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A3', 'C01NC');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A4', 'G06');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A5', 'G06NC');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A6', 'G07');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A7', 'G07NC');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A8', 'G08');

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A9', 'G08NC');

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('FORMAT CELL TYPE');

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="format_rm_conversion.xls"');
$objWriter->save('php://output');
?>
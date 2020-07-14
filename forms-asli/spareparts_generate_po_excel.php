<?php
require_once '../class/phpexcel/PHPExcel.php';
$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SUPPLIER')
            ->setCellValue('B1', 'ITEM NO')
            ->setCellValue('C1', 'QTY')
            ->setCellValue('D1', 'PRICE')
            ->setCellValue('E1', 'ETA')
            ->setCellValue('F1', 'PAYMENT TERM DAY')
            ->setCellValue('G1', 'PAYMENT TERM DESCRIPTION')
            ;
foreach(range('A','G') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('GENERATE PO');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="format_spareparts_generate_po.xls"');
$objWriter->save('php://output');
?>
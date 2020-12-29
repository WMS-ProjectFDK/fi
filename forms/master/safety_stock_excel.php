<?php
require_once '../../class/phpexcel/PHPExcel.php';
$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ITEM NO')
            ->setCellValue('B1', 'PERIOD/MONTH (1-12)')
            ->setCellValue('C1', 'YEAR (YYYY)')
            ->setCellValue('D1', 'QTY')
            ->setCellValue('E1', 'BUNDLE (Y/N)')
            ->setCellValue('F1', 'BUNDLE=JML PER BUNDLE/PCS=1')
            ;
foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

include("../../connect/conn.php");
$sql = "select item_no, format(getdate(),'MM') as period, format(getdate(),'yyyy') as year, 0 as qty, upload, sts_bundle, bundle_qty
    from ztb_safety_stock 
    where year = 'MSTR'
    order by item_no asc";
$result = sqlsrv_query($connect, $sql);
$no=2;
while ($data=sqlsrv_fetch_object($result)) {
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $data->item_no)
                ->setCellValue('B'.$no, $data->period)
                ->setCellValue('C'.$no, $data->year)
                ->setCellValue('D'.$no, $data->qty)
                ->setCellValue('E'.$no, $data->sts_bundle)
                ->setCellValue('F'.$no, $data->bundle_qty);
    $no++;
}

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('SAFETY STOCK');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="format_safety_stock.xls"');
$objWriter->save('php://output');
?>
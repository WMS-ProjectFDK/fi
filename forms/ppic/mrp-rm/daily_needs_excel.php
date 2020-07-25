<?php
// Create By : Ueng hernama
// Date : 29-Sept-2017
// ID = 2
require_once '../../../class/phpexcel/PHPExcel.php';
include("../../../connect/conn.php");

$sql = "select distinct tipe from ztb_config_rm order by tipe asc";
$data = sqlsrv_query($connect, strtoupper($sql));


$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'TYPE')
            ->setCellValue('B1', 'YEAR')
            ->setCellValue('C1', 'MONTH')
            ->setCellValue('D1', 'QTY')
            ;

foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A1:D1')->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $sheet->getStyle('A1:D1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'B4B4B4')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
}

$row_xl =2;
while($row = sqlsrv_fetch_object($data)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$row_xl, $row->TIPE)
                ->setCellValue('B'.$row_xl,date('Y'))
                ->setCellValue('C'.$row_xl,date('m'))
                ->setCellValue('D'.$row_xl,0)
    ;
    $row_xl++;
}

$dt = date('M').'-'.date('y');

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('DAILY_NEEDS '.$dt);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="DAILY NEEDS '.$dt.'.xls"');
$objWriter->save('php://output');
?>
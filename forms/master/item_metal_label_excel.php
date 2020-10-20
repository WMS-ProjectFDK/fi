<?php
require_once '../../class/phpexcel/PHPExcel.php';

$objPHPExcel = new PHPExcel();
/*---------------------- SHEETT-1 ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ITEM NO.')
            ->setCellValue('B1', 'INKJET CODE (1 OR 2)')
            ->setCellValue('C1', 'PERIOD(YEAR)')
            ->setCellValue('D1', 'DRAWING NO.')
            ->setCellValue('E1', 'REMARK');
$sheet = $objPHPExcel->getActiveSheet(0);
$sheet->getStyle('A1:E1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:E1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:E1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        )
    )
);

$sheet->getStyle('A1:E11')->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

foreach(range('A','E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getSheet(0)->setTitle('DATA INKJET METAL LABEL');

$dt = date('M').'-'.date('y');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Template Metal Label'.$dt.'.xls"');
$objWriter->save('php://output');
exit;
?>
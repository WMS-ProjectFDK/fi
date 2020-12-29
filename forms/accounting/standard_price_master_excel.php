<?php
require_once '../../class/phpexcel/PHPExcel.php';
$objPHPExcel = new PHPExcel();

/*---------------------- SHEETT-1 ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ITEM NO.')
            ->setCellValue('B1', 'STANDARD PRICE');
$sheet = $objPHPExcel->getActiveSheet(0);
$sheet->getStyle('A1:B1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:B1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:B1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        )
    )
);

$sheet->getStyle('A1:B11')->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

foreach(range('A','B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getSheet(0)->setTitle('DATA');

/*---------------------- set sheet 1 active ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0);
$dt = date('M').'-'.date('y');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Template Standard Price '.$dt.'.xls"');
$objWriter->save('php://output');
exit;
?>
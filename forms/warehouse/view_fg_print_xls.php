<?php
// error_reporting(0);
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$data = file_get_contents("view_fg_print_result.json");
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// --------------------------------------------------------------------
$noRow = 1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'VIEW TRANSACTION FINISH GOODS');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':I'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':I'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'SLIP NO.')
            ->setCellValue('B'.$noRow, 'SLIP DATE')
            ->setCellValue('C'.$noRow, 'ITEM NO.')
            ->setCellValue('D'.$noRow, 'ITEM NAME')
            ->setCellValue('E'.$noRow, 'DESCRIPTION')
            ->setCellValue('F'.$noRow, 'QTY')
            ->setCellValue('G'.$noRow, 'WO NO.')
            ->setCellValue('H'.$noRow, 'PALLET NO.')
            ->setCellValue('I'.$noRow, 'APPROVAL DATE');

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':I'.$noRow)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

$noRow++;
foreach ($someArray as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noRow, $value['SLIP_NO'])
                ->setCellValue('B'.$noRow, $value['SLIP_DATE'])
                ->setCellValue('C'.$noRow, $value['ITEM_NO'])
                ->setCellValue('D'.$noRow, $value['ITEM_NAME'])
                ->setCellValue('E'.$noRow, $value['ITEM_DESCRIPTION'])
                ->setCellValue('F'.$noRow, $value['SLIP_QUANTITY'])
                ->setCellValue('G'.$noRow, $value['WO_NO'])
                ->setCellValue('H'.$noRow, $value['PLT_NO'])
                ->setCellValue('I'.$noRow, $value['APPROVAL_DATE']);
    
    $sheet->getStyle('A'.$noRow.':I'.$noRow)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $noRow++;    
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="VIEW-TRANS-FG.xlsx"');
$objWriter->save('php://output');
?>
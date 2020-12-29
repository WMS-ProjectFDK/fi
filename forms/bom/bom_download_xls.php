<?php
error_reporting(0);
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$data = file_get_contents("bom_download_result.json");
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
            ->setCellValue('A'.$noRow, 'BOM MAINTENANCE');

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'UPPER_ITEM_NO')
            ->setCellValue('B'.$noRow, 'UPPER_ITEM')
            ->setCellValue('C'.$noRow, 'UPPER_DESCRIPTION')
            ->setCellValue('D'.$noRow, 'DRAWING_NO')
            ->setCellValue('E'.$noRow, 'DRAWING_REV')
            ->setCellValue('F'.$noRow, 'APPLICABLE_MODEL')
            ->setCellValue('G'.$noRow, 'CATALOG_NO')
            ->setCellValue('H'.$noRow, 'UNIT')
            ->setCellValue('I'.$noRow, 'LOWER_ITEM_NO')
            ->setCellValue('J'.$noRow, 'LOWER_ITEM')
            ->setCellValue('K'.$noRow, 'LOWER_DESCRIPTION')
            ->setCellValue('L'.$noRow, 'LEVEL_NO')
            ->setCellValue('M'.$noRow, 'LINE_NO')
            ->setCellValue('N'.$noRow, 'REFERENCE_NUMBER')
            ->setCellValue('O'.$noRow, 'QUANTITY')
            ->setCellValue('P'.$noRow, 'UNIT')
            ->setCellValue('Q'.$noRow, 'QUANTITY_BASE')
            ->setCellValue('R'.$noRow, 'FAILURE_RATE')
            ->setCellValue('S'.$noRow, 'USER_SUPPLY_FLAG')
            ->setCellValue('T'.$noRow, 'SUBCON_SUPPLY')
;

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':T'.$noRow)->applyFromArray(
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
                ->setCellValue('A'.$noRow, $value['UPPER_ITEM_NO'])
                ->setCellValue('B'.$noRow, $value['UPPER_ITEM'])
                ->setCellValue('C'.$noRow, $value['UPPER_DESCRIPTION'])
                ->setCellValue('D'.$noRow, $value['DRAWING_NO'])
                ->setCellValue('E'.$noRow, $value['DRAWING_REV'])
                ->setCellValue('F'.$noRow, $value['APPLICABLE_MODEL'])
                ->setCellValue('G'.$noRow, $value['CATALOG_NO'])
                ->setCellValue('H'.$noRow, $value['UNIT'])
                ->setCellValue('I'.$noRow, $value['LOWER_ITEM_NO'])
                ->setCellValue('J'.$noRow, $value['LOWER_ITEM'])
                ->setCellValue('K'.$noRow, $value['LOWER_DESCRIPTION'])
                ->setCellValue('L'.$noRow, $value['LEVEL_NO'])
                ->setCellValue('M'.$noRow, $value['LINE_NO'])
                ->setCellValue('N'.$noRow, $value['REFERENCE_NUMBER'])
                ->setCellValue('O'.$noRow, $value['QUANTITY'])
                ->setCellValue('P'.$noRow, $value['UNIT'])
                ->setCellValue('Q'.$noRow, $value['QUANTITY_BASE'])
                ->setCellValue('R'.$noRow, $value['FAILURE_RATE'])
                ->setCellValue('S'.$noRow, $value['USER_SUPPLY_FLAG'])
                ->setCellValue('T'.$noRow, $value['SUBCON_SUPPLY'])
    ;
    
    $sheet->getStyle('A'.$noRow.':T'.$noRow)->applyFromArray(
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
header('Content-Disposition: attachment; filename="DOWNLOAD-MASTER-BOM.xlsx"');
$objWriter->save('php://output');
?>
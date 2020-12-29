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
$data = file_get_contents("company_download_result.json");
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
            ->setCellValue('A'.$noRow, 'COMPANY MAINTENANCE');

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'COMPANY CODE')
            ->setCellValue('B'.$noRow, 'COMPANY')
            ->setCellValue('C'.$noRow, 'COMPANY TYPE')
            ->setCellValue('D'.$noRow, 'TYPE')
            ->setCellValue('E'.$noRow, 'COUNTRY CODE')
            ->setCellValue('F'.$noRow, 'COUNTRY')
            ->setCellValue('G'.$noRow, 'ADDRESS-1')
            ->setCellValue('H'.$noRow, 'ADDRESS-2')
            ->setCellValue('I'.$noRow, 'ADDRESS-3')
            ->setCellValue('J'.$noRow, 'ADDRESS-4')
            ->setCellValue('K'.$noRow, 'ATTN')
            ->setCellValue('L'.$noRow, 'CONTRACT SEQ')
            ->setCellValue('M'.$noRow, 'TERM')
            ->setCellValue('N'.$noRow, 'PAYMENT')
            ->setCellValue('O'.$noRow, 'BONDED TYPE')
;

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':O'.$noRow)->applyFromArray(
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
                ->setCellValue('A'.$noRow, $value['COMPANY_CODE'])
                ->setCellValue('B'.$noRow, $value['COMPANY'])
                ->setCellValue('C'.$noRow, $value['COMPANY_TYPE'])
                ->setCellValue('D'.$noRow, $value['TYPE'])
                ->setCellValue('E'.$noRow, $value['COUNTRY_CODE'])
                ->setCellValue('F'.$noRow, $value['COUNTRY'])
                ->setCellValue('G'.$noRow, $value['ADDRESS1'])
                ->setCellValue('H'.$noRow, $value['ADDRESS2'])
                ->setCellValue('I'.$noRow, $value['ADDRESS3'])
                ->setCellValue('J'.$noRow, $value['ADDRESS4'])
                ->setCellValue('K'.$noRow, $value['ATTN'])
                ->setCellValue('L'.$noRow, $value['CONTRACT_SEQ'])
                ->setCellValue('M'.$noRow, $value['TTERM'])
                ->setCellValue('N'.$noRow, $value['PAYMENT'])
                ->setCellValue('O'.$noRow, $value['BONDED_TYPE'])
    ;
    
    $sheet->getStyle('A'.$noRow.':O'.$noRow)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );

    $sheet->getStyle('G'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $sheet->getStyle('H'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $sheet->getStyle('I'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $sheet->getStyle('J'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $noRow++;    
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="DOWNLOAD-MASTER-COMPANY.xlsx"');
$objWriter->save('php://output');
?>
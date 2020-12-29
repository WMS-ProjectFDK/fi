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
$data = file_get_contents("sales_price_download_result.json");
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
            ->setCellValue('A'.$noRow, 'SALES PRICE DOWNLOAD');

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'CUSTOMER CODE')
            ->setCellValue('B'.$noRow, 'COMPANY')
            ->setCellValue('C'.$noRow, 'CUST-PART_NO')
            ->setCellValue('D'.$noRow, 'ITEM NO')
            ->setCellValue('E'.$noRow, 'ITEM')
            ->setCellValue('F'.$noRow, 'DESCRIPTION')
            ->setCellValue('G'.$noRow, 'SP_CURR')
            ->setCellValue('H'.$noRow, 'SP')
;

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':H'.$noRow)->applyFromArray(
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
                ->setCellValue('A'.$noRow, $value['CUSTOMER_CODE'])
                ->setCellValue('B'.$noRow, $value['COMPANY'])
                ->setCellValue('C'.$noRow, $value['CUSTOMER_PART_NO'])
                ->setCellValue('D'.$noRow, $value['ITEM_NO'])
                ->setCellValue('E'.$noRow, $value['ITEM'])
                ->setCellValue('F'.$noRow, $value['DESCRIPTION'])
                ->setCellValue('G'.$noRow, $value['CURR_MARK'])
                ->setCellValue('H'.$noRow, $value['U_PRICE'])
    ;
    
    $sheet->getStyle('A'.$noRow.':H'.$noRow)->applyFromArray(
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
header('Content-Disposition: attachment; filename="DOWNLOAD-MASTER-SALES-PRICE.xlsx"');
$objWriter->save('php://output');
?>
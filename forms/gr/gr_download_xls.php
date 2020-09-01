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
$data = file_get_contents("gr_download_result.json");
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
            ->setCellValue('A'.$noRow, 'GOODS RECEIVE INVOICE REPORT');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':T'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':T'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'SUPPLIER CODE')
            ->setCellValue('B'.$noRow, 'SUPPLIER')
            ->setCellValue('C'.$noRow, 'COUNTRY OF SUPPLIER')
            ->setCellValue('D'.$noRow, 'DELIVERY SLIP NO')
            ->setCellValue('E'.$noRow, 'ITM')
            ->setCellValue('F'.$noRow, 'PO NO.')
            ->setCellValue('G'.$noRow, 'ITM')
            ->setCellValue('H'.$noRow, 'DATE')
            ->setCellValue('I'.$noRow, 'INV. NO.')
            ->setCellValue('J'.$noRow, 'INV. DATE')
            ->setCellValue('K'.$noRow, 'ITEM NO')
            ->setCellValue('L'.$noRow, 'FDK PART')
            ->setCellValue('M'.$noRow, 'ORIGIN CODE')
            ->setCellValue('N'.$noRow, 'COUNTRY')
            ->setCellValue('O'.$noRow, 'QTY')
            ->setCellValue('P'.$noRow, 'UNIT')
            ->setCellValue('Q'.$noRow, 'SLIP TYPE')
            ->setCellValue('R'.$noRow, 'SLIP NAME')
            ->setCellValue('S'.$noRow, 'UPTO DATE')
            ->setCellValue('T'.$noRow, 'REG DATE');

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
                ->setCellValue('A'.$noRow, $value['SUPPLIER_CODE'])
                ->setCellValue('B'.$noRow, $value['SUPPLIER'])
                ->setCellValue('C'.$noRow, $value['COUNTRY_SUPPLIER'])
                ->setCellValue('D'.$noRow, $value['GR_NO'])
                ->setCellValue('E'.$noRow, $value['GR_LINE_NO'])
                ->setCellValue('F'.$noRow, $value['PO_NO'])
                ->setCellValue('G'.$noRow, $value['PO_LINE_NO'])
                ->setCellValue('H'.$noRow, $value['GR_DATE'])
                ->setCellValue('I'.$noRow, $value['INV_NO'])
                ->setCellValue('J'.$noRow, $value['INV_DATE'])
                ->setCellValue('K'.$noRow, $value['ITEM_NO'])
                ->setCellValue('L'.$noRow, $value['ITEM'])
                ->setCellValue('M'.$noRow, $value['ORIGIN_CODE'])
                ->setCellValue('N'.$noRow, $value['COUNTRY'])
                ->setCellValue('O'.$noRow, $value['QTY'])
                ->setCellValue('P'.$noRow, $value['UNIT'])
                ->setCellValue('Q'.$noRow, $value['SLIP_TYPE'])
                ->setCellValue('R'.$noRow, $value['SLIP_NAME'])
                ->setCellValue('S'.$noRow, $value['UPTO_DATE'])
                ->setCellValue('T'.$noRow, $value['REG_DATE']);
    
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
header('Content-Disposition: attachment; filename="DOWNLOAD-GR.xlsx"');
$objWriter->save('php://output');
?>
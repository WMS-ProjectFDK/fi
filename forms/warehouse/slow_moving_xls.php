<?php
// error_reporting(0);
session_start();
ini_set('memory_limit', '-1');
set_time_limit(0);
include("../../connect/conn.php");
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$user = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$date=date("d M y / H:i:s",time());
$date2=date("d M y",time());
$data = file_get_contents('slow_moving_'.$user.'.json');

$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$noRow = 1;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'NO')
            ->setCellValue('B'.$noRow, 'ITEM NO'.PHP_EOL.'DESCRIPTION')
            ->setCellValue('C'.$noRow, 'LAST'.PHP_EOL.'INVENTORY')
            ->setCellValue('D'.$noRow, 'STANDARD'.PHP_EOL.'PRICE')
            ->setCellValue('E'.$noRow, 'SUPPLIER')
            ->setCellValue('F'.$noRow, 'RECEIVE'.PHP_EOL.'NO.')
            ->setCellValue('G'.$noRow, 'RECEIVE'.PHP_EOL.'QTY')
            ->setCellValue('H'.$noRow, 'RECEIVE'.PHP_EOL.'DATE')
            ->setCellValue('I'.$noRow, 'SLIP'.PHP_EOL.'NAME')
            ->setCellValue('J'.$noRow, 'SLIP'.PHP_EOL.'QTY')
            ->setCellValue('K'.$noRow, 'SLIP'.PHP_EOL.'DATE');

$objPHPExcel->getActiveSheet()->getStyle('B'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('J'.$noRow)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('K'.$noRow)->getAlignment()->setWrapText(true);

foreach(range('A','K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A'.$noRow.':K'.$noRow)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$noRow.':K'.$noRow)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4D648D')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12,
            'color' => array('rgb' => 'FFFFFF'),
        )
    )
);
$noRow++;
$noUrut = 1;

foreach ($someArray as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noRow, $noUrut)
                ->setCellValue('B'.$noRow, $value['ITEM_NO'].' - '.$value['DESCRIPTION'])
                ->setCellValue('C'.$noRow, $value['LAST_INVENTORY'])
                ->setCellValue('D'.$noRow, $value['STANDARD_PRICE'])
                ->setCellValue('E'.$noRow, $value['SUPPLIER_CODE'].' - '.$value['COMPANY'])
                ->setCellValue('F'.$noRow, $value['GR_NO'])
                ->setCellValue('G'.$noRow, $value['QTY'])
                ->setCellValue('H'.$noRow, $value['LAST_DATE_BUY'])
                ->setCellValue('I'.$noRow, $value['TRANSACTION_TYPE'])
                ->setCellValue('J'.$noRow, $value['TRANSACTION_QTY'])
                ->setCellValue('K'.$noRow, $value['TRANSACTION_DATE']);

    foreach(range('A','K') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->getStyle('A'.$noRow.':K'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
    
    if($noRow % 2 == 0){
        $sheet->getStyle('A'.$noRow.':K'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D0E1F9')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => FALSE,
                    'size'  => 11
                )
            )
        );
    }else{
        $sheet->getStyle('A'.$noRow.':K'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F4F4F8')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => FALSE,
                    'size'  => 11
                )
            )
        );
    }

    $noUrut++;
    $noRow++;
}

$objPHPExcel->setActiveSheetIndex();
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="SLOW_MOVING.xlsx"');
$objWriter->save('php://output');
?>
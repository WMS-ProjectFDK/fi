<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
include("../connect/conn.php");
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$book = isset($_REQUEST['book']) ? strval($_REQUEST['book']) : '';
$cont = isset($_REQUEST['cont']) ? strval($_REQUEST['cont']) : '';

// Get the contents of the JSON file 
$data = file_get_contents("sscc_booking_result.json");

$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

// $where = " where booking='$book' and container='$cont'";

// $sql = "select * from ztb_sscc 
//     $where 
//     order by sscc asc";
// $data = oci_parse($connect, $sql);
// oci_execute($data);


$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$noRow = 1;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'BOOKING NO.')
            ->setCellValue('B'.$noRow, 'CONTAINER NO.')
            ->setCellValue('C'.$noRow, 'PO NO.')
            ->setCellValue('D'.$noRow, 'ASIN')
            ->setCellValue('E'.$noRow, 'SSCC');
foreach(range('A','E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A'.$noRow.':E'.$noRow)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$noRow.':E'.$noRow)->applyFromArray(
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

//while ($dt=oci_fetch_object($data) ){
foreach ($someArray as $key => $value) {
  if($book == $value['BOOKING'] AND $cont == $value['CONTAINER']){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noRow, $value['BOOKING'])//$dt->BOOKING)
                ->setCellValue('B'.$noRow, $value['CONTAINER'])//$dt->CONTAINER)
                ->setCellValue('C'.$noRow, $value['PO'])//$dt->PO)
                ->setCellValue('D'.$noRow, $value['ASIN'])//$dt->ASIN)
                ->setCellValue('E'.$noRow, "'".$value['SSCC']);//$dt->SSCC);

    foreach(range('A','E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->getStyle('A'.$noRow.':E'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
    
    if($noRow % 2 == 0){
        $sheet->getStyle('A'.$noRow.':E'.$noRow)->applyFromArray(
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
        $sheet->getStyle('A'.$noRow.':E'.$noRow)->applyFromArray(
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

    // menghilangkan single quotes 
    $sheet->setCellValueExplicit('E'.$noRow, $value['SSCC'], PHPExcel_Cell_DataType::TYPE_STRING);
    // merubah format cell menjadi text
    $sheet->getStyle('E'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $noRow++;
  }
}

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$book.' '.$cont.'.xlsx"');
$objWriter->save('php://output');
?>
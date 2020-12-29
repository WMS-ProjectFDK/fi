<?php
ini_set('memory_limit', '-1');
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

session_start();
$user = $_SESSION['id_wms'];
$data = file_get_contents('bc_gr_pglosas_result_'.$user.'.json');

$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'BC DOC.')
            ->setCellValue('C1', 'BC NO.')
            ->setCellValue('D1', 'RECEIVE DATE')
            ->setCellValue('E1', 'RECEIVE NO.')
            ->setCellValue('F1', 'SUPPLIER')
            ->setCellValue('G1', 'AMOUNT (O)');
            
foreach(range('A','G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:G1')->applyFromArray(
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

$noUrut = 1;    
$no=2;
$p = '';
$rev = 0;
foreach ($someArray as $key => $data) {
    $c = $data['CURR_MARK'];
    $a = $data['AMT_O'];
    $AMT = $c.' '.number_format($a,5);
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $noUrut)
                ->setCellValue('B'.$no, number_format($data['BC_DOC'],1))
                ->setCellValue('C'.$no, $data['BC_NO'])
                ->setCellValue('D'.$no, $data['GR_DATE'])
                ->setCellValue('E'.$no, $data['GR_NO'])
                ->setCellValue('F'.$no, $data['COMPANY'])
                ->setCellValue('G'.$no, $AMT);
    
    if($no % 2 == 0){
        $sheet->getStyle('A'.$no.':G'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }else{
        $sheet->getStyle('A'.$no.':G'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FCE4D6')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }

    $objPHPExcel->getActiveSheet()->getStyle('B'.$no)->getNumberFormat()->setFormatCode('#,##0.0');
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

    $no++;      $noUrut++;
}   

//$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('BC_GR_PGLOSAS');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 // Menambahkan file gambar pada document excel pada kolom B2
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('FDK');
$objDrawing->setDescription('FDK');
$objDrawing->setPath('../images/fdk8.png');
$objDrawing->setWidth('100px');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="BC_GR_PGLOSAS.xls"');

// Write file to the browser
$objWriter->save('php://output');
?>
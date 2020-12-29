<?php
ini_set('memory_limit', '-1');
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

session_start();
$user = $_SESSION['id_wms'];
$data = file_get_contents('bc_sales_pglosas_result_'.$user.'.json');

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
            ->setCellValue('B1', 'PEB NO.')
            ->setCellValue('C1', 'PE NO.')
            ->setCellValue('D1', 'INV DATE')
            ->setCellValue('E1', 'CUSTOMER')
            ->setCellValue('F1', 'INV NO.')
            ->setCellValue('G1', 'AMOUNT (O)')
            ->setCellValue('H1', 'STATUS');
            
foreach(range('A','H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A1:H1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:H1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:H1')->applyFromArray(
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
    $AMT = $c.' '.$a;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $noUrut)
                ->setCellValue('B'.$no, $data['PEB_NO'])
                ->setCellValue('C'.$no, $data['PE_NO'])
                ->setCellValue('D'.$no, $data['INV_DATE'])
                ->setCellValue('E'.$no, $data['CUSTOMER'])
                ->setCellValue('F'.$no, $data['DO_NO'])
                ->setCellValue('G'.$no, $AMT)
                ->setCellValue('H'.$no, $data['STS']);
    
    if($no % 2 == 0){
        $sheet->getStyle('A'.$no.':H'.$no)->applyFromArray(
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
        $sheet->getStyle('A'.$no.':H'.$no)->applyFromArray(
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

    $objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0');

    $no++;      $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('BC_SALES_WMS');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="BC_SALES_WMS.xls"');

// Write file to the browser
$objWriter->save('php://output');
?>
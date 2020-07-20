<?php
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$book = isset($_REQUEST['book']) ? strval($_REQUEST['book']) : '';
$cont = isset($_REQUEST['cont']) ? strval($_REQUEST['cont']) : '';

// Get the contents of the JSON file 
// $data = file_get_contents("mps_download_result.json");
// $dt = json_decode(json_encode($data));
// $str = preg_replace('/\\\\\"/',"\"", $dt);
// $someArray = json_decode($str,true);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// --------------------------------------------------------------------
$noRow = 1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'MPS');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':D'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':D'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'ITEM NO.')
            ->setCellValue('B'.$noRow, 'ITEM NAME')
            ->setCellValue('C'.$noRow, 'BATTERY TYPE')
            ->setCellValue('D'.$noRow, 'CELL GRADE')
            ->setCellValue('E'.$noRow, 'PO NO')
            ->setCellValue('F'.$noRow, 'PO LINE NO.')
            ->setCellValue('G'.$noRow, 'WORK ORDER')
            ->setCellValue('H'.$noRow, 'CONSIGNEE')
            ->setCellValue('I'.$noRow, 'PACKAGING TYPE')
            ->setCellValue('J'.$noRow, 'DATE CODE')
            ->setCellValue('K'.$noRow, 'CR DATE')
            ->setCellValue('L'.$noRow, 'REQUESTED ETD')
            ->setCellValue('M'.$noRow, 'STATUS')
            ->setCellValue('N'.$noRow, 'LABEL ITEM NO.')
            ->setCellValue('O'.$noRow, 'LABEL NAME')
            ->setCellValue('P'.$noRow, 'QTY')
            ->setCellValue('Q'.$noRow, 'MAN POWER')
            ->setCellValue('R'.$noRow, 'OT(MAN SECOND/PCS')
            ->setCellValue('S'.$noRow, 'PACKAGING GROUPING')
            ->setCellValue('T'.$noRow, 'CAPACITY (GROUP/DAY)')
            ->setCellValue('U'.$noRow, 'REMARK')
            ;

            $sheet = $objPHPExcel->getActiveSheet();
            // foreach(range('A',$noRow) as $columnID) {
            //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
            // }

            // $sheet->getStyle('A'.$noRow.':U'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            // $sheet->getStyle('A'.$noRow.':U'.$noRow)->getAlignment()->applyFromArray(
            //     array(
            //         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            //         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            //     )
            // );
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
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
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFD966')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
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
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="MPS.xls"');
$objWriter->save('php://output');
?>
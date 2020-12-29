<?php
// error_reporting(0);
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

$period = isset($_REQUEST['period']) ? strval($_REQUEST['period']) : '';
$jns_report = isset($_REQUEST['jns_report']) ? strval($_REQUEST['jns_report']) : '';

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$Arr_sheet = array('INVOICES','INVOICE DETAILS','INVOICE PAYMENT SCHEDULE');
$s=0;

$sql1 = "select cnbtch, idvend, idinvc, '1' as texttrx, '12' as idtrx,
    invcdesc, idacctset, dateinvc, fiscyr, fiscper, codecurn, EX_RATE as EXCHRATEHC, amtinvctot, duedate as DATEDUE
    from zvw_purchase_import
    where ACCOUNTING_MONTH='$period'";
// echo $sql1;
$data1 = sqlsrv_query($connect, strtoupper($sql1));

$sql2 = "select cnbtch, '140-305-100' as idglacct, AMTINVCTOT as AMTDIST, textdesc, invcdesc
    from zvw_purchase_import
    where ACCOUNTING_MONTH='$period'";
$data2 = sqlsrv_query($connect, strtoupper($sql2));

$sql3 = "select cnbtch, duedate as datedue, amtinvctot, invcdesc
    from zvw_purchase_import
    where ACCOUNTING_MONTH='$period'";
$data3 = sqlsrv_query($connect, strtoupper($sql3));

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


$noRow = 1;
while ($s < 3) {
    if ($Arr_sheet[$s] == "INVOICES"){
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'CNTBTCH')
                    ->setCellValue('B1', 'CNTITEM')
                    ->setCellValue('C1', 'IDVEND')
                    ->setCellValue('D1', 'IDINVC')
                    ->setCellValue('E1', 'TEXTTRX')
                    ->setCellValue('F1', 'IDTRX')
                    ->setCellValue('G1', 'INVCDESC')
                    ->setCellValue('H1', 'INVCAPPLTO')
                    ->setCellValue('I1', 'IDACCTSET')
                    ->setCellValue('J1', 'DATEINVC')
                    ->setCellValue('K1', 'FISCYR')
                    ->setCellValue('L1', 'FISCPER')
                    ->setCellValue('M1', 'CODECURN')
                    ->setCellValue('N1', 'EXCHRATEHC')
                    ->setCellValue('O1', 'AMTINVCTOT')
                    ->setCellValue('P1', 'AMTGROSDST')
                    ->setCellValue('Q1', 'AMTDUETC')
                    ->setCellValue('R1', 'AMTGROSTOT')
                    ->setCellValue('S1', 'DATEDUE')
        ;
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:S1')->applyFromArray(
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

        $no1=2;
        $cntitem=1;     $inv = '';
        while ($row1 = sqlsrv_fetch_object($data1)){
            $inv_a = $row1->IDINVC;
            
            if($no1 != 2){
                if ($inv == $inv_a){
                    $cntitem;
                }else{
                    $cntitem++;
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no1, $row1->CNBTCH)
                        ->setCellValue('B'.$no1, $cntitem)
                        ->setCellValue('C'.$no1, $row1->IDVEND)
                        ->setCellValue('D'.$no1, $row1->IDINVC)
                        ->setCellValue('E'.$no1, $row1->TEXTTRX)
                        ->setCellValue('F'.$no1, $row1->IDTRX)
                        ->setCellValue('G'.$no1, $row1->INVCDESC)
                        ->setCellValue('H'.$no1, '')
                        ->setCellValue('I'.$no1, $row1->IDACCTSET)
                        ->setCellValue('J'.$no1, $row1->DATEINVC)
                        ->setCellValue('K'.$no1, $row1->FISCYR)
                        ->setCellValue('L'.$no1, $row1->FISCPER)
                        ->setCellValue('M'.$no1, $row1->CODECURN)
                        ->setCellValue('N'.$no1, $row1->EXCHRATEHC)
                        ->setCellValue('O'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('P'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('Q'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('R'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('S'.$no1, $row1->DATEDUE);
            
            $sheet->getStyle('A'.$no1.':S'.$no1)->applyFromArray(
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
            $no1++;
            $inv = $inv_a;
        }

        foreach(range('A','S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Invoices', $objPHPExcel->getActiveSheet(), 'A1:S1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Invoices');
    }elseif ($Arr_sheet[$s] == "INVOICE DETAILS"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'CNTBTCH')
                    ->setCellValue('B1', 'CNTITEM')
                    ->setCellValue('C1', 'CNTLINE')
                    ->setCellValue('D1', 'IDGLACCT') 
                    ->setCellValue('E1', 'AMTDIST')
                    ->setCellValue('F1', 'TEXTDESC');
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:F1')->applyFromArray(
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

        $no2=2;
        $cntitem2=1;        $invcdesc = '';
        $cntline2=1;        $textdesc = '';

        while ($row2 = sqlsrv_fetch_object($data2)){
            $invcdesc_a = $row2->INVCDESC;
            $textdesc_a = $row2->TEXTDESC;

            if($no2 != 2){
                if ($invcdesc == $invcdesc_a){
                    $cntitem2;

                    if ($textdesc == $textdesc_a){
                        $cntline2;
                    }else{
                        $cntline2++;
                    }
                }else{
                    $cntitem2++;
                    $cntline2=1;
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no2, $row2->CNBTCH)
                        ->setCellValue('B'.$no2, $cntitem2)
                        ->setCellValue('C'.$no2, $cntline2)
                        ->setCellValue('D'.$no2, $row2->IDGLACCT)
                        ->setCellValue('E'.$no2, $row2->AMTDIST)
                        ->setCellValue('F'.$no2, $row2->TEXTDESC);
            
            $sheet->getStyle('A'.$no2.':F'.$no2)->applyFromArray(
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
            $no2++;
            $invcdesc == $invcdesc_a;
            $textdesc == $textdesc_a;
        }

        foreach(range('A','F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Invoice_Details', $objPHPExcel->getActiveSheet(), 'A1:F1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Invoice_Details');
    }elseif ($Arr_sheet[$s] == "INVOICE PAYMENT SCHEDULE"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'CNTBTCH')
                    ->setCellValue('B1', 'CNTITEM')
                    ->setCellValue('C1', 'CNTPAYM')
                    ->setCellValue('D1', 'DATEDUE')
                    ->setCellValue('E1', 'AMTDUE');
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:E1')->applyFromArray(
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

        $no3 = 2;
        $cntitem3=1;        $invcdesc3 = '';
        while ($row3 = sqlsrv_fetch_object($data3)){
            $inv_c = $row3->INVCDESC;
            
            if($no3 != 2){
                if ($invcdesc3 == $inv_c){
                    $cntitem3;
                }else{
                    $cntitem3++;
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no3, $row3->CNBTCH)
                        ->setCellValue('B'.$no3, $cntitem3)
                        ->setCellValue('C'.$no3, '1')
                        ->setCellValue('D'.$no3, $row3->DATEDUE)
                        ->setCellValue('E'.$no3, $row3->AMTINVCTOT);

            $sheet->getStyle('A'.$no3.':E'.$no3)->applyFromArray(
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
            $no3++;
            $invcdesc3 == $inv_c;
        }

        foreach(range('A','E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Invoice_Payment_Schedules', $objPHPExcel->getActiveSheet(), 'A1:E1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Invoice_Payment_Schedules');
    }
    $s++;
}

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="JURNAL-PURCHASE-IMPORT-'.$period.'.xlsx"');
$objWriter->save('php://output');
?>
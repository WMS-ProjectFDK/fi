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

$sql1 = "select cnbtch, idcust, idinvc, '1' as texttrx, '12' as idtrx, TERMCODE, invcdesc, idacctset, dateinvc, 
    fiscyr, fiscper, codecurn, EX_RATE as EXCHRATEHC, duedate as datedue, SUM(amtinvctot) AS amtinvctot
    from zvw_sales_accpac
    where ACCOUNTING_MONTH='$period'
    GROUP BY cnbtch, idcust, idinvc, TERMCODE, invcdesc, idacctset, dateinvc, fiscyr, fiscper, codecurn, EX_RATE, duedate
    ORDER BY IDINVC";
$data1 = sqlsrv_query($connect, strtoupper($sql1));

$sql2 = "select cnbtch, '400-002-100' as IDACCTREV, AMTINVCTOT as AMTEXTN, textdesc, invcdesc, IDINVC
    from zvw_sales_accpac
    where ACCOUNTING_MONTH='$period'
    ORDER BY IDINVC";
$data2 = sqlsrv_query($connect, strtoupper($sql2));

$sql3 = "select cnbtch, duedate as datedue, SUM(amtinvctot) AS amtinvctot, invcdesc, idinvc
    from zvw_sales_accpac
    where ACCOUNTING_MONTH='$period'
    GROUP BY cnbtch, duedate, invcdesc, idinvc
    ORDER BY IDINVC";
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
                    ->setCellValue('C1', 'IDCUST')
                    ->setCellValue('D1', 'IDINVC')
                    ->setCellValue('E1', 'IDTRX')
                    ->setCellValue('F1', 'TEXTTRX')
                    ->setCellValue('G1', 'INVCDESC')
                    ->setCellValue('H1', 'INVCAPPLTO')
                    ->setCellValue('I1', 'DATEINVC')
                    ->setCellValue('J1', 'IDACCTSET')
                    ->setCellValue('K1', 'FISCYR')
                    ->setCellValue('L1', 'FISCPER')
                    ->setCellValue('M1', 'CODECURN ')
                    ->setCellValue('N1', 'TERMCODE')
                    ->setCellValue('O1', 'DATEDUE')
                    ->setCellValue('P1', 'AMTNETTOT')
                    ->setCellValue('Q1', 'AMTGROSTOT')
                    ->setCellValue('R1', 'EXCHRATEHC')
                    ->setCellValue('S1', 'RATEDATERC')
                    ->setCellValue('T1', 'RATEDATEHC')
                    ->setCellValue('U1', 'AMTDUE')
                    ->setCellValue('V1', 'CUSTPO');
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:V1')->applyFromArray(
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
                        ->setCellValue('C'.$no1, $row1->IDCUST)
                        ->setCellValue('D'.$no1, $row1->IDINVC)
                        ->setCellValue('E'.$no1, $row1->IDTRX)
                        ->setCellValue('F'.$no1, $row1->TEXTTRX)
                        ->setCellValue('G'.$no1, $row1->INVCDESC)
                        ->setCellValue('H'.$no1, '')
                        ->setCellValue('I'.$no1, $row1->DATEINVC)
                        ->setCellValue('J'.$no1, $row1->IDACCTSET)
                        ->setCellValue('K'.$no1, $row1->FISCYR)
                        ->setCellValue('L'.$no1, $row1->FISCPER)
                        ->setCellValue('M'.$no1, $row1->CODECURN)
                        ->setCellValue('N'.$no1, $row1->TERMCODE)
                        ->setCellValue('O'.$no1, $row1->DATEDUE)
                        ->setCellValue('P'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('Q'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('R'.$no1, $row1->EXCHRATEHC)
                        ->setCellValue('S'.$no1, $row1->DATEINVC)
                        ->setCellValue('T'.$no1, $row1->DATEINVC)
                        ->setCellValue('U'.$no1, $row1->AMTINVCTOT)
                        ->setCellValue('V'.$no1, '');
            
            $sheet->getStyle('A'.$no1.':V'.$no1)->applyFromArray(
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
        
        foreach(range('A','V') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Invoices', $objPHPExcel->getActiveSheet(), 'A1:V1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Invoices');
    }elseif ($Arr_sheet[$s] == "INVOICE DETAILS"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'CNTBTCH')
                    ->setCellValue('B1', 'CNTITEM')
                    ->setCellValue('C1', 'CNTLINE')
                    ->setCellValue('D1', 'IDACCTREV') 
                    ->setCellValue('E1', 'AMTEXTN')
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
            $invcdesc_a = $row2->IDINVC;
            $textdesc_a = $row2->TEXTDESC;

            if($no2 != 2){
                if ($invcdesc == $invcdesc_a){
                    $cntitem2;

                    if ($textdesc == $textdesc_a){
                        $cntline2++;
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
                        ->setCellValue('D'.$no2, $row2->IDACCTREV)
                        ->setCellValue('E'.$no2, $row2->AMTEXTN)
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
            $invcdesc = $invcdesc_a;
            $textdesc = $textdesc_a;
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
                    ->setCellValue('E1', 'AMTDUE')
                    ->setCellValue('F1', '')
                    ->setCellValue('G1', '');
        $sheet = $objPHPExcel->getActiveSheet();
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

        $no3 = 2;
        $cntitem3=1;        $invcdesc3 = '';
        while ($row3 = sqlsrv_fetch_object($data3)){
            $inv_c = $row3->IDINVC;
            
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
                        ->setCellValue('E'.$no3, $row3->AMTINVCTOT)
                        ->setCellValue('F'.$no3, $row3->INVCDESC)
                        ->setCellValue('G'.$no3, $row3->IDINVC);

            $sheet->getStyle('A'.$no3.':G'.$no3)->applyFromArray(
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
            $invcdesc3 = $inv_c;
        }

        foreach(range('A','G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Invoice_Payment_Schedules', $objPHPExcel->getActiveSheet(), 'A1:G1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Invoice_Payment_Schedules');
    }
    $s++;
}

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="JURNAL-SALES-'.$period.'.xlsx"');
$objWriter->save('php://output');
?>
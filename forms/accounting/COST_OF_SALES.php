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

$Arr_sheet = array('Journal_Headers','Journal_Details','Journal_Detail_Optional_Fields');
$s=0;

$sql1 = "select distinct cnbtch as batchid, btchentry, srceledger, srcetype, fiscyr, fiscper as FSCSPERD, jrnldesc, dateentry 
    from ZVW_COST_OF_SALES
    where ACCOUNTING_MONTH='$period'";
// echo $sql1;
$data1 = sqlsrv_query($connect, strtoupper($sql1));

$sql2 = "select cnbtch as BATCHNBR, journalid, TRANSNBR, acctid, transamt, '2' as SCURNDEC, transamt as SCURNAMT, HCURNCODE,
    'TX' as RATETYPE, SCURNCODE, RATEDATE, CONVRATE, TRANSDESC, TRANSREF, TRANSDATE, SRCELDGR, SRCETYPE, '' AS COMMENT
    from ZVW_COST_OF_SALES
    where ACCOUNTING_MONTH='$period'";
$data2 = sqlsrv_query($connect, strtoupper($sql2));

// $sql3 = "select cnbtch, duedate as datedue, SUM(amtinvctot) AS amtinvctot, invcdesc, idinvc
//     from zvw_sales_accpac
//     where ACCOUNTING_MONTH='$period'
//     GROUP BY cnbtch, duedate, invcdesc, idinvc
//     ORDER BY IDINVC";
// $data3 = sqlsrv_query($connect, strtoupper($sql3));

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


$noRow = 1;
while ($s < 3) {
    if ($Arr_sheet[$s] == "Journal_Headers"){
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATCHID')
                    ->setCellValue('B1', 'BTCHENTRY')
                    ->setCellValue('C1', 'SRCELEDGER')
                    ->setCellValue('D1', 'SRCETYPE')
                    ->setCellValue('E1', 'FSCSYR')
                    ->setCellValue('F1', 'FSCSPERD')
                    ->setCellValue('G1', 'JRNLDESC')
                    ->setCellValue('H1', 'DATEENTRY')
                    ;
        $sheet = $objPHPExcel->getActiveSheet();
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
        $no1=2;
        while ($row1 = sqlsrv_fetch_object($data1)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no1, "'".$row1->BATCHID)
                        ->setCellValue('B'.$no1, "'".$row1->BTCHENTRY)
                        ->setCellValue('C'.$no1, $row1->SRCELEDGER)
                        ->setCellValue('D'.$no1, $row1->SRCETYPE)
                        ->setCellValue('E'.$no1, "'".$row1->FISCYR)
                        ->setCellValue('F'.$no1, "'".$row1->FSCSPERD)
                        ->setCellValue('G'.$no1, $row1->JRNLDESC)
                        ->setCellValue('H'.$no1, $row1->DATEENTRY)
                        ;
            $sheet->getStyle('A'.$no1.':H'.$no1)->applyFromArray(
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

            $sheet->setCellValueExplicit('A'.$no1, $row1->BATCHID, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B'.$no1, $row1->BTCHENTRY, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E'.$no1, $row1->FISCYR, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F'.$no1, $row1->FSCSPERD, PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getStyle('A'.$no1.':B'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $sheet->getStyle('E'.$no1.':F'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $no1++;
        }

        foreach(range('A','H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Journal_Headers', $objPHPExcel->getActiveSheet(), 'A1:H1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Journal_Headers');
        
    }elseif ($Arr_sheet[$s] == "Journal_Details"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATCHNBR')
                    ->setCellValue('B1', 'JOURNALID')
                    ->setCellValue('C1', 'TRANSNBR')
                    ->setCellValue('D1', 'ACCTID')
                    ->setCellValue('E1', 'TRANSAMT')
                    ->setCellValue('F1', 'SCURNDEC')
                    ->setCellValue('G1', 'SCURNAMT')
                    ->setCellValue('H1', 'HCURNCODE')
                    ->setCellValue('I1', 'RATETYPE')
                    ->setCellValue('J1', 'SCURNCODE')
                    ->setCellValue('K1', 'RATEDATE')
                    ->setCellValue('L1', 'CONVRATE')
                    ->setCellValue('M1', 'TRANSDESC')
                    ->setCellValue('N1', 'TRANSREF')
                    ->setCellValue('O1', 'TRANSDATE')
                    ->setCellValue('P1', 'SRCELDGR')
                    ->setCellValue('Q1', 'SRCETYPE')
                    ->setCellValue('R1', 'COMMENT')
                    ;
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:R1')->applyFromArray(
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
        while ($row2 = sqlsrv_fetch_object($data2)){
            $objPHPExcel->setActiveSheetIndex($s)
            ->setCellValue('A'.$no2, $row2->BATCHNBR)
            ->setCellValue('B'.$no2, $row2->JOURNALID)
            ->setCellValue('C'.$no2, $row2->TRANSNBR)
            ->setCellValue('D'.$no2, "'".$row2->ACCTID)
            ->setCellValue('E'.$no2, $row2->TRANSAMT)
            ->setCellValue('F'.$no2, $row2->SCURNDEC)
            ->setCellValue('G'.$no2, $row2->SCURNAMT)
            ->setCellValue('H'.$no2, $row2->HCURNCODE)
            ->setCellValue('I'.$no2, $row2->RATETYPE)
            ->setCellValue('J'.$no2, $row2->SCURNCODE)
            ->setCellValue('K'.$no2, $row2->RATEDATE)
            ->setCellValue('L'.$no2, $row2->CONVRATE)
            ->setCellValue('M'.$no2, $row2->TRANSDESC)
            ->setCellValue('N'.$no2, $row2->TRANSREF)
            ->setCellValue('O'.$no2, $row2->TRANSDATE)
            ->setCellValue('P'.$no2, $row2->SRCELDGR)
            ->setCellValue('Q'.$no2, $row2->SRCETYPE)
            ->setCellValue('R'.$no2, $row2->COMMENT)
            ;
            
            $sheet->getStyle('A'.$no2.':R'.$no2)->applyFromArray(
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

            // menghilangkan single quotes 
            $sheet->setCellValueExplicit('D'.$no2, $row2->ACCTID, PHPExcel_Cell_DataType::TYPE_STRING);
            // merubah format cell menjadi text
            $sheet->getStyle('D'.$no2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $no2++;
        }

        foreach(range('A','R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Journal_Details', $objPHPExcel->getActiveSheet(), 'A1:R1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Journal_Details');
    }elseif ($Arr_sheet[$s] == "Journal_Detail_Optional_Fields"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATCHNBR')
                    ->setCellValue('B1', 'JOURNALID')
                    ->setCellValue('C1', 'TRANSNBR')
                    ->setCellValue('D1', 'OPTFIELD')
                    ->setCellValue('E1', 'VALUE')
                    ->setCellValue('F1', 'TYPE')
                    ->setCellValue('G1', 'LENGTH')
                    ->setCellValue('H1', 'DECIMALS')
                    ->setCellValue('I1', 'ALLOWNULL')
                    ->setCellValue('J1', 'VALIDATE')
                    ->setCellValue('K1', 'SWSET')
                    ->setCellValue('L1', 'VALINDEX')
                    ->setCellValue('M1', 'VALIFTEXT')
                    ->setCellValue('N1', 'VALIFMONEY')
                    ->setCellValue('O1', 'VALIFNUM')
                    ->setCellValue('P1', 'VALIFLONG')
                    ->setCellValue('Q1', 'VALIFBOOL')
                    ->setCellValue('R1', 'VALIFDATE')
                    ->setCellValue('S1', 'VALIFTIME')
                    ->setCellValue('T1', 'FDESC')
                    ->setCellValue('U1', 'VDESC')
                    ;
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A1:U1')->applyFromArray(
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

        // $no3 = 2;
        // while ($row3 = sqlsrv_fetch_object($data3)){
        //     $objPHPExcel->setActiveSheetIndex($s)
        //     ->setCellValue('A1', $row3->BATCHNBR)
        //     ->setCellValue('B1', $row3->JOURNALID)
        //     ->setCellValue('C1', $row3->TRANSNBR)
        //     ->setCellValue('D1', $row3->OPTFIELD)
        //     ->setCellValue('E1', $row3->VALUE)
        //     ->setCellValue('F1', $row3->TYPE)
        //     ->setCellValue('G1', $row3->LENGTH)
        //     ->setCellValue('H1', $row3->DECIMALS)
        //     ->setCellValue('I1', $row3->ALLOWNULL)
        //     ->setCellValue('J1', $row3->VALIDATE)
        //     ->setCellValue('K1', $row3->SWSET)
        //     ->setCellValue('L1', $row3->VALINDEX)
        //     ->setCellValue('M1', $row3->VALIFTEXT)
        //     ->setCellValue('N1', $row3->VALIFMONEY)
        //     ->setCellValue('O1', $row3->VALIFNUM)
        //     ->setCellValue('P1', $row3->VALIFLONG)
        //     ->setCellValue('Q1', $row3->VALIFBOOL)
        //     ->setCellValue('R1', $row3->VALIFDATE)
        //     ->setCellValue('S1', $row3->VALIFTIME)
        //     ->setCellValue('T1', $row3->FDESC)
        //     ->setCellValue('U1', $row3->VDESC)
        //     ;
        //     $sheet->getStyle('A'.$no3.':G'.$no3)->applyFromArray(
        //         array(
        //             'fill' => array(
        //                 'type' => PHPExcel_Style_Fill::FILL_SOLID,
        //                 'color' => array('rgb' => 'E2EFDA')
        //             ),
        //             'borders' => array(
        //                 'allborders' => array(
        //                     'style' => PHPExcel_Style_Border::BORDER_THIN
        //                 )
        //             )
        //         )
        //     );
        //     $no3++;
        // }
        foreach(range('A','U') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objPHPExcel->addNamedRange(
            new PHPExcel_NamedRange('Journal_Detail_Optional_Fields', $objPHPExcel->getActiveSheet(), 'A1:U1')
        );

        $objPHPExcel->getActiveSheet()->setTitle('Journal_Detail_Optional_Fields');
    }

    $s++;
}

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="COST-OF-SALES-'.$period.'.xlsx"');
$objWriter->save('php://output');
?>
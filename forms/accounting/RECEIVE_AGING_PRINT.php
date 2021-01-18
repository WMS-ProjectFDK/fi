<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

// http://fdki:8088/wms/forms/accounting/RECEIVE_AGING_PRINT.php?jns_report=MATERIAL

$jns_report = isset($_REQUEST['jns_report']) ? strval($_REQUEST['jns_report']) : '';

if ($jns_report == 'MATERIAL'){
    $sql_h = "select a.ITEM_NO, i.item, i.DESCRIPTION, i.STANDARD_PRICE, I.STOCK_SUBJECT_CODE, ss.STOCK_SUBJECT, a.last_inventory as THIS_INVENTORY
        from WHINVENTORY a
        INNER JOIN ITEM i ON a.ITEM_NO = i.ITEM_NO
        INNER JOIN STOCK_SUBJECT ss on i.STOCK_SUBJECT_CODE = ss.STOCK_SUBJECT_CODE
        where a.last_inventory <> 0 and i.STOCK_SUBJECT_CODE not in (3,5,7)
        order by i.STOCK_SUBJECT_CODE, a.ITEM_NO"; 
}elseif($jns_report == 'SPAREPARTS'){
    $sql_h = "select a.ITEM_NO, i.DESCRIPTION AS ITEM, i.DESCRIPTION_ORG AS DESCRIPTION, 0 AS STANDARD_PRICE, i.REPORTGROUP_CODE AS STOCK_SUBJECT_CODE, 
        ss.REPORTGROUP_NAME AS STOCK_SUBJECT, a.last_inventory as THIS_INVENTORY
        from SP_WHINVENTORY a
        INNER JOIN SP_ITEM i ON a.ITEM_NO = i.ITEM_NO
        INNER JOIN SP_REPORTGROUP ss on i.REPORTGROUP_CODE = ss.REPORTGROUP_CODE
        where a.last_inventory <> 0
        order by i.REPORTGROUP_CODE, a.ITEM_NO"; 
}
$result = sqlsrv_query($connect, strtoupper($sql_h));

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

// --------------------------------------------------------------------
$no = 1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'RECEIVE AGING '.$jns_report);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$no.':F'.$no);

// --------------------------------------------------------------------
$no++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$no.':F'.$no);

// --------------------------------------------------------------------
$no++;
$no++;
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'NO')
            ->setCellValue('B'.$no, 'ITEM NO')
            ->setCellValue('C'.$no, 'DESCRIPTION')
            ->setCellValue('D'.$no, 'STANDARD PRICE')
            ->setCellValue('E'.$no, 'STOCK SUBJECT')
            ->setCellValue('F'.$no, 'THIS INV.');

cellColor('A'.$no.':F'.$no, 'A5A5A5');

$sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'A5A5A5')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$noUrut = 1;    
$no++;
$itm = '';

while ($data_h=sqlsrv_fetch_object($result)){
    if ($no == 2){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, $noUrut)
                    ->setCellValue('B'.$no, $data_h->ITEM_NO)
                    ->setCellValue('C'.$no, $data_h->ITEM.' - '.$data_h->DESCRIPTION)
                    ->setCellValue('D'.$no, $data_h->STANDARD_PRICE)
                    ->setCellValue('E'.$no, $data_h->STOCK_SUBJECT)
                    ->setCellValue('F'.$no, $data_h->THIS_INVENTORY);
        $sheet = $objPHPExcel->getActiveSheet();
        cellColor('A'.$no.':F'.$no, 'A5A5A5');
        $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'A5A5A5')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => true,
                    'size'  => 12
                )
            )
        );
        $no++;    
    }else{
        if ($itm != $data_h->ITEM_NO){
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$no, 'NO')
                        ->setCellValue('B'.$no, 'ITEM NO')
                        ->setCellValue('C'.$no, 'DESCRIPTION')
                        ->setCellValue('D'.$no, 'STANDARD PRICE')
                        ->setCellValue('E'.$no, 'STOCK SUBJECT')
                        ->setCellValue('F'.$no, 'THIS INV.');

            cellColor('A'.$no.':F'.$no, 'A5A5A5');

            $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'A5A5A5')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'size'  => 12
                    )
                )
            );
            $no++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$no, $noUrut)
                        ->setCellValue('B'.$no, $data_h->ITEM_NO)
                        ->setCellValue('C'.$no, $data_h->ITEM.' - '.$data_h->DESCRIPTION)
                        ->setCellValue('D'.$no, $data_h->STANDARD_PRICE)
                        ->setCellValue('E'.$no, $data_h->STOCK_SUBJECT)
                        ->setCellValue('F'.$no, $data_h->THIS_INVENTORY);
            $sheet = $objPHPExcel->getActiveSheet();
            cellColor('A'.$no.':F'.$no, 'A5A5A5');
            $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'A5A5A5')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'size'  => 12
                    )
                )
            );
            $no++;
        }
    }
    
    // ======================================= DETAILS =======================================
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$no, 'NO')
                ->setCellValue('C'.$no, 'RECEIVE NO')
                ->setCellValue('D'.$no, 'RECEIVE DATE')
                ->setCellValue('E'.$no, 'QTY')
                ->setCellValue('F'.$no, 'AGING');

    cellColor('B'.$no.':F'.$no, 'D2D2D2');
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('B'.$no.':F'.$no)->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'D2D2D2')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => true,
                'size'  => 12
            )
        )
    );
    $no++;

    if ($jns_report == 'MATERIAL'){
        $sql_d = "select b.gr_no, cast(b.GR_DATE AS VARCHAR(10)) AS GR_DATE, a.QTY, CAST(DATEDIFF(day,b.gr_date, getdate()) as varchar)+' DAY' as aging
            from GR_DETAILS a
            inner join GR_HEADER b on a.gr_no = b.GR_NO
            where item_no=$data_h->ITEM_NO
            order by b.GR_DATE desc";
    }else if ($jns_report == 'SPAREPARTS'){
        $sql_d = "select b.gr_no, cast(b.GR_DATE AS VARCHAR(11)) AS GR_DATE, a.QTY, CAST(DATEDIFF(day,b.gr_date, getdate()) as varchar)+' DAY' as aging
            from SP_GR_DETAILS a
            inner join SP_GR_HEADER b on a.gr_no = b.GR_NO
            where item_no='".$data_h->ITEM_NO."'
            order by b.GR_DATE desc";
    }
    $detail = sqlsrv_query($connect, strtoupper($sql_d));

    $nourut_dtl = 1;
    $jum_tot = $data_h->THIS_INVENTORY;
    $jum_d = 0;
    
    while ($data_d = sqlsrv_fetch_object($detail)){
        if ($jum_d <= $jum_tot) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B'.$no, $nourut_dtl)
                        ->setCellValue('C'.$no, $data_d->GR_NO)
                        ->setCellValue('D'.$no, $data_d->GR_DATE)
                        ->setCellValue('E'.$no, $data_d->QTY)
                        ->setCellValue('F'.$no, $data_d->AGING);
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->getStyle('B'.$no.':F'.$no)->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $no++;
        }

        $jum_d = $jum_d + $data_d->QTY;
        $nourut_dtl++;
    }

    $itm = $data_h->ITEM_NO;
    $noUrut++;

    foreach(range('A','F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
}   

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');
// It will be called file.xls
header('Content-Disposition: attachment; filename="RECEIVE_AGING_'.$jns_report.'.xlsx"');
// Write file to the browser
$objWriter->save('php://output');
?>
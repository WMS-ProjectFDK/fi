<?php
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(-1);
require_once '../../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

include("../../../connect/conn.php");
$period = isset($_REQUEST['period']) ? strval($_REQUEST['period']) : '';
$jns_report = isset($_REQUEST['jns_report']) ? strval($_REQUEST['jns_report']) : '';

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

    $sql = "select a.item_no, replace(replace(b.DESCRIPTION_ORG,'&nbsp;',''),'%[^0-9a-zA-Z]%','-') as description, isnull(a.qty,0) as qty, a.amount
        from rpt_inventory_Stock a
        left outer join sp_item b on a.item_no = b.ITEM_NO
        order by a.item_no asc";
    
    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A1', $jns_report);
    cellColor('A1:D1', 'A5A5A5');
    $objPHPExcel->setActiveSheetIndex()->mergeCells('A1:D1');

    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A2', $period);
    cellColor('A2:D2', 'A5A5A5');
    $objPHPExcel->setActiveSheetIndex()->mergeCells('A2:D2');

    $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A3', 'ITEM NO')
            ->setCellValue('B3', 'ITEM NAME')
            ->setCellValue('C3', 'QTY')
            ->setCellValue('D3', 'VALUE');
    cellColor('A3:D3', 'A5A5A5');
    $objPHPExcel->setActiveSheetIndex()->mergeCells('A3:D3');

    $sheet->getStyle('A3:D3')->applyFromArray(
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

function clean($string) {
    $res = str_ireplace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $string);
    return $res;
}

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

$no=4;
$results = sqlsrv_query($connect, strtoupper($sql));

while ($l = sqlsrv_fetch_object($results){
        $objPHPExcel->setActiveSheetIndex()
                    ->setCellValue('A'.$no, $l->ITEM_NO)
                    ->setCellValue('B'.$no, clean($l->DESCRIPTION))
                    ->setCellValue('C'.$no, $l->QTY)
                    ->setCellValue('D'.$no, 'AMOUNT');

        foreach(range('A','D') as $columnID) {
	        $sheet->getColumnDimension($columnID)->setAutoSize(true);
	    }
        $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
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
        
    	// $sheet->getStyle('A'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        // $sheet->getStyle('B'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        // $sheet->getStyle('C'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        // $sheet->getStyle('D'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    $no++;
}

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

header("Content-Disposition: attachment; filename=AK_SALDO_STK.xlsx");
// Write file to the browser
$objWriter->save('php://output');
?>
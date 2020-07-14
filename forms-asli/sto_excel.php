<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';

$qry = "select distinct a.item_no, b.description, sum (a.qty-a.qty_out) as total, c.unit from ztb_wh_in_det a 
    inner join item b on a.item_no=b.item_no inner join unit c on b.uom_q=c.unit_code
    where a.rack is not null and a.qty-a.qty_out > 0 and to_date(substr(a.tanggal, 0, 8),'YYYY=MM-DD') <= to_date('$tanggal','YYYY-MM-DD')
    group by a.item_no, b.description, c.unit
    order by b.description asc";
$result = oci_parse($connect, $qry);
oci_execute($result);

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
            ->setCellValue('B1', 'ITEM NO.')
            ->setCellValue('C1', 'ITEM NAME')
            ->setCellValue('D1', 'UoM')
            ->setCellValue('E1', 'STOCK RACK')
            ->setCellValue('F1', 'STOCK NON-RACK');

cellColor('A1:F1', 'D2D2D2');
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=2;

while ($data=oci_fetch_array($result)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data[0])
                ->setCellValue('C'.$no, $data[1])
                ->setCellValue('D'.$no, $data[3])
                ->setCellValue('E'.$no, number_format($data[2]))
                ->setCellValue('F'.$no, ' - ');

    $sheet = $objPHPExcel->getActiveSheet();
    $no++;
    $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('STO - '.$tanggal);
 
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
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="sto_'.$tanggal.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

$sql = "select * from zvw_fg_inventory";

$result = oci_parse($connect, $sql);
oci_execute($result);

$bln = array("", 
           "JAN", 
           "FEB",
           "MAR",
           "APR",
           "MAY",
           "JUN",
           "JUL",
           "AUG",
           "SEP",
           "OKT",
           "NOV",
           "DES");

$tahun = substr($cmbBln,0,4);
$month = substr($cmbBln,4,2);

$bln_lalu = $bln[intval($month)-1].' '.$tahun;
$bln_ini = $bln[intval($month)].' '.$tahun;

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
            ->setCellValue('B1', 'CUSTOMER')
            ->setCellValue('C1', 'INVENTORY '.$bln_lalu)
            ->setCellValue('D1', 'AMOUNT '.$bln_lalu)
            ->setCellValue('E1', 'INVENTORY '.$bln_ini)
            ->setCellValue('F1', 'AMOUNT '.$bln_ini);

foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'B4B4B4')
        ),
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

cellColor('A1:F1', 'D2D2D2');
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=2;

while ($data=oci_fetch_object($result)){

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->CUSTOMER)
                ->setCellValue('C'.$no, $data->INVENTORYBULANLALU)
                ->setCellValue('D'.$no, $data->AMOUNTINVENTORYBULANLALU)
                ->setCellValue('E'.$no, $data->INVENTORYBULANINI)
                ->setCellValue('F'.$no, $data->AMOUNTINVENTORYBULANINI);
    $sheet = $objPHPExcel->getActiveSheet();

    $no++;
    $noUrut++;
}

$no -= 1;

$sheet->getStyle('A1:F'.$no)->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);   

$objPHPExcel->getDefaultStyle()
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('FG_INVENTORY - '.$cmbBln_txt);
 
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
header('Content-Disposition: attachment; filename="FG-INV_'.$cmbBln_txt.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
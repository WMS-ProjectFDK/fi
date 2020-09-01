<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

include("../connect/conn2.php");

/*QUERY*/
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$req_no = isset($_REQUEST['reqno']) ? strval($_REQUEST['reqno']) : '';
$req_date = isset($_REQUEST['reqdate']) ? strval($_REQUEST['reqdate']) : '';

$sql = "select a.item_no, b.description, a.qty, c.unit, a.price, d.req_date, d.remarks from ztb_prf_req_details a left join item b on a.item_no=b.item_no 
        left join unit c on a.unit_code = c.unit_code left join ztb_prf_req_header d on a.req_no=d.req_no where a.req_no='$req_no' order by a.id asc";
$result = oci_parse($connect, $sql);
oci_execute($result);

$date=date("d M y / H:i:s",time());

$nourut = 1;    
$no=6;

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
            ->mergeCells('A6:A7')->setCellValue('A6', 'NO')
            ->mergeCells('B6:B7')->setCellValue('B6', 'ITEM NO')
            ->mergeCells('C6:C7')->setCellValue('C6', 'MATERIAL NAME')
            ->mergeCells('D6:D7')->setCellValue('D6', 'QTY')
            ->mergeCells('E6:E7')->setCellValue('E6', 'UoM')
            ->mergeCells('F6:F7')->setCellValue('F6', 'Estimation Price US$')
            ->mergeCells('G6:G7')->setCellValue('G6', 'Required Incoming Date')
            ->mergeCells('H6:H7')->setCellValue('H6', 'EstimatedIncoming Date')
            ->mergeCells('I6:I7')->setCellValue('I6', 'OHSAS(K3) Elements');

cellColor('A6:I6', 'D2D2D2');
$sheet->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$sheet->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_TOP);
$sheet->getStyle('A6:I6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle('A6:I6')->getFont()->setBold(true)->setSize(11);
$sheet->getStyle('A7:I7')->getFont()->setBold(true)->setSize(11);
$no += 2;

while($data=oci_fetch_array($result)){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$no, $nourut)
        ->setCellValue('B'.$no, $data[0])
        ->setCellValue('C'.$no, $data[1])
        ->setCellValue('D'.$no, number_format($data[2],2))
        ->setCellValue('E'.$no, $data[3])
        ->setCellValue('F'.$no, number_format($data[4],1))
        ->setCellValue('G'.$no, ' - ')
        ->setCellValue('H'.$no, ' - ')
        ->setCellValue('I'.$no, ' - ');
        $no++;  $nourut++;
}
// Rename worksheetz
$sheet->setTitle('PRF-REQUISITION');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 // Menambahkan file gambar pada document excel pada kolom B2
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('WMS');
$objDrawing->setDescription('WMS');
$objDrawing->setPath('../images/logo-print4.png');
$objDrawing->setWidth('500px');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($sheet);
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="prf_req-'.$req_no.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
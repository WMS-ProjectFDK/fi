<?php
ini_set('memory_limit', '-1');
require_once '../../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../../connect/conn.php");

// http://localhost:8088/fi/forms/ppic/mrp-rm/assy_plan_xls.php?pl_bulan=7&pl_tahun=2020&pl_cdate=false&pl_aline=1&pl_cline=true&pl_cltyp=1&pl_cktyp=true&pl_revis=&pl_crev=true&pl_day=1&pl_cday=true&pl_cuse=true

$pl_bulan = isset($_REQUEST['pl_bulan']) ? strval($_REQUEST['pl_bulan']) : '';
$pl_tahun = isset($_REQUEST['pl_tahun']) ? strval($_REQUEST['pl_tahun']) : '';
$pl_cdate = isset($_REQUEST['pl_cdate']) ? strval($_REQUEST['pl_cdate']) : '';
$pl_aline = isset($_REQUEST['pl_aline']) ? strval($_REQUEST['pl_aline']) : '';
$pl_cline = isset($_REQUEST['pl_cline']) ? strval($_REQUEST['pl_cline']) : '';
$pl_cltyp = isset($_REQUEST['pl_cltyp']) ? strval($_REQUEST['pl_cltyp']) : '';
$pl_cktyp = isset($_REQUEST['pl_cktyp']) ? strval($_REQUEST['pl_cktyp']) : '';
$pl_revis = isset($_REQUEST['pl_revis']) ? strval($_REQUEST['pl_revis']) : '';
$pl_crev = isset($_REQUEST['pl_crev']) ? strval($_REQUEST['pl_crev']) : '';
$pl_day = isset($_REQUEST['pl_day']) ? strval($_REQUEST['pl_day']) : '';
$pl_cday = isset($_REQUEST['pl_cday']) ? strval($_REQUEST['pl_cday']) : '';
$pl_cuse = isset($_REQUEST['pl_cuse']) ? strval($_REQUEST['pl_cuse']) : '';

if($pl_cdate != "true"){
    $date = "bulan = '$pl_bulan' and tahun = '$pl_tahun' and ";
}else{
    $date = "";
}

if($pl_cline != "true"){
    $line = "assy_line = '$pl_aline' and ";
}else{
    $line = "";
}

if($pl_cktyp != "true"){
    $type = "cell_type = '$pl_cltyp' and ";
}else{
    $type = "";
}

if($pl_crev != "true"){
    if($pl_revis == "USED"){
        $rev = "used = 1 and ";
    }else{
        $rev = "revisi = $pl_revis and ";
    }
}else{
    $rev = "";
}

if($pl_cday != "true"){
    $days = "tanggal = '$pl_day' and ";
}else{
    $days = "";
}

if($pl_cuse == "true"){
    $use = "used = 1 and ";
}else{
    $use = " ";
}

$where = " where $date $line $type $rev $days $use qty!= 0 ";

$qry = "select * from ztb_assy_plan $where
    order by tanggal, bulan, tahun, assy_line";
$result = sqlsrv_query($connect, strtoupper($qry));

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
            ->setCellValue('B1', 'CELL TYPE')
            ->setCellValue('C1', 'ASSY LINE')
            ->setCellValue('D1', 'TANGGAL')
            ->setCellValue('E1', 'QTY')
            ->setCellValue('F1', 'REVISI');
            
foreach(range('A','F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:F1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

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

$noUrut = 1;    
$no=2;
$p = '';
$rev = 0;
while ($data=sqlsrv_fetch_object($result)) {
    $tgl = $data->TAHUN.'-'.$data->BULAN.'-'.$data->TANGGAL;
    $rev = $data->REVISI;
    $bln = $data->BULAN;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $noUrut)
                ->setCellValue('B'.$no, $data->CELL_TYPE)
                ->setCellValue('C'.$no, $data->ASSY_LINE)
                ->setCellValue('D'.$no, $tgl)
                ->setCellValue('E'.$no, $data->QTY)
                ->setCellValue('F'.$no, $data->REVISI);
    
    if($no % 2 == 0){
        $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
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
        $sheet->getStyle('A'.$no.':F'.$no)->applyFromArray(
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

    $objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0');

    $no++;      $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('ASSY_PLAN');
 
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
header('Content-Disposition: attachment; filename="ASSY_PLAN_Bulan_'.$pl_bulan.'_Revisi_'.$rev.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';

if($item=="ALL"){
    $where = "where a.qty - a.qty_out > 0 and rack is not null";
}elseif($item=="ALL_WHCC"){
    $wh = "WH-CATHODE CAN";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}elseif($item=="ALL_WHRM"){
    $wh = "WH-RAW MATERIAL";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}elseif($item=="ALL_WHSP"){
    $wh = "WH-SEPARATOR";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}elseif($item=="ALL_WHFM"){
    $wh = "WH-FLAMMABLE";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}elseif($item=="ALL_WHNP"){
    $wh = "WH-NPS";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}elseif($item=="ALL_WHCR"){
    $wh = "WH-AREA CORIDOR";
    $where = "where a.qty - a.qty_out > 0 and rack is not null and c.warehouse='$wh'";
}else{
    $wh=$item;
    $where = "where a.qty - a.qty_out > 0 and a.item_no = '$item' and rack is not null";
}

$qry = "select a.gr_no, a.line_no,coalesce(a.rack,'-') as rack, a.pallet, a.qty-a.qty_out as qty, a.id,b.description, coalesce(c.warehouse,'-') as warehouse, d.gr_date, a.id, a.item_no 
        from ztb_wh_in_det a 
        left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack left join gr_header d on a.gr_no = d.gr_no 
        $where order by d.gr_date asc, a.pallet asc, rack asc";
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
            ->setCellValue('B1', 'ID INCOMING')
            ->setCellValue('C1', 'GOODS RECEIVE NO')
            ->setCellValue('D1', 'GOODS RECEIVE DATE')
            ->setCellValue('E1', 'ITEM NO.')
            ->setCellValue('F1', 'DESCRIPTION')
            ->setCellValue('G1', 'LINE')
            ->setCellValue('H1', 'WH')
            ->setCellValue('I1', 'RACK')
            ->setCellValue('J1', 'PALLET')
            ->setCellValue('K1', 'QTY')
            ;

cellColor('A1:K1', 'D2D2D2');
$sheet->getStyle('A1:K1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=2;

while ($data=oci_fetch_object($result)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->ID)
                ->setCellValue('C'.$no, $data->GR_NO)
                ->setCellValue('D'.$no, $data->GR_DATE)
                ->setCellValue('E'.$no, $data->ITEM_NO)
                ->setCellValue('F'.$no, $data->DESCRIPTION)
                ->setCellValue('G'.$no, $data->LINE_NO)
                ->setCellValue('H'.$no, $data->WAREHOUSE)
                ->setCellValue('I'.$no, $data->RACK)
                ->setCellValue('J'.$no, $data->PALLET)
                ->setCellValue('K'.$no, number_format($data->QTY));
    $sheet = $objPHPExcel->getActiveSheet();
    $no++;
    $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('ITEM - '.$wh);
 
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
header('Content-Disposition: attachment; filename="item_'.$wh.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
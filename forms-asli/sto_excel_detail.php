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
            ->setCellValue('E1', 'UoM')
            ->setCellValue('F1', 'STOCK RACK')
            ->setCellValue('G1', 'STOCK NON-RACK');

$sheet->mergeCells('C1:D1');
$sheet->getStyle('A1:G1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=2;

while ($data=oci_fetch_array($result)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data[0])
                ->setCellValue('C'.$no, $data[1])
                ->setCellValue('E'.$no, $data[3])
                ->setCellValue('F'.$no, number_format($data[2]))
                ->setCellValue('G'.$no, ' - ');

    cellColor('A'.$no.':G'.$no, 'D2D2D2');
    $sheet->mergeCells('C'.$no.':D'.$no);
    $sheet = $objPHPExcel->getActiveSheet();
    $no++;
    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, 'INCOMING NO.')
                ->setCellValue('B'.$no, 'GOOD RECEIVE NO.')
                ->setCellValue('C'.$no, 'GOOD RECEIVE DATE')
                ->setCellValue('D'.$no, 'PALLET')
                ->setCellValue('E'.$no, 'QTY')
                ->setCellValue('F'.$no, 'RACK')
                ->setCellValue('G'.$no, 'WAREHOUSE');

    cellColor('A'.$no.':G'.$no, 'E3E3E3');
    $sheet = $objPHPExcel->getActiveSheet();
    $no++;

    $item = trim($data[0]);
    $nourut_dtl = 1;
    
    $sql = "select a.id, a.gr_no, a.line_no,coalesce(a.rack,'-') as rack, a.pallet, a.qty-a.qty_out as qty, a.id,b.description, 
        coalesce(c.warehouse,'-') as warehouse, d.gr_date, a.item_no from ztb_wh_in_det a 
        left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack 
        left join gr_header d on a.gr_no=d.gr_no
        where a.qty - a.qty_out > 0 and rack is not null and a.item_no='$item' and to_date(substr(a.tanggal, 0, 8),'YYYY=MM-DD') <= to_date('$tanggal','YYYY-MM-DD')
        order by d.gr_date asc, a.pallet asc";
    $detail = oci_parse($connect, $sql);
    oci_execute($detail);
    while ($dta = oci_fetch_object($detail) ){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, $dta->ID)
                    ->setCellValue('B'.$no, $dta->GR_NO)
                    ->setCellValue('C'.$no, $dta->GR_DATE)
                    ->setCellValue('D'.$no, $dta->PALLET)
                    ->setCellValue('E'.$no, number_format($dta->QTY))
                    ->setCellValue('F'.$no, $dta->RACK)
                    ->setCellValue('G'.$no, $dta->WAREHOUSE);

        $sheet = $objPHPExcel->getActiveSheet();
        $no++;
        $nourut_dtl++;
    }
    $noUrut++;
}

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('STO-DTL_'.$tanggal);
 
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
header('Content-Disposition: attachment; filename="sto_details_'.$tanggal.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
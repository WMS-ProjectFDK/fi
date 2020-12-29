<?php
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

$cek = "select distinct(this_month) as month from whinventory";
$data_cek = oci_parse($connect, $cek);
oci_execute($data_cek);
$dt_cek = oci_fetch_object($data_cek);

if($dt_cek->MONTH == $cmbBln){
    $sql = "select * from zvw_material_item_view_this";
}else{
    $sql = "select * from zvw_material_item_view_last";
}

$result = oci_parse($connect, $sql);
oci_execute($result);

$target_qty = $arrayName = array("ANODE DISK" => "9200000",
                                 "CATHODE CAN" => "18000000",
                                 "CC ROD" => "23000000",
                                 "CHEMICAL - OTHERS" => "0",
                                 "EMD" => "200000",
                                 "GASKET" => "23000000",
                                 "GRAPHITE" => "22000",
                                 "MAGIC INK" => "0",
                                 "MAGIC PEN" => "0",
                                 "METAL LABEL" => "0",
                                 "NPS" => "9200",
                                 "SEPARATOR" => "3800000",
                                 "WASHER" => "9200000",
                                 "ZINC POWDER" => "80000",
                                 "SCRAP GEL LR" => "0",
                                 "CCR AFTER PLATING" => "0",
                                 "BATTERY AFTER WEIGHT CHECKER" => "0",
                                 "SCRAP BLACK MIX LR" => "0",
                                 "SCRAP NPS" => "0",
                                 "A. DISK AFT DEGREASE" => "0"
                            );

$target_amt = $arrayName = array("ANODE DISK" => "28566",
                                 "CATHODE CAN" => "264825",
                                 "CC ROD" => "87400",
                                 "CHEMICAL - OTHERS" => "0",
                                 "EMD" => "380540",
                                 "GASKET" => "115742",
                                 "GRAPHITE" => "137206",
                                 "MAGIC INK" => "0",
                                 "MAGIC PEN" => "0",
                                 "METAL LABEL" => "0",
                                 "NPS" => "20286",
                                 "SEPARATOR" => "112465",
                                 "WASHER" => "7575",
                                 "ZINC POWDER" => "326969",
                                 "SCRAP GEL LR" => "0",
                                 "CCR AFTER PLATING" => "0",
                                 "BATTERY AFTER WEIGHT CHECKER" => "0",
                                 "SCRAP BLACK MIX LR" => "0",
                                 "SCRAP NPS" => "0",
                                 "A. DISK AFT DEGREASE" => "0"
                            );
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
            ->setCellValue('B1', 'PERIOD')
            ->setCellValue('C1', 'ITEM')
            ->setCellValue('D1', 'LAST MONTH')
            ->setCellValue('E1', 'LAST MONTH AMOUNT')
            ->setCellValue('F1', 'DATE (QTY)')
            ->setCellValue('F2', '1')
            ->setCellValue('G2', '2')
            ->setCellValue('H2', '3')
            ->setCellValue('I2', '4')
            ->setCellValue('J2', '5')
            ->setCellValue('K2', '6')
            ->setCellValue('L2', '7')
            ->setCellValue('M2', '8')
            ->setCellValue('N2', '9')
            ->setCellValue('O2', '10')
            ->setCellValue('P2', '11')
            ->setCellValue('Q2', '12')
            ->setCellValue('R2', '13')
            ->setCellValue('S2', '14')
            ->setCellValue('T2', '15')
            ->setCellValue('U2', '16')
            ->setCellValue('V2', '17')
            ->setCellValue('W2', '18')
            ->setCellValue('X2', '19')
            ->setCellValue('Y2',  '20')
            ->setCellValue('Z2',  '21')
            ->setCellValue('AA2', '22')
            ->setCellValue('AB2', '23')
            ->setCellValue('AC2', '24')
            ->setCellValue('AD2', '25')
            ->setCellValue('AE2', '26')
            ->setCellValue('AF2', '27')
            ->setCellValue('AG2', '28')
            ->setCellValue('AH2', '29')
            ->setCellValue('AI2', '30')
            ->setCellValue('AJ2', '31')
            ->setCellValue('AK1', 'THIS MONTH')
            ->setCellValue('AL1', 'THIS MONTH AMOUNT')
            ->setCellValue('AM1', 'ACTUAL AMOUNT')
            ->setCellValue('AN1', 'TARGET QTY')
            ->setCellValue('AO1', 'TARGET AMOUNT')
            ->setCellValue('AP1', 'RESULT');

foreach(range('A','AP') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
$objPHPExcel->getActiveSheet()->mergeCells('E1:E2');
$objPHPExcel->getActiveSheet()->mergeCells('F1:AJ1');
$objPHPExcel->getActiveSheet()->mergeCells('AK1:AK2');
$objPHPExcel->getActiveSheet()->mergeCells('AL1:AL2');
$objPHPExcel->getActiveSheet()->mergeCells('AM1:AM2');
$objPHPExcel->getActiveSheet()->mergeCells('AN1:AN2');
$objPHPExcel->getActiveSheet()->mergeCells('AO1:AO2');
$objPHPExcel->getActiveSheet()->mergeCells('AP1:AP2');

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1:AP2')->getAlignment()->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'B4B4B4')
        ),
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

cellColor('A1:AP2', 'D2D2D2');
$sheet->getStyle('A1:AP2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:AP2')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=3;

while ($data=oci_fetch_object($result)){
    $item = $data->ITEM1;
    $last_month = $data->LASTMONTH;
    $last_month_amt = $data->LASTMONTHAMOUNT;
    $this_month = $data->THISMONTH;
    $this_month_amt = $data->THISMONTHAMOUNT;
    $act_month_amt = $data->ACTUALAMOUNT;

    if($target_amt[$item] == 0){
        $sts = '0';
    }elseif (floatval($act_month_amt)>floatval($target_amt[$item])){
        $sts = 'BAD';
    }else{
        $sts = 'GOOD';
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $cmbBln_txt)
                ->setCellValue('C'.$no, $item)
                ->setCellValue('D'.$no, $last_month)
                ->setCellValue('E'.$no, $last_month_amt)
                ->setCellValue('F'.$no, $data->TANGGAL1)
                ->setCellValue('G'.$no, $data->TANGGAL2)
                ->setCellValue('H'.$no, $data->TANGGAL3)
                ->setCellValue('I'.$no, $data->TANGGAL4)
                ->setCellValue('J'.$no, $data->TANGGAL5)
                ->setCellValue('K'.$no, $data->TANGGAL6)
                ->setCellValue('L'.$no, $data->TANGGAL7)
                ->setCellValue('M'.$no, $data->TANGGAL8)
                ->setCellValue('N'.$no, $data->TANGGAL9)
                ->setCellValue('O'.$no, $data->TANGGALL0)
                ->setCellValue('P'.$no, $data->TANGGALL1)
                ->setCellValue('Q'.$no, $data->TANGGALL2)
                ->setCellValue('R'.$no, $data->TANGGALL3)
                ->setCellValue('S'.$no, $data->TANGGALL4)
                ->setCellValue('T'.$no, $data->TANGGALL5)
                ->setCellValue('U'.$no, $data->TANGGALL6)
                ->setCellValue('V'.$no, $data->TANGGALL7)
                ->setCellValue('W'.$no, $data->TANGGALL8)
                ->setCellValue('X'.$no, $data->TANGGALL9)
                ->setCellValue('Y'.$no, $data->TANGGAL20)
                ->setCellValue('Z'.$no, $data->TANGGAL21)
                ->setCellValue('AA'.$no, $data->TANGGAL22)
                ->setCellValue('AB'.$no, $data->TANGGAL23)
                ->setCellValue('AC'.$no, $data->TANGGAL24)
                ->setCellValue('AD'.$no, $data->TANGGAL25)
                ->setCellValue('AE'.$no, $data->TANGGAL26)
                ->setCellValue('AF'.$no, $data->TANGGAL27)
                ->setCellValue('AG'.$no, $data->TANGGAL28)
                ->setCellValue('AH'.$no, $data->TANGGAL29)
                ->setCellValue('AI'.$no, $data->TANGGAL30)
                ->setCellValue('AJ'.$no, $data->TANGGAL31)
                ->setCellValue('AK'.$no, $this_month)
                ->setCellValue('AL'.$no, $this_month_amt)
                ->setCellValue('AM'.$no, $act_month_amt)
                ->setCellValue('AN'.$no, $target_qty[$item])
                ->setCellValue('AO'.$no, $target_amt[$item])
                ->setCellValue('AP'.$no, $sts);
    $sheet = $objPHPExcel->getActiveSheet();

    if($target_amt[$item] == '0'){
        $sheet->getStyle("A".$no.":AP".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
    }elseif (floatval($act_month_amt)>floatval($target_amt[$item])){
        $sheet->getStyle("A".$no.":AP".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('FF0000');
    }else{
        $sheet->getStyle("A".$no.":AP".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('0000FF');
    }

    $no++;
    $noUrut++;
}

$no -= 1;

$sheet->getStyle('A1:AP'.$no)->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('material_inventory - '.$cmbBln_txt);
 
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
header('Content-Disposition: attachment; filename="MATERIAL-INVENTORY_'.$cmbBln_txt.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
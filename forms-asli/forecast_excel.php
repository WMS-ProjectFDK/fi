<?php
set_time_limit(0);
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '999MB');

PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");


$qry = "select * from zvw_forecast_01  ";
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
            ->setCellValue('A2', 'NO')
            ->setCellValue('B2', 'PACKAGING TYPE')
            // ->setCellValue('C2', 'WORK ORDER')
            ->setCellValue('C2', 'PO NO.')
            ->setCellValue('D2', 'ITEM NO.')
            ->setCellValue('E2', 'BRAND')
            ->setCellValue('F2', 'BATERY TYPE')
            ->setCellValue('G2', 'CELL GRADE')
            ->setCellValue('H2', 'ITEM REMARK 2')
            ->setCellValue('I2', 'REMARK')
            ->setCellValue('J2', 'DESTINATION')
            ->setCellValue('K2', 'ORDER QTY')
            ->setCellValue('L2', 'CR DATE')
            ->setCellValue('M2', 'CURRENCY')
            ->setCellValue('N2', 'PRICE')
            ->setCellValue('O2', 'SALES AMOUNT')
            ->setCellValue('P2', 'CR MONTH')
            ->setCellValue('Q2', 'SALES MONTH')
            ->setCellValue('R2', 'TERM')
            ->setCellValue('S2', 'STANDARD PRICE')
          
            
            
            ->setCellValue('T2', 'STOCK WAREHOUSE')
            ->setCellValue('U2', 'STOCK IN TRANSIT')
            
            ->setCellValue('V2', 'JAN QTY')
            ->setCellValue('W2', 'JAN AMT')
            ->setCellValue('X2', 'FEB QTY')
            ->setCellValue('Y2', 'FEB AMT')
            ->setCellValue('Z2', 'MAR QTY')
            ->setCellValue('AA2', 'MAR AMT')
            ->setCellValue('AB2', 'APR QTY')
            ->setCellValue('AC2', 'APR AMT')
            ->setCellValue('AD2', 'MAY QTY')
            ->setCellValue('AE2', 'MAY AMT')
            ->setCellValue('AF2', 'JUN QTY')
            ->setCellValue('AG2', 'JUN AMT')
            ->setCellValue('AH2', 'JUL QTY')
            ->setCellValue('AI2', 'JUL AMT')
            ->setCellValue('AJ2', 'AUG QTY')
            ->setCellValue('AK2', 'AUG AMT')
            ->setCellValue('AL2', 'SEP QTY')
            ->setCellValue('AM2', 'SEP AMT')
            ->setCellValue('AN2', 'OCT QTY')
            ->setCellValue('AO2', 'OCT AMT')
            ->setCellValue('AP2', 'NOV AMT')
            ->setCellValue('AQ2', 'NOV AMT')
            ->setCellValue('AR2', 'DES AMT')
            ->setCellValue('AS2', 'DES AMT');

$sheet->getStyle('A1:AR2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:AS2')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FFFF00')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

// cellColor('U2', 'FFFF00');      cellColor('AG2', 'FFFF00');
// cellColor('W2', 'FFFF00');      cellColor('AI2', 'FFFF00');
// cellColor('Y2', 'FFFF00');      cellColor('AK2', 'FFFF00');
// cellColor('AA2', 'FFFF00');     cellColor('AM2', 'FFFF00');
// cellColor('AC2', 'FFFF00');     cellColor('AO2', 'FFFF00');
// cellColor('AE2', 'FFFF00');     cellColor('AQ2', 'FFFF00');

$WHStock= 0; $TransAmount= 0;
$JanStock= 0; $JanAmount= 0;
$FebStock= 0; $FebAmount= 0;
$MarStock= 0; $MarAmount= 0;
$AprStock= 0; $AprAmount= 0;
$MayStock= 0; $MayAmount= 0;
$JunStock= 0; $JunAmount= 0;
$JulStock= 0; $JulAmount= 0;
$AugStock= 0; $AugAmount= 0;
$SepStock= 0; $SepAmount= 0;
$OctStock= 0; $OctAmount= 0;
$NovStock= 0; $NovAmount= 0;
$DecStock= 0; $DecAmount= 0;


foreach(range('A','AR') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$noUrut = 1;    
$no=3;

while ($data=oci_fetch_array($result)){
    $objPHPExcel->setActiveSheetIndex(0)
               
                ->setCellValue('A'.$no, $data[0])
                ->setCellValue('B'.$no, $data[1])
                ->setCellValue('C'.$no, "'".($data[2]))
                ->setCellValue('D'.$no, $data[3])
                ->setCellValue('E'.$no, $data[4])
                ->setCellValue('F'.$no, $data[5])
                ->setCellValue('G'.$no, $data[6])
                ->setCellValue('H'.$no, $data[7])
                ->setCellValue('I'.$no, $data[8])
                ->setCellValue('J'.$no, number_format($data[9]))
                ->setCellValue('K'.$no, $data[10])
                ->setCellValue('L'.$no, $data[11])
                ->setCellValue('M'.$no, $data[12])
                ->setCellValue('N'.$no, $data[13])
                ->setCellValue('O'.$no, $data[14])
                ->setCellValue('P'.$no, number_format($data[15]))
                ->setCellValue('Q'.$no, number_format($data[16]))
                ->setCellValue('R'.$no, $data[17])
                ->setCellValue('S'.$no, number_format($data[18]))
                ->setCellValue('T'.$no, number_format($data[19]))
                ->setCellValue('U'.$no, number_format($data[20]))
                ->setCellValue('V'.$no, number_format($data[21]))
                ->setCellValue('W'.$no, number_format($data[22]))
                ->setCellValue('X'.$no, number_format($data[23]))
                ->setCellValue('Y'.$no, number_format($data[24]))
                ->setCellValue('Z'.$no, number_format($data[25]))
                ->setCellValue('AA'.$no, number_format($data[26]))
                ->setCellValue('AB'.$no, number_format($data[27]))
                ->setCellValue('AC'.$no, number_format($data[28]))
                ->setCellValue('AD'.$no, number_format($data[29]))
                ->setCellValue('AE'.$no, number_format($data[30]))
                ->setCellValue('AF'.$no, number_format($data[31]))
                ->setCellValue('AG'.$no, number_format($data[32]))
                ->setCellValue('AH'.$no, number_format($data[33]))
                ->setCellValue('AI'.$no, number_format($data[34]))
                ->setCellValue('AJ'.$no, number_format($data[35]))
                ->setCellValue('AK'.$no, number_format($data[36]))
                ->setCellValue('AL'.$no, number_format($data[37]))
                ->setCellValue('AM'.$no, number_format($data[38]))
                ->setCellValue('AN'.$no, number_format($data[39]))
                ->setCellValue('AO'.$no, number_format($data[40]))
                ->setCellValue('AP'.$no, number_format($data[41]))
                ->setCellValue('AQ'.$no, number_format($data[42]))
                ->setCellValue('AR'.$no, number_format($data[42]))
				->setCellValue('AS'.$no, number_format($data[42]));
    
    $WHStock+=  number_format($data[19]); $TransAmount+= number_format($data[20]);
    $JanStock+= number_format($data[21]); $JanAmount+= number_format($data[22]);
	$FebStock+= number_format($data[23]); $FebAmount+= number_format($data[24]);
	$MarStock+= 0; $MarAmount+= 0;
	$AprStock+= 0; $AprAmount+= 0;
	$MayStock+= 0; $MayAmount+= 0;
	$JunStock+= 0; $JunAmount+= 0;
	$JulStock+= 0; $JulAmount+= 0;
	$AugStock+= 0; $AugAmount+= 0;
	$SepStock+= 0; $SepAmount+= 0;
	$OctStock+= 0; $OctAmount+= 0;
	$NovStock+= 0; $NovAmount+= 0;
	$DecStock+= 0; $DecAmount+= 0;
    

    $sheet->getStyle('A'.$no.':AS'.$no)->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    $sheet->getStyle("A".$no.":AS".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
    
    $sheet->getStyle("A".$no.":AS".$no)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );

    $no++;
    $noUrut++;
}   

$dt = date('d-M-Y');

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->getActiveSheet()->setTitle('FORECAST '.$dt);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="FORECAST '.$dt.'.csv"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
?>
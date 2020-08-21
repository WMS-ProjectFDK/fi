<?php
require_once '../class/phpexcel/PHPExcel.php';

$Arr_AssLine = array('LR01#1','LR03#1','LR03#2','LR03#3','LR06#1','LR06#2','LR06#3','LR06#4(T)','LR06#5','LR06#6');
$Arr_cellType = array('C01','C01NC','G06','G06NC','G07','G07NC','G08','G08NC','G08E');

$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ASSEMBLY LINE')
            ->setCellValue('B1', 'CELL TYPE')
            ->setCellValue('C1', 'MONTH')
            ->setCellValue('D1', 'YEAR')
            ->setCellValue('E1', 'DATE (QTY)')
            ->setCellValue('E2', '1')
            ->setCellValue('F2', '2')
            ->setCellValue('G2', '3')
            ->setCellValue('H2', '4')
            ->setCellValue('I2', '5')
            ->setCellValue('J2', '6')
            ->setCellValue('K2', '7')
            ->setCellValue('L2', '8')
            ->setCellValue('M2', '9')
            ->setCellValue('N2', '10')
            ->setCellValue('O2', '11')
            ->setCellValue('P2', '12')
            ->setCellValue('Q2', '13')
            ->setCellValue('R2', '14')
            ->setCellValue('S2', '15')
            ->setCellValue('T2', '16')
            ->setCellValue('U2', '17')
            ->setCellValue('V2', '18')
            ->setCellValue('W2', '19')
            ->setCellValue('X2', '20')
            ->setCellValue('Y2', '21')
            ->setCellValue('Z2',  '22')
            ->setCellValue('AA2', '23')
            ->setCellValue('AB2', '24')
            ->setCellValue('AC2', '25')
            ->setCellValue('AD2', '26')
            ->setCellValue('AE2', '27')
            ->setCellValue('AF2', '28')
            ->setCellValue('AG2', '29')
            ->setCellValue('AH2', '30')
            ->setCellValue('AI2', '31')
            ;
foreach(range('A','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
    $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
    $objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
    $objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
    $objPHPExcel->getActiveSheet()->mergeCells('E1:AI1');

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A1:AI2')->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $sheet->getStyle('A1:AI2')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'B4B4B4')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );

    $sheet->getColumnDimension('A')->setAutoSize(false);
    $sheet->getColumnDimension('A')->setWidth('15');

    $sheet->getColumnDimension('B')->setAutoSize(false);
    $sheet->getColumnDimension('B')->setWidth('15');

    $sheet->getColumnDimension('C')->setAutoSize(false);
    $sheet->getColumnDimension('C')->setWidth('15');

    $sheet->getColumnDimension('D')->setAutoSize(false);
    $sheet->getColumnDimension('D')->setWidth('15');

    $sheet->getColumnDimension('E')->setAutoSize(false);
    $sheet->getColumnDimension('E')->setWidth('8');

    $sheet->getColumnDimension('F')->setAutoSize(false);
    $sheet->getColumnDimension('F')->setWidth('8');

    $sheet->getColumnDimension('F')->setAutoSize(false);
    $sheet->getColumnDimension('F')->setWidth('8');

    $sheet->getColumnDimension('G')->setAutoSize(false);
    $sheet->getColumnDimension('G')->setWidth('8');

    $sheet->getColumnDimension('H')->setAutoSize(false);
    $sheet->getColumnDimension('H')->setWidth('8');

    $sheet->getColumnDimension('I')->setAutoSize(false);
    $sheet->getColumnDimension('I')->setWidth('8');

    $sheet->getColumnDimension('J')->setAutoSize(false);
    $sheet->getColumnDimension('J')->setWidth('8');

    $sheet->getColumnDimension('K')->setAutoSize(false);
    $sheet->getColumnDimension('K')->setWidth('8');

    $sheet->getColumnDimension('L')->setAutoSize(false);
    $sheet->getColumnDimension('L')->setWidth('8');

    $sheet->getColumnDimension('M')->setAutoSize(false);
    $sheet->getColumnDimension('M')->setWidth('8');

    $sheet->getColumnDimension('N')->setAutoSize(false);
    $sheet->getColumnDimension('N')->setWidth('8');

    $sheet->getColumnDimension('O')->setAutoSize(false);
    $sheet->getColumnDimension('O')->setWidth('8');

    $sheet->getColumnDimension('P')->setAutoSize(false);
    $sheet->getColumnDimension('P')->setWidth('8');

    $sheet->getColumnDimension('Q')->setAutoSize(false);
    $sheet->getColumnDimension('Q')->setWidth('8');

    $sheet->getColumnDimension('R')->setAutoSize(false);
    $sheet->getColumnDimension('R')->setWidth('8');

    $sheet->getColumnDimension('S')->setAutoSize(false);
    $sheet->getColumnDimension('S')->setWidth('8');

    $sheet->getColumnDimension('T')->setAutoSize(false);
    $sheet->getColumnDimension('T')->setWidth('8');

    $sheet->getColumnDimension('U')->setAutoSize(false);
    $sheet->getColumnDimension('U')->setWidth('8');

    $sheet->getColumnDimension('V')->setAutoSize(false);
    $sheet->getColumnDimension('V')->setWidth('8');

    $sheet->getColumnDimension('W')->setAutoSize(false);
    $sheet->getColumnDimension('W')->setWidth('8');

    $sheet->getColumnDimension('X')->setAutoSize(false);
    $sheet->getColumnDimension('X')->setWidth('8');

    $sheet->getColumnDimension('Y')->setAutoSize(false);
    $sheet->getColumnDimension('Y')->setWidth('8');

    $sheet->getColumnDimension('Z')->setAutoSize(false);
    $sheet->getColumnDimension('Z')->setWidth('8');

    $sheet->getColumnDimension('AA')->setAutoSize(false);
    $sheet->getColumnDimension('AA')->setWidth('8');

    $sheet->getColumnDimension('AB')->setAutoSize(false);
    $sheet->getColumnDimension('AB')->setWidth('8');

    $sheet->getColumnDimension('AC')->setAutoSize(false);
    $sheet->getColumnDimension('AC')->setWidth('8');

    $sheet->getColumnDimension('AD')->setAutoSize(false);
    $sheet->getColumnDimension('AD')->setWidth('8');

    $sheet->getColumnDimension('AE')->setAutoSize(false);
    $sheet->getColumnDimension('AE')->setWidth('8');

    $sheet->getColumnDimension('AF')->setAutoSize(false);
    $sheet->getColumnDimension('AF')->setWidth('8');

    $sheet->getColumnDimension('AG')->setAutoSize(false);
    $sheet->getColumnDimension('AG')->setWidth('8');

    $sheet->getColumnDimension('AH')->setAutoSize(false);
    $sheet->getColumnDimension('AH')->setWidth('8');

    $sheet->getColumnDimension('AI')->setAutoSize(false);
    $sheet->getColumnDimension('AI')->setWidth('8');
}

$row_xl =3;
for ($i=0; $i<count($Arr_AssLine); $i++){
    for ($j=0;$j<count($Arr_cellType);$j++){ 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row_xl, $Arr_AssLine[$i])
                    ->setCellValue('B'.$row_xl, $Arr_cellType[$j])
                    ->setCellValue('C'.$row_xl,date('m'))
                    ->setCellValue('D'.$row_xl,date('Y'))
        ;
        $sheet->getStyle('A'.$row_xl.':AI'.$row_xl)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $sheet->getStyle("A".$row_xl.":AI".$row_xl)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
        $sheet->getStyle("A".$row_xl.":AI".$row_xl)->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $ct = substr($Arr_cellType[$j],0,3);
        if($ct=='C01'){
            $sheet->getStyle("B".$row_xl.":AI".$row_xl)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
        }elseif($ct=='G06'){
            $sheet->getStyle("B".$row_xl.":AI".$row_xl)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('ED1C24');
        }elseif ($ct=='G07') {
            $sheet->getStyle("B".$row_xl.":AI".$row_xl)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('088F3D');
        }else{
            $sheet->getStyle("B".$row_xl.":AI".$row_xl)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('00CCFF');
        }
        $row_xl++;
    }
}

$dt = date('M').'-'.date('y');


$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->getActiveSheet()->setTitle('ASSEMBLY PLAN '.$dt);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="format assembly plan '.$dt.'.xls"');
$objWriter->save('php://output');
?>
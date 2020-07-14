<?php
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
include("../connect/conn.php");
date_default_timezone_set('Asia/Jakarta');
require_once '../class/phpexcel/PHPExcel.php';

$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';

$where ="where to_char(date_prod, 'YYYY-MM-DD HH24:MI:SS') BETWEEN '$date_awal' AND '$date_akhir'";

$sql = "select type_gel, kanban_no, no_tag, type_zn, qty_zn, qty_aquapec, qty_pw150, qty_th175b, qty_elec, qty_air, qty_total, 
    act_qty_aqupec, act_qty_pw150, act_qty_th175b,
    density,worker_id_gel, zw.name, to_char(upto_date_hasil_anode,'DD-MON-YY HH24:MI:SS') as upto_date_hasil_anode, 
    remark, assy_line, to_char(date_use,'DD-MON-YY HH24:MI:SS') as date_use, to_char(date_prod,'DD-MON-YY HH24:MI:SS') as date_prod
    from ztb_assy_anode_gel a
    inner join ztb_worker zw on a.worker_id_gel = zw.worker_id
    $where
    order by date_prod asc";
$data = oci_parse($connect, $sql);
oci_execute($data);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ANODE GEL TRANSACTION');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A1:S1');

$sheet->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 14
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'DATE PRODUCTION : '.$date_awal.' TO '.$date_akhir);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A2:S2');

$sheet->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'NO.')
                    ->setCellValue('B3', 'PRODUCT DATE')
                    ->setCellValue('C3', 'TYPE GEL')
                    ->setCellValue('D3', 'KANBAN NO.')
                    ->setCellValue('E3', 'TAG NO.')
                    ->setCellValue('F3', 'TYPE ZINC')
                    ->setCellValue('G3', 'COMPOSITION')
                    ->setCellValue('M3', 'TOTAL')
                    ->setCellValue('N3', 'WEIGHT RESULT')
                    ->setCellValue('Q3', 'DENSITY')
                    ->setCellValue('R3', 'ANODE GEL WORKER')
                    ->setCellValue('S3', 'ANODE GEL TIME')
                    ->setCellValue('T3', 'REMARK')
                    ->setCellValue('U3', 'ASSEMBLY LINE')
                    ->setCellValue('V3', 'ASSEMBLY TIME');

$sheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->setActiveSheetIndex()->mergeCells('A3:A4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('B3:B4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('C3:C4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('D3:D4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('E3:E4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('F3:F4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('G3:L3');
$objPHPExcel->setActiveSheetIndex()->mergeCells('M3:M4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('N3:P3');
$objPHPExcel->setActiveSheetIndex()->mergeCells('Q3:Q4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('R3:R4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('S3:S4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('T3:T4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('U3:U4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('V3:V4');

foreach(range('A','S') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->getStyle('A3:V4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A3:V4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A3:V4')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
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

$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G4', 'ZN')
                    ->setCellValue('H4', 'AQUPEC HV-505 HC')
                    ->setCellValue('I4', 'AQUPEC HV-501 E')
                    ->setCellValue('J4', 'TH-175B')
                    ->setCellValue('K4', 'ELECTROLYTE L')
                    ->setCellValue('L4', 'AIR')
                    ->setCellValue('N4', 'AQUPEC HV-505 HC')
                    ->setCellValue('O4', 'AQUPEC HV-501 E')
                    ->setCellValue('P4', 'TH-175B');

$sheet->getStyle('G4:P4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('G4:P4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('G4:P4')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
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

$nourut = 1;    $no_row = 5;
$t_zn = 0;      $t_el = 0;
$t_aq = 0;      $t_ai = 0;
$t_pw = 0;      $t_to = 0;
$t_th = 0;

$t_a_a = 0;
$t_a_p = 0;
$t_a_t = 0;

while ($row=oci_fetch_object($data)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no_row, $nourut) 
                ->setCellValue('B'.$no_row, $row->DATE_PROD) 
                ->setCellValue('C'.$no_row, $row->TYPE_GEL) 
                ->setCellValue('D'.$no_row, $row->KANBAN_NO) 
                ->setCellValue('E'.$no_row, $row->NO_TAG) 
                ->setCellValue('F'.$no_row, $row->TYPE_ZN) 
                ->setCellValue('G'.$no_row, $row->QTY_ZN) 
                ->setCellValue('H'.$no_row, $row->QTY_AQUAPEC) 
                ->setCellValue('I'.$no_row, $row->QTY_PW150) 
                ->setCellValue('J'.$no_row, $row->QTY_TH175B) 
                ->setCellValue('K'.$no_row, $row->QTY_ELEC) 
                ->setCellValue('L'.$no_row, $row->QTY_AIR) 
                ->setCellValue('M'.$no_row, $row->QTY_TOTAL) 
                ->setCellValue('N'.$no_row, $row->ACT_QTY_AQUPEC)
                ->setCellValue('O'.$no_row, $row->ACT_QTY_PW150)
                ->setCellValue('P'.$no_row, $row->ACT_QTY_TH175B)
                ->setCellValue('Q'.$no_row, $row->DENSITY) 
                ->setCellValue('R'.$no_row, $row->WORKER_ID_GEL.' - '.$row->NAME)
                ->setCellValue('S'.$no_row, $row->UPTO_DATE_HASIL_ANODE) 
                ->setCellValue('T'.$no_row, $row->REMARK) 
                ->setCellValue('U'.$no_row, $row->ASSY_LINE) 
                ->setCellValue('V'.$no_row, $row->DATE_USE);


    $objPHPExcel->getActiveSheet()->getStyle('G'.$no_row.':Q'.$no_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle('A'.$no_row.':F'.$no_row)->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $sheet->getStyle('A'.$no_row.':V'.$no_row)->applyFromArray(
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

    $n_zn = $row->QTY_ZN;
    $n_aq = $row->QTY_AQUAPEC;
    $n_pw = $row->QTY_PW150;
    $n_th = $row->QTY_TH175B;
    $n_el = $row->QTY_ELEC;
    $n_ai = $row->QTY_AIR;
    $n_to = $row->QTY_TOTAL;
    $n_a_aq = $row->ACT_QTY_AQUPEC;
    $n_a_pw = $row->ACT_QTY_PW150;
    $n_a_th = $row->ACT_QTY_TH175B;

    $t_zn += $n_zn;
    $t_aq += $n_aq;
    $t_pw += $n_pw;
    $t_th += $n_th;
    $t_el += $n_el;
    $t_ai += $n_ai;
    $t_to += $n_to;
    $t_a_aq += $n_a_aq;
    $t_a_pw += $n_a_pw;
    $t_a_th += $n_a_th;

    $nourut++;
    $no_row++;
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no_row, 'TOTAL')
            ->setCellValue('G'.$no_row, $t_zn)
            ->setCellValue('H'.$no_row, $t_aq)
            ->setCellValue('I'.$no_row, $t_pw)
            ->setCellValue('J'.$no_row, $t_th)
            ->setCellValue('K'.$no_row, $t_el)
            ->setCellValue('L'.$no_row, $t_ai)
            ->setCellValue('M'.$no_row, $t_to)
            ->setCellValue('N'.$no_row, $t_a_aq)
            ->setCellValue('O'.$no_row, $t_a_pw)
            ->setCellValue('P'.$no_row, $t_a_th)
            ->setCellValue('Q'.$no_row, '');

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no_row.':F'.$no_row);
$objPHPExcel->getActiveSheet()->mergeCells('Q'.$no_row.':V'.$no_row);
$objPHPExcel->getActiveSheet()->getStyle('G'.$no_row.':P'.$no_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$sheet->getStyle('A'.$no_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A'.$no_row)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$no_row.':V'.$no_row)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
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

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ANODE GEL - '.substr($date_awal,0,10).'.xlsx"');
$objWriter->save('php://output');
?>
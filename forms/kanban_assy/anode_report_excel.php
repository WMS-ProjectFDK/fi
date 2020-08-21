<?php
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
include("../connect/conn_kanbansys.php");
date_default_timezone_set('Asia/Jakarta');
require_once '../class/phpexcel/PHPExcel.php';

$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';

$where ="where date_prod BETWEEN '$date_awal' AND '$date_akhir'";

$sql = "select type_gel, kanban_no, no_tag, type_zn, qty_zn, qty_aquapec, qty_pw150, qty_th175b, qty_elec, qty_air, qty_total, 
    act_qty_aqupec, act_qty_pw150, act_qty_th175b, density,worker_id_gel, zw.name, qty_aqupec2, act_qty_aqupec2,
    convert(varchar, upto_date_hasil_anode,120) as upto_date_hasil_anode,
    convert(varchar, date_use,120) as date_use, 
    convert(varchar, date_prod,120) as date_prod,
    case when assy_line is null then '' 
         when assy_line = '0' then '' 
    else assy_line end as assy_line, 
    case when remark is null then '' 
         when remark = '0' then '' 
    else remark end as remark
    from ztb_assy_anode_gel a
    inner join ztb_worker zw on a.worker_id_gel = zw.worker_id
    $where
    order by date_prod asc";
$data = odbc_exec($connect, $sql);

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
                    ->setCellValue('N3', 'TOTAL')
                    ->setCellValue('O3', 'WEIGHT RESULT')
                    ->setCellValue('S3', 'DENSITY')
                    ->setCellValue('T3', 'ANODE GEL WORKER')
                    ->setCellValue('U3', 'ANODE GEL TIME')
                    ->setCellValue('V3', 'REMARK')
                    ->setCellValue('W3', 'ASSEMBLY LINE')
                    ->setCellValue('X3', 'ASSEMBLY TIME');

$sheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->setActiveSheetIndex()->mergeCells('A3:A4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('B3:B4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('C3:C4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('D3:D4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('E3:E4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('F3:F4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('G3:M3');
$objPHPExcel->setActiveSheetIndex()->mergeCells('N3:N4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('O3:R3');
$objPHPExcel->setActiveSheetIndex()->mergeCells('S3:S4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('T3:T4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('U3:U4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('V3:V4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('W3:W4');
$objPHPExcel->setActiveSheetIndex()->mergeCells('X3:X4');

foreach(range('A','X') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->getStyle('A3:X4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A3:X4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A3:X4')->applyFromArray(
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
                    ->setCellValue('J4', 'AQUPEC HV-505 E')
                    ->setCellValue('K4', 'TH-175B')
                    ->setCellValue('L4', 'ELECTROLYTE L')
                    ->setCellValue('M4', 'AIR')
                    ->setCellValue('O4', 'AQUPEC HV-505 HC')
                    ->setCellValue('P4', 'AQUPEC HV-501 E')
                    ->setCellValue('Q4', 'AQUPEC HV-505 E')
                    ->setCellValue('R4', 'TH-175B');

$sheet->getStyle('G4:R4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('G4:R4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('G4:R4')->applyFromArray(
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
$t_a2 = 0;      $t_th = 0;


$t_a_a = 0;
$t_a_p = 0;
$t_a_t = 0;

while ($row=odbc_fetch_object($data)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no_row, $nourut) 
                ->setCellValue('B'.$no_row, $row->date_prod)
                ->setCellValue('C'.$no_row, $row->type_gel)
                ->setCellValue('D'.$no_row, $row->kanban_no) 
                ->setCellValue('E'.$no_row, $row->no_tag) 
                ->setCellValue('F'.$no_row, $row->type_zn) 
                ->setCellValue('G'.$no_row, $row->qty_zn) 
                ->setCellValue('H'.$no_row, $row->qty_aquapec) 
                ->setCellValue('I'.$no_row, $row->qty_pw150) 
                ->setCellValue('J'.$no_row, $row->qty_aqupec2) 
                ->setCellValue('K'.$no_row, $row->qty_th175b) 
                ->setCellValue('L'.$no_row, $row->qty_elec) 
                ->setCellValue('M'.$no_row, $row->qty_air) 
                ->setCellValue('N'.$no_row, $row->qty_total) 
                ->setCellValue('O'.$no_row, $row->act_qty_aqupec)
                ->setCellValue('P'.$no_row, $row->act_qty_pw150)
                ->setCellValue('Q'.$no_row, $row->act_qty_aqupec2) 
                ->setCellValue('R'.$no_row, $row->act_qty_th175b)
                ->setCellValue('S'.$no_row, $row->density) 
                ->setCellValue('T'.$no_row, $row->worker_id_gel.' - '.$row->name)
                ->setCellValue('U'.$no_row, $row->upto_date_hasil_anode) 
                ->setCellValue('V'.$no_row, $row->remark)
                ->setCellValue('W'.$no_row, $row->assy_line) 
                ->setCellValue('X'.$no_row, $row->date_use);

    $objPHPExcel->getActiveSheet()->getStyle('G'.$no_row.':Q'.$no_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle('A'.$no_row.':F'.$no_row)->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $sheet->getStyle('A'.$no_row.':X'.$no_row)->applyFromArray(
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

    $n_zn = $row->qty_zn;
    $n_aq = $row->qty_aquapec;
    $n_pw = $row->qty_pw150;
    $n_a2 = $row->qty_aqupec2;
    $n_th = $row->qty_th175b;
    $n_el = $row->qty_elec;
    $n_ai = $row->qty_air;
    $n_to = $row->qty_total;
    $n_a_aq = $row->act_qty_aqupec;
    $n_a_pw = $row->act_qty_pw150;
    $n_a_a2 = $row->act_qty_aqupec2;
    $n_a_th = $row->act_qty_th175b;

    $t_zn += $n_zn;
    $t_aq += $n_aq;
    $t_pw += $n_pw;
    $t_a2 += $n_a2;
    $t_th += $n_th;
    $t_el += $n_el;
    $t_ai += $n_ai;
    $t_to += $n_to;
    $t_a_aq += $n_a_aq;
    $t_a_pw += $n_a_pw;
    $t_a_a2 += $n_a_a2;
    $t_a_th += $n_a_th;

    $nourut++;
    $no_row++;
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no_row, 'TOTAL')
            ->setCellValue('G'.$no_row, $t_zn)
            ->setCellValue('H'.$no_row, $t_aq)
            ->setCellValue('I'.$no_row, $t_pw)
            ->setCellValue('J'.$no_row, $t_a2)
            ->setCellValue('K'.$no_row, $t_th)
            ->setCellValue('L'.$no_row, $t_el)
            ->setCellValue('M'.$no_row, $t_ai)
            ->setCellValue('N'.$no_row, $t_to)
            ->setCellValue('O'.$no_row, $t_a_aq)
            ->setCellValue('P'.$no_row, $t_a_pw)
            ->setCellValue('Q'.$no_row, $t_a_a2)
            ->setCellValue('R'.$no_row, $t_a_th)
            ->setCellValue('S'.$no_row, '');

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no_row.':F'.$no_row);
$objPHPExcel->getActiveSheet()->mergeCells('S'.$no_row.':X'.$no_row);
$objPHPExcel->getActiveSheet()->getStyle('G'.$no_row.':R'.$no_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$sheet->getStyle('A'.$no_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A'.$no_row)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$no_row.':X'.$no_row)->applyFromArray(
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
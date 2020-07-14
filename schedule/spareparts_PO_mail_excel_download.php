<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
require_once '../class/phpexcel/PHPExcel.php';
include("../connect/conn2.php");

$qry = "select case substr(trim(mpr_no),0,1) 
                    when 'A' Then 'Assembling'
                    when 'C' Then 'Component'
                    when 'F' Then 'Finishing'
                    when 'M' Then 'Mechanical'
                    when 'Q' Then 'QC' END
                    Department,
               r.supplier_code, 
               cc.company,
               s.ETA,     
               s.Item_no,
               i.description,
               s.po_no,
               s.line_no, 
               ex_rate * u_price * qty Expense,
               ex_rate * u_price * bal_qty Sparts_Expense_Not_Arrive,
               ex_rate * u_price * gr_qty Sparts_Expense_Arrive 
        from po_details s
        inner join po_header r
        on s.po_no = r.po_no
        inner join company cc
        on r.supplier_code = cc.company_code
        inner join item i
        on i.item_no = s.item_no
        where to_char(s.ETA,'YYYYMM') = (select to_char(sysdate,'YYYYMM') from dual)
        order by substr(trim(mpr_no),0,1),ETA";
$data = oci_parse($connect, $qry);
oci_execute($data);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SPAREPARTS PURCHASE '.strtoupper(date('F Y')));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A1:I1');

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
                    ->setCellValue('A2', 'DEPARTEMENT')
                    ->setCellValue('B2', 'SUPPLIER')
                    ->setCellValue('C2', 'ITEM')
                    ->setCellValue('D2', 'PO NO.')
                    ->setCellValue('E2', 'ETA')
                    ->setCellValue('F2', 'TOTAL')
                    ->setCellValue('G2', 'NOT ARRIVE')
                    ->setCellValue('H2', 'ARRIVE');

$sheet = $objPHPExcel->getActiveSheet();

foreach(range('A','H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A2:H2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A2:H2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A2:H2')->applyFromArray(
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

$no=3;  $dept = '';     $tot_row = 0;
$tot_expense = 0;       $dpt = '';
$tot_not_expense = 0;   $d = '';
$tot_arrive_expense = 0;
while ($row = oci_fetch_object($data)){
    $SUPP = $row->SUPPLIER_CODE.' - '.$row->COMPANY;
    $ITM = $row->ITEM_NO.' - '.$row->DESCRIPTION;

    if($no==3){
        $dpt = strtoupper($row->DEPARTMENT);
        $d = strtoupper($row->DEPARTMENT);
        $tot_expense += $row->EXPENSE;
        $tot_not_expense += $row->SPARTS_EXPENSE_NOT_ARRIVE;
        $tot_arrive_expense += $row->SPARTS_EXPENSE_ARRIVE;
        $tot_row = $no;
        $no++;
    }else{
        if($dept == $row->DEPARTMENT){
            $dpt = strtoupper($row->DEPARTMENT);
            $d = '';
            $tot_expense += $row->EXPENSE;
            $tot_not_expense += $row->SPARTS_EXPENSE_NOT_ARRIVE;
            $tot_arrive_expense += $row->SPARTS_EXPENSE_ARRIVE;
        }else{
            //tampilan total
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$tot_row, $dpt)
                        ->setCellValue('F'.$tot_row, $tot_expense)
                        ->setCellValue('G'.$tot_row, $tot_not_expense)
                        ->setCellValue('H'.$tot_row, $tot_arrive_expense);

            $objPHPExcel->getActiveSheet()->mergeCells('A'.$tot_row.':E'.$tot_row);

            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':E'.$tot_row);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$tot_row.':H'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $sheet->getStyle('A'.$tot_row.':H'.$tot_row)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00AAFF')
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

            $tot_expense = 0;
            $tot_not_expense = 0;
            $tot_arrive_expense = 0;
            $tot_row = $no;
            $no++;

            $dpt = strtoupper($row->DEPARTMENT);
            $d = strtoupper($row->DEPARTMENT);
            $tot_expense += $row->EXPENSE;
            $tot_not_expense += $row->SPARTS_EXPENSE_NOT_ARRIVE;
            $tot_arrive_expense += $row->SPARTS_EXPENSE_ARRIVE;
        }
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $d)
                ->setCellValue('B'.$no, $SUPP)
                ->setCellValue('C'.$no, $ITM)
                ->setCellValue('D'.$no, $row->PO_NO)
                ->setCellValue('E'.$no, $row->ETA)
                ->setCellValue('F'.$no, $row->EXPENSE)
                ->setCellValue('G'.$no, $row->SPARTS_EXPENSE_NOT_ARRIVE)
                ->setCellValue('H'.$no, $row->SPARTS_EXPENSE_ARRIVE);

    $sheet->getStyle('A'.$no.':H'.$no)->applyFromArray(
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

    $objPHPExcel->getActiveSheet()->getStyle('G'.$no.':I'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $dept = $row->DEPARTMENT;
    $dpt = strtoupper($row->DEPARTMENT);
    $no++;
}
//tampilan
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$tot_row, $dpt)
            ->setCellValue('F'.$tot_row, $tot_expense)
            ->setCellValue('G'.$tot_row, $tot_not_expense)
            ->setCellValue('H'.$tot_row, $tot_arrive_expense);

$objPHPExcel->getActiveSheet()->mergeCells('A'.$tot_row.':H'.$tot_row);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':H'.$tot_row);
$objPHPExcel->getActiveSheet()->getStyle('F'.$tot_row.':H'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A'.$tot_row)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$tot_row.':H'.$tot_row)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00AAFF')
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

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('SPAREPARTS PURCHASE '.strtoupper(date('M Y')));
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="SPAREPARTS PURCHASE '.strtoupper(date('M Y')).'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
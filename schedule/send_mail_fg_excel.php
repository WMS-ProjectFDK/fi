<?php
// Create By : Ueng hernama
// Date : 24-oct-2017
// ID = 2
include("../connect/conn.php");
/* CONTENT ATACHMENT*/
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
$qry = "select aa.* from (
        select a.item_no, b.description, a.wo_no,max(a.tanggal) as tgl,ms.cr_date,sum(a.qty) as qty,ms.qty as Order_Quantity, b.standard_price, sum(a.qty)*b.standard_price as amount 
                from ztb_fg_fifo a
                inner join item b on a.item_no=b.item_no
                left outer join mps_header ms
                on ms.work_order = a.wo_no
                group by a.item_no, b.description, a.wo_no, b.standard_price,ms.cr_date,ms.qty
                order by a.item_no, sum(a.qty) desc
        ) aa
        left outer join
        (
        SELECT item_no, sum(qty) as qty_total from ztb_fg_fifo
        group by item_no
        ) bb
        on aa.item_no=bb.item_no
        order by bb.qty_total desc";
$result = oci_parse($connect, $qry);
oci_execute($result);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FINISH GOODS INCOME FOR '.date('l, F d, Y'));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A1:H1');

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
            'size'  => 12
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'ITEM')
            ->setCellValue('B4', 'DESCRIPTION')
            ->setCellValue('C4', 'WO')
            ->setCellValue('D4', 'DATE')
            ->setCellValue('E4', 'CR DATE')
            ->setCellValue('F4', 'ORDER QTY')
            ->setCellValue('G4', 'KURAIRE QTY')
            ->setCellValue('H4', 'AMOUNT BY STANDARD PRICE');

$sheet = $objPHPExcel->getActiveSheet();

foreach(range('A','H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A4:H4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A4:H4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A4:H4')->applyFromArray(
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

$no = 5;        
$tot_qty=0;             $tot_amo = 0;
$tot_qty_item = 0;      $tot_amo_item=0;
$tot_row = 0;           $itm = '';          $itm_desc = '';
while ($row=oci_fetch_object($result)){
    if($no==5){
        $tot_row = $no;                 $no++;
        $itm_desc = $row->ITEM_NO.' - '.$row->DESCRIPTION;
        $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
    }else{
        if($row->ITEM_NO == $itm){
            $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
            $itm_desc = $row->ITEM_NO.' - '.$row->DESCRIPTION;
        }else{

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$tot_row, $itm_desc)
                ->setCellValue('G'.$tot_row, $tot_qty_item)
                ->setCellValue('H'.$tot_row, $tot_amo_item);
            
            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':F'.$tot_row);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$tot_row.':H'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

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

            $tot_qty += $tot_qty_item;             
            $tot_amo += $tot_amo_item;

            $tot_qty_item = 0;
            $tot_amo_item = 0;
            $tot_row = $no;                 $no++;
            $itm = $row->ITEM_NO.' - '.$row->DESCRIPTION;
            $itm_desc = $row->ITEM_NO.' - '.$row->DESCRIPTION;
            $tot_qty_item += $row->QTY;     $tot_amo_item += $row->AMOUNT;
        }
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $row->ITEM_NO)
                ->setCellValue('B'.$no, $row->DESCRIPTION)
                ->setCellValue('C'.$no, $row->WO_NO)
                ->setCellValue('D'.$no, $row->TGL)
                ->setCellValue('E'.$no, $row->CR_DATE)
                ->setCellValue('F'.$no, $row->ORDER_QUANTITY)
                ->setCellValue('G'.$no, $row->QTY)
                ->setCellValue('H'.$no, $row->AMOUNT);

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

    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':H'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    
    $itm = $row->ITEM_NO;
    $no++;
}

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$tot_row, $itm_desc)
            ->setCellValue('G'.$tot_row, $tot_qty_item)
            ->setCellValue('H'.$tot_row, $tot_amo_item);
        
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$tot_row.':F'.$tot_row);
$objPHPExcel->getActiveSheet()->getStyle('G'.$tot_row.':H'.$tot_row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

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

$tot_qty += $tot_qty_item;             
$tot_amo += $tot_amo_item;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'QTY : '.number_format($tot_qty,2));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A2:H2');

$sheet->getStyle('A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
$sheet->getStyle('A2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A2')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'AMOUNT : '.number_format($tot_amo,2));
$objPHPExcel->setActiveSheetIndex()->mergeCells('A3:H3');

$sheet->getStyle('A3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
$sheet->getStyle('A3')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A3')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('FG REPORT'.date('M'));
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="FG REPORT '.date('M').'.xls"');
$objWriter->save('php://output');
?>
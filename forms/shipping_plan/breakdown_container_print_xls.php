<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
include("../../connect/conn.php");
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';

$exp_ppbe = explode('/', $ppbe);
if ($exp_ppbe[1] =='W'){
    $nm = 'WISNU';
}elseif ($exp_ppbe[1] == 'A'){
    $nm = 'AGUNG';
}elseif ($exp_ppbe[1] == 'D'){
    $nm = 'DEWI';
}

$sql_h = "select distinct LIST_COLLECT(a.SI_NO, ', ') as SI_NO, a.crs_remark, b.do_no, b.do_date, c.booking_no,
    rtrim(replace(b.ship_name,chr(10),'<br>'),'|') as vessel, b.final_destination, b.etd, s.forwarder_name
    from answer a
    left join do_header b on a.si_no = b.si_no
    left join forwarder_letter c on b.do_no = c.do_no
    left join si_header s on a.si_no = s.si_no
    where a.crs_remark = '$ppbe'";
$data_h = sqlsrv_query($connect, strtoupper($sql_h));
$dt_h = sqlsrv_fetch_object($data_h);

$sql = "select a.ppbe_no, LIST_COLLECT(b.SI_NO, ', ') as SI_NO, a.wo_no, a.item_no, it.description,
    a.qty, a.net, a.gross, a.msm, a.pallet, a.container_no || chr(10) || '(' || a.containers || ')' as container_no, zti.pallet_pcs, zti.pallet_ctn, 
    a.qty/(zti.pallet_pcs/zti.pallet_ctn) as carton_qty, a.tw, b.customer_po_no, a.enr
    from ztb_shipping_detail a
    left outer join answer b on a.wo_no = b.work_no and a.ppbe_no = b.crs_remark
    inner join item it on a.item_no = it.item_no
    left join ztb_item zti on a.item_no = zti.item_no
    where a.ppbe_no='$ppbe'
    order by a.container_no";
$data = sqlsrv_query($connect, strtoupper($sql));

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

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'BREAKDOWN CONTAINER');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A4:M4');

$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A4')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B5', 'ATTN :')
            ->setCellValue('C5', $dt_h->FORWARDER_NAME)
            ->setCellValue('H5', 'BOOKING NO. :')
            ->setCellValue('J5', $dt_h->BOOKING_NO);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C5:F5');
$objPHPExcel->setActiveSheetIndex()->mergeCells('H5:I5');
$objPHPExcel->setActiveSheetIndex()->mergeCells('J5:L5');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B6', 'FROM :')
            ->setCellValue('C6', $nm.' - FDK INDONESIA')
            ->setCellValue('H6', 'INVOICE NO. :')
            ->setCellValue('J6', $dt_h->DO_NO);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C6:F6');
$objPHPExcel->setActiveSheetIndex()->mergeCells('H6:I6');
$objPHPExcel->setActiveSheetIndex()->mergeCells('J6:L6');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B7', 'RE :')
            ->setCellValue('C7', 'CONTAINER BREAK DOWN DESTINED TO '.$dt_h->FINAL_DESTINATION)
            ->setCellValue('H7', 'PPBE NO. :')
            ->setCellValue('J7', $ppbe);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C7:F7');
$objPHPExcel->setActiveSheetIndex()->mergeCells('H7:I7');
$objPHPExcel->setActiveSheetIndex()->mergeCells('J7:L7');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B8', 'VESSEL :')
            ->setCellValue('C8', $dt_h->VESSEL)
            ->setCellValue('H8', 'FDKA INV. NO. :')
            ->setCellValue('J8', '');
$objPHPExcel->setActiveSheetIndex()->mergeCells('C8:F8');
$objPHPExcel->setActiveSheetIndex()->mergeCells('H8:I8');
$objPHPExcel->setActiveSheetIndex()->mergeCells('J8:L8');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B9', 'DATE :')
            ->setCellValue('C9', $dt_h->DO_DATE)
            ->setCellValue('H9', 'ETD :')
            ->setCellValue('J9', $dt_h->ETD);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C9:F9');
$objPHPExcel->setActiveSheetIndex()->mergeCells('H9:I9');
$objPHPExcel->setActiveSheetIndex()->mergeCells('J9:L9');

 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A11', 'FI PO NO.')
            ->setCellValue('B11', 'FI ITEM NAME')
            ->setCellValue('C11', 'FI ITEM NO.')
            ->setCellValue('D11', 'ENR PART')
            ->setCellValue('E11', 'QTY')
            ->setCellValue('F11', 'CARTON QTY')
            ->setCellValue('G11', 'GW (KGS)')
            ->setCellValue('H11', 'NW (KGS)')
            ->setCellValue('I11', 'MSM (CBM)')
            ->setCellValue('J11', 'PALLET QTY')
            ->setCellValue('K11', 'CONTAINER NO.')
            ->setCellValue('L11', 'TARE WEIGHT (KGS)')
            ->setCellValue('M11', 'VGM (KGS)');
            
foreach(range('A','M') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A11:M11')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A11:M11')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A11:M11')->applyFromArray(
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

$cont = '';     $no=1;      $noUrut = 12;
$grand_tot_qty = 0;         $tot_qty = 0;           
$grand_tot_carton = 0;      $tot_carton = 0;        
$grand_tot_gw  = 0;         $tot_gw = 0;
$grand_tot_nw = 0;          $tot_nw = 0;
$grand_tot_msm = 0;         $tot_msm = 0;
$grand_tot_plt = 0;         $tot_plt = 0;       
$grand_tw = 0;              $tw = 0;
while ($dt=oci_fetch_object($data)){
    $container = $dt->CONTAINER_NO;
    $qty = $dt->QTY;                        $nw = $dt->NET;
    $carton = $dt->CARTON_QTY;              $msm = number_format($dt->MSM,3);
    $gw = $dt->GROSS;                       $plt = ceil($dt->PALLET);

    if($no == 1){
        $tot_qty += $qty;           $tot_nw += $nw;
        $tot_carton += $carton;     $tot_msm += $msm;
        $tot_gw += $gw;             $tot_plt += $plt;
        $tw = $dt->TW;
    }else{
        if ($cont == $container){
            $tot_qty += $qty;           $tot_nw += $nw;
            $tot_carton += $carton;     $tot_msm += $msm;
            $tot_gw += $gw;             $tot_plt += $plt;
            $tw = $dt->TW;
        }else{
            $grand_tot_qty += $tot_qty;
            $grand_tot_carton += $tot_carton;
            $grand_tot_gw  += $tot_gw;
            $grand_tot_nw += $tot_nw;
            $grand_tot_msm += $tot_msm;
            $grand_tot_plt += $tot_plt;
            $grand_tw += $tw;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C'.$noUrut, 'TOTAL')
                        ->setCellValue('E'.$noUrut, $tot_qty)
                        ->setCellValue('F'.$noUrut, $tot_carton)
                        ->setCellValue('G'.$noUrut, $tot_gw)
                        ->setCellValue('H'.$noUrut, $tot_nw)
                        ->setCellValue('I'.$noUrut, $tot_msm)
                        ->setCellValue('J'.$noUrut, $tot_plt)
                        ->setCellValue('K'.$noUrut, '')
                        ->setCellValue('L'.$noUrut, $tw)
                        ->setCellValue('M'.$noUrut, $tot_gw+$tw);
            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noUrut.':B'.$noUrut);
            $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$noUrut.':D'.$noUrut);
            $sheet->getStyle('C'.$noUrut.':M'.$noUrut)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F4F4F4')
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

            $sheet->getStyle('E'.$noUrut.':J'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getStyle('L'.$noUrut.':M'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $noUrut++;

            $tot_qty = 0;           $tot_nw = 0;
            $tot_carton = 0;        $tot_msm = 0;
            $tot_gw = 0;            $tot_plt = 0;   
            $tw=0;

            $tot_qty += $qty;           $tot_nw += $nw;
            $tot_carton += $carton;     $tot_msm += $msm;
            $tot_gw += $gw;             $tot_plt += $plt;   
            $tw = $dt->TW;
        }   
    }

    if ($cont == $container){
        $cnt = '';
    }else{
        $cnt = $container;
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noUrut, $dt->CUSTOMER_PO_NO)
                ->setCellValue('B'.$noUrut, $dt->DESCRIPTION)
                ->setCellValue('C'.$noUrut, $dt->ITEM_NO)
                ->setCellValue('D'.$noUrut, $dt->ENR)
                ->setCellValue('E'.$noUrut, $dt->QTY)
                ->setCellValue('F'.$noUrut, $dt->CARTON_QTY)
                ->setCellValue('G'.$noUrut, $dt->GROSS)
                ->setCellValue('H'.$noUrut, $dt->NET)
                ->setCellValue('I'.$noUrut, $dt->MSM)
                ->setCellValue('J'.$noUrut, ceil($dt->PALLET))
                ->setCellValue('K'.$noUrut, $cnt)
                ->setCellValue('L'.$noUrut, $dt->TW)
                ->setCellValue('M'.$noUrut, $dt->GROSS+$dt->TW);

    $sheet->getStyle('E'.$noUrut.':J'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle('L'.$noUrut.':M'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $cont = $container;
    $no++;  $noUrut++;
}

$grand_tot_qty += $tot_qty;
$grand_tot_carton += $tot_carton;
$grand_tot_gw  += $tot_gw;
$grand_tot_nw += $tot_nw;
$grand_tot_msm += $tot_msm;
$grand_tot_plt += $tot_plt;
$grand_tw += $tw;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$noUrut, 'TOTAL')
            ->setCellValue('E'.$noUrut, $tot_qty)
            ->setCellValue('F'.$noUrut, $tot_carton)
            ->setCellValue('G'.$noUrut, $tot_gw)
            ->setCellValue('H'.$noUrut, $tot_nw)
            ->setCellValue('I'.$noUrut, $tot_msm)
            ->setCellValue('J'.$noUrut, $tot_plt)
            ->setCellValue('K'.$noUrut, '')
            ->setCellValue('L'.$noUrut, $tw)
            ->setCellValue('M'.$noUrut, $tot_gw+$tw);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noUrut.':B'.$noUrut);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$noUrut.':D'.$noUrut);
$sheet->getStyle('C'.$noUrut.':M'.$noUrut)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F4F4F4')
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

$sheet->getStyle('E'.$noUrut.':J'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle('L'.$noUrut.':M'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$noUrut+=2;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$noUrut, 'TOTAL ALL')
            ->setCellValue('E'.$noUrut, $grand_tot_qty)
            ->setCellValue('F'.$noUrut, $grand_tot_carton)
            ->setCellValue('G'.$noUrut, $grand_tot_gw)
            ->setCellValue('H'.$noUrut, $grand_tot_nw)
            ->setCellValue('I'.$noUrut, $grand_tot_msm)
            ->setCellValue('J'.$noUrut, $grand_tot_plt)
            ->setCellValue('K'.$noUrut, '')
            ->setCellValue('L'.$noUrut, $grand_tw)
            ->setCellValue('M'.$noUrut, $grand_tot_gw + $grand_tw);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noUrut.':B'.$noUrut);
$objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$noUrut.':D'.$noUrut);
$sheet->getStyle('C'.$noUrut.':M'.$noUrut)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'BCBCBC')
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

$sheet->getStyle('E'.$noUrut.':J'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle('L'.$noUrut.':M'.$noUrut)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->setActiveSheetIndex(0);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('WMS-FDKI');
$objDrawing->setDescription('FDKI');
$objDrawing->setPath('../images/logo-print4.png');
$objDrawing->setWidth('500px');
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="BreakDown_Cont_'.$ppbe.'.xlsx"');
$objWriter->save('php://output');
?>
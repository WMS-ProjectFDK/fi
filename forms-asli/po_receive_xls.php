<?php
ini_set('memory_limit', '-1');
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$date_awal=isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir=isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date=isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$date_sts = isset($_REQUEST['date_sts']) ? strval($_REQUEST['date_sts']) : '';      //check_eta//check_po;

$cmb_po=isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po=isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';

$supplier=isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
$supplier_nm=isset($_REQUEST['supplier_nm']) ? strval($_REQUEST['supplier_nm']) : '';
$ck_supplier=isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';

$cmb_item_no=isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no=isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$txt_item_name=isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';

$endorder=isset($_REQUEST['endorder']) ? strval($_REQUEST['endorder']) : '';

if($ck_date!='true'){
    if($date_sts== 'check_eta'){
        $dt = "aa.eta between to_date('$date_awal','yyyy-mm-dd') AND to_date('$date_akhir','yyyy-mm-dd') AND ";
    }elseif($date_sts== 'check_po'){
        $dt = "aa.po_date between to_date('$date_awal','yyyy-mm-dd') AND to_date('$date_akhir','yyyy-mm-dd') AND ";
    }
}else{
    $dt = "";
}

if($ck_po!='true'){
    $po = "aa.po_no = '$cmb_po' AND ";
}else{
    $po = "";
}

if($ck_supplier!='true'){
    $supp = "aa.supplier_code = '$supplier' AND ";
}else{
    $supp = "";
}

if($ck_item_no!='true'){
    $itm = "aa.item_no = '$cmb_item_no' AND ";
}else{
    $itm = "";
}

if($endorder!='true'){
    $order = "aa.statuspo = 1 ";
}else{
    $order = "(aa.statuspo = 0 OR aa.statuspo = 1)";
}

$where = "where $dt $po $supp $itm $order ";

$result = array();

$qry = "select * from (select r.po_no, s.line_no, r.po_date, r.supplier_code, cc.company, r.remark1, s.item_no, ii.description,
       s.qty, s.bal_qty, s.gr_qty, s.eta, tt.slip_no, tt.slip_quantity, tt.slip_date, 
       case when qty <> bal_qty+ gr_qty then 1 else case when totalGR <> gr_qty then 1 else 0 end end as StatusBalance,
       case when qty > gr_qty then 1 else 0 end StatusPO
       from po_header r
       inner join po_details s on r.po_no = s.po_no
       inner join company cc on r.supplier_code = cc.company_code
       inner join (select item_no,description from item) ii on s.item_no = ii.item_no
       left outer join (select order_number,line_no, slip_no,slip_quantity,slip_date from transaction)tt on s.po_no = tt.order_number and s.line_no = tt.line_no
       left outer join (select order_number,line_no, Sum(slip_quantity) totalGR from transaction group by order_number,line_no)ttx 
       on s.po_no = ttx.order_number and s.line_no =ttx.line_no) aa
       $where
       order by po_no,po_date,line_no, slip_date asc";
$result = oci_parse($connect, $qry);
oci_execute($result);

$date=date("d M y / H:i:s",time());

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

$no=2;
$pono='';   
$LN = '';
while ($data=oci_fetch_object($result)) {
    if ($data->STATUSBALANCE == 0){
        $bnc = 'BALANCE';
    }else{
        $bnc = 'NOT BALANCE';
    }

    if($data->STATUSPO == 0){
        $posts = 'FILLED';
    }else{
        $posts = 'IN ORDER';
    }

    if($no==2){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, 'PO NO.')
                    ->setCellValue('B'.$no, 'PO DATE')
                    ->setCellValue('C'.$no, 'SUPPLIER CODE')
                    ->setCellValue('D'.$no, 'SUPPLIER NAME');

        $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
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
        $no++;

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, $data->PO_NO)
                    ->setCellValue('B'.$no, $data->PO_DATE)
                    ->setCellValue('C'.$no, $data->SUPPLIER_CODE)
                    ->setCellValue('D'.$no, $data->COMPANY);
        
        $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
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
        $no+=2;

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, 'LINE NO')
                    ->setCellValue('B'.$no, 'ITEM')
                    ->setCellValue('C'.$no, 'DESCRIPTION')
                    ->setCellValue('E'.$no, 'ETA')
                    ->setCellValue('F'.$no, 'PO QTY')
                    ->setCellValue('G'.$no, 'RECEIVE QTY')
                    ->setCellValue('H'.$no, 'BALANCE_QTY')
                    ->setCellValue('I'.$no, 'BALANCE')
                    ->setCellValue('J'.$no, 'STATUS')
                    ->setCellValue('K'.$no, 'GR NO.')
                    ->setCellValue('L'.$no, 'GR DATE')
                    ->setCellValue('M'.$no, 'GR QTY');

        $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$no.':D'.$no);
        $sheet->getStyle('A'.$no.':M'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no.':M'.$no)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A'.$no.':J'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D0FBD0')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle('K'.$no.':M'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFAA00')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
        
        $no++;
    }else{
        if($pono != $data->PO_NO){
            $no++;
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$no, 'PO NO.')
                        ->setCellValue('B'.$no, 'PO DATE')
                        ->setCellValue('C'.$no, 'SUPPLIER CODE')
                        ->setCellValue('D'.$no, 'SUPPLIER NAME');

            $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
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
            $no++;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$no, $data->PO_NO)
                        ->setCellValue('B'.$no, $data->PO_DATE)
                        ->setCellValue('C'.$no, $data->SUPPLIER_CODE)
                        ->setCellValue('D'.$no, $data->COMPANY);

            $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
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
            $no+=2;

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$no, 'LINE NO')
                        ->setCellValue('B'.$no, 'ITEM')
                        ->setCellValue('C'.$no, 'DESCRIPTION')
                        ->setCellValue('E'.$no, 'ETA')
                        ->setCellValue('F'.$no, 'PO QTY')
                        ->setCellValue('G'.$no, 'RECEIVE QTY')
                        ->setCellValue('H'.$no, 'BALANCE_QTY')
                        ->setCellValue('I'.$no, 'BALANCE')
                        ->setCellValue('J'.$no, 'STATUS')
                        ->setCellValue('K'.$no, 'GR NO.')
                        ->setCellValue('L'.$no, 'GR DATE')
                        ->setCellValue('M'.$no, 'GR QTY');

            $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$no.':D'.$no);
            $sheet->getStyle('A'.$no.':M'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('A'.$no.':M'.$no)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $sheet->getStyle('A'.$no.':J'.$no)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'D0FBD0')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );

            $sheet->getStyle('K'.$no.':M'.$no)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFAA00')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $no++;
        }
    }

    if ($data->PO_NO == $pono AND $data->LINE_NO == $LN){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$no, $data->SLIP_NO)      
                    ->setCellValue('L'.$no, $data->SLIP_DATE)
                    ->setCellValue('M'.$no, $data->SLIP_QUANTITY);

        $sheet->getStyle('K'.$no.':M'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFAA00')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);            
    }else{
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no, $data->LINE_NO) 
                    ->setCellValue('B'.$no, $data->ITEM_NO)   
                    ->setCellValue('C'.$no, $data->DESCRIPTION)   
                    ->setCellValue('E'.$no, $data->ETA)
                    ->setCellValue('F'.$no, $data->QTY)       
                    ->setCellValue('G'.$no, $data->GR_QTY)  
                    ->setCellValue('H'.$no, $data->BAL_QTY)
                    ->setCellValue('I'.$no, $bnc)
                    ->setCellValue('J'.$no, $posts)
                    ->setCellValue('K'.$no, $data->SLIP_NO)      
                    ->setCellValue('L'.$no, $data->SLIP_DATE)
                    ->setCellValue('M'.$no, $data->SLIP_QUANTITY);

        $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$no.':D'.$no);
        $sheet->getStyle('A'.$no.':J'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D0FBD0')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle('K'.$no.':M'.$no)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFAA00')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('F'.$no.':H'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    }

    $pono = $data->PO_NO;
    $LN = $data->LINE_NO;
    $no++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('PO RECEIVE'.$tanggal);
 
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
header('Content-Disposition: attachment; filename="PO RECEIVE - '.$tanggal.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>
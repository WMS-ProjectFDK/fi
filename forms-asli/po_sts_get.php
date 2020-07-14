<?php
ini_set('memory_limit', '-1');
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$supplier = isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$date_eta = isset($_REQUEST['date_eta']) ? strval($_REQUEST['date_eta']) : '';
$date_eta_akhir = isset($_REQUEST['date_eta_akhir']) ? strval($_REQUEST['date_eta_akhir']) : '';
$ck_eta = isset($_REQUEST['ck_eta']) ? strval($_REQUEST['ck_eta']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';
$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';
$ck_gr = isset($_REQUEST['ck_gr']) ? strval($_REQUEST['ck_gr']) : '';
$ck_endorder = isset($_REQUEST['ck_endorder']) ? strval($_REQUEST['ck_endorder']) : '';
$ck_endorder_str = "";
$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';
$ck_prf = isset($_REQUEST['ck_PRF']) ? strval($_REQUEST['ck_PRF']) : '';

if ($ck_date != "true"){
    $date_po = "to_char(h.po_date,'YYYY-MM-DD') between '$date_awal' and '$date_akhir' and ";
}else{
    $date_po = "";
}   

if ($ck_gr != "true"){
    $gr = "gg.gr_no = '$gr' and ";
}else{
    $gr = "";
}

if ($ck_supplier != "true"){
    $supp = "h.supplier_code = '$supplier' and ";
}else{
    $supp = "";
}

if ($ck_prf != "true"){
    $prf = "d.prf_no = '$prf' and ";
}else{
    $prf = "";
}

if ($ck_item_no != "true"){
    $item_no = "d.item_no='$cmb_item_no' and ";
}else{
    $item_no = "";
}

if ($ck_po != "true"){
    $po = "d.po_no='$cmb_po' and ";
}else{
    $po = "";
}   

if ($ck_eta != "true"){
    $eta = "d.eta between to_date('$date_eta','yyyy-mm-dd') and to_date('$date_eta_akhir','yyyy-mm-dd') and ";
}else{
    $eta = "";
}

if ($ck_endorder != "true"){
    $ck_endorder_str = " d.bal_qty > 0 and ";
}else{
    $ck_endorder_str = "    ";
}

//$where ="where $supp $item_no $po $eta $date_po $GR $ck_endorder_str $prf d.item_no is not null";
$where ="where $date_po $supp $item_no $po $eta $ck_endorder_str $prf  $gr d.item_no is not null";

$qry = "select curr_mark,gg.gr_no,h.supplier_code,company, d.po_no, h.po_date, d.item_no, itm.description, line_no,d.eta, d.qty, d.gr_qty,gg.qty as Receipt_Qty, 
    gg.gr_Date, c.accpac_company_code, d.eta etad, gg.gr_Date grd, d.eta - gg.gr_date as diff,d.u_price,itm.standard_price,d.amt_o, d.amt_l,d.bal_qty from po_header h
    inner join po_details d on h.po_no = d.po_no
    inner join company c on h.supplier_code = c.company_code and c.company_type = 3
    inner join currency bx on h.curr_code = bx.curr_code
    left outer join (select gh.gr_no,gr_date, gs.po_no, gs.po_line_no, gs.qty from gr_details gs inner join gr_header gh on gs.gr_no = gh.gr_no) gg
    on gg.po_no = d.po_no and gg.po_line_no = d.line_no
    left join item itm on d.item_no= itm.item_no
    $where 
    order by h.po_Date,h.po_no asc, line_no,gg.gr_Date asc";

$result = oci_parse($connect, $qry);
oci_execute($result);

$items = array();
    $rowno=0;
    while($row = oci_fetch_object($result)){
        array_push($items, $row);
        // $items[$rowno]->O = number_format($items[$rowno]->AMT_O,2);
        // $items[$rowno]->L = $items[$rowno]->CURR_MARK." ".number_format($items[$rowno]->AMT_L,2);
        // $items[$rowno]->REQ_2 =strtoupper($items[$rowno]->PERSON);
           $items[$rowno]->GR_QTY = number_format($items[$rowno]->GR_QTY);
           $items[$rowno]->QTY = number_format($items[$rowno]->QTY);
           $items[$rowno]->AMT_O = number_format($items[$rowno]->AMT_O,2);
           $items[$rowno]->AMT_L = number_format($items[$rowno]->AMT_L,2);
           $items[$rowno]->U_PRICE = number_format($items[$rowno]->U_PRICE,6);
           $items[$rowno]->STANDARD_PRICE = number_format($items[$rowno]->STANDARD_PRICE,2);
           $items[$rowno]->RECEIPT_QTY = number_format($items[$rowno]->RECEIPT_QTY);
           $items[$rowno]->BAL_QTY = number_format($items[$rowno]->BAL_QTY);
           
//                 ->setCellValue('I'.$no, number_format($data->GR_QTY))
                
//                 ->setCellValue('J'.$no, number_format($data->U_PRICE))
//                 ->setCellValue('K'.$no, number_format($data->STANDARD_PRICE))
//                 ->setCellValue('L'.$no, number_format($data->AMT_O))
//                 ->setCellValue('M'.$no, number_format($data->AMT_L))

               
               
//                 ->setCellValue('N'.$no, $data->GR_NO)
//                 ->setCellValue('O'.$no, number_format($data->RECEIPT_QTY))
        $rowno++;
    }
    $result1["rows"] = $items;
    echo json_encode($result1);

// function cellColor($cells,$color){
//     global $objPHPExcel;

//     $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
//         'type' => PHPExcel_Style_Fill::FILL_SOLID,
//         'startcolor' => array(
//              'rgb' => $color
//         )
//     ));
// }

// $objPHPExcel = new PHPExcel(); 
// $sheet = $objPHPExcel->getActiveSheet();
 
// // Add Data in your file
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A1', 'NO')
//             ->setCellValue('B1', 'PO No.')
//             ->setCellValue('C1', 'PO DATE')
//             ->setCellValue('D1', 'SUPPLIER')
//             ->setCellValue('E1', 'ITEM NAME')
//             ->setCellValue('F1', 'DESCRIPTION')
//             ->setCellValue('G1', 'LINE')

//             ->setCellValue('H1', 'QTY')
//             ->setCellValue('I1', 'GR QTY')
//             ->setCellValue('J1', 'UNIT PRICE')
//             ->setCellValue('K1', 'STANDARD PRICE')
//             ->setCellValue('L1', 'AMT ORIGINAL')
//             ->setCellValue('M1', 'AMT LOCAL')

           
            
//             ->setCellValue('N1', 'GR NO')
//             ->setCellValue('O1', 'RECEIPT QTY')
//             ->setCellValue('P1', 'ETA')
//             ->setCellValue('Q1', 'GR DATE')
//             ->setCellValue('R1', 'DIFFERENCE');

// $sheet->getStyle('A1:R1')->getFont()->setBold(true)->setSize(12);
// $sheet->getStyle('A1:R1')->getAlignment()->applyFromArray(
//     array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
//     )
// );
// $sheet->getStyle('A1:R1')->applyFromArray(
//     array(
//         'fill' => array(
//             'type' => PHPExcel_Style_Fill::FILL_SOLID,
//             'color' => array('rgb' => 'B4B4B4')
//         ),
//         'borders' => array(
//             'allborders' => array(
//                 'style' => PHPExcel_Style_Border::BORDER_THIN
//             )
//         )
//     )
// );

// $arrABC = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');

// for ($i=0;$i<count($arrABC);$i++){
//     $sheet->getColumnDimension($arrABC[$i])->setAutoSize(false);
//     if($arrABC[$i]=='A'){
//         $sheet->getColumnDimension($arrABC[$i])->setWidth('4');
//     }else if($arrABC[$i]=='G'){
//         $sheet->getColumnDimension($arrABC[$i])->setWidth('5');
//     }else if($arrABC[$i]!='D' AND $arrABC[$i]!='F' AND $arrABC[$i]!='A' AND $arrABC[$i]!='G'){
//         $sheet->getColumnDimension($arrABC[$i])->setWidth('12');
//     }else{
//         $sheet->getColumnDimension($arrABC[$i])->setWidth('37');
//     }
// }


// $noUrut = 1;    
// $no=2;
// $p = '';

// while ($data=oci_fetch_object($result)) {
//     $po = $data->PO_NO;
//     $eta = $data->ETA;
//     $gr = $data->GR_DATE;

//     if($po==$p){
//         $po_fx = '';
//         $po_dt = '';
//         $po_sp = '';
//         $no_R = '';
//     }else{
//         $po_fx = $data->PO_NO;
//         $po_dt = $data->PO_DATE;
//         $po_sp = $data->SUPPLIER_CODE."-".$data->COMPANY;

//         if($noUrut==1){
//             $no_R = $noUrut;
//         }else{
//             $no_R = $noUrut;
//         }
//         $noUrut++;
//     }

//     $objPHPExcel->setActiveSheetIndex(0)
//                 ->setCellValue('A'.$no, $no_R)
//                 ->setCellValue('B'.$no, $po_fx)
//                 ->setCellValue('C'.$no, $po_dt)
//                 ->setCellValue('D'.$no, $po_sp)
//                 ->setCellValue('E'.$no, $data->ITEM_NO)
//                 ->setCellValue('F'.$no, $data->DESCRIPTION)
//                 ->setCellValue('G'.$no, $data->LINE_NO)
//                 ->setCellValue('H'.$no, number_format($data->QTY))
//                 ->setCellValue('I'.$no, number_format($data->GR_QTY))
                
//                 ->setCellValue('J'.$no, number_format($data->U_PRICE))
//                 ->setCellValue('K'.$no, number_format($data->STANDARD_PRICE))
//                 ->setCellValue('L'.$no, number_format($data->AMT_O))
//                 ->setCellValue('M'.$no, number_format($data->AMT_L))

               
               
//                 ->setCellValue('N'.$no, $data->GR_NO)
//                 ->setCellValue('O'.$no, number_format($data->RECEIPT_QTY))
//                 ->setCellValue('P'.$no, $data->ETA)
//                 ->setCellValue('Q'.$no, $data->GR_DATE)
//                 ->setCellValue('R'.$no, $data->DIFF." DAY");

//     if($data->DIFF>0){
//         $sheet->getStyle("A".$no.":R".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('004BD6');
//     }elseif($data->DIFF<0){
//         $sheet->getStyle("A".$no.":R".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('ED1C24');
//     }else{
//         $sheet->getStyle("A".$no.":R".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
//     }

//     $sheet->getStyle('A'.$no.':R'.$no)->getAlignment()->applyFromArray(
//         array(
//             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
//         )
//     );
//     /*$sheet->getStyle("A".$no.":M".$no)->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');*/
//     $sheet->getStyle("A".$no.":R".$no)->applyFromArray(
//         array(
//             'borders' => array(
//                 'allborders' => array(
//                     'style' => PHPExcel_Style_Border::BORDER_THIN
//                 )
//             )
//         )
//     );

//     $p = $po;
//     $no++;
// }   

// $objPHPExcel->getDefaultStyle()
//     ->getNumberFormat()
//     ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// // Rename worksheet
// $objPHPExcel->getActiveSheet()->setTitle('PO status - '.$tanggal);
 
// // Set active sheet index to the first sheet, so Excel opens this as the first sheet
// $objPHPExcel->setActiveSheetIndex(0);
//  // Menambahkan file gambar pada document excel pada kolom B2
// /*$objDrawing = new PHPExcel_Worksheet_Drawing();
// $objDrawing->setName('FDK');
// $objDrawing->setDescription('FDK');
// $objDrawing->setPath('../images/fdk8.png');
// $objDrawing->setWidth('100px');
// $objDrawing->setCoordinates('B2');
// $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
// // Save Excel 2007 file
// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// // We'll be outputting an excel file
// header('Content-type: application/vnd.ms-excel');

// // It will be called file.xls
// header('Content-Disposition: attachment; filename="po_sts_'.$tanggal.'.xlsx"');

// // Write file to the browser
//$objWriter->save('php://output');
?>
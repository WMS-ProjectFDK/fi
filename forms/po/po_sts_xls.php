<?php
set_time_limit(0);
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

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
$GR = isset($_REQUEST['GR']) ? strval($_REQUEST['GR']) : '';
$ck_GR = isset($_REQUEST['ck_GR']) ? strval($_REQUEST['ck_GR']) : '';
$ck_endorder = isset($_REQUEST['ck_endorder']) ? strval($_REQUEST['ck_endorder']) : '';
$ck_endorder_str = "";
$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';
$ck_prf = isset($_REQUEST['ck_prf']) ? strval($_REQUEST['ck_prf']) : '';
$ck_det = isset($_REQUEST['ck_det']) ? strval($_REQUEST['ck_det']) : '';

if ($ck_date != "true"){
    $date_po = "CAST(h.po_date as varchar(10)) between '$date_awal' and '$date_akhir' and ";
}else{
    $date_po = "";
}   

if ($ck_GR != "true"){
    $GR = "gg.gr_no = '$GR' and ";
}else{
    $GR = "";
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
    $eta = "CAST(d.eta as varchar(10)) between '$date_eta' and '$date_eta_akhir' and ";
}else{
    $eta = "";
}

if ($ck_endorder != "true"){
    $ck_endorder_str = "  d.bal_qty > 0 and  ";
}else{
    $ck_endorder_str = "  ";
}

$where ="where $supp $item_no $po $eta $date_po $GR $ck_endorder_str $prf d.item_no is not null";

$qry = "select curr_mark,gg.gr_no,h.supplier_code,company, d.po_no, CONVERT(varchar,h.po_date,113) as po_date, d.item_no, itm.description, line_no,
    CONVERT(VARCHAR,d.eta,106) AS eta, d.qty, d.gr_qty,gg.qty as Receipt_Qty, d.bal_qty, 
    CONVERT(VARCHAR,gg.gr_Date,106) gr_Date, c.accpac_company_code, CONVERT(VARCHAR,d.eta,106) as etad, 
    CONVERT(VARCHAR,gg.gr_Date,106) as grd, DATEDIFF(day,d.eta,gg.gr_date) as diff,d.u_price,itm.standard_price,d.amt_o, d.amt_l 
    from po_header h
    left join po_details d on h.po_no = d.po_no
    left join company c on h.supplier_code = c.company_code and c.company_type = 3
    left join currency bx on h.curr_code = bx.curr_code
    left outer join (select gh.gr_no,gr_date, gs.po_no, gs.po_line_no, gs.qty from gr_details gs left join gr_header gh on gs.gr_no = gh.gr_no) gg
    on gg.po_no = d.po_no and gg.po_line_no = d.line_no
    left join item itm on d.item_no= itm.item_no
    $where
    order by h.po_Date,h.po_no asc, line_no asc";
// echo $qry;
$result = sqlsrv_query($connect, strtoupper($qry));

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
            ->setCellValue('B1', 'PO No.')
            ->setCellValue('C1', 'PO DATE')
            ->setCellValue('D1', 'SUPPLIER')
            ->setCellValue('E1', 'ITEM NAME')
            ->setCellValue('F1', 'DESCRIPTION')
            ->setCellValue('G1', 'LINE')
            ->setCellValue('H1', 'QTY')
            ->setCellValue('I1', 'BAL QTY')
            ->setCellValue('J1', 'GR QTY')
            ->setCellValue('K1', 'UNIT PRICE')
            ->setCellValue('L1', 'CURRENCY')
            ->setCellValue('M1', 'STANDARD PRICE')
            ->setCellValue('N1', 'AMT ORIGINAL')
            ->setCellValue('O1', 'AMT LOCAL')
            ->setCellValue('P1', 'GR NO')
            ->setCellValue('Q1', 'RECEIPT QTY')
            ->setCellValue('R1', 'ETA')
            ->setCellValue('S1', 'GR DATE')
            ->setCellValue('T1', 'DIFFERENCE');

$sheet->getStyle('A1:T1')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A1:T1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:T1')->applyFromArray(
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

$arrABC = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T');

for ($i=0;$i<count($arrABC);$i++){
    $sheet->getColumnDimension($arrABC[$i])->setAutoSize(false);
    if($arrABC[$i]=='A'){
        $sheet->getColumnDimension($arrABC[$i])->setWidth('5');
    }else if($arrABC[$i]=='G'){
        $sheet->getColumnDimension($arrABC[$i])->setWidth('6');
    }else if($arrABC[$i]!='D' AND $arrABC[$i]!='F' AND $arrABC[$i]!='A' AND $arrABC[$i]!='G'){
        $sheet->getColumnDimension($arrABC[$i])->setWidth('14');
    }else{
        $sheet->getColumnDimension($arrABC[$i])->setWidth('40');
    }
}

$noUrut = 1;    
$no=2;
$p = '';
$l = '';

while ($data=sqlsrv_fetch_object($result)) {
    $line = $data->LINE_NO;
    $po = $data->PO_NO ;
    // $po = $po+$line;
    $eta = $data->ETA;
    $gr = $data->GR_DATE;

    if($po==$p & $line==$l){
        $po_fx = '';
        $po_dt = '';
        $po_sp = '';
        $no_R = '';
    }else{
        $po_fx = $data->PO_NO;
        $po_dt = $data->PO_DATE;
        $po_sp = $data->SUPPLIER_CODE."-".$data->COMPANY;

        if($noUrut==1){
            $no_R = $noUrut;
        }else{
            $no_R = $noUrut;
        }
        $noUrut++;
    }

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $no_R)
                ->setCellValue('B'.$no, $po_fx)
                ->setCellValue('C'.$no, $po_dt)
                ->setCellValue('D'.$no, $po_sp)
                ->setCellValue('E'.$no, $data->ITEM_NO)
                ->setCellValue('F'.$no, $data->DESCRIPTION)
                ->setCellValue('G'.$no, $data->LINE_NO)
                ->setCellValue('H'.$no, $data->QTY)
                ->setCellValue('I'.$no, $data->BAL_QTY)
                ->setCellValue('J'.$no, $data->GR_QTY)
                ->setCellValue('K'.$no, $data->U_PRICE)
                ->setCellValue('L'.$no, $data->CURR_MARK)
                ->setCellValue('M'.$no, $data->STANDARD_PRICE)
                ->setCellValue('N'.$no, $data->AMT_O)
                ->setCellValue('O'.$no, $data->AMT_L)
                ->setCellValue('P'.$no, $data->GR_NO)
                ->setCellValue('Q'.$no, $data->RECEIPT_QTY)
                ->setCellValue('R'.$no, $data->ETA)
                ->setCellValue('S'.$no, $data->GR_DATE)
                ->setCellValue('T'.$no, $data->DIFF." DAY");

    $sheet->getStyle("A".$no.":T".$no)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );

    $sheet->getStyle('G'.$no.':P'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle('R'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $l = $line;
    $p = $po;
    $no++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
$tanggal = date('Y-m-d');
$objPHPExcel->getActiveSheet()->setTitle('PO status - '.$tanggal);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="po_sts_'.$tanggal.'.xlsx"');
$objWriter->save('php://output');
?>
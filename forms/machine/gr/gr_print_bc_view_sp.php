<?php 
//error_reporting(0);
include("../../connect/conn2.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
ini_set('memory_limit', '-1');
set_time_limit(-1);

require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '999MB');

PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$date=date("d M y / H:i:s",time());
$result = array();

$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$cmb_gr_no = isset($_REQUEST['cmb_gr_no']) ? strval($_REQUEST['cmb_gr_no']) : '';
$ck_gr_no = isset($_REQUEST['ck_gr_no']) ? strval($_REQUEST['ck_gr_no']) : '';
$cmb_supp = isset($_REQUEST['cmb_supp']) ? strval($_REQUEST['cmb_supp']) : '';
$nm_supp = isset($_REQUEST['nm_supp']) ? strval($_REQUEST['nm_supp']) : '';
$ck_supp = isset($_REQUEST['ck_supp']) ? strval($_REQUEST['ck_supp']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_date != "true"){
	$gr_date = "gh.gr_date BETWEEN to_date('$date_awal', 'YYYY-MM-DD') and to_date('$date_akhir', 'YYYY-MM-DD') and ";
	$h_date = $date_awal.' TO '.$date_akhir;
}else{
	$gr_date = "";
	$h_date = "-";
}

if ($ck_gr_no != "true"){
	$gr = "gh.gr_no = '$cmb_gr_no' and ";
	$h_gr = $cmb_gr_no;
}else{
	$gr = "";
	$h_gr = "-";
}

if ($ck_supp != "true"){
	$supp = "gh.supplier_code = '$cmb_supp' and ";
	$h_supp = "[".$cmb_supp."] ".$nm_supp;
}else{
	$supp = "";
	$h_supp = "-";
}

if ($ck_po != "true"){
	$po = "gh.gr_no in (select distinct gr_no from gr_details where po_no='$cmb_po') and ";
	$h_po = $cmb_po;
}else{
	$po = "";
	$h_po = "-";
}

if ($ck_item != "true"){
	$item = "gh.gr_no in (select distinct gr_no from gr_details where item_no=$cmb_item) and ";
	$h_item = $cmb_item;
}else{
	$item = "";
	$h_item ="-";
}

$where ="where $gr_date $gr $supp $po $item gh.gr_no is not null";

$sql = "select gd.item_no,gd.qty,(select unit from unit where unit_code = gd.uom_q) unit,i.description,
nvl(gd.u_price,(select  max(po_details.u_price) from po_details where po_no = gd.po_no and item_no = gd.item_no)) u_price
		,(qty * nvl(gd.u_price,(select  max(po_details.u_price) from po_details where po_no = gd.po_no and item_no = gd.item_no))) amt_o
		,(qty * nvl(gd.u_price,(select  max(po_details.u_price) from po_details where po_no = gd.po_no and item_no = gd.item_no))
      * nvl(gh.ex_rate,(select po_header.ex_rate from po_header where po_no = gd.po_no))) amt_l
		,nvl(gh.ex_rate,(select po_header.ex_rate from po_header where po_no = gd.po_no)) ex_rate
    ,gh.GR_NO,to_char(gh.GR_DATE,'DD/MM/YY') GR_DATE,to_char(gh.INV_DATE,'DD/MM/YY') INV_DATE,gh.SUPPLIER_CODE,gh.INV_NO,gh.CURR_CODE,gh.EX_RATE,gh.DO_NO,gh.BC_NO,c.COMPANY SUPPLIER,cc.CURR_MARK,gh.TAX_INV_NO,gh.BC_DOC
	FROM GR_HEADER gh
	inner join gr_details gd on gh.gr_no = gd.gr_no
	inner join item  i on i.item_no = gd.item_no 
	left join company c on gh.SUPPLIER_CODE = c.COMPANY_CODE
	left join currency cc on gh.CURR_CODE = cc.CURR_CODE
	$where
	order by gh.gr_date,gh.SUPPLIER_CODE,gh.BC_NO,Ltrim(gh.GR_NO)";
$data = oci_parse($connect, $sql);
oci_execute($data);



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


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'BC NO')
            ->setCellValue('B2', 'TAX INVOICE NO')
            ->setCellValue('C2', 'BC DOC.')
            ->setCellValue('D2', 'DELIVERY SLIP NO.')
            ->setCellValue('E2', 'GR DATE')
            ->setCellValue('F2', 'SUPPLIER')
            ->setCellValue('G2', 'INVOICE NO')
            ->setCellValue('H2', 'INVOICE DATE')
			->setCellValue('I2', 'CURRENCY')
			->setCellValue('J2', 'ITEM NO')
			->setCellValue('K2', 'DESCRIPTION')
            ->setCellValue('L2', 'QUANTITY')
            ->setCellValue('M2', 'UOM')
			->setCellValue('N2', 'PRICE')
			->setCellValue('O2', 'AMOUNT ORIGINAL')
            ->setCellValue('P2', 'AMOUNT LOCAL');



$sheet->getStyle('A2:P2')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A2:P2')->applyFromArray(
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
$no=3;

while ($dt=oci_fetch_object($data)){
	

		$objPHPExcel->setActiveSheetIndex(0)        
		->setCellValue('A'.$no, $dt->BC_NO)
		->setCellValue('B'.$no, $dt->TAX_INV_NO)
		->setCellValue('C'.$no, "'".$dt->BC_DOC)
		->setCellValue('D'.$no, $dt->GR_NO)
		->setCellValue('E'.$no, $dt->GR_DATE)
		->setCellValue('F'.$no, "[".$dt->SUPPLIER_CODE."] ".$dt->SUPPLIER."")
		->setCellValue('G'.$no, $dt->INV_NO)
		->setCellValue('H'.$no, $dt->INV_DATE)
		->setCellValue('I'.$no, $dt->CURR_MARK)
		->setCellValue('J'.$no, $dt->ITEM_NO)
		->setCellValue('K'.$no, $dt->DESCRIPTION)
		->setCellValue('L'.$no, number_format($dt->QTY))
		->setCellValue('M'.$no, $dt->UNIT)
		->setCellValue('N'.$no, number_format($dt->U_PRICE))
		->setCellValue('O'.$no, number_format($dt->AMT_O))
		->setCellValue('P'.$no, number_format($dt->AMT_L));

		$no++;
}



$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);
$objPHPExcel->getDefaultStyle()
    ->getNumberFormat();
    // ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->getActiveSheet()->setTitle('GR BC SPAREPARTS LIST '.$dt);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="GR BC SPARTS '.$dt.'.csv"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
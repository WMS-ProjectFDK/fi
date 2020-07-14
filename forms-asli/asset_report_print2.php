<?php
include("../connect/conn_accpac.php");
$sql = "select distinct ASTNO from zvw_ast_detail where 1=1 order by ASTNO asc";
$rs=odbc_exec($con,$sql);

require_once('../plugins/TCPDF-master/config/tcpdf_config.php');
$tcpdf_include_dirs = array(
	realpath('../plugins/TCPDF-master/tcpdf.php'),
	'/usr/share/php/tcpdf/tcpdf.php',
	'/usr/share/tcpdf/tcpdf.php',
	'/usr/share/php-tcpdf/tcpdf.php',
	'/var/www/tcpdf/tcpdf.php',
	'/var/www/html/tcpdf/tcpdf.php',
	'/usr/local/apache2/htdocs/tcpdf/tcpdf.php'
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// add a page
$pdf->AddPage('L','A4');

// -----------------------------------------------------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(1, 1, 1, 1);
$pdf->SetFillColor(255, 255, 127);

// define barcode style
$style = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => true,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);
$items = array();
$i=1;
$txt = "ASSET ID: <br>";
while($row = odbc_fetch_object($rs) ) { 
	$txt .= $pdf->write1DBarcode($row->ASTNO, 'C39', '', '', '', 18, 0.4, $style, 'N');
	$pdf->Ln(2);
	$i++;
}

$pdf->lastPage();
//Close and output PDF document
$pdf->Output('asset_report.pdf', 'I');
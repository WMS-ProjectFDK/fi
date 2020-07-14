<?php 
//error_reporting(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
ini_set('memory_limit', '-1');
set_time_limit(-1);
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

$sql = "select gh.GR_NO,to_char(gh.GR_DATE,'DD/MM/YY') GR_DATE,to_char(gh.INV_DATE,'DD/MM/YY') INV_DATE,gh.SUPPLIER_CODE,gh.INV_NO,gh.CURR_CODE,gh.EX_RATE,gh.DO_NO,gh.BC_NO,gh.SHIP,c.COMPANY SUPPLIER,cc.CURR_MARK,gh.TAX_INV_NO,gh.BC_DOC
	FROM GR_HEADER gh
	left join company c on gh.SUPPLIER_CODE = c.COMPANY_CODE
	left join currency cc on gh.CURR_CODE = cc.CURR_CODE
	$where
	order by gh.gr_date,gh.SUPPLIER_CODE,gh.BC_NO,Ltrim(gh.GR_NO)";
$data = oci_parse($connect, $sql);
oci_execute($data);

$content = "	
	<style>
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:11px;
		}
		table, th, td {
			border: 1px solid #d0d0d0;	
		}
		th {
			//background-color: #4bd2fe;
			color: black;
		}
		.brd {
			border:none;
		}
	</style>
	<page>
		<div style='position:absolute;margin-top:0px;'>
			<img src='../images/logo-print4.png' alt='#' style='width:270px;height: 35px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:940px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		<h3 align='center'>BC No. VIEW<br></h3>
		<table>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;'>GR Date</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$h_date."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>GR NO.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$h_gr."</td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;width: 50px;'>PO No.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$h_po."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>Item No.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$h_item."</td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;width: 50px;'>Supplier</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$h_supp."</td>
			</tr>
		</table>
		<table align='center'>
		<thead>
			<tr>
				<th valign='middle' align='center' style='font-size:11px;width:60px;height:20px;'>BC NO.</th>
				<th valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>TAX INVOICE No.</th>
				<th valign='middle' align='center' style='font-size:11px;width:55px;height:20px;'>BC DOC</th>
				<th valign='middle' align='center' style='font-size:11px;width:120px;height:20px;'>DELIVERY SLIP No.</th>
				<th valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>GR DATE</th>
				<th valign='middle' align='center' style='font-size:11px;width:300px;height:20px;'>SUPPLIER</th>
				<th valign='middle' align='center' style='font-size:11px;width:120px;height:20px;'>INVOICE<br/>NO.</th>
				<th valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>INVOICE<br/>DATE</th>
				<th valign='middle' align='center' style='font-size:11px;width:60px;height:20px;'>CURRENCY</th>
				<th valign='middle' align='center' style='font-size:11px;width:60px;height:20px;'>SHIP</th>
			</tr>
		</thead>";

while ($dt=oci_fetch_object($data)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:9px;width:60px;'>".$dt->BC_NO."</td>
			<td valign='middle' align='left' style='font-size:9px;width:70px;'>".$dt->TAX_INV_NO."</td>
			<td valign='middle' align='center' style='font-size:9px;width:55px;'>".$dt->BC_DOC."</td>
			<td valign='middle' align='center' style='font-size:9px;width:120px;'>".$dt->GR_NO."</td>
			<td valign='middle' align='center' style='font-size:9px;width:70px;'>".$dt->GR_DATE."</td>
			<td valign='middle' align='left' style='font-size:9px;width:300px;'>[".$dt->SUPPLIER_CODE."] ".$dt->SUPPLIER."</td>
			<td valign='middle' align='center' style='font-size:9px;width:120px;'>".$dt->INV_NO."</td>
			<td valign='middle' align='center' style='font-size:9px;width:70px;'>".$dt->INV_DATE."</td>
			<td valign='middle' align='center' style='font-size:9px;width:60px;'>".$dt->CURR_MARK."</td>
			<td valign='middle' align='left' style='font-size:9px;width:60px;'>".$dt->SHIP."</td>	
		</tr>";
}

$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('breakdown_'.$ppbe.'.pdf');
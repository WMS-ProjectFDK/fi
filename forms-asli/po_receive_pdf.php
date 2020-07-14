<?php 
error_reporting(0);
ini_set('memory_limit', '-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$date_awal=isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir=isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date=isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';

$cmb_gr=isset($_REQUEST['cmb_gr']) ? strval($_REQUEST['cmb_gr']) : '';
$ck_gr=isset($_REQUEST['ck_gr']) ? strval($_REQUEST['ck_gr']) : '';

$cmb_po=isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po=isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';

$supplier=isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
$supplier_nm=isset($_REQUEST['supplier_nm']) ? strval($_REQUEST['supplier_nm']) : '';
$ck_supplier=isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';

$cmb_item_no=isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no=isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$txt_item_name=isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';


if($ck_date!='true'){
	$dt = "b.gr_date between to_date('$date_awal','yyyy-mm-dd') AND to_date('$date_akhir','yyyy-mm-dd') AND ";
}else{
	$dt = "";
}

if($ck_gr!='true'){
	$gr = "b.gr_no = '$cmb_gr' AND ";
}else{
	$gr = "";
}

if($ck_po!='true'){
	$po = "a.po_no = '$cmb_po' AND ";
}else{
	$po = "";
}

if($ck_supplier!='true'){
	$supp = "b.supplier_code = '$supplier' AND ";
}else{
	$supp = "";
}

if($ck_item_no!='true'){
	$itm = "a.item_no = '$cmb_item_no' AND ";
}else{
	$itm = "";
}

$where = "where $dt $gr $po $supp $itm b.gr_no is not null "; 

$result = array();

$qry = "select b.gr_no, b.gr_date,a.line_no,b.supplier_code,d.company,a.item_no, c.item,c.description,a.po_no, a.po_line_no, a.qty, e.unit, e.unit_pl
	from gr_details a 
	inner join gr_header b on a.gr_no=b.gr_no
	left join item c on a.item_no=c.item_no
	left join company d on b.supplier_code=d.company_code
	left join unit e on a.uom_q=e.unit_code
	$where
	order by b.gr_date desc, b.gr_no desc, a.line_no asc";
//echo $qry;
$result = oci_parse($connect, $qry);
oci_execute($result);

$date=date("d M y / H:i:s",time());
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
			<img src='../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:930px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
			page [[page_cu]] of [[page_nb]]
		</div>
    </page_footer>
	<br/>
	<div style='margin-top:20px;margin-bottom:100%;position:absolute;'>
		<h3 align='center'>PURCHASE RECEIVE REPORT<br></h3>
		<table align='center'>
			<thead>
				<tr>
					<th valign='middle' align='center' style='font-size:12px;width:30px;height:35px;'>NO</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>RECEIVE<br/>NO.</th>
					<th valign='middle' align='center' style='font-size:12px;width:80px;height:35px;'>RECEIVE<br/>DATE</th>
					<th valign='middle' align='center' style='font-size:12px;width:250px;height:35px;'>SUPPLIER</th>
					<th valign='middle' align='center' style='font-size:12px;width:250px;height:35px;'>ITEM</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>PURCHASE<br/>NO.</th>
					<th valign='middle' align='center' style='font-size:12px;width:80px;height:35px;'>PURCHASE<br/>LINE</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>QTY</th>
				</tr>
			</thead>";

		$nourut = 1;
		$total=0;		$grn='';
		while ($data=oci_fetch_object($result)){
			$j = "select count(gr_no) as JML from gr_details where gr_no='".$data->GR_NO."' ";
			$result_j = oci_parse($connect, $j);
			oci_execute($result_j);
			$data_jum=oci_fetch_object($result_j);

			if($grn!=$data->GR_NO){
				$content .= "
					<tr>
						<td rowspan='".$data_jum->JML."' valign='middle' align='center' style='font-size:11px;height:25px;'>".$nourut."</td>
						<td rowspan='".$data_jum->JML."' valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->GR_NO."</td>
						<td rowspan='".$data_jum->JML."' valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->GR_DATE."</td>
						<td rowspan='".$data_jum->JML."' valign='middle' align='left' style='font-size:11px;height:25px;'>".wordwrap($data->COMPANY, 35, '<br />', true)."</td>
						<td valign='middle' align='left' style='font-size:11px;height:25px;'>".wordwrap($data->DESCRIPTION,35)."<br/>[".$data->ITEM_NO."] - ".$data->ITEM."</td>
						<td valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->PO_NO."&nbsp;</td>
						<td valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->PO_LINE_NO."</td>
						<td valign='middle' align='right' style='font-size:11px;height:25px;'>".number_format($data->QTY)." ".$data->UNIT_PL."</td>
					</tr>";
				$nourut++;
			}else{
				$content .= "
					<tr>
						<td valign='middle' align='left' style='font-size:11px;height:25px;'>".wordwrap($data->DESCRIPTION,35)."<br/>[".$data->ITEM_NO."] - ".$data->ITEM."</td>
						<td valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->PO_NO."&nbsp;</td>
						<td valign='middle' align='center' style='font-size:11px;height:25px;'>".$data->PO_LINE_NO."</td>
						<td valign='middle' align='right' style='font-size:11px;height:25px;'>".number_format($data->QTY)." ".$data->UNIT_PL."</td>
					</tr>";
			}
			$grn = $data->GR_NO;
		}

$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('PO_RECEIVE.pdf');
//echo  $content;
?>
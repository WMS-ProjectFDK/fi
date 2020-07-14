<?php 
error_reporting(0);
include("../connect/conn.php");
session_start();
	date_default_timezone_set('Asia/Jakarta');
	$user_name = $_SESSION['id_wms'];
	$nama_user = $_SESSION['name_wms'];

	$result = array();

	$i = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$s = isset($_REQUEST['s']) ? intval($_REQUEST['s']) : '';

	if($s==1){
		$sql = "select max(id) from ztb_wh_in_det";
		$data = oci_parse($connect, $sql);
		oci_execute($data);
		$row = oci_fetch_array($data);
		$id = $row[0];
	}else{
		$id=$i;
	}
	
	$upd = "update ztb_wh_in_det set print=1  where id='$id'";
	$updNya = oci_parse($connect, $upd);
  	oci_execute($updNya);

	$sql = "select a.item_no, b.description, b.class_code, a.qty, e.unit_pl, a.pallet, d.company, c.gr_date, a.item_no||'('||b.description||'),'||a.qty||','||a.pallet||','||a.id as qr, b.stock_subject_code from ztb_wh_in_det a  left join item b on a.item_no=b.item_no
	left join gr_header c on a.gr_no=c.gr_no left join company d on c.supplier_code=d.company_code and d.company_type=3
	left join unit e on b.uom_q=e.unit_code where a.id='$id' order by a.pallet asc";
	$result = oci_parse($connect, $sql);
  	oci_execute($result);

	$date=date("d M y / H:i:s",time());

$content = "	
	<style> 
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:14px;
			height:10px;
		}
		table, th, td {
			border-left:1px solid #ffffff; 
			border-right:1px solid #ffffff; 
			border-bottom:1px solid #ffffff; 
			border-top:1px solid #ffffff;
		}
		th {
			//background-color: #4bd2fe;
			color: black;
		}
		.brd {
			border:none;
		}
	</style>
	<page format='110x110' orientation='L' backtop='1mm' backleft='0mm' backright='1mm'>
	<page_footer>
		<div style='font-size:9px;width:98%;margin-bottom:100%;'>
			<div style='text-align:right;'>print by: ".$nama_user."</div>
		</div>
    </page_footer>
    ";

$typeArr = array(
	"WOODEN PALLET",
	"RAW MATERIALS",
	"PACKING MATERIALS",
	"WORK IN PROCESS",
	"SEMI FINISHED GOODS",
	"FINISHED GOODS",
	"-",
	"MATERIALS 2",
	"LR6 MANAGEMENT",
	"LR03 MANAGEMENT"
	);

$cls_item = array('111000' => 'LR',
				  '111311' => 'LR03',
				  '111312' => 'LR03(P.P)',
				  '111314' => 'LR03COMMON',
				  '111313' => 'LR03N',
				  '111111' => 'LR1',
				  '111411' => 'LR14',
				  '111511' => 'LR20',
				  '111211' => 'LR6',
				  '111212' => 'LR6(P.P)',
				  '111214' => 'LR6COMMON',
				  '111911' => 'LRCOMMON',
				  '' => ''
);

while ($data=oci_fetch_array($result)){
$content .= "
    <div style='margin-top:5px;'><h4 align='center'>INCOMING MATERIAL</h4></div>
	<div style:position:absolute;'>
		<table>
			<tr>
				<td style='width:110px;'>ITEM NO.</td>
				<td>:</td>
				<td style='width:242px;'>".$data[0]."</td>
			</tr>
			<tr>
				<td>ITEM NAME</td>
				<td>:</td>
				<td><div style='width: 242px;word-wrap: break-word;''>".$data[1]."</div></td>
			</tr>
			<tr>
				<td>TYPE</td>
				<td>:</td>
				<td>".$cls_item[$data[2]]."</td>
			</tr>
			<tr>
				<td>QUANTITY</td>
				<td>:</td>
				<td>".number_format($data[3]).' '.$data[4]."</td>
			</tr>
			<tr>
				<td>PALLET</td>
				<td>:</td>
				<td>".$data[5]."</td>
			</tr>
			<tr>
				<td>VENDOR</td>
				<td>:</td>
				<td><div style='width: 242px;word-wrap: break-word;''>".$data[6]."</div></td>
			</tr>
			<tr>
				<td>INCOMING DATE</td>
				<td>:</td>
				<td>".$data[7]."</td>
			</tr>
			<tr>
				<td align='left' valign='bottom'><img src='../images/fdk8.png' alt='#' style='width:110px;height:75px'/></td>
				<td></td>
				<td align='right'><qrcode value='".$data[8].','.$user_name."' ec='H' style=' border:none; width:131px;background-color: white; color: black;'></qrcode></td>
			</tr>
		</table>
	</div> ";
}
$content .= "			
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('incoming.pdf');	
?>
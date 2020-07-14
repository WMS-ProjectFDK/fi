<?php 
error_reporting(0);
ini_set('memory_limit','999M');
include("../connect/conn.php");
session_start();
	date_default_timezone_set('Asia/Jakarta');
	$user_name = $_SESSION['id_wms'];
	$nama_user = $_SESSION['name_wms'];


	$result = array();

	$grn = isset($_REQUEST['grn']) ? strval($_REQUEST['grn']) : '';
	$item = isset($_REQUEST['item']) ? strval($_REQUEST['item']) : '';
	$receive = isset($_REQUEST['receive']) ? strval($_REQUEST['receive']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	
	$upd = "update ztb_wh_in_det set print=1  where gr_no='$grn' and item_no='$item' and line_no='$line' ";
	$updNya = oci_parse($connect, $upd);
  	oci_execute($updNya);

	$sql = "select a.item_no, b.description, b.class_code, a.qty, e.unit_pl, a.pallet, d.company, c.gr_date, 
	a.item_no||'('||b.description||'),'||a.qty||','||a.pallet||','||a.id||','||to_char(c.gr_date,'yyyy-mm-dd') as qr, b.stock_subject_code 
	from ztb_wh_in_det a  left join item b on a.item_no=b.item_no
	left join gr_header c on a.gr_no=c.gr_no left join company d on c.supplier_code=d.company_code and d.company_type=3
	left join unit e on b.uom_q=e.unit_code where a.gr_no='$grn' and a.item_no='$item' and line_no='$line' order by a.pallet asc";
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
			border-left:0px solid #ffffff; 
			border-right:0px solid #ffffff; 
			border-bottom:0px solid #ffffff; 
			border-top:0px solid #ffffff;
		}
		th {
			//background-color: #4bd2fe;
			color: black;
		}
		.brd {
			border:none;
		}
	</style>
	<page format='110x110' orientation='P' backtop='1mm' backleft='0mm' backright='1mm'>
	<page_footer>
		<div style='font-size:9px;width:98%;margin-bottom:100%;'>
			<div style='text-align:right;'>print by: ".$receive."</div>
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
				<td style='width:110px;'>ITEM NAME</td>
				<td>:</td>
				<td style='width:242px;'><div style='width: 242px;word-wrap: break-word;''>".mb_substr($data[1], 0,25)."</div></td>
			</tr>
			<tr>
				<td style='width:110px;'>TYPE</td>
				<td>:</td>
				<td style='width:242px;'>".$cls_item[$data[2]]."</td>
			</tr>
			<tr>
				<td style='width:110px;'>QUANTITY</td>
				<td>:</td>
				<td style='width:242px;'>".number_format($data[3]).' '.$data[4]."</td>
			</tr>
			<tr>
				<td style='width:110px;'>VENDOR</td>
				<td>:</td>
				<td style='width:242px;'><div style='width: 242px;word-wrap: break-word;''>".substr($data[6],0,25)."</div></td>
			</tr>
			<tr>
				<td style='width:110px;'>INCOMING</td>
				<td>:</td>
				<td rowspan=2 style='font-size:32px;'><b><i>".$data[7]."</i></b></td>
			</tr>
			<tr>
				<td style='width:110px;'></td>
				<td></td>
			</tr>
			<tr>
				<td>PALLET :<br/><div align='right' style='font-size:57px; width: 110px;'><b>".$data[5]."</b></div></td>
				<td></td>
				<td><qrcode value='".$data[8].','.$user_name."' ec='L' style=' border:none; width:131px;background-color: white; color: black;'></qrcode></td>
			</tr>
		</table>
	</div> ";
}
$content .= "			
</page>";
/*<img src='../images/fdk8.png' alt='#' style='width:110px;height:75px'/>*/
require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('incoming.pdf');	
?>
<?php 
//error_reporting(0);
<<<<<<< HEAD
include("../../connect/conn.php");
=======
include("../connect/conn.php");
>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$prf = isset($_REQUEST['prf']) ? strval($_REQUEST['prf']) : '';

$sql_h = "select a.*, (select count(*) from prf_details where prf_no=a.prf_no) as jum_dtl, rtrim(replace(a.remark,chr(10),'<br/>'),'|') as remark1 
	from prf_header a where a.prf_no='$prf' ";
$head = sqlsrv_query($connect, $sql_h);
<<<<<<< HEAD
=======

>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
$dt_h = sqlsrv_fetch_object($head);

if($dt_h->JUM_DTL<=10 OR $dt_h->JUM_DTL>=20){
	$plus_ln = "";
}else{
	$plus_ln = "<tr>
					<td colspan=9 align='left' style='border:5px solid #ffffff;height: 170px;'></td>
				</tr>";
}

$result = array();
<<<<<<< HEAD
$qry = "select a.*, b.item, b.description, c.unit_pl 
	from prf_details a 
	left join item b on a.item_no=b.item_no
	left join unit c on a.uom_q=c.unit_code
=======
$qry = "select a.*, b.item, b.description, c.unit_pl from prf_details a 
	inner join item b on a.item_no=b.item_no
	inner join unit c on a.uom_q=c.unit_code
>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
	where a.prf_no='$prf'
	order by a.line_no asc";
$result = sqlsrv_query($connect, $qry);

<<<<<<< HEAD
=======

>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
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
			<img src='../images/logo-print4.png' alt='#' style='width:350px;height: 50px'/>
		</div>	

		<div style='margin-top:0;margin-left:950px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>
	<page_footer>
		<div style='width:100%; text-align:right; margin-bottom:100%; font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin: 20px 5px 100px 0px;position:absolute;width:100%; padding-bottom:25px;'>
		<h3 align='center'>PURCHASE REQUISITION FORM (PRF)<br></h3>
		<div>
			<table>
				<tr>
					<td style='width:10px; font-size:9px; border-left:15px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'></td>
					<td style='width:100px; font-size:12px; border-left:15px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'><b>REQUISITION No.</b></td>
					<td style='width:450px; font-size:12px; border-left:0px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>: ".$dt_h->PRF_NO."</td>
				</tr>
			</table>
		</div>
		<table align='center' style='margin: 0px; padding-bottom:50px;'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:12px;width:30px;height:35px;'>NO</th>
			<th valign='middle' align='center' style='font-size:12px;width:80px;height:35px;'>ITEM NO</th>
			<th valign='middle' align='center' style='font-size:12px;width:270px;height:35px;'>ITEM DESCRIPTION</th>
			<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>QTY</th>
			<th valign='middle' align='center' style='font-size:12px;width:60px;height:35px;'>UNIT</th>
			<th valign='middle' align='center' style='font-size:12px;width:120px;height:35px;'>ESTIMATE PRICE<br/>US$</th>
			<th valign='middle' align='center' style='font-size:12px;width:120px;height:35px;'>REQUIRED<br/>INCOMING DATE</th>
			<th valign='middle' align='center' style='font-size:12px;width:120px;height:35px;'>ESTIMATED<br/>INCOMING DATE *</th>
			<th valign='middle' align='center' style='font-size:12px;width:100px;height:35px;'>OHSAS (K3)<br/>ELEMENTS</th>
		</tr>
	</thead>";
$total=0;
<<<<<<< HEAD
while ($data=sqlsrv_fetch_object($result)){
=======
while ($data=oci_fetch_object($result)){
>>>>>>> 77172d8c738f23e29278a5ce17a9606a9260d23e
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data->LINE_NO."</td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data->ITEM_NO."</td>
			<td valign='middle' align='left' style='font-size:12px;height:25px;'>&nbsp;".$data->ITEM."<br/>&nbsp;".$data->DESCRIPTION."</td>
			<td valign='middle' align='right' style='font-size:12px;height:25px;'>".number_format($data->QTY)."&nbsp;</td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data->UNIT_PL."</td>
			<td valign='middle' align='right' style='font-size:12px;height:25px;'>".number_format($data->ESTIMATE_PRICE,2)."&nbsp;</td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data->REQUIRE_DATE."</td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'></td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data->OHSAS."</td>
		</tr>";
	$total += $data->AMT_O;
	if($nourut % 10==0){
		$content .= $plus_ln;
	}
	$nourut++;
}

$content .= "
			<tr>
				<td colspan=9 valign='top' align='left' style='font-size:12px;height:35px;'><b><i>&nbsp;NOTE :</i></b> ".$dt_h->REMARK1."&nbsp;</td>
			</tr>
		</table>
	
		<div style='width:100%; margin-bottom:90%; font-size:12px;' align='left'>
			<table id='brd'>
				<tr>
					<td valign='top' align='center' style='width:363px;height:100px;border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>Accepted By : </td>
					<td valign='top' align='center' style='width:363px;height:100px;border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>Approved By : </td>
					<td valign='top' align='center' style='width:363px;height:100px;border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>Required By : </td>
				</tr>
				<tr>
					<td align='center' style='border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>(Purchasing & Export-Import Manager)</td>
					<td align='center' style='border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>Director/Senior Manager</td>
					<td align='center' style='border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'>Department Manager</td>
				</tr>
			</table><br/>
			<div style='width:100%;text-align:left;margin-bottom:100%;font-size:9px;'>* Filled by : Purchasing & Exim Dept.
				<p style='font-size:12px;'>FM-06-PEI-001<br/>REVISI:2</p>
			</div>
		</div>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('PO-'.$po.'.pdf');
//echo  $content;
?>
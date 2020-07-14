<?php 
//error_reporting(0);
include("../connect/conn2.php");
session_start();
	date_default_timezone_set('Asia/Jakarta');
	$user_name = $_SESSION['id_wms'];
	$nama_user = $_SESSION['name_wms'];

	$result = array();

	$req_no = isset($_REQUEST['reqno']) ? strval($_REQUEST['reqno']) : '';
	$req_date = isset($_REQUEST['reqdate']) ? strval($_REQUEST['reqdate']) : '';

	$sql = "select a.item_no, b.description, a.qty, c.unit, a.price, d.req_date, d.remarks from ztb_prf_req_details a left join item b on a.item_no=b.item_no 
		left join unit c on a.unit_code = c.unit_code left join ztb_prf_req_header d on a.req_no=d.req_no where a.req_no='$req_no' order by a.id asc";
	$result = oci_parse($connect, $sql);
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
			<img src='../images/logo-print4.png' alt='#' style='width:250px;height: 50px;'/>
		</div>	

		<div style='margin-top:0;margin-left:600px;font-size:8px;'>
			<p align='' >DEPARTEMENT : COMPONENT</p>		
		</div>

	<page_footer>
		<div style='width:100%;margin-bottom:100%;font-size:9px;'>
			<table style='width:100%;' align='center'>
				<tr>
					<td style='width:40%; border:5px solid #ffffff;'>Accepted by :</td>
					<td style='width:35%; border:5px solid #ffffff;'>Approved by :</td>
					<td style='width:35%; border:5px solid #ffffff;'>Required by :</td>
				</tr>
				<tr>
					<td valign='bottom' style='width:100px;height:50px;border:5px solid #ffffff;'>(Purchasing & Exim Manager)</td>
					<td valign='bottom' style='width:100px;height:50px;border:5px solid #ffffff;'>(Director/Senior Manager)</td>
					<td valign='bottom' style='width:100px;height:50px;border:5px solid #ffffff;'>(Department Manager)</td>
				</tr>
			</table>
		</div>
		<p style='font-size:7px;'>* Filled by : Purchasing & Exim Dept.</p>
		<div style='text-align:right;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<h5 align='center'>PURCHASE REQUISITION FORM (PRF)<br></h5>
	
	<div>
		<table align='center' style='width:97%;'>
			<tr>
				<td align='left' style='width:50%;font-size:9px; border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'><b>REQUISITION No. : </b>".$req_no."</td>
				<td align='right' style='width:50%;font-size:9px; border-left:5px solid #ffffff; border-right:0px solid #ffffff; border-bottom:0px solid #ffffff; border-top:0px solid #ffffff;'><b>REQUISITION DATE : </b>".$req_date."</td>
			</tr>
		</table>
	</div>

	<div style='margin-top:80px;position:absolute;'>
		<table align='center' style='width:100%;height:300px;'>";
$nourut = 1;
$content .= "
		<thead>
			<tr>
    			<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
    			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>ITEM NO.</th>
    			<th valign='middle' align='center' style='font-size:10px;width:200px;height:25px;'>Material Name</th>
    			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>QTY</th>
    			<th valign='middle' align='center' style='font-size:10px;width:35px;height:25px;'>UoM</th>
    			<th valign='middle' align='center' style='font-size:10px;width:80px;height:25px;'>Estimation<br>Price US$</th>
    			<th valign='middle' align='center' style='font-size:10px;width:75px;height:25px;'>Required<br>Incoming Date</th>
    			<th valign='middle' align='center' style='font-size:10px;width:75px;height:25px;'>Estimated<br>Incoming Date</th>
    			<th valign='middle' align='center' style='font-size:10px;width:75px;height:25px;'>OHSAS (K3)<br>Elements</th>
   			</tr>
   		</thead>";

while ($data=oci_fetch_array($result)){
	$content .= "
			<tr>
				<td valign='middle' align='center' style='font-size:9px;'>".$nourut."</td>
				<td valign='middle' align='left' style='font-size:9px;'>".$data[0]."</td>
				<td valign='middle' align='left' style='font-size:9px;'>".$data[1]."</td>
				<td valign='middle' align='right' style='font-size:9px;'>".number_format($data[2],2)."</td>
				<td valign='middle' align='center' style='font-size:9px;'>".$data[3]."</td>
				<td valign='middle' align='right' style='font-size:9px;'>".number_format($data[4],1)."</td>
				<td valign='middle' align='center' style='font-size:9px;'></td>
				<td valign='middle' align='center' style='font-size:9px;'></td>
				<td valign='middle' align='center' style='font-size:9px;'></td>
			</tr>";
	$rmk = $data[6];
	$nourut++;
}
$content .= "
			<tr>
				<td colspan='9' style='font-size:10px;height:100px;'>Note :<br/>".$rmk."</td>
			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A5','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('prf_req-'.$req_no.'.pdf');	
//echo  $content;
?>
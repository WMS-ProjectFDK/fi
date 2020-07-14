<?php 
//error_reporting(0);
include("../connect/conn2.php");
session_start();
	date_default_timezone_set('Asia/Jakarta');
	$user_name = $_SESSION['id_wms'];
	$nama_user = $_SESSION['name_wms'];

	$result = array();

	$quotation = isset($_REQUEST['quotation']) ? strval($_REQUEST['quotation']) : '';

	$sql = "";
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
	<page_footer>
		<div style='text-align:right;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<h5 align='center'>Request For Approval<br></h5>

	<div style='margin-top:50px;position:absolute;'>
		<table align='center' style='width:100%;height:300px;'>";
$nourut = 1;
$content .= "
			<tr>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Approval No.</td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Approval Date</td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Request Date</td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Request Dept.</td>
    			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Type of Request (Tick one)</td>
	   			<td colspan=3 valign='middle' style='font-size:10px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Subject</td>
	   			<td colspan=3 valign='middle' style='font-size:10px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:300px;'>Outline of Request<br>including Purpose of Request<br><i>(Please describe clearly the schedule, the benefit and investment return period if any)</i></td>
	   			<td colspan=3 valign='middle' style='font-size:10px;width:178px;height:200px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Request Amount</td>
	   			<td valign='middle' style='font-size:10px;height:25px;'></td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Exchange Rate(if Any)</td>
	   			<td colspan=3 valign='middle' style='font-size:10px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Quarterly Budget amount</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Quarterly Budget Consumed</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Queaterly Budget Remaining</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
	   			<td colspan=2 valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Name of Supplier/Vendor/Agent</td>
	   			<td colspan=3 valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Contact of Supplier/Vendor/Agent</td>
	   			<td colspan=3 valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Trade Term</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Paymnet Term</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Goods/Service Receipt Date</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Paymnet Date</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Attachments</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>1) Purchasae Requisition Form</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>2) Quotation</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(A.sohara)<br>President Director</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Djatmiko Tanuwidjojo)<br>Board of Director</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Suzuki)<br>Executive Senior Manager</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(M. Ema)<br>Senior Manager</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
   			</tr>
   			<tr>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Victor Antonio)<br>Finance & Accounting Manager</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Agusman Surya)<br>Senior Manager</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Prihartanto)<br>Senior Manager</td>
	   			<td valign='bottom' align='center' style='font-size:10px;width:178px;height:100px;'>(Antonius MJP)<br>Requesting Manager</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
	   			<td valign='middle' style='font-size:10px;width:178px;height:25px;'>Date:</td>
   			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('RFA-'.$quotation.'.pdf');	
//echo  $content;
?>
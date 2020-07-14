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
	$dt = date("M d").','.date("Y");
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
			<img src='../images/logo-print4.png' alt='#' style='width:330px;height: 50px;'/>
		</div>
	<page_footer>
		<div style='text-align:right;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<h4 align='center'>REQUEST FOR APPROVAL<br></h4>

	<div style='margin-top:50px;position:absolute;'>
		<table align='center' style='width:100%;height:300px;'>";
$nourut = 1;
$content .= "
			<tr>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Approval No.</td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Approval Date</td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Request Date</td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'> 02/06/2019</td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Request Department</td>
    			<td valign='middle' style='font-size:12px;width:178px;height:25px;'> SCM (IT Section)</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Type of Request </td>
	   			<td colspan=3 valign='middle' style='font-size:12px;height:25px;'> Investment</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Subject</td>
	   			<td colspan=3 valign='middle' style='font-size:13px;height:25px;'> Server Replacement and Accounting System Migration   </td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:300px;'>Outline of Request<br>including Purpose of Request<br><i>(Please describe clearly the schedule, the benefit and investment return period if any)</i></td> 
				   <td colspan=3  style='font-size:13px;width:178px;height:200px;'> <br> <br> Replacement Of Server Accounting and File Server <br>
				    <br> Reason of replacement <br> <br> 1.) Server already out of support by vendor, which mean no longer spareparts.
				   <br><br> 2.) Server performance is slowing compared of data is being increased.
				   <br> <br> 3.) New technology will improve our development tools it will increase performance.</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Request Amount</td>
	   			<td valign='middle' style='font-size:12px;height:25px;'>Rp. 124.540.000</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Exchange Rate</td>
	   			<td colspan=3 valign='middle' style='font-size:12px;height:25px;'>JPY : 0.8</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Quarterly Budget amount</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>¥ 10.000</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Quarterly Budget Consumed</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>¥ 99.632</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Queaterly Budget Remaining</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>¥ 638</td>
	   			<td colspan=2 valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Name of Supplier/Vendor/Agent</td>
	   			<td colspan=3 valign='middle' style='font-size:12px;width:178px;height:25px;'> PT. Bhinneka Mentari Dimensi Solusi & Sage</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Contact of Supplier/Vendor/Agent</td>
	   			<td colspan=3 valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Trade Term</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Paymnet Term</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>30 Days</td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Goods/Service Receipt Date</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Paymnet Date</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>Attachments <i>(Please describe)</i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>1) Approval Form</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'>2) Quotation</td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'></td>
   			</tr>
   			<tr>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Tsuchida Yuji)<br><i>President Director</i></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Shibata Hiroshi)<br><i>Director</i></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Djatmiko Tanuwidjojo)<br><i>Board of Director</i></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Agusman Surya)<br><i>Senior Manager</i></td>
	   			
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>

   			</tr>
   			<tr>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Victor Antonio)<br><i>Accounting Manager</i></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'>(Reza Vebrian)<br><i>IT Section</i></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'></td>
	   			<td valign='bottom' align='center' style='font-size:12px;width:178px;height:100px;'></td>
   			</tr>
   			<tr>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
	   			<td valign='middle' style='font-size:12px;width:178px;height:25px;'><i>Date : </i></td>
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
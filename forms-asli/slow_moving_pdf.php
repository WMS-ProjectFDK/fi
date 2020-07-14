<?php
error_reporting(0);
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');

// Get the contents of the JSON file 
$user = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$date=date("d M y / H:i:s",time());
$date2=date("d M y",time());
$data = file_get_contents('slow_moving_'.$user.'.json');

$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

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

		<div style='margin-top:0;margin-left:950px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

		<page_footer>
			<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
				page [[page_cu]] of [[page_nb]]
			</div>
	    </page_footer>
		<div style='margin-top:20px;margin-bottom:100%;position:absolute;'>
			<h2 align='center'>SLOW MOVING<br></h2>
			<table align='center'>
				<thead>
					<tr>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:20px;height:25px;'>NO</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:150px;height:25px;'>ITEM NO<br/>DESCRIPTION</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>LAST<br/>INVENTORY</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>STANDARD<BR/>PRICE</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:150px;height:25px;'>SUPPLIER</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:100px;height:25px;'>RECEIVE<br/>NO.</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>RECEIVE<br/>QTY</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>RECEIVE<br/>DATE</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:130px;height:25px;'>SLIP<br/>NAME</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>SLIP<br/>QTY</th>
						<th valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;width:70px;height:25px;'>SLIP<br/>DATE</th>
					</tr>
				</thead>";
	$nourut = 1;

	foreach ($someArray as $key => $value) {
		$content .= "
			<tr>
				<td valign='middle' align='center' style='font-size:11px;width:20px;height:20px;'>".$nourut."</td>
				<td valign='middle' align='left' style='font-size:11px;width:150px;height:20px;'>".$value['ITEM_NO'].'<br/>'.$value['DESCRIPTION']."</td>
				<td valign='middle' align='right' style='font-size:11px;width:70px;height:20px;'>".$value['LAST_INVENTORY']."</td>
				<td valign='middle' align='right' style='font-size:11px;width:&0px;height:20px;'>".$value['STANDARD_PRICE']."</td>
				<td valign='middle' align='left' style='font-size:11px;width:150px;height:20px;'>".$value['SUPPLIER_CODE'].'<BR/>'.$value['COMPANY']."&nbsp;</td>
				<td valign='middle' align='left' style='font-size:11px;width:100px;height:20px;'>".$value['GR_NO']."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:11px;width:70px;height:20px;'>".number_format($value['QTY'])."&nbsp;</td>
				<td valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>".$value['LAST_DATE_BUY']."&nbsp;</td>
				<td valign='middle' align='left' style='font-size:11px;width:150px;height:20px;'>".$value['TRANSACTION_TYPE']."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:11px;width:70px;height:20px;'>".number_format($value['TRANSACTION_QTY'])."&nbsp;</td>
				<td valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>".$value['TRANSACTION_DATE']."&nbsp;</td>
			</tr>";
		$nourut++;			
	}

$content.="
			</table>
		</div>
	</page>
";

// echo $content;

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('SLOW_MOVING_'.$date2.'.pdf');
?>
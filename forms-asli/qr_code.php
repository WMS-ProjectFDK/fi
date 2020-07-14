<?php
ini_set('memory_limit','-1');
session_start();
date_default_timezone_set('Asia/Jakarta');

$link = "http://localhost/wms/forms/qr_generate_coba.php";

$content = " 
	<style> 
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:14px;
			height:10px;
		}
		table, th, tr {
			border-left:0px solid #ffffff; 
			border-right:0px solid #ffffff; 
			border-bottom:0px solid #ffffff; 
			border-top:0px solid #ffffff;
		}
		th {
			
			color: black;
		}
		.brd {
			border:none;
		}
		.class_00{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_01{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_02{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:25px;
		}


		.class_10{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_11{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:265px;
		}
		.class_12{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:265px;
		}

		.class_20{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_21{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:505px;
		}
		.class_22{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:505px;
		}
	</style>

	<page>
		<div>
          	<qrcode 
				value='".$link."' 
				ec='Q' style=' border:none; width:80px;background-color: white; color: black;'>
			</qrcode>
		</div>
	</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
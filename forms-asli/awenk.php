<?php 
error_reporting(0);
$dt = date('YmdHis');
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
	<page format='100x108' orientation='L' backtop='1mm' backleft='0mm' backright='1mm'>
    ";

$content .= "
    <div style='margin-top:5px;'>
    	<h4 align='center'>INCOMING MATERIAL</h4>
    	<h6>RM.D.01</h6>
    </div>
	<div style:'position:absolute;'>
		<table>
			<tr>
				<td align='center'><qrcode value='RM.D.01' ec='H' style=' border:none; width:120px;background-color: white; color: black;'></qrcode></td>
			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('incoming.pdf');	
?>
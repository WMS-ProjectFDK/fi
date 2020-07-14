<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');

$content = " 
	<style> 
		table {
			border-collapse: collapse;
			padding:0px;
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
			border:1px;width:360px;height:510px;border-radius:4px;margin-top:10px;
		}
		.class_01{
			position:absolute;margin-left:390px;border:1px;width:360px;height:510px;border-radius:4px;margin-top:10px;
		}


		.class_10{
			border:1px;width:360px;height:510px;border-radius:4px;margin-top:30px;
		}
		.class_11{
			position:absolute;margin-left:390px;border:1px;width:360px;height:510px;border-radius:4px;margin-top:550px;
		}
	</style>

	<page>
     ";

$i=1;	$row_a=0;	$col_a=0;
while ($i <= 10) {
	if ($row_a==0) {
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif ($col_a == 1) {
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==1){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif ($col_a == 1) {
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a=0;
		}
	}

	$content.= "<div class=".$cls.">".$i."</div>";
	$i++;
}

$content .= "</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
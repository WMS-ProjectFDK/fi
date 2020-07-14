<?php
error_reporting(0);
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');

// http://localhost/wms/forms/wnx_print?data=[{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:1,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:2,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:3,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:4,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:5,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:6,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:7,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:8,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:9,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22},{%22crt_wo%22:%22FI/19-181-LR6G07-2%22,%22crt_plt%22:10,%22crt_book%22:%22AWW%22,%22crt_cont%22:%22LLK%22,%22crt_po%22:%22%208RTXZQCL%22,%22crt_asin%22:%22B00MNV8E0C%22}]&bookingHeader=AWW&containerHeader=LLK

$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
$bookingHeader = isset($_REQUEST['bookingHeader']) ? strval($_REQUEST['bookingHeader']) : '';
$containerHeader = isset($_REQUEST['containerHeader']) ? strval($_REQUEST['containerHeader']) : '';

$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

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
	</style>

	<page>
    	<div>";

    		foreach ($someArray as $key => $value) {
			    $content.= "WO : ".$value["crt_wo"] . "<br/>PLT : " . $value["crt_plt"] . "<br/>PO : " . $value["crt_po"] . "<br>ASIN : ".$value["crt_asin"]."<br/>";
			    $content.= "<hr>";
			}

$content.="
		</div>
	</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
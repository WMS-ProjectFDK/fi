<?php
error_reporting(0);
ini_set('memory_limit','-1');
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');

// Get the contents of the JSON file 
$user = $_SESSION['id_wms'];
$data = file_get_contents('kanban_print_sscc_result_'.$user.'.json');

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
		.brd {
			border:none;
		}
		.class_00{
			border:1px;width:66mm;height:84mm;margin-top:20px;
		}
		.class_01{
			position:absolute;margin-left:270px;border:1px;width:66mm;height:84mm;margin-top:20px;
		}
		.class_02{
			position:absolute;margin-left:540px;width:66mm;height:84mm;border:1px;margin-top:20px;
		}
		.class_03{
			position:absolute;margin-left:810px;width:66mm;height:84mm;border:1px;margin-top:20px;
		}


		.class_10{
			border:1px;width:66mm;height:84mm;margin-top:45px;
		}
		.class_11{
			position:absolute;margin-left:270px;border:1px;width:66mm;height:84mm;margin-top:385px;
		}
		.class_12{
			position:absolute;margin-left:540px;width:66mm;height:84mm;border:1px;margin-top:385px;
		}
		.class_13{
			position:absolute;margin-left:810px;width:66mm;height:84mm;border:1px;margin-top:385px;
		}
	</style>
	<page>";

	$row_a=0;	$col_a=0;     
	$row_z=1;	$col_Z=3;
	$no = 1;

	foreach ($someArray as $key => $value) {
	  // if($no <= 0){
	  	if ($row_a==0) {
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==1){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==2){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==3){
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a++;
			}
		}elseif($row_a==1){
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==1){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==2){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==3){
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a=0;
			}
		}

		$content.= "<div class=".$cls.">
						<table border=1 style='font-size:10px;'>
								<tr>
									<td style='width:110px;height:55px;border:0px solid #ffffff;border-bottom:1px solid #000000;border-right:1px solid #000000;'>
										SHIP FROM :<br/>
										<p style='font-size: 8px;width: 95%;'>
											PT. FDK INDONESIA<br/>KAWASAN INDUSTRI, BLOK MM-1, <br/>CIKARANG NDONESIA. 17520
										</p>
									</td>
									<td style='width:110px;height:55px;border:0px solid #ffffff;border-bottom:1px solid #000000;'>
										&nbsp;
										SHIP TO :<br/>
										<p style='font-size: 8px;margin-left:5px;width: 95%;'>
											".$value['ADDRESS1']."<br/>
											".$value['ADDRESS2']."<br/>
											".$value['ADDRESS3']."<br/>
											".$value['ADDRESS4']."
										</p>
									</td>
								</tr>
								<tr>
									<td colspan=2  style='height:130px;border-right:0px solid #ffffff;border-left:0px solid #ffffff;margin-bottom:100%;'>
										<p align='center' style='padding: 2px 2px;'>
											<barcode dimension='2D' type='C128B' value='".strval(trim($value['PO']))."' style='width:40mm; height:8mm; color: #000000;font-color: #ffffff' label='none'></barcode>
										</p>
										<div style='width:95%; font-size: 11px;' >
											PURCHASE ORDER(S) : ".$value['PO']."
											<br/><br/>
											ASIN # : ". $value['ASIN']."
											<br/><br/>
											Country of Origin : INDONESIA
											<br/><br/>
											QTY : ".number_format($value['QTY'])."
											<br/><br/>
											CARTON # : ".$value['FROM_CARTON']." OF ".$value['TO_CARTON']."
										</div>
									</td>
								</tr>
								<tr>
									<td colspan=2 align='center' style='height:70px;border-right:0px solid #ffffff;border-left:0px solid #ffffff;border-bottom:0px solid #ffffff;'>
										";
									if($value['SSCC'] != ''){
										$content .= "
										<span style='font-size: 11px;'>SERIAL SHIPPING CONTAINER CODE (SSCC)</span>
										<br/><br/>
										<barcode dimension='2D' type='C128C' value='".$value['SSCC']."' style='width:60mm; height:10mm; color: #000000; font-size: 2.5mm'></barcode>";
									}
									$content .="
									</td>
								</tr>
							</table>	
					</div>";
	  // }	
		$no++;			
	}

$content.="
	</page>";

// echo $content;

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
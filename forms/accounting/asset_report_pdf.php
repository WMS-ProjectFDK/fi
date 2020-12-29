<?php
// <!-- 
// ID = 2
// Name : UENG H
// Tanggal : 21 MAR 2018
// Deskripsi : membuat print barcode u/ asset report -->
error_reporting(0);
ini_set('memory_limit','-1');
include("../../connect/conn_accpac.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$tipe = isset($_REQUEST['tipe']) ? strval($_REQUEST['tipe']) : '';
$category = isset($_REQUEST['category']) ? strval($_REQUEST['category']) : '';
$ast = isset($_REQUEST['ast']) ? strval($_REQUEST['ast']) : '';
$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
$location = isset($_REQUEST['location']) ? strval($_REQUEST['location']) : '';
$group = isset($_REQUEST['group']) ? strval($_REQUEST['group']) : '';

$sql = "select astno, desc_name, CAST(acqdate as varchar(10)) as acqdate 
	from zvw_ast_detail where 1=1 ";

$line = str_replace("*","#",$line);
if($category!="")$sql.=" and ltrim(rtrim(grpdesc)) like '%$category%'";
if($group!="")$sql.=" and ltrim(rtrim(CATEGORY)) like '%$group%'";
if($ast!="")$sql.=" and ltrim(rtrim(astno)) like '%$ast%'";
if($line!="")$sql.=" and ltrim(rtrim(line)) like '%$line%'";
if($location!="")$sql.=" and ltrim(rtrim(location)) like '%$location%'";

$sql .= " order by CATEGORY,grp";
$rs=sqlsrv_query($connect, strtoupper($sql));

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
			border:1px;margin-left:40px;width:300px;height:120px;border-radius:4px;margin-top:25px;
		}
		.class_01{
			position:absolute;margin-left:370px;border:1px;width:300px;height:120px;border-radius:4px;margin-top:25px;
		}
		.class_02{
			position:absolute;margin-left:700px;width:300px;height:120px;border:1px;border-radius:4px;margin-top:25px;
		}


		.class_10{
			border:1px;margin-left:40px;width:300px;height:120px;border-radius:4px;margin-top:30px;
		}
		.class_11{
			position:absolute;margin-left:370px;border:1px;width:300px;height:120px;border-radius:4px;margin-top:177px;
		}
		.class_12{
			position:absolute;margin-left:700px;width:300px;height:120px;border:1px;border-radius:4px;margin-top:177px;
		}


		.class_20{
			border:1px;margin-left:40px;width:300px;height:120px;border-radius:4px;margin-top:30px;
		}
		.class_21{
			position:absolute;margin-left:370px;border:1px;width:300px;height:120px;border-radius:4px;margin-top:329px;
		}
		.class_22{
			position:absolute;margin-left:700px;width:300px;height:120px;border:1px;border-radius:4px;margin-top:329px;
		}


		.class_30{
			border:1px;margin-left:40px;width:300px;height:120px;border-radius:4px;margin-top:50px;
		}
		.class_31{
			position:absolute;margin-left:370px;border:1px;width:300px;height:120px;border-radius:4px;margin-top:501px;
		}
		.class_32{
			position:absolute;margin-left:700px;width:300px;height:120px;border:1px;border-radius:4px;margin-top:501px;
		}

	</style>
	<page>";

$row_a=0;	$col_a=0;	$i=1;
while( $row = sqlsrv_fetch_object($rs) ) { 
	if ($row_a==0) {
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==2){
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
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==2){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==3){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a=0;
		}
	}

	$content.= "<div class=".$cls.">
					<table  border=0 style='border-radius:4px;font-size:12px;width:290px; height: 110px;'>
						<tr>
							<td colspan=3 style='height: 30px;background-color:#595959;'>
								<img src='../../images/fdk_asset.png' style='width:250px;height:35px;'/>
								<qrcode 
									value='".$row->ASTNO."' ec='Q' style='width:35px;background-color: white; color: black;'>
								</qrcode>
							</td>
						</tr>

						<tr>
							<td>ASSET ID</td>
							<td>:</td>
							<td style='width:235px;font-size:13px;'><b>".$row->ASTNO."</b></td>
						</tr>
						<tr>
							<td style='font-size:10px;'>DESC</td>
							<td>:</td>
							<td style='width:235px;height:8px;font-size:10px;'>".substr($row->DESC_NAME, 0, 30)."</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style='width:235px;height:8px;font-size:10px;'>".substr($row->DESC_NAME, 30, 30)."</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style='width:235px;height:8px;font-size:10px;'>".substr($row->DESC_NAME, 60, 30)."</td>
						</tr>
						<tr>
							<td style='font-size:10px;'>Acq Date</td>
							<td style='font-size:10px;'>:</td>
							<td valign = 'bottom' style='width:235px;font-size:10px;'>".$row->ACQDATE."</td>
						</tr>
					</table>
				</div>";
	$i++;
}
$content .= "
	</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Asset_Report.pdf');
?>
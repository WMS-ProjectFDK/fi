<?php
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';
$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

$q_si = "select distinct customer_po_no as po_no from answer where crs_remark='$ppbe' OR si_no='$si'";
$data = oci_parse($connect, $q_si);
oci_execute($data);
$dt = oci_fetch_object($data);

$qry = "select rtrim(replace(marks,chr(10),'<br/>'),'|') as pallet_mark from do_marks where do_no='$do' order by mark_no asc";
/*$qry = "select pallet_mark_1 || '<br/>' || pallet_mark_2 || '<br/>' || pallet_mark_3 || '<br/>' || pallet_mark_4 || '<br/>' || pallet_mark_5
 || '<br/>' || pallet_mark_6 || '<br/>' || pallet_mark_7 || '<br/>' || pallet_mark_8 || '<br/>' || pallet_mark_9 || '<br/>' || pallet_mark_10 
 as pallet_mark
 from so_details where so_no in (select distinct so_no from answer where crs_remark='$ppbe' OR si_no='$si')";*/
$result = oci_parse($connect, $qry);
oci_execute($result);

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
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:13px;
		}
		.class_01{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:150px;
		}
		.class_02{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:150px;
		}

		.class_10{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_11{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:325px;
		}
		.class_12{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:325px;
		}

		.class_20{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_21{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:500px;
		}
		.class_22{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:500px;
		}

		.class_30{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_31{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:675px;
		}
		.class_32{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:675px;
		}

		.class_40{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_41{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:850px;
		}
		.class_42{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:850px;
		}

		.class_00_NEXT{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:13px;
		}
		.class_01_NEXT{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:13px;
		}
		.class_02_NEXT{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:13px;
		}

		.class_10_NEXT{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_11_NEXT{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:190px;
		}
		.class_12_NEXT{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:190px;
		}

		.class_20_NEXT{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_21_NEXT{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:500px;
		}
		.class_22_NEXT{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:500px;
		}

		.class_30_NEXT{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_31_NEXT{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:675px;
		}
		.class_32_NEXT{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:675px;
		}

		.class_40_NEXT{
			margin-left:20px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:50px;
		}
		.class_41_NEXT{
			position:absolute;margin-left:270px;border:0px;width:240px;height:125px;border-radius:4px;margin-top:850px;
		}
		.class_42_NEXT{
			position:absolute;margin-left:520px;width:240px;height:125px;border:0px;border-radius:4px;margin-top:850px;
		}
	</style>
	<page>
		<div style='position:absolute;margin-top:0px;height:100px;'>
			<img src='../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:620px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<br>
	<p><h3 align='center'>ATTACHMENT OF SI NO. ".$dt->PO_NO."</h3></p>
	<div style='clear:both;'></div>";

$row_a=0;	$col_a=0;	$page_next=0;
while ($data=oci_fetch_object($result)){

  if($page_next == 0){
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
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==4){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a=0;	$page_next++;
		}
	}
  }else{
  	if ($row_a==0) {
		if($col_a==0){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==1){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==2){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==3){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==4){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==1){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a++;
		}elseif($col_a==2){
			$cls = "class_".$row_a.$col_a.'_NEXT';
			$col_a=0;	$row_a=0;	$page_next++;
		}
	}
  }

	$content.= "<div class=".$cls.">
					<table border=0 style='font-size:16px;font-weight: bold;'>
						<tr>
							<td style='width:250px;'>".$data->PALLET_MARK."</td>
						</tr>
					</table>
				</div>";

	if($cls == 'class_42'){
		$content.="<div style='clear:both;'></div>";
	}
}

$content .= "
	</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
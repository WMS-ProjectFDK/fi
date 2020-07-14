<?php 
//error_reporting(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

//pd_periode_awal=2017-04-03&pd_periode_akhir=2017-04-03&dn_supp=&ck_supp=true

$pd_periode_awal = isset($_REQUEST['pd_periode_awal']) ? strval($_REQUEST['pd_periode_awal']) : '';
$pd_periode_akhir = isset($_REQUEST['pd_periode_akhir']) ? strval($_REQUEST['pd_periode_akhir']) : '';
$dn_supp = isset($_REQUEST['dn_supp_nm']) ? strval($_REQUEST['dn_supp_nm']) : '';
$dn_supp_nm = isset($_REQUEST['dn_supp_nm']) ? strval($_REQUEST['dn_supp_nm']) : '';

if ($ck_supp != "true"){
	$supp = "a.pu_suppliername='$dn_supp' AND ";	
	$spp=$dn_supp_nm;
}else{
	$supp = " ";	
	$spp="ALL";
}

$head = "select distinct
	(select sum(amt_l) from po_header 
	where po_date BETWEEN to_date('$pd_periode_awal','yyyy-mm-dd') and to_date('$pd_periode_akhir','yyyy-mm-dd')) as total_amount,
	(select sum(amt_l) from po_header 
	where po_date BETWEEN to_date('$pd_periode_awal','yyyy-mm-dd') and to_date('$pd_periode_akhir','yyyy-mm-dd') and
	curr_code=23) as total_local,
	(select sum(amt_l) from po_header 
	where po_date BETWEEN to_date('$pd_periode_awal','yyyy-mm-dd') and to_date('$pd_periode_akhir','yyyy-mm-dd') and
	curr_code!=23) as total_import
	from po_header";
$dt_h = oci_parse($connect, $head);
oci_execute($dt_h);
$headNya = oci_fetch_object($dt_h);

$qry = "select distinct
	case 
	  when(b.item_no between 1200000 and 1299999) then 'LABEL'
	  when(b.item_no not between 1200000 and 1299999) then 'RAW MATERIAL'
	  when(b.item_no between 2100000 and 2199999) then 'PACKAGING MATERIAL'
	end 
	as grp,  
	0 as amount,
	0 as local, 
	0 as import
	from po_header a
	inner join po_details b on a.po_no= b.po_no
	where a.po_date BETWEEN to_date('$pd_periode_awal','yyyy-mm-dd') and to_date('$pd_periode_akhir','yyyy-mm-dd')
	group by 
	(case 
	  when(b.item_no between 1200000 and 1299999) then 'LABEL'
	  when(b.item_no not between 1200000 and 1299999) then 'RAW MATERIAL'
	  when(b.item_no between 2100000 and 2199999) then 'PACKAGING MATERIAL'
	end)";
$result = oci_parse($connect, $qry);
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
			<img src='../images/logo-print4.png' alt='#' style='width:270px;height: 35px'/>
		</div>	

		<div style='margin-top:0;margin-left:640px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>		
		</div>
		".$ket."

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:20px;position:absolute;'>
		<h5 align='center'>PURCHASE ORDER BY MATERIAL GROUP<br></h5>
		<p align='center' style='font-size:9px;margin-top:0px;'><i>Period : ".$pd_periode_awal."&nbsp; to &nbsp;".$pd_periode_akhir."</i></p>
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
			<th valign='middle' align='center' style='font-size:10px;width:150px;height:25px;'>MATERIAL GROUP</th>
			<th valign='middle' colspan=2 align='center' style='font-size:10px;width:100px;height:25px;'>AMOUNT</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>%</th>
			<th valign='middle' colspan=2 align='center' style='font-size:10px;width:100px;height:25px;'>LOCAL</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>%</th>
			<th valign='middle' colspan=2 align='center' style='font-size:10px;width:100px;height:25px;'>IMPORT</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>%</th>
		</tr>
	</thead>";
$total=0;
$t_amount=0;
while ($data=oci_fetch_object($result)){
	$prs_amount = ($data->AMOUNT * 100) / floatval($headNya->TOTAL_AMOUNT);
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$nourut."</td>
			<td valign='middle' align='left' style='font-size:10px;height:25px;'>".$data->GRP."</td>
			<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->AMOUNT)."</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($prs_amount,2)."&nbsp; %</td>
			<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->LOCAL)."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>&nbsp; %</td>
			<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->IMPORT)."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;'>&nbsp; %</td>
		</tr>";
	$t_amount += $data->AMOUNT;
	$t_local += $data->LOCAL;
	$nourut++;
}

$content .= "
			<tr>
				<td colspan=2 valign='middle' align='center' style='font-size:12px;height:25px;'><b><i>TOTAL</i></b> &nbsp;</td>
				<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>".number_format($headNya->TOTAL_AMOUNT,2)."</b></td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>&nbsp; %</b></td>
				<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>".number_format($headNya->TOTAL_LOCAL,2)."</b></td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>&nbsp; %</b></td>
				<td valign='middle' style='font-size:12px;height:25px;border-right:0px solid #ffffff;'>$</td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>".number_format($headNya->TOTAL_IMPORT,2)."</b></td>
				<td valign='middle' align='right' style='font-size:12px;height:25px;'><b>&nbsp; %</b></td>
			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('PO-'.$po.'.pdf');
//echo  $content;
?>
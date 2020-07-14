<?php 
//error_reporting(0);
session_start();
date_default_timezone_set('Asia/Jakarta');
include("../connect/conn.php");
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';

$where ="where to_char(date_prod, 'YYYY-MM-DD HH24:MI:SS') BETWEEN '$date_awal' AND '$date_akhir'";

$sql = "select type_gel, kanban_no, no_tag, type_zn, qty_zn, 
	to_char(qty_aquapec, '0.0') as qty_aquapec, to_char(qty_pw150,'0,0') as qty_pw150, to_char(qty_th175b,'0,0') as qty_th175b, qty_elec, 
	to_char(act_qty_aqupec, '0.0') as act_qty_aqupec, to_char(act_qty_pw150,'0,0') as act_qty_pw150, to_char(act_qty_th175b,'0,0') as act_qty_th175b,
	to_char(qty_air,'0,0') as qty_air, qty_total, density,worker_id_gel, zw.name, to_char(upto_date_hasil_anode,'DD-MON-YY HH24:MI:SS') as upto_date_hasil_anode, 
	remark, assy_line, to_char(date_use,'DD-MON-YY HH24:MI:SS') as date_use, to_char(date_prod,'DD-MON-YY HH24:MI:SS') as date_prod
	from ztb_assy_anode_gel a
	inner join ztb_worker zw on a.worker_id_gel = zw.worker_id
	$where
	order by date_prod asc";
$data = oci_parse($connect, $sql);
oci_execute($data);

$date = date("d M y / H:i:s",time());
$content .= "	
	<style>
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:12px;
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
			<img src='../images/logo-print4.png' alt='#' style='width:300px;height: 65px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:1950px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		<h3 align='center'>ANODE GEL TRANSACTION<br></h3>
		<table align='center'>
			<tr>
				<td style='border:0px solid #ffffff;'>DATE PRODUCTION</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$date_awal." TO ".$date_akhir."</td>
			</tr>
		</table>
		<table align='center'>";

$nourut = 1;
$t_zn = 0;		$t_el = 0;
$t_aq = 0;		$t_ai = 0;
$t_pw = 0;		$t_to = 0;
$t_th = 0;

$content .= "
	<thead>
		<tr>
	    	<th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:30px;height:25px;'>NO</th>
	    	<th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:70px;height:25px;'>PRODUCT<br/>DATE</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:70px;height:25px;'>TYPE<br/>GEL</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:70px;height:25px;'>KANBAN<br/>NO.</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:70px;height:25px;'>TAG<br/>NO.</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:70px;height:25px;'>TYPE<br/>ZINC</th>
	        <th colspan='6' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;height:25px;'>COMPOSITION</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>TOTAL</th>
	        <th colspan='3' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;height:25px;'>WEIGHT RESULT</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>DENSITY</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:80px;height:25px;'>ANODE GEL<br/>WORKER</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:80px;height:25px;'>ANODE GEL<br/>TIME</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:150x;height:25px;'>REMARK</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:80px;height:25px;'>ASSEMBLY<br/>LINE</th>
	        <th rowspan='2' valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:80px;height:25px;'>ASSEMBLY<br/>TIME</th>
	    </tr>
	    <tr>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>ZN</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>AQUPEC<br>HV-505 HC</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>AQUPEC<br>HV-501 E</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>TH-175B</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:75px;height:25px;'>ELEC. L</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>AIR</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>ACTUAL AQUPEC<br>HV-505 HC</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>ACTUAL AQUPEC<br>HV-501 E</th>
	    	<th valign='middle' align='center' style='background-color: #ABABAB; font-size:12px;width:65px;height:25px;'>ACTUAL TH-175B</th>
	    </tr>
	</thead>";

while ($row=oci_fetch_object($data)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:11px;width:30px;height:20px;'>".$nourut."</td>
			<td valign='middle' style='font-size:11px;width:70px;height:20px;'>&nbsp;".$row->DATE_PROD."</td>
			<td valign='middle' style='font-size:11px;width:70px;height:20px;'>&nbsp;".$row->TYPE_GEL."</td>
			<td valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>".$row->KANBAN_NO."</td>
			<td valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>".$row->NO_TAG."</td>
			<td valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>".$row->TYPE_ZN."</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->QTY_ZN."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->QTY_AQUAPEC."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->QTY_PW150."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->QTY_TH175B."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:75px;height:25px;'>".$row->QTY_ELEC."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->QTY_AIR."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:20px;'>".$row->QTY_TOTAL."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->ACT_QTY_AQUPEC."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->ACT_QTY_PW150."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:25px;'>".$row->ACT_QTY_TH175B."&nbsp;</td>
			<td valign='middle' align='right' style='font-size:11px;width:65px;height:20px;'>".$row->DENSITY."&nbsp;</td>
			<td valign='middle' style='font-size:11px;width:80px;height:20px;'>&nbsp;".$row->WORKER_ID_GEL."<br/>".$row->NAME."</td>
			<td valign='middle' style='font-size:11px;width:80px;height:20px;'>&nbsp;".$row->UPTO_DATE_HASIL_ANODE."</td>
			<td valign='middle' style='font-size:11px;width:150px;height:20px;'>&nbsp;".$row->REMARK."</td>
			<td valign='middle' style='font-size:11px;width:80px;height:20px;'>&nbsp;".$row->ASSY_LINE."</td>
			<td valign='middle' style='font-size:11px;width:80px;height:20px;'>&nbsp;".$row->DATE_USE."</td>
		</tr>";

	$n_zn = $row->QTY_ZN;
	$n_aq = $row->QTY_AQUAPEC;
	$n_pw = $row->QTY_PW150;
	$n_th = $row->QTY_TH175B;

	$a_n_aq = $row->ACT_QTY_AQUPEC;
	$a_n_pw = $row->ACT_QTY_PW150;
	$a_n_th = $row->ACT_QTY_TH175B;

	$n_el = $row->QTY_ELEC;
	$n_ai = $row->QTY_AIR;
	$n_to = $row->QTY_TOTAL;

	$t_zn += $n_zn;
	$t_aq += $n_aq;
	$t_pw += $n_pw;
	$t_th += $n_th;
	
	$t_a_aq += $a_n_aq;
	$t_a_pw += $a_n_pw;
	$t_a_th += $a_n_th;

	$t_el += $n_el;
	$t_ai += $n_ai;
	$t_to += $n_to;

	$nourut++;
}

$content .= "
			<tr>
				<td colspan='6' valign='middle' align='center' style='background-color: #ABABAB;'><b>TOTAL</b></td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_zn,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_aq,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_pw,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_th,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_el,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_ai,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_to,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_a_aq,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_a_pw,2)."</b>&nbsp;</td>
				<td valign='middle' align='right' style='background-color: #ABABAB; font-size:12px;height:25px;'><b>".number_format($t_a_th,2)."</b>&nbsp;</td>
				<td colspan='6' valign='middle' align='center' style='background-color: #ABABAB;'></td>
			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A2','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('ANODE GEL '.$date_awal.'.pdf');
//echo $sql;
?>
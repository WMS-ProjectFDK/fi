<?php 
error_reporting(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$arrBulan = array( '1' => 'JANUARY',   '2' => 'FEBRUARY',  '3' => 'MARCH',  '4' => 'APRIL',  '5' => 'MAY',  
                   '6' => 'JUNE',  '7' => 'JULY',  '8' => 'AUGUST',  '9' => 'SEPTEMBER',  '10' => 'OCTOBER', '11' => 'NOVEMBER',  '12' => 'DECEMBER');

$min_date = strtotime('-1 day',strtotime(date('Y-m-d')));
$plan_date = intval(date("d",$min_date));

if(intval(date('d')) == 1){
    if(intval(date('m')) == 1){
        $bulan = 12;
        $tahun = intval(date('Y')) - 1;
    }else{
        $bulan = intval(date('m')) -1;
        $tahun = intval(date('Y'));
    }
}else{
    $bulan = intval(date('m'));
    $tahun = intval(date('Y'));
}

$result = array();

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';

$qry61 = "select batery_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation  
          from zvw_comparison_summary where bulan = '".$arrBulan[$bulan]."' and hari <= $plan_date group by batery_type";
$data61 = oci_parse($connect, $qry61);
oci_execute($data61);

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
			<img src='../images/fdk8.png' alt='#' style='width:110px;height: 80px'/>
		</div>	

		<div style='margin-top:0;margin-left:630px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>		
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<div style='margin-top:150px;position:absolute;'>	
		<table align='center'>";
$nox = 1;
$content .= '
	<thead>
		<tr>
			<td style="background-color: #D2D2D2;width: 40px;" align="center"><b>NO.</b></td>
			<td style="background-color: #D2D2D2;width: 250px;" align="center"><b>'.$arrBulan[$bulan].'</b></td>
			<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>'.date('m').'</b></td>
			</tr>
	</thead>';
while ($row61x = oci_fetch_object($data61)){
	$accum += $row61x->ACCUMULATION;
	if(intval($row61x->ACCUMULATION) < 0){
		$acc = '<td style="background-color: #E2EFDA;color: #FF0000;" align="right">'.number_format($row61x->ACCUMULATION).'</td>';
	}else{
		$acc = '<td style="background-color: #E2EFDA;" align="right">'.number_format($row61x->ACCUMULATION).'</td>';
	}

	$content.='<tr>
					<td style="background-color: #E2EFDA;">'.$nox.'</td>
					<td style="background-color: #E2EFDA;">'.$row61x->BATERY_TYPE.'</td>
					'.$acc.'
			   </tr>';
	$nox++;
}
if(intval($accum) < 0){
	$tg = 	'<td style="background-color: #D2D2D2;color: #FF0000;" align="right"><b>'.number_format($accum).'</b></td>';
}else{
	$tg = 	'<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($accum).'</b></td>';
}
$content.='
		<tr>
			<td style="background-color: #D2D2D2;" align="right" colspan=2><b>TOTAL GAP</b></td>
			'.$tg.'
		</tr>';
$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('STO_'.$tanggal.'.pdf');
?>
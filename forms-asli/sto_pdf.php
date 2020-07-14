<?php 
error_reporting(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';

$qry = "select distinct a.item_no, b.description, sum (a.qty-a.qty_out) as total, c.unit,
	coalesce((select sum(qty) from ztb_wh_in_det where item_no = a.item_no and rack is null and daterecord is null and userid is null),0) as non_rack	
	from ztb_wh_in_det a 
	inner join item b on a.item_no=b.item_no inner join unit c on b.uom_q=c.unit_code
	where a.rack is not null and a.qty-a.qty_out > 0 and to_date(substr(a.tanggal, 0, 8),'YYYY=MM-DD') <= to_date('$tanggal','YYYY-MM-DD')
	group by a.item_no, b.description, c.unit
	order by b.description asc";
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
			<img src='../images/fdk8.png' alt='#' style='width:110px;height: 80px'/>
		</div>	

		<div style='margin-top:0;margin-left:630px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>		
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer> 
    <br/><br/><br/>
	<h4 align='center'>STOCK TAKING OPNAME<br></h4>
	<p align='center'>period : ".$tanggal."<br></p>
	
	<div style='margin-top:150px;position:absolute;'>	
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:14px;width:30px;height:50px;'>NO</th>
			<th valign='middle' align='center' style='font-size:14px;width:80px;height:35px;'>ITEM NO.</th>
			<th valign='middle' align='center' style='font-size:14px;width:320px;height:35px;'>ITEM NAME</th>
			<th valign='middle' align='center' style='font-size:14px;width:50px;height:35px;'>UoM</th>
			<th valign='middle' align='center' style='font-size:14px;width:120px;height:35px;'>STOCK RACK</th>
			<th valign='middle' align='center' style='font-size:14px;width:120px;height:35px;'>STOCK NON-RACK</th>
		</tr>
	</thead>";

while ($data=oci_fetch_array($result)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$nourut."</td>
			<td valign='middle' style='font-size:12px;height:25px;'>".$data[0]."</td>
			<td valign='middle' style='font-size:12px;height:25px;'>".$data[1]."</td>
			<td valign='middle' align='center' style='font-size:12px;height:25px;'>".$data[3]."</td>
			<td valign='middle' align='right' style='font-size:12px;height:25px;'>".number_format($data[2])."</td>
			<td valign='middle' align='right' style='font-size:12px;height:25px;'>".number_format($data[4])."</td>
		</tr>";
	$nourut++;
}
$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('STO_'.$tanggal.'.pdf');
?>
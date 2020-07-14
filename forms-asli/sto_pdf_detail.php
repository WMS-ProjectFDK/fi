
<?php 
//error_reporting(0);
ini_set('memory_limit','999M');
include("../connect/conn.php");
//session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';

$qry = "select distinct a.item_no, b.description, sum (a.qty-a.qty_out) as total, c.unit from ztb_wh_in_det a 
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

		<div style='margin-top:0;margin-left:940px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>		
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer> 
    <br/><br/><br/>
	<h4 align='center'>STOCK TAKING OPNAME DETAIL<br></h4>
	<p align='center'>period : ".$tanggal."<br></p>
	
	<div style='margin-top:150px;position:absolute;'>	
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:14px;width:50px;height:35px;'>NO</th>
			<th colspan=2 valign='middle' align='center' style='font-size:14px;width:120px;height:35px;'>ITEM NO.</th>
			<th colspan=4 valign='middle' align='center' style='font-size:14px;width:385px;height:35px;'>ITEM NAME</th>
			<th valign='middle' align='center' style='font-size:14px;width:100px;height:35px;'>UoM</th>
			<th valign='middle' align='center' style='font-size:14px;width:180px;height:35px;'>STOCK RACK</th>
			<th valign='middle' align='center' style='font-size:14px;width:180px;height:35px;'>STOCK NON-RACK</th>
		</tr>
	</thead>";

while ($data=oci_fetch_array($result)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><b>".$nourut."</b></td>
			<td colspan=2 valign='middle' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><b>".$data[0]."</b></td>
			<td colspan=4 valign='middle' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><b>".$data[1]."</b></td>
			<td valign='middle' align='center' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><B>".$data[3]."</b></td>
			<td valign='middle' align='right' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><b>".number_format($data[2])."</b></td>
			<td valign='middle' align='right' style='font-size:12px;height:23px;background-color:#505050;color:#FFFFFF;'><b></b></td>
		</tr>
		";
		/*<tr>
			<td valign='middle' align='center' style='font-size:14px;height:25px;border-right:0px solid #ffffff;'></td>
			<td valign='middle' align='center' style='font-size:14px;width:70px;height:25px;'></td>
			<td valign='middle' align='center' style='font-size:14px;width:50px;height:25px;background-color:#E3E3E3;'><b>NO</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:120px;height:25px;background-color:#E3E3E3;'><b>GOOD RECEIVE NO.</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:120px;height:25px;background-color:#E3E3E3;'><b>GOOD RECEIVE DATE</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:70px;height:25px;background-color:#E3E3E3;'><b>LINE</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:70px;height:25px;background-color:#E3E3E3;'><b>PALLET</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:100px;height:25px;background-color:#E3E3E3;'><b>QTY</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:180px;height:25px;background-color:#E3E3E3;'><b>RACK</b></td>
			<td valign='middle' align='center' style='font-size:14px;width:180px;height:25px;background-color:#E3E3E3;'><b>WAREHOUSE</b></td>
		</tr>
		$item = trim($data[0]);
		$nourut_dtl = 1;
		$sql = "select a.gr_no, a.line_no,coalesce(a.rack,'-') as rack, a.pallet, a.qty-a.qty_out as qty, a.id,b.description, 
			coalesce(c.warehouse,'-') as warehouse, d.gr_date, a.item_no from ztb_wh_in_det a 
			left join item b on a.item_no=b.item_no left join ztb_wh_rack c on a.rack=c.id_rack 
			left join gr_header d on a.gr_no=d.gr_no
			where a.qty - a.qty_out > 0 and rack is not null and a.item_no='$item' and to_date(a.tanggal,'YYYY=MM-DD') <= to_date('$tanggal','YYYY-MM-DD')
			order by a.rack asc";
		$detail = oci_parse($connect, $sql);
		oci_execute($detail);
		while ($dta = oci_fetch_array($detail) ){
			$content .= "
				<tr>
					<td valign='middle' align='center' style='font-size:10px;height:15px;border-bottom:0px solid #ffffff;border-right:0px solid #ffffff;'></td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;border-top:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$nourut_dtl."</td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$dta[0]."</td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$dta[8]."</td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$dta[1]."</td>
					<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$dta[3]."</td>
					<td valign='middle' align='right' style='font-size:10px;height:15px;'>".number_format($dta[4])."</td>
					<td valign='middle' align='center'  style='font-size:10px;height:15px;'>".$dta[2]."</td>
					<td valign='middle' align='center'  style='font-size:10px;height:15px;'>".$dta[7]."</td>
				</tr>";
			$nourut_dtl++;
		}*/
	$nourut++;
}
$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('STO_DETAILS_'.$tanggal.'.pdf');
?>
<?php 
error_reporting(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

$qry = "select a.slip_no, b.line_no, b.item_no, c.description, b.qty, b.uom_q, d.unit from mte_header a
	inner join mte_details b on a.slip_no= b.slip_no inner join item c on b.item_no = c.item_no inner join unit d on b.uom_q = d.unit_code
	where a.slip_no='$do' order by b.line_no asc";
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
	<h4 align='center'>MATERIAL TRANSACTION<br></h4>
	
	<div style='margin-top:120px;position:absolute;'>	
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
			<th valign='middle' align='center' style='font-size:10px;width:100px;height:25px;'>SLIP NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>RACK NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>MATERIAL NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:300px;height:25px;'>MATERIAL NAME</th>
			<th valign='middle' align='center' style='font-size:10px;width:50px;height:25px;'>PALLET</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>QTY</th>
			<th valign='middle' align='center' style='font-size:10px;width:50px;height:25px;'>UoM</th>
			<th valign='middle' align='center' style='font-size:10px;width:250px;height:25px;'>QR CODE</th>
		</tr>
	</thead>";

while ($data=oci_fetch_array($result)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:10px;height:25px;background-color:#EBEBEB;'>".$nourut."</td>
			<td valign='middle' align='left' style='font-size:10px;height:25px;background-color:#EBEBEB;'>".$data[0]."</td>
			<td valign='middle' align='center' style='font-size:10px;height:25px;background-color:#EBEBEB;'>-</td>
			<td valign='middle' align='center' style='font-size:10px;height:25px;background-color:#EBEBEB;'>".$data[2]."</td>
			<td valign='middle' align='left' style='font-size:10px;height:25px;background-color:#EBEBEB;'>".$data[3]."</td>
			<td valign='middle' align='center' style='font-size:10px;height:25px;;background-color:#EBEBEB;'> - </td>
			<td valign='middle' align='right' style='font-size:10px;height:25px;;background-color:#EBEBEB;'>".number_format($data[4])."&nbsp;</td>
			<td valign='middle' align='center' style='font-size:10px;height:25px;;background-color:#EBEBEB;'>".$data[6]."</td>
			<td valign='middle' align='center' style='font-size:10px;height:25px;;background-color:#EBEBEB;'>-</td>
		</tr>";
	
	$item = $data[2];
	$ln = $data[1];

	$cek_count = "select count(*) as jum from ztb_wh_do_det where do_no='$do' and item_no='$item' and line_no='$ln'";
	$data_cek = oci_parse($connect, $cek_count);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);

	if(($dt_cek->JUM) > 0){

		$sql = "select b.gr_no, a.line_no, a.rack, a.item_no, b.tanggal, coalesce(b.pallet,0), a.qty as QTY, 
			(select sum(aa.qty) from ztb_wh_do_det aa where do_no= a.do_no and line_no= a.line_no and item_no= a.item_no) as total, c.description, a.do_no,
			a.do_no||','||a.line_no||','||a.item_no||','||coalesce(b.pallet,0)||','||a.qty||','||coalesce(a.sticker_id,'0')||','||a.rack as qr from ztb_wh_do_det a 
			left join ztb_wh_in_det b on a.sticker_id=b.id left join item c on a.item_no=c.item_no where a.do_no='$do' and item_no='$item' and a.line_no='$ln' order by c.description asc";
		$detail = oci_parse($connect, $sql);
		oci_execute($detail);
			$nourut_dtl = 1;
			$content .= "
				<tr>
					<td valign='middle' align='center' colspan=9 style='font-size:10px;height:100px;'>
					<table>";
					while ($dt=oci_fetch_array($detail)){
						$content .="
						<td style='font-size:10px;width:100px;border-right:0px solid #ffffff;border-top:0px solid #ffffff;border-left:0px solid #ffffff;border-bottom:0px solid #ffffff;'>
							<b>RACK: ".$dt[2]."</b><br/>
							<qrcode value='".$dt[10].','.$user_name."' ec='H' style=' border:none; width: 90px; background-color: white; color: black;'></qrcode><br/>
							<b>PALLET: ".$dt[5]."<br/>
							TOTAL: ".number_format($dt[6])."</b>
						</td>";
						$nourut_dtl++;
					}
					$content .= "
					<tr>
						<td style='font-size:10px;width:120px;border-right:0px solid #ffffff;border-top:0px solid #ffffff;border-left:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
					</tr>
					</table>		
					</td>
				</tr>";
	}
	$nourut++;
}

$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('outgoing.pdf');	
//echo  $content;
?>
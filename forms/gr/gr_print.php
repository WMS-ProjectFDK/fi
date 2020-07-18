<?php 
error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$gr = isset($_REQUEST['gr']) ? strval($_REQUEST['gr']) : '';

$sql = "select a.*,b.curr_short, c.company from gr_header a 
	inner join currency b on a.curr_code= b.curr_code
	inner join company c on a.supplier_code=c.company_code
	where a.gr_no='$gr'";
$data_header = sqlsrv_query($connect, strtoupper($sql));
$dt = sqlsrv_fetch_object($data_header);

$newGRDate = $dt->GR_DATE->format('d/m/Y');//$newDate->format('d/m/Y'); // for example

$qry = "select b.po_no, b.item_no, c.item, c.description, b.qty, b.u_price, e.curr_short, 
	b.amt_o, a.ex_rate , b.amt_l, d.unit_pl, a.amt_o as amt_o_tot, a.amt_l as amt_l_tot
	from gr_header a
	inner join gr_details b on a.gr_no= b.gr_no 
  	left join item c on b.item_no = c.item_no 
  	left join unit d on b.uom_q = d.unit_code
  	left join currency e on a.curr_code=e.curr_code
	where a.gr_no='$gr' 
	order by b.line_no asc";
$result = sqlsrv_query($connect, strtoupper($qry));

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
			<img src='../../images/logo-print4.png' alt='#' style='width:270px;height: 35px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:940px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
			<span style='width:70px;height:35px;margin-left:0px;'>
				<qrcode value='".$gr."' ec='Q' style=' border:none; width:40px;background-color: white; color: black;'></qrcode>
			</span>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		<h3 align='center'>GOODS RECEIVE / INVOICE CHECK LIST<br></h3>
		<table align='center'>
			<tr>
				<td style='border:0px solid #ffffff;'>SLIP NO.</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->GR_NO."</td>
				<td style='border:0px solid #ffffff;width: 50px;'></td>
				<td style='border:0px solid #ffffff;'>SUPPLIER</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>[".$dt->SUPPLIER_CODE."] ".$dt->COMPANY."</td>
				<td style='border:0px solid #ffffff;width: 50px;'></td>
				<td style='border:0px solid #ffffff;'>REMARK</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->REMARK."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;'>SLIP DATE</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$newGRDate."</td>
				<td style='border:0px solid #ffffff;width: 50px;'></td>
				<td style='border:0px solid #ffffff;'>PAYMENT TERMS</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->PDAYS." ".$dt->PDESC."</td>
			</tr>
		</table>
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
			<th valign='middle' align='center' style='font-size:10px;width:80px;height:25px;'>PO NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>ITEM NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:250px;height:25px;'>DESCRIPTION</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>CURRENCY</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>QTY</th>
			<th valign='middle' align='center' style='font-size:10px;width:50px;height:25px;'>UoM</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>PRICE</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>AMOUNT<br/>ORIGIN</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>EXCHANGE<br/>RATE</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>AMOUNT<br/>LOCAL</th>
		</tr>
	</thead>";

while ($data=sqlsrv_fetch_object($result)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:10px;width:30px;height:15px;'>".$nourut."</td>
			<td valign='middle' align='left' style='font-size:10px;width:80px;height:15px;'>".$data->PO_NO."</td>
			<td valign='middle' align='center' style='font-size:10px;width:70px;height:15px;'>".$data->ITEM_NO."</td>
			<td valign='middle' align='left' style='font-size:10px;width:250px;height:15px;'>".$data->ITEM."<br/>".$data->DESCRIPTION."</td>
			<td valign='middle' align='center' style='font-size:10px;width:70px;height:15px;'>".$data->CURR_SHORT."</td>
			<td valign='middle' align='right' style='font-size:10px;width:90px;height:15px;'>".number_format($data->QTY)."</td>
			<td valign='middle' align='center' style='font-size:10px;width:50px;height:15px;'>".$data->UNIT_PL."</td>
			<td valign='middle' align='right' style='font-size:10px;width:90px;height:15px;'>".number_format($data->U_PRICE)."</td>
			<td valign='middle' align='right' style='font-size:10px;width:90px;height:15px;'>".number_format($data->AMT_O,2)."</td>
			<td valign='middle' align='center' style='font-size:10px;width:70px;height:15px;'>".number_format($data->EX_RATE,6)."</td>
			<td valign='middle' align='right' style='font-size:10px;width:90px;height:15px;'>".number_format($data->AMT_L,2)."</td>
		</tr>";
		$amt_o_total = $data->AMT_O_TOT;
		$amt_l_total = $data->AMT_L_TOT;
	$nourut++;
}

$content .= "
			<tr>
				<td colspan=8 valign='middle' align='center' style='font-size:10px;height:25px;'><b><i>TOTAL</i></b></td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;'><b>".number_format($amt_o_total,2)."</b></td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;background-color:#EBEBEB;'><b></b></td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;'><b>".number_format($amt_l_total,2)."</b></td>
			</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('outgoing.pdf');	
//echo  $content;
?>
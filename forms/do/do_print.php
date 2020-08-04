<?php 
error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

$sql = "select SLIP_NO,SLIP_DATE,COMPANY_CODE,SLIP_TYPE,UPPER_ITEM_NO,UPPER_LEVEL_NO,UPPER_QTY,PERSON_CODE,DISPLAY_TYPE,APPROVAL_DATE,APPROVAL_PERSON_CODE,SECTION_CODE,WO_NO from mte_header where slip_no='$do'";
$data_header = sqlsrv_query($connect, strtoupper($sql));
$dt = sqlsrv_fetch_object($data_header);


$qry = "select SLIP_NO,LINE_NO,a.ITEM_NO,QTY,a.UOM_Q,a.COST_PROCESS_CODE,DATE_CODE,REMARK,a.REG_DATE,a.UPTO_DATE,i.ITEM_TYPE2,RACK_ADDR,WO_NO,item,description,unit_pl,cost_process_name
from mte_details a
	left join item i on a.item_no=i.item_no
	left join unit u on i.uom_q=u.unit_code
	left join costprocess co on a.COST_PROCESS_CODE=co.cost_process_code
	where slip_no='$do' order by line_no asc";
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
			<img src='../images/logo-print4.png' alt='#' style='width:270px;height: 35px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:940px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
			<span style='width:70px;height:35px;margin-left:0px;'>
				<qrcode value='".$do."' ec='Q' style=' border:none; width:40px;background-color: white; color: black;'></qrcode>
			</span>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		<h3 align='center'>MATERIAL TRANSACTION<br></h3>
		<table align='center'>
			<tr>
				<td style='border:0px solid #ffffff;'>SLIP NO.</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->SLIP_NO."</td>
				<td style='border:0px solid #ffffff;width: 50px;'></td>
				<td style='border:0px solid #ffffff;'>SLIP DATE</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->SLIP_DATE."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;'>SLIP TYPE</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->SLIP_TYPE."</td>
			</tr>
		</table>
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>ITEM NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:300px;height:25px;'>DESCRIPTION</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>QTY</th>
			<th valign='middle' align='center' style='font-size:10px;width:50px;height:25px;'>UoM</th>
			<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>COST PROCCESS CODE</th>
			<th valign='middle' align='center' style='font-size:10px;width:250px;height:25px;'>COST PROCCESS</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>DATE<br/>CODE</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>REMARK</th>
		</tr>
	</thead>";

while ($data=sqlsrv_fetch_object($result)){
	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$nourut."</td>
			<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$data->ITEM_NO."</td>
			<td valign='middle' align='left' style='font-size:10px;height:15px;'>".$data->ITEM."<br/>".$data->DESCRIPTION."</td>
			<td valign='middle' align='right' style='font-size:10px;height:15px;'>".number_format($data->QTY)."</td>
			<td valign='middle' align='center' style='font-size:10px;height:15px;'>".$data->UNIT_PL."</td>
			<td valign='middle' align='right' style='font-size:10px;height:15px;'>".$data->COST_PROCESS_CODE."</td>
			<td valign='middle' align='left' style='font-size:10px;height:15px;'>".$data->COST_PROCESS_NAME."</td>
			<td valign='middle' align='left' style='font-size:10px;height:15px;'>".$data->DATE_CODE."</td>
			<td valign='middle' align='left' style='font-size:10px;height:15px;'>".$data->REMARK."</td>
		</tr>";
	$nourut++;
}

$content .= "
		</table>
	</div>
	<div style='position: absolute; width:100%; bottom: 15; font-size:12px;' align='left'>
		<table style='width:100%;'>
			<tr>
				<td style='width:25%; border:5px solid #ffffff;'>Dipersiapkan</td>
				<td style='width:25%; border:5px solid #ffffff;'>Disetujui</td>
				<td style='width:25%; border:5px solid #ffffff;'>Dipersiapkan</td>
				<td style='width:25%; border:5px solid #ffffff;'>Disetujui</td>
			</tr>
			<tr>
				<td valign='bottom' style='width:25%;height:90px;border:5px solid #ffffff;'>Adm Prod.</td>
				<td valign='bottom' style='width:25%;height:90px;border:5px solid #ffffff;'>Staff Prod.</td>
				<td valign='bottom' style='width:25%;height:90px;border:5px solid #ffffff;'>WH Staff</td>
				<td valign='bottom' style='width:25%;height:90px;border:5px solid #ffffff;'>WH SPV</td>
			</tr>
		</table>
		<div style='width:50%;text-align:left; font-size:9px;'>
			<p style='font-size:12px;'>Asli: Accounting</p>
		</div>
	</div>
</page>";


require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output($do.'.pdf');	
//echo  $content;
?>
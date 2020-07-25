<?php 
// error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$p = intval(date('m'));
$y = date('Y');

/*
$plusDay = "+".intval(75)." day";
$tambah_date = strtotime($plusDay,strtotime('2017-03-03'));
$due_date = date('Y-m-d',$tambah_date);
*/

$result = array();

$di = isset($_REQUEST['di']) ? strval($_REQUEST['di']) : '';

if(substr($di, 0,5) == 'DIMRP'){
	$mrp = "Y";
}else{
	$mrp = "";
}

$qry_company = "select a.*, b.*, CAST(a.di_date as varchar(10)) as di_dt 
		from di_header a 
		inner join company b on a.shipto_code=b.company_code 
		where a.di_no='$di'";
$data_company = sqlsrv_query($connect, strtoupper($qry_company));
$dt_company = sqlsrv_fetch_object($data_company);

if(date('D')=='Fri'){
	$hari = "Monday";
	$tambah_date = strtotime('+3 day',strtotime($dt_company->DI_DT));
	$del_date = date('Y-m-d',$tambah_date);
	//$del_date = "2017-03-29";
}else{
	$hari = "Tomorrow";
	$tambah_date = strtotime('+1 day',strtotime($dt_company->DI_DT));
	$del_date = date('Y-m-d',$tambah_date);
	//$del_date = "2017-03-29";
}

if($mrp == ""){
	$qry_item = "select distinct a.po_no, a.item_no, b.item, b.description, 
		case d.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
		a.qty, d.bundle_qty, a.data_date from di_details a 
		inner join item b on a.item_no=b.item_no 
		inner join unit c on a.uom_q=c.unit_code
		inner join ztb_safety_stock d on a.item_no=d.item_no
		where a.di_no='$di' and d.period=$p and d.year='$y'
		order by b.description asc";
}else{
	$qry_item = "select distinct a.po_no, a.item_no, b.item, b.description, 
		case d.sts_bundle when 'Y' then 'BUNDLE' else c.unit end as unit,
		a.qty, d.bundle_qty, a.data_date from di_details a 
		inner join item b on a.item_no=b.item_no 
		inner join unit c on a.uom_q=c.unit_code
		left join ztb_safety_stock d on a.item_no=d.item_no
		where a.di_no='$di'
		order by b.description asc";
}

$data_item = sqlsrv_query($connect, strtoupper($qry_item));

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
			<img src='../../images/logo-print4.png' alt='#' style='width:210px;height: 50px'/>
		</div>	

		<div style='margin-top:0;margin-left:640px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>	
			<qrcode value='".$di."' ec='Q' style=' border:none; width:40px;background-color: white; color: black;'></qrcode>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	<h3 align='center'>DELIVERY INSTRUCTION</h3>
	<p align='center'>No. ".$di."</p>
	
	<table>
		<tr>
			<td style='border:5px solid #ffffff;' valign='top'>TO</td>
			<td style='border:5px solid #ffffff;' valign='top'>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style='border:5px solid #ffffff;'><div style='font-size:14px;'><b>[".$dt_company->SHIPTO_CODE."] ".$dt_company->COMPANY."</b></div>
				<p>".$dt_company->ADDRESS1." ".$dt_company->ADDRESS2." ".$dt_company->ADDRESS3." ".$dt_company->ADDRESS4."</p>
			</td>
		</tr>
		<tr>
			<td style='border:5px solid #ffffff;' valign='top' colspan=3></td>
		</tr>
		<tr>
			<td style='border:5px solid #ffffff;'>FAX</td>
			<td style='border:5px solid #ffffff;'>:</td>
			<td style='border:5px solid #ffffff;'>".$dt_company->FAX_NO."</td>
		</tr>
		<tr>
			<td style='border:5px solid #ffffff;'>ATTN</td>
			<td style='border:5px solid #ffffff;'>:</td>
			<td style='border:5px solid #ffffff;'>".$dt_company->ATTN."</td>
		</tr>
	</table>
	
	<div style='margin-top:320px;position:absolute;'>	
		<p>Dear Sir / Madam <br/>
			Please deliver our buffer stock to our warehouse as follow :<br/>
		</p>
		<table>";
$nourut = 1;
$content .= "
			<thead>
				<tr>
					<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
					<th valign='middle' align='center' style='font-size:10px;width:100px;height:25px;'>PO NO.</th>
					<th valign='middle' align='center' style='font-size:10px;width:90px;height:25px;'>MATERIAL NO.</th>
					<th valign='middle' align='center' style='font-size:10px;width:300px;height:25px;'>MATERIAL NAME</th>
					<th valign='middle' align='center' style='font-size:10px;width:100px;height:25px;'>QTY</th>
					<th valign='middle' align='center' style='font-size:10px;width:80px;height:25px;'>DELIVERY DATE</th>
				</tr>
			</thead>";
		while($dt_item = sqlsrv_fetch_object($data_item)){
			if($dt_item->BUNDLE_QTY==1){
				$q_bdl='';
			}elseif(is_null($dt_item->BUNDLE_QTY)){
				$q_bdl='';
			}else{
				$q_bdl="<br/>[@ : ".$dt_item->BUNDLE_QTY."]";
			}
$content.="		<tr>
					<td valign='middle' align='center'>$nourut</td>
					<td valign='middle'>&nbsp;".$dt_item->PO_NO."</td>
					<td valign='middle' align='center'>".$dt_item->ITEM_NO."</td>
					<td valign='middle'>&nbsp;<div>".$dt_item->ITEM."</div>&nbsp;".$dt_item->DESCRIPTION."</td>
					<td valign='middle' align='right'>".number_format($dt_item->QTY)." ".$dt_item->UNIT."&nbsp;".$q_bdl."</td>
					<td valign='middle' align='center'>".$del_date."</td>
				</tr>
			";			
			$nourut++;
		}
$content .= "
		</table>
		<p style='margin-left:550px;'>yours Faithfully<br/><br/><br/><br/><br/>".$dt_company->SEC."</p>
	</div>
</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('delivery_instruction_'.$di.'.pdf');	
//echo  $content;
?>
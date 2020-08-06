<?php 
error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$result = array();

$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';
$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';
$close_cargo = isset($_REQUEST['close_cargo']) ? strval($_REQUEST['close_cargo']) : '';
$jns_export = isset($_REQUEST['jns_export']) ? strval($_REQUEST['jns_export']) : '';
$j = isset($_REQUEST['jam']) ? strval($_REQUEST['jam']) : '';
$menit = isset($_REQUEST['menit']) ? strval($_REQUEST['menit']) : '';
$note = isset($_REQUEST['note']) ? strval($_REQUEST['note']) : '';

if ($j < 10){
	$jam = "0".$j;
}else{
	$jam = $j;
}

if ($menit < 10){
	$mnt = "0".$menit;
}else{
	$mnt = $menit;
}

$newDate = date("l, d F Y", strtotime($close_cargo));
if ($jns_export == 'lokal'){
	$head = "<h3 align='center'>PEMBERITAHUAN PENGIRIMAN BARANG LOKAL<br></h3>";
	$notes = "<b>MOHON CARGO AGAR SEGERA DI LOADING, DAN DI INFOKAN KE CS JIKA SUDAH SELESAI.<br/>KARENA PROSES PEMBUATAN BC DILAKUKAN SETELAH PROSES LOADING SELESAI</b>";
}elseif ($jns_export == 'export'){
	$head = "<h3 align='center'>PEMBERITAHUAN PENGIRIMAN BARANG EKSPORT<br></h3>";
	$notes = "<b>".strtoupper($note)."</b>";
}

$sql = "select distinct a.do_date, a.customer_code, b.company, c.booking_no, c.forwarder_code, d.forwarder, e.emkl_name, e.final_dest,
	e.consignee_name, a.person_code, f.person, g.net_uom, g.gross_uom, h.unit_pl,
	zts.net as nw, zts.gross as gw, zts.msm as cbm,  
	a.description, c.cargo_type1, cm.description as desc_method1, c.cargo_size1, cs.description as desc_size1, c.transport_type, c.cargo_qty1,
	nvl(c.cargo_type2,0) cargo_type2, cm2.description as desc_method2, 
  	nvl(c.cargo_size2,0) cargo_size2, cs2.description as desc_size2, 
  	nvl(c.cargo_qty2,0) cargo_qty2
	from do_header a
	left join company b on a.customer_code = b.company_code
	left join FORWARDER_LETTER c on a.do_no = c.do_no
	left join cargo_method cm on c.cargo_type1 = cm.method_type
	left join cargo_size cs on c.cargo_size1 = cs.size_type
	left join cargo_method cm2 on c.cargo_type2 = cm2.method_type
	left join cargo_size cs2 on c.cargo_size2 = cs2.size_type
	left join forwarder d on c.forwarder_code = d.forwarder_code
	left join si_header e on a.si_no = e.si_no
	left join person f on a.person_code = f.person_code
	left join do_pl_header g on a.do_no = g.do_no
	left join unit h on g.net_uom = h.unit_code
	left join answer ans on a.si_no=ans.si_no and a.description = ans.crs_remark
  	left join (select ppbe_no,sum(net) net, sum(gross) gross, sum(msm) msm from ztb_shipping_detail group by ppbe_no) zts 
    on ans.crs_remark = zts.ppbe_no
	where a.do_no='$do'";
$data_header = sqlsrv_query($connect, strtoupper($sql));
$dt = sqlsrv_fetch_object($data_header);

$qry = "select a.line_no, a.item_no, c.description, zz.qty, b.customer_po_no, b.work_no, 
	ceil((zz.qty /  (zi.pallet_pcs / zi.pallet_ctn)))  case_total, ceil(zz.pallet) as pallet, ceil(zz.pallet)* zi.pallet_ctn carton, zs.box_pcs,
	floor(zz.pallet) full_pallet,carton_not_full ctn_terakir,'(' || CONTAINER_NO || ') ' || CONTAINERS CONTAINER_NO  
	from do_details a
	inner join answer b on a.answer_no1 = b.answer_no
	inner join item c on a.item_no = c.item_no
	inner join do_pl_header d on a.do_no=d.do_no and a.line_no=d.pl_line_no
  inner join ztb_shipping_detail zz on zz.ppbe_no = b.crs_remark and b.item_no= zz.item_no and  zz.wo_no = b.work_no and zz.answer_no = b.answer_no
  inner join ztb_item zi on zi.item_no = zz.item_no 
	left join ztb_shipping_ins zs on a.answer_no1 = zs.answer_no
	where a.do_no='$do' order by CONTAINER_NO,a.line_no asc";
$result = sqlsrv_query($connect, strtoupper($qry));

$date=date("d M y / H:i:s",time());

if($dt->TRANSPORT_TYPE == 2){
	if ($dt->CARGO_TYPE1 == 2 OR $dt->CARGO_TYPE2 == 2){
		$shipp_method1 = $dt->DESC_METHOD1." ( ".$dt->CARGO_QTY1." x ".$dt->DESC_SIZE1." ) ";
		$shipp_method2 = $dt->DESC_METHOD2." ( ".$dt->CARGO_QTY2." x ".$dt->DESC_SIZE2." ) ";
	}else{
		$shipp_method1 = $dt->DESC_METHOD1;
		$shipp_method2 = $dt->DESC_METHOD2;
	}
}elseif($dt->TRANSPORT_TYPE == 1){
	$mthd = "AIR SHIPMENT";
}elseif($dt->TRANSPORT_TYPE == 3){
	$mthd = "TRUCK SHIPMENT";
}

if($dt->TRANSPORT_TYPE == 2){
	if(is_null($dt->CARGO_TYPE2)) {
		$shipp1 = $shipp_method1;
		$shipp2 = '';
	}else{
		$shipp1= $shipp_method1;
		$shipp2= $shipp_method2;
	}
}else{
	$shipp1 = $mthd;
	$shipp2 = '';
}

/*$exp_ppbe = explode('/', $dt->DESCRIPTION);
if ($exp_ppbe[1] =='W'){
	$nm = 'WISNU';
}elseif ($exp_ppbe[1] == 'A'){
	$nm = 'AGUNG';
}elseif ($exp_ppbe[1] == 'D'){
	$nm = 'DEWI';
}*/

//$ppbe_no = $exp_ppbe[0].'/'.$nm;

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
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
		<div style='width:50%;text-align:left; font-size:9px;margin-bottom:100%;'>
			<p style='font-size:12px;'>FM-19-PEI-004</p>
		</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		".$head."
		<table>
			<tr>
				<td style='border:0px solid #ffffff;'>ATTN / Kepada</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>Warehouse Section</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;width: 50px;'>NO.</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;width: 100px'>".$dt->DESCRIPTION."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;width: 50px;'>FROM</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;width:'> CS Section</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;width: 50px;'>INV NO.</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;width: 100px'>".$do."</td>
			</tr>
			<tr><td style='border:0px solid #ffffff;height:10px;'></td></tr>
			<tr>
				<td style='border:0px solid #ffffff;width: 50px;'>TUJUAN</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->FINAL_DEST."</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;'>EMKL</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->EMKL_NAME."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;'>CONSIGNEE</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->CONSIGNEE_NAME."</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;'>FORWARDING</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->FORWARDER."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;'>SHIPMENT METHOD</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$shipp1."</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;'>NO. BOOKING/DO(FCL)</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->BOOKING_NO."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;'></td>
				<td style='border:0px solid #ffffff;'></td>
				<td style='border:0px solid #ffffff;'>".$shipp2."</td>
				<td style='border:0px solid #ffffff;width: 200px'></td>
				<td style='border:0px solid #ffffff;'>TGL EXPORT</td>
				<td style='border:0px solid #ffffff;'>:</td>
				<td style='border:0px solid #ffffff;'>".$dt->DO_DATE."</td>
			</tr>
		</table>
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:10px;width:30px;height:20px;'>NO</th>
			<th valign='middle' align='center' style='font-size:10px;width:80px;height:20px;'>PO NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:140px;height:20px;'>WO NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:50px;height:20px;'>ITEM<br>NO.</th>
			<th valign='middle' align='center' style='font-size:10px;width:200px;height:20px;'>DESCRIPTION</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>ORDER<BR/>QTY(PCS)</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>TOTAL<br/>CTN</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>TOTAL<br/>PLT</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>FULL<br/>PLT</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>CTN<br/>TERAKHIR</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>QTY/PALLET<br/>(CARTON)</th>
			<th valign='middle' align='center' style='font-size:10px;width:70px;height:20px;'>REMARKS</th>
		</tr>
	</thead>";
$s=0;
while ($s <= $dt->CARGO_QTY1) {
	while ($data=oci_fetch_object($result)){
		if($data->CARTON < $data->BOX_PCS){
			$full_plt = "-" ;
			$ctn_last = $data->CARTON;
		}elseif($data->CARTON > $data->BOX_PCS){
			$full_plt = floor($data->CARTON / $data->BOX_PCS);
			$ctn_last = $data->CARTON-($full_plt*$data->BOX_PCS);
		}else{
			$full_plt = 1 ;
			$ctn_last = $data->BOX_PCS;
		}

		if ($shipp1 == 'LCL' OR $shipp1 == 'AIR SHIPMENT' OR $shipp1 == 'TRUCK SHIPMENT'){
			$con_no = '';
		}else{
			$con_no = $data->CONTAINER_NO;	
		}
		
		$content .= "
			<tr>
				<td valign='middle' align='center' style='font-size:9px;width:30px;height:10px;'>".$nourut."</td>
				<td valign='middle' align='left' style='font-size:9px;width:80px;height:10px;'>".$data->CUSTOMER_PO_NO."</td>
				<td valign='middle' align='center' style='font-size:9px;width:140px;height:10px;'>".$data->WORK_NO."</td>
				<td valign='middle' align='center' style='font-size:9px;width:50px;height:10px;'>".$data->ITEM_NO."</td>
				<td valign='middle' align='left' style='font-size:9px;width:200px;height:10px;'>".$data->DESCRIPTION."</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->QTY)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->CASE_TOTAL)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->PALLET)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->FULL_PALLET)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->CTN_TERAKIR)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:9px;width:70px;height:10px;'>".number_format($data->BOX_PCS)."&nbsp;</td>
				<td valign='middle' align='center' style='font-size:9px;width:70px;height:10px;'>".$con_no."</td>
			</tr>";	

		$order_total += $data->QTY;
		$case_total += $data->CASE_TOTAL;
		$plt_total += $data->PALLET;
		$nourut++;
	}
	$s++;
}

$content .= "
			<tr>
				<td colspan=5 valign='middle' align='center' style='font-size:10px;height:15px;'><b></b></td>
				<td valign='middle' align='right' style='font-size:10px;height:15px;'><b>".number_format($order_total)."&nbsp;</b></td>
				<td valign='middle' align='right' style='font-size:10px;height:15px;'><b>".number_format($case_total)."&nbsp;</b></td>
				<td valign='middle' align='right' style='font-size:10px;height:15px;'><b>".number_format($plt_total)."&nbsp;</b></td>
				<td colspan=4 valign='middle' align='center' style='font-size:10px;height:15px;'><b></b></td>
			</tr>
		</table>
		<p align='center' style='font-size:12px;'><b>CLOSING CARGO : ".$newDate."&nbsp;&nbsp;".$jam.":".$mnt."</b></p>
		<table border=0>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'>GW</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>:</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>".number_format($dt->GW,2)."&nbsp;".$dt->UNIT_PL."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'>NW</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>:</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>".number_format($dt->NW,2)."&nbsp;".$dt->UNIT_PL."</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'>CBM</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>:</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>".number_format($dt->CBM,3)."&nbsp;M3</td>
			</tr>
			<tr><td style='border:0px solid #ffffff;height:10px;'></td></tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'><b>NOTE</b></td>
				<td style='border:0px solid #ffffff;font-size:11px;'>:</td>
				<td style='border:0px solid #ffffff;font-size:11px;'>
					".$notes."
				</td>
			</tr>
			<tr><td style='border:0px solid #ffffff;height:10px;'></td></tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>DIBUAT OLEH</td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>DISETUJUI</td>
			</tr>
			<tr><td style='border:0px solid #ffffff;height:50px;'></td></tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>".strtoupper($dt->PERSON)."</td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>AGUSMAN SURYA</td>
			</tr>
			<tr>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;'></td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>(CS STAFF)</td>
				<td style='border:0px solid #ffffff;font-size:11px;width: 250px;' align='center'>(SCM SENIOR MANAGER)</td>
			</tr>
		</table>


	</div>
</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('outgoing.pdf');	
//echo  $content;
?>
<?php 
error_reporting(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$date=date("d M y / H:i:s",time());
$result = array();

$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';

$exp_ppbe = explode('/', $ppbe);
if ($exp_ppbe[1] =='W'){
	$nm = 'WISNU';
}elseif ($exp_ppbe[1] == 'A'){
	$nm = 'AGUNG';
}elseif ($exp_ppbe[1] == 'D'){
	$nm = 'DEWI';
}else{
    $nm = 'ADMIN';
}

$sql_h = "select distinct dbo.LIST_COLLECT(a.SI_NO, ', ') as SI_NO, a.crs_remark, b.do_no, cast(b.do_date as varchar(10)) as do_date, c.booking_no,
replace(b.ship_name,char(10),'<br>') as vessel, b.final_destination, cast(b.etd as vachar(10)) as etd, s.forwarder_name
from answer a
left join do_header b on a.si_no = b.si_no
left join forwarder_letter c on b.do_no = c.do_no
left join si_header s on a.si_no = s.si_no
	where a.crs_remark = '$ppbe'";
$data_h = sqlsrv_query($connect, strtoupper($sql_h));
$dt_h = sqlsrv_fetch_object($data_h);

$sql = "select a.ppbe_no, dbo.LIST_COLLECT(b.SI_NO, ', ') as SI_NO, a.wo_no, a.item_no, it.description,
	a.qty, a.net, a.gross, a.msm, a.pallet, a.container_no + char(10) + '(' + a.containers + ')' as container_no, zti.pallet_pcs, zti.pallet_ctn, 
	a.qty/(zti.pallet_pcs/zti.pallet_ctn) as carton_qty, a.tw, b.customer_po_no, a.enr
	from ztb_shipping_detail a
	left outer join answer b on a.wo_no = b.work_no and a.ppbe_no = b.crs_remark and a.answer_no = b.answer_no
	inner join item it on a.item_no = it.item_no
	left join ztb_item zti on a.item_no = zti.item_no
	where a.ppbe_no='$ppbe'
	order by a.container_no asc, b.so_no asc, b.so_line_no asc";
$data = sqlsrv_query($connect, strtoupper($sql));

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
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>page [[page_cu]] of [[page_nb]]</div>
    </page_footer>
	
	<div style='margin-top:30px;position:absolute;'>
		<h3 align='center'>BREAKDOWN CONTAINER<br></h3>
		".$head."
		<table>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;'>ATTN</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$dt_h->FORWARDER_NAME."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>BOOKING NO.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$dt_h->BOOKING_NO."</td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;width: 50px;'>FROM</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$nm." - FDK INDONESIA</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>INVOICE NO.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$dt_h->DO_NO."</td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;width: 50px;'>RE</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>CONTAINER BREAK DOWN DESTINED TO ".$dt_h->FINAL_DESTINATION."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>PPBE NO.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$ppbe."</td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;'>VESSEL</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$dt_h->VESSEL."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>FDKA INV. NO.</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'></td>
			</tr>
			<tr>
				<td style='font-size:11px;border:0px solid #ffffff;'>DATE</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;width: 350px;'>".$dt_h->DO_DATE."</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>ETD</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>:</td>
				<td style='font-size:11px;border:0px solid #ffffff;'>".$dt_h->ETD."</td>
			</tr>
		</table>
		<table align='center'>";
$nourut = 1;
$content .= "
	<thead>
		<tr>
			<th valign='middle' align='center' style='font-size:11px;width:65px;height:20px;'>FI PO NO.</th>
			<th valign='middle' align='center' style='font-size:11px;width:200px;height:20px;'>FI ITEM NAME</th>
			<th valign='middle' align='center' style='font-size:11px;width:40px;height:20px;'> FI ITEM<br>NO.</th>
			<th valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>ENR PART</th>
			<th valign='middle' align='center' style='font-size:11px;width:70px;height:20px;'>QTY</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>CARTON<br/>QTY</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>GW<br/>(KGS)</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>NW<br/>(KGS)</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>MSM<br/>(CBM)</th>
			<th valign='middle' align='center' style='font-size:11px;width:40px;height:20px;'>PALLET<br/>QTY</th>
			<th valign='middle' align='center' style='font-size:11px;width:200px;height:20px;'>CONTAINER<br/>NO.</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>TARE WEIGHT<br/>(KGS)</th>
			<th valign='middle' align='center' style='font-size:11px;width:50px;height:20px;'>VGM<br/>(KGS)</th>
		</tr>
	</thead>";
$cont = '';		$no=1;
$grand_tot_qty = 0;			$tot_qty = 0;			
$grand_tot_carton = 0;		$tot_carton = 0;		
$grand_tot_gw  = 0;			$tot_gw = 0;
$grand_tot_nw = 0;			$tot_nw = 0;	
$grand_tot_msm = 0;			$tot_msm = 0;
$grand_tot_plt = 0;			$tot_plt = 0;		
$grand_tw = 0;				$tw = 0;

while ($dt=sqlsrv_fetch_object($data)){
	$container = $dt->CONTAINER_NO;
	$qty = $dt->QTY;						$nw = $dt->NET;
	$carton = $dt->CARTON_QTY;				$msm = number_format($dt->MSM,3);
	$gw = $dt->GROSS;						$plt = ceil($dt->PALLET);

	if($no == 1){
		$tot_qty += $qty;			$tot_nw += $nw;
		$tot_carton += $carton;		$tot_msm += $msm;
		$tot_gw += $gw;				$tot_plt += $plt;
		$tw = $dt->TW;
	}else{
		if ($cont == $container){
			$tot_qty += $qty;			$tot_nw += $nw;
			$tot_carton += $carton;		$tot_msm += $msm;
			$tot_gw += $gw;				$tot_plt += $plt;
			$tw = $dt->TW;
		}else{
			$grand_tot_qty += $tot_qty;
			$grand_tot_carton += $tot_carton;
			$grand_tot_gw  += $tot_gw;
			$grand_tot_nw += $tot_nw;
			$grand_tot_msm += $tot_msm;
			$grand_tot_plt += $tot_plt;
			$grand_tw += $tw;

			$content .= "
				<tr>
					<td colspan=2 valign='middle' align='center' style=';border-left :0px solid #ffffff;'></td>
					<td colspan=2 valign='middle' align='center' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b><i>TOTAL</i></b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_qty)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_carton)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_gw,2)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_nw,2)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_msm,3)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_plt)."</b></td>
					<td valign='middle' align='right' style='border-left:0px solid #ffffff;'><b></b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tw)."</b></td>
					<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_gw+$tw,2)."</b></td>
				</tr>";

			$tot_qty = 0;			$tot_nw = 0;
			$tot_carton = 0;		$tot_msm = 0;
			$tot_gw = 0;			$tot_plt = 0;	
			$tw=0;

			$tot_qty += $qty;			$tot_nw += $nw;
			$tot_carton += $carton;		$tot_msm += $msm;
			$tot_gw += $gw;				$tot_plt += $plt;	
			$tw = $dt->TW;
		}	
	}

	if ($cont == $container){
		$cnt = '';
	}else{
		$cnt = $container;
	}

	$content .= "
		<tr>
			<td valign='middle' align='center' style='font-size:9px;width:65px;'>".$dt->CUSTOMER_PO_NO."</td>
			<td valign='middle' align='left' style='font-size:9px;width:200px;'>".$dt->DESCRIPTION."</td>
			<td valign='middle' align='center' style='font-size:9px;width:40px;'>".$dt->ITEM_NO."</td>
			<td valign='middle' align='center' style='font-size:9px;width:70px;'>".$dt->ENR."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->QTY)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->CARTON_QTY)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->GROSS,2)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->NET,2)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->MSM,3)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:40px;'>".ceil($dt->PALLET)."</td>
			<td valign='middle' align='left'  style='font-size:9px;width:200px;'>".$cnt."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->TW)."</td>
			<td valign='middle' align='right' style='font-size:9px;width:50px;'>".number_format($dt->GROSS+$dt->TW,2)."</td>
		</tr>";
	$cont = $container;
	$no++;
}

$grand_tot_qty += $tot_qty;
$grand_tot_carton += $tot_carton;
$grand_tot_gw  += $tot_gw;
$grand_tot_nw += $tot_nw;
$grand_tot_msm += $tot_msm;
$grand_tot_plt += $tot_plt;
$grand_tw += $tw;

$content .= "
		<tr>
			<td colspan=2 valign='middle' align='center' style='border-left:0px solid #ffffff;border-bottom:0px solid #ffffff;'></td>
			<td colspan=2 valign='middle' align='center' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b><i>TOTAL</i></b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_qty)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_carton)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_gw,2)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_nw,2)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_msm,3)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_plt)."</b></td>
			<td valign='middle' align='right' style='border-bottom:0px solid #ffffff;'><b></b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tw)."</b></td>
			<td valign='middle' align='right' style='background-color:#F4F4F4;font-size:11px;height:10px;'><b>".number_format($tot_gw+$tw,2)."</b></td>
		</tr>
		<tr>
			<td colspan=13 valign='middle' align='center' style='border:0px solid #ffffff;height:7px;'></td>
		</tr>
		<tr>
		  	<td colspan=2 valign='middle' align='center' style='background-color:#ffffff;border:0px solid #ffffff;'></td>
		  	<td colspan=2 valign='middle' align='center' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b><i>TOTAL ALL</i></b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_qty)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_carton)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_gw,2)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_nw,2)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_msm,3)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_plt)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#ffffff;border:0px solid #ffffff;'><b></b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tw)."&nbsp;</b></td>
		  	<td valign='middle' align='right' style='background-color:#BCBCBC;font-size:12px;height:25px;'><b>".number_format($grand_tot_gw + $grand_tw,2)."&nbsp;</b></td>
		</tr>
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('breakdown_'.$ppbe.'.pdf');
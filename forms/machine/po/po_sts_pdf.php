<?php 
//error_reporting(0);
ini_set('memory_limit', '-1');
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];


$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
$supplier = isset($_REQUEST['supplier']) ? strval($_REQUEST['supplier']) : '';
$supplier_nm = isset($_REQUEST['supplier_nm']) ? strval($_REQUEST['supplier_nm']) : '';
$ck_supplier = isset($_REQUEST['ck_supplier']) ? strval($_REQUEST['ck_supplier']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$txt_item_name = isset($_REQUEST['txt_item_name']) ? strval($_REQUEST['txt_item_name']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$cmb_po = isset($_REQUEST['cmb_po']) ? strval($_REQUEST['cmb_po']) : '';
$ck_po = isset($_REQUEST['ck_po']) ? strval($_REQUEST['ck_po']) : '';
$date_eta = isset($_REQUEST['date_eta']) ? strval($_REQUEST['date_eta']) : '';
$ck_eta = isset($_REQUEST['ck_eta']) ? strval($_REQUEST['ck_eta']) : '';

if ($ck_date != "true"){
    $date_po = "h.po_date between '$date_awal' and '$date_akhir' and ";
    $period = $date_awal." TO ".$date_akhir;
}else{
    $date_po = "";
    $period = "ALL";
}   

if ($ck_supplier != "true"){
    $supp = "h.supplier_code = '$supplier' and ";
    $spp = $supplier_nm;
}else{
    $supp = "";
    $spp = "ALL";
}

if ($ck_item_no != "true"){
    $item_no = "d.item_no='$cmb_item_no' and ";
    $item = $cmb_item_no."-".$txt_item_name;
}else{
    $item_no = "";
    $item = "ALL";
}

if ($ck_po != "true"){
    $po = "d.po_no='$cmb_po' and ";
    $pono = $cmb_po;
}else{
    $po = "";
    $pono = "ALL";
}   

if ($ck_eta != "true"){
    $eta = "d.eta = '$date_eta' and ";
    $e = $date_eta;
}else{
    $eta = "";
    $e = "ALL";
}

$where ="where $supp $item_no $po $eta $date_po d.item_no is not null";

$qry = "select h.supplier_code,company, d.po_no, cast(h.po_date as varchar(10)) as po_date, d.item_no, itm.description, itm.item, line_no,cast(d.eta as varchar(10)) as eta, d.qty, 
d.gr_qty,gg.qty as Receipt_Qty, gg.gr_Date, c.accpac_company_code, cast(d.eta as varchar(10)) as etad, 
cast(gg.gr_Date as varchar(10)) as grd, datediff(d,d.eta , gg.gr_date) as diff from po_header h inner join po_details d on h.po_no = d.po_no 
left join company c on h.supplier_code = c.company_code and c.company_type = 3 
left outer join 
(select gr_date, gs.po_no, gs.po_line_no, gs.qty from gr_details gs 
    inner join gr_header gh on gs.gr_no = gh.gr_no) 
gg on gg.po_no = d.po_no and gg.po_line_no = d.line_no 
left join item itm on d.item_no= itm.item_no 
    $where
	order by h.po_no asc, line_no asc";
//echo $qry;	
$result = sqlsrv_query($connect, $qry);

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
			<img src='../../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:925px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
			page [[page_cu]] of [[page_nb]]
		</div>
    </page_footer>
	
	<div style='margin-top:20px;position:absolute;'>
		<h3 align='center'>PURCHASE ORDER STATUS<br></h3>
		<table align='left' style='width: 100%;margin-left:10px;'>
			<tr>
			  <td style='border:0px solid #ffffff;'>Period</td>
              <td style='border:0px solid #ffffff;'>:</td>
              <td style='border:0px solid #ffffff;width:300px;'>".$period."</td>
              <td style='border:0px solid #ffffff;width:50px;'></td>
              <td style='border:0px solid #ffffff;'>ITEM</td>
              <td style='border:0px solid #ffffff;'>:</td>
              <td style='border:0px solid #ffffff;width:300px;'>".$item."</td>
			</tr>
			<tr>
			  <td style='border:0px solid #ffffff;'>PO NO.</td>
              <td style='border:0px solid #ffffff;'>:</td>
              <td style='border:0px solid #ffffff;width:300px;'>".$pono."</td>
              <td style='border:0px solid #ffffff;width:50px;'></td>
              <td style='border:0px solid #ffffff;'>Supplier</td>
              <td style='border:0px solid #ffffff;'>:</td>
              <td style='border:0px solid #ffffff;width:300px;'>".$spp."</td>
              <td style='border:0px solid #ffffff;width:50px;'></td>
              <td style='border:0px solid #ffffff;'>E T A</td>
              <td style='border:0px solid #ffffff;'>:</td>
              <td style='border:0px solid #ffffff;width:100px;'>".$e."</td>
			</tr>
		</table>
		<table align='center'>";
$nourut = 1;
$content .= "
			<thead>
				<tr>
					<th valign='middle' align='center' style='font-size:10px;width:30px;height:25px;'>NO</th>
					<th valign='middle' align='center' style='font-size:10px;width:80px;height:25px;'>PO NO.</th>
					<th valign='middle' align='center' style='font-size:10px;width:80px;height:25px;'>PO DATE</th>
					<th valign='middle' align='center' style='font-size:10px;width:200px;height:25px;'>SUPPLIER</th>
					<th valign='middle' align='center' style='font-size:10px;width:200px;height:25px;'>ITEM DESCRIPTION<br/>ITEM NO.</th>
					<th valign='middle' align='center' style='font-size:10px;width:50px;height:25px;'>LINE</th>
					<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>QTY</th>
					<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>GR QTY</th>
					<th valign='middle' align='center' style='font-size:10px;width:70px;height:25px;'>RECEIPT<br/>QTY</th>
					<th valign='middle' align='center' style='font-size:10px;width:60px;height:25px;'>E.T.A</th>
					<th valign='middle' align='center' style='font-size:10px;width:60px;height:25px;'>GR DATE</th>
					<th valign='middle' align='center' style='font-size:10px;width:60px;height:25px;'>DIFF</th>
				</tr>
			</thead>";
while ($data=sqlsrv_fetch_object($result)){
	$po = $data->po_no;
    $eta = $data->eta;
    $gr = $data->gr_Date;

    if($po==$p){
        $po_fx = '';
        $po_dt = '';
        $po_sp = '';
        $no_R = '';
    }else{
        $po_fx = $data->po_no;
        $po_dt = $data->po_date;
        $po_sp = $data->supplier_code."<br/>".$data->company;

        if($nourut==1){
            $no_R = $nourut;
        }else{
            $no_R = $nourut;
        }
        $nourut++;
      }

	$content .= "
			<tr>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$no_R."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$po_fx."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$po_dt."</td>
				<td valign='middle' align='left' style='font-size:10px;height:25px;'>".$po_sp."</td>
				<td valign='middle' align='left' style='font-size:10px;height:25px;'>".$data->description."<br/>[".$data->item_no."] - ".$data->item."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$data->line_no."</td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->qty)."</td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->gr_qty)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:10px;height:25px;'>".number_format($data->Receipt_qty)."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$data->eta."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$data->gr_Date."</td>
				<td valign='middle' align='center' style='font-size:10px;height:25px;'>".$data->diff." DAY</td>
			</tr>";
	$p = $po;
    $no++;
}
$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('PO-STATUS'.$date.'.pdf');
 //echo $content
?>
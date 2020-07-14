<?php

// <!-- 
// ID = 1
// Name : Reza Vebrian
// Tanggal : 25 April 2017
// Deskripsi : Membuat Report Kanban untuk assembling -->

/*id =2
NAme : ueng hernana
tanggal : 27-Apr-2017
deskripsi: perbaikan report kanban
tanggal : 05-may-2017
deskripsi: perbaikan where dengan 1 tanggal
*/

//error_reporting(0);
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
//date_prod=2017-05-05&cell_type=G07NC&Line=LR06-5
$date_prod = isset($_REQUEST['date_prod']) ? strval($_REQUEST['date_prod']) : '';
$Line = isset($_REQUEST['Line']) ? strval($_REQUEST['Line']) : '';
$cell_type = isset($_REQUEST['cell_type']) ? strval($_REQUEST['cell_type']) : '';

$date = split('-',$date_prod);

$Hari = intval($date[2]);
$Bulan = intval($date[1]);
$Tahun = intval($date[0]);

if ($cell_type!=''){
	$cell = "a.cell_type='$cell_type' and ";
}else{
	$cell = "";
}

$string = "where 
		a.tanggal='$Hari' and 
		a.bulan='$Bulan' and 
		a.tahun='$Tahun' and
		replace(a.assy_line,'#','-') = '$Line' and $cell
		USED = 1";
//echo $string;

$sql_h = "select a.*, ceil(a.qty/b.qty_box) as JumlahBox,ceil(qty/ b.qty_total) as JumlahPallet, b.qty_total, b.qty_box_pallet, b.qty_box from ztb_assy_plan a 
	inner join ztb_assy_set_pallet b on a.assy_line = b.assy_line $string ";
$result = oci_parse($connect, $sql_h);
oci_execute($result);
//echo $sql_h;
$content = " 
	<style> 
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:14px;
			height:10px;
		}
		table, th, tr {
			border-left:0px solid #ffffff; 
			border-right:0px solid #ffffff; 
			border-bottom:0px solid #ffffff; 
			border-top:0px solid #ffffff;
		}
		th {
			
			color: black;
		}
		.brd {
			border:none;
		}
		.class_00{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_01{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_02{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:25px;
		}


		.class_10{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_11{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:265px;
		}
		.class_12{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:265px;
		}

		.class_20{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_21{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:505px;
		}
		.class_22{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:505px;
		}
	</style>

	<page>
     ";
$row_a=0;	$col_a=0;     
$row_z=2;	$col_Z=2;
while ($data=oci_fetch_object($result)){
	$tgl = $data->TANGGAL."/".$data->BULAN."/".$data->TAHUN;
	$jmlperPallet = intval($data->JUMLAHPALLET);
	$qtyperpallet = $data->QTY_TOTAL;
	$qty = $data->QTY;
	$qty_b = $data->QTY_BOX;
	$qty_bp = $data->QTY_BOX_PALLET;
	$no_plt=1;

	for ($i=1; $i<=$jmlperPallet; $i++) {
		if($qty>0){
			$t = floatval($qty-$qtyperpallet);
			if($t<0) {
				$qty_save = $qty;
				$box = ceil($qty/$qty_b);
			}else{
				$qty_save = $qtyperpallet;
				$box = $qty_bp;
			}
		}

		if ($row_a==0) {
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==1){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==2){
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a++;
			}
		}elseif($row_a==1){
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==1){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==2){
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a++;
			}
		}elseif($row_a==2){
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==1){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif($col_a==2){
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a=0;
			}
		}

		$content.= "<div class=".$cls.">
						<table border=0 style='font-size:16px;width:100%;font-weight: bold;'>
							<tr>
								<td>TANGGAL PLAN</td>
								<td>:</td>
								<td style='width:150px;'>".$tgl."</td>
							</tr>
							<tr>
								<td>ASSY. LINE</td>
								<td>:</td>
								<td>".$data->ASSY_LINE."</td>
							</tr>
							<tr>
								<td>CELL TYPE</td>
								<td>:</td>
								<td>".$data->CELL_TYPE."</td>
							</tr>
							<tr>
								<td>QTY BOX</td>
								<td>:</td>
								<td>".$box." (@ ".$data->QTY_BOX." pcs)</td>
							</tr>
							<tr>
								<td>QTY PALLET</td>
								<td>:</td>
								<td>".number_format($qty_save)."</td>
							</tr>
							<tr>
								<td>PALLET NO.</td>
								<td>:</td>
								<td>".$i."</td>
							</tr>
							<tr>
								<td colspan=3 align='right' valign='middle' style='height:80px;'>
									<table border=1>
							            <tr>
							              <td style='width:65px;'></td>
							              <td style='width:65px;'></td>
							              <td style='width:65px;'></td>
							              <td style='width:10px;border-color: #FFFFFF;'></td>
							              <td style='width:65px;border-color: #FFFFFF;' align='right' valign='middle'>
							              	<qrcode 
												value='".$tgl.','.$data->ASSY_LINE.','.$data->CELL_TYPE.','.$box.','.$qty_save.','.$i.','.$data->QTY_BOX.','.$data->ID_PLAN."' 
												ec='H' style=' border:none; width:80px;background-color: white; color: black;'>
											</qrcode>
							              </td>
							            </tr>
							        </table>
								</td>
							</tr>
							<tr>
								<td colspan=3 valign='middle' style='height:5px;font-size:8px;'>
									<b>".$data->ID_PLAN."</b>
								</td>
							</tr>
						</table>
					</div>";
		$qty = $t;
	}
}

$content .= "
	</page>";

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
<!-- 
QR_CODE UPDATE- UENG ID=2, date: 24-08-2017
 -->
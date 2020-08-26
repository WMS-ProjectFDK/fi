<?php

// <!-- 
// ID = 1
// Name : Reza Vebrian
// Tanggal : 25 April 2017
// Deskripsi : Membuat Report Kanban untuk assembling -->

/*id =2
NAme : ueng hernana
tanggal : 24-AUG-2017
deskripsi: perbaikan report kanban
tanggal : 05-may-2017
deskripsi: perbaikan where dengan 1 tanggal
tanggal : 25-aug-2020
deskripsi: rebuild to sqlserver
*/

error_reporting(0);
ini_set('memory_limit','-1');
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$kodeid = isset($_REQUEST['kodeid']) ? strval($_REQUEST['kodeid']) : '';

$kd = str_replace("-", ",", $kodeid);

$sql_h = "select a.ASYLINE, a.CELL_TYPE, CAST(a.DATE_PROD as varchar(10)) as DATE_PROD, a.PALLET, a.QTY, a.ID, a.PRINTED, a.ID_PLAN, a.BOX, a.UPTO_DATE,
	b.qty_box_pallet, b.qty_box, a.date_prod as dateprod from ztb_assy_print a 
	inner join ztb_assy_set_pallet b on a.asyline = b.assy_line
	where a.id in (".$kd.")
	order by a.pallet asc";
$result = sqlsrv_query($connect, strtoupper($sql_h));
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
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:10px;
		}
		.class_11{
			position:absolute;margin-left:350px;border:1px;width:340px;height:225px;border-radius:4px;margin-top:265px;
		}
		.class_12{
			position:absolute;margin-left:700px;width:340px;height:225px;border:1px;border-radius:4px;margin-top:265px;
		}

		.class_20{
			border:1px;width:340px;height:225px;border-radius:4px;margin-top:9px;
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
while ($data=sqlsrv_fetch_object($result)){

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
							<td style='width:150px;'>".$data->DATE_PROD."</td>
						</tr>
						<tr>
							<td>ASSY. LINE</td>
							<td>:</td>
							<td>".$data->ASYLINE."</td>
						</tr>
						<tr>
							<td>CELL TYPE</td>
							<td>:</td>
							<td>".$data->CELL_TYPE."</td>
						</tr>
						<tr>
							<td>QTY BOX</td>
							<td>:</td>
							<td>".$data->BOX." (@ ".$data->QTY_BOX." pcs)</td>
						</tr>
						<tr>
							<td>QTY PALLET</td>
							<td>:</td>
							<td>".number_format($data->QTY)."</td>
						</tr>
						<tr>
							<td>PALLET NO.</td>
							<td>:</td>
							<td>".$data->PALLET."</td>
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
											value='".$data->dateprod.','.$data->ASYLINE.','.$data->CELL_TYPE.','.$data->BOX.','.$data->QTY.','.$data->PALLET.','.$data->qty_box.','.$data->ID_PLAN.','.$data->ID."' 
											ec='Q' style=' border:none; width:80px;background-color: white; color: black;'>
										</qrcode>
						              </td>
						            </tr>
						        </table>
							</td>
						</tr>
						<tr>
							<td colspan=2 valign='middle' style='height:5px;font-size:8px;'><b>".$data->ID_PLAN."</b></td>
							<td align='right' valign='middle' style='height:5px;font-size:8px;'><b>ID:".$data->ID."</b></td>
						</tr>
					</table>
				</div>";
}

$content .= "
	</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
<?php
/*id =2
NAme : ueng hernana
tanggal : 27-sep-2017
deskripsi: print pallet tambahan
*/

//error_reporting(0);
ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

$sql_h = "select * from ztb_assy_set_pallet where assy_line != 'LR03#3' order by assy_line asc";
$result = oci_parse($connect, $sql_h);
oci_execute($result);
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
$ks = 0;	$id_plan = 'LEBIH';

while ($data=oci_fetch_object($result)){
	for ($i=1; $i<=1; $i++) {
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
								<td style='width:150px;'></td>
							</tr>
							<tr>
								<td>ASSY. LINE</td>
								<td>:</td>
								<td>".$data->ASSY_LINE."</td>
							</tr>
							<tr>
								<td>CELL TYPE</td>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<td>QTY BOX</td>
								<td>:</td>
								<td>".$data->QTY_BOX_PALLET." (@ ".$data->QTY_BOX." pcs)</td>
							</tr>
							<tr>
								<td>QTY PALLET</td>
								<td>:</td>
								<td>".number_format($data->QTY_TOTAL)."</td>
							</tr>
							<tr>
								<td>PALLET NO.</td>
								<td>:</td>
								<td>0</td>
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
												value='".$ks.','.$data->ASSY_LINE.','.$ks.','.$data->QTY_BOX_PALLET.','.$data->QTY_TOTAL.','.$ks.','.$data->QTY_BOX.','.$id_plan."' 
												ec='H' style=' border:none; width:80px;background-color: white; color: black;'>
											</qrcode>
							              </td>
							            </tr>
							        </table>
								</td>
							</tr>
							<tr>
								<td colspan=3 valign='middle' style='height:5px;font-size:8px;'>
									<b>* : KANBAN PLAN LEBIH</b>
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
$html2pdf->Output('KanbanAssembling_lebih.pdf');
?>
<!-- 
QR_CODE UPDATE- UENG ID=2, date: 24-08-2017
 -->
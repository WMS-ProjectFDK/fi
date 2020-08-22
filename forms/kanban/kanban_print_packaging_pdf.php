<?php
error_reporting(0);
/*id =2
NAme : ueng hernana
tanggal : 18-JAN-19
deskripsi: print kanban packaging
 */
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../../connect/conn.php");
//include('../class/phpqrcode/qrlib.php'); 
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
date_default_timezone_set('Asia/Jakarta');
session_start();
$user_name = $_SESSION['id_wms'];

$sql = "select a.id, a.wo_no, a.item_no, a.brand, a.date_code, a.type_item, a.grade, a.qty_order,
cast(a.date_prod as varchar(10)) as date_prod, a.qty_prod, a.plt_no, a.plt_tot, cast(a.cr_date as varchar(10)) cr_date,
b.groups_pck, c.label_item_number, i.aging_day,
  left(cast(a.id as varchar(10))+'          ',10) +
  left(cast(i.item as varchar(20))+'                    ',20) +
  left(cast(a.wo_no as varchar(20))+'                    ',20) +
  left(cast(a.plt_no as varchar(5))+'     ',5) +
  left(cast(a.plt_tot as varchar(5))+'     ',5) +
  right('0000000000'+cast(a.qty_prod as varchar(10)),10) +
  left(cast(a.date_code as varchar(10))+'          ',10) + '     ' +
  left(cast(a.item_no as varchar(10))+'          ',10) +
  left(cast(a.brand as varchar(20))+'                    ',20) +
  right('0000000000'+cast(a.qty_prod as varchar(10)),10) +
  left(cast(a.type_item as varchar(10))+'          ',10) +
  left(cast(a.grade as varchar(5))+'     ',5) + '       ' +
  left(cast(isnull(itf.[INNER],' ') as varchar(14))+'              ',14) +'          ' +
  left(cast(isnull(itf.blister,' ') as varchar(20))+'                    ',20) +
  left(cast(isnull(itf.[outer],' ') as varchar(14))+'              ',14) 
as qr_code1,
  left(cast(a.wo_no as varchar(20))+'                    ',20) +
  left(cast(a.item_no as varchar(10))+'          ',10) +
  left(isnull(cast(itf.berat_inner as varchar(8)),' ') +'        ',8) +
  left(isnull(cast(itf.isi_inner as varchar(8)),' ') +'      ',6) +
  left(isnull(cast(itf.[INNER] as varchar(16)),' ')+'                ' ,16) +
  left(isnull(cast(itf.toleransi_plus as varchar(8)),' ')+'          ' ,10) +
  left(isnull(cast(itf.toleransi_minus as varchar(8)),' ')+'          ' ,10) +
  left(cast(a.plt_no as varchar(4))+'    ',4) +
  case when itf.berat_inner < 1000 then '1' else '0' end + 
  case when itf.[INNER] is null then '1' else '0' end
as qr_code2,
floor(c.operateion_time * qty_prod / (60 * b.man_power)) time_of_kanban,
floor(a.qty_prod / (zi.pallet_pcs/pallet_ctn)) as jumlah_box
from ztb_plan a
left join ztb_item_pck b on a.item_no = b.item_no
left join mps_header c on a.wo_no = c.work_order
left join item i on a.item_no=i.item_no
left join ztb_item zi on a.item_no = zi.item_no
left outer join ztb_itf_code itf on a.item_no = itf.item_no
where user_id = '$user_name'
order by id asc";
$result = sqlsrv_query($connect, strtoupper($sql));

if( $result === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
		foreach( $errors as $error ) {
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
			echo "code: ".$error[ 'code']."<br />";
			echo "message: ".$error[ 'message']."<br />";
			echo $sql;
		}
	}
}

$content = " 
	<style> 
		table {
			border-collapse: collapse;
			padding:0px;
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
			border:1px;width:525px;height:220px;border-radius:4px;margin-top:10px;
		}
		.class_01{
			position:absolute;margin-left:555px;border:1px;width:525px;height:220px;border-radius:4px;margin-top:10px;
		}

		.class_10{
			border:1px;width:525px;height:220px;border-radius:4px;margin-top:30px;
		}
		.class_11{
			position:absolute;margin-left:555px;border:1px;width:525px;height:220px;border-radius:4px;margin-top:262px;
		}

		.class_20{
			border:1px;width:525px;height:220px;border-radius:4px;margin-top:30px;
		}
		.class_21{
			position:absolute;margin-left:555px;border:1px;width:525px;height:220px;border-radius:4px;margin-top:514px;
		}
	</style>
	<page>
     ";

$row_a=0;	$col_a=0;
$plt_no_new = 1;
$plt_no_old = 0;
while ($data=sqlsrv_fetch_object($result)){
	$plt_no_new =  $data->PLT_NO;
	if($plt_no_new != $plt_no_old){
	 	$QR_CODE1 = str_replace(' ', '%20',$data->QR_CODE1);
	 	$QR_CODE2 = str_replace(' ', '%20',$data->QR_CODE2);
		
	 	$link1 = 'http://172.23.225.113:8088/wms/forms/qr_generate.php?string='.$QR_CODE1;
		$link2 = 'http://172.23.225.113:8088/wms/forms/qr_generate_timbangan.php?string='.$QR_CODE2;
		 
		// echo $link1;

		if ($row_a==0) {
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif ($col_a == 1) {
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a++;
			}
		}elseif($row_a==1){
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif ($col_a == 1) {
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a++;
			}
		}elseif($row_a==2){
			if($col_a==0){
				$cls = "class_".$row_a.$col_a;
				$col_a++;
			}elseif ($col_a == 1) {
				$cls = "class_".$row_a.$col_a;
				$col_a=0;	$row_a=0;
			}
		}
	
		$content.= "
		<div class=".$cls." style='padding: 0px 1px;'>
			<table border=1 style='font-size:10px;width:100%;'>
				<tr>
					<td align='center' valign='middle' style='width: 250px;height: 40px;border:0px solid #ffffff; font-size:16px;'>
						<b>Indication of Production for Packaging</b>
					</td>
					<td align='center' valign='middle' style='border:0px solid #ffffff;'>
						Pallet No.<br>
						<span style='font-size: 27px;'>".$data->PLT_NO."/".$data->PLT_TOT."</span>
					</td>
				</tr>
				<tr>
					<td colspan=6 style='height: 2px;border:0px solid #ffffff;'>
						<table border=1 style='width:535px;font-size:9px;width:100%;'>
							<tr>
								<td align='left' valign='middle' style='width: 40px;border-right:0px solid #ffffff;'>WO NO.</td>
								<td align='left' valign='middle' style='width: 290px;border-left:0px solid #ffffff;'>: <b>".$data->WO_NO."</b></td>
								<td align='center' valign='middle' style='width: 30px;'>TYPE</td>
								<td align='center' valign='middle' style='width: 30px;'>GRADE</td>
								<td align='center' valign='middle' style='width: 100px;'>QTY</td>
							</tr>
							<tr>
								<td style='width: 40px;border-right:0px solid #ffffff;' align='left ' valign='middle'>BRAND</td>
								<td style='width: 290px;border-left:0px solid #ffffff;' align='left' valign='middle'>: <b>".$data->ITEM_NO." - ".$data->BRAND."</b></td>
								<td style='width: 30px;' align='center' valign='middle'><b>".$data->TYPE_ITEM."</b></td>
								<td style='width: 30px;' align='center' valign='middle'><b>".$data->GRADE."</b></td>
								<td style='width: 100px;' align='right' valign='middle'><b>".number_format($data->QTY_PROD)." pcs (".$data->JUMLAH_BOX." box)</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=6 style='height: 1px;border:0px solid #ffffff;'></td>
				</tr>
				<tr>
					<td colspan=6 style='border:0px solid #ffffff;'>
						<table border=1 style='font-size:9px;width:100%;'>
							<tr>
								<td style='width: 130px;' align='center' valign='middle'>LABEL NO</td>
								<td style='width: 60px;' align='center' valign='middle'>CR DATE</td>
								<td style='width: 60px;' align='center' valign='middle'>AGING</td>
								<td style='width: 65px;' align='center' valign='middle'>OPR. TIME</td>
								<td style='width: 65px;' align='center' valign='middle'>DATE CODE</td>
								<td style='width: 60px;' align='center' valign='middle'>DATE</td>
							</tr>
							<tr>
								<td style='width: 130px;' align='left' valign='middle'>".$data->LABEL_ITEM_NUMBER."</td>
								<td style='width: 60px;' align='center' valign='middle'>".$data->CR_DATE."</td>
								<td style='width: 60px;' align='center' valign='middle'>".$data->AGING_DAY."</td>
								<td style='width: 65px;' align='center' valign='middle'>".$data->TIME_OF_KANBAN."</td>
								<td style='width: 65px;' align='center' valign='middle'>".$data->DATE_CODE."</td>
								<td style='width: 60px;' align='center' valign='middle'>".$data->DATE_PROD."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=6 style='height: 1px;border:0px solid #ffffff;'></td>
				</tr>
				<tr>
					<td colspan=6 style='border:0px solid #ffffff;'>
						<table border=1 style='font-size:9px;width:100%;'>
							<tr>
								<td style='width:120px;' align='center' valign='middle' rowspan=5>
									  <p style='font-size: 8px;'>QR-CODE : TIMBANGAN</p>
									  <img src=".$link2." style='width:70px; height:70px;'/>
					            </td>
								<td colspan=4 style='width: 280px;' align='center' valign='middle'>SCHEDULE OF FINISHING</td>
								<td style='width:100px;' align='center' valign='middle' rowspan=5>
									<img src=".$link1." style='width:85px; height: 85px;'/> 
									<br><b>ID : ".$data->ID."</b>
					            </td>
							</tr>
							<tr>
								<td valign='middle' style='width:12px;border-left:0px solid #ffffff;'>1.</td>
							</tr>
							<tr>
								<td valign='middle' style='width:12px;border-left:0px solid #ffffff;'>2.</td>
							</tr>
							<tr>
								<td valign='middle' style='width:12px;border-left:0px solid #ffffff;'>3.</td>
							</tr>
							<tr>
								<td valign='middle' style='width:12px;border-left:0px solid #ffffff;'>4.</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		";
	}
	$plt_no_old =  $plt_no_new;
}

$content .= "
	</page>
";

//echo $content;
require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('Kanban_Packaging.pdf');
?>
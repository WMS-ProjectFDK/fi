<?php
/*id =2
NAme : ueng hernana
tanggal : 18-JAN-19
deskripsi: print kanban label
 */
error_reporting(0);
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];

$machine = isset($_REQUEST['machine']) ? strval($_REQUEST['machine']) : '';
$kd_MACH = str_replace("-", "#", $machine);
$cap = 222;

$sql = "select a.id, a.wo_no, a.item_no, i.description as brand, a.date_code, a.type_item, a.grade, a.qty_order,
cast(a.date_prod as varchar(10)) date_prod, a.qty_prod, a.plt_no, a.plt_tot, cast(a.cr_date as varchar(10)) cr_date, b.package_type, i.aging_day,
(select lower_item_no 
  from structure 
  where upper_item_no = a.item_no 
  and level_no = (select max(level_no)from structure where upper_item_no = a.item_no)
  and lower_item_no like '12%') label_item_number,  
(select cast(inkjet_code as varchar(5)) inkjet_code 
 from ztb_item_label_inkjet_code az
 inner join (select * from structure s
		   inner join (select max(level_no) level_nos, upper_item_no upper 
					   from structure 
					   group by upper_item_no
					  ) ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
		  ) bz on az.item_no= bz.lower_item_no
 where bz.upper_item_no= a.item_no and bz.lower_item_no like '12%') inkjet_code,
	left(cast(a.id as varchar(10))+'          ',10) +
 	left(cast(i.item as varchar(23))+'                       ',23) +
  	left(cast(a.wo_no+' '+cast(a.plt_no as varchar(5))+' '+cast(a.plt_tot as varchar(5))+' ' as varchar(31))+'                                ',31) +
  	right('0000000000'+cast(a.qty_prod as varchar(10)),10) +
  	left(cast(a.date_code as varchar(10))+'          ',10) + '     ' +
  	left(cast(a.item_no as varchar(10))+'          ',10) +
  	left(cast(a.brand as varchar(20))+'                    ',20) +
  	right('0000000000'+cast(a.qty_prod as varchar(10)),10) +
	left(cast(a.type_item+a.grade as varchar(14))+'              ',14) +
(select cast(inkjet_code as varchar(5)) inkjet_code  
  from ztb_item_label_inkjet_code az
  inner join (select * from structure s
			  inner join (select max(level_no) level_nos, upper_item_no upper 
						  from structure 
						  group by upper_item_no
						 ) ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
			  ) bz on az.item_no= bz.lower_item_no
  where bz.upper_item_no= a.item_no and bz.lower_item_no like '12%'
) +
right(cast(b.package_type as varchar(25))+'                         ',25)
  as qr_code
from ztb_plan_l a
left join ztb_item_pck b on a.item_no = b.item_no
left join mps_header c on a.wo_no = c.work_order
left join item i on a.item_no=i.item_no
where a.user_id =  '$user_name'
order by id asc";
$result = sqlsrv_query($connect, strtoupper($sql));


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
while ($data=sqlsrv_fetch_object($result)){
	$QR_CODE1 = str_replace(' ', '%20', $data->QR_CODE);
	$link1 = 'http://localhost:8088/wms/forms/qr_generate.php?string='.$QR_CODE1;
	
	
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

	$content.= "<div class=".$cls." style='padding: 0px 1px;'>
		<table border=1 style='font-size:10px;width:100%;'>
			<tr>
				<td align='center' valign='middle' style='width: 250px;height: 30px;border:0px solid #ffffff; font-size:16px;'>
					<b>Indication of Production for Labeling</b>
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
							<td align='left' valign='middle' style='width: 35px;border-right:0px solid #ffffff;'>WO NO.</td>
							<td align='left' valign='middle' style='width: 268px;border-left:0px solid #ffffff;'>: <b>".$data->WO_NO."</b></td>
							<td align='center' valign='middle' style='width: 30px;'>TYPE</td>
							<td align='center' valign='middle' style='width: 30px;'>GRADE</td>
							<td align='center' valign='middle' style='width: 60px;'>LBL ITEM</td>
							<td align='center' valign='middle' style='width: 60px;'>QTY</td>
						</tr>
						<tr>
							<td style='width: 35px;border-right:0px solid #ffffff;' align='left ' valign='middle'>BRAND</td>
							<td style='width: 268px;border-left:0px solid #ffffff;' align='left' valign='middle'>: <b>".$data->ITEM_NO." - ".$data->BRAND."</b></td>
							<td style='width: 30px;' align='center' valign='middle'><b>".$data->TYPE_ITEM."</b></td>
							<td style='width: 30px;' align='center' valign='middle'><b>".$data->GRADE."</b></td>
							<td style='width: 60px;' align='center' valign='middle'><b>".$data->LABEL_ITEM_NUMBER."</b></td>
							<td style='width: 60px;' align='right' valign='middle'><b>".number_format($data->QTY_PROD)."</b></td>
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
							<td style='width: 210px;' align='center' valign='middle'>NEXT PROCESS</td>
							<td style='width: 50px;' align='center' valign='middle'>CR DATE</td>
							<td style='width: 40px;' align='center' valign='middle'>AGING</td>
							<td style='width: 80px;' align='center' valign='middle'>TIME OF KANBAN</td>
							<td style='width: 40px;' align='center' valign='middle'>MACHINE</td>
							<td style='width: 60px;' align='center' valign='middle'>DATE CODE</td>
						</tr>
						<tr>
							<td style='width: 210px;' align='left' valign='middle'>".$data->PACKAGE_TYPE."</td>
							<td style='width: 50px;' align='center' valign='middle'>".$data->CR_DATE."</td>
							<td style='width: 40px;' align='center' valign='middle'>".$data->AGING_DAY."</td>
							<td style='width: 80px;' align='center' valign='middle'>".number_format($data->QTY_PROD/$cap)."</td>
							<td style='width: 40px;' align='center' valign='middle'>".$kd_MACH."</td>
							<td style='width: 60px;' align='center' valign='middle'>".$data->DATE_CODE." (".$data->INKJET_CODE."X)</td>
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
							<td style='width: 27px;' align='center' valign='middle'>SHIFT</td>
							<td style='width: 68px;' align='center' valign='middle'>LABEL DATE</td>
							<td style='width: 68px;' align='center' valign='middle'>LABEL LINE</td>
							<td style='width: 68px;' align='center' valign='middle'>ASSY LINE</td>
							<td style='width: 68px;' align='center' valign='middle'>ASSY DATE</td>
							<td style='width: 67px;' align='center' valign='middle'>QTY</td>
							<td style='width: 106px;' align='center' valign='top' rowspan=6>
								DATE : ".$data->DATE_PROD."<br>
								<img src=".$link1." style='width:75px;height:75px;'/> <br>
								<b>ID : ".$data->ID."</b>
				            </td>
						</tr>
						<tr>
							<td align='center' style='height:12px;' valign='middle'>1</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='height:12px;' valign='middle'>2</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='height:12px;' valign='middle'>3</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='height:12px;' valign='middle'>4</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>";
}

$content .= "</page>";
// echo $content;

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
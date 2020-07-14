<?php
/*id =2
NAme : ueng hernana
tanggal : 18-JAN-19
deskripsi: print kanban label
 */
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];


/*
"03#1" = 281
"03#2" = 222
"3#1" = 222
"6#2" = 222
"6#3" = 222
"6#4" = 296
"6#5" = 370
"6#6" = 444
*/

$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
$kd_MACH = str_replace("-", "#", $machine);
$cap = 222;

// if($machine='LR03#1'){ $cap = 281; }
// else if ($machine='LR6#4'){ $cap = 296; }
// else if ($machine='LR6#5'){ $cap = 370; }
// else if ($machine='LR6#6'){ $cap = 444; }


$sql = "select a.id, a.wo_no, a.item_no, i.description as brand, a.date_code, a.type_item, a.grade, a.qty_order,
	a.date_prod, a.qty_prod, a.plt_no, a.plt_tot, a.cr_date,
	b.package_type,  (select lower_item_no from structure where upper_item_no = a.item_no and level_no = c.bom_level and lower_item_no like '12%') label_item_number, i.aging_day,
	rpad(cast(a.id as varchar(10)),10,' ') ||
  	rpad(cast(i.item as varchar(23)),23,' ') ||
  	rpad(cast(a.wo_no||' '||a.plt_no||' '||a.plt_tot||' ' as varchar(31)),31,' ') ||
  	lpad(cast(a.qty_prod as varchar(10)),10,'0') ||
  	rpad(cast(a.date_code as varchar(10)),10,' ') || '     ' ||
  	rpad(cast(a.item_no as varchar(10)),10,' ') ||
  	rpad(cast(a.brand as varchar(20)),20,' ') ||
  	lpad(cast(a.qty_prod as varchar(10)),10,'0') ||
  	rpad(cast(a.type_item||a.grade as varchar(14)),14,' ')
  	as qr_code
	from ztb_l_plan a
	left join ztb_item_pck b on a.item_no = b.item_no
	left join mps_header c on a.wo_no = c.work_order
	left join item i on a.item_no=i.item_no
	where id in ($id)
	order by id asc";
$result = oci_parse($connect, $sql);
oci_execute($result);

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
while ($data=oci_fetch_object($result)){
	$QR_CODE1 = str_replace(' ', '%20', $data->QR_CODE);
	$link1 = 'http://172.23.225.85/wms/forms/qr_generate.php?string='.$QR_CODE1;
	
	
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
							<td style='width: 60px;' align='center' valign='middle'>".$data->DATE_CODE."</td>
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

require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
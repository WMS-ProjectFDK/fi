<?php
error_reporting(0);
ini_set('memory_limit','-1');
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$wo = isset($_REQUEST['wo']) ? strval($_REQUEST['wo']) : '';


$sql = "select a.po_no as path, a.work_order, a.batery_type, a.cell_grade, a.po_no,
soh.so_no, sod.customer_part_no, a.item_no, sod.origin_code, a.qty, sod.uom_q,
sod.customer_po_line_no, i.aging_day,
replace(replace(PALLET_MARK_1,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_1, 
replace(replace(PALLET_MARK_2,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_2, 
replace(replace(PALLET_MARK_3,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_3,
replace(replace(PALLET_MARK_4,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_4, 
replace(replace(PALLET_MARK_5,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_5, 
replace(replace(PALLET_MARK_6,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_6, 
replace(replace(PALLET_MARK_7,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_7, 
replace(replace(PALLET_MARK_8,'1-UP',''),'MODEL NAME',i.item) as PALLET_MARK_8,
PALLET_MARK_9, PALLET_MARK_10,
replace(replace(CASE_MARK_1,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_1, 
replace(replace(CASE_MARK_2,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_2, 
replace(replace(CASE_MARK_3,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_3, 
replace(replace(CASE_MARK_4,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_4,
replace(replace(CASE_MARK_5,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_5, 
replace(replace(CASE_MARK_6,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_6, 
replace(replace(CASE_MARK_7,'1-UP',''),'MODEL NAME',i.item) as  CASE_MARK_7, 
CASE_MARK_8, CASE_MARK_9, CASE_MARK_10,
sod.ASIN,sod.AMAZON_PO_NO,com.address1,com.address2,com.address3,com.address4
from mps_header a
inner join so_header soh on a.po_no = soh.customer_po_no
inner join so_details sod on soh.so_no=sod.so_no and 
			case when len(a.po_line_no) > 1 then 
				cast(left(a.po_line_no,1) as int) 
			  else cast(a.po_line_no as int) end = sod.line_no
left outer join item i on a.item_no=i.item_no
left outer join company com on soh.consignee_code = cast(com.company as varchar(100))
	where a.work_order='$wo'";
$result = sqlsrv_query($connect, strtoupper($sql));


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
			border:1px;width:350px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_01{
			position:absolute;margin-left:360px;border:1px;width:350px;height:225px;border-radius:4px;margin-top:25px;
		}
		.class_02{
			position:absolute;margin-left:720px;width:350px;height:225px;border:1px;border-radius:4px;margin-top:25px;
		}


		.class_10{
			border:1px;width:350px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_11{
			position:absolute;margin-left:360px;border:1px;width:350px;height:225px;border-radius:4px;margin-top:265px;
		}
		.class_12{
			position:absolute;margin-left:720px;width:350px;height:225px;border:1px;border-radius:4px;margin-top:265px;
		}

		.class_20{
			border:1px;width:350px;height:225px;border-radius:4px;margin-top:13px;
		}
		.class_21{
			position:absolute;margin-left:360px;border:1px;width:350px;height:225px;border-radius:4px;margin-top:505px;
		}
		.class_22{
			position:absolute;margin-left:720px;width:350px;height:225px;border:1px;border-radius:4px;margin-top:505px;
		}
	</style>

	<page>
     ";

$row_a=0;	$col_a=0;     
$row_z=2;	$col_Z=2;
while ($data=sqlsrv_fetch_object($result)){
	for ($i=0; $i < 9; $i++) { 
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
					<br/>
						<table border=0 style='font-size:14px;width:100%;'>
							<tr><td>".$data->CASE_MARK_1."</td></tr>
							<tr><td>".$data->CASE_MARK_2."</td></tr>
							<tr><td>".$data->CASE_MARK_3."</td></tr>
							<tr><td>".$data->CASE_MARK_4."</td></tr>
							<tr><td>".$data->CASE_MARK_5."</td></tr>
							<tr><td>".$data->CASE_MARK_6."</td></tr>
							<tr><td>".$data->CASE_MARK_7."</td></tr>
							<tr><td>".$data->CASE_MARK_8."</td></tr>
							<tr><td>".$data->CASE_MARK_9."</td></tr>
							<tr><td>".$data->CASE_MARK_10."</td></tr>
						</table>
					<br/>
					</div>";
	}
}

$content .= "
	</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>
<?php
// error_reporting(0);
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
 	inner join so_details sod on soh.so_no=sod.so_no and substring(a.po_line_no,1,1) = sod.line_no
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
		div {
	    	width:100%;
	    	height: 97%;
	   	}
	   	.class_00 {
	    	border: 2px solid #000000;border-radius:4px;margin-top:15px;
	   	}
	</style>

	<page>
     ";

$row_a=0;	$col_a=0;     
$row_z=2;	$col_Z=2;
while ($data=sqlsrv_fetch_object($result)){
	$content.= "<div class='class_00'>
					<table border=0 style='font-size:48px;width:90%;'>
						<tr><td>&nbsp;".$data->PALLET_MARK_1."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_2."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_3."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_4."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_5."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_6."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_7."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_8."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_9."</td></tr>
						<tr><td>&nbsp;".$data->PALLET_MARK_10."</td></tr>
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
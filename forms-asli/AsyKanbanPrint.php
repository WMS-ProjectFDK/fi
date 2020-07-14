<?php 

function strtodate($tanggal){

 return $timestamp = strtotime($tanggal);

}
//error_reporting(0);

// <!-- 
// ID = 1
// Name : Reza Vebrian
// Tanggal : 25 April 2017
// Deskripsi : Membuat Report Kanban untuk assembling -->

ini_set('memory_limit','-1');
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];

 $Date_Start = isset($_REQUEST['Date_Start']) ? strval($_REQUEST['Date_Start']) : '';
 $Date_End = isset($_REQUEST['Date_End']) ? strval($_REQUEST['Date_End']) : '';
 $Line = isset($_REQUEST['Line']) ? strval($_REQUEST['Line']) : '';
 $Cell_Type = isset($_REQUEST['Cell_Type']) ? strval($_REQUEST['Cell_Type']) : '';
 
 $HariStart = intval(Date('d',strtodate($Date_Start)));
 $HariEnd = intval(Date('d',strtodate($Date_End)));

 $BulanStart = intval(Date('m',strtodate($Date_Start)));
 $BulanEnd = intval(Date('m',strtodate($Date_End)));

 $TahunStart = intval(Date('Y',strtodate($Date_Start)));
 $TahunEnd = intval(Date('Y',strtodate($Date_End)));
 $string = "where tanggal between '$HariStart' and '$HariEnd' and bulan between '$BulanStart' and '$BulanEnd' and tahun between '$TahunStart' and '$TahunEnd' and USED = 1 and rownum <=2";


$sql_h = "select a.*, ceil(a.qty/b.qty_box) as JumlahBox,ceil(qty/ b.qty_total) as JumlahPallet, b.qty_total from ztb_assy_plan a inner join ztb_assy_set_pallet 	   b  on a.assy_line = b.assy_line $string";

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
	</style>
	
	<page>
     ";
$content = "  " ;
$loop = 0;
$ururt = 0;
while ($data=oci_fetch_object($result)){
	
	
	 $i = 1;
	 $urut +=1;
	 

	while($i <= $data->JUMLAHPALLET){
		
		     
			 $loop++ ;
			 $stringJudul .= "<td>Kanban Assembling</td>";
			 $StringCell .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;width:500px;'>Cell Type : ".$data->CELL_TYPE." </td>";
			 $StringLine .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;'>Assembling Line : ".$data->ASSY_LINE."</td>";
			 $StringTanggal .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;'>Tanggal Produksi : ".$data->TANGGAL."/".$data->BULAN."/".$data->TAHUN."</td>";
			 $StringQty .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;'>Quantity : ".$data->QTY."</td>";
			 $StringJumlahBox .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;'>Jumlah Box : ".$data->JUMLAHBOX."</td>";
			 $StringNoPallet  .= "<td valign='border-left' align='border-left' style='font-size:24px;height:25px;'>Nomor Pallet : ".$i."      NOMOR URUT :  ".$urut."</td>";
			 
		
		if ($loop >= 3 ){ 	
			$loop = 0;
			$content .= " 
			<table> 
			<tr>				
				
				<tr>".$StringJudul."</tr>
				<tr>".$StringCell." </tr>
				<tr>".$StringLine."</tr>
				<tr>".$StringTanggal."</tr>
				<tr>".$StringJumlahBox."</tr>
				<tr>".$StringNoPallet."</tr>
					
				
							
			</tr>
			</table> 
			<p></p>";
			 $stringJudul = "";
			 $StringCell = "";
			 $StringLine = "";
			 $StringTanggal = "";
			 $StringQty = "";
			 $StringJumlahBox = "";
			 $StringNoPallet  = "";
		}
	
	$i++;
	}


}
$content .= " 
<table> 
			<tr>				
				
				<tr>".$StringJudul."</tr>
				<tr>".$StringCell." </tr>
				<tr>".$StringLine."</tr>
				<tr>".$StringTanggal."</tr>
				<tr>".$StringJumlahBox."</tr>
				<tr>".$StringNoPallet."</tr>
				
							
			</tr>
			</table>

</page>";



// require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
// $html2pdf = new HTML2PDF('L','A4','en');
// $html2pdf->WriteHTML($content);
// $html2pdf->Output('KanbanAssembling.pdf');
echo $content;



?>
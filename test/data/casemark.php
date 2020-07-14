<?php
	include("../../connect/conn.php");
	$items = array();
	$rowno=0;

	$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

	$sql = " select do_no,replace(marks,chr(13),'') marks,mark_no,
    (select do_details.so_no1 from do_details where rownum = 1 and do_details.do_no = do_marks.do_no ) so  from do_marks
    where do_no = '$do'";
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$i = 0;
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
	<table align='left'>
	<tr>";
	while($row = oci_fetch_object($data)){
		// array_push($items, $row);
		$content .= "
		
			
			<td valign='middle' align='center' style='font-size:10px;height:15px;width:100px'>".$row->MARKS."</td>
			
		";
		$rowno++;
		if ($rowno==4){
			$content .= "</table> <br>  <table align='center'>";
			$rowno=0;
		};
	}
	

	$content .= "</tr>
		</table>
	</div>
	</page>
	
";

echo $content;
// require_once(dirname(__FILE__).'../../../class/html2pdf/html2pdf.class.php');
// $html2pdf = new HTML2PDF('L','A4','en');
// $html2pdf->WriteHTML($content);
// $html2pdf->Output($do.'.pdf');	

?>
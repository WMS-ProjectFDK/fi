<?php
$id=isset($_REQUEST['id']) ? strval($_REQUEST['id']) : ''; //33434
$line=isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';//LR6-6

$ng = array(
	array("ID"=>"A1", "PROCESS_O"=>"UNCASING LOADER", "PROCESS"=>"<span style='color:red;font-size:12px;'><b>UNCASING LOADER</b></span>", "NG_CONTENT1"=>"TOP/DISC PENYOK/LECET", "QTY1"=>"0", "NG_CONTENT2"=>"BATERAI BOCOR (LEAKAGE)", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"A2", "PROCESS_O"=>"UNCASING LOADER", "PROCESS"=>"<span style='color:red;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BODY PENYOK (DENT BODY)",  "QTY1"=>"0", "NG_CONTENT2"=>"TOP PENYOK/LECET (DENT/SCRATCH)", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"A3", "PROCESS_O"=>"UNCASING LOADER", "PROCESS"=>"<span style='color:red;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BATTERY JATUH (DROP CELL)", "QTY1"=>"0", "NG_CONTENT2"=>"DISC PENYOK/LECET (DENT/SCRATCH)", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"A4", "PROCESS_O"=>"UNCASING LOADER", "PROCESS"=>"<span style='color:red;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"-", "QTY1"=>"0", "NG_CONTENT2"=>"BODY PENYOK (BODY DENT)", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"B1", "PROCESS_O"=>"WASHER FITTING", "PROCESS"=>"<span style='color:#FF00FF;font-size:12px;'><b>WASHER FITTING</b></span>", "NG_CONTENT1"=>"TOP/DISC PENYOK/LECET", "QTY1"=>"0", "NG_CONTENT2"=>"BATERAI TINGGI NG (NG HIGH BATT.)", "QTY2"=>"0", "NG_CONTENT3"=>"BATTERY NO WASHER", "QTY3"=>"0"),
	array("ID"=>"B2", "PROCESS_O"=>"WASHER FITTING", "PROCESS"=>"<span style='color:#FF00FF;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BODY PENYOK (DENT BODY)", "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"BATTERY NG WASHER", "QTY3"=>"0"),
	array("ID"=>"B3", "PROCESS_O"=>"WASHER FITTING", "PROCESS"=>"<span style='color:#FF00FF;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BATTERY JATUH (DROP CELL)", "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"C1", "PROCESS_O"=>"OCV CHECK", "PROCESS"=>"<span style='color:orange;font-size:12px;'><b>OCV CHECK</b></span>", "NG_CONTENT1"=>"TOP/DISC PENYOK/LECET", "QTY1"=>"0", "NG_CONTENT2"=>"OV NG ( < 1.615 volt )", "QTY2"=>"0", "NG_CONTENT3"=>"OCV NG at 1st OV (> 1.615 V)", "QTY3"=>"0"),
	array("ID"=>"C2", "PROCESS_O"=>"OCV CHECK", "PROCESS"=>"<span style='color:orange;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BODY PENYOK (DENT BODY)", "QTY1"=>"0", "NG_CONTENT2"=>"NG QTY at 2nd CHECK OV NG", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"C3", "PROCESS_O"=>"OCV CHECK", "PROCESS"=>"<span style='color:orange;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BATTERY JATUH (DROP CELL)", "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"D1", "PROCESS_O"=>"LABELING", "PROCESS"=>"<span style='color:blue;font-size:12px;'><b>LABELING</b></span>","NG_CONTENT1"=>"TOP/DISC PENYOK/LECET",  "QTY1"=>"0", "NG_CONTENT2"=>"DIRTY CAN'T CLEANING BY ADHESIVE", "QTY2"=>"0", "NG_CONTENT3"=>"MEANDERING (GONDRONG)", "QTY3"=>"0"),
	array("ID"=>"D2", "PROCESS_O"=>"LABELING", "PROCESS"=>"<span style='color:blue;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BODY PENYOK (DENT BODY)", "QTY1"=>"0", "NG_CONTENT2"=>"DIRTY CAN'T CLEANING BY ELECTROLYTE", "QTY2"=>"0", "NG_CONTENT3"=>"SLANTING (MIRING)", "QTY3"=>"0"),
	array("ID"=>"D3", "PROCESS_O"=>"LABELING", "PROCESS"=>"<span style='color:blue;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BATTERY JATUH (DROP CELL)", "QTY1"=>"0", "NG_CONTENT2"=>"DIRTY CAN'T CLEANING BY INK JET", "QTY2"=>"0", "NG_CONTENT3"=>"WRINKLE (KERIPUT)", "QTY3"=>"0"),
	array("ID"=>"D4", "PROCESS_O"=>"LABELING", "PROCESS"=>"<span style='color:blue;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"-", "QTY1"=>"0", "NG_CONTENT2"=>"DIRTY CAN'T CLEANING BY RUSTY", "QTY2"=>"0", "NG_CONTENT3"=>"DATE CODE INK JET PRINT NG", "QTY3"=>"0"),
	array("ID"=>"E1", "PROCESS_O"=>"CASING LOADER", "PROCESS"=>"<span style='color:green;font-size:12px;'><b>CASING LOADER</b></span>", "NG_CONTENT1"=>"TOP/DISC PENYOK/LECET", "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"E2", "PROCESS_O"=>"CASING LOADER", "PROCESS"=>"<span style='color:green;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BODY PENYOK (DENT BODY)", "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0"),
	array("ID"=>"E3", "PROCESS_O"=>"CASING LOADER", "PROCESS"=>"<span style='color:green;font-size:12px;'><b></b></span>", "NG_CONTENT1"=>"BATTERY JATUH (DROP CELL)",  "QTY1"=>"0", "NG_CONTENT2"=>"-", "QTY2"=>"0", "NG_CONTENT3"=>"-", "QTY3"=>"0")
);

include("../../connect/conn.php"); 

$sql = "select id, id_print, labelline, qty1, qty2, qty3
	from ztb_lbl_trans_ng
	where id_print=$id and replace(labelline,'#','-')='$line'
	order by id asc";
$data = oci_parse($connect, $sql);
oci_execute($data);
$rowno = 0;
$items = array();
 
while ($row = oci_fetch_object($data)) {
	array_push($items, $row);
	for ($i=0; $i<count($ng);$i++){
	 	$na = trim($items[$rowno]->ID);
	 	$nb = trim($ng[$i]["ID"]);
		if($na == $nb){
			$ng[$i]["QTY1"] = $items[$rowno]->QTY1;
			$ng[$i]["QTY2"] = $items[$rowno]->QTY2;
			$ng[$i]["QTY3"] = $items[$rowno]->QTY3;
		}
	}
	$rowno++;
}

$result["rows"] = $ng;
echo json_encode($result);
?>
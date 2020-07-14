<?php
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';
$link = 'C:\xampp\Kuraire\wms\schedule\assembly_report.xls';
ini_set('memory_limit', '-1');
error_reporting(0);
set_time_limit(0);
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "virus.fdk.co.jp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
//$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
$mail->Password = "fidonot";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

//Set who the message is to be sent to
$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
$mail->addAddress('yaser.ali@fdk.co.jp', 'yaser.ali@fdk.co.jp');
$mail->addAddress('ferry.agung@fdk.co.jp', 'ferry.agung@fdk.co.jp');

$mail->addAddress('arihudayato@fdk.co.jp', 'arihudayato@fdk.co.jp');
$mail->addAddress('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
$mail->addAddress('wahyu@fdk.co.jp', 'Wahyu');
$mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
$mail->addAddress('suharti@fdk.co.jp', 'suharti@fdk.co.jp');
$mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
$mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
$mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');

$mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
$mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
$mail->addAddress('tuduki@fdk.co.jp', 'tuduki@fdk.co.jp');
$mail->addAddress('daedalus@fdk.co.jp', 'daedalus@fdk.co.jp');
$mail->addAddress('aris@fdk.co.jp', 'aris@fdk.co.jp');
$mail->addAddress('novianto.hadi@fdk.co.jp', 'novianto.hadi@fdk.co.jp');
$mail->addAddress('prihartanto@fdk.co.jp', 'prihartanto@fdk.co.jp');
$mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
$mail->addAddress('agung.mardiansyah@fdk.co.jp', 'agung.mardiansyah@fdk.co.jp');

$mail->addAddress('handiko.haminanto@fdk.co.jp', 'handiko.haminanto@fdk.co.jp');
$mail->addAddress('joni.susilo@fdk.co.jp', 'joni.susilo@fdk.co.jp');
$mail->addAddress('ardian.ary@fdk.co.jp', 'ardian.ary@fdk.co.jp');
$mail->addAddress('widodo@fdk.co.jp', 'widodo@fdk.co.jp');

$mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
$mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');

$mail->addAddress('antonius@fdk.co.jp', 'antonius@fdk.co.jp');
$mail->addAddress('fifin2@fdk.co.jp', 'fifin2@fdk.co.jp');
$mail->addAddress('fauzi.budi@fdk.co.jp', 'fauzi.budi@fdk.co.jp');
$mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
$mail->addAddress('labellr@fdk.co.jp', 'labellr@fdk.co.jp');
$mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
$mail->addAddress('samekto@fdk.co.jp', 'samekto@fdk.co.jp');
$mail->addAddress('firdaus@fdk.co.jp', 'firdaus@fdk.co.jp');
$mail->addAddress('agustinus@fdk.co.jp','agustinus@fdk.co.jp');
$mail->addAddress('wiwith@fdk.co.jp', 'wiwith@fdk.co.jp');
$mail->addAddress('wiwiky@fdk.co.jp', 'wiwiky@fdk.co.jp');
$mail->addAddress('fifin1@fdk.co.jp', 'fifin1@fdk.co.jp');
$mail->addAddress('fransiska@fdk.co.jp', 'fransiska@fdk.co.jp');

//Set the subject line
$mail->Subject = 'Assembly Daily Report';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../images/logo-print4.png");

include("../connect/conn.php");
$min_date = strtotime('-1 day',strtotime(date('Y-m-d')));
$on_date = date("l, F d, Y",$min_date);
$plan_date = intval(date("d",$min_date));
$plan = "plan_".$plan_date;
$tot_plan = "total_plan_".$plan_date;
$actual = "actual_".$plan_date;
$tot_actual = "total_actual_".$plan_date;

if(intval(date('d')) == 1){
	if(intval(date('m')) == 1){
		$bulan = 12;
		$tahun = intval(date('Y'))-1;
	}else{
		$bulan = intval(date('m')) -1;
		$tahun = intval(date('Y'));
	}

}else{
	$bulan = date('m');
	$tahun = date('Y');
}

if($plan_date == 1){
	$pl= "PLAN_1";
}elseif($plan_date == 2){
	$pl= "PLAN_1+PLAN_2";
}elseif($plan_date == 3){
	$pl= "PLAN_1+PLAN_2+PLAN_3";
}elseif($plan_date == 4){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4";
}elseif($plan_date == 5){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5";
}elseif($plan_date == 6){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6";
}elseif($plan_date == 7){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7";
}elseif($plan_date == 8){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8";
}elseif($plan_date == 9){
	$pl= "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9";
}elseif($plan_date == 10){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10";
}elseif($plan_date == 11){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11";
}elseif($plan_date == 12){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12";
}elseif($plan_date == 13){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13";
}elseif($plan_date == 14){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14";
}elseif($plan_date == 15){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15";
}elseif($plan_date == 16){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16";
}elseif($plan_date == 17){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17";
}elseif($plan_date == 18){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18";
}elseif($plan_date == 19){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19";
}elseif($plan_date == 20){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20";
}elseif($plan_date == 21){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21";
}elseif($plan_date == 22){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22";
}elseif($plan_date == 23){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23";
}elseif($plan_date == 24){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24";
}elseif($plan_date == 25){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25";
}elseif($plan_date == 26){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26";
}elseif($plan_date == 27){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26+PLAN_27";
}elseif($plan_date == 28){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26+PLAN_27+PLAN_28";
}elseif($plan_date == 29){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26+PLAN_27+PLAN_28+PLAN_29";
}elseif($plan_date == 30){
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26+PLAN_27+PLAN_28+PLAN_29+PLAN_30";
}else{
	$pl = "PLAN_1+PLAN_2+PLAN_3+PLAN_4+PLAN_5+PLAN_6+PLAN_7+PLAN_8+PLAN_9+PLAN_10+PLAN_11+PLAN_12+PLAN_13+PLAN_14+PLAN_15+PLAN_16+PLAN_17+PLAN_18+PLAN_19+PLAN_20+PLAN_21+PLAN_22+PLAN_23+PLAN_24+PLAN_25+PLAN_26+PLAN_27+PLAN_28+PLAN_29+PLAN_30+PLAN_31";
}


$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-ASSEMBLY REPORT</title>
  <style>
	table {
	    border-collapse: collapse;
	}

	table, td, th {
	    border: 1px solid black;
	    font-family: Verdana, Geneva, sans-serif; 
        font-size: 12px;
	}
  </style>
</head>
<body>
<div style="width: 640px; font-family: Verdana, Geneva, sans-serif; font-size: 12px;">
  <p>Dear All,</p>
  <p>Please see the result of assembling production :<br/> 
	 Actual Assembling Production VS  Plan Assembling Production.
  </p>';

$sql2 = "select --aa.assy_line,
	case when aa.assy_line = 'LR01#1' then 'LR1#1' 
	when substr(aa.assy_line,0,4) = 'LR06' then 'LR6'|| substr(aa.assy_line,5,2)
	else aa.assy_line end as assy_line,
	total_plan, nvl(total_actual,0), nvl(total_actual,0) - total_plan  Balance from 
	(
	select assy_line,sum(QTY) Total_Plan from ztb_assy_plan where used = 1 and bulan = $bulan and tanggal < ".$plan_date."+1 and tahun = $tahun
	group by assy_line
	)aa
	left outer join (

	select assy_line,sum(qty_act_perpallet) Total_Actual from ztb_assy_kanban
	where to_number(to_char(tanggal_actual,'mm')) = $bulan and  to_number(to_char(tanggal_actual,'yyyy')) = $tahun and to_number(to_char(tanggal_actual,'dd')) < ".$plan_date."+1
	group by assy_line
	) bb
	on aa.assy_line = bb.assy_line
	order by aa.assy_line"  ;




$message .='
  <div>
  	  <p>Production Balance untill '.$on_date.'</p>
	  <table>
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">ASSY LINE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">TOTAL PLAN</th>
	       <th style="background-color: #D2D2D2;width: 120px;" align="center">TOTAL ACTUAL</th>
	       <th style="background-color: #D2D2D2;width: 120px;" align="center">BALANCE</th>
	    </tr>';


$result2 = oci_parse($connect, $sql2);
oci_execute($result2);
$no2=1; $t_plan2 = 0;  $t_act2 = 0;   $t_gap2 = 0;
while ($data_cek2=oci_fetch_array($result2)){
	if($data_cek2[3] < 0){
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[1])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
		        	<td style='background-color: #E2EFDA; color: #FF0000;' align='right'>".number_format($data_cek2[3])."</td>";
	}else{
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[1])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
		        	<td style='background-color: #E2EFDA; ' align='right'>".number_format($data_cek2[3])."</td>";	
	}
	
	$message .= '<tr>'.$print_gp.'</tr>';
	$t_plan2 += $data_cek2[1];
	$t_act2 += $data_cek2[2];
	$t_gap2 += $data_cek2[3];	
}

if($t_gap2 < 0){
	$tot_gap2 = '<td style="background-color: #D2D2D2; color: #FF0000;" align="right"><b>'.number_format($t_gap2).'</b></td>';
}else{
	$tot_gap2 = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_gap2).'</b></td>';
}


$message.= '<tr>
        <td colspan="2" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan2).'</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act2).'</b></td>
        '.$tot_gap2.'
      </tr>
    </table>
  </div>';
$message .='<br/>';


$message .='
  <div>
  	  <p>Production Each Grade until '.$on_date.'</p>
	  <table>
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">ASSY LINE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">CELL TYPE</th>
	       <th style="background-color: #D2D2D2;width: 120px;" align="center">PLAN</th>
	       <th style="background-color: #D2D2D2;width: 120px;" align="center">ACTUAL</th>
	       <th style="background-color: #D2D2D2;width: 120px;" align="center">GAP</th>
	    </tr>';
$sql2 ="select --p.assy_line, 
	case when p.assy_line = 'LR01#1' then 'LR1#1' 
	when substr(p.assy_line,0,4) = 'LR06' then 'LR6'|| substr(p.assy_line,5,2)
	else p.assy_line end as assy_line,
	p.cell_type, sum(plan) as plan, sum(actual) as actual, sum(actual) - sum(plan) as Gap
		from (
			select aa.assy_line, aa.cell_type, case when keterangan = 'a.plan' then qty else 0 end as Plan, case when keterangan = 'b.actual' then qty else 0 end as Actual 
			from (
				select 'a.plan' as keterangan, assy_line, cell_type,
				".$pl." as qty 
				from zvw_plan_assy 
				where  bulan = $bulan and tahun = $tahun and ".$pl." > 0
				UNION ALL
				select 'b.actual',k.assy_line, k.cell_type, sum(qty_act_perpallet) as ActualAmount from ztb_assy_kanban k
				where to_number(to_char(tanggal_actual,'mm'))= $bulan
				AND to_number(to_char(tanggal_actual,'yyyy'))= $tahun
				AND to_number(to_char(tanggal_actual,'dd')) <= ".$plan_date."
				group by k.assy_line,k.cell_type
			) aa
			order by aa.assy_line,keterangan,aa.cell_type
		) P group by p.assy_line, p.cell_type
		order by p.assy_line";
$result2 = oci_parse($connect, $sql2);
oci_execute($result2);
$no2=1;  $t_plan2 = 0;  $t_act2 = 0;   $t_gap2 = 0;
while ($data_cek2=oci_fetch_array($result2)){
	if($data_cek2[4] < 0){
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[1]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[3])."</td>
		        	<td style='background-color: #E2EFDA;color: #FF0000;' align='right'>".number_format($data_cek2[4])."</td>";
	}else{
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek2[1]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[3])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[4])."</td>";	
	}
	
	$message .= '<tr>'.$print_gp.'</tr>';

	$t_plan2 += $data_cek2[2];
	$t_act2 += $data_cek2[3];
	$t_gap2 += $data_cek2[4];	
}

if($t_gap2 < 0){
	$tot_gap2 = '<td style="background-color: #D2D2D2; color: #FF0000;" align="right"><b>'.number_format($t_gap2).'</b></td>';
}else{
	$tot_gap2 = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_gap2).'</b></td>';
}

$message.= '<tr>
        <td colspan="3" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan2).'</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act2).'</b></td>
        '.$tot_gap2.'
      </tr>
    </table>
  </div>';
$message .='<br/>';

$message.='
  <div>
    <p>Production on '.$on_date.'</p>
     <table>
      <tr>
        <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
        <th style="background-color: #D2D2D2;width: 150px;" align="center">ASSY LINE</th>
        <th style="background-color: #D2D2D2;width: 150px;" align="center">CELL TYPE</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">PLAN</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">ACTUAL</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">GAP</th>
      </tr>';

$sql = "select --p.assy_line, 
  case when p.assy_line = 'LR01#1' then 'LR1#1' 
  when substr(p.assy_line,0,4) = 'LR06' then 'LR6'|| substr(p.assy_line,5,2)
  else p.assy_line end as assy_line,
	p.cell_type, sum(plans) as plan, sum(actual) as actual, sum(actual) - sum(plans) as gap
		from (
		  select aa.assy_line, aa.cell_type, case when keterangan = 'a.plan' then qty else 0 end as Plans, case when keterangan = 'b.actual' then qty else 0 end as Actual
		  from (
		    select 'a.plan' as keterangan,assy_line, cell_type, $plan as Qty from zvw_plan_assy 
		    where  bulan = $bulan and tahun = $tahun and $plan > 0 
		    union all
		    select 'b.actual',k.assy_line,k.cell_type,sum(qty_act_perpallet) as ActualAmount from ztb_assy_kanban k
			where to_number(to_char(tanggal_actual,'mm'))= $bulan
		    AND to_number(to_char(tanggal_actual,'yyyy'))= $tahun
		    AND to_number(to_char(tanggal_actual,'dd')) = ".$plan_date."
			group by k.assy_line,k.cell_type
		  )aa
		  order by aa.assy_line,keterangan,aa.cell_type
		) P 
		group by p.assy_line, p.cell_type
		order by p.assy_line";
$result = oci_parse($connect, $sql);
oci_execute($result);
$no=1;  $t_plan = 0;  $t_act = 0;   $t_gap = 0;
while ($data_cek=oci_fetch_array($result)){
	if($data_cek[4] < 0){
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek[0]."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek[1]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[2])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[3])."</td>
		        	<td style='background-color: #E2EFDA; color: #FF0000;' align='right'>".number_format($data_cek[4])."</td>";
	}else{
		$print_gp ="<td style='background-color: #E2EFDA;'>".$no++."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek[0]."</td>
		        	<td style='background-color: #E2EFDA;'>".$data_cek[1]."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[2])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[3])."</td>
		        	<td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[4])."</td>";	
	}
	
	$message .= '<tr>'.$print_gp.'</tr>';

	$t_plan += $data_cek[2];
	$t_act += $data_cek[3];
	$t_gap += $data_cek[4];
}

if($t_gap < 0){
	$tot_gap = '<td style="background-color: #D2D2D2; color: #FF0000;" align="right"><b>'.number_format($t_gap).'</b></td>';
}else{
	$tot_gap = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_gap).'</b></td>';
}

$message.= '<tr>
        <td colspan="3" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan).'</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act).'</b></td>
        '.$tot_gap.'
      </tr>
    </table>
  </div>';

$message.='
<p>Do not reply this email.<br/><p>
<p>Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="80"/></p>';

$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
$mail->addAttachment($link);


//echo $message;
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
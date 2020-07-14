<?php
// Create By : Ueng hernama
// Date : 1-mar-2018
// ID = 2
include("../connect/conn.php");
//$dataXLS = 'C:\xampp/Kuraire/wms/schedule/SPAREPARTS_PO_REPORT.xls';
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';

//set shift 3
$ins2 = "update ztb_kanban_lbl set mulai =  trim((select sysdate - 1 from dual)) where trim(mulai) = trim((select sysdate from dual)) and cast(substr(startdate,12,2) as int) < 7";
$data_ins2 = oci_parse($connect, $ins2);
oci_execute($data_ins2);

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

//  $mail->addAddress('labellr@fdk.co.jp', 'labellr@fdk.co.jp');
// $mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
// $mail->addAddress('antonius@fdk.co.jp', 'antonius@fdk.co.jp');
// $mail->addAddress('fifin2@fdk.co.jp', 'fifin2@fdk.co.jp');
// $mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
// $mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
// $mail->addAddress('wiwith@fdk.co.jp', 'wiwith@fdk.co.jp');
// $mail->addAddress('wiwiky@fdk.co.jp', 'wiwiky@fdk.co.jp');
// $mail->addAddress('yadi.kurniadi@fdk.co.jp', 'yadi.kurniadi@fdk.co.jp');
// $mail->addAddress('satrio.adiwibowo@fdk.co.jp', 'satrio.adiwibowo@fdk.co.jp');
// $mail->addAddress('setiawan@fdk.co.jp', 'setiawan@fdk.co.jp');
// $mail->addAddress('mintardi@fdk.co.jp', 'mintardi@fdk.co.jp');
// $mail->addAddress('daryanto@fdk.co.jp', 'daryanto@fdk.co.jp');
// $mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
// $mail->addAddress('samekto@fdk.co.jp', 'samekto@fdk.co.jp');
// $mail->addAddress('firdaus@fdk.co.jp', 'firdaus@fdk.co.jp');
// $mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
// $mail->addAddress('firmandita@fdk.co.jp', 'firmandita@fdk.co.jp');
// // $mail->addAddress('atsushi.sohara@fdk.co.jp', 'atsushi.sohara@fdk.co.jp');
// // $mail->addAddress('kamiya@fdk.co.jp', 'kamiya@fdk.co.jp');
// $mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');
// $mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
// $mail->addAddress('nobufumi@fdk.co.jp', 'nobufumi@fdk.co.jp');
$mail->addAddress('reza@fdk.co.jp', 'reza@fdk.co.jp');
// $mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
// $mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
// $mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');
// $mail->addAddress('anggari.nugraheni@fdk.co.jp', 'anggari.nugraheni@fdk.co.jp');
// $mail->addAddress('yoshi@fdk.co.jp', 'yoshi@fdk.co.jp');
// $mail->addAddress('hagai@fdk.co.jp', 'hagai@fdk.co.jp');

// $mail->addAddress('fifin1@fdk.co.jp', 'fifin1@fdk.co.jp');
// $mail->addAddress('fransiska@fdk.co.jp', 'fransiska@fdk.co.jp');


//Set the subject line
$mail->Subject = 'LABEL DAILY REPORT';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$arrBln = array('','January','February','March','April','May','June','July','','August','September','October','November','December');

$min_date = strtotime('-1 day',strtotime(date('Y-m-d')));
$on_date = date("l, F d, Y",$min_date);
$on_dt = date("d",$min_date);
$on_month = date("F",$min_date);
$on_Dmonth = date("d F",$min_date);
$on_monthY = date("F Y",$min_date);
$date_qry = date("Y-m-d",$min_date);

if(intval(date('d')) == 1){
	if(intval(date('m')) == 1){
		$b = 12;
		$t = intval(date('Y'))-1;
	}else{
		$b = intval(date('m')) -1;
		$t = intval(date('Y'));
	}
}else{
	$b = intval(date('m'));
	$t = intval(date('Y'));
}

if($b <= 9){
	$tgl1 = $t.'-0'.$b.'-01';
}else{
	$tgl1 = $t.'-'.$b.'-01';
}


$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-LABEL REPORT</title>
  <style>
	table {
	    border-collapse: collapse;
	}

	table, td, th {
	    border: 1px solid black;
	}
</style>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
  <h4>Dear All,</h4>
  <p>Please see the label report below : <br/></p>';

$message .='
	  <div>
	  	<p>1. Summary label report until '.$on_date.' : <br/></p>
	  	<table style="font-size: 12px;">
	  		<tr>
		       <th style="background-color: #D2D2D2;width: 200px; height: 25px;" align="center">LINE</th>
		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">INFO</th>
		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">PLAN</th>
		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">ACCUMULATION</th>
		    </tr>';

$sql0 = "select xx.labelline,
         
           xx.actual  Accum,
           xx.planqty PlanAccum,
           yy.actual ActualDaily,
           yy.planqty PlanDaily
           
    from (

                select labelline,
                 sum(shift1+ shift2 + shift3) Actual,
                
                 (select sum(qty) 
                       from ztb_label_plan_detail l
                       where to_char(date_prod,'YYYY-MM-DD') between '".$tgl1."' and '".$date_qry."'
                             and substr(l.labelline,0,instr(l.labelline,'#')-1) = bbb.labelline 
                ) PlanQty
                 
          from (
              select nvl(substr(Labelline,0,instr(labelline,'#')-1),labelline) labelline,
                     case when shift = 1 then sum(total) else 0 end Shift1,
                     case when shift = 2 then sum(total) else 0 end Shift2,
                     case when shift = 3 then sum(total) else 0 end Shift3
              
              from (
              
              select 
                     case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
                     else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
                     else 3 end end shift ,sum(battery_in) total,labelline   
              from ZTB_KANBAN_LBL s
              inner join ztb_l_plan b
              on s.idkanban = b.id
             
              where to_char(mulai,'YYYY-MM-DD') between '".$tgl1."' and  '".$date_qry."'   and labelline is not null
              group by labelline ,
                     case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
                     else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
                     else 3 end end
                )a
                group by labelline,shift
          )bbb group by labelline
         
    )xx
    left outer join (  
    


     select labelline,
                 sum(shift1+ shift2 + shift3) Actual,
                
                 (

                          select sum(qty) 
                       from ztb_label_plan_detail l
                       where to_char(date_prod,'YYYY-MM-DD') = '".$date_qry."'
                             and substr(l.labelline,0,instr(l.labelline,'#')-1) = bbb.labelline 
                ) PlanQty
                 
          from (
              select nvl(substr(Labelline,0,instr(labelline,'#')-1),labelline) labelline,
                     case when shift = 1 then sum(total) else 0 end Shift1,
                     case when shift = 2 then sum(total) else 0 end Shift2,
                     case when shift = 3 then sum(total) else 0 end Shift3
              
              from (
              
              select 
                     case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
                     else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
                     else 3 end end shift ,sum(battery_in) total,labelline   
              from ZTB_KANBAN_LBL s
              inner join ztb_l_plan b
              on s.idkanban = b.id
              inner join ztb_label_header x
              on x.wo_no = b.wo_no
              where to_char(mulai,'YYYY-MM-DD') = '".$date_qry."' and labelline is not null
              group by labelline ,
                     case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
                     else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
                     else 3 end end
                )a
                group by labelline,shift
          )bbb group by labelline
    )yy on xx.labelline = yy.labelline


";

echo $sql0;
$data0 = oci_parse($connect, $sql0);
oci_execute($data0);

$items0 = array();
$rowno0=0;
$blnc3d = 0;		$blnc6d = 0;
$blnc3c = 0;		$blnc6c = 0;
while($row0 = oci_fetch_object($data0)){
	array_push($items0, $row0);
	$rowno0++;
}

//echo json_encode($items0);
//echo '<br/><br/>';

$TotalPlanDaily = 0;
$TotalPlanAccum = 0; 
$TotalActualDaily = 0;
$TotalActualAccum = 0; 

for ($h=0; $h < count($items0) ; $h++) { 
	$ln_lr30 = str_replace('"', '', json_encode($items0[$h]->LABELLINE));
	$ln_lr60 = str_replace('"', '', json_encode($items0[$h]->LABELLINE));
	

	$TotalPlanDaily = $TotalPlanDaily + $items0[$h]->PLANDAILY;
	$TotalPlanAccum = $TotalPlanAccum + $items0[$h]->PLANACCUM;
	$TotalActualDaily = $TotalActualDaily + $items0[$h]->ACTUALDAILY;
	$TotalActualAccum = $TotalActualAccum + $items0[$h]->ACCUM;

	if ($ln_lr30 == 'LR03') {
		$blnc3d = intval(str_replace('"', '', json_encode($items0[$h]->PLANDAILY)) - str_replace('"', '', json_encode($items0[$h]->ACTUALDAILY)))*-1;
		$blnc3c = intval(str_replace('"', '', json_encode($items0[$h]->PLANACCUM)) - str_replace('"', '', json_encode($items0[$h]->ACCUM)))*-1;
		$message.= '<tr>
						<td rowspan=3 style="background-color: #DDEBF7;width: 200px;" >'.$ln_lr30.'</td>
						<td style="background-color: #E2EFDA;width: 150px;" >PLAN</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->PLANDAILY))).'</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->PLANACCUM))).'</td>
					</tr>
					<tr>
						<td style="background-color: #FCE4D6;width: 150px;" >ACTUAL</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->ACTUALDAILY))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->ACCUM))).'</td>
					</tr>
					<tr>
						<td style="background-color: #FFAA00;width: 150px;" >BALANCE</td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format($blnc3d).'</b></td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format($blnc3c).'</b></td>
					</tr>';
		



	}else{
		$blnc6d = intval(str_replace('"', '', json_encode($items0[$h]->PLANDAILY)) - str_replace('"', '', json_encode($items0[$h]->ACTUALDAILY)))*-1;
		$blnc6c = intval(str_replace('"', '', json_encode($items0[$h]->PLANACCUM)) - str_replace('"', '', json_encode($items0[$h]->ACCUM)))*-1;
		$message.= '<tr>
						<td rowspan=3 style="background-color: #DDEBF7;width: 200px;" >'.$ln_lr60.'</td>
						<td style="background-color: #E2EFDA;width: 150px;" >PLAN</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->PLANDAILY))).'</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->PLANACCUM))).'</td>
					</tr>
					<tr>
						<td style="background-color: #FCE4D6;width: 150px;" >ACTUAL</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->ACTUALDAILY))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items0[$h]->ACCUM))).'</td>
					</tr>
					<tr>
						<td style="background-color: #FFAA00;width: 150px;" >BALANCE</td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format($blnc6d).'</b></td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format($blnc6c).'</b></td>
					</tr>';
	}


	
}

			$message.= '<tr>
						<td rowspan=3 style="background-color: #DDEBF7;width: 200px;" >TOTAL</td>
						<td style="background-color: #E2EFDA;width: 150px;" >PLAN</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format($TotalPlanDaily).'</td>
						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format($TotalPlanAccum).'</td>
					</tr>
					<tr>
						<td style="background-color: #FCE4D6;width: 150px;" >ACTUAL</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format($TotalActualDaily).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format($TotalActualAccum).'</td>
					</tr>
					<tr>
						<td style="background-color: #FFAA00;width: 150px;" >BALANCE</td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format( $TotalActualDaily - $TotalPlanDaily ).'</b></td>
						<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format( $TotalActualAccum - $TotalPlanAccum ).'</b></td>
					</tr>';


$message .='
	  	</table>
	  </div><br/>
';


$message .='
  	  <div style="margin-top: 20px;">
  	  <p>&nbsp;&nbsp;&nbsp; b. Label report details on '.$on_date.' : <br/></p>
	  <table style="font-size: 13px;">
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 500px; height: 25px;" align="center">Line - Remark </th>
	       <th style="background-color: #D2D2D2;width: 200px; height: 25px;" align="center">Accumulation / Line </th>
	       <th style="background-color: #D2D2D2;width: 200px; height: 25px;" align="center">Daily / Line </th>
	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-1</th>
	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-2</th>
	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-3</th>
	    </tr>';

$sql = "


	select * from (
		select aaa.labelline,
	         
			    Accumulation,
	           sum(shift1) + 
			       sum(shift2) + 
			       sum(shift3) Daily ,
	           sum(shift1) shift1,
			       sum(shift2) shift2,
			       sum(shift3) shift3
		       
		from   (select sum(battery_in) Accumulation,labelline from ZTB_KANBAN_LBL xx
		          where to_char(xx.mulai,'yyyy-mm-dd') between '".$tgl1."' and  '".$date_qry."' 
		                group by  xx.labelline
		     )aaa
		      left outer join 
		(
		    select Labelline,
		           case when shift = 1 then sum(total) else 0 end Shift1,
		           case when shift = 2 then sum(total) else 0 end Shift2,
		           case when shift = 3 then sum(total) else 0 end Shift3
		    
		   from
		     (
		    
			    select 
			           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
			           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
			           else 3 end end shift ,sum(battery_in) total,labelline   
			    from ZTB_KANBAN_LBL s
			    inner join ztb_l_plan b
			    on s.idkanban = b.id
			    
			    where to_char(mulai,'yyyy-mm-dd') = '".$date_qry."' and labelline is not null
			    group by labelline ,
			           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
			           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
			           else 3 end end
		      )a 
		 group by labelline,shift
	)bbb on aaa.labelline = bbb.labelline group by aaa.labelline,Accumulation)
	where accumulation <> 0   AND LABELLINE not in('LR03','LR6') AND (Accumulation is not null or Accumulation = 0)
    ORDER BY LABELLINE


	";

//echo $sql;
$data = oci_parse($connect, $sql);
oci_execute($data);

$items = array();
$rowno=0;
$tot3_1 = 0;		$tot3_2 = 0;		$tot3_3 = 0;	$planqty3_2 = 0;		$planqty3_3 = 0;
$tot6_1 = 0;		$tot6_2 = 0;		$tot6_3 = 0;	$planqty6_2 = 0;		$planqty6_3 = 0;

$daily3 = 0;		$Accum3 = 0;
$daily6 = 0;		$Accum6 = 0;

while($row = oci_fetch_object($data)){
	array_push($items, $row);
	$ln3 = explode("#", $row->LABELLINE);
	$ln6 = explode("#", $row->LABELLINE);

	if($ln3[0] == 'LR03'){
		$tot3_1 += $items[$rowno]->SHIFT1;
		$tot3_2 += $items[$rowno]->SHIFT2;
		$tot3_3 += $items[$rowno]->SHIFT3;
		$daily3 += $items[$rowno]->DAILY;
		$Accum3 += $items[$rowno]->ACCUMULATION;
	}elseif($ln6[0] == 'LR6'){
		$tot6_1 += $items[$rowno]->SHIFT1;
		$tot6_2 += $items[$rowno]->SHIFT2;
		$tot6_3 += $items[$rowno]->SHIFT3;
		$daily6 += $items[$rowno]->DAILY;
		$Accum6 += $items[$rowno]->ACCUMULATION;
	}
		$tot3_6_1 =  $tot3_1 + $tot6_1;
		$tot3_6_2 =  $tot3_2 + $tot6_2;
		$tot3_6_3 =  $tot3_3 + $tot6_3;
		$daily = $daily3 + $daily6;
		$Accum = $Accum3 + $Accum6;
	$rowno++;
}

//echo json_encode($items);

for ($i=0; $i < count($items) ; $i++) { 
	$ln_lr3 = substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4);
	$ln_lr6 = substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 3);

	if($i == 0){
		$message.= '<tr>
						<td colspan=6 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4).'</b></td>;';
	// 				</tr>
	// 				<tr>
	// 					<td style="background-color: #E2EFDA;width: 200px;" >PLAN '.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4).'</td>
	// 					<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY))).'</td>
	// 					<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - $tot3_1 ))).'</td>
	// 					<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - ($tot3_1 + $tot3_2) ))).'</td>
	// 				</tr>';		
	}

	if ($ln_lr3 == 'LR03') {
		$message.= '<tr>
						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items[$i]->LABELLINE)).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->ACCUMULATION))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->DAILY))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT1))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT2))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT3))).'</td>
					</tr>';
	}elseif($ln_lr6 == 'LR6'){
		if($i == 2){
			$message.= '<tr>
							<td style="background-color: #DDEBF7;width: 200px;" >SUB TOTAL ACTUAL LR#03 </td>
							
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($Accum3))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($daily3))).'</td>

							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_1))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_2))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_3))).'</td>
						</tr>
						
						<tr>
							<td colspan=6 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 3).'</b></td>
						</tr>
						';
		}

		$message.= '<tr>
						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items[$i]->LABELLINE)).'</td>

						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->ACCUMULATION))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->DAILY))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT1))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT2))).'</td>
						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT3))).'</td>
					</tr>';

		if($i == count($items)-1){
			$message.= '<tr>
							<td style="background-color: #DDEBF7;width: 200px;" >SUB TOTAL ACTUAL LR#6 </td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($Accum6))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($daily6))).'</td>

							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_1))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_2))).'</td>
							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_3))).'</td>
						</tr>
						';
		}
	}

}

$message.= '<tr>
							<td style="background-color: #D2D2D2;width: 200px;" >TOTAL ALL </td>
							
							<td style="background-color: #D2D2D2;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($Accum))).'</td>
							<td style="background-color: #D2D2D2;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($daily))).'</td>

							<td style="background-color: #D2D2D2;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_6_1))).'</td>
							<td style="background-color: #D2D2D2;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_6_2))).'</td>
							<td style="background-color: #D2D2D2;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_6_3))).'</td>
						</tr>
						
					
						';

$message.='</table>
		</div>';

// $message.='		
// 		<div style="margin-top: 20px;">
// 		   <p>2. Label report details each shift : <br/></p>
// 		   <p>&nbsp;&nbsp;&nbsp; a. Label report details until '.$on_date.' : <br/></p>
// 		   <table style="font-size: 13px;">
// 		  	<tr>
// 		       <th style="background-color: #D2D2D2;width: 200px; height: 25px;" align="center">REMARK</th>
// 		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-1</th>
// 		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-2</th>
// 		       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-3</th>
// 		    </tr>';

// $sql2 = "select * from (
// 	select labelline,
// 		       sum(shift1) shift1,
// 		       sum(shift2) shift2,
// 		       sum(shift3) shift3,
// 		       (select sum(qty) from ztb_label_header r
// 		          inner join ztb_label_detail l
// 		          on r.wo_no = l.wo_no
// 		          where to_char(date_prod,'yyyy-mm-dd') between '".$tgl1."' AND '".$date_qry."'
// 		                and item_type = substr(labelline,1,instr(labelline,'#',1,1)-1)
// 		      ) PlanQty
		       
// 		from (
// 		    select Labelline,
// 		           case when shift = 1 then sum(total) else 0 end Shift1,
// 		           case when shift = 2 then sum(total) else 0 end Shift2,
// 		           case when shift = 3 then sum(total) else 0 end Shift3
		    
// 		    from (
		    
// 		    select 
// 		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
// 		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
// 		           else 3 end end shift ,sum(battery_in) total,labelline   
// 		    from ZTB_KANBAN_LBL s
// 		    inner join ztb_l_plan b
// 		    on s.idkanban = b.id
// 		    inner join ztb_label_header x
// 		    on x.wo_no = b.wo_no
// 		    where to_char(mulai,'yyyy-mm-dd') between '".$tgl1."' AND '".$date_qry."' and labelline is not null
// 		    group by labelline ,
// 		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
// 		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
// 		           else 3 end end
// 		      )a
// 		      group by labelline,shift
// 		)bbb group by labelline)
// 	where (shift1 != 0 OR shift2 != 0 OR shift3 != 0) AND LABELLINE not in('LR03','LR6') AND (PLANQTY is not null or planqty = 0)";

// $data2 = oci_parse($connect, $sql2);
// oci_execute($data2);

// $items2 = array();
// $rowno2=0;
// $tot3_12 = 0;		$tot3_22 = 0;		$tot3_32 = 0;	$planqty3_22 = 0;		$planqty3_32 = 0;
// $tot6_12 = 0;		$tot6_22 = 0;		$tot6_32 = 0;	$planqty6_22 = 0;		$planqty6_32 = 0;

// while($row2 = oci_fetch_object($data2)){
// 	array_push($items2, $row2);
// 	$ln32 = explode("#", $row2->LABELLINE);
// 	$ln62 = explode("#", $row2->LABELLINE);

// 	if($ln32[0] == 'LR03'){
// 		$tot3_12 += $items2[$rowno2]->SHIFT1;
// 		$tot3_22 += $items2[$rowno2]->SHIFT2;
// 		$tot3_32 += $items2[$rowno2]->SHIFT3;
// 	}elseif($ln62[0] == 'LR6'){
// 		$tot6_12 += $items2[$rowno2]->SHIFT1;
// 		$tot6_22 += $items2[$rowno2]->SHIFT2;
// 		$tot6_32 += $items2[$rowno2]->SHIFT3;
// 	}
// 	$rowno2++;
// }

// //echo json_encode($items2);
// //echo '<br/><br/>';

// for ($j=0; $j < count($items2) ; $j++) { 
// 	$ln_lr32 = substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 4);
// 	$ln_lr62 = substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 3);

// 	if($j == 0){
// 		$message.= '<tr>
// 						<td colspan=4 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 4).'</b></td>
// 					</tr>
// 					<tr>
// 						<td style="background-color: #E2EFDA;width: 200px;" >PLAN '.substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 4).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY))).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - $tot3_12))).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - ($tot3_12 + $tot3_22)))).'</td>
// 					</tr>';		
// 	}

// 	if ($ln_lr32 == 'LR03') {
// 		$message.= '<tr>
// 						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items2[$j]->LABELLINE)).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT1))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT2))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT3))).'</td>
// 					</tr>';
// 	}elseif($ln_lr62 == 'LR6'){
// 		if($j == 2){
// 			$message.= '<tr>
// 							<td style="background-color: #DDEBF7;width: 200px;" >TOTAL ACTUAL</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_12))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_22))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_32))).'</td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #FFAA00;width: 200px;" >BALANCE</td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j-1]->PLANQTY - $tot3_12))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j-1]->PLANQTY - ($tot3_12 + $tot3_22)))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j-1]->PLANQTY - ($tot3_12 + $tot3_22 + $tot3_32)))*(-1)).'</b></td>
// 						</tr>
// 						<tr>
// 							<td colspan=4 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 3).'</b></td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #E2EFDA;width: 200px;" >PLAN '.substr(str_replace('"', '', json_encode($items2[$j]->LABELLINE)), 0, 3).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY))).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - $tot6_12))).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - ($tot6_12 + $tot6_22) ))).'</td>
// 						</tr>';
// 		}

// 		$message.= '<tr>
// 						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items2[$j]->LABELLINE)).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT1))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT2))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items2[$j]->SHIFT3))).'</td>
// 					</tr>';

// 		if($j == count($items2)-1){
// 			$message.= '<tr>
// 							<td style="background-color: #DDEBF7;width: 200px;" >TOTAL ACTUAL</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_12))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_22))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_32))).'</td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #FFAA00;width: 200px;" >BALANCE</td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - $tot6_12 ))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - ($tot6_12 + $tot6_22) ))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items2[$j]->PLANQTY - ($tot6_12 + $tot6_22 + $tot6_32) ))*(-1)).'</b></td>
// 						</tr>';
// 		}
// 	}

// }


// $message.='</table>
// 		</div>';

// $message .='
//   	  <div style="margin-top: 20px;">
//   	  <p>&nbsp;&nbsp;&nbsp; b. Label report details on '.$on_date.' : <br/></p>
// 	  <table style="font-size: 13px;">
// 	  	<tr>
// 	       <th style="background-color: #D2D2D2;width: 200px; height: 25px;" align="center">REMARK</th>
// 	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-1</th>
// 	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-2</th>
// 	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">SHIFT-3</th>
// 	    </tr>';

// $sql = "select * from (
// 	select labelline,
// 		       sum(shift1) shift1,
// 		       sum(shift2) shift2,
// 		       sum(shift3) shift3,
// 		       (select sum(qty) from ztb_label_header r
// 		          inner join ztb_label_detail l
// 		          on r.wo_no = l.wo_no
// 		          where to_char(date_prod,'yyyy-mm-dd') = '".$date_qry."'
// 		                and item_type = substr(labelline,1,instr(labelline,'#',1,1)-1)
// 		      ) PlanQty
		       
// 		from (
// 		    select Labelline,
// 		           case when shift = 1 then sum(total) else 0 end Shift1,
// 		           case when shift = 2 then sum(total) else 0 end Shift2,
// 		           case when shift = 3 then sum(total) else 0 end Shift3
		    
// 		    from (
		    
// 		    select 
// 		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
// 		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
// 		           else 3 end end shift ,sum(battery_in) total,labelline   
// 		    from ZTB_KANBAN_LBL s
// 		    inner join ztb_l_plan b
// 		    on s.idkanban = b.id
// 		    inner join ztb_label_header x
// 		    on x.wo_no = b.wo_no
// 		    where to_char(mulai,'yyyy-mm-dd') = '".$date_qry."' and labelline is not null
// 		    group by labelline ,
// 		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
// 		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
// 		           else 3 end end
// 		      )a
// 		      group by labelline,shift
// 		)bbb group by labelline)
// 	where (shift1 != 0 OR shift2 != 0 OR shift3 != 0) AND LABELLINE not in('LR03','LR6') AND (PLANQTY is not null or planqty = 0)";
// $data = oci_parse($connect, $sql);
// oci_execute($data);

// $items = array();
// $rowno=0;
// $tot3_1 = 0;		$tot3_2 = 0;		$tot3_3 = 0;	$planqty3_2 = 0;		$planqty3_3 = 0;
// $tot6_1 = 0;		$tot6_2 = 0;		$tot6_3 = 0;	$planqty6_2 = 0;		$planqty6_3 = 0;

// while($row = oci_fetch_object($data)){
// 	array_push($items, $row);
// 	$ln3 = explode("#", $row->LABELLINE);
// 	$ln6 = explode("#", $row->LABELLINE);

// 	if($ln3[0] == 'LR03'){
// 		$tot3_1 += $items[$rowno]->SHIFT1;
// 		$tot3_2 += $items[$rowno]->SHIFT2;
// 		$tot3_3 += $items[$rowno]->SHIFT3;
// 	}elseif($ln6[0] == 'LR6'){
// 		$tot6_1 += $items[$rowno]->SHIFT1;
// 		$tot6_2 += $items[$rowno]->SHIFT2;
// 		$tot6_3 += $items[$rowno]->SHIFT3;
// 	}
// 	$rowno++;
// }

// //echo json_encode($items);

// for ($i=0; $i < count($items) ; $i++) { 
// 	$ln_lr3 = substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4);
// 	$ln_lr6 = substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 3);

// 	if($i == 0){
// 		$message.= '<tr>
// 						<td colspan=4 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4).'</b></td>
// 					</tr>
// 					<tr>
// 						<td style="background-color: #E2EFDA;width: 200px;" >PLAN '.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 4).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY))).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - $tot3_1 ))).'</td>
// 						<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - ($tot3_1 + $tot3_2) ))).'</td>
// 					</tr>';		
// 	}

// 	if ($ln_lr3 == 'LR03') {
// 		$message.= '<tr>
// 						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items[$i]->LABELLINE)).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT1))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT2))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT3))).'</td>
// 					</tr>';
// 	}elseif($ln_lr6 == 'LR6'){
// 		if($i == 2){
// 			$message.= '<tr>
// 							<td style="background-color: #DDEBF7;width: 200px;" >TOTAL ACTUAL</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_1))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_2))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot3_3))).'</td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #FFAA00;width: 200px;" >BALANCE</td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i-1]->PLANQTY - $tot3_1))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i-1]->PLANQTY - ($tot3_1 + $tot3_2) ))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i-1]->PLANQTY - ($tot3_1 + $tot3_2 + $tot3_3) ))*(-1)).'</b></td>
// 						</tr>
// 						<tr>
// 							<td colspan=4 style="background-color: #D2D2D2; height: 25px;" align="center"><b>'.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 3).'</b></td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #E2EFDA;width: 200px;" >PLAN '.substr(str_replace('"', '', json_encode($items[$i]->LABELLINE)), 0, 3).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY))).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - $tot6_1 ))).'</td>
// 							<td style="background-color: #E2EFDA;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - ($tot6_1 + $tot6_2) ))).'</td>
// 						</tr>';
// 		}

// 		$message.= '<tr>
// 						<td style="background-color: #FCE4D6;width: 200px;" >ACTUAL '.str_replace('"', '', json_encode($items[$i]->LABELLINE)).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT1))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT2))).'</td>
// 						<td style="background-color: #FCE4D6;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($items[$i]->SHIFT3))).'</td>
// 					</tr>';

// 		if($i == count($items)-1){
// 			$message.= '<tr>
// 							<td style="background-color: #DDEBF7;width: 200px;" >TOTAL ACTUAL</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_1))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_2))).'</td>
// 							<td style="background-color: #DDEBF7;width: 150px;" align="right">'.number_format(str_replace('"', '', json_encode($tot6_3))).'</td>
// 						</tr>
// 						<tr>
// 							<td style="background-color: #FFAA00;width: 200px;" >BALANCE</td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - $tot6_1))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - ($tot6_1 + $tot6_2) ))*(-1)).'</b></td>
// 							<td style="background-color: #FFAA00;width: 150px;" align="right"><b>'.number_format(str_replace('"', '', json_encode($items[$i]->PLANQTY - ($tot6_1 + $tot6_2 + $tot6_3) ))*(-1)).'</b></td>
// 						</tr>';
// 		}
// 	}

// }

// $message.='</table>
// 		</div>';





$message.='
<p>Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="75"/></p>';
$message.='
		</div>
	</body>
</html>';

//$mail->msgHTML($message);

if (intval($on_dt) <= 7){
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Comparison Report '.$$arrBln[intval(date('m'))-1].'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Production By Line Report '.$arrBln[intval(date('m'))-1].'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Comparison Report '.$on_month.'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Production By Line Report '.$on_month.'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Summary Comparison Report '.$on_Dmonth.'.xls');
}else{
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Comparison Report '.$on_month.'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Production By Line Report '.$on_month.'.xls');
	$mail->AddAttachment('D:\Program/LabelDailyReport/FileExcel/Label Summary Comparison Report '.$on_Dmonth.'.xls');
}
//send the message, check for errors

echo $message;
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
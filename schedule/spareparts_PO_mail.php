<?php
// Create By : Ueng hernama
// Date : 24-oct-2017
// ID = 2

// update By : Ueng hernama
// Date : 12-nov-2019
// ID = 2

include("../connect/conn2.php");
$dataXLS = 'C:\xampp/Kuraire/wms/schedule/SPAREPARTS_PO_REPORT.xls';
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
$mail->Host = "virus.fdk.co.jp";
$mail->Port = 25;
$mail->Username = "do.not.reply.fdkindonesia";
$mail->Password = "fidonot";
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

$mail->addAddress('prihartanto@fdk.co.jp', 'prihartanto@fdk.co.jp');
$mail->addAddress('yoga.kristianto@fdk.co.jp', 'yoga.kristianto@fdk.co.jp');
$mail->addAddress('yaser.ali@fdk.co.jp', 'yaser.ali@fdk.co.jp');
$mail->addAddress('ferry.agung@fdk.co.jp', 'ferry.agung@fdk.co.jp');
$mail->addAddress('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
$mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
$mail->addAddress('wahyu@fdk.co.jp', 'wahyu@fdk.co.jp');
$mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
$mail->addAddress('anton.yuhadi@fdk.co.jp', 'anton.yuhadi@fdk.co.jp');
$mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
$mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
$mail->addAddress('santi.cipta@fdk.co.jp', 'santi.cipta@fdk.co.jp');
$mail->addAddress('budi.setiadi@fdk.co.jp', 'budi.setiadi@fdk.co.jp');
$mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');
$mail->addAddress('rahmat.budiyanto@fdk.co.jp', 'rahmat.budiyanto@fdk.co.jp');
$mail->addAddress('darmaji@fdk.co.jp', 'darmaji@fdk.co.jp');
$mail->addAddress('endang.kosasih@fdk.co.jp', 'endang.kosasih@fdk.co.jp');
$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
$mail->addAddress('rokhani@fdk.co.jp', 'rokhani@fdk.co.jp');
$mail->addAddress('heru.wibowo@fdk.co.jp','heru.wibowo@fdk.co.jp');
$mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
$mail->addAddress('aris@fdk.co.jp', 'aris@fdk.co.jp');
$mail->addAddress('setiawan@fdk.co.jp', 'setiawan@fdk.co.jp');
$mail->addAddress('satrio.adiwibowo@fdk.co.jp', 'satrio.adiwibowo@fdk.co.jp');
$mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
$mail->addAddress('handiko.haminanto@fdk.co.jp', 'handiko.haminanto@fdk.co.jp');
$mail->addAddress('ardian.ary@fdk.co.jp', 'ardian.ary@fdk.co.jp');
$mail->addAddress('widodo@fdk.co.jp', 'widodo@fdk.co.jp');
$mail->addAddress('labellr@fdk.co.jp', 'labellr@fdk.co.jp');

//$mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
$mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
$mail->addAddress('natsume@fdk.co.jp', 'natsume@fdk.co.jp');
$mail->addAddress('ishimasa@fdk.co.jp', 'ishimasa@fdk.co.jp');
$mail->addAddress('tsuboi_satoshi@fdk.co.jp', 'tsuboi_satoshi@fdk.co.jp');

$mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
$mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');

$mail->addAddress('yoshi@fdk.co.jp', 'yoshi@fdk.co.jp');
$mail->addAddress('hagai@fdk.co.jp', 'hagai@fdk.co.jp');

//Set the subject line
$mail->Subject = 'SPAREPARTS PURCHASE REPORT';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$arrBln = array('DECEMBER','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER','JANUARY','FEBRUARY','MARCH','APRIL');

$m  = $arrBln[intval(date('m'))];

if ($m == 'SEPTEMBER'){
	$mm1 = 'AUGUST';		$tm1 = intval(date('Y')); 
	$m = 'SEPTEMBER';		$t = intval(date('Y'));
	$m1 = 'OCTOBER';		$t1 = intval(date('Y')); 
	$m2 = 'NOVEMBER';		$t2 = intval(date('Y')); 
	$m3 = 'DECEMBER';		$t3 = intval(date('Y')); 
	$m4 = 'JANUARY';		$t4 = intval(date('Y')+1); 
}else if ($m == 'OCTOBER'){ 
	$mm1 = 'SEPTEMBER';		$tm1 = intval(date('Y')); 
	$m = 'OCTOBER';			$t = intval(date('Y'));
	$m1 = 'NOVEMBER';		$t1 = intval(date('Y')); 
	$m2 = 'DECEMBER';		$t2 = intval(date('Y')); 
	$m3 = 'JANUARY';		$t3 = intval(date('Y')+1); 
	$m4 = 'FEBRUARY';		$t4 = intval(date('Y')+1); 
}else if ($m == 'NOVEMBER'){ 
	$mm1 = 'OCTOBER';		$tm1 = intval(date('Y')); 
	$m = 'NOVEMBER';		$t = intval(date('Y'));
	$m1 = 'DECEMBER';		$t1 = intval(date('Y')); 
	$m2 = 'JANUARY';		$t2 = intval(date('Y')+1); 
	$m3 = 'FEBRUARY';		$t3 = intval(date('Y')+1); 
	$m4 = 'MARCH';			$t4 = intval(date('Y')+1); 
}else if ($m == 'DECEMBER'){
	$mm1 = 'NOVEMBER';		$tm1 = intval(date('Y')); 
	$m = 'DECEMBER';		$t = intval(date('Y'));
	$m1 = 'JANUARY';		$t1 = intval(date('Y')+1); 
	$m2 = 'FEBRUARY';		$t2 = intval(date('Y')+1); 
	$m3 = 'MARCH';			$t3 = intval(date('Y')+1); 
	$m4 = 'APRIL';			$t4 = intval(date('Y')+1); 
}else{
	$mm1  = $arrBln[intval(date('m')-1)];		$tm1 = intval(date('Y')); 
	$m  = $arrBln[intval(date('m'))];			$t = intval(date('Y'));
	$m1 = $arrBln[intval(date('m'))+1];			$t1 = intval(date('Y')); 
	$m2 = $arrBln[intval(date('m'))+2];			$t2 = intval(date('Y')); 
	$m3 = $arrBln[intval(date('m'))+3];			$t3 = intval(date('Y')); 
	$m4 = $arrBln[intval(date('m'))+4];			$t4 = intval(date('Y')); 
}

$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-SPAREPARTS PURCHASE REPORT</title>
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
  <p>Please see the spare parts purchase '.$m.' :<br/></p>';

//cek bulan lalu yg belom datang
$cek = "select sum(poh.ex_rate * pod.u_price * pod.bal_qty) as not_arrive from po_details pod
inner join po_header poh on pod.po_no=poh.po_no
where to_char(pod.ETA,'YYYYMM') = (select to_char(ADD_MONTHS(sysdate,-1),'YYYYMM') from dual)";
$dt_cek = oci_parse($connect, $cek);
oci_execute($dt_cek);
$data_cek=oci_fetch_object($dt_cek);
$not_arrive_bulan_lalu = $data_cek->NOT_ARRIVE;


$message .='
  	  <div >
	  <table>
	  	<tr>
	       <th rowspan=2 style="background-color: #D2D2D2;width: 40px; " align="center">NO.</th>
	       <th rowspan=2 style="background-color: #D2D2D2;width: 200px;" align="center">DEPARTMENT</th>';

if($not_arrive_bulan_lalu != 0){
$message .='
		   <th colspan=5 style="background-color: #D2D2D2;width: 150px;" align="center">'.$mm1.'</th>';
}
	       
$message .='
	       <th colspan=5 style="background-color: #99CC99;width: 150px;" align="center">'.$m.'</th>
	       <th colspan=3 style="background-color: #66CCCC;width: 150px;" align="center">'.$m1.'</th>
	       <th colspan=3 style="background-color: #99CC99;width: 150px;" align="center">'.$m2.'</th>
	       <th colspan=3 style="background-color: #66CCCC;width: 150px;" align="center">'.$m3.'</th>
	      
	    </tr>
	    <tr>';

if($not_arrive_bulan_lalu != 0){
$message .='
		   <th style="background-color: #D2D2D2;width: 150px;" align="center">PURCHASE<br/>NOT ARRIVE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">PURCHASE<br/>ARRIVE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">TOTAL<br/>FORECAST<br/>EXPENSE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">TOTAL<br/>PURCHASE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">PURCHASE<br/>VS<br/>FORECAST<br/>EXPENSE</th>';
}
	       
$message .='
	       <th style="background-color: #99CC99;width: 150px;" align="center">PURCHASE<br/>NOT ARRIVE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">PURCHASE<br/>ARRIVE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">TOTAL<br/>FORECAST<br/>EXPENSE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">TOTAL<br/>PURCHASE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">PURCHASE<br/>VS<br/>FORECAST<br/>EXPENSE</th>

	       <th style="background-color: #66CCCC;width: 150px;" align="center">TOTAL<br/>FORECAST<br/>EXPENSE</th>
	       <th style="background-color: #66CCCC;width: 150px;" align="center">TOTAL<br/>PURCHASE</th>
	       <th style="background-color: #66CCCC;width: 150px;" align="center">PURCHASE<br/>VS<br/>FORECAST<br/>EXPENSE</th>

	       <th style="background-color: #99CC99;width: 150px;" align="center">TOTAL<br/>FORECAST<br/>EXPENSE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">TOTAL<br/>PURCHASE</th>
	       <th style="background-color: #99CC99;width: 150px;" align="center">PURCHASE<br/>VS<br/>FORECAST<br/>EXPENSE</th>

	       <th style="background-color: #66CCCC;width: 150px;" align="center">TOTAL<br/>FORECAST<br/>EXPENSE</th>
	       <th style="background-color: #66CCCC;width: 150px;" align="center">TOTAL<br/>PURCHASE</th>
	       <th style="background-color: #66CCCC;width: 150px;" align="center">PURCHASE<br/>VS<br/>FORECAST<br/>EXPENSE</th>
	    </tr>';

$no=1;
$sql = "select aa.department,
		   sum(Total_Expense) Total_Expense,
           z.$m,
           sum(Sparts_Expense_Not_Arrive_1) Sparts_Expense_Not_Arrive_1,sum(Sparts_Expense_Arrive_1) Sparts_Expense_Arrive_1,
		   sum(Sparts_Expense_Not_Arrive) Sparts_Expense_Not_Arrive,
		   sum(Sparts_Expense_Arrive) Sparts_Expense_Arrive,
		   z1.$m1,
           sum(Total_Expense_Next_Month) Total_Expense_Next_Month,
           z2.$m2,
           sum(Total_Expense_Next_Two_Month) Total_Expense_Next_Two_Month,
           z3.$m3,
           sum(Total_Expense_Next_Three_Month) Total_Expense_Next_Three_Month,
           zm1.$mm1,
           sum(Total_Expense_Next_Four_Month) Total_Expense_Next_Four_Month
		   from 
    (select department, nvl($mm1,0) as $mm1 from ztb_sp_budget where tahun='$tm1') zm1
    inner join (select department, nvl($m,0) as $m from ztb_sp_budget where tahun='$t') z on zm1.department=z.department
    inner join (select department, nvl($m1,0) as $m1 from ztb_sp_budget where tahun='$t1') z1 on zm1.department=z1.department
    inner join (select department, nvl($m2,0) as $m2 from ztb_sp_budget where tahun='$t2')  z2 on zm1.department=z2.department
    inner join (select department, nvl($m3,0) as $m3 from ztb_sp_budget where tahun='$t3') z3 on zm1.department=z3.department
    inner join (select department, nvl($m4,0) as $m4 from ztb_sp_budget where tahun='$t4') z4 on zm1.department=z4.department
   
    left outer join (       
		select case substr(trim(mpr_no),0,1) 
		                    when 'A' Then 'Assembling'
		                    when 'C' Then 'Component'
		                    when 'F' Then 'Finishing'
		                    when 'M' Then 'PE'
		                    when 'Q' Then 'QC' END 
		                    Department,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(sysdate,'YYYYMM') from dual) then sum(ex_rate * u_price * qty) else 0 end Total_Expense,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(sysdate,'YYYYMM') from dual) then sum(ex_rate * u_price * bal_qty) else 0 end Sparts_Expense_Not_Arrive,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(sysdate,'YYYYMM') from dual) then sum(ex_rate * u_price * gr_qty) else 0 end Sparts_Expense_Arrive,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(to_char(ADD_MONTHS(sysdate,1),'YYYYMM')) from dual) then sum(ex_rate * u_price * qty) else 0 end Total_Expense_Next_Month,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(to_char(ADD_MONTHS(sysdate,2),'YYYYMM')) from dual) then sum(ex_rate * u_price * qty) else 0 end Total_Expense_Next_Two_Month,
               case when to_char(s.ETA,'YYYYMM') = (select to_char(to_char(ADD_MONTHS(sysdate,3),'YYYYMM')) from dual) then sum(ex_rate * u_price * qty) else 0 end Total_Expense_Next_Three_Month,
               case when to_char(s.ETA,'YYYYMM') = (select to_char(to_char(ADD_MONTHS(sysdate,-1),'YYYYMM')) from dual) then sum(ex_rate * u_price * qty) else 0 end Total_Expense_Next_four_Month,
		       to_char(s.ETA,'YYYYMM') ETA,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(ADD_MONTHS(sysdate,-1),'YYYYMM') from dual) then sum(ex_rate * u_price * bal_qty) else 0 end Sparts_Expense_Not_Arrive_1,
		       case when to_char(s.ETA,'YYYYMM') = (select to_char(ADD_MONTHS(sysdate,-1),'YYYYMM') from dual) then sum(ex_rate * u_price * gr_qty) else 0 end Sparts_Expense_Arrive_1
		from po_details s
		inner join po_header r on s.po_no = r.po_no
		where   to_char(s.ETA,'YYYYMM') between (select to_char(sysdate,'YYYYMM') from dual) and 
			(select to_char(ADD_MONTHS(sysdate,4),'YYYYMM') from dual)
		group by substr(trim(mpr_no),0,1),to_char(s.ETA,'YYYYMM') 
	)aa on upper(aa.department) = z.department and aa.department is not null
    group by aa.department, z.$m, z1.$m1, z2.$m2, z3.$m3, zm1.$mm1
    having sum(Total_Expense) is not null
    order by aa.department asc";

$dt = oci_parse($connect, $sql);
oci_execute($dt);

$tot_a = 0;		$tot_b = 0;		$tot_c = 0;		$tot_d = 0;		$tot_e = 0; $tot_f = 0; $tot_g = 0;
while ($data=oci_fetch_object($dt)){
	$l_a = $data->$m - $data->TOTAL_EXPENSE;
	$l_d = $data->$m1 - $data->TOTAL_EXPENSE_NEXT_MONTH;
	$l_e = $data->$m2 - $data->TOTAL_EXPENSE_NEXT_TWO_MONTH;
	$l_f = $data->$m3 - $data->TOTAL_EXPENSE_NEXT_THREE_MONTH;
	$l_g = $data->$mm1 - $data->TOTAL_EXPENSE_NEXT_FOUR_MONTH;

	$l_a_tot += $data->$m - $data->TOTAL_EXPENSE;
	$l_d_tot += $data->$m1 - $data->TOTAL_EXPENSE_NEXT_MONTH;
	$l_e_tot += $data->$m2 - $data->TOTAL_EXPENSE_NEXT_TWO_MONTH;
	$l_f_tot += $data->$m3 - $data->TOTAL_EXPENSE_NEXT_THREE_MONTH;
	$l_g_tot += $data->$mm1 - $data->TOTAL_EXPENSE_NEXT_FOUR_MONTH;

	if ($l_a<0) {
		$l_a_print = '<td style="background-color: #99CC99;color: red;" align="right">'.number_format($l_a,2).'&nbsp;</td>';
	}else{
		$l_a_print = '<td style="background-color: #99CC99;" align="right">'.number_format($l_a,2).'&nbsp;</td>';
	}

	if ($l_d<0) {
		$l_d_print = '<td style="background-color: #66CCCC;color: red;" align="right">'.number_format($l_d,2).'&nbsp;</td>';
	}else{
		$l_d_print = '<td style="background-color: #66CCCC;" align="right">'.number_format($l_d,2).'&nbsp;</td>';
	}

	if ($l_e<0) {
		$l_e_print = '<td style="background-color: #99CC99;color: red;" align="right">'.number_format($l_e,2).'&nbsp;</td>';
	}else{
		$l_e_print = '<td style="background-color: #99CC99;" align="right">'.number_format($l_e,2).'&nbsp;</td>';
	}

	if ($l_f<0) {
		$l_f_print = '<td style="background-color: #66CCCC;color: red;" align="right">'.number_format($l_f,2).'&nbsp;</td>';
	}else{
		$l_f_print = '<td style="background-color: #66CCCC;" align="right">'.number_format($l_f,2).'&nbsp;</td>';
	}

	if ($l_g<0) {
		$l_g_print = '<td style="background-color: #E2EFDA;color: red;" align="right">'.number_format($l_g,2).'&nbsp;</td>';
	}else{
		$l_g_print = '<td style="background-color: #E2EFDA;" align="right">'.number_format($l_g,2).'&nbsp;</td>';
	}

	$message.='<tr>
					<td style="background-color: #E2EFDA;">'.$no.'</td>
					<td style="background-color: #E2EFDA;">'.strtoupper($data->DEPARTMENT).'</td>';

	if($not_arrive_bulan_lalu != 0){
	$message.='
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->SPARTS_EXPENSE_NOT_ARRIVE_1,2).'&nbsp;</td>
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->SPARTS_EXPENSE_ARRIVE_1,2).'&nbsp;</td>
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->$mm1,2).'&nbsp;</td>
					<td style="background-color: #E2EFDA;" align="right">'.number_format($data->TOTAL_EXPENSE_NEXT_FOUR_MONTH,2).'&nbsp;</td>
					'.$l_g_print;
	}

	$message.='
					<td style="background-color: #99CC99;" align="right">'.number_format($data->SPARTS_EXPENSE_NOT_ARRIVE,2).'&nbsp;</td>
					<td style="background-color: #99CC99;" align="right">'.number_format($data->SPARTS_EXPENSE_ARRIVE,2).'&nbsp;</td>
					<td style="background-color: #99CC99;" align="right">'.number_format($data->$m,2).'&nbsp;</td>
					<td style="background-color: #99CC99;" align="right">'.number_format($data->TOTAL_EXPENSE,2).'&nbsp;</td>
					'.$l_a_print.'
					
					<td style="background-color: #66CCCC;" align="right">'.number_format($data->$m1,2).'&nbsp;</td>
					<td style="background-color: #66CCCC;" align="right">'.number_format($data->TOTAL_EXPENSE_NEXT_MONTH,2).'&nbsp;</td>
					'.$l_d_print.'

					<td style="background-color: #99CC99;" align="right">'.number_format($data->$m2,2).'&nbsp;</td>
					<td style="background-color: #99CC99;" align="right">'.number_format($data->TOTAL_EXPENSE_NEXT_TWO_MONTH,2).'&nbsp;</td>
					'.$l_e_print.'

					<td style="background-color: #66CCCC;" align="right">'.number_format($data->$m3,2).'&nbsp;</td>
					<td style="background-color: #66CCCC;" align="right">'.number_format($data->TOTAL_EXPENSE_NEXT_THREE_MONTH,2).'&nbsp;</td>
					'.$l_f_print.'
			   </tr>';
			    
	$tot_a += $data->TOTAL_EXPENSE;
	$tot_b += $data->SPARTS_EXPENSE_NOT_ARRIVE;
	$tot_c += $data->SPARTS_EXPENSE_ARRIVE;
	$tot_d += $data->TOTAL_EXPENSE_NEXT_MONTH;
	$tot_e += $data->TOTAL_EXPENSE_NEXT_TWO_MONTH;
	$tot_f += $data->TOTAL_EXPENSE_NEXT_THREE_MONTH;
	$tot_g += $data->TOTAL_EXPENSE_NEXT_FOUR_MONTH;
	$tot_h += $data->SPARTS_EXPENSE_NOT_ARRIVE_1;
	$tot_i += $data->SPARTS_EXPENSE_ARRIVE_1;

	$bud_0 += $data->$m;
	$bud_1 += $data->$m1;
	$bud_2 += $data->$m2;
	$bud_3 += $data->$m3;
	$bud_4 += $data->$mm1;
	
	$no++;
}

if ($l_a_tot<0) {
	$l_a_tot_print = '<td style="background-color: #99CC99; color: red;" align="right"><b>'.number_format($l_a_tot,2).'</b>&nbsp;</td>';
}else{
	$l_a_tot_print = '<td style="background-color: #99CC99;" align="right"><b>'.number_format($l_a_tot,2).'</b>&nbsp;</td>';
}

if ($l_d_tot<0) {
	$l_d_tot_print = '<td style="background-color: #66CCCC; color: red;" align="right"><b>'.number_format($l_d_tot,2).'</b>&nbsp;</td>';
}else{
	$l_d_tot_print = '<td style="background-color: #66CCCC;" align="right"><b>'.number_format($l_d_tot,2).'</b>&nbsp;</td>';
}

if ($l_e_tot<0) {
	$l_e_tot_print = '<td style="background-color: #99CC99; color: red;" align="right"><b>'.number_format($l_e_tot,2).'</b>&nbsp;</td>';
}else{
	$l_e_tot_print = '<td style="background-color: #99CC99;" align="right"><b>'.number_format($l_e_tot,2).'</b>&nbsp;</td>';
}

if ($l_f_tot<0) {
	$l_f_tot_print = '<td style="background-color: #66CCCC; color: red;" align="right"><b>'.number_format($l_f_tot,2).'</b>&nbsp;</td>';
}else{
	$l_f_tot_print = '<td style="background-color: #66CCCC;" align="right"><b>'.number_format($l_f_tot,2).'</b>&nbsp;</td>';
}

if ($l_g_tot<0) {
	$l_g_tot_print = '<td style="background-color: #D2D2D2; color: red;" align="right"><b>'.number_format($l_g_tot,2).'</b>&nbsp;</td>';
}else{
	$l_g_tot_print = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($l_g_tot,2).'</b>&nbsp;</td>';
}

$message.='<tr>
			<td colspan=2 style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>';

if($not_arrive_bulan_lalu != 0){

$message.='
			<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($tot_h,2).'</b>&nbsp;</td>
			<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($tot_i,2).'</b>&nbsp;</td>
			<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($bud_4,2).'</b>&nbsp;</td>
			<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($tot_g,2).'</b>&nbsp;</td>
			'.$l_g_tot_print ;
}

$message .='
			<td style="background-color: #99CC99;" align="right"><b>'.number_format($tot_b,2).'</b>&nbsp;</td>
			<td style="background-color: #99CC99;" align="right"><b>'.number_format($tot_c,2).'</b>&nbsp;</td>
			<td style="background-color: #99CC99;" align="right"><b>'.number_format($bud_0,2).'</b>&nbsp;</td>
			<td style="background-color: #99CC99;" align="right"><b>'.number_format($tot_a,2).'</b>&nbsp;</td>
			'.$l_a_tot_print.'
			
			<td style="background-color: #66CCCC;" align="right"><b>'.number_format($bud_1,2).'</b>&nbsp;</td>
			<td style="background-color: #66CCCC;" align="right"><b>'.number_format($tot_d,2).'</b>&nbsp;</td>
			'.$l_d_tot_print.'

			<td style="background-color: #99CC99;" align="right"><b>'.number_format($bud_2,2).'</b>&nbsp;</td>
			<td style="background-color: #99CC99;" align="right"><b>'.number_format($tot_e,2).'</b>&nbsp;</td>
			'.$l_e_tot_print.'

			<td style="background-color: #66CCCC;" align="right"><b>'.number_format($bud_3,2).'</b>&nbsp;</td>
			<td style="background-color: #66CCCC;" align="right"><b>'.number_format($tot_f,2).'</b>&nbsp;</td>
			'.$l_f_tot_print.'

			
		   </tr>';
$message.='</table>
		</div>';

$message.='
<p><u>TOTAL FORECAST</u> = Total forecast expense each section .It is for reference for each section.<br/>
Do not reply this email.<br/><br/></p>
<p>Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="75"/></p>';
$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);
$mail->AltBody = 'This is a plain-text message body';
$mail->AddAttachment($dataXLS);

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
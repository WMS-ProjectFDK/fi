<?php
set_time_limit(0);
include("../connect/conn.php");
$dataXLS = 'D:\Program/FinishingReportCopy/FINISHING_REPORT_Send.xls';

$arrBulan = array( '1' => 'JANUARY',   '2' => 'FEBRUARY',  '3' => 'MARCH',  '4' => 'APRIL',  '5' => 'MAY',  
                   '6' => 'JUNE',  '7' => 'JULY',  '8' => 'AUGUST',  '9' => 'SEPTEMBER',  '10' => 'OCTOBER', '11' => 'NOVEMBER',  '12' => 'DECEMBER');

$min_date = strtotime('-1 day',strtotime(date('Y-m-d')));
$plan_date = intval(date("d",$min_date));

if(intval(date('d')) == 1){
    if(intval(date('m')) == 1){
        $bulan = 12;
        $tahun = intval(date('Y')) - 1;
    }else{
        $bulan = intval(date('m')) -1;
        $tahun = intval(date('Y'));
    }
}else{
    $bulan = intval(date('m'));
    $tahun = intval(date('Y'));
}

$qry61 = "select batery_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation  
          from zvw_comparison_summary where bulan = '".$arrBulan[$bulan]."' and hari <= $plan_date group by batery_type";
$data61 = oci_parse($connect, $qry61);
oci_execute($data61);

$qry62 = "select label_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation,
		  case label_type 
                when 'AUTO SHRINK LR03' then 1
                when 'AUTO SHRINK LR6' then 2
                when 'MULTI SHRINK' then 3
                when 'MANUAL SHRINK' then 4
                else 10 end Urut

          from zvw_comparison_summary where bulan = '".$arrBulan[$bulan]."' and hari <= $plan_date group by label_type order by Urut";
$data62 = oci_parse($connect, $qry62);
oci_execute($data62);

$qry63 = "select label_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation,
				 case label_type 
                when 'AUTO SHRINK LR03' then 1
                when 'AUTO SHRINK LR6' then 2
                when 'MULTI SHRINK' then 3
                when 'MANUAL SHRINK' then 4
                else 10 end Urut  
          from zvw_comparison_summary where bulan = '".$arrBulan[$bulan]."' and hari = $plan_date group by label_type order by urut";
$data63 = oci_parse($connect, $qry63);
oci_execute($data63);

// $qrydelay = "select package_type, 
// 			       sum(delayQTY) DelayQty,
// 			       sum(MoveupQTY) MoveupQTY,
// 			       -1* (sum(delayQTY) -  sum(MoveupQTY)) TotalDelay
// 			from (
// 			  select package_type,
// 			         case when remark = 'Delay' then qty else 0 end DelayQTY,
// 			         case when remark = 'Moveup' then qty else 0 end MoveupQTY
// 			  from 
// 			  (
// 			  select package_type ,sum(delayqty) Qty,'Delay' remark from zvw_production_delay
// 			  group by package_type
// 			  union
// 			  select package_type,sum(moveupqty) Qty,'Moveup' from zvw_production_moveup
// 			  group by package_type
// 			  )
// 			)xx group by package_type";
// $datadelay = oci_parse($connect, $qrydelay);
// oci_execute($datadelay);

/* START BODY EMAIL*/
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';
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
$mail->Host = "smtp01.fdk.co.jp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
//$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
// $mail->Password = "V6iT7n8U";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

$mail->addAddress('ueng.hernama@fdk.co.jp', 'Ueng Hernama');
$mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');

$mail->addAddress('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
$mail->addAddress('antonius@fdk.co.jp', 'antonius@fdk.co.jp');
$mail->addAddress('endang.kosasih@fdk.co.jp', 'endang.kosasih@fdk.co.jp');
$mail->addAddress('fauzi.budi@fdk.co.jp', 'fauzi.budi@fdk.co.jp');
$mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
$mail->addAddress('agustinus@fdk.co.jp', 'agustinus@fdk.co.jp');
$mail->addAddress('wiwith@fdk.co.jp', 'wiwith@fdk.co.jp');
$mail->addAddress('wiwiky@fdk.co.jp', 'wiwiky@fdk.co.jp');

$mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
$mail->addAddress('samekto@fdk.co.jp', 'samekto@fdk.co.jp');
$mail->addAddress('firdaus@fdk.co.jp', 'firdaus@fdk.co.jp');

$mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
$mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');

$mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
$mail->addAddress('firmandita@fdk.co.jp', 'firmandita@fdk.co.jp');
$mail->addAddress('anggari.nugraheni@fdk.co.jp', 'anggari.nugraheni@fdk.co.jp');

$mail->addAddress('yadi.kurniadi@fdk.co.jp', 'yadi.kurniadi@fdk.co.jp');
$mail->addAddress('satrio.adiwibowo@fdk.co.jp', 'satrio.adiwibowo@fdk.co.jp');
$mail->addAddress('setiawan@fdk.co.jp', 'setiawan@fdk.co.jp');
$mail->addAddress('mintardi@fdk.co.jp', 'mintardi@fdk.co.jp');
$mail->addAddress('daryanto@fdk.co.jp', 'daryanto@fdk.co.jp');

$mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
$mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
$mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
$mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');
$mail->addAddress('yoshi@fdk.co.jp', 'yoshi@fdk.co.jp');
$mail->addAddress('hagai@fdk.co.jp', 'hagai@fdk.co.jp');

$mail->addAddress('yaser.ali@fdk.co.jp', 'yaser.ali@fdk.co.jp');

$mail->Subject = 'PACKAGING PRODUCTION REPORT';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../images/logo-print4.png");

$message = '<!DOCTYPE>
<html>
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	  <title>PACKAGING PRODUCTION REPORT </title>
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
		<div style="position:absolute;font-family: Verdana, Geneva, sans-serif; font-size: 12px;">
		  <p>Dear All,</p>
			<p>Please see the information below : <br/></p>';

// $message.='<h4>DELAY vs MOVE UP PRODUCTION (QUANTITY BASED) : </h4>';

// $message.='<table>
// 			<tr>
// 				<td style="background-color: #D2D2D2;width: 40px;" align="center"><b>NO.</b></td>
// 				<td style="background-color: #D2D2D2;width: 250px;" align="center"><b>PACKING TYPE</b></td>
// 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>DELAY QTY</b></td>
// 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>MOVE UP QTY</b></td>
// 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>TOTAL DELAY QTY</b></td>

// 			</tr>';

// $nox=0;			$accum = 0;

// while ($rowdelay = oci_fetch_object($datadelay)){
// 	$nox1 = $nox1 + 1;
// 	$message .='<tr>
// 							<td style="background-color: #E2EFDA;">'.$nox1.'</td>
// 							<td style="background-color: #E2EFDA;">'.$rowdelay->PACKAGE_TYPE.'</td>
// 							<td style="background-color: #E2EFDA;" align="right">'.number_format($rowdelay->DELAYQTY).'</td>
// 							<td style="background-color: #E2EFDA;" align="right">'.number_format($rowdelay->MOVEUPQTY).'</td>
// 							<td style="background-color: #E2EFDA;" align="right">'.number_format($rowdelay->TOTALDELAY).'</td>
// 							</tr>';
						
//     $accum = $accum + $rowdelay->TOTALDELAY;
//     $accumDelay = $accumDelay + $rowdelay->DELAYQTY;
//     $accumMoveup = $accumMoveup + $rowdelay->MOVEUPQTY;
// }
// if(intval($accum) < 0){
// 				$acc1 .= '<td style="background-color: #D2D2D2;color: #FF0000;" align="right">'.number_format($accumDelay).'</td>';
// 				$acc1 .= '<td style="background-color: #D2D2D2;color: #FF0000;" align="right">'.number_format($accumMoveup).'</td>';
// 				$acc1 .= '<td style="background-color: #D2D2D2;color: #FF0000;" align="right">'.number_format($accum).'</td>';
// 			}else{
// 				$acc1 .= '<td style="background-color: #D2D2D2;" align="right">'.number_format($accumDelay).'</td>';
// 				$acc1 .= '<td style="background-color: #D2D2D2;" align="right">'.number_format($accumMoveup).'</td>';
// 				$acc1 .= '<td style="background-color: #D2D2D2;" align="right">'.number_format($accum).'</td>';
// };

// $message.='
// 			<tr>
// 				<td style="background-color: #D2D2D2;" align="right" colspan=2><b>TOTAL DELAY</b></td>
// 				'.$acc1.'
// 			</tr>
// 		</table><br/>';


$message.='<p>1. Progress of delivery for packaging <br/>&nbsp;&nbsp;&nbsp; 1-1. Gap of Accumulation</p>
		<table>
			<tr>
				<th style="background-color: #D2D2D2;width: 40px;" align="center"><b>NO.</b></th>
				<th style="background-color: #D2D2D2;width: 250px;" align="center"><b>BATTERY TYPE</b></th>
				<th style="background-color: #D2D2D2;width: 150px;" align="center"><b>ACCUMULATION</b></th>
			</tr>';
		$nox=1;			$accum = 0;
		while ($row61x = oci_fetch_object($data61)){
			$accum += $row61x->ACCUMULATION;
			if(intval($row61x->ACCUMULATION) < 0){
				$acc = '<td style="background-color: #E2EFDA;color: #FF0000;" align="right">'.number_format($row61x->ACCUMULATION).'</td>';
			}else{
				$acc = '<td style="background-color: #E2EFDA;" align="right">'.number_format($row61x->ACCUMULATION).'</td>';
			}

			$message.='<tr>
							<td style="background-color: #E2EFDA;">'.$nox.'</td>
							<td style="background-color: #E2EFDA;">'.$row61x->BATERY_TYPE.'</td>
							'.$acc.'
					   </tr>';
			$nox++;
		}
		if(intval($accum) < 0){
			$tg = 	'<td style="background-color: #D2D2D2;color: #FF0000;" align="right"><b>'.number_format($accum).'</b></td>';
		}else{
			$tg = 	'<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($accum).'</b></td>';
		}
$message.='
			<tr>
				<td style="background-color: #D2D2D2;" align="right" colspan=2><b>TOTAL GAP</b></td>
				'.$tg.'
			</tr>
		</table><br/>';

$message.='
		<table>
			<tr>
				<td style="background-color: #D2D2D2;width: 40px;" align="center"><b>NO.</b></td>
				<td style="background-color: #D2D2D2;width: 250px;" align="center"><b>GROUP TYPE</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>PLAN</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>OUTPUT</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>GAP</b></td>
			</tr>';
		$nox2=1;			$p = 0;     $o=0;       $g=0;
		while ($row62x = oci_fetch_object($data62)){
			$p += $row62x->PLN;      $o += $row62x->OUTPUT;   $g += $row62x->ACCUMULATION;
			if(intval($row62x->ACCUMULATION) < 0){
				$acc2 = '<td style="background-color: #E2EFDA; color: #FF0000;" align="right">'.number_format($row62x->ACCUMULATION).'</td>';
			}else{
				$acc2 = '<td style="background-color: #E2EFDA;" align="right">'.number_format($row62x->ACCUMULATION).'</td>';
			}

			$message.='<tr>
							<td style="background-color: #E2EFDA;">'.$nox2.'</td>
							<td style="background-color: #E2EFDA;">'.$row62x->LABEL_TYPE.'</td>
							<td style="background-color: #E2EFDA;" align="right">'.number_format($row62x->PLN).'</td>
							<td style="background-color: #E2EFDA;" align="right">'.number_format($row62x->OUTPUT).'</td>
							'.$acc2.'
					   </tr>';
			$nox2++;
		}
		if(intval($accum) < 0){
			$tg2 = '<td style="background-color: #D2D2D2;color: #FF0000;" align="right"><b>'.number_format($g).'</b></td>';
		}else{
			$tg2 = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($g).'</b></td>';
		}
$message.='<tr>
				<td style="background-color: #D2D2D2;" align="right" colspan=2><b>TOTAL GAP</b></td>
				<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($p).'</b></td>
				<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($o).'</b></td>
				'.$tg2.'
			</tr>
		</table><br/> ';

$message.='<p>&nbsp;&nbsp;&nbsp; 1-2. Output Result Yesterday</p>
		<table>
			<tr>
				<td style="background-color: #D2D2D2;width: 40px;" align="center"><b>NO.</b></td>
				<td style="background-color: #D2D2D2;width: 250px;" align="center"><b>GROUP</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>PLAN</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>OUTPUT</b></td>
				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>GAP</b></td>
			</tr>';
		$nox3=1;			$p63x = 0;     $o63x=0;       $g63x=0;
		while ($row63x = oci_fetch_object($data63)){
			$p63x += $row63x->PLN;      $o63x += $row63x->OUTPUT;   $g63x += $row63x->ACCUMULATION;
			if(intval($row63x->ACCUMULATION) < 0){
				$acc3 = '<td style="background-color: #E2EFDA;color: #FF0000;" align="right">'.number_format($row63x->ACCUMULATION).'</td>';
			}else{
				$acc3 = '<td style="background-color: #E2EFDA;" align="right">'.number_format($row63x->ACCUMULATION).'</td>';
			}
			$message.='<tr>
							<td style="background-color: #E2EFDA;">'.$nox3.'</td>
							<td style="background-color: #E2EFDA;">'.$row63x->LABEL_TYPE.'</td>
							<td style="background-color: #E2EFDA;" align="right">'.number_format($row63x->PLN).'</td>
							<td style="background-color: #E2EFDA;" align="right">'.number_format($row63x->OUTPUT).'</td>
							'.$acc3.'
					   </tr>';
			$nox3++;
		}
		if(intval($g63x) < 0){
			$tg3 = '<td style="background-color: #D2D2D2;color: #FF0000;" align="right"><b>'.number_format($g63x).'</b></td>';
		}else{
			$tg3 = '<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($g63x).'</b></td>';
		}
$message.='<tr>
				<td style="background-color: #D2D2D2;" align="right" colspan=2><b>TOTAL GAP</b></td>
				<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($p63x).'</b></td>
				<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($o63x).'</b></td>
				'.$tg3.'
			</tr>
		</table>';

$message.='
<p>Do not reply this email.<br/><br/></p>
<p>Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="80"/></p>';
$message.='
        </div>
    </body>
</html>';
/*END BODY EMAIL*/

//echo $message;
$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//$mail->AddStringAttachment($dataXLS, "FINISHING_REPORT ".date('M').".xls");
$mail->AddAttachment($dataXLS);

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>


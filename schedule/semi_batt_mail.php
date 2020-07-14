<?php
set_time_limit(0);
include("../connect/conn.php");

$totq3 = "select count(*) as j3 from (
	select distinct a.id_print, a.ket, b.tanggal_produksi, a.qty, b.assy_line, b.cell_type,
	--case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end as type,
  	c.id_pallet as type,
	floor((select sysdate from dual) - b.tanggal_produksi) as n,
	case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end as lr,
  	nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet) as id_pallet
	from zvw_semi_bat a
	left join ztb_assy_kanban b on a.id_print = b.id_print
  	left join ztb_assy_heating c on a.id_print = c.id_print
	where floor((select sysdate from dual) - b.tanggal_produksi) >= 75
	and ket='AGING'
	and case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end = 'LR03'
  	and case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end
    = nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet)
	order by b.tanggal_produksi asc
	)";
$data_tot3 = oci_parse($connect, $totq3);
oci_execute($data_tot3);
$rows3 = oci_fetch_object($data_tot3);

$totq6 = "select count(*) as j6 from (
	select distinct a.id_print, a.ket, b.tanggal_produksi, a.qty,
	case when substr(b.assy_line,0,INSTR(b.assy_line,'#')-1) = 'LR06' then 'LR6' else 'LR03' end || substr(b.assy_line,5,2) as assy_line,  
	b.cell_type,
	--case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end as type,
  	c.id_pallet as type,
	floor((select sysdate from dual) - b.tanggal_produksi) as n,
	case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end as lr,
  	nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet) as id_pallet
	from zvw_semi_bat a
	left join ztb_assy_kanban b on a.id_print = b.id_print
  	left join ztb_assy_heating c on a.id_print = c.id_print
	where floor((select sysdate from dual) - b.tanggal_produksi) >= 75
	and ket='AGING'
	and case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end = 'LR6'
  	and case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else
  	case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end
  	= nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet)
	order by b.tanggal_produksi asc
	)";
$data_tot6 = oci_parse($connect, $totq6);
oci_execute($data_tot6);
$rows6 = oci_fetch_object($data_tot6);

$totqs ="select count(*) js from (
	select aa.id_print, aa.lot_date, aa.ok_qty, aa.ng_qty, aa.qty_suspend, --aa.assy_line, 
	case when substr(aa.assy_line,0,INSTR(aa.assy_line,'#')-1) = 'LR06' then 'LR6' else 'LR03' end || substr(aa.assy_line,5,2) as assy_line,  
	aa.cell_type,
	--case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end || aa.cell_type as type,
	case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end as lr,
	floor((select sysdate from dual) - aa.lot_date) as n, bb.id_pallet as type
	from zvw_suspend_list aa
	left join (select distinct id_print, id_pallet from ztb_assy_heating where id_pallet is not null) bb on aa.id_print=bb.id_print
	where aa.unsuspend_date is null
	and floor((select sysdate from dual) - aa.lot_date) >= 75
	and aa.qty_suspend > 0
	order by 
	case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end asc, 
	case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end || aa.cell_type asc,
	floor((select sysdate from dual) - aa.lot_date) desc,
	aa.lot_date asc
	)";
$data_tots = oci_parse($connect, $totqs);
oci_execute($data_tots);
$rowss = oci_fetch_object($data_tots);

/*-----------------------------------------------------------------------------------------------------------------------------------------*/
$a = $rows3->J3; 
$b = $rows6->J6;
$c = $rowss->JS;
$n='';

if ($a == 0){
	if($b == 0){
		if($c == 0){
			$n=0;
		}else{
			$n=1;
		}
	}else{
		$n=1;
	}
}else{
	$n=1;
}

if ($n == 1){
/*-----------------------------------------------------------------------------------------------------------------------------------------*/

	$q3 = "select distinct a.id_print, a.ket, b.tanggal_produksi, a.qty, b.assy_line, b.cell_type,
		--case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end as type,
	  	c.id_pallet as type,
		floor((select sysdate from dual) - b.tanggal_produksi) as n,
		case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end as lr,
	  	nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet) as id_pallet
		from zvw_semi_bat a
		left join ztb_assy_kanban b on a.id_print = b.id_print
	  	left join ztb_assy_heating c on a.id_print = c.id_print
		where floor((select sysdate from dual) - b.tanggal_produksi) >= 75
		and ket='AGING'
		and case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end = 'LR03'
	  	and case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end
	    = nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet)
		order by b.tanggal_produksi asc";
	$data3 = oci_parse($connect, $q3);
	oci_execute($data3);

	$q6 = "select distinct a.id_print, a.ket, b.tanggal_produksi, a.qty,
		case when substr(b.assy_line,0,INSTR(b.assy_line,'#')-1) = 'LR06' then 'LR6' else 'LR03' end || substr(b.assy_line,5,2) as assy_line,  
		b.cell_type,
		--case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end as type,
	  	c.id_pallet as type,
		floor((select sysdate from dual) - b.tanggal_produksi) as n,
		case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end as lr,
	  	nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet) as id_pallet
		from zvw_semi_bat a
		left join ztb_assy_kanban b on a.id_print = b.id_print
	  	left join ztb_assy_heating c on a.id_print = c.id_print
		where floor((select sysdate from dual) - b.tanggal_produksi) >= 75
		and ket='AGING'
		and case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end = 'LR6'
	  	and case when case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type = 'LR03C01' then 'LR03C1' else
	  	case when substr(b.assy_line,0,4) = 'LR06' then 'LR6' else substr(b.assy_line,0,4) end || b.cell_type end
	  	= nvl(SUBSTR(c.id_pallet,0,INSTR(c.id_pallet,'-')-1),c.id_pallet)
		order by b.tanggal_produksi asc";
	$data6 = oci_parse($connect, $q6);
	oci_execute($data6);

	$qs = "select aa.id_print, aa.lot_date, aa.ok_qty, aa.ng_qty, aa.qty_suspend, --aa.assy_line, 
		case when substr(aa.assy_line,0,INSTR(aa.assy_line,'#')-1) = 'LR06' then 'LR6' else 'LR03' end || substr(aa.assy_line,5,2) as assy_line,  
		aa.cell_type,
		--case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end || aa.cell_type as type,
		case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end as lr,
		floor((select sysdate from dual) - aa.lot_date) as n, bb.id_pallet as type
		from zvw_suspend_list aa
		left join (select distinct id_print, id_pallet from ztb_assy_heating where id_pallet is not null) bb on aa.id_print=bb.id_print
		where aa.unsuspend_date is null
		and floor((select sysdate from dual) - aa.lot_date) >= 75
		and aa.qty_suspend > 0
		order by 
		case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end asc, 
		case when substr(aa.assy_line,0,4) = 'LR06' then 'LR6' else substr(aa.assy_line,0,4) end || aa.cell_type asc,
		floor((select sysdate from dual) - aa.lot_date) desc,
		aa.lot_date asc";
	$datas = oci_parse($connect, $qs);
	oci_execute($datas);

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

	$mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
	$mail->addAddress('wahyu@fdk.co.jp', 'wahyu@fdk.co.jp');
	$mail->addAddress('setiawan@fdk.co.jp', 'setiawan@fdk.co.jp');
	$mail->addAddress('satrio.adiwibowo@fdk.co.jp', 'satrio.adiwibowo@fdk.co.jp');
	$mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
	$mail->addAddress('samekto@fdk.co.jp', 'samekto@fdk.co.jp');
	$mail->addAddress('firdaus@fdk.co.jp', '@fdk.co.jp');
	$mail->addAddress('fransiska@fdk.co.jp', 'fransiska@fdk.co.jp');
	$mail->addAddress('suharti@fdk.co.jp', 'suharti@fdk.co.jp');
	$mail->addAddress('mintardi@fdk.co.jp','mintardi@fdk.co.jp');
	$mail->addAddress('taufik.andriyanto@fdk.co.jp','taufik.andriyanto@fdk.co.jp');
	$mail->addAddress('holik.sidik@fdk.co.jp','holik.sidik@fdk.co.jp');

	$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
	$mail->addAddress('yaser.ali@fdk.co.jp', 'yaser.ali@fdk.co.jp');
	$mail->addAddress('ferry.agung@fdk.co.jp', 'ferry.agung@fdk.co.jp');

	$mail->addcc('ema@fdk.co.jp', 'ema@fdk.co.jp');
	$mail->addcc('natsume@fdk.co.jp', 'natsume@fdk.co.jp');
	$mail->addcc('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
	$mail->addcc('aris@fdk.co.jp', 'aris@fdk.co.jp');
	$mail->addcc('antonius@fdk.co.jp', 'antonius@fdk.co.jp');
	$mail->addcc('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');

	$mail->Subject = 'SEMI BATTERIES OVER THAN 75 DAYS';
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
			  <p>Dear All,</p>';
	$message.='<p>This is reminder of semi batteries aging over than 75 days.</p>';

/*TABLE - 1 : LR03*/
	$message.='<span>Semi batteries in Aging Area (LR03) : </span>
			   <table>
		 			<tr>
		 				<td style="background-color: #D2D2D2;width: 50px;" align="center"><b>NO.</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>TYPE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>ASSY LINE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>KANBAN ID</b></td>
		 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>PRODUCTION DATE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>QTY</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>AGING DAY</b></td>
		 			</tr>';
	 		$no3=1;			$accum3 = 0;
			while ($row3 = oci_fetch_object($data3)){
				$accum3 += $row3->QTY;
	$message.=		'<tr>
						<td style="background-color: #E2EFDA;" align="left">'.$no3.'</td>
						<td style="background-color: #E2EFDA;" align="left" >'.$row3->TYPE.'</td>
						<td style="background-color: #E2EFDA;" align="left" >'.$row3->ASSY_LINE.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$row3->ID_PRINT.'</td>
						<td style="background-color: #E2EFDA;" align="center">'.$row3->TANGGAL_PRODUKSI.'</td>
						<td style="background-color: #E2EFDA;" align="right">'.number_format($row3->QTY).'</td>
						<td style="background-color: #E2EFDA;" align="right">'.$row3->N.'</td>
				    </tr>';
				$no3++;
			}

	$message.=		'<tr>
						<td style="background-color: #D2D2D2;" colspan=5 align="center"><b>TOTAL</b></td>
						<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($accum3).'</b></td>
						<td style="background-color: #D2D2D2;"></td>
					</tr> 			
	 		   </table>
	 		   <br>';

/*TABLE - 2 : LR6*/
	$message.='<span>Semi batteries in Aging Area (LR6) : </span>
			   <table>
		 			<tr>
		 				<td style="background-color: #D2D2D2;width: 50px;" align="center"><b>NO.</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>TYPE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>ASSY LINE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>KANBAN ID</b></td>
		 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>PRODUCTION DATE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>QTY</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>AGING DAY</b></td>
		 			</tr>';
	 		$no6=1;			$accum6 = 0;
			while ($row6 = oci_fetch_object($data6)){
				$accum6 += $row6->QTY;
	$message.=		'<tr>
						<td style="background-color: #E2EFDA;" align="left">'.$no6.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$row6->TYPE.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$row6->ASSY_LINE.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$row6->ID_PRINT.'</td>
						<td style="background-color: #E2EFDA;" align="center">'.$row6->TANGGAL_PRODUKSI.'</td>
						<td style="background-color: #E2EFDA;" align="right">'.number_format($row6->QTY).'</td>
						<td style="background-color: #E2EFDA;" align="right">'.$row6->N.'</td>
			   		</tr>';
				$no6++;
			}

	$message.=		'<tr>
						<td style="background-color: #D2D2D2;" colspan=5 align="center"><b>TOTAL</b></td>
						<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($accum6).'</b></td>
						<td style="background-color: #D2D2D2;"></td>
					</tr>
	 		   </table>
	 		   <br>';

/*TABLE - 3 : SUSPEND*/
	$message.='<span>Semi batteries in Suspended Area LR6 & LR03 : </span>
			   <table>
		 			<tr>
		 				<td style="background-color: #D2D2D2;width: 50px;" align="center"><b>NO.</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>TYPE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>ASSY LINE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>KANBAN ID</b></td>
		 				<td style="background-color: #D2D2D2;width: 150px;" align="center"><b>PRODUCTION DATE</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>QTY</b></td>
		 				<td style="background-color: #D2D2D2;width: 100px;" align="center"><b>AGING DAY</b></td>
		 			</tr>';
	 		$nos=1;			$accums = 0;
			while ($rows = oci_fetch_object($datas)){
				$accums += $rows->QTY_SUSPEND;
	$message.=		'<tr>
						<td style="background-color: #E2EFDA;" align="left">'.$nos.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$rows->TYPE.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$rows->ASSY_LINE.'</td>
						<td style="background-color: #E2EFDA;" align="left">'.$rows->ID_PRINT.'</td>
						<td style="background-color: #E2EFDA;" align="center">'.$rows->LOT_DATE.'</td>
						<td style="background-color: #E2EFDA;" align="right">'.number_format($rows->QTY_SUSPEND).'</td>
						<td style="background-color: #E2EFDA;" align="right">'.$rows->N.'</td>
				   	</tr>';
				$nos++;
			}

	$message.=		'<tr>
						<td style="background-color: #D2D2D2;" colspan=5 align="center"><b>TOTAL</b></td>
						<td style="background-color: #D2D2D2;" align="right"><b>'.number_format($accums).'</b></td>
						<td style="background-color: #D2D2D2;"></td>
					</tr>
		 	   </table>';
		 	   
	$message.='<p>For detail of WIP semi batteries, view at this link:<br/>http://172.23.225.85/wms/forms/stock_semi_battery.php<br/>or<br/> http://kanbansvr/wms/forms/stock_semi_battery.php</p>';
	$message.='<p>Do not reply this email.<br/><br/><br/>
	Thanks and Regards,<br/>
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

	//send the message, check for errors
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	}else{
	    echo "Message sent!";
	    echo "<script>window.onload = self.close();</script>";
	}
}
?>
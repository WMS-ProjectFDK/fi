<?php
// Create By : Ueng hernama
// Date : 29-mar-2018
// ID = 2
include("../connect/conn.php");
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

$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');

//Set the subject line
$mail->Subject = 'WIP PACKING MATERIAL';
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

if($b < 9){
	$tgl1 = $t.'-0'.$b.'-01';
}else{
	$tgl1 = $t.'-'.$b.'-01';
}

$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-WIP PACKING MATERIAL</title>
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
  <p>Please see the WIP of Packing Material : <br/></p>';

$message .='
	  <div>
	  	<p>Total WIP Packing Material : <br/></p>
	  	<table style="font-size: 12px;">
	  		<tr>
		       <th style="background-color: #D2D2D2;width: 140px; height: 25px;" align="center">MATERIAL NO.</th>
		       <th style="background-color: #D2D2D2;width: 250px; height: 25px;" align="center">DESCRIPTION</th>
		       <th style="background-color: #D2D2D2;width: 120px; height: 25px;" align="center">TOTAL QTY WIP</th>
		    </tr>';

$sql0 = "select  material_item, description,sum(qty) TotalWIP 
	from zvw_wip_packing_mat
	group by material_item, description";

$data0 = oci_parse($connect, $sql0);
oci_execute($data0);

$rowno0 = 1 ;
while($row0 = oci_fetch_object($data0)){
	if($rowno0%2 == 1){
		$message.= '
			<tr>
				<td style="background-color: #E2EFDA;width: 140px;" >'.$row0->MATERIAL_ITEM.'</td>
				<td style="background-color: #E2EFDA;width: 250px;" >'.$row0->DESCRIPTION.'</td>
				<td style="background-color: #E2EFDA;width: 120px;" align="right">'.number_format($row0->TOTALWIP).'&nbsp;</td>
			</tr>';
	}else{
		$message.= '
			<tr>
				<td style="background-color: #C4D79B;width: 140px;" >'.$row0->MATERIAL_ITEM.'</td>
				<td style="background-color: #C4D79B;width: 250px;" >'.$row0->DESCRIPTION.'</td>
				<td style="background-color: #C4D79B;width: 120px;" align="right">'.number_format($row0->TOTALWIP).'&nbsp;</td>
			</tr>';	
	}

	$rowno0++;
}

$message .='
	  	</table>
	  </div><br/>
';


$message .='
  	  <div style="margin-top: 20px;">
  	  <p>List WO in process : <br/></p>
	  <table style="font-size: 13px;">
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 40px; height: 25px;" align="center">NO</th>
	       <th style="background-color: #D2D2D2;width: 100px; height: 25px;" align="center">ITEM NO.</th>
	       <th style="background-color: #D2D2D2;width: 250px; height: 25px;" align="center">WO NO.</th>
	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">START TIME</th>
	       <th style="background-color: #D2D2D2;width: 60px; height: 25px;" align="center">PALLET</th>
	       <th style="background-color: #D2D2D2;width: 150px; height: 25px;" align="center">QTY PRODUCT</th>
	    </tr>';

$sql = "select distinct wo_no,plt_no, startchecker,item_no, qty_prod from zvw_wip_packing_mat";

//echo $sql;
$data = oci_parse($connect, $sql);
oci_execute($data);

$rowno = 1;
while($row = oci_fetch_object($data)){
	if($rowno % 2 == 1){
		$message .='
			<tr>
				<td style="background-color: #E2EFDA;">'.$rowno.'</td>
				<td style="background-color: #E2EFDA;">'.$row->ITEM_NO.'</td>
				<td style="background-color: #E2EFDA;">'.$row->WO_NO.'</td>
				<td style="background-color: #E2EFDA;">'.$row->STARTCHECKER.'</td>
				<td style="background-color: #E2EFDA;" align="right">'.$row->PLT_NO.'&nbsp;</td>
				<td style="background-color: #E2EFDA;" align="right">'.number_format($row->QTY_PROD).'&nbsp;</td>
			</tr>
		';
	}else{
		$message .='
			<tr>
				<td style="background-color: #C4D79B;">'.$rowno.'</td>
				<td style="background-color: #C4D79B;">'.$row->ITEM_NO.'</td>
				<td style="background-color: #C4D79B;">'.$row->WO_NO.'</td>
				<td style="background-color: #C4D79B;">'.$row->STARTCHECKER.'</td>
				<td style="background-color: #C4D79B;" align="right">'.$row->PLT_NO.'&nbsp;</td>
				<td style="background-color: #C4D79B;" align="right">'.number_format($row->QTY_PROD).'&nbsp;</td>
			</tr>
		';
	}

	$rowno++;
}

$message.='</table>
		</div>';

$message.='
<p>Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="75"/></p>';
$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
<?php
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';
// $link = 'C:\xampp\Kuraire\wms\schedule\assembly_report.xls';
ini_set('memory_limit', '-1');
// error_reporting(0);
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
// $mail->Host = "smtp01.fdk.co.jp";
$mail->Host = "smtp.office365.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
// Whether to use SMTP authentication
$mail->SMTPAuth = true;

$mail->SMTPSecure = 'tls';
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
$mail->Password = "V6iT7n8U";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

// //Set who the message is to be sent to
$mail->addAddress('ueng.hernama@fdk.co.jp', 'ueng.hernama@fdk.co.jp');
// $mail->addAddress('yaser.ali@fdk.co.jp', 'yaser.ali@fdk.co.jp');
// $mail->addAddress('ferry.agung@fdk.co.jp', 'ferry.agung@fdk.co.jp');

// $mail->addAddress('arihudayato@fdk.co.jp', 'arihudayato@fdk.co.jp');
// $mail->addAddress('garnadibs@fdk.co.jp', 'garnadibs@fdk.co.jp');
// $mail->addAddress('wahyu@fdk.co.jp', 'Wahyu');
// $mail->addAddress('slamet.maryanto@fdk.co.jp', 'slamet.maryanto@fdk.co.jp');
// $mail->addAddress('suharti@fdk.co.jp', 'suharti@fdk.co.jp');
// $mail->addAddress('ari_harari@fdk.co.jp', 'ari_harari@fdk.co.jp');
// $mail->addAddress('victor.antonio@fdk.co.jp', 'victor.antonio@fdk.co.jp');
// $mail->addAddress('agusman@fdk.co.jp', 'agusman@fdk.co.jp');

// $mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'wakuda_nobuyuki@fdk.co.jp');
// $mail->addAddress('ema@fdk.co.jp', 'ema@fdk.co.jp');
// $mail->addAddress('tuduki@fdk.co.jp', 'tuduki@fdk.co.jp');
// $mail->addAddress('daedalus@fdk.co.jp', 'daedalus@fdk.co.jp');
// $mail->addAddress('aris@fdk.co.jp', 'aris@fdk.co.jp');
// $mail->addAddress('novianto.hadi@fdk.co.jp', 'novianto.hadi@fdk.co.jp');
// $mail->addAddress('prihartanto@fdk.co.jp', 'prihartanto@fdk.co.jp');
// $mail->addAddress('lutfi.sulthony@fdk.co.jp', 'lutfi.sulthony@fdk.co.jp');
// $mail->addAddress('agung.mardiansyah@fdk.co.jp', 'agung.mardiansyah@fdk.co.jp');
// $mail->addAddress('agung.kurniawan@fdk.co.jp', 'agung.kurniawan@fdk.co.jp');

// $mail->addAddress('handiko.haminanto@fdk.co.jp', 'handiko.haminanto@fdk.co.jp');
// $mail->addAddress('joni.susilo@fdk.co.jp', 'joni.susilo@fdk.co.jp');
// $mail->addAddress('ardian.ary@fdk.co.jp', 'ardian.ary@fdk.co.jp');
// $mail->addAddress('widodo@fdk.co.jp', 'widodo@fdk.co.jp');

// $mail->addAddress('shiba@fdk.co.jp', 'shiba@fdk.co.jp');
// $mail->addAddress('yuji@fdk.co.jp', 'yuji@fdk.co.jp');

// $mail->addAddress('antonius@fdk.co.jp', 'antonius@fdk.co.jp');
// $mail->addAddress('fifin2@fdk.co.jp', 'fifin2@fdk.co.jp');
// $mail->addAddress('fauzi.budi@fdk.co.jp', 'fauzi.budi@fdk.co.jp');
// $mail->addAddress('lukman@fdk.co.jp', 'lukman@fdk.co.jp');
// $mail->addAddress('labellr@fdk.co.jp', 'labellr@fdk.co.jp');
// $mail->addAddress('dono@fdk.co.jp', 'dono@fdk.co.jp');
// $mail->addAddress('samekto@fdk.co.jp', 'samekto@fdk.co.jp');
// $mail->addAddress('firdaus@fdk.co.jp', 'firdaus@fdk.co.jp');
// $mail->addAddress('agustinus@fdk.co.jp','agustinus@fdk.co.jp');
// $mail->addAddress('wiwith@fdk.co.jp', 'wiwith@fdk.co.jp');
// $mail->addAddress('wiwiky@fdk.co.jp', 'wiwiky@fdk.co.jp');
// $mail->addAddress('fifin1@fdk.co.jp', 'fifin1@fdk.co.jp');
// $mail->addAddress('fransiska@fdk.co.jp', 'fransiska@fdk.co.jp');

//Set the subject line
$mail->Subject = 'Assembly Daily Report';
$mail->AddEmbeddedImage("../images/logo-print4.png", "my-attach", "../images/logo-print4.png");

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

$message.='
</div>
</body>
</html>';

$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
// $mail->addAttachment($link);

// echo $message;
// //send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
// ?>
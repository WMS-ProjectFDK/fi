<?php
// Create By : Ueng hernama
// Date : 24-oct-2017
// ID = 2
include("../connect/conn.php");
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';

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
$mail->Subject = 'TEST EMAIL';

$message = '<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>TEST EMAIL !!</title>
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
<div style="width: 920px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">';

$message.= "<div > This email sent without permission under the name of :  $name </div >";
$message.='Do not reply this email.<br/><br/><br/></p>';
$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);
// echo $sql;
// echo $message;

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

// $mail->AddAttachment($dataXLS);
//send the message, check for errors


if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}
?>
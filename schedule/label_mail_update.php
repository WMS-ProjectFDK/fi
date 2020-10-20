<?php
include("../connect/conn.php");
date_default_timezone_set('Etc/UTC');
require_once '../class/PHPMailer/PHPMailerAutoload.php';

$ins2 = "update ztb_kanban_lbl set mulai =  getdate()
    where mulai = getdate() and cast(substring(startdate,12,2) as int) < 7 ";
$data_ins2 = sqlsrv_query($connect, $ins2);
?>
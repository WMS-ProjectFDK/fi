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

?>
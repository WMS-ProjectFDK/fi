<?php
include('../class/phpqrcode/phpqrcode.php'); 
$string = isset($_REQUEST['string']) ? strval($_REQUEST['string']) : '';     
ob_start("callback"); 
     
// here DB request or some processing 
$codeText = $string; 
 
// end of processing here 
$debugLog = ob_get_contents(); 
ob_end_clean(); 
 
// outputs image directly into browser, as PNG stream 
// QRcode::png($codeText);
QRcode::png($codeText, $tempDir, QR_ECLEVEL_L, 2);
?>
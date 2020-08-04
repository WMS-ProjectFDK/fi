<?php
include('../class/phpqrcode/phpqrcode.php');
ini_set('memory_limit','-1');

$codeText = '1946094 , FI/19-024-LR6G07-5 , 49 , 2223193 , 432 , 2225314 , 36 , 2226114 , 36 , 2710002 , 1 , 2226100 , 432 , 2600026 , 4 , 2720008 , 1 ';

//echo $codeText;

// end of processing here 
$debugLog = ob_get_contents(); 
ob_end_clean(); 

// outputs image directly into browser, as PNG stream
QRcode::png($codeText);
//QRcode::png($codeText, $tempDir.'007_4.png', QR_ECLEVEL_L, 4); 
?>
<?php
// function require_multi($files) {
//     $files = func_get_args();
//     foreach($files as $file)
//         require_once($file);
// }

// require_multi("mps_list_excel.php","mps_migration.php","safety_stock_xls.php");

$ArrLink = array("http://172.23.225.85/wms/forms/mps_list_excel.php",
				 "http://172.23.225.85/wms/forms/mps_migration.php",
				 "http://172.23.225.85/wms/forms/safety_stock_xls.php"
				);

for ($i=0; $i < count($ArrLink); $i++) { 
	file_get_contents($ArrLink[$i]);
}
?>
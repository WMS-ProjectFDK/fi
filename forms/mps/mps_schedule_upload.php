<?php
$ArrLink = array("http://localhost:8088/fi/forms/mps/mps_list_excel.php",
				 "http://localhost:8088/fi/forms/mps/safety_stock_xls.php"
				);

for ($i=0; $i < count($ArrLink); $i++) { 
	file_get_contents($ArrLink[$i]);
}
?>
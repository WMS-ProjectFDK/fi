<?php
require("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ASSET PRINT</title>
<link rel="icon" type="image/png" href="../favicon.png">
<script language="javascript">
	function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
	}
</script> 

<style> 
	table {
		border-collapse: collapse;
		padding:0px;
		font-size:14px;
		height:10px;
	}
	table, th, tr {
		border-left:0px solid #ffffff; 
		border-right:0px solid #ffffff; 
		border-bottom:0px solid #ffffff; 
		border-top:0px solid #ffffff;
	}
	th {
		
		color: black;
	}
	.brd {
		border:none;
	}

	.class_00{
		border:1px;width:150px;height:50px;border-radius:4px;margin-top:10px;
	}
	.class_01{
		position:absolute;margin-left:180px;border:1px;width:150px;height:50px;border-radius:4px;margin-top:10px;
	}


	.class_10{
		border:1px;width:150px;height:50px;border-radius:4px;margin-top:30px;
	}
	.class_11{
		position:absolute;margin-left:180px;border:1px;width:150px;height:50px;border-radius:4px;margin-top:92px;
	}


	.class_20{
		border:1px;width:150px;height:50px;border-radius:4px;margin-top:30px;
	}
	.class_21{
		position:absolute;margin-left:180px;border:1px;width:150px;height:50px;border-radius:4px;margin-top:144px;
	}
</style>
</head>

<body onLoad="window.print()">
	<?php
		$i = 1;		$row_a=0;	$col_a=0;
		while ($i <= 10){
			if ($row_a==0) {
				if($col_a==0){
					$cls = "class_".$row_a.$col_a;
					$col_a++;
				}elseif ($col_a == 1) {
					$cls = "class_".$row_a.$col_a;
					$col_a=0;	$row_a++;
				}
			}elseif($row_a==1){
				if($col_a==0){
					$cls = "class_".$row_a.$col_a;
					$col_a++;
				}elseif ($col_a == 1) {
					$cls = "class_".$row_a.$col_a;
					$col_a=0;	$row_a++;
				}
			}elseif($row_a==2){
				if($col_a==0){
					$cls = "class_".$row_a.$col_a;
					$col_a++;
				}elseif ($col_a == 1) {
					$cls = "class_".$row_a.$col_a;
					$col_a=0;	$row_a=0;
				}
			}
			echo "<div class='class_00' style='border:1px;'>
					<img alt='testing' src='http://localhost/wms/plugins/php-barcode-master/barcode.php?codetype=Code39&size=20&print=true&text=testing'/>
				</div>";
			$i++;
		}
	?>
</body>
</html>
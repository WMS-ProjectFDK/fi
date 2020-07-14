<?php
if (isset($_SESSION['id_wms'])){
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>WMS</title>
</head>
<body>
	WMS - PT. FDK INDONESIA
</body>
</html>

<script type='text/javascript'>
	location.href='../index.php';
</script>
<?php
}else{
	echo "<script type='text/javascript'>location.href='../home.php';</script>";
}

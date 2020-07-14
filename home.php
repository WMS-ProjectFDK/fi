<?php 
session_start();
require_once('sessionvalidation.php');
if(isset($_SESSION['id_wms'])){
	include("connect/conn.php");
?>
	<html>
	<head>
		<title>WMS | PT. FDK Indonesia</title>
		<link rel="icon" type="image/png" href="favicon.png">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<frameset rows="80,*" cols="*" frameborder="no" border="0" framespacing="0">
	  <frame src="HeaderPage.php" name="topFrame" scrolling="NO" noresize />
	  <frameset id="content" rows="*" cols="20,*" framespacing="0" frameborder="NO" border="0">
	    <frame src="menus.php" name="left" scrolling="auto" frameborder="NO" style="border-right:1px solid #DDDDDD;" />
	    <frame src="dashboard/dashboard.php" name="right" frameborder="NO" scrolling="Auto" />
	  </frameset>
	</frameset>

	<noframes><body>
	You need to enable frames in your browser.
	</body></noframes>
	</html>
<?php
}else{
	echo "<script type='text/javascript'>alert('Session Expired');self.location.href = 'index.php';</script>";
}
?>
<?php
	include 'function.php';
	unset($_SESSION['id_wms']);
	session_destroy();
	header("Location:index.php");
?>
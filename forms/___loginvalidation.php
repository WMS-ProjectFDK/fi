<?php
require_once "../../function.php";
if(!isset ($_SESSION['id_wms'])) {
	header('location:../../index.php');

}elseif (!login_check()) {
	echo "<script type='text/javascript'>
			alert('Session Expired');
			var topwindow = window.top.document;
			topwindow.location.href = '../../logout.php';
		</script>";
	exit(0);
}
?>
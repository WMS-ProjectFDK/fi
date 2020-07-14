<?php
	session_start();
	unset($_SESSION['user_labelAfter']);
	unset($_SESSION['name_labelAfter']);
	unset($_SESSION['shift_labelAfter']);
	unset($_SESSION['line_labelAfter']);
	header("Location:index.php");
?>
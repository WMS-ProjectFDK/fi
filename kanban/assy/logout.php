<?php
	session_start();
	unset($_SESSION['id_kanban']);
	unset($_SESSION['name_kanban']);
	header("Location:../index.php");
?>
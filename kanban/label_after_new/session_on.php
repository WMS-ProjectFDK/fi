<?php
session_start();
$user = isset($_REQUEST['user']) ? strval($_REQUEST['user']) : '';
$name = isset($_REQUEST['name']) ? strval($_REQUEST['name']) : '';
$shift = isset($_REQUEST['shift']) ? strval($_REQUEST['shift']) : '';
$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';

$_SESSION['user_labelAfter'] = $user;
$_SESSION['name_labelAfter'] = $name;
$_SESSION['shift_labelAfter'] = $shift;
$_SESSION['line_labelAfter'] = $line;

header("Location:label_after.php");
?>
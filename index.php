<?php
error_reporting(0); 
session_start();

include("connect/conn.php");
include "function.php";
include "detect.php";

if(isset($_SESSION['id_wms'])){ ?>
	<!-- header('location:home.php'); -->
	<meta http-equiv="Refresh" content="0; url=home.php">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  	<title>Login | WMS</title>
	<link rel="icon" type="image/png" href="favicon.png?v=2">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />	
<style>
	.myImg{
		text-align:center;
		margin: auto;
  	}
</style>
</head>

<body>
<div class="myImg">
	<br/>
	<img src="images/fdki-322.png?v=2">
</div>
<div class="container">
	<div id="row">
		<div class="container">
			<div style="text-align: center; ">
				<form class="form-group" action="#" method="post">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="form-group has-feedback">
							<input type="text" class="form-control" name="log" id="log" value="" size="23" placeholder="username" autofocus/>
						</div>
						<div class="form-group has-feedback">
							<input class="form-control" type="password" name="pwd" id="pwd" size="23" placeholder="password"/>
						</div>
						<div class="form-group has-feedback">
							<input type="submit" name="submit" value="Login" class="btn btn-primary btn-raised btn-flat" />
						</div>
					</div>
					<div class="col-md-4"></div>
					<?php 
					if(isset($_POST['submit'])){
						$username=$_POST['log'];
						$password=$_POST['pwd'];
						
						$cek = "select person_code, password, person from person where person_code='$username' and password='$password'";
						$result = sqlsrv_query($connect, $cek);
						$row = sqlsrv_fetch_object($result);

						if($username == '' OR $password == ''){
							echo "<script type='text/javascript'>alert('Maaf username atau password salah');self.location.href = 'index.php';</script>";
						}elseif ($row->person_code == $username AND $row->password == $password ){
							$_SESSION['id_wms'] = $username;
							$_SESSION['name_wms'] = $row->person;

							//insert log_history
							if ($ip_user != '127.0.0.1'){
								$ins = "insert into ztb_log_history (person_code, person_name, ip_address, log_time, log_sys, log_browser, log_date, log_os, server_name, comp_name, mac_address) VALUES ('".$row->person_code."',
																'".$row->person."',
																'".$ip_user."',
																getdate(),
																'WMS',
																'".$user_browser."',
																getdate(),
																'".$user_os."',
																'WMS-RBI',
																'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
																'".$get_addr."')";
								$insert = sqlsrv_query($connect, $ins);
								
								$ins2 = "insert into ZTB_LOG_HISTORY_DTL VALUES ('".$row->person_code."', '".$row->person."', 'LOGIN', 'LOGIN', 'LOGIN', SYSDATETIME())";
								$insert2 = sqlsrv_query($connect, $ins2);
							}

							//fungsi untuk membuat waktu session
							login_validate();
							
							echo "<script type='text/javascript'>location.href='home.php';</script>";
						}else{
							echo "<script type='text/javascript'>alert('Maaf username atau password salah');self.location.href = 'index.php';</script>";  
						}
					}
					?>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
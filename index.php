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
	<link rel="icon" type="image/png" href="favicon.png">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<!-- stylesheets -->
  	<link rel="stylesheet" href="css/login.css" type="text/css" media="screen" />
  	<link rel="stylesheet" href="css/slide.css" type="text/css" media="screen" />
    <!-- jQuery - the core -->
	<script src="js/jquery-1.8.1.min.js" type="text/javascript"></script>
	<!-- Sliding effect -->
	<script src="js/slide.js" type="text/javascript"></script>
	
</head>

<body>
<div style="position:fixed;width:100%;height:100%;background:url('images/fdki-3.png') no-repeat;background-size: 100% 100%;"></div>
<!-- <div style="position:fixed;width:100%;height:100%;background:url('images/fdk1.jpg') no-repeat;background-size: 100% 100%;"></div> -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
<!-- 				<h4>Welcome to Warehouse Management Systems</h4> -->
				<br /><br />
				<img id="bgimage" src="images/wms-111.png" width="450" height="120">
			</div>
			<div class="left">
				<form action="#" method="post">
				
				</form>
			</div>
			<div class="left right">
				<form class="clearfix" action="#" method="post">
				<a href> <img src="images/kunci1.png" width="200" height="35"> </a>
					
					<label class="grey" for="log">Username:</label>
					<input class="field" type="text" name="log" id="log" value="" size="23" />
					<label class="grey" for="pwd">Password:</label>
					<input class="field" type="password" name="pwd" id="pwd" size="23" />
	            	
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
					
	
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
					    $ins = "insert into ztb_log_history VALUES ('".$row->person_code."',
					    											'".$row->person."',
					    											'".$ip_user."',
					    											getdate(),
					    											'WMS',
					    											'".$user_browser."',
																	getdate(),
																	'".$user_os."',
																	'KANBANSVR',
																	'".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',
																	'".$get_addr."')";
					    $insert = sqlsrv_query($connect, $ins);
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
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#">LOGIN</a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> 
</div>
</body>
</html>
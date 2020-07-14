<?php 
include("koneksi.php");
session_start();
$act=$_GET['act'];
if($act=="logout"){
	unset($_SESSION['id_user']);
	$id_user="";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  	<title>Login</title>
  	<meta name="description" content="Login application" />
  	<meta name="keywords" content="jquery, sliding, toggle, slideUp, slideDown, login, login form, register" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	

	<!-- stylesheets -->
  	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
  	<link rel="stylesheet" href="css/slide.css" type="text/css" media="screen" />
	
  	<!-- PNG FIX for IE6 -->
  	<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
	<![endif]-->
	 
    <!-- jQuery - the core -->
	<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
	<!-- Sliding effect -->
	<script src="js/slide.js" type="text/javascript"></script>

</head>

<body>
	<!-- Login -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Welcome to SITA</h1>
				
			</div>
			<div class="left">
				<form action="#" method="post">
				</form>
				<!--<form class="clearfix" action="#" method="post">
					<h1 class="padlock">Member Login</h1>
					<label class="grey" for="log">Username:</label>
					<input class="field" type="text" name="log" id="log" value="" size="23" />
					<label class="grey" for="pwd">Password:</label>
					<input class="field" type="password" name="pwd" id="pwd" size="23" />
	            	
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>-->
			</div>
			<div class="left right">
				<form class="clearfix" action="#" method="post">
					<h1 class="padlock">Member Login</h1>
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

 			
			$pass_md=md5($password);
			$cek="SELECT USER_ID, JABATAN FROM USER WHERE NAMA_USER='$username' AND PASSWORD='$pass_md'";
			$query=mysql_query($cek);
			$jml=mysql_num_rows($query);
			while($row=mysql_fetch_array($query)){
				 $_SESSION['id_user'] = $row[USER_ID];
				 if($row[JABATAN]=="Supervisor"){
				?>
					<script type="text/javascript">
						location.href="transaksi/permintaan/list_spv.php";
					</script>				
	<?php		 }else if($row[JABATAN]=="Manager"){
				?>
					<script type="text/javascript">
						location.href="transaksi/permintaan/list_man.php";
					</script>				
	<?php		 }else if($row[JABATAN]=="Direktur"){
				?>
					<script type="text/javascript">
						location.href="transaksi/permintaan/list_dir.php";
					</script>				
	<?php		 }else{
				?>
					<script type="text/javascript">
						location.href="home.php";
					</script>				
	<?php		}
			}
			if($jml==0){ 				
				?>
				<script type="text/javascript">
					alert("Maaf, Anda tidak diperkenankan mengakses web ini!");
				</script>				
		<?php
			}//end else nama belum ada dalam database
		}
	?>

				</form>
<!--				<form action="#" method="post">
					<h1>Not a member yet? Sign Up!</h1>				
					<label class="grey" for="signup">Username:</label>
					<input class="field" type="text" name="signup" id="signup" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
					<label>A password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
-->			</div>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello Guest!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#">Log In | Register</a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->

<!--    <div id="container">
		<div id="content" style="padding-top:100px;">
			<h1>Mrs. Sha</h1>
			<img src="images/water-wallpapers.jpg" width="750" height="350" border="0" />
				
			
		</div><!-- / content 		
	</div>
-->	<!-- / container -->
</body>
</html>

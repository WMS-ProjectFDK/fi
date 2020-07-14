<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SQL</title>
    <link rel="icon" type="image/png" href="../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../themes/icon.css" />
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
	<style>
	*{
	font-size:12px;
	}
	body {
		font-family:verdana,helvetica,arial,sans-serif;
		padding:20px;
		font-size:12px;
		margin:0;
	}
	h2 {
		font-size:18px;
		font-weight:bold;
		margin:0;
		margin-bottom:15px;
	}
	.demo-info{
		padding:0 0 12px 0;
	}
	.demo-tip{
		display:none;
	}
	.fitem{
		padding: 3px 0px;
	}
	.board_2 {
		position: absolute;
		margin-left:725px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	</style>
    </head>
    <body>
	    <form method="post" target="<?php $_SERVER['PHP_SELF'];?>">
		  <textarea type="text" rows="4" cols="100" name="qry" id="qry"></textarea>
		  <input type="submit" name="submit">
		</form>
		<div class="blinktext2"></div>

		<script>
			$(function blink() {
			    $('.blink_me').fadeOut(500).fadeIn(500, blink); 
			});
		</script>
		<?php
		if(isset($_POST['submit'])){
			include("../connect/conn2.php");
			$qry = stripcslashes($_POST['qry']);
			$data = oci_parse($connect, $qry);
			oci_execute($data);
			if($data){
				echo "QUERY EXECUTE SUCCESS";	
			}else{
				echo "QUERY EXECUTE FAILED";	
			}
		}
		?>
    </body>
    </html>
<?php 
session_start();

set_time_limit(0);
ini_set('memory_limit', '-1');
include("../connect/conn_japan.php");

if (isset($_SESSION['id_wms'])){
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SQL</title>
    <link rel="icon" type="image/png" href="../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link href="../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../plugins/bootstrap/js/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
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
	</style>
  </head>
    <body>
    	<?php include ('../ico_logout.php'); ?>
    	<div class="container">
	      <div class="starter-template">
	        <h1></h1>
	        <form method="post" target="<?php $_SERVER['PHP_SELF'];?>">
			    <div class="form-group">	
					  <textarea type="text" rows="4" cols="150" name="qry" id="qry" autofocus="autofocus"></textarea>
					  <p><button type="submit" name="submit" id="submit" class="btn btn-primary">Process</button></p>
				</div>
			</form>
	      </div>
	      <div class="container">
		      <div class="blinktext2"></div>
		  </div>
	    </div>
	    <script>
	    	var x ="";
	    	$(function(){
	    		document.getElementById("qry").autofocus; 
	    	});

			$(function blink() {
			    $('.blink_me').fadeOut(500).fadeIn(500, blink); 
			});
		</script>

		<?php
		if(isset($_POST['submit'])){
			$qry = stripcslashes($_POST['qry']);
			$select = strtoupper(substr($qry, 0, 6));
			$cc = strpos($qry,';');
			if ($select == 'SELECT') {
				$data = oci_parse($connect, $qry);
				oci_execute($data);

				echo "<p><b>".strtoupper($qry)."</b></p><br/><br/>";
				
				echo "<table class='table table-striped'>";
				$ncols = oci_num_fields($data);
				echo "<thead><tr>";
				echo "<th>NO</th>";
				if($ncols != 0){
					for ($i = 1; $i <= $ncols; ++$i) {
					    $colname = oci_field_name($data, $i);
					    echo "  <th>".htmlspecialchars($colname,ENT_QUOTES|ENT_SUBSTITUTE)."</th>";
					}

					echo '</tr></thead>';

					$j=1;
					while (($row = oci_fetch_array($data, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
					    echo "<tr>";
					    echo "<td>".$j."</td>";
					    foreach ($row as $item) {
					        echo "<td>";
					        echo $item!==null?htmlspecialchars($item, ENT_QUOTES|ENT_SUBSTITUTE):"&nbsp;";
					        echo "</td>";
					    }
					    echo "</tr>";
					    $j++;
					}
					echo "</table>";
				}else{
					echo "QUERY EXECUTE FAILED";
				}
			}else{
				if($cc == ''){
					$data = oci_parse($connect, $qry);
					oci_execute($data);
					echo "<p><b>".$qry.";</b></p><br/><br/>";
					if($data){
						echo "QUERY EXECUTE SUCCESS";	
					}else{
						echo "QUERY EXECUTE FAILED";	
					}
				}else{
					$arr_qry = explode(';',$qry);
					for($i=0;$i<count($arr_qry);$i++){
						$data = oci_parse($connect, $arr_qry[$i]);
						oci_execute($data);
						if($data){
							echo $i." QUERY EXECUTE SUCCESS<br/>";		
						}else{
							echo $i." QUERY EXECUTE FAILED<br/>";			
						}
					}
				}
			}
		}
		?>
    </body>
</html>
<?php
}else{
	header('location:../404.html');
}
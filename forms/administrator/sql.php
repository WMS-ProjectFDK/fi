<?php 
session_start();

set_time_limit(0);
ini_set('memory_limit', '-1');
include("../../connect/conn.php");

if (isset($_SESSION['id_wms'])){
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SQL</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link href="../../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../plugins/bootstrap/js/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
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
    	<?php include ('../../ico_logout.php'); ?>
    	<div class="container">
	      <div class="starter-template">
	        <h1></h1>
	        <form method="post" target="<?php $_SERVER['PHP_SELF'];?>">
			    <div class="form-group">	
					<textarea type="text" class="form-control" rows="3" cols="170" name="qry" id="qry" autofocus="autofocus"></textarea>
				</div>
				<button type="submit" class="btn btn-primary" name="submit" id="submit" class="btn btn-primary">Process</button>
			</form>
	      </div>
	      <hr>
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
				$data = sqlsrv_query($connect, $qry);

				if( $data === false ) {
					if( ($errors = sqlsrv_errors() ) != null) {  
				         foreach( $errors as $error){  
				            // echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br/>";  
				            // echo "code: ".$error[ 'code']."<br/>";  
				            echo "message: ".$error[ 'message']."<br/>";  
				         }  
				    }
				}else{
					echo "<p><b>".strtoupper($qry)."</b></p>";	
				
					echo "<table class='table table-striped'>";
					$ncols = sqlsrv_num_fields($data);
					$fieldmeta = sqlsrv_field_metadata($data);

					echo "<thead><tr>";
					echo "<th>NO</th>";
					if($ncols != 0){
						foreach ($fieldmeta as $f) {
			            	// echo $f['Name'] . ": \n";
			            	echo "  <th>".htmlspecialchars($f['Name'])."</th>";
					    }

						echo '</tr></thead>';

						$j=1;
						while (($row = sqlsrv_fetch_array($data, SQLSRV_FETCH_ASSOC)) != false) {
						    echo "<tr>";
						    echo "<td>".$j."</td>";
						    foreach ($row as $item) {
						        echo "<td>";
						        echo var_dump($item)!==null?htmlspecialchars($item, ENT_QUOTES|ENT_SUBSTITUTE):"&nbsp;";
						        echo "</td>";
						    }
						    echo "</tr>";
						    $j++;
						}
						echo "</table>";
					}else{
						echo "QUERY EXECUTE FAILED";
					}
				}
			}else{
				if($cc == ''){
					$data = sqlsrv_query($connect, $qry);
					echo "<p><b>".$qry.";</b></p><br/><br/>";
					if($data){
						echo "QUERY EXECUTE SUCCESS";	
					}else{
						echo "QUERY EXECUTE FAILED";	
					}
				}else{
					$arr_qry = explode(';',$qry);
					for($i=0;$i<count($arr_qry);$i++){
						$data = sqlsrv_query($connect, $arr_qry[$i]);
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
	header('location:../../404.html');
}
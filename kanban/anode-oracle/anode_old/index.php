<?php
	date_default_timezone_set('Asia/Jakarta');
	include("../../connect/conn.php");
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>INSTRUKSI DRY MIX</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
    <script src="jquery.min.js"></script>
	<style>
		*{
		font-size:14px;
		}
		body {
			font-family:verdana,helvetica,arial,sans-serif;
			padding:20px;
			font-size:16px;
			margin:0;
		}
		.fitem{
			padding: 3px 0px;
		}
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:11px;
		}
		table, th, td {
			border: 1px solid #d0d0d0;	
		}
		th {
			color: black;
		}
	</style>
    </head>
    <body>
		<div style="width:100%;height:600px;padding:5px;">
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">SCAN KANBAN UNTUK INSTRUKSI DRY MIX</span> 
			</div>
			<br/>
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">SCAN : </span> 
				<input style="width:450px; border: 1px solid #0099FF;border-radius: 5px;font-size: 20px; height: 30px;" onkeypress="scan(event)" name="scn" id="scn" 
					type="text" autofocus="autofocus" />
			</div>
			<br/>
			<form action="#" method="post">
			<div align="center">
			  <div align="center">
				<fieldset style="width:650px;height: auto;border-radius:4px;">
					<div align="left">
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Gel</span>
						<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Zn Powder</span>
						<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" required="" />
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Kanban No.</span>
						<input style="width:330px;" name="txt_kanban_no" id="txt_kanban_no" readonly/>
					</div>
					<div class="fitem">
						<span style="margin-top:10px; width:130px;display:inline-block;"><b>COMPOSITION :</b></span>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Material</span>
						<span style="width:100px;display:inline-block;">Qty (Kg)</span>
					</div>
					<br/>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Zn Powder</span>
						<input  type="number" style="width:100px;text-align:right;" name="qty_zn" id="qty_zn" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<span style="width:150px;display:inline-block;">Keterangan</span>
						<div style="position:absolute;margin-left: 285px;">
		    				<div class="fitem">
			    				<textarea name="ket" id="ket" cols="40" rows="4"></textarea>
			    			</div>
		    			</div>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Aquapec</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0"/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">PW-150</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0"/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">TH-175B</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_th" id="qty_th" step="0.01" min="0" value="0"/>
					</div>
					</div>
				</fieldset>
				<div style="clear:both;margin-bottom:10px;"></div>
			  </div>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>
			<div align="center" style="margin-top: 10px;">
				<input type="submit" style="width: 150px;" id="submit" name="submit" value="INPUT" disabled=""/>
				<span style="width:50px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>
			<div align="center" id="list_table" style="width:650px;margin-left: 330px;"></div>
		</div>

		<div style="position: absolute;bottom: 10px;width: 100%;" align="center">
			<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		</div>

		<script type="text/javascript">
			var sc = '';

			function content_list(){
				$("#list_table").load("list_instruksi_1.php");
			}

			$(function(){
				var myVar = setInterval(function(){content_list()},1000);

			})

			function scan(event){
				if(event.keyCode == 13 || event.which == 13){
					sc = document.getElementById('scn').value;
		            //alert(sc);
		            var split = sc.split(",");
		            document.getElementById('txt_type_gel').value = split[0];
		            document.getElementById('txt_kanban_no').value = split[2];
		            document.getElementById('txt_type_zn').value = split[1];
		            process();
		            document.getElementById('scn').value = '';
				}
			}

			function process(){
				var split = sc.split(",");
	    		$.ajax({
					type: 'GET',
					url: 'cek_komposisi.php?ty_gel='+split[0]+'&typ_zn='+split[1],
					data: { kode:'kode' },
					success: function(data){
						document.getElementById('qty_zn').value = data[0].zn;
						document.getElementById('qty_aquapec').value = data[0].aquapec;
						document.getElementById('qty_pw').value = data[0].pw150;
						document.getElementById('qty_th').value = data[0].th175b;
						document.getElementById("submit").disabled = false;
					}
				});
			}
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				$type_gel = $_POST['txt_type_gel'];
				$type_zn = $_POST['txt_type_zn'];
				$kanban_no = $_POST['txt_kanban_no'];
								
				$qty_zn = $_POST['qty_zn'];
				$qty_aquapec = $_POST['qty_aquapec'];
				$qty_pw = $_POST['qty_pw'];
				$qty_th = $_POST['qty_th'];
				$ket = $_POST['ket'];

				//cek dahulu no.tag dan type_gel
				$cek = "select count(*) as j from ztb_assy_anode_gel_sts where flag=0 ";
				$data_cek = oci_parse($connect, $cek);
				oci_execute($data_cek);
				$row_cek = oci_fetch_array($data_cek);

				if($row_cek[0] < 2){
					$field .= "KANBAN_NO,"				;	$value .= "$kanban_no," 											;
					$field .= "TYPE_GEL,"			;	$value .= "'$type_gel',"												;
					$field .= "TYPE_ZN,"			;	$value .= "'$type_zn',"													;
					$field .= "DATE_PROD,"			;	$value .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss'),"	;
					$field .= "QTY_ZN,"				;	$value .= "$qty_zn,"													;
					$field .= "QTY_AQUAPEC,"		;	$value .= "$qty_aquapec,"												;
					$field .= "QTY_PW150,"			;	$value .= "$qty_pw,"													;
					$field .= "QTY_TH175B,"			;	$value .= "$qty_th,"													;
					$field .= "REMARK"				;	$value .= "'$ket'" 														;
					chop($field) ;              		chop($value) ;

					$ins = "insert into ztb_assy_anode_gel ($field) values ($value) ";
					$data_ins = oci_parse($connect, $ins);
					oci_execute($data_ins);

					//insert-II
					$field2 .= "KANBAN_NO,"				;	$value2 .= "$kanban_no," 												;
					$field2 .= "TYPE_GEL,"				;	$value2 .= "'$type_gel',"												;
					$field2 .= "TYPE_ZN,"				;	$value2 .= "'$type_zn',"												;
					$field2 .= "FLAG,"					;	$value2 .= "0,"															;
					$field2 .= "UPTO_DATE_INSTRUKSI"	;	$value2 .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss')"	;
					chop($field2) ;              	chop($value2) ;

					$ins2 = "insert into ztb_assy_anode_gel_sts ($field2) values ($value2) ";
					$data_ins2 = oci_parse($connect, $ins2);
					oci_execute($data_ins2);

					//echo $ins;

					if($data_ins){
						echo "<script type='text/javascript'>alert('INSERT DATA SUCCESS');self.location.href = 'index.php';</script>";
					}else{
						echo "<script type='text/javascript'>alert('INSERT DATA FAILED');self.location.href = 'index.php';</script>";
					}
				}else{
					$string = 'INSERT DATA ERROR\nMAX 2 ANTRIAN KE DRY MIX';
					echo "<script type='text/javascript'>alert(\"$string\");self.location.href = 'index.php';</script>";
				}
			}
		?>
 	</body>
	</html>
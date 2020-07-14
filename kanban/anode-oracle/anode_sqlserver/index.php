<?php
	date_default_timezone_set('Asia/Jakarta');
	include("../../connect/conn_kanbansys.php");

	if(intval(date('H')) >= 7){
		//ini jika lebih dari 07:00
		$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
			where date_prod between 
			(SELECT CONVERT(VARCHAR(10), SYSDATETIME(),121) + ' 07:00:00')
			AND
			(SELECT CONVERT(VARCHAR(10),CAST(DATEADD(d,+1,GETDATE()) AS DATE)) + ' 07:00:00')";
	}else{
		//ini jika jam kurang dari 07:00
		$jum_adukan = "select count(*) jumlah_adukan from ztb_assy_anode_gel 
			where date_prod between 
			(SELECT CONVERT(VARCHAR(10),CAST(DATEADD(d,-1,GETDATE()) AS DATE)) + ' 07:00:00')
			AND
			(SELECT CONVERT(VARCHAR(10), SYSDATETIME(),121) + ' 07:00:00')";	
	}
	
	$data_jum_aduk = odbc_exec($connect,$jum_adukan);
	$row_jum_aduk = odbc_fetch_object($data_jum_aduk);
	$jumlah_aduk = $row_jum_aduk->jumlah_adukan;
	$adukan_create = $jumlah_aduk+1;
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
		a:link, a:visited {
		    background-color: #f44336;
		    color: white;
		    padding: 3px 35px;
		    text-align: center;
		    text-decoration: none;
		    display: inline-block;
		}

		a:hover, a:active {
		    background-color: red;
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
				<span style="display:inline-block;font-size: 16px;font-weight: bold;width: 130px;">SCAN : </span> 
				<input style="width:450px; border: 1px solid #0099FF;border-radius: 5px;font-size: 20px; height: 30px;" onkeypress="scan(event)" name="scn" id="scn" 
					type="text" autofocus="autofocus" />
			</div>
			<br/>
			<form id="myForm" action="#" method="post">
			<div align="center">
			  <div align="center">
				<fieldset style="width:650px;height: auto;border-radius:4px;">
					<div align="left">
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Type Gel</span>
						<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
						<input style="width:50px;" name="txt_flag" id="txt_flag"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Type Zn Powder</span>
						<select style="width:330px;" name="cmb_type_zn" id="cmb_type_zn" required="">
	    					<option value="" selected="true">-- silahkan pilih --</option>
	    					<option value="FF">FF</option>
	    					<option value="F8">F8</option>
	    					<option value="FG">FG</option>
	    				</select>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Kanban No.</span>
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
						<span style="width:150px;display:inline-block;">Zn Powder</span>
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
						<span style="width:150px;display:inline-block;">Aqupec HV-505 HC</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Aqupec HV-501 E</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Aqupec HV-505 E</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec2" id="qty_aquapec2" step="0.01" min="0" value="0"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">TH-175B</span>
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
				<span style="width:35px;display:inline-block;"></span>
				<a href="javascript:void(0)" id="btn_delete" name="btn_delete" onclick="delete_instruksi()">DELETE</a>
				<span style="width:35px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>
			<div align="center" id="list_table" style="width:650px;margin-left: 330px;"></div>
		</div>
		<br>
		<div style="bottom: 10px;width: 100%;" align="center">
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
				var src = document.getElementById('scn').value;
	            var up = src.toUpperCase();
	            document.getElementById('scn').value = up;
	            
				if(event.keyCode == 13 || event.which == 13){
					sc = document.getElementById('scn').value;
		            //alert(sc);
		            var split = sc.split(",");
		            document.getElementById('txt_type_gel').value = split[0];
		            document.getElementById('txt_kanban_no').value = split[1];
		            process();
		            document.getElementById('scn').value = '';
				}
			}

			var flag = 0;

			function process(){
				var split = sc.split(",");
	    		$.ajax({
					type: 'GET',
					url: 'cek_komposisi.php?ty_gel='+split[0]+'&kanban='+split[1],
					data: { kode:'kode' },
					success: function(data){
						document.getElementById('qty_zn').value = data[0].zn;
						document.getElementById('qty_aquapec').value = data[0].aquapec;
						document.getElementById('qty_pw').value = data[0].pw150;
						document.getElementById('qty_th').value = data[0].th175b;
						document.getElementById('cmb_type_zn').value = data[0].type_zn;
						document.getElementById('qty_aquapec2').value = data[0].aqupec2;
						if(data[0].flag == 1){
							alert("KANBAN SUDAH TERPAKAI");
							var r = confirm("YAKIN INSTRUKSI INI AKAN DIHAPUS?");
						    if (r == true) {
						        delete_instruksi();
						    }
							self.location.href = 'index.php';
						}
						document.getElementById('txt_flag').value = data[0].flag;
						document.getElementById("submit").disabled = false;
					}
				});
			}

			function delete_instruksi(){
				var gel = document.getElementById('txt_type_gel').value;
	            var kanban = document.getElementById('txt_kanban_no').value;
	            if (gel == '' && kanban==''){
	            	alert("DATA TIDAK ADA, SCAN TERLEBIH DAHULU");
	            }else{
	            	$.ajax({
						type: 'GET',
						url: 'delete_instruksi.php?ty_gel='+gel+'&kanban='+kanban,
						data: { kode:'kode' },
						success: function(data){
							if(data[0].successMsg == 'success'){
								document.getElementById("myForm").reset();
							}
						}
					});
	            }
			}
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				if (! $connect){
			    	echo "<script type='text/javascript'>alert('CONNECT TO SERVER FAILED ... !!');</script>";
			  	}else{
					$type_gel = $_POST['txt_type_gel'];
					$flag = $_POST['txt_flag'];
					$type_zn = $_POST['cmb_type_zn'];
					$kanban_no = $_POST['txt_kanban_no'];
									
					$qty_zn = $_POST['qty_zn'];
					$qty_aquapec = $_POST['qty_aquapec'];
					$qty_pw = $_POST['qty_pw'];
					$qty_th = $_POST['qty_th'];
					$qty_aquapec2 = $_POST['qty_aquapec2'];
					$ket = $_POST['ket'];

					//cek dahulu no.tag dan type_gel
					$cek = "select count(*) as j from ztb_assy_anode_gel where upto_date_drymix is null AND upto_date_hasil_anode is null";
					$data_cek = odbc_exec($connect, $cek);
					$row_cek = odbc_fetch_object($data_cek);
					if($row_cek->j < 2){
						$field .= "KANBAN_NO,"			;	$value .= "$kanban_no," 	;
						$field .= "TYPE_GEL,"			;	$value .= "'$type_gel',"	;
						$field .= "TYPE_ZN,"			;	$value .= "'$type_zn',"		;
						$field .= "DATE_PROD,"			;	$value .= "GETDATE(),"		;
						$field .= "QTY_ZN,"				;	$value .= "$qty_zn,"		;
						$field .= "QTY_AQUAPEC,"		;	$value .= "$qty_aquapec,"	;
						$field .= "QTY_PW150,"			;	$value .= "$qty_pw,"		;
						$field .= "QTY_TH175B,"			;	$value .= "$qty_th,"		;
						$field .= "QTY_AQUPEC2,"		;	$value .= "$qty_aquapec2,"	;
						$field .= "REMARK,"				;	$value .= "'$ket'," 		;
						$field .= "UPTO_DATE_INSTRUKSI,";	$value .= "GETDATE(),"		;
						$field .= "NO_ADUKAN"			;	$value .= "$adukan_create"	;
						chop($field) ;              		chop($value) ;

						$ins = "insert into ztb_assy_anode_gel ($field) values ($value) ";
						//echo $ins;
						$data_ins = odbc_exec($connect, $ins);

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
			}
		?>
 	</body>
	</html>
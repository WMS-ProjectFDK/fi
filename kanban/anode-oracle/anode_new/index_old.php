<?php
	include("../../connect/conn.php"); 
	$worker = "select * from ztb_worker where group_worker='ANODE' order by name asc";
	
	$data_worker = oci_parse($connect, $worker);
	oci_execute($data_worker);

	$data_worker2 = oci_parse($connect, $worker);
	oci_execute($data_worker2);	
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Production Anode Gel</title><?php
	include("../../connect/conn.php"); 
	$worker = "select * from ztb_worker where group_worker='ANODE' order by name asc";
	
	$data_worker = oci_parse($connect, $worker);
	oci_execute($data_worker);

	$data_worker2 = oci_parse($connect, $worker);
	oci_execute($data_worker2);	
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Production Anode Gel</title>
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
	</style>
    </head>
    <body>
		<div style="width:100%;height:600px;padding:5px;">
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">INPUT HASIL PRODUKSI ANODE GEL</span> 
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
				<div align="left">
				<fieldset style="float:left;width:47%;height: 270px;border-radius:4px;"><legend><span class="style3"><strong>VIEW</strong></span></legend>
					<!-- <div class="fitem">
						<span style="width:130px;display:inline-block;">Date</span>
						<input style="width:250px;" type="text" name="date" id="date" readonly="" />
					</div> -->
					<div class="fitem">
						<span style="width:130px;display:inline-block;">No. Tag</span>
						<input style="width:330px;" name="txt_no_tag" id="txt_no_tag" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Gel</span>
						<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Zn Powder</span>
						<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" readonly/>
					</div>
					<!-- <div class="fitem">
						<span style="width:130px;display:inline-block;">No. Process</span>
						<input style="width:330px;" name="txt_no_proses" id="txt_no_proses" readonly="" />
					</div> -->
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Density (gr/cc)</span>
						<input style="width:330px;" name="txt_density" id="txt_density" required="true"/>
					</div>
				</fieldset>

				<fieldset style="position:absolute;margin-left:48%;border-radius:4px;width: 47%; height: 270px;"><legend><span class="style3"><strong>COMPOSITION</strong></span></legend>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Material</span>
						<span style="width:100px;display:inline-block;">Qty (Kg)</span>
						<span style="width:50px;display:inline-block;"></span>
						<span style="width:150px;display:inline-block;">No. Lot Material</span>
					</div>
					<br/>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Zn Powder</span>
						<input  type="number" style="width:100px;text-align:right;" name="qty_zn" id="qty_zn" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_zn" id="lot_zn"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Aquapec</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_aquapec" id="lot_aquapec"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">PW-150</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_pw" id="lot_pw"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">TH-175B</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_th" id="qty_th" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_th" id="lot_th"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Electrolyte L</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_electrolit" id="qty_electrolit" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_electrolit" id="lot_electrolit"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Air</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_air" id="qty_air" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_air" id="lot_air"/>
					</div>
					<br/>
					<div class="fitem">
						<span style="width:150px;display:inline-block;"><b>TOTAL</b></span>
						<input type="number" style="width:100px;text-align:right;" name="qty_total" id="qty_total" readonly="" step="0.01" min="0" value="0" onchange="tryNumberFormat(this.form.thirdBox);"/>
						<span style="width:50px;display:inline-block;"><b>Kg</b></span>
					</div>
				</fieldset>
				<div style="clear:both;margin-bottom:10px;"></div>
				<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left;">
					<div style="float:left">
						<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Petugas Dry Mix</span>
		    				<select style="width:370px;" name="cmb_dry_mix" id="cmb_dry_mix" required="">
		    					<option value="" selected="true">-- silahkan pilih --</option>
		    					<?php while ($row = oci_fetch_object($data_worker)) {
		    						echo "<option value='".$row->WORKER_ID."'>".$row->WORKER_ID.' - '.$row->NAME."</option>"; } ?>
		    				</select>
		    			</div>
		    			<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Petugas Anode Gel</span>
		    				<select style="width:370px;" name="cmb_anode_gel" id="cmb_anode_gel" required="">
		    					<option value="" selected="true">-- silahkan pilih --</option>
		    					<?php while ($row2 = oci_fetch_object($data_worker2)) {
		    						echo "<option value='".$row2->WORKER_ID."'>".$row2->WORKER_ID.' - '.$row2->NAME."</option>"; } ?>
		    				</select>
		    			</div>
		    		</div>	
	    			<div style="position:absolute;margin-left: 655px;">
	    				<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Keterangan</span>
		    				<textarea name="ket" id="ket" cols="55" rows="2"></textarea>
		    			</div>
	    			</div>
				</fieldset>
				</div>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>
			<div align="center" style="margin-top: 50px;">
				<input type="submit" style="width: 150px;" name="submit" value="INPUT"/>
				<span style="width:50px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>
		</div>

		<div style="position: absolute;bottom: 10px;width: 100%;" align="center">
			<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		</div>

		<script type="text/javascript">
			var sc = '';

			function scan(event){
				if(event.keyCode == 13 || event.which == 13){
					sc = document.getElementById('scn').value;
		            //alert(sc);
		            var split = sc.split(",");
		            document.getElementById('txt_type_gel').value = split[0];
		            document.getElementById('txt_no_tag').value = split[2];
		            document.getElementById('txt_type_zn').value = split[1];
		            process();
		            document.getElementById('scn').value = '';
				}
			}

			function startCalc(){
				interval = setInterval("calc()",1);
			}

			function calc(){
				zn = parseFloat(document.getElementById('qty_zn').value);
				aq = parseFloat(document.getElementById('qty_aquapec').value);
				pw = parseFloat(document.getElementById('qty_pw').value);
				th = parseFloat(document.getElementById('qty_th').value);
				el = parseFloat(document.getElementById('qty_electrolit').value);
				er = parseFloat(document.getElementById('qty_air').value);
				document.getElementById('qty_total').value = ( zn + aq + pw + th + el + er);
			}

			function stopCalc(){
				clearInterval(interval);
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
						document.getElementById('qty_electrolit').value = data[0].electrolyte;
						document.getElementById('qty_air').value = data[0].air;
						document.getElementById('qty_total').value = data[0].total;
					}
				});
			}
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				$no_tag = $_POST['txt_no_tag'];
				$type_gel = $_POST['txt_type_gel'];
				$type_zn = $_POST['txt_type_zn'];
				$density = $_POST['txt_density'];
				$qty_zn = $_POST['qty_zn'];
				$lot_zn = $_POST['lot_zn'];
				$qty_aquapec = $_POST['qty_aquapec'];
				$lot_aquapec = $_POST['lot_aquapec'];
				$qty_pw = $_POST['qty_pw'];
				$lot_pw = $_POST['lot_pw'];
				$qty_th = $_POST['qty_th'];
				$lot_th = $_POST['lot_th'];
				$qty_electrolit = $_POST['qty_electrolit'];
				$lot_electrolit = $_POST['lot_electrolit'];
				$qty_air = $_POST['qty_air'];
				$lot_air = $_POST['lot_air'];
				$qty_total = $_POST['qty_total'];
				$ptgas_dry_mix = $_POST['cmb_dry_mix'];
				$ptgas_anode_gel = $_POST['cmb_anode_gel'];
				$ket = $_POST['ket'];

				//cek dahulu no.tag dan type_gel
				$cek = "select count(*) as j from ztb_assy_anode_gel_sts where no_tag=$no_tag AND type_gel='$type_gel' AND flag=0 ";
				$data_cek = oci_parse($connect, $cek);
				oci_execute($data_cek);
				$row_cek = oci_fetch_array($data_cek);

				if($row_cek[0] == 0){
					$field .= "NO_TAG,"				;	$value .= "$no_tag," 													;
					$field .= "TYPE_GEL,"			;	$value .= "'$type_gel',"												;
					$field .= "TYPE_ZN,"			;	$value .= "'$type_zn',"													;
					$field .= "DENSITY,"			;	$value .= "$density,"													;
					$field .= "DATE_PROD,"			;	$value .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss'),"	;
					$field .= "QTY_ZN,"				;	$value .= "$qty_zn,"													;
					$field .= "LOT_ZN,"				;	$value .= "'$lot_zn'," 													;
					$field .= "QTY_AQUAPEC,"		;	$value .= "$qty_aquapec,"												;
					$field .= "LOT_AQUAPEC,"		;	$value .= "'$lot_aquapec'," 											;
					$field .= "QTY_PW150,"			;	$value .= "$qty_pw,"													;
					$field .= "LOT_PW150,"			;	$value .= "'$lot_pw'," 													;
					$field .= "QTY_TH175B,"			;	$value .= "$qty_th,"													;
					$field .= "LOT_TH175B,"			;	$value .= "'$lot_th'," 													;
					$field .= "QTY_ELEC,"			;	$value .= "$qty_electrolit,"											;
					$field .= "LOT_ELEC,"			;	$value .= "'$lot_electrolit'," 											;
					$field .= "QTY_AIR,"			;	$value .= "$qty_air,"													;
					$field .= "QTY_TOTAL,"			;	$value .= "$qty_total,"													;
					$field .= "WORKER_ID_DRYMIX,"	;	$value .= "'$ptgas_dry_mix'," 											;
					$field .= "WORKER_ID_GEL,"		;	$value .= "'$ptgas_anode_gel'," 										;
					$field .= "REMARK"				;	$value .= "'$ket'" 														;
					chop($field) ;              		chop($value) ;

					$ins = "insert into ztb_assy_anode_gel ($field) values ($value) ";
					$data_ins = oci_parse($connect, $ins);
					oci_execute($data_ins);

					//insert-II
					$field2 .= "NO_TAG,"		;	$value2 .= "$no_tag," 													;
					$field2 .= "TYPE_GEL,"		;	$value2 .= "'$type_gel',"												;
					$field2 .= "FLAG,"			;	$value2 .= "0,"															;	
					$field2 .= "UPTO_DATE"		;	$value2 .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss')"	;
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
					$string = 'INSERT DATA ERROR\nANODE GEL ALREADY EXIST';
					echo "<script type='text/javascript'>alert(\"$string\");self.location.href = 'index.php';</script>";
				}
			}
		?>
 	</body>
	</html>
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
	</style>
    </head>
    <body>
		<div style="width:100%;height:600px;padding:5px;">
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">INPUT HASIL PRODUKSI ANODE GEL</span> 
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
				<div align="left">
				<fieldset style="float:left;width:47%;height: 270px;border-radius:4px;"><legend><span class="style3"><strong>VIEW</strong></span></legend>
					<!-- <div class="fitem">
						<span style="width:130px;display:inline-block;">Date</span>
						<input style="width:250px;" type="text" name="date" id="date" readonly="" />
					</div> -->
					<div class="fitem">
						<span style="width:130px;display:inline-block;">No. Tag</span>
						<input style="width:330px;" name="txt_no_tag" id="txt_no_tag" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Gel</span>
						<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Zn Powder</span>
						<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" readonly/>
					</div>
					<!-- <div class="fitem">
						<span style="width:130px;display:inline-block;">No. Process</span>
						<input style="width:330px;" name="txt_no_proses" id="txt_no_proses" readonly="" />
					</div> -->
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Density (gr/cc)</span>
						<input style="width:330px;" name="txt_density" id="txt_density" required="true"/>
					</div>
				</fieldset>

				<fieldset style="position:absolute;margin-left:48%;border-radius:4px;width: 47%; height: 270px;"><legend><span class="style3"><strong>COMPOSITION</strong></span></legend>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Material</span>
						<span style="width:100px;display:inline-block;">Qty (Kg)</span>
						<span style="width:50px;display:inline-block;"></span>
						<span style="width:150px;display:inline-block;">No. Lot Material</span>
					</div>
					<br/>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Zn Powder</span>
						<input  type="number" style="width:100px;text-align:right;" name="qty_zn" id="qty_zn" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_zn" id="lot_zn"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Aquapec</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_aquapec" id="lot_aquapec"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">PW-150</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_pw" id="lot_pw"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">TH-175B</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_th" id="qty_th" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_th" id="lot_th"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Electrolyte L</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_electrolit" id="qty_electrolit" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_electrolit" id="lot_electrolit"/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Air</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_air" id="qty_air" step="0.01" min="0" value="0" onFocus="startCalc();"/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_air" id="lot_air"/>
					</div>
					<br/>
					<div class="fitem">
						<span style="width:150px;display:inline-block;"><b>TOTAL</b></span>
						<input type="number" style="width:100px;text-align:right;" name="qty_total" id="qty_total" readonly="" step="0.01" min="0" value="0" onchange="tryNumberFormat(this.form.thirdBox);"/>
						<span style="width:50px;display:inline-block;"><b>Kg</b></span>
					</div>
				</fieldset>
				<div style="clear:both;margin-bottom:10px;"></div>
				<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:98%; float:left;">
					<div style="float:left">
						<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Petugas Dry Mix</span>
		    				<select style="width:370px;" name="cmb_dry_mix" id="cmb_dry_mix" required="">
		    					<option value="" selected="true">-- silahkan pilih --</option>
		    					<?php while ($row = oci_fetch_object($data_worker)) {
		    						echo "<option value='".$row->WORKER_ID."'>".$row->WORKER_ID.' - '.$row->NAME."</option>"; } ?>
		    				</select>
		    			</div>
		    			<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Petugas Anode Gel</span>
		    				<select style="width:370px;" name="cmb_anode_gel" id="cmb_anode_gel" required="">
		    					<option value="" selected="true">-- silahkan pilih --</option>
		    					<?php while ($row2 = oci_fetch_object($data_worker2)) {
		    						echo "<option value='".$row2->WORKER_ID."'>".$row2->WORKER_ID.' - '.$row2->NAME."</option>"; } ?>
		    				</select>
		    			</div>
		    		</div>	
	    			<div style="position:absolute;margin-left: 655px;">
	    				<div class="fitem">
		    				<span style="width:150px;display:inline-block;">Keterangan</span>
		    				<textarea name="ket" id="ket" cols="55" rows="2"></textarea>
		    			</div>
	    			</div>
				</fieldset>
				</div>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>
			<div align="center" style="margin-top: 50px;">
				<input type="submit" style="width: 150px;" name="submit" value="INPUT"/>
				<span style="width:50px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>
		</div>

		<div style="position: absolute;bottom: 10px;width: 100%;" align="center">
			<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		</div>

		<script type="text/javascript">
			var sc = '';

			function scan(event){
				if(event.keyCode == 13 || event.which == 13){
					sc = document.getElementById('scn').value;
		            //alert(sc);
		            var split = sc.split(",");
		            document.getElementById('txt_type_gel').value = split[0];
		            document.getElementById('txt_no_tag').value = split[2];
		            document.getElementById('txt_type_zn').value = split[1];
		            process();
		            document.getElementById('scn').value = '';
				}
			}

			function startCalc(){
				interval = setInterval("calc()",1);
			}

			function calc(){
				zn = parseFloat(document.getElementById('qty_zn').value);
				aq = parseFloat(document.getElementById('qty_aquapec').value);
				pw = parseFloat(document.getElementById('qty_pw').value);
				th = parseFloat(document.getElementById('qty_th').value);
				el = parseFloat(document.getElementById('qty_electrolit').value);
				er = parseFloat(document.getElementById('qty_air').value);
				document.getElementById('qty_total').value = ( zn + aq + pw + th + el + er);
			}

			function stopCalc(){
				clearInterval(interval);
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
						document.getElementById('qty_electrolit').value = data[0].electrolyte;
						document.getElementById('qty_air').value = data[0].air;
						document.getElementById('qty_total').value = data[0].total;
					}
				});
			}
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				$no_tag = $_POST['txt_no_tag'];
				$type_gel = $_POST['txt_type_gel'];
				$type_zn = $_POST['txt_type_zn'];
				$density = $_POST['txt_density'];
				$qty_zn = $_POST['qty_zn'];
				$lot_zn = $_POST['lot_zn'];
				$qty_aquapec = $_POST['qty_aquapec'];
				$lot_aquapec = $_POST['lot_aquapec'];
				$qty_pw = $_POST['qty_pw'];
				$lot_pw = $_POST['lot_pw'];
				$qty_th = $_POST['qty_th'];
				$lot_th = $_POST['lot_th'];
				$qty_electrolit = $_POST['qty_electrolit'];
				$lot_electrolit = $_POST['lot_electrolit'];
				$qty_air = $_POST['qty_air'];
				$lot_air = $_POST['lot_air'];
				$qty_total = $_POST['qty_total'];
				$ptgas_dry_mix = $_POST['cmb_dry_mix'];
				$ptgas_anode_gel = $_POST['cmb_anode_gel'];
				$ket = $_POST['ket'];

				//cek dahulu no.tag dan type_gel
				$cek = "select count(*) as j from ztb_assy_anode_gel_sts where no_tag=$no_tag AND type_gel='$type_gel' AND flag=0 ";
				$data_cek = oci_parse($connect, $cek);
				oci_execute($data_cek);
				$row_cek = oci_fetch_array($data_cek);

				if($row_cek[0] == 0){
					$field .= "NO_TAG,"				;	$value .= "$no_tag," 													;
					$field .= "TYPE_GEL,"			;	$value .= "'$type_gel',"												;
					$field .= "TYPE_ZN,"			;	$value .= "'$type_zn',"													;
					$field .= "DENSITY,"			;	$value .= "$density,"													;
					$field .= "DATE_PROD,"			;	$value .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss'),"	;
					$field .= "QTY_ZN,"				;	$value .= "$qty_zn,"													;
					$field .= "LOT_ZN,"				;	$value .= "'$lot_zn'," 													;
					$field .= "QTY_AQUAPEC,"		;	$value .= "$qty_aquapec,"												;
					$field .= "LOT_AQUAPEC,"		;	$value .= "'$lot_aquapec'," 											;
					$field .= "QTY_PW150,"			;	$value .= "$qty_pw,"													;
					$field .= "LOT_PW150,"			;	$value .= "'$lot_pw'," 													;
					$field .= "QTY_TH175B,"			;	$value .= "$qty_th,"													;
					$field .= "LOT_TH175B,"			;	$value .= "'$lot_th'," 													;
					$field .= "QTY_ELEC,"			;	$value .= "$qty_electrolit,"											;
					$field .= "LOT_ELEC,"			;	$value .= "'$lot_electrolit'," 											;
					$field .= "QTY_AIR,"			;	$value .= "$qty_air,"													;
					$field .= "QTY_TOTAL,"			;	$value .= "$qty_total,"													;
					$field .= "WORKER_ID_DRYMIX,"	;	$value .= "'$ptgas_dry_mix'," 											;
					$field .= "WORKER_ID_GEL,"		;	$value .= "'$ptgas_anode_gel'," 										;
					$field .= "REMARK"				;	$value .= "'$ket'" 														;
					chop($field) ;              		chop($value) ;

					$ins = "insert into ztb_assy_anode_gel ($field) values ($value) ";
					$data_ins = oci_parse($connect, $ins);
					oci_execute($data_ins);

					//insert-II
					$field2 .= "NO_TAG,"		;	$value2 .= "$no_tag," 													;
					$field2 .= "TYPE_GEL,"		;	$value2 .= "'$type_gel',"												;
					$field2 .= "FLAG,"			;	$value2 .= "0,"															;	
					$field2 .= "UPTO_DATE"		;	$value2 .= "to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss')"	;
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
					$string = 'INSERT DATA ERROR\nANODE GEL ALREADY EXIST';
					echo "<script type='text/javascript'>alert(\"$string\");self.location.href = 'index.php';</script>";
				}
			}
		?>
 	</body>
	</html>
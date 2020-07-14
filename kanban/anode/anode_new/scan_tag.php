<?php
	date_default_timezone_set('Asia/Jakarta');
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
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">INPUT HASIL PRODUKSI ANODE GEL</span> 
			</div>
			<div align="center" id="list_table" style="width:650px;margin-left: 250px;"></div>
			<div class="fitem" align="center"></div>
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">SCAN KANBAN : </span> 
				<input style="width:450px; border: 1px solid #0099FF;border-radius: 5px;font-size: 20px; height: 30px;" onkeypress="scan_kanban(event)" name="scn_kanban" id="scn_kanban" 
					type="text" autofocus="autofocus" />
			</div>
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">SCAN TAG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span> 
				<input style="width:450px; border: 1px solid #0099FF;border-radius: 5px;font-size: 20px; height: 30px;" onkeypress="scan_tag(event)" name="scn_tag" id="scn_tag" 
					type="text" autofocus="autofocus" />
			</div>
			<form action="#" method="post">
			<div align="center">
				<div align="left">
				<fieldset style="float:left;width:47%;height: 330px;border-radius:4px;"><legend><span class="style3"><strong>VIEW</strong></span></legend>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Kanban No.</span>
						<input style="width:330px;" name="txt_kanban_no" id="txt_kanban_no" readonly/>
						<input style="width:330px;" name="txt_proses_no" id="txt_proses_no" readonly="" hidden="" />
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Tag No.</span>
						<input style="width:330px;" name="txt_no_tag" id="txt_no_tag" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Gel</span>
						<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Type Zinc Powder</span>
						<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" readonly/>
					</div>
					<div class="fitem">
						<span style="width:130px;display:inline-block;">Density (gr/cc)</span><!-- 
						<input style="width:330px;" name="txt_density" id="txt_density" required="true"/> -->
						<input type="number" style="width:330px;text-align:right;" name="txt_density" id="txt_density" step="0.001" min="0" required="" />
					</div>
					<div class="fitem"><br/><br/></div>
					<fieldset style="position: relative; bottom: 0px;border:1px solid #d0d0d0; border-radius:2px; width:95%;">
						<div style="float:left">
							<div class="fitem">
			    				<span style="width:150px;display:inline-block;">Petugas Dry Mix</span>
			    				<select style="width:370px;" name="cmb_dry_mix" id="cmb_dry_mix" disabled="">
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
					</fieldset>
				</fieldset>

				<fieldset style="position:absolute;margin-left:48%;border-radius:4px;width: 47%; height: 330px;"><legend><span class="style3"><strong>COMPOSITION</strong></span></legend>
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
						<input type="text" style="width:150px;" name="lot_zn" id="lot_zn" readonly=""/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">Aquapec</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0" onFocus="startCalc();" readonly=""/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_aquapec" id="lot_aquapec" readonly=""/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">PW-150</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0" onFocus="startCalc();" readonly=""/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_pw" id="lot_pw" readonly=""/>
					</div>
					<div class="fitem">
						<span style="width:150px;display:inline-block;">TH-175B</span>
						<input type="number" style="width:100px;text-align:right;" name="qty_th" id="qty_th" step="0.01" min="0" value="0" onFocus="startCalc();" readonly=""/>
						<span style="width:50px;display:inline-block;"></span>
						<input type="text" style="width:150px;" name="lot_th" id="lot_th" readonly=""/>
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
					<div align="center" >
						<input type="submit" style="width: 150px;" id="submit" name="submit" value="INPUT" disabled="" />
						<span style="width:50px;display:inline-block;"></span>
						<input type="reset" style="width: 150px;" value="Cancel">
					</div>
				</div>
			</div>
			</form>
		</div>

		<div style="position: absolute;bottom: 10px;width: 100%;" align="center">
			<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		</div>

		<script type="text/javascript">
			var sc_kanban = '';
			var sc_tag = '';

			function content_list(){
				$("#list_table").load("list_instruksi_3.php");
			}

			$(function(){
				var myVar = setInterval(function(){content_list()},1000);
			})

			function scan_kanban(event){
				var src1 = document.getElementById('scn_kanban').value;
				var search1 = src1.toUpperCase();
				document.getElementById('scn_kanban').value = search1;

		        if(event.keyCode == 13 || event.which == 13){
		            sc_kanban = document.getElementById('scn_kanban').value;
		        	var split = sc_kanban.split(",");
		        	$.ajax({
						type: 'GET',
						url: 'cek_scan_tag.php?type_gel='+split[0]+'&kanban_no='+split[1],
						data: { kode:'kode' },
						success: function(data){
							document.getElementById('txt_proses_no').value = data[0].ID;
							document.getElementById('txt_type_gel').value = data[0].TYPE_GEL;
							document.getElementById('txt_type_zn').value = data[0].TYPE_ZN;
							document.getElementById('txt_kanban_no').value = data[0].KANBAN_NO;
							document.getElementById('qty_zn').value = data[0].QTY_ZN;
							document.getElementById('lot_zn').value = data[0].LOT_ZN;
							document.getElementById('qty_aquapec').value = data[0].QTY_AQUAPEC;
							document.getElementById('lot_aquapec').value = data[0].LOT_AQUAPEC;
							document.getElementById('qty_pw').value = data[0].QTY_PW150;
							document.getElementById('lot_pw').value = data[0].LOT_PW150;
							document.getElementById('qty_th').value = data[0].QTY_TH175B;
							document.getElementById('lot_th').value = data[0].LOT_TH175B;
							document.getElementById('qty_electrolit').value = data[0].ELECTROLYTE;
							document.getElementById('qty_air').value = data[0].AIR;
							document.getElementById('qty_total').value = data[0].TOTAL;
							document.getElementById('cmb_dry_mix').value = data[0].WORKER_ID_DRYMIX
							document.getElementById('scn_tag').focus();
						}
					});
		        }
		    }

		    function scan_tag(event){
		    	var src2 = document.getElementById('scn_tag').value;
				var search2 = src2.toUpperCase();
				document.getElementById('scn_tag').value = search2;
		    	if(event.keyCode == 13 || event.which == 13){
		    		sc_tag = document.getElementById('scn_tag').value;
		    		proses();
		    	}
		    }

		    function proses(){
		    	var split1 = sc_kanban.split(",");
		    	var split2 = sc_tag.split(",");

		    	//alert(sc_tag);
		    	if(split1[0] != split2[0]){
		    		alert("TYPE GEL TIDAK SAMA");
		    		document.getElementById('scn_tag').value = '';
		    	}else{
		    		document.getElementById('txt_no_tag').value = split2[1];
		    		document.getElementById('scn_kanban').value = '';
					document.getElementById('scn_tag').value = '';
					document.getElementById('submit').disabled = false;
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
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				$id = $_POST['txt_proses_no'];
				$no_tag = $_POST['txt_no_tag'];
				$density = $_POST['txt_density'];
				$qty_electrolit = $_POST['qty_electrolit'];
				$lot_electrolit = $_POST['lot_electrolit'];
				$qty_air = $_POST['qty_air'];
				$lot_air = $_POST['lot_air'];
				$qty_total = $_POST['qty_total'];
				$ptgas_anode_gel = $_POST['cmb_anode_gel'];

				$field .= "no_tag = $no_tag," 																	;
				$field .= "density = $density," 																;
				$field .= "qty_elec = $qty_electrolit," 														;
				$field .= "lot_elec = '$lot_electrolit'," 														;
				$field .= "qty_air= $qty_air," 																	;
				$field .= "qty_total = $qty_total,"																;
				$field .= "worker_id_gel= '$ptgas_anode_gel'," 													;
				$field .= "UPTO_DATE_HASIL_ANODE = to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss')"	;

				$upd = "update ztb_assy_anode_gel set $field where id = $id ";
				$data_upd = oci_parse($connect, $upd);
				oci_execute($data_upd);

				$upd2 = "update ztb_assy_anode_gel_sts set no_tag='', flag=0, pemakaian_assy = 1
					where rowid= (select b.rowid as row_sts from ztb_assy_anode_gel a
              					  inner join ztb_assy_anode_gel_sts b on a.kanban_no= b.kanban_no AND a.type_gel= b.type_gel AND a.type_zn= b.type_zn
              					  where a.id = $id AND b.no_tag is null)";
              	$data_upd2 = oci_parse($connect, $upd2);
				oci_execute($data_upd2);

				if($data_upd2){
					echo "<script type='text/javascript'>alert('INSERT DATA SUCCESS');self.location.href = 'scan_tag.php';</script>";
				}else{
					echo "<script type='text/javascript'>alert('INSERT DATA FAILED');self.location.href = 'scan_tag.php';</script>";
				}
			}
		?>
 	</body>
	</html>
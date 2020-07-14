<?php
	date_default_timezone_set('Asia/Jakarta');
	include("../../connect/conn.php");
	$worker = "select * from ztb_worker where group_worker='ANODE' order by name asc";
	$data_worker = oci_parse($connect, $worker);
	oci_execute($data_worker);
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
		.link_A{
		 	display:block;
		 	background-color: #E6E6E6;
		 	text-decoration: none; 
		 	color: black;
		 	height: 25px;
		 	width: 200px;
		}

	</style>
    </head>
    <body>
		<div style="width:auto;height:auto;padding:5px;">
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">INSTRUKSI DRY MIX 2nd FLOOR</span> 
			</div>
			<br/>
			<form action="#" method="post">
			<div align="center">
				<div align="center" id="list_table" style="width:650px;"></div>
				<div align="left" style="width:100%;">
					<fieldset style="float:left;width:48.5%;height: 290px;border-radius:4px;"><legend><span class="style3"><strong>VIEW</strong></span></legend>
						<div class="fitem">
							<span style="width:130px;display:inline-block;">Type Gel</span>
							<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly/>
						</div>
						<div class="fitem">
							<span style="width:130px;display:inline-block;">Type Zn Powder</span>
							<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" readonly/>
						</div>
						<div class="fitem">
							<span style="width:130px;display:inline-block;">Kanban No.</span>
							<input style="width:330px;" name="txt_no_tag" id="txt_kanban_no" readonly/>
						</div>
						<div class="fitem">
							<span style="width:130px;display:inline-block;">Adukan Ke</span>
							<input style="width:330px;" name="txt_adukan_ke" id="txt_adukan_ke" readonly=""/>
						</div>
						<div class="fitem">
							<span style="width:130px;display:inline-block;">No. Transaksi</span>
							<input style="width:330px;" name="txt_proses_no" id="txt_proses_no" readonly=""/>
						</div>
						<div class="fitem"><br/><br/></div>
						<fieldset style="position: relative; bottom: 0px;border:1px solid #d0d0d0; border-radius:2px; width:550px;">
							<div style="float:left">
								<div class="fitem">
				    				<span style="width:150px;display:inline-block;">Petugas Dry Mix</span>
				    				<select style="width:370px;" name="cmb_dry_mix" id="cmb_dry_mix" required="">
				    					<option value="" selected="true">-- silahkan pilih --</option>
				    					<?php while ($row = oci_fetch_object($data_worker)) {
				    						echo "<option value='".$row->WORKER_ID."'>".$row->WORKER_ID.' - '.$row->NAME."</option>"; } ?>
				    				</select>
				    			</div>
				    		</div>
						</fieldset>
					</fieldset>

					<fieldset style="position:absolute;margin-left:48.5%;border-radius:4px;width: 48.5%; height: 290px;"><legend><span class="style3"><strong>COMPOSITION</strong></span></legend>
						<div class="fitem">
							<span style="width:150px;display:inline-block;">Material</span>
							<span style="width:100px;display:inline-block;">Qty (Kg)</span>
							<span style="width:50px;display:inline-block;"></span>
							<span style="width:150px;display:inline-block;">No. Lot Material</span>
						</div>
						<br/>
						<div class="fitem">
							<span style="width:150px;display:inline-block;">Zn Powder</span>
							<input  type="number" style="width:100px;text-align:right;" name="qty_zn" id="qty_zn" step="0.01" min="0" value="0" readonly=""/>
							<span style="width:50px;display:inline-block;"></span>
							<input type="text" style="width:150px;" name="lot_zn" id="lot_zn" required=""/>
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;">Aqupec HV-505 HC</span>
							<input type="number" style="width:100px;text-align:right;" name="qty_aquapec" id="qty_aquapec" step="0.01" min="0" value="0" readonly=""/>
							<span style="width:50px;display:inline-block;"></span>
							<input type="text" style="width:150px;" name="lot_aquapec" id="lot_aquapec" required=""/>
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;">Aqupec HV-501 E</span>
							<input type="number" style="width:100px;text-align:right;" name="qty_pw" id="qty_pw" step="0.01" min="0" value="0" readonly=""/>
							<span style="width:50px;display:inline-block;"></span>
							<input type="text" style="width:150px;" name="lot_pw" id="lot_pw" required=""/>
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;">TH-175B</span>
							<input type="number" style="width:100px;text-align:right;" name="qty_th" id="qty_th" step="0.01" min="0" value="0" readonly=""/>
							<span style="width:50px;display:inline-block;"></span>
							<input type="text" style="width:150px;" name="lot_th" id="lot_th" required=""/>
						</div>
						<div class="fitem">
		    				<span style="width:150px;display:inline-block;v">Keterangan</span>
		    				<textarea name="ket" id="ket" cols="55" rows="3" readonly=""></textarea>
		    			</div>
					</fieldset>
				</div>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>

			<div align="center">
				<input type="submit" style="width: 150px;" id="submit" name="submit" value="FINISH" disabled="" />
				<span style="width:50px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>

			<div style="position: absolute;bottom: 20px;width: 100%;" align="center">
				<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
			</div>
		</div>

		<script type="text/javascript">
			function content_list(){
				$("#list_table").load("list_instruksi_2.php");
			}

			$(function(){
				var myVar = setInterval(function(){content_list()},1000);
			})

			function proses(){
	    		$.ajax({
					type: 'GET',
					url: 'cek_antrian.php',
					data: { kode:'kode' },
					success: function(data){
						document.getElementById('txt_type_gel').value = data[0].type_gel;
						document.getElementById('txt_type_zn').value = data[0].type_zn;
						document.getElementById('txt_kanban_no').value = data[0].kanban_no;
						document.getElementById('txt_proses_no').value = data[0].process_no;
						document.getElementById('txt_adukan_ke').value = data[0].adukan_ke;
						document.getElementById('qty_zn').value = data[0].zn;
						document.getElementById('qty_aquapec').value = data[0].aquapec;
						document.getElementById('qty_pw').value = data[0].pw150;
						document.getElementById('qty_th').value = data[0].th175b;
						document.getElementById('ket').value = data[0].remark;
						document.getElementById('submit').disabled = false;
					}
				});
			}
		</script>
		
		<?php
			if(isset($_POST['submit'])){
				if (! $connect){
			    	echo "<script type='text/javascript'>alert('CONNECT TO SERVER FAILED ... !!');</script>";
			  	}else{
					$type_gel = $_POST['txt_type_gel'];
					$type_zn = $_POST['txt_type_zn'];
					$kanban_no = $_POST['txt_kanban_no'];
					$proses_no = $_POST['txt_proses_no'];
					$dry_mix = $_POST['cmb_dry_mix'];
					$lot_zn = $_POST['lot_zn'];
					$lot_aquapec = $_POST['lot_aquapec'];
					$lot_pw = $_POST['lot_pw'];
					$lot_th = $_POST['lot_th'];

					$cek = "select count(*) from ztb_assy_anode_gel 
						where upto_date_instruksi is not null and upto_date_drymix is not null AND upto_date_hasil_anode is null";
					$data_cek = oci_parse($connect, $cek);
					oci_execute($data_cek);
					$row_cek = oci_fetch_array($data_cek);

					if($type_gel == '' AND $type_zn == '' AND $kanban_no == ''){
						echo "<script type='text/javascript'>alert('DATA BELUM LENGKAP');</script>";
					}elseif($row_cek[0] >= 2){
						$string = 'INSERT DATA ERROR\nHASIL PRODUKSI ANODE GEL SUDAH MAKSIMAL';
						echo "<script type='text/javascript'>alert(\"$string\");</script>";
					}else{
						$field .= "worker_id_drymix = '$dry_mix'," 													;
						$field .= "lot_zn = '$lot_zn'," 															;
						$field .= "lot_aquapec = '$lot_aquapec'," 													;
						$field .= "lot_pw150 = '$lot_pw'," 															;
						$field .= "lot_th175B = '$lot_th',"															;
						$field .= "upto_date_drymix = to_date('".date('Y-m-d H:i:s')."','yyyy-mm-dd hh24:mi:ss') "	;
						chop($field);
						$upd = "update ztb_assy_anode_gel set $field where id = $proses_no ";
						$data_upd = oci_parse($connect, $upd);
						oci_execute($data_upd);

						if($data_upd){
							echo "<script type='text/javascript'>
									alert('INSERT DATA SUCCESS');
									self.location.href = 'instruksi_dry_mix.php';
									document.getElementById('cmb_dry_mix').value = ".$dry_mix."
								  </script>";
						}else{
							echo "<script type='text/javascript'>alert('INSERT DATA FAILED');self.location.href = 'instruksi_dry_mix.php';</script>";
						}
					}
				}
			}
		?>
 	</body>
	</html>
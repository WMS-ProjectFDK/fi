<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Consumption Anode Gel</title>
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
    <body onload="disableTest();">
		<div id="p" class="easyui-panel" title="PRODUCTION ANODE GEL" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'" style="width:100%;">
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">PEMAKAIAN ANODE GEL (ASSEMBLY)</span> 
			</div>
			<br/>
			<div class="fitem" align="center">
				<span style="display:inline-block;font-size: 16px;font-weight: bold;">SCAN : </span> 
				<input style="width:450px; border: 1px solid #0099FF;border-radius: 5px;font-size: 20px; height: 30px;" onkeypress="scan(event)" name="scn" id="scn" 
					type="text" autofocus="autofocus" />
			</div><br/>

			<form action="#" method="post">
			<div align="center">
				<fieldset style="width:630px;height: 240px;border-radius:4px;">
					<div align="left" style="margin-left: 70px;margin-top: 30px;">
						<div class="fitem">
							<!-- <span style="width:150px;display:inline-block;float:left;">Date</span>
							<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?//date();?>"/>  -->
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">No. Tag</span>
							<input style="width:330px;" name="txt_no_tag" id="txt_no_tag" readonly="" />
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">Type Gel</span>
							<input style="width:330px;" name="txt_type_gel" id="txt_type_gel" readonly="" />
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">Type Zn Powder</span>
							<input style="width:330px;" name="txt_type_zn" id="txt_type_zn" readonly="" />
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">Adukan Ke</span>
							<input style="width:330px;" name="txt_no" id="txt_no" readonly="" hidden="" />
							<input style="width:330px;" name="txt_adukan_ke" id="txt_adukan_ke" readonly=""/>
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">Densisity</span>
							<input style="width:330px;" name="txt_density" id="txt_density" readonly="" />
						</div>
						<div class="fitem">
							<span style="width:150px;display:inline-block;float:left;">Line Assembly</span>
							<select style="width:335px;" name="cmb_assyline" id="cmb_assyline" required="">
								<option selected="" value="">-- silahkan pilih --</option>
								<option value="LR01#1">LR01#1</option>
								<option value="LR03#1">LR03#1</option>
								<option value="LR03#2">LR03#2</option>
								<option value="LR06#1">LR06#1</option>
								<option value="LR06#2">LR06#2</option>
								<option value="LR06#3">LR06#3</option>
								<option value="LR06#4(T)">LR06#4(T)</option>
								<option value="LR06#5">LR06#5</option>
								<option value="LR06#6">LR06#6</option>
							</select>
						</div>
					</div>
				</fieldset>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>
			<div align="center" style="margin-top: 10px;">
				<input type="submit" style="width: 150px;" name="submit" value="INPUT"/>
				<span style="width:50px;display:inline-block;"></span>
				<input type="reset" style="width: 150px;" value="Cancel">
			</div>
			</form>

			<div style="bottom: 10px;width: 100%;" align="center">
				<small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
			</div>
		</div>

		<script type="text/javascript">
			var sc = '';

			function content_list(){
				$("#list_table").load("list_instruksi_4.php");
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
		            document.getElementById('txt_type_zn').value = split[1];
		            process();

		            document.getElementById('scn').value = '';
				}
			}

			function process(){
				var split = sc.split(",");

	    		$.ajax({
					type: 'GET',
					url: 'cek_data.php?ty_gel='+split[0]+'&no_tag='+split[1],
					data: { kode:'kode' },
					success: function(data){
						if(data[0].id == 'ERROR'){
							alert(data[0].density);
							self.location.href = 'anode_gel_assy.php';
						}else{
							document.getElementById('txt_no').value = data[0].id;
							document.getElementById('txt_density').value = data[0].density;
							document.getElementById('txt_adukan_ke').value = data[0].jum;
							document.getElementById('txt_no_tag').value = data[0].notag;
						}
						
					}
				});
			}
		</script>
		<?php
		include("../../connect/conn_kanbansys.php");
		if(isset($_POST['submit'])){
			if (! $connect){
		    	echo "<script type='text/javascript'>alert('CONNECT TO SERVER FAILED ... !!');</script>";
		  	}else{
				$no_tag = $_POST['txt_no_tag'];
				$type_gel = $_POST['txt_type_gel'];
				$type_zn = $_POST['txt_type_zn'];
				$no_proc = $_POST['txt_no'];
				$density = $_POST['txt_density'];
				$assyline = $_POST['cmb_assyline'];

				$field .= "ASSY_LINE = '$assyline',"	;
				$field .= "DATE_USE = GETDATE()" 		;

				$upd = "update ztb_assy_anode_gel set $field 
					where id = $no_proc ";
				$data_upd = odbc_exec($connect, $upd);

				$upd1 = "update ztb_assy_anode_gel_sts set flag = 0, pemakaian_assy = 1, no_tag=''
					where no_tag = $no_tag AND type_gel = '$type_gel' ";
				$data_upd1 = odbc_exec($connect, $upd1);

				if($data_upd){
					echo "<script type='text/javascript'>alert('UPDATE DATA SUCCESS');self.location.href = 'anode_gel_assy.php';</script>";
				}else{
					echo "<script type='text/javascript'>alert('UPDATE DATA FAILED');</script>";
				}
			}
		}
		?>
 	</body>
	</html>
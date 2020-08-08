<?php include("../../connect/conn.php"); ?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>KANBAN KURAIRE</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	<style>
	*{
	font-size:12px;
	}
	body {
		font-family:verdana,helvetica,arial,sans-serif;
		padding:20px;
		font-size:16px;
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
    <body class="easyui-layout">
		<div id="layout" 
		 data-options="region: 'north',
					   iconCls: 'icon-save',
					   title: 'KURAIRE KANBAN SYSTEM',
					   collapsible:false,
					   modal: true,
					   tools: '#tt',
					   footer: '#footer'"
		 style="height:100%;padding:17px;">
			<fieldset style="position: absolute; float: left;margin-left:5px;border-radius:4px;width: 43%;height: 85.5%;"><legend><span class="style3"><strong>INPUT KURAIRE TRANSAKSI</strong></span></legend>
				<div class="fitem" style="padding: 5px;" align="center">
					<span style="width:50%;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN TO KURAIRE</span>
				</div>
				<div class="fitem" style="padding: 5px;" align="center">
					<input style="width:550px; height: 40px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_kuraire(event)" name="scan_kuraire" id="scan_kuraire" type="text" 
						autofocus="autofocus" />
				</div>
				<hr>
				<div class="fitem">
					<span style="margin-left: 20px;width:100px;display:inline-block;">WO NO.</span>
					<input style="width:445px;" name="wo_no" id="wo_no" class="easyui-textbox" disabled="true" />
				</div>
				<div class="fitem">
					<span style="margin-left: 20px;width:100px;display:inline-block;">ITEM NO.</span>
					<input style="width:100px;" name="item_no" id="item_no" class="easyui-textbox" disabled="true" />
					<input style="width:340px;" name="item_name" id="item_name" class="easyui-textbox" disabled="true"/>
				</div>
				<div class="fitem">
					<span style="margin-left: 20px;width:100px;display:inline-block;">QTY</span>
					<input style="width:100px;" name="qty" id="qty" class="easyui-textbox" disabled="true" />
				</div>
				<div class="fitem">
					<span style="margin-left: 20px;width:100px;display:inline-block;">PALLET</span>
					<input style="width:90px;" name="pallet_a" id="pallet_a" class="easyui-textbox" disabled="true"/>
					<span style="width: 20px;display:inline-block;">TO</span>
					<input style="width:90px;" name="pallet_z" id="pallet_z" class="easyui-textbox" disabled="true"/>
				</div>
				<div class="fitem">
					<span style="margin-left: 20px; width:100px;display:inline-block;">TYPE / GRADE</span>
					<input style="width:90px;" name="type_lr" id="type_lr" class="easyui-textbox" disabled="true"/>
					<span style="width: 20px;display:inline-block;">&nbsp;/&nbsp;</span>
					<input style="width:90px;" name="grade_lr" id="grade_lr" class="easyui-textbox" disabled="true"/>
				</div>
				<div class="fitem">
					<span style="margin-left: 20px;width:100px;display:inline-block;">TGL/DATECODE</span>
					<input style="width:90px;" name="tgl" id="tgl" class="easyui-textbox" disabled="true"/>
					<span style="width: 20px;display:inline-block;">&nbsp;/&nbsp;</span>
					<input style="width:90px;" name="datecode" id="datecode" class="easyui-textbox" disabled="true"/>
				</div>
				<hr>
				<div class="fitem" style="padding: 5px;" align="center">
					<span style="width:50%;display:inline-block;font-size: 18px;">SCAN JUNIOR SPV ID.</span>
				</div>
				<div class="fitem" style="padding: 5px;" align="center">
					<input style="width:550px; height: 40px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" name="scan_junior_spv" id="scan_junior_spv" type="text"/>
				</div>
				<hr>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width: 205px;height: 40px;" id="save_kuraire" class="easyui-linkbutton c2" onclick="save_kuraire()"> KIRIM KE WAREHOUSE </a>
				</div>
			</fieldset>

			<fieldset style="position:absolute;margin-left:46%;border-radius:4px;width: 49%;height: 85.5%;"><legend><span class="style3"><strong>LIST OF KURAIRE</strong></span></legend>
				<table id="dg" class="easyui-datagrid" data-options="method: 'get', height: '97%', singleSelect: true">
					<thead>
		            <tr>
						<th halign="center" data-options="field:'SLIP_NO', width: 70">ID<br>KANBAN</th>
						<th halign="center" data-options="field:'SCAN_TIME', width: 130">SCAN DATE</th>
						<th halign="center" data-options="field:'WO_NO', width: 160">WORK ORDER<br/>NO.</th>
						<th halign="center" data-options="field:'PLT_NO', width: 30, align:'center'">PLT</th>
						<th halign="center" data-options="field:'SLIP_QUANTITY', width: 70, align:'right'">QTY</th>
						<th halign="center" data-options="field:'ITEM_NO', width: 50, align:'center'">ITEM<br/>NO.</th>
						<th halign="center" data-options="field:'ITEM_DESCRIPTION', width: 250">DESC</th>
		            </tr>
		        	</thead>
				</table>
			</fieldset>
			<div style="clear:both;"></div>
			<div id="footer" style="padding:5px;height: 2%;" align="center">
		        <small><i>&copy; Copyright 2020, PT. FDK INDONESIA</i></small>
		    </div>
		</div>

		<script type="text/javascript">
			var sc = '';
			var split = '';
			var total_K;		var state_K;		
			var Temp_K;			var table_K;

			$(function(){
				document.getElementById('scan_junior_spv').disabled = true;
				$('#save_kuraire').linkbutton('disable');
				document.getElementById('scan_kuraire').focus();
				content_list();
			});

			function content_list(){
				$('#dg').datagrid( {
					url: 'list_in_kuraire.php'
				});
			}

			function scan_kuraire(event) {
				if(event.keyCode == 13 || event.which == 13){
		            sc = document.getElementById('scan_kuraire').value;
		            document.getElementById('scan_kuraire').focus();
		            document.getElementById('scan_kuraire').value = '';
		            proses();
		        }
	        }

	        function proses() {
	        	split = sc.split(" ");

	        	if (sc == ''){
		    		$.messager.show({
						title:'KANBAN KURAIRE',
						msg:'SCAN KANBAN TIDAK BOLEH KOSONG',
						timeout:4000,
						showType:'show',
						style:{
							middle:document.body.scrollTop+document.documentElement.scrollTop,
						}
					});
		    	}else{
		    		$.messager.progress({
					    msg:'Checking data...'
					});

			    	$.ajax({
			    		type: 'GET',
						url: 'cek_mps.php?id_kanban='+split[0],
						success: function(data){
							console.log(data);
							total_K = data.total;
							state_K = data.state;

							var src = data.rows[0].WO_NO;
			    			var search = src.search("TEMP");

			    			if(parseFloat(search) < 0) {
			    				table_K = 'production_income';
			    			}else{
			    				table_K = 'ztb_production_income';
			    			}

			   	 			console.log(search+'-'+table_K);
							
							if(data.total == 1 && data.state == 'X') {
								$.messager.progress('close');
								$('#wo_no').textbox('setValue', data.rows[0].WO_NO);
								$('#item_no').textbox('setValue', data.rows[0].ITEM_NO);
								$('#item_name').textbox('setValue', data.rows[0].BRAND);
								$('#qty').textbox('setValue', data.rows[0].QTY_PROD);
								$('#pallet_a').textbox('setValue', data.rows[0].PLT_NO);
								$('#pallet_z').textbox('setValue', data.rows[0].PLT_TOT);
								$('#type_lr').textbox('setValue', data.rows[0].TYPE_ITEM);
								$('#grade_lr').textbox('setValue', data.rows[0].GRADE);
								$('#tgl').textbox('setValue', data.rows[0].DATE_PROD);
								$('#datecode').textbox('setValue', data.rows[0].DATE_CODE);
								$('#save_kuraire').linkbutton('enable');
							}else if(data.state == 'Y') {
								$.messager.progress('close');
								$('#wo_no').textbox('setValue', data.rows[0].WO_NO);
								$('#item_no').textbox('setValue', data.rows[0].ITEM_NO);
								$('#item_name').textbox('setValue', data.rows[0].BRAND);
								$('#qty').textbox('setValue', data.rows[0].QTY_PROD);
								$('#pallet_a').textbox('setValue', data.rows[0].PLT_NO);
								$('#pallet_z').textbox('setValue', data.rows[0].PLT_TOT);
								$('#type_lr').textbox('setValue', data.rows[0].TYPE_ITEM);
								$('#grade_lr').textbox('setValue', data.rows[0].GRADE);
								$('#tgl').textbox('setValue', data.rows[0].DATE_PROD);
								$('#datecode').textbox('setValue', data.rows[0].DATE_CODE);
								document.getElementById('scan_junior_spv').disabled = false;
								document.getElementById('scan_junior_spv').focus();
								$('#save_kuraire').linkbutton('enable');
							}else{
								$.messager.progress('close');

								$.messager.show({
									title: 'ERROR',
									msg: data.state,
									timeout: 4000,
									showType: 'show',
									style:{
										middle:document.body.scrollTop+document.documentElement.scrollTop,
									}
								});
							}
						}
			    	});
		        }
		    }

		    function save_kuraire() {
		    	console.log(total_K+'-'+state_K+'-'+table_K);
		    	var dataRows = [];
		    	dataRows.push({
					k_table: table_K,
					k_id: split[0],
					k_wo: $('#wo_no').textbox('getValue')
				});

				var myJSON=JSON.stringify(dataRows);
				var str_unescape=unescape(myJSON);

				console.log(str_unescape);

				// $.post('kuraire_save.php',{
				// 	data: unescape(str_unescape)
				// }).done(function(res){
				// 	if(res == '"success"'){
				// 		$('#dg').datagrid('reload');
				// 		$.messager.alert('INFORMATION','Kuraire Data Success..!!','info');
				// 	}else{
				// 		$.messager.alert('ERROR',res,'warning');
				// 	}
				// });
		    }
		</script>
 	</body>
	</html>
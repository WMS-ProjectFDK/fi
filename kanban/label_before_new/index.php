<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>KANBAN LABEL SYSTEM [IN]</title>
<link rel="icon" type="../../image/png" href="../../favicon.png">
<link rel="icon" type="../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
<script type="tex [IN]t/javascript" src="../../js/jquery.edatagrid.js"></script>
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
					   title: 'OUT LABEL KANBAN SYSTEM',
					   collapsible:false,
					   modal: true,
					   tools: '#tt',
					   footer: '#footer'"
		 style="height:100%;padding:15px;">

		<div id='dlg_labelline' class="easyui-dialog" style="width:400px;height:150px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	    	<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="label" id="line3" onclick="MeClick(this);" value="line3"/>LR03</span>
			<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="label" id="line6" onclick="MeClick(this);" value="line6"/>LR6</span>
			<hr>
			<span id='span_ln1' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln1" value="ln1"/>#1</span>
			<span id='span_ln2' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln2" value="ln2"/>#2</span>
			<span id='span_ln3' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln3" value="ln3"/>#3</span>
			<span id='span_ln4' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln4" value="ln4"/>#4</span>
			<span id='span_ln5' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln5" value="ln5"/>#5</span>
			<span id='span_ln6' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln6" value="ln6"/>#6</span>
			<hr>
			<div align="center">
				<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="add" onclick="save()"><i class="fa fa-plus" aria-hidden="true"></i> SIMPAN</a>
			</div>
    	</div>

	    <div id='dlg_kupasan' class="easyui-dialog" style="width:400px;height:auto;padding:5px 5px;" closed="true" data-options="modal:true">
	    	<div class="fitem">
				<span style="width:100px;display:inline-block;">QTY</span>
				<input style="width:150px;" name="qty_kupas" id="qty_kupas" class="easyui-numberbox" data-options="groupSeparator:','"/>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">GRADE</span>
				<select style="width:150px;" name="cmb_cell_type_kupas" id="cmb_cell_type_kupas" class="easyui-combobox" >
					<option value=""></option>
 					<option selected="selected">C01</option>
 					<option>C01NC</option>
 					<option>G06</option>
 					<option>G06NC</option>
 					<option>G07</option>
 					<option>G07NC</option>
 					<option>G08</option>
 					<option>G08NC</option>
 					<option>G08E</option>
				</select>
			</div>
			<hr>
	    	<span style="width:120px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="labelk" id="line3k" onclick="MeClickK(this);" value="line3k"/>LR03</span>
			<span style="width:120px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="labelk" id="line6k" onclick="MeClickK(this);" value="line6k"/>LR6</span>
			<hr>
			<span id='span_ln1k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln1k" value="ln1k"/>#1</span>
			<span id='span_ln2k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln2k" value="ln2k"/>#2</span>
			<span id='span_ln3k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln3k" value="ln3k"/>#3</span>
			<span id='span_ln4k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln4k" value="ln4k"/>#4</span>
			<span id='span_ln5k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln5k" value="ln5k"/>#5</span>
			<span id='span_ln6k' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lblk" id="ln6k" value="ln6k"/>#6</span>
			<hr>
			<div align="center">
				<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="add" onclick="saveKupas()"><i class="fa fa-plus" aria-hidden="true"></i> SAVE</a>
			</div>
	    </div>

		<fieldset style="float: left;margin-left:2px;border-radius:4px;width: 45%;height: 530px;"><legend><span class="style3"><strong>INPUT LABEL TRANSAKSI</strong></span></legend>
			<div class="fitem" style="padding: 5px;" align="center">
				<span style="width:300px;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN ASSEMBLING</span>
			</div>
			<div class="fitem" style="padding: 5px;" align="center">
				<input style="width:550px; height: 40px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
					onkeypress="scan_lbl(event)" name="scan_lbl" id="scan_lbl" type="text" 
					autofocus="autofocus" />
			</div>
			<hr>
			<div class="fitem" style="padding: 5px;" align="center">
				<a id="savebtn_kupasan" class="easyui-linkbutton c2" onClick="kupasan()" style="width:180px; height: 41px;display:inline-block;">
					<i class="fa fa-save" aria-hidden="true"></i> INPUT HASIL KUPASAN</a>
				<a id="savebtn_Plan" class="easyui-linkbutton c5" onClick="cancel()" style="width:180px; height: 41px;display:inline-block;">
					<i class="fa fa-remove" aria-hidden="true"></i> CANCEL</a>
			</div>
			<hr>
			<span style="display:inline-block;font-weight: bold;">LAPORAN : </span>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true" >LAP 1</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">LAP 2</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">LAP 3</a>
			<hr>
			<table id="dg_kupas" class="easyui-datagrid" toolbar="#toolbar_kupas" data-options="method: 'get', fitColumns: true, width:'100%', height: '58%', singleSelect: true">
		        <thead>
		            <tr>
						<th halign="center" data-options="field:'LABELLINE', width: 60, align:'center' ">LINE LABEL</th>
						<th halign="center" data-options="field:'SHIFT', width: 40, align:'center' ">SHIFT</th>
						<th halign="center" data-options="field:'GRADE', width: 50">GRADE</th>
						<th halign="center" data-options="field:'QTY', width: 80, align:'right'">QTY</th>
						<th halign="center" data-options="field:'UPTO_DATE', width: 80">WAKTU SCAN</th>
						<th halign="center" data-options="field:'LOTDATE', width: 80, align:'center'">KETERANGAN</th>
						<th halign="center" data-options="field:'ROW_ID', width: 80, align:'center', hidden: true"></th>
						<th halign="center" data-options="field:'ACTION', width: 80, align:'right'">SELESAI</th>
		            </tr>
		        </thead>
		    </table>
		    <div id="toolbar_kupas">
		    	<span style="float:left;padding:5px 5px; display: inline-block;">
		    		<a href="javascript:void(0)" iconCls="icon-cancel" class="easyui-linkbutton" onclick="destroy_kupas()">KELUAR ANTRIAN</a>
		    	</span>
				<span style="float: right; padding:5px 5px;">
					<a href="javascript:void(0)" iconCls="icon-reload" class="easyui-linkbutton" title="reload table list" onclick="reload_kupas()" style="margin-left: : 900px;"></a>
				</span>
			</div>
		</fieldset>

		<fieldset style="position:absolute;margin-left:47%;border-radius:4px;width: 49%;height: 530px;"><legend><span class="style3"><strong>LIST OF IN LABEL BATTERY</strong></span></legend>
			<table id="dg" class="easyui-datagrid" toolbar="#toolbar_list" data-options="method: 'get', fitColumns: true, height: '97%', singleSelect: true">
		        <thead>
		            <tr>
						<th halign="center" data-options="field:'ID_PRINT', width: 70">ID PRINT</th>
						<th halign="center" data-options="field:'ASSY_LINE', width: 75">LINE<br/>ASSY</th>
						<th halign="center" data-options="field:'PALLET', width: 45, align:'center'">PALLET</th>
						<th halign="center" data-options="field:'CELL_TYPE', width: 45">GRADE</th>
						<th halign="center" data-options="field:'QTY', width: 70, align:'right'">QTY</th>
						<th halign="center" data-options="field:'LOT_NUMBER', width: 80, align:'center'">LOT<br/>NUMBER</th>
						<th halign="center" data-options="field:'UPTO_DATE', width: 90">WAKTU SCAN</th>
						<th halign="center" data-options="field:'STS', width: 90">KETERANGAN</th>
						<th halign="center" data-options="field:'ACTION', width: 100" align="right">SELESAI</th>
		            </tr>
		        </thead>
		    </table>
		    <div id="toolbar_list">
				<span style="float:left;padding:5px 5px; display: inline-block;">
					<a href="javascript:void(0)" iconCls="icon-cancel" class="easyui-linkbutton" onclick="destroy_view()">KELUAR ANTRIAN</a>
				</span>
				<span style="float: right; padding:5px 5px;">
					<a href="javascript:void(0)" iconCls="icon-reload" class="easyui-linkbutton" title="reload table list" onclick="reload()"></a>
				</span>
			</div>
		</fieldset>
		<br/>
		<div style="clear:both;"></div>
		<div id="footer" style="padding:5px;" align="center">
	        <small><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></small>
	    </div>

		<div id='dlg_keluarAntrian' class="easyui-dialog" style="width:350px;height:160px;padding:5px 5px;" closed="true" data-options="modal:true">
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:auto; height:auto; padding: 5px;">
				<div class="fitem" hidden="true">
					<span style="width:120px;display:inline-block;">ID</span>
					<input style="width:150px;" name="id_keluarAntrian" id="id_keluarAntrian" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">ASSY QTY</span>
					<input style="width:150px;" name="qty_assy" id="qty_assy" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">IN MACHINE BOX</span>
					<input style="width:50px;" name="qty_masuk_box" id="qty_masuk_box" class="easyui-textbox" placeholder="box" value="0"/>
					<div hidden="true"><input style="width:95px;" name="qty_masuk_pcs" id="qty_masuk_pcs" class="easyui-textbox" disabled=""/></div>
				</div>
				<hr>
				<div align="center">
					<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="add" onclick="destroy_antrian()"><i class="fa fa-plus" aria-hidden="true"></i> SAVE</a>
				</div>
			</fieldset>
		</div>

		<div id='dlg_keluarAntrian_kupas' class="easyui-dialog" style="width:350px;height:160px;padding:5px 5px;" closed="true" data-options="modal:true">
			<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:auto; height:auto; padding: 5px;">
				<div class="fitem" hidden="true"><!-- -->
					<span style="width:120px;display:inline-block;">ID</span>
					<input style="width:150px;" name="id_keluarAntrian_kupas" id="id_keluarAntrian_kupas" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">KUPASAN QTY</span>
					<input style="width:150px;" name="qty_assy_kupas" id="qty_assy_kupas" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:120px;display:inline-block;">IN MACHINE</span>
					<input style="width:50px;" name="qty_masuk_kupas" id="qty_masuk_kupas" class="easyui-textbox" placeholder="box" value="0"/> 
					<span style="width:120px;display:inline-block;color: red">pcs (not box)</span>
				</div>
				<hr>
				<div align="center">
					<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" id="add" onclick="destroy_antrian_kupas()"><i class="fa fa-plus" aria-hidden="true"></i> SAVE</a>
				</div>
			</fieldset>
		</div>
	</div>

	<div id="tt" style="height: 20px;">
		<a href="#"  class="easyui-linkbutton" plain="true" iconCls="icon-help"></a>
	</div>

	<script type="text/javascript">
		var sc = '';
		var lb1 = '';
	    var lb2 = 0;
	    var lb1k = '';
	    var lb2k = 0;
	    var shift = 0;
	    var split = '';

		function MeClick(label){
			if(label.value == 'line3'){
				document.getElementById('span_ln3').style.display = 'none';
				document.getElementById('span_ln4').style.display = 'none';
				document.getElementById('span_ln5').style.display = 'none';
				document.getElementById('span_ln6').style.display = 'none';
			}else{
				document.getElementById('span_ln3').style.display = 'inline';
				document.getElementById('span_ln4').style.display = 'inline';
				document.getElementById('span_ln5').style.display = 'inline';
				document.getElementById('span_ln6').style.display = 'inline';
			}
		}

		function reload_kupas(){
			content_list_kupas();
		}

		function reload(){
			content_list();
		}

		function content_list(){
			$('#dg').datagrid( {
				url: 'list_in_label.php'
			});

			$('#dg').datagrid('enableFilter');
		}

		function content_list_kupas(){
			$('#dg_kupas').datagrid( {
				url: 'list_in_label_kupas.php'
			});

			$('#dg_kupas').datagrid('enableFilter');
		}

		$(function(){
			content_list();
			content_list_kupas();

			//var myVar1 = setInterval(function(){content_list()},10000);
			//var myVar2 = setInterval(function(){content_list_kupas()},10000);
		})

		function cancel(){
			document.getElementById('scan_lbl').focus();
            document.getElementById('scan_lbl').value = ''
		}

		function scan_lbl(event){
	        if(event.keyCode == 13 || event.which == 13){
	            sc = document.getElementById('scan_lbl').value;
	            document.getElementById('scan_lbl').focus();
	            document.getElementById('scan_lbl').value = '';
	            proses();
	        }
	    }

	    function proses(){
	    	split = sc.split(",");

	    	if (sc == ''){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'SCAN KANBAN TIDAK BOLEH KOSONG',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
	    	}else{
		    	$.ajax({
		    		type: 'GET',
					url: 'cek_status.php?id_print='+split[8],
					success: function(data){
						if(data[0].kode=='BEFORE LABEL'){
							$('#dlg_labelline').dialog('open').dialog('setTitle','PILIH LINE LABEL');
				            document.getElementById('line3').checked = false;
				            document.getElementById('line6').checked = false;
				            
				            document.getElementById('ln1').checked = false;
				            document.getElementById('ln2').checked = false;
				            document.getElementById('ln3').checked = false;
				            document.getElementById('ln4').checked = false;
				            document.getElementById('ln5').checked = false;
				            document.getElementById('ln6').checked = false;
				            
				            document.getElementById('span_ln3').style.display = 'inline';
							document.getElementById('span_ln4').style.display = 'inline';
							document.getElementById('span_ln5').style.display = 'inline';
							document.getElementById('span_ln6').style.display = 'inline';
							document.getElementById('span_ln3').style.display = 'inline';
							document.getElementById('span_ln4').style.display = 'inline';
							document.getElementById('span_ln5').style.display = 'inline';
							document.getElementById('span_ln6').style.display = 'inline';
						}else if(data[0].kode=='IN'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PALLET BELUM DI SCAN HEATING ROOM',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else if(data[0].kode=='OUT'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PALLET MASIH DI HEATING ROOM',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else if(data[0].kode=='EXISTS'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PALLET SUDAH DI SCAN SEBELUMNYA, SILAHKAN LANJUTKAN PROSES',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else if(data[0].kode=='ASSEMBLY'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PROSES ASSEMBLING BELUM SELESAI',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}
					}
		    	});
	    	}
	    }

	    function save(){
	    	if(document.getElementById('line3').checked == true){
	    		lb1 = 'LR03-';
	    	}else if(document.getElementById('line6').checked == true){
	    		lb1 = 'LR6-';
	    	}

			if(document.getElementById('ln1').checked == true){
	    		lb2 = 1;
	    	}else if(document.getElementById('ln2').checked == true){
	    		lb2 = 2;
	    	}else if(document.getElementById('ln3').checked == true){
	    		lb2 = 3;
	    	}else if(document.getElementById('ln4').checked == true){
	    		lb2 = 4;
	    	}else if(document.getElementById('ln5').checked == true){
	    		lb2 = 5;
	    	}else if(document.getElementById('ln6').checked == true){
	    		lb2 = 6;
	    	}

	    	var d = new Date();
		  	var n = parseInt(d.getHours());

		  	if (n >= 7 && n < 15){
		  		shift = 1;
		  	}else if (n >= 15 && n < 23){
		  		shift = 2;
		  	}else{
		  		shift = 3;
		  	}

		  	if (lb1 == '' || lb2 == 0){
	    		$.messager.alert('Warning','Please select Label line','warning');
	    	}else{
				$.post('save_b_label.php',{
		    		id_prin: split[8],
					id_plan: split[7],
					lblLine: lb1+lb2,
					shift: shift
		    	},function(result){
					if(result.successMsg == 'BEFORE LABEL'){
						$.messager.show({
							title:'KANBAN LABEL',
							msg:'PROSES LABEL BERHASIL',
							timeout:4000,
							showType:'show',
							style:{
								middle:document.body.scrollTop+document.documentElement.scrollTop,
							}
						});
						document.getElementById('scan_lbl').focus();
			    		content_list();
			            $('#dlg_labelline').dialog('close');
					}else{
	                    $.messager.show({
							title:'KANBAN LABEL',
							msg:result.errorMsg,
							timeout:4000,
							showType:'show',
							style:{
								middle:document.body.scrollTop+document.documentElement.scrollTop,
							}
						});
	                }
				},'json');
	        }
	    }


	    function MeClickK(labelk){
			if(labelk.value == 'line3k'){
				document.getElementById('span_ln3k').style.display = 'none';
				document.getElementById('span_ln4k').style.display = 'none';
				document.getElementById('span_ln5k').style.display = 'none';
				document.getElementById('span_ln6k').style.display = 'none';
			}else{
				document.getElementById('span_ln3k').style.display = 'inline';
				document.getElementById('span_ln4k').style.display = 'inline';
				document.getElementById('span_ln5k').style.display = 'inline';
				document.getElementById('span_ln6k').style.display = 'inline';
			}
		}

	    function kupasan(){
	    	$('#dlg_kupasan').dialog('open').dialog('setTitle','INPUT HASIL KUPASAN');
	    	$('#qty_kupas').numberbox('clear').numberbox('textbox').focus();
	    }

	    function saveKupas(){
	    	if(document.getElementById('line3k').checked == true){
	    		lb1k = 'LR03-';
	    	}else if(document.getElementById('line6k').checked == true){
	    		lb1k = 'LR6-';
	    	}

			if(document.getElementById('ln1k').checked == true){
	    		lb2k = 1;
	    	}else if(document.getElementById('ln2k').checked == true){
	    		lb2k = 2;
	    	}else if(document.getElementById('ln3k').checked == true){
	    		lb2k = 3;
	    	}else if(document.getElementById('ln4k').checked == true){
	    		lb2k = 4;
	    	}else if(document.getElementById('ln5k').checked == true){
	    		lb2k = 5;
	    	}else if(document.getElementById('ln6k').checked == true){
	    		lb2k = 6;
	    	}

	    	var d = new Date();
		  	var n = parseInt(d.getHours());

		  	if (n >= 7 && n < 15){
		  		shift = 1;
		  	}else if (n >= 15 && n < 23){
		  		shift = 2;
		  	}else{
		  		shift = 3;
		  	}

		  	var q = $('#qty_kupas').numberbox('getValue');
		  	var cell = $('#cmb_cell_type_kupas').combobox('getText');

		  	if (q <= 0){
	    		$.messager.alert('WARNING','PLEASE INPUT QTY...','warning');
		  	}else if (cell == ''){
	    		$.messager.alert('WARNING','<b>PLEASE SELECT GRADE BATTERY...</b>','warning'); 
		  	}else if (lb1k == '' || lb2k == 0){
	    		$.messager.alert('WARNING','<b>PLEASE SELECT LABEL LINE...</b>','warning'); 
	    	}else{
				$.post('save_b_label_kupas.php',{
		    		id_prin: 0,
					id_plan: 'KUPASAN',
					lblLine: lb1k+lb2k,
					shift: shift,
					qty: q,
					celltype: cell
		    	},function(result){
					if(result.successMsg == 'BEFORE LABEL'){
						$.messager.show({
							title:'KANBAN LABEL',
							msg:'INPUT KUPASAN BERHASIL',
							timeout:4000,
							showType:'show',
							style:{
								middle:document.body.scrollTop+document.documentElement.scrollTop,
							}
						});
						document.getElementById('scan_lbl').focus();
			    		content_list_kupas();
			            $('#dlg_kupasan').dialog('close');
					}else{
	                    $.messager.show({
							title:'KANBAN LABEL',
							msg:result.errorMsg,
							timeout:4000,
							showType:'show',
							style:{
								middle:document.body.scrollTop+document.documentElement.scrollTop,
							}
						});
	                }
				},'json');
	        }
	    }

	    function finish(a){
	    	$.messager.confirm('Confirm','Apakah ini Sudah box terakhir?<br>Apakah pallet ini akan diselesaikan sekarang?',function(r){
				if(r){
					$.ajax({
			    		type: 'GET',
						url: 'selesaikan.php?id_print='+a+'&pcs=0&sts=selesaikan',
						success: function(data){
							if(data[0].kode=='success'){
								$.messager.show({
									title:'KANBAN LABEL',
									msg:'PROSES SELESAIKAN UNTUK PALLET INI BERHASIL',
									timeout:4000,
									showType:'show',
									style:{
										middle:document.body.scrollTop+document.documentElement.scrollTop,
									}
								});
								content_list();
								$('#dg').datagrid('reload');
							}else{
								$.messager.show({
									title:'ERROR',
									msg:'PROSES SELESAI GAGAL',
									timeout:4000,
									showType:'show',
									style:{
										middle:document.body.scrollTop+document.documentElement.scrollTop,
									}
								});
							}
						}
					});
				}
			});
	    }

	    function destroy_view(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				if(row.ACTION == '<span style="color:blue;font-size:11px;"><b>SUDAH<br>SELESAI</b></span>'){
					$.messager.show({
						title:'KANBAN LABEL',
						msg:'DATA INI SUDAH DISELESAIKAN',
						timeout:2000,
						showType:'show',
						style:{
							middle:document.body.scrollTop+document.documentElement.scrollTop,
						}
					});
				}else{
					$('#dlg_keluarAntrian').dialog('open').dialog('setTitle','DATA KELUAR ANTRIAN');
					$('#id_keluarAntrian').textbox('setValue',row.ID_PRINT);
					$('#qty_assy').textbox('setValue',row.QTY);
					$('#qty_masuk_pcs').textbox('setValue',row.QTY_BOX+'/'+row.QTY_BOX_PALLET);
				}
			}else{
				$.messager.show({title: 'KANBAN LABEL',msg:'Data Not select'});
			}
		}

		function destroy_antrian(){
			var id  = $('#id_keluarAntrian').textbox('getValue');
			var act = $('#qty_assy').textbox('getValue').replace(/,/g,'');
			var bx = parseInt($('#qty_masuk_box').textbox('getValue'));
			var pc = $('#qty_masuk_pcs').textbox('getValue');
			var splitPc = pc.split("/");
			var bxAct = parseInt(act/splitPc[0]);
			var pxAct = parseInt(bx*splitPc[0]);
			var sisa = act - pxAct;
			

			if(bx > bxAct){
				$.messager.show({
					title:'KANBAN LABEL',
					msg:'JUMLAH BOX MELEBIHI JUMLAH BOX ACTUAL',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
			}else{
				$.ajax({
		    		type: 'GET',
					url: 'selesaikan.php?id_print='+id+'&pcs='+pxAct+'&sts=keluarAntrian',
					success: function(data){
						if(data[0].kode=='success'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PROSES KELUAR ANTRIAN BERHASIL',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else{
							$.messager.show({
								title:'ERROR',
								msg:'PROSES KELUAR ANTRIAN GAGAL',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}
					}
				});
				content_list();
				$('#dlg_keluarAntrian').dialog('close');
			}
		}

		function finish_kupas(a){
			$.messager.confirm('Confirm','Apakah ini Sudah box terakhir?<br>Apakah pallet ini akan diselesaikan sekarang?',function(r){
				if(r){
					$.ajax({
			    		type: 'GET',
						url: 'selesaikan_kupas.php?id='+a+'&pcs=0&sts=selesaikan',
						success: function(data){
							if(data[0].kode=='success'){
								$.messager.show({
									title:'KANBAN LABEL',
									msg:'PROSES SELESAIKAN UNTUK PALLET INI BERHASIL',
									timeout:4000,
									showType:'show',
									style:{
										middle:document.body.scrollTop+document.documentElement.scrollTop,
									}
								});
								content_list_kupas();
								$('#dg_kupas').datagrid('reload');
							}else{
								$.messager.show({
									title:'ERROR',
									msg:'PROSES SELESAI GAGAL',
									timeout:4000,
									showType:'show',
									style:{
										middle:document.body.scrollTop+document.documentElement.scrollTop,
									}
								});
							}
						}
					});
				}
			});			
		}

		function destroy_kupas(){
			var row = $('#dg_kupas').datagrid('getSelected');	
			if (row){
				if (row.ACTION == '<span style="color:blue;font-size:11px;"><b>SUDAH<br>SELESAI</b></span>') {
					$.messager.show({
						title:'KANBAN LABEL',
						msg:'DATA INI SUDAH DISELESAIKAN',
						timeout:2000,
						showType:'show',
						style:{
							middle:document.body.scrollTop+document.documentElement.scrollTop,
						}
					});
				}else{	
					var rowID = row.ID;
					$('#dlg_keluarAntrian_kupas').dialog('open').dialog('setTitle','DATA KELUAR ANTRIAN KUPASAN');
					$('#id_keluarAntrian_kupas').textbox('setValue',row.ID);
					$('#qty_assy_kupas').textbox('setValue',row.QTY);
				}
			}else{
				$.messager.show({title: 'KANBAN LABEL',msg:'Data Not select'});
			}
		}

		function destroy_antrian_kupas(){
			var id  = $('#id_keluarAntrian_kupas').textbox('getValue');
			var act = $('#qty_assy_kupas').textbox('getValue').replace(/,/g,'');
			var px = parseInt($('#qty_masuk_kupas').textbox('getValue'));
			var sisa = act - px;

			if(sisa < 0){
				$.messager.show({
					title:'KANBAN LABEL',
					msg:'JUMLAH PCS BATTERY MELEBIHI JUMLAH PCS ACTUAL',
					timeout:2000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
			}else{
				$.ajax({
		    		type: 'GET',
					url: 'selesaikan_kupas.php?id='+id+'&pcs='+px+'&sts=keluarAntrian',
					success: function(data){
						if(data[0].kode=='success'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PROSES KELUAR ANTRIAN BERHASIL',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
							content_list_kupas();
							$('#dlg_keluarAntrian_kupas').dialog('close');
						}else{
							$.messager.show({
								title:'ERROR',
								msg:'PROSES KELUAR ANTRIAN GAGAL',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}
					}
				});
			}
		}
	</script>
	</body>
</html>
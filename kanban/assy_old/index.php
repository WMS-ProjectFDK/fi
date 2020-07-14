<?php 
session_start();
if(isset($_SESSION['id_kanban'])){
	include("../../connect/conn.php");
	$id_kanban = $_SESSION['id_kanban'];
	$user_name = substr(strtoupper($_SESSION['name_kanban']),0,18);
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Assembly Kanban System</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="my.js"></script>

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
	.fitem{
		padding: 3px 0px;
	}
	</style>
    </head>
    <body>
		<div id="p" class="easyui-panel" title="ASSEMBLY KANBAN SYSTEM" data-options="tools:'#tt', footer:'#footer'"
			style="width:100%;height:auto;padding:5px;">
			<table id="dg_in" class="easyui-datagrid" toolbar="#toolbar_in" style="width:100%;height:auto;"></table>
			<div id="toolbar_in">
				<div style="width:580px; float:left;padding: 10px;" class="style3">
					<div class="fitem" style="padding: 5px;">
						<span style="width:100px;display:inline-block;font-size: 25px;font-weight: bold;">SCAN</span> 
						<input 
							style="width:450px; height: 41px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
							onkeypress="scan(event)" name="scn" id="scn" type="text"
							autofocus="autofocus"
						/>
					</div>
					<div class="fitem">
						<span style="width:105px;display:inline-block;font-weight: bold;"></span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Assy Line</span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Cell Type</span>
						<span style="width:80px;display:inline-block;font-weight: bold;">Pallet</span>
						<span style="width:120px;display:inline-block;font-weight: bold;">Tanggal Produksi</span>
					</div>
					<div class="fitem">
						<input style="width:105px;height: 30px;" name="id_no" id="id_no" class="easyui-textbox" disabled=""/>
						<input style="width:120px;height: 30px;" name="assy_line" id="assy_line" class="easyui-textbox" disabled=""/>
						<input style="width:120px; height:30px;" name="cell_type" id="cell_type" class="easyui-combobox" data-options="url:'../../forms/json/json_cell_type.json', method:'get', valueField:'cell_type', textField:'cell_type', panelHeight:'120px'" disabled=""/>

						<input style="width:80px;height: 30px;" name="pallet" id="pallet" class="easyui-textbox" disabled=""/>
						<input style="width:120px;height: 30px;" name="tanggal_produksi" id="tanggal_produksi" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'"/>
					</div>
					<div class="fitem">
						<span style="width:105px;display:inline-block;font-weight: bold;">Adh. painting</span>
						<span style="width:110px;display:inline-block;font-weight: bold;">CCA</span>
						<span style="width:110px;display:inline-block;font-weight: bold;">Separator</span>
						<span style="width:110px;display:inline-block;font-weight: bold;">Gel</span>
						<span style="width:110px;display:inline-block;font-weight: bold;">Electrolyte</span>
					</div>
					<div class="fitem">
						<input style="width:105px;height: 30px;" name="tgl_adh" id="tgl_adh" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'" value="<?php echo date('Y');?>" />
						<input style="width:110px;height: 30px;" name="tgl_cca" id="tgl_cca" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'" value="<?php echo date('Y');?>" />
						<input style="width:110px;height: 30px;" name="tgl_sep" id="tgl_sep" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'" value="<?php echo date('Y');?>" />
						<input style="width:110px;height: 30px;" name="tgl_gel" id="tgl_gel" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'" value="<?php echo date('Y');?>" />
						<input style="width:110px;height: 30px;" name="tgl_elektrolyte" id="tgl_elektrolyte" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser" value="<?php echo date('Y');?>" />
					</div>
				</div>

				<div style="position:absolute;margin-left:600px;border-radius:4px;width: 400px;height: 250px;" class="style3">
					<div class="fitem">
						<span style="width:340px;display:inline-block;font-size: 16px;font-weight: bold;"><?=$id_kanban.' - '.$user_name?></span>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY JALAN</span>
						<input style="width:100px;height: 25px;" name="qty_berjalan" id="qty_berjalan" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
						<input style="width:168px;height: 25px;" name="id_plan" id="id_plan" class="easyui-textbox" disabled=""/>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY/PALLET</span>
						<input style="width:100px;height: 25px;" name="qty_perpallet" id="qty_perpallet" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
						<span style="width:30px;display:inline-block;font-weight: bold;">ACT</span>
						<input style="width:130px;height: 20px;border-radius: 5px; font-size: 16px;" name="qty_act_perpallet" id="qty_act_perpallet" onkeypress="qty_act(event)"/>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;font-weight: bold;">QTY/BOX</span>
						<input style="width:100px;height: 25px;" name="qty_perbox" id="qty_perbox" class="easyui-numberbox" disabled="" data-options="min:0,precision:0,groupSeparator:','"/>
						<span style="width:30px;display:inline-block;font-weight: bold;">ACT</span>
						<input style="width:60px;height: 20px;border-radius: 5px;font-size: 16px;" name="qty_act_perbox" id="qty_act_perbox"
							onkeypress="qty_act_box(event)"/>
						<span style="display:inline-block;font-weight: bold;">@</span>
						<input style="width:50px;height: 25px;" name="qty_box" id="qty_box" class="easyui-numberbox" disabled="" data-options="min:0,precision:0,groupSeparator:','"/>
					</div>
					<div class="fitem">
						<span style="width:340px;display:inline-block;font-weight: bold;">Adh. Painting</span>
					</div>
					<div class="fitem">
						<input style="width:357px;height: 28px;" name="txt_adh_paint" id="txt_adh_paint" class="easyui-textbox" data-options="multiline:true"/>
					</div>
				</div>

				<div style="height: 180px;" >
					<div style="margin-left:960px;">
					   	<div class="fitem">
						   <a href="javascript:void(0)" id="mulai" class="easyui-linkbutton c2" onclick="start()" style="width:80px;height: 50px;font-weight: bold;" disabled="">MULAI</a>
						   <input style="width:190px;;height: 40px;font-size: 33px;" name="date_mulai" id="date_mulai" class="easyui-textbox" disabled=""/>
						</div>
						<div class="fitem">
						   <a href="javascript:void(0)" id="akhir" class="easyui-linkbutton c2" onclick="finish()" style="width:80px;height: 50px;font-weight: bold;" disabled="">SELESAI</a>
						   <input style="width:190px;;height: 40px;" name="date_akhir" id="date_akhir" class="easyui-textbox" disabled=""/>
						</div>
						<div class="fitem">
						<span style="width:340px;display:inline-block;font-weight: bold;">Separator</span>
					</div>
					<div class="fitem">
						<input style="width:275px;height: 37px;" name="txt_separator" id="txt_separator" class="easyui-textbox" data-options="multiline:true"/>
					</div>
					</div>
				</div>
			</div>
			
			<div class="fitem"></div>

			<table id="dg_ng" class="easyui-datagrid" toolbar="#toolbar_ng" singleSelect="true" style="width:100%;height:180px;"></table>
			<div id="toolbar_ng">
				<div class="fitem" style="padding: 5px;">
					<span style="width:80px; height:35px; display:inline-block;font-weight: bold;">Proses</span>
					<select style="width:200px; height:35px;" name="cmb_ng_proses" id="cmb_ng_proses" class="easyui-combobox" 
						data-options="url:'../../forms/json/json_ng_proses.php',method:'get',valueField:'ng_proses_id',textField:'ng_proses_name', panelHeight:'150px',
									  onSelect: function(rec){
									  	var link = '../../forms/json/json_ng_name.php?ng_pro='+rec.ng_proses_id;
									  	$('#cmb_ng').combobox('reload', link);
									  }
						"
					disabled="" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:80px; height:35px; display:inline-block;font-weight: bold;">Trouble</span>
					<select style="width:200px; height:35px;" name="cmb_ng" id="cmb_ng" class="easyui-combobox" 
						data-options="method:'get',valueField:'ng_id',textField:'ng_name', panelHeight:'150px'"
					disabled="" required=""></select>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:50px;display:inline-block;font-weight: bold;">Menit </span>
					<input style="width:100px;height:35px;" name="qty_ng" id="qty_ng" class="easyui-numberbox" data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
					<span style="width:50px;display:inline-block;"></span>
					<a href="javascript:void(0)" id="input_ng" class="easyui-linkbutton c2" onclick="in_ng()" plain="true" style="width:100px; height:35px;font-weight: bold;" disabled=""> INPUT</a>
					<a href="javascript:void(0)" id="viewer" class="easyui-linkbutton c2" onclick="viewer()" plain="true" style="width:150px; height:35px;font-weight: bold;"> LIHAT HASIL LAIN</a>
					<a href="javascript:void(0)" id="viewer" class="easyui-linkbutton c2" onclick="report()" plain="true" style="width:100px; height:35px;font-weight: bold;"> LAP. HARIAN</a>
				</div>
			</div>
		</div>
		<div id="tt">
	        <a href="javascript:void(0)" iconCls='icon-shutdown' class="easyui-linkbutton" onclick="logOut()"></a>
	    </div>

	    <div id='dlg' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons" data-options="modal:true">
		    <table id="dg_view" class="easyui-datagrid" toolbar="#toolbar_view" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_view" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Assy LIne</span>
			<input style="width:100px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" 
				data-options="url:'../../forms/json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line_2', panelHeight:'160px'" required="" 
			/>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_view_line()">SEARCH ITEM</a>
		</div>

		<div id='dlg_daily' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons" data-options="modal:true">
		    <table id="dg_daily" class="easyui-datagrid" toolbar="#toolbar_daily" style="width:100%; height:auto; border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_daily" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Assy LIne</span>
			<input style="width:100px;" name="cmb_Line_hasil" id="cmb_Line_hasil" class="easyui-combobox" 
				data-options="url:'../../forms/json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line_2', panelHeight:'160px'" required="" 
			/>
			<span style="width:50px;display:inline-block;"></span>
			<span style="width:80px;display:inline-block;">Tgl Scan</span>
			<input style="width:100px;" name="tgl_scn" id="tgl_scn" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser" value="<?php echo date('Y');?>" />
			<span style="width:20px;display:inline-block;"></span>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_hasil()">Search Hasil</a>
			<a href="javascript:void(0)" iconCls='icon-edit' class="easyui-linkbutton" onclick="edit_hasil()">Edit Hasil</a>
		</div>

		<div id="dlg_edit" class="easyui-dialog" style="width:600px;height:250px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Assy Line</span>
				<input style="width:150px;" name="cmb_Line_edit" id="cmb_Line_edit" class="easyui-combobox" 
				data-options="url:'../../forms/json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line_2'" disabled="true" />
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">Cell Type</span>
				<input style="width:150px;" name="cell_type_edit" id="cell_type_edit" class="easyui-combobox" data-options="url:'../../forms/json/json_cell_type.json', method:'get', valueField:'cell_type', textField:'cell_type'"/><!-- disabled="true" -->
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PALLET</span>
				<input style="width:150px;" name="palet_edit" id="palet_edit" class="easyui-textbox" disabled="true"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">TANGGAL PROD.</span>
				<input style="width:150px" name="tgl_prod_edit" id="tgl_prod_edit" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser" />
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">MULAI</span>
				<input style="width:150px;" name="txt_mulai" id="txt_mulai" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">AKHIR</span>
				<input style="width:150px;" name="txt_akhir" id="txt_akhir" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">QTY/PALLET</span>
				<input style="width:150px;" name="txt_akhir" id="txt_qtypallet" class="easyui-textbox" disabled="true"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">QTY/BOX</span>
				<input style="width:73px;" name="txt_qtybox" id="txt_qtybox" class="easyui-textbox"/>
				<input style="width:73px;" name="txt_perbox" id="txt_perbox" class="easyui-textbox" disabled="true"/>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">TANGGAL SCAN</span>
				<input style="width:150px" name="tgl_scan_edit" id="tgl_scan_edit" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser" />
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">ID</span>
				<input style="width:150px;" name="txt_id_edit" id="txt_id_edit" class="easyui-textbox" disabled="true" />
			</div>
		</div>

		<div id="dlg-buttons-edit">
			<a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
			<a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>	

		<div id='dlg_trouble' class="easyui-dialog" style="width:900px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons" data-options="modal:true">
		    <table id="dg_trouble" class="easyui-datagrid" toolbar="#toolbar_trouble" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_trouble" style="padding: 5px 5px;">
			<a href="javascript:void(0)" iconCls='icon-add' class="easyui-linkbutton" onclick="perbaikan()">PERBAIKAN</a>
		</div>

		<div id='dlg_perbaikan' class="easyui-dialog" style="width:400px;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-perbaikan" data-options="modal:true">
		    <div class="fitem">
		    	<span style="width:80px;display:inline-block;">NG No.</span>
		    	<input name="ng_no" id="ng_no" class="easyui-textbox" disabled=""/>
		    </div>
		    <input style="width:365px;height: 85px;" name="txt_perbaikan" id="txt_perbaikan" class="easyui-textbox" data-options="multiline:true"/>
		</div>

		<div id="dlg-buttons-perbaikan">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePerbaikan()" style="width:90px">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_perbaikan').dialog('close')" style="width:90px">Cancel</a>
		</div>

		<!-- FOR QC -->
		<div id='dlg_qc' class="easyui-dialog" style="width:580px;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-qc" data-options="modal:true">
		    <div class="fitem">
				<span style="width:80px;display:inline-block;">ASSY LINE</span>
				<input style="width:150px;" name="cmb_Line_qc" id="cmb_Line_qc" class="easyui-combobox" 
				data-options="url:'../../forms/json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line_2'" disabled="true" />
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">CELL TYPE</span>
				<input style="width:150px;" name="cell_type_qc" id="cell_type_qc" class="easyui-combobox" data-options="url:'../../forms/json/json_cell_type.json', method:'get', valueField:'cell_type', textField:'cell_type'" required="true"/>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">QTY PALLET/BOX</span>
				<input style="width:150px;" name="txt_qtypallet_qc" id="txt_qtypallet_qc" class="easyui-textbox" disabled="true"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">QTY SAMPLE</span>
				<input style="width:73px;" name="txt_qty_sample_qc" id="txt_qty_sample_qc" class="easyui-textbox" required="true"/>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">QTY/BOX</span>
				<input style="width:73px;" name="txt_perbox_qc" id="txt_perbox_qc" class="easyui-textbox" disabled="true"/>
				<span style="width:128px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">TANGGAL PROD.</span>
				<input style="width:150px" name="tgl_prod_qc" id="tgl_prod_qc" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser" />
			</div>
		</div>

		<div id="dlg-buttons-qc">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_qc()" style="width:90px">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_qc').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<!-- END FOR QC -->


		<div id="footer" style="padding:5px;" align="center">
	        <small><i>&copy; Copyright 2017, PT. FDK INDONESIA</i></small>
	    </div>
	<script type="text/javascript">	
		function myformatter(date){
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
		}
		function myparser(s){
			if (!s) return new Date();
				var ss = (s.split('-'));
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
				return new Date(y,m-1,d);
			} else {
				return new Date();
			}
		}

		var url_troule='';

		function viewer(){
			$('#dlg').dialog('open').dialog('setTitle','VIEW RESULT OTHER LINE');
			$('#dg_view').datagrid({
				fitColumns: true,
				columns:[[
					{field:'WORKER_ID1',title:'ID USER',width:140,halign:'center',hidden:true},
					{field:'NAME',title:'USER',width:140,halign:'center'},
	                {field:'ASSY_LINE',title:'ASSY<br/>LINE',width:80,halign:'center', align:'center'},
	                {field:'CELL_TYPE',title:'CELL<br/>TYPE',width:80,halign:'center', align:'center'},
	                {field:'PALLET',title:'PALLET',width:70,halign:'center', align:'center'},
	                {field:'TANGGAL_PRODUKSI',title:'TANGGAL<br/>PRODUKSI',width:150,halign:'center', align:'center'},
	               	{field:'QTY_ACT_PERPALLET',title:'QTY/PALLET',width:100,halign:'center', align:'right'},
	               	{field:'QTY_ACT_PERBOX',title:'QTY/BOX',width:100,halign:'center', align:'right'},
	               	{field:'START_DATE',title:'MULAI',width:150,halign:'center'},
	               	{field:'END_DATE',title:'AKHIR',width:150,halign:'center'},
	               	{field:'VW_TROUBLE',title:'TROUBLE',width:70,halign:'center', align:'center'}
	            ]],
	            onClickRow:function(id,row){
	            	$('#dg_trouble').datagrid('load',{
				    	worker: row.WORKER_ID1,
				    	assy_line: row.ASSY_LINE.replace('#','-'),
				    	cell_type: row.CELL_TYPE,
				    	pallet: row.PALLET,
				    	tgl_prod: row.TANGGAL_PRODUKSI
				    });

	            	$('#dlg_trouble').dialog('open').dialog('setTitle','VIEW TROBLE');
					$('#dg_trouble').datagrid({
						url : 'view_trouble.php',
						fitColumns: true,
						columns:[[
			                {field:'ASSY_LINE',title:'ASSY<br/>LINE',width:80,halign:'center', align:'center'},
			                {field:'CELL_TYPE',title:'CELL<br/>TYPE',width:80,halign:'center', align:'center'},
			                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
			                {field:'TANGGAL_PRODUKSI',title:'TANGGAL<br/>PRODUKSI',width:100,halign:'center', align:'center'},
			                {field:'NG_NAME_PROSES',title:'PROSES',width:150,halign:'center'},
			                {field:'NG_NAME',title:'TROBLE',width:150,halign:'center'},
			                {field:'NG_QTY',title:'TIME<br/>(MINUTE)',width:100,halign:'center', align:'center'},
			                {field:'NG_NO',title:'NO.',width:50,hidden: true},
			                {field:'PERBAIKAN',title:'SERVICE',width:150,halign:'center'},

			            ]]
			        });
				}
	        });
		}

		function perbaikan(){
			var row = $('#dg_trouble').datagrid('getSelected');
			if(row){
				$('#dlg_perbaikan').dialog('open').dialog('setTitle','PERBAIKAN '+row.NG_NAME);
				$('#txt_perbaikan').textbox('textbox').focus();
				$('#ng_no').textbox('setValue',row.NG_NO);
				$('#txt_perbaikan').textbox('setValue',row.PERBAIKAN);
			}else{
				$.messager.alert('INFORMATION','DATA BELUM DIPILIH..!!','info');
			}
		}

		function savePerbaikan(){
			$.post('perbaikan_save.php',{
				perbaikan_no: $('#ng_no').textbox('getValue'),
				perbaikan_commenet: $('#txt_perbaikan').textbox('getValue')
			}).done(function(res){
				//alert(res);
				$.messager.alert('INFORMATION','Insert Perbaikan Success..!!','info');
				$('#dlg_perbaikan').dialog('close');
				$('#dg_trouble').datagrid('reload');
				$('#dg_ng').datagrid('reload');
			});
		}

		function search_view_line(){
			var ln = $('#cmb_Line').combobox('getValue');
			if(ln == ''){
				$.messager.alert('INFORMATION','FIELD TIDAK BOLEH KOSONG..!!','info');
			}else{
				$('#dg_view').datagrid('load',{line: ln, st: 'R'});
				$('#dg_view').datagrid({url: 'result_line.php',});
				$('#cmb_Line').combobox('setValue','');
			}
		}

		function report(){
			$('#dlg_daily').dialog('open').dialog('setTitle','VIEW LAPORAN HARIAN ASSEMBLING');
			$('#cmb_Line_hasil').combobox('setValue','');
			$('#tgl_scn').datebox('setValue','<? echo date('Y'); ?>');
			$('#dg_daily').datagrid('load',{line: '', tgl: '', st: ''});
			$('#dg_daily').datagrid({
				fitColumns: true,
				showFooter: true,
				columns:[[
					{field:'ID',title:'ID',width:100,halign:'center', align:'center', hidden:true}, 
					{field:'WORKER_ID1',title:'ID USER',width:140,halign:'center', hidden:true},
					{field:'NAME',title:'USER',width:140,halign:'center'},
					{field:'ID_PLAN',title:'ID<br/>PLAN',width:120,halign:'center'},
	                {field:'ASSY_LINE',title:'ASSY<br/>LINE',width:80,halign:'center', align:'center'},
	                {field:'CELL_TYPE',title:'CELL<br/>TYPE',width:80,halign:'center', align:'center'},
	                {field:'PALLET',title:'PALLET',width:70,halign:'center', align:'center'},
	                {field:'TANGGAL_PRODUKSI',title:'TANGGAL<br/>PRODUKSI',width:100,halign:'center', align:'center'},
	                {field:'TGL_PROD', hidden: true},
	               	{field:'QTY_ACT_PERPALLET',title:'QTY<br/>/PALLET',width:100,halign:'center', align:'right'},
	               	{field:'QTY_ACT_PERBOX',title:'QTY<br/>/BOX',width:100,halign:'center', align:'right'},
	               	{field:'START_DATE',title:'MULAI',width:150,halign:'center'},
	               	{field:'END_DATE',title:'AKHIR',width:150,halign:'center'},
	               	{field:'TANGGAL_ACTUAL',title:'TANGGAL<br/>SCAN',width:100,halign:'center', align:'center'},
	               	{field:'TGL_ACT', hidden: true}
	            ]]
	        })
		}

		function search_hasil(){
			var ln_h = $('#cmb_Line_hasil').combobox('getValue');
			var tg_h = $('#tgl_scn').datebox('getValue');
			if(ln_h == ''){
				$.messager.alert('INFORMATION','FIELD TIDAK BOLEH KOSONG..!!','info');
			}else{
				$('#dg_daily').datagrid('load',{line: ln_h, tgl: tg_h, st: 'H'});
				$('#dg_daily').datagrid({url: 'result_line.php',});
				$('#cmb_Line_hasil').combobox('setValue','');
			}
		}

		function edit_hasil(){
			var row = $('#dg_daily').datagrid('getSelected');
            if (row){
            	$('#dlg_edit').dialog('open').dialog('setTitle','Edit Hasil Kanban');
            	$('#cmb_Line_edit').combobox('setValue',row.ASSY_LINE);
            	$('#cell_type_edit').combobox('setValue',row.CELL_TYPE);
            	$('#palet_edit').textbox('setValue',row.PALLET);
            	$('#tgl_prod_edit').datebox('setValue',row.TGL_PROD);
            	$('#txt_mulai').textbox('setValue',row.START_DATE.replace('<br/>',' '));
            	$('#txt_akhir').textbox('setValue',row.END_DATE.replace('<br/>',' '));
            	$('#txt_qtypallet').textbox('setValue',row.QTY_ACT_PERPALLET.replace(/,/g,''));
            	$('#txt_qtybox').textbox('setValue',row.QTY_ACT_PERBOX.replace(/,/g,''));
            	$('#txt_perbox').textbox('setValue',row.QTY_BOX);
            	$('#tgl_scan_edit').datebox('setValue',row.TGL_ACT);
            	$('#txt_id_edit').textbox('setValue',row.ID);
            }
		}

		function saveEdit(){
			var qbox = $('#txt_qtybox').textbox('getValue');
	    	if(qbox == ''){
	    		$.messager.alert('INFORMATION','Jumlah box masih kosong','info');
	    	}else{
	    		$.post('save_edit.php',{
	    			edit_cells: $('#cell_type_edit').combobox('getValue'),
	    			edit_tprod: $('#tgl_prod_edit').datebox('getValue'),
	    			edit_mulai: $('#txt_mulai').textbox('getValue'),
					edit_akhir: $('#txt_akhir').textbox('getValue'),
					edit_qtbox: $('#txt_qtybox').textbox('getValue'),
					edit_prbox: $('#txt_perbox').textbox('getValue'),
					edit_tscan: $('#tgl_scan_edit').datebox('getValue'),
					edit_idpln: $('#txt_id_edit').textbox('getValue')
				}).done(function(res){
					//alert(res);
					console.log(res);
					if(res.errorMsg=='Session Expired'){
						$.messager.alert('INFORMATION','Simpan Data Gagal..!!','info');
					}else{
						$('#dlg_edit').dialog('close');
						$('#dg_daily').datagrid('reload');
						$.messager.alert('INFORMATION','Edit Data Berhasil..!!','info');
					}
				})
	    	}
		}

		$(function(){
			$('#id_no').textbox('textbox').css('font-size','16px');
			$('#assy_line').textbox('textbox').css('font-size','16px');
			$('#cell_type').combobox('textbox').css('font-size','18px');
			$('#pallet').textbox('textbox').css('font-size','16px');
			$('#tanggal_produksi').datebox('textbox').css('font-size','16px');
			$('#qty_berjalan').numberbox('textbox').css('font-size','16px');
			$('#id_plan').numberbox('textbox').css('font-size','16px');
			$('#qty_perpallet').numberbox('textbox').css('font-size','16px');
			//document.getElementById('qty_act_perpallet').css('font-size','16px');
			$('#qty_perbox').numberbox('textbox').css('font-size','16px');
			//document.getElementById('qty_act_perbox').css('font-size','16px');
			$('#qty_box').numberbox('textbox').css('font-size','16px');
			$('#date_akhir').textbox('textbox').css('font-size','18px');
			$('#cmb_ng_proses').combobox('textbox').css('font-size','18px');
			$('#cmb_ng').combobox('textbox').css('font-size','18px');
			$('#qty_ng').numberbox('textbox').css('font-size','18px');
			$('#tgl_adh').datebox('textbox').css('font-size','14px');
			$('#tgl_cca').datebox('textbox').css('font-size','14px');
			$('#tgl_sep').datebox('textbox').css('font-size','14px');
			$('#tgl_gel').datebox('textbox').css('font-size','14px');
			$('#tgl_elektrolyte').datebox('textbox').css('font-size','14px');
		})

		$('#dg_in').datagrid({
			url: 'get.php',
			singleSelect: true,
			rownumbers: true,
			columns:[[
				{field:'ID',title:'ID',width:100,halign:'center', align:'center'},
                {field:'ASSY_LINE',title:'ASSEMBLY<br/>LINE',width:100,halign:'center', align:'center'},
                {field:'CELL_TYPE',title:'CELL TYPE',width:100,halign:'center', align:'center'},
                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
                {field:'TANGGAL_PRODUKSI',title:'TANGGAL<br/>PRODUKSI',width:100,halign:'center', align:'center'},
               	{field:'QTY_ACT_PERPALLET',title:'QTY/PALLET',width:100,halign:'center', align:'right'},
               	{field:'QTY_ACT_PERBOX',title:'QTY/BOX',width:100,halign:'center', align:'right'},
               	{field:'START_DATE',title:'MULAI',width:150,halign:'center', align:'center'},
               	{field:'END_DATE',title:'AKHIR',width:150,halign:'center', align:'center'},
               	{field:'TANGGAL_ADHESIVE',title:'TANGGAL<br/>ADH. PAINTING',width:100,halign:'center', align:'center'},
               	{field:'TANGGAL_CCA',title:'TANGGAL<br/>CCA',width:100,halign:'center', align:'center'},
               	{field:'TANGGAL_SEPARATOR',title:'TANGGAL<br/>SEPARATOR',width:100,halign:'center', align:'center'},
               	{field:'TANGGAL_GEL',title:'TANGGAL<br/>GEL',width:100,halign:'center', align:'center'},
               	{field:'COMM_ADHESIVE',title:'ADHESIVE',width:100,halign:'center', align:'center'},
               	{field:'COMM_SEPARATOR',title:'SEPARATOR',width:100,halign:'center', align:'center'}
            ]]
        });

        $('#dg_ng').datagrid({
        	url: 'get_ng.php',
        	fitColumns: true,
        	rownumbers: true,
			columns:[[
                {field:'NG_NO',title:'TROUBLE NO.',width:100,halign:'center', align:'center'},
                {field:'ASSY_LINE',title:'ASSEMBLY LINE',width:100,halign:'center', align:'center'},
                {field:'CELL_TYPE',title:'CELL TYPE',width:100,halign:'center', align:'center'},
                {field:'PALLET',title:'PALLET',width:50,halign:'center', align:'center'},
                {field:'TANGGAL_PRODUKSI',title:'TANGGAL PRODUKSI',width:150,halign:'center', align:'center'},
                {field:'NG_PROSES',title:'PROSES',width:150,halign:'center'},
                {field:'NG_NAME',title:'Trouble',width:200,halign:'center'},
                {field:'NG_QTY',title:'MENIT',width:50,halign:'center', align:'right'},
                {field:'PERBAIKAN',title:'PERBAIKAN',width:200,halign:'center'}
            ]],
            onClickRow:function(id,row){
            	var row = $('#dg_ng').datagrid('getSelected');
				if(row){
					$('#dlg_perbaikan').dialog('open').dialog('setTitle','PERBAIKAN '+row.NG_NAME);
					$('#txt_perbaikan').textbox('textbox').focus();
					$('#ng_no').textbox('setValue',row.NG_NO);
					$('#txt_perbaikan').textbox('setValue',row.PERBAIKAN);
				}else{
					$.messager.alert('INFORMATION','DATA BELUM DIPILIH..!!','info');
				}
            }
        });

	    function scan(event){
	        if(event.keyCode == 13 || event.which == 13){
	            var sc = document.getElementById('scn').value;
	            //alert(sc);
	            var split = sc.split(",");
	            var asyln=split[1].replace('#','-');

	            var date = new Date();
				var hh = date.getHours();
				var yy = date.getFullYear();
				var mm = date.getMonth()+1;
				var dd = date.getDate();
				var ddmin = date.getDate()-1;

	            if(split[0] == '0' && split[7] == 'LEBIH'){
					$.ajax({
						type: 'GET',
						url: '../../forms/json/json_cek_start_kanban.php?plt='+split[5]+'&plan='+split[7]+'&line='+asyln,
						success: function(data){
							$('#date_mulai').textbox('textbox').css('font-size','18px');
							$('#date_mulai').textbox('setValue',data[0].START);
						}
					});
					$('#id_no').textbox('setValue',split[8]);
	            	$('#assy_line').textbox('setValue',split[1]);
					$('#cell_type').combobox('enable');
					$('#cell_type').combobox('setValue','');
					$('#pallet').textbox('setValue',split[5]);
					//$('#tanggal_produksi').datebox('setValue',myformatter(new Date()));
					/**/
					if (parseInt(hh) < 7){
						$('#tanggal_produksi').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(ddmin<10?('0'+ddmin):ddmin));
					}else{
						$('#tanggal_produksi').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd));
					}
					/**/

					$('#qty_perpallet').numberbox('setValue',split[4]);
					$('#qty_perbox').numberbox('setValue',split[3]);
					$('#qty_box').numberbox('setValue',split[6]);
					$('#id_plan').textbox('setValue',split[7]);

					$('#mulai').linkbutton('enable');
					$('#akhir').linkbutton('enable');
					$('#cmb_ng_proses').combobox('enable');	
					$('#cmb_ng').combobox('enable');
					$('#qty_ng').numberbox('enable');
					$('#input_ng').linkbutton('enable');
					$('#qty_ng').numberbox('setValue',0);

					if(split[1] == 'LR06#4'){
						$('#tgl_adh').datebox('setValue','');
						$('#tgl_adh').datebox('enable');
						$('#txt_adh_paint').textbox('enable');
						$('#txt_separator').textbox('disable');
					}else if(split[1] == 'LR03#2' || split[1] == 'LR06#5'){
						$('#tgl_sep').datebox('setValue','');
						$('#tgl_sep').datebox('enable');
						$('#txt_adh_paint').textbox('disable');
						$('#txt_separator').textbox('enable');
					}else if (split[1] == 'LR06#6'){
						$('#tgl_adh').datebox('setValue','');
						$('#tgl_sep').datebox('setValue','');
						$('#tgl_adh').datebox('disable');
						$('#tgl_sep').datebox('disable');
						$('#txt_adh_paint').textbox('enable');
						$('#txt_separator').textbox('enable');
					}
					document.getElementById('qty_act_perpallet').disabled = true;
					document.getElementById('qty_act_perpallet').value = split[4];
					document.getElementById('qty_act_perbox').disabled = false;
					document.getElementById('qty_act_perbox').value = split[3];
					document.getElementById('scn').value='';
	            }else if(split[0] == '0' && split[7] == 'QC'){
	            	$('#dlg_qc').dialog('open').dialog('setTitle','SAMPLE QC '+split[1]);
					$('#cmb_Line_qc').combobox('setValue',split[1]);
					$('#txt_qtypallet_qc').textbox('setValue',split[4]+"/"+split[3]);
					$('#txt_perbox_qc').textbox('setValue',split[6]);
					
					if (parseInt(hh) < 7){
						$('#tgl_prod_qc').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(ddmin<10?('0'+ddmin):ddmin));
					}else{
						$('#tgl_prod_qc').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd));
					}
					document.getElementById('scn').value='';
	            }else{
		            if (parseInt(hh) < 7){
		            	var tgl_p = yy+'-'+(mm<10?('0'+mm):mm)+'-'+(ddmin<10?('0'+ddmin):ddmin)
					}else{
						var tgl_p = yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd)
					}

					$.ajax({
						type: 'GET',
						url: '../../forms/json/json_cek_qty_kanban.php?plt='+split[5]+'&tgl_pro='+tgl_p+'&plan='+split[7],
						success: function(data){
							$('#qty_berjalan').numberbox('setValue',data[0].QTY);
							document.getElementById('qty_act_perpallet').value = split[4]-data[0].QTY;
							document.getElementById('qty_act_perbox').value = parseInt((split[4]-data[0].QTY) / split[6]);
						}
					});

					$.ajax({
						type: 'GET',
						url: '../../forms/json/json_cek_start_kanban.php?plt='+split[5]+'&tgl_pro='+tgl_p+'&plan='+split[7]+'&line='+asyln,
						success: function(data){
							$('#date_mulai').textbox('textbox').css('font-size','18px');
							$('#date_mulai').textbox('setValue',data[0].START);
						}
					});

					$('#id_no').textbox('setValue',split[8]);
					$('#cell_type').combobox('disable');
		            $('#assy_line').textbox('setValue',split[1]);
					$('#cell_type').combobox('setValue',split[2]);
					$('#pallet').textbox('setValue',split[5]);
					//$('#tanggal_produksi').datebox('setValue',tgl_p);
					/**/
					if (parseInt(hh) < 7){
						$('#tanggal_produksi').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(ddmin<10?('0'+ddmin):ddmin));
					}else{
						$('#tanggal_produksi').datebox('setValue', yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd));
					}
					/**/
					$('#qty_perpallet').numberbox('setValue',split[4]);
					$('#qty_perbox').numberbox('setValue',split[3]);
					$('#qty_box').numberbox('setValue',split[6]);
					$('#id_plan').textbox('setValue',split[7]);

					$('#mulai').linkbutton('enable');
					$('#akhir').linkbutton('enable');
					$('#cmb_ng_proses').combobox('enable');	
					$('#cmb_ng').combobox('enable');
					$('#qty_ng').numberbox('enable');
					$('#input_ng').linkbutton('enable');
					$('#qty_ng').numberbox('setValue',0);

					if(split[1] == 'LR06#4'){
						$('#tgl_adh').datebox('setValue','');
						$('#tgl_adh').datebox('enable');
						$('#txt_adh_paint').textbox('enable');
						$('#txt_separator').textbox('disable');
					}else if(split[1] == 'LR03#2' || split[1] == 'LR06#5'){
						$('#tgl_sep').datebox('setValue','');
						$('#tgl_sep').datebox('enable');
						$('#txt_adh_paint').textbox('disable');
						$('#txt_separator').textbox('enable');
					}else if (split[1] == 'LR06#6'){
						$('#tgl_adh').datebox('setValue','');
						$('#tgl_sep').datebox('setValue','');
						$('#tgl_adh').datebox('disable');
						$('#tgl_sep').datebox('disable');
						$('#txt_adh_paint').textbox('enable');
						$('#txt_separator').textbox('enable');
					}
					document.getElementById('scn').value='';
					document.getElementById('qty_act_perpallet').disabled = true;
					document.getElementById('qty_act_perbox').disabled = false;
				}
	        }
	    }

        function qty_act(event){
        	if(event.keyCode == 13 || event.which == 13){
        		var a = parseInt(document.getElementById('qty_act_perpallet').value);
        		var b = parseInt($('#qty_box').numberbox('getValue'));
        		var c = a/b;
        		document.getElementById('qty_act_perbox').value = Math.ceil(c);
        	}
        }

        function qty_act_box(event){
        	if(event.keyCode == 13 || event.which == 13){
        		var a = parseInt(document.getElementById('qty_act_perbox').value);
        		var b = parseInt($('#qty_box').numberbox('getValue'));
        		var c = a*b;
        		document.getElementById('qty_act_perpallet').value = Math.ceil(c);
        	}
        }

	    function save_mulai(){
	    	var ctp =  $('#cell_type').combobox('getValue');
    		$.post('save_start.php',{
				assy_line: $('#assy_line').textbox('getValue'),
				cell_type: ctp,
				pallet: $('#pallet').textbox('getValue'),
				tgl_prod: $('#tanggal_produksi').datebox('getValue'),
				qty_perpallet: $('#qty_perpallet').numberbox('getValue'),
				qty_perbox: $('#qty_perbox').numberbox('getValue'),
				date_mulai: $('#date_mulai').textbox('getValue'),
				plan: $('#id_plan').textbox('getValue'),
				id: $('#id_no').textbox('getValue')
			}).done(function(res){
				//alert(res);
				console.log(res);
				$('#dg_in').datagrid('reload');
				window.location.reload(1);
			})
	    }

	    function waktu_awal(){
	    	var qty_j = $('#qty_berjalan').numberbox('getValue').replace(/,/g,'');
	    	var qty_p = $('#qty_perpallet').numberbox('getValue').replace(/,/g,'');
	    	var assy_ln = $('#assy_line').textbox('getValue');
	    	var assy_ln2 = assy_ln.replace('#','-');
	    	if(qty_j!=qty_p){
	    		$.ajax({
					type: 'GET',
					url: '../../forms/json/json_date_now.php?assy_line='+assy_ln2,
					data: { kode:'kode', jum:'jum'},
					success: function(data){
						var idp = $('#id_plan').textbox('getValue');
				    	var ct =  $('#cell_type').combobox('getValue');
				    	if(data[0].jum != 0){
				    		$.messager.alert('INFORMATION','MAAF PALLET SEBELUMNYA BELUM SELESAI','info');
				    	}else if((idp == 'LEBIH' && ct == '') || (idp == 'QC' && ct == '')) {
				    		$.messager.alert('INFORMATION','CELL TYPE TIDAK BOLEH KOSONG','info');
				    	}else{
				    		$('#date_mulai').textbox('textbox').css('font-size','18px');
							$('#date_mulai').textbox('setValue',data[0].kode);
				    		save_mulai();	
				    	}
						
					}
				});
	    	}else{
	    		$.messager.alert('INFORMATION','PALLET INI SUDAH DI SCAN','info');
	    		clear();
	    	}
	    	
	    }

	    function start(){
	    	waktu_awal();
	    }

	    function save(){
	    	var dt_z = $('#date_akhir').textbox('getValue');
	    	if(dt_z==''){
	    		$.messager.alert('INFORMATION','waktu selesai masih kosong','info');
	    	}else{
	    		$.post('save.php',{
					assy_line: $('#assy_line').textbox('getValue'),
					cell_type: $('#cell_type').combobox('getValue'),
					pallet: $('#pallet').textbox('getValue'),
					tgl_prod: $('#tanggal_produksi').datebox('getValue'),
					qty_perpallet: $('#qty_perpallet').numberbox('getValue'),
					qty_act_perpallet: document.getElementById('qty_act_perpallet').value,
					qty_perbox: $('#qty_perbox').numberbox('getValue'),
					qty_act_perbox: document.getElementById('qty_act_perbox').value,
					qty_box: parseInt($('#qty_box').numberbox('getValue')),
					date_mulai: $('#date_mulai').textbox('getValue'),
					date_akhir: $('#date_akhir').textbox('getValue'),
					tgl_adh: $('#tgl_adh').datebox('getValue'),
					tgl_cca: $('#tgl_cca').datebox('getValue'),
					tgl_sep: $('#tgl_sep').datebox('getValue'),
					tgl_gel: $('#tgl_gel').datebox('getValue'),
					tgl_elektrolyte: $('#tgl_elektrolyte').datebox('getValue'),
					txt_adh_paint: $('#txt_adh_paint').textbox('getValue'),
					txt_separator: $('#txt_separator').textbox('getValue'),
					plan: $('#id_plan').textbox('getValue'),
					id: $('#id_no').textbox('getValue')
				}).done(function(res){
					//alert(res);
					console.log(res);
					if(res.errorMsg=='Error' || res.errorMsg=='Session Expired'){
						$.messager.alert('INFORMATION','Simpan Data Gagal..!!','info');
					}else{
						$('#dg_in').datagrid('reload');
						$.messager.alert('INFORMATION','Simpan Data Berhasil..!!','info');
				    	window.location.reload(1);
				    	//clear();
						document.getElementById('scn').focus();
					}
					
				})
	    	}
	    }

	    function in_ng(){
	    	var ng_p = $('#cmb_ng_proses').combobox('getValue');
	    	var ng_n = $('#cmb_ng').combobox('getValue');
	    	var dt_m = $('#date_mulai').textbox('getValue');

	    	if(ng_p == '' && ng_n == ''){
	    		$.messager.alert('INFORMATION','FIELD TIDAK BOLEH KOSONG..!!','info');
	    	}else{
	    		if(dt_m == ''){
	    			$.messager.alert('INFORMATION','BELUM DIMULAI ..!!, KLIK TOMBOL MULAI' ,'info');
	    		}else{
	    			if ($('#qty_ng').numberbox('getValue') != 0){
			    		$.post('save_ng.php',{
				    		assy_line: $('#assy_line').textbox('getValue'),
							cell_type: $('#cell_type').combobox('getValue'),
							pallet: $('#pallet').textbox('getValue'),
							tgl_prod: $('#tanggal_produksi').datebox('getValue'),
							ng_proses: $('#cmb_ng_proses').combobox('getValue'),
							ng_name: $('#cmb_ng').combobox('getValue'),
							ng_qty: $('#qty_ng').numberbox('getValue'),
							plan: $('#id_plan').textbox('getValue'),
							id: $('#id_no').textbox('getValue')
				    	}).done(function(res){
							//alert(res);
							console.log(res);
							if(res.errorMsg=='Error' || res.errorMsg=='Session Expired'){
								$.messager.alert('INFORMATION','Simpan Trouble Gagal..!!','info');
							}else{
								$('#dg_ng').datagrid('reload');
								$('#cmb_ng_proses').combobox('setValue','');
								$('#cmb_ng').combobox('setValue','');
								$('#qty_ng').numberbox('setValue','0');
							}
						});
					}
				}
	    	}
	    }

	    function clear(){
	    	$('#assy_line').textbox('setValue','');
			$('#cell_type').combobox('setValue','');
			$('#pallet').textbox('setValue','');
			$('#tanggal_produksi').datebox('setValue','');
			$('#qty_perpallet').numberbox('setValue','');
			document.getElementById('qty_act_perpallet').value='';
			$('#qty_perbox').numberbox('setValue','');
			document.getElementById('qty_act_perbox').value='';
			$('#qty_berjalan').numberbox('setValue','');
			$('#mulai').linkbutton('disable');
			$('#akhir').linkbutton('disable');
			$('#date_mulai').textbox('setValue','');
			$('#date_akhir').textbox('setValue','');
			$('#cmb_ng_proses').combobox('setValue','');	
			$('#cmb_ng_proses').combobox('disable');
			$('#cmb_ng').combobox('setValue','');
			$('#cmb_ng').combobox('disable');
			$('#qty_ng').numberbox('setValue',0);
			$('#qty_ng').numberbox('disable');	
			$('#txt_adh_paint').textbox('setValue','');
			$('#txt_separator').textbox('setValue','');
	    }

	    function waktu_akhir(){
	    	$.ajax({
				type: 'GET',
				url: '../../forms/json/json_date_now.php',
				data: { kode:'kode'},
				success: function(data){
					$('#date_akhir').textbox('setValue',data[0].kode);
					save();
				}
			});
			
	    }

	    function finish(){
	    	var dt_a = $('#date_mulai').textbox('getValue');
	    	if (dt_a==''){
	    		$.messager.alert('INFORMATION','WAKTU MULAI TIDAK BOLEH KOSONG..!!','info');
	    	}else{
	    		waktu_akhir();
	    	}
	    }

	    function save_qc(){
	    	var cell_qc = $('#cell_type_qc').combobox('getValue');
	    	var qty_qc = $('#txt_qty_sample_qc').textbox('getValue');
	    	var tgl_qc = $('#tgl_prod_qc').datebox('getValue');
	    	var qty_set = $('#txt_qtypallet_qc').textbox('getValue');
	        var split_qc = qty_set.split("/");
	        var qty_box_qc = $('#txt_perbox_qc').textbox('getValue');
	        var qty_act_box_qc = Math.ceil(qty_qc/qty_box_qc);


	    	if(cell_qc == ''){
	    		$.messager.alert('INFORMATION','CELL TYPE TIDAK BOLEH KOSONG..!!','info');
	    	}else if(qty_qc==''){
	    		$.messager.alert('INFORMATION','QTY SAMPLE TIDAK BOLEH KOSONG..!!','info');
	    	}else if(tgl_qc == ''){
	    		$.messager.alert('INFORMATION','TANGGAL PRODUKSI TIDAK BOLEH KOSONG..!!','info');
	    	}else{
	    		$.post('save_qc.php',{
					assy_line: $('#cmb_Line_qc').textbox('getValue'),
					cell_type: cell_qc,
					pallet: 0,
					tgl_prod: tgl_qc,
					qty_perpallet: split_qc[0],
					qty_act_perpallet: qty_qc,
					qty_perbox: split_qc[1],
					qty_act_perbox: qty_act_box_qc,
					qty_box: qty_box_qc
				}).done(function(res){
					//alert(res);
					console.log(res);
					if(res.errorMsg=='Error' || res.errorMsg=='Session Expired'){
						$.messager.alert('INFORMATION','Simpan Data Gagal..!!','info');
					}else{
						$.messager.alert('INFORMATION','Simpan Data Berhasil..!!','info');
						$('#dlg_qc').dialog('close');
						document.getElementById('scn').focus();
					}					
				})
	    	}
	    }

	    function logOut(){
	    	$.messager.confirm('Confirm','Yakin Akan keluar dari System Kanban?',function(r){
				if(r){
					$.post('logout.php');
					self.location.href = '../index.php';
				}
			});
	    }

	</script>
    </body>
    </html>
    <?php
}else{
	header('location:../index.php');
}
?>
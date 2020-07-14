<?php
include("../connect/koneksi.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['sita_user_name'];
	$nik = $_SESSION['sita_user_nik'];

	$sql= pg_query("select b.nik, a.kode_menu, a.parent, a.nama_menu, a.link, b.access_view, b.access_add, b.access_update, b.access_print, b.access_delete
		from menu a left join user_access b
		on a.kode_menu = b.kode_menu
		where a.kode_menu ='30312' and b.nik = '$nik'");
	$data = pg_fetch_assoc($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Order Customer</title>
	<script language="javascript">
 function confirmLogOut(){
	var is_confirmed;
	is_confirmed = widthndow.confirm("End current session?");
	return is_confirmed;
 }
  </script> 
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/qrcode.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/myentertab.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
    <script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="../js/datagrid-detailview.js"></script>
    <script type="text/javascript" src="../js/datagrid-cellediting.js"></script>
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
	.style4 {
	font-size: 11px;
	color: #CC0000;
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
		padding: 2px 0px;
		width: 100%;
	}
	.board_1 {
		position: absolute;
		margin-left:615px;	
		top: 0px;
		border-style: solid;
		border-width: 0px;
	}
	.board_2 {
		position: absolute;
		margin-left:615px;	
		top: 51px;
		border-style: solid;
		border-width: 0px;
	}
	.button_filter {
		position: absolute;
		margin-left:135px;	
		top: 120px;
	}
	.button_generate {
		position: absolute;
		margin-left:100px;	
		top: 134px;
	}
	.do_field{
		width:800px;
		border: 1px solid #d0d0d0;
		float: left;
	}
	.clear{
		clear: both;
	}
	</style>
</head>
<body>
	<?php include ('../ico_logout.php'); 
	?>

	<div id="toolbar">
		<div style="">
			<fieldset class="do_field"><legend>Transaction</legend>
				<div style="width:420px; float:left;">
					<div class="fitem">
						<span style="width:100px;display:inline-block;">Periode</span>
						<input style="width:90px;" name="s_periode_awal" id="s_periode_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
						to 
						<input style="width:90px;" name="s_periode_akhir" id="s_periode_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
						<!-- <label>
							<input type="checkbox" name="ck_date" id="ck_date" checked="true"></input>All
						</label> -->
					</div>
					<div class="fitem">
						<span style="width:100px;display:inline-block;">Customer</span>
						<select style="width:200px;" name="kode_customer" id="kode_customer" class="easyui-combobox" data-options=" url:'json/json_customer.php',method:'get',valueField:'kode_customer',textField:'nama_customer', panelHeight:'150px', panelWidth:'300px'"></select>
						<label><input type="checkbox" name="ck_cus" id="ck_cus" checked="true">All</input></label>
					</div>	
				</div>
				<div style="width:280px; float:left;">
					<div class="fitem">
						<div style="float:left;">
							<span style="width:70px;display:inline-block;">Category</span>
							<select style="width:70px;;" name="jenis_kategori" id="jenis_kategori" class="easyui-combobox" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'auto',
							onSelect:function(rec){
		    					//alert(rec.ket_kategori);
		    					if($.trim(rec.ket_kategori)=='RM'){
		    						$('#subcat').show();
		    						$('#subcategory').combobox('setValue', '00001');
		    					}else{
		    						$('#subcat').hide();
		    						$('#subcategory').combobox('setValue', '');
		    					}
		    				}"></select>
						</div>
						<div style="float:left;" id="subcat">
							<select style="width:55px;" name="subcategory" id="subcategory" class="easyui-combobox" data-options=" url:'json/json_subcategory.php',method:'get',valueField:'kode_subkategori',textField:'nama_subkategori', panelHeight:'70px'
							"></select>
						</div>
						<label><input type="checkbox" name="ck_cat" id="ck_cat" checked="true">All</input></label>
					</div>
					<div style="clear:both;"></div>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="filterData()" iconCls="icon-filter" style="width:70px; ">Filter</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="createnonDo()" iconCls="icon-add" style="width:160px; ">Create D.O Non Sales</a>
					
				</div>
				<div class="clear"></div>
			</fieldset>
			<div style="width:400px; float:left;">
				<div style="margin: 40px 0 0 100px;;">
					<span style="font-size:18px;">Total Qty DO : </span>
					<span style="font-size:18px;" id="t_qty">0</span>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<label style="margin-right:20px; "><input type="checkbox" name="ck_all" id="ck_all">Select All</input></label>
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="createDo()" iconCls="icon-add" plain="true" style="margin-right:1050px;">CREATE DO</a>
		<!-- <label><input type="checkbox" name="ck_all" id="ck_all" disabled="true">Select All</input></label> -->
	</div>

	<table id="dg" title="Create Delivery Order Customer" class="easyui-datagrid" style="width:1250px;height:500px;">
	</table>

	<div id="dlg_do" class="easyui-dialog" style="height:220px; width:365px;" buttons='#do-buttons' data-options="modal:false,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<div style="margin:20px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">DO. No.</span>
				<input style="width:150px;" name="do_no" id="do_no" class="easyui-textbox" disabled="true" />
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">REF: </span>
				<select style="width:150px;" name="dn_no" id="dn_no" class="easyui-combobox" data-options=" url:'json/json_bookdn.php',method:'get',valueField:'dn_no',textField:'dn_no', panelHeight:'150px',
				onSelect:function(rec){
					var note = $('#notes').textbox('getValue');
					var tot = note.length;

						//$('#notes').textbox('setValue', 'REF:'+rec.dn_no);
					if(tot != 0){
					}
				}"></select>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Note</span>
				<input name="notes" id="notes" class="easyui-textbox" data-options="multiline:true" style="width:200px;height:50px" >
			</div>
		</div>
	</div>

	<div id="dlg_nondo" class="easyui-dialog" style="height:200px; width:365px;" buttons='#nondo-buttons' data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<div style="margin:20px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">DO. No.</span>
				<input style="width:150px;" name="nondo_no" id="nondo_no" class="easyui-textbox" disabled="true" />
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Note</span>
				<input name="notes" id="nonotes" class="easyui-textbox" data-options="multiline:true" style="width:200px;height:50px" >
			</div>
		</div>
	</div>

	<div id="nondo-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" style="width:90px" onClick="saveNondo()">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_nondo').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="do-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" style="width:90px" onClick="saveDo()">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_do').dialog('close')" style="width:90px">Cancel</a>
	</div>
	
	<div id="dlg_non" class="easyui-dialog" style="height:380px; width:565px;" buttons='#nonso-buttons' data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<div style="width:250px; float:left; margin:5px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">DO Date</span>
				<input style="width:150px;" name="do_date" id="do_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/> 
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Category</span>
				<select style="width:150px;;" name="non_kategori" id="non_kategori" class="easyui-combobox" data-options=" url:'json/json_category.php',method:'get',valueField:'jenis_kategori',textField:'ket_kategori', panelHeight:'auto',
				onSelect:function(rec){
		    		//alert(rec.ket_kategori);
		    		if($.trim(rec.ket_kategori)=='RM'){
		    			$('#subcatnon').show();
		    			$('#non_subcategory').combobox('setValue', '00001');
		    		}else{
		    			$('#subcatnon').hide();
		    			$('#non_subcategory').combobox('setValue', '');
		    		}
		    	}"></select>
			</div>
			<div class="fitem" id="subcatnon">
				<span style="width:80px;display:inline-block;">SubCategory</span>
				<select style="width:150px;" name="non_subcategory" id="non_subcategory" class="easyui-combobox" data-options=" url:'json/json_subcategory.php',method:'get',valueField:'kode_subkategori',textField:'nama_subkategori', panelHeight:'70px'
				"></select>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">WHS Type</span>
				<select style="width:150px;" name="whs_type" id="whs_type" class="easyui-combobox" data-options=" url:'json/json_type_po.php',method:'get',valueField:'id_type',textField:'name_type', panelHeight:'100px'"></select>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Trans Type</span>
				<input id="t_type" name="t_type" style="width:148px;">
				<!-- <select style="width:150px;" name="t_type" id="t_type" class="easyui-combobox" data-options=" url:'json/json_trans_typeout.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'150px', panelWidth:'200px'"></select> -->
			</div>
			<div class="fitem" style="display:none">
				<span style="width:80px;display:inline-block;">Sub Type</span>
				<input id="subtype" name="subtype" class="easyui-textbox" style="width:148px;">
				<!-- <select style="width:150px;" name="t_type" id="t_type" class="easyui-combobox" data-options=" url:'json/json_trans_typeout.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'150px', panelWidth:'200px'"></select> -->
			</div>
		</div>
		<div style="width:250px; float:left; margin:5px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Destination</span>
				<select style="width:150px;" name="customer" id="customer" class="easyui-combobox" data-options=" url:'create_do_customer_gettype.php', method:'get', valueField:'kode', textField:'nama', panelHeight:'150px', panelWidth:'300px'"></select>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Attention</span>
				<input name="att" id="att"  class="easyui-combobox" style="width:150px;" data-options="
						url: 'json/json_attention.php',
						method: 'get',
						textField: 'attention',
						valueField: 'id',
						panelHeight: '150px'">
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Scan Status</span>
				<select style="width:100px" name="scn_sts" id="scn_sts" class="easyui-combobox" data-options=" url:'json/json_scan.json', method:'get', valueField:'scan', textField:'scan', panelHeight:'auto'"></select>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;"></span>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="findItem()">Find Part No.</a>
			</div>
		</div>
		<div class="clear"></div>
		<div align="center">
			<table id="dg_non" class="easyui-datagrid" style="width:540px;height:150px;">
			</table>
		</div>
	</div>

	<div id="nonso-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" style="width:90px" onClick="saveNon()">Create</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_non').dialog('close')" style="width:90px">Cancel</a>
	</div>

	<div id="dlg_part" title="Find Part No." class="easyui-dialog" style="height:350px; width:400px;" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:true,closed:true">
		<table id="dg_part" class="easyui-datagrid" style="width:380px;height:300px;"
				url=""
				rownumbers="true" singleSelect="true"
				sortName="brg_codebarang" sortOrder="asc">
		</table>
	</div>


	<!-- <div id="notif" style="width:8%; position:fixed; bottom:0; right:0;" >
		<a href="javascript:void(0)" onclick="javascript:$('#notif').hide()" style="text-decoration:none;" title="Close"><img src="../images/xclose.png" alt="[X]" title="Close" style="width:16px;"></a>
		<a href="javascript:void(0)" onclick="help()"><img src="../images/gif/thinking.gif" alt="help" title="help?"></a>
	</div> -->
	
	<script type="text/javascript">
			function closeHelp(){
				$('#notif').hide();
			}
			function help(){
				$.messager.alert('help');
			}
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
		</script>
		

	<script type="text/javascript">
		var pdf_url='';

		$('#t_type').combogrid({
			url: 'json/json_trans_typeout.php',
			panelWidth: 330,
			mode: 'remote',
			idField: 'name_type',
			textField: 'name_type',
			columns: [[
				{field:'name_type', halign:'center', title:'Trans Type',width:120},
		        {field:'type', halign:'center', title:'Type',width:60},
		        {field:'subtype', halign:'center', title:'Subtype',width:120}
			]],
			onClickRow:function(id, row){
				if (row.subtype==null) {
					row.subtype='';
				};
				// alert(row.subtype);
				$('#subtype').textbox('setValue', row.subtype);
				
				var url = 'create_do_customer_gettype.php';
				if ($.trim(row.subtype)=='SUPPLIER') {	
					$('#sub').text('Supplier');
					url = 'create_do_customer_gettype.php?subtype='+row.subtype;
					$('#customer').combobox('reload', url);
					$('#customer').combobox('setValue', '');
					//alert('create_do_customer_gettype.php?subtype='+row.subtype);
				}else if($.trim(row.subtype)=='CUSTOMER'){
					$('#sub').text('Customer');
					url = 'create_do_customer_gettype.php?subtype='+row.subtype;
					$('#customer').combobox('reload', url);
					$('#customer').combobox('setValue', '');
				}else{
					$('#sub').text('Subtype');
					url = 'create_do_customer_gettype.php?subtype='+row.name_type;
					$('#customer').combobox('reload', url);
					$('#customer').combobox('setValue', row.id_type);
				}
				//alert(url);
			}
		});

		function saveNon(){
			$.ajax({
				type: 'GET',
				url: 'json/json_delivery_no.php',
				data: { kode:'kode' },
				success: function(data){
					//$('#nondo_no').textbox('setValue','DO'+'<?=date('Ym')?>'+data[0].kode);
				}
			});
			$('#dlg_nondo').dialog('open').dialog('setTitle', 'Save to Delivery Order');
		}

		function saveNondo(){
			$.get('json/json_delivery_no.php').done(function(res){
				var dono = 'DO'+'<?=date('Ym')?>'+res[0].kode;
				//alert(dono);
				var total = $('#dg_non').datagrid('getData').total;
				//alert(total);
				var i=0;
				var totcek=0;
				//alert($('#scn_sts').combobox('getValue'));
				for (i = 0; i < total; i++) {
					var qty = $('#dg_non').datagrid('getEditor', {index:i,field:'qty'});
					var remarks = $('#dg_non').datagrid('getEditor', {index:i,field:'remarks'});
					$.post('create_do_customer_savenon.php',{
						do_date: $('#do_date').datebox('getValue'),
						kategori: $('#non_kategori').combobox('getValue'),
						subkategori: $('#non_subcategory').combobox('getValue'),
						whs_type: $('#whs_type').combobox('getText'),
						trans_type: $('#t_type').combobox('getValue'),
						customer: $('#customer').combobox('getValue'),
						subtype: $('#subtype').textbox('getValue'),
						att: $('#att').combobox('getValue'),
						serial_no: $('#dg_non').datagrid('getData').rows[i].serial_no,
						part_no: $('#dg_non').datagrid('getData').rows[i].part_no,
						qty: $(qty.target).numberbox('getValue'),
						remarks: $(remarks.target).textbox('getValue'),
						scn_sts: $('#scn_sts').combobox('getValue'),
						do_no: dono,
						notes: $('#nonotes').textbox('getValue'),
						id: totcek+1
					}).done(function(res){
						//alert(res);
						$('#dlg_nondo').dialog('close');
						$('#dlg_non').dialog('close');
					});
						totcek++;
				};
				$.messager.alert('Information',dono+' is Saved','info');
			});
		}
		function findItem(){
			$('#dlg_part').dialog('open');
			$('#dg_part').datagrid('loadData', []);
			$('#dg_part').datagrid({
				url: 'create_do_customer_getitem.php?kode_customer='+$('#customer').combobox('getValue')+'&jenis_kategori='+$('#non_kategori').combobox('getValue')
			});
			$('#dg_part').datagrid('enableFilter');
		}

		$('#dg_part').datagrid({
			columns:[[
				{field:'serial_no', title:'Serial No', width:80, halign:'center'},
				{field:'brg_codebarang', title:'Part No', width:80, halign:'center'},
				{field:'nama_barang', title:'Part Name', width:130, halign:'center'},
				{field:'ket_satuan', title:'Uom', width:60, halign:'center'}
			]],
			onDblClickRow:function(id, row){
				var total = $('#dg_non').datagrid('getData').total;
				var idxfield=0;
				var i = 0;
				var count = 0;
				if (parseInt(total)==0) {
					idxfield=total;
				}else{
					idxfield=total+1;
					for (i=0; i < total; i++) {
						//alert(i);
						var part = $('#dg_non').datagrid('getData').rows[i].part_no;
						//alert(part);
						if (part == row.brg_codebarang) {
							count++;
						};
					};
				}

				if (count>0) {
					$.messager.alert('Information','Part present','warning');
				}else{
					$('#dg_non').datagrid('insertRow',{
						index: idxfield,	// index start with 0
						row: {
							serial_no : row.serial_no,
							part_no : row.brg_codebarang,
							part_name : row.nama_barang,
							uom : row.ket_satuan,
							qty : 0,
							remarks : '' 
						}
					});
					//alert(idxfield);
					$('#dg_non').datagrid('beginEdit', total);
				}
			}
		});

		function viewDo(){
			$('#dlg').dialog('open').dialog('setTitle','Create Delivery Order');
			//document.getElementById('text').value="http://mynesia.com";
			makeCode();
		}
		$('#dg_non').datagrid({
			url : '',
			//toolbar:'#toolbar',
			singleSelect: true,
			//pagination: true,
			rownumbers: true,
			//remoteFilter: true,
			sortName: '-',
			sortOrder: 'asc',
			columns: [[
				{field: 'serial_no', title: 'Serial No', halign:'center', width:90, sortable: true},
				{field: 'part_no', title: 'Part No', halign:'center', width:90, sortable: true},
				{field: 'part_name', title: 'Part Name', halign:'center', width:100, sortable: true},
				{field: 'uom', title: 'UOM', halign:'center', width:80, sortable: true},
				{field: 'qty', title: 'Qty', halign:'center', align:'right', width:80, sortable: true, editor:{type:'numberbox',options:{precision:2, groupSeparator:','}}},
				{field: 'remarks', title: 'remarks', halign:'center', width:80, sortable: true, editor:{type:'textbox'}}
			]]

		});

		function saveDo(){
			$.get('json/json_delivery_no.php').done(function(res){
				var total = $('#dg').datagrid('getData').total;
				var totcek=0;
				var dono = 'DO'+'<?=date('Ym')?>'+res[0].kode;
				//alert(dono);
				for (i = 0; i < total; i++) {
					$('#dg').datagrid('endEdit', i);
					var ck = $('#dg').datagrid('getData').rows[i].ck;
					var scn = $('#dg').datagrid('getData').rows[i].scan;
					
					//alert(ck+approval_sts+scn);
					//var cek=$(ck.target);
					if (ck=='TRUE') {
						status_cek='TRUE';
						$.post('create_do_customer_savedo.php',{
							do_no: dono,
							scdl_date: $('#dg').datagrid('getData').rows[i].dos_deldate,
							serial_no: $('#dg').datagrid('getData').rows[i].serial_no,
							part_no: $('#dg').datagrid('getData').rows[i].dos_partname,
							qty: $('#dg').datagrid('getData').rows[i].dos_qtyorder.replace(/,/g,''),
							stock_whs: $('#dg').datagrid('getData').rows[i].stock.replace(/,/g,''),
							customer: $('#dg').datagrid('getData').rows[i].kode_customer,
							so_no: $('#dg').datagrid('getData').rows[i].soc_docno,
							kategori: $('#dg').datagrid('getData').rows[i].soc_kategori,
							subkategori: $('#dg').datagrid('getData').rows[i].kode_subkategori,
							remarks: $('#dg').datagrid('getData').rows[i].soc_remarks,
							whs_type: $('#dg').datagrid('getData').rows[i].soc_type,
							trans_type: $('#dg').datagrid('getData').rows[i].trans_type,
							descript: $('#dg').datagrid('getData').rows[i].descript,
							id_delivery: $('#dg').datagrid('getData').rows[i].id_delivery,
							scan: scn,
							notes: $('#notes').textbox('getValue'),
							ref: $('#dn_no').combobox('getValue'),
							id:totcek+1,
							approval_sts: approval_sts
						}).done(function(res){
							//alert(res);
							$('#dlg_do').dialog('close');
							$('#dg').datagrid('reload');
						});
						totcek++;
					}else{
						status_cek='FALSE';
					}	
				};
				$.messager.alert('Information', totcek+' Data has been saved <br/> with DO No. '+dono, 'info');
			});
		}

		function createDo(){
			var date = new Date();
			var total = $('#dg').datagrid('getData').total;

			

			//var serverDate = <?=date('Y-m-d H:i:s'); ?>
			
			// var jam = ("0" + date.getHours()).slice(-2)+':'+("0" + date.getMinutes()).slice(-2)+':'+("0" + date.getSeconds()).slice(-2);
			// var tanggal = date.getFullYear()+'-'+("0" + (date.getMonth() + 1)).slice(-2)+'-'+("0" + date.getDate()).slice(-2)+' '+$('#demo').text();
			// alert(serverDate);
			$('#notes').textbox('setValue', '');
			$('#dn_no').combobox('setValue', '');
			$('#dn_no').combobox('reload',{
				url : 'json/json_bookdn.php'
			})
			if (total >0) {
				$.ajax({
					type: 'GET',
					url: 'json/json_delivery_no.php',
					data: { kode:'kode' },
					success: function(data){
						//$('#do_no').textbox('setValue','DO'+'<?=date('Ym')?>'+data[0].kode);
					}
				});
				$('#dlg_do').dialog('open').dialog('setTitle','Create Delivery Order');
			}else{
				$.messager.alert('Information', 'Data empty', 'info');
			}
		}

	function createnonDo(){
		$('#dlg_non').dialog('open').dialog('setTitle','Create Delivery Non Sales');
		$('#dg_non').datagrid('loadData',[]);
		$('#do_date').datebox('setValue', '<?=date('Y-m-d')?>');
		$('#non_kategori').combobox('setValue', '');
		$('#whs_type').combobox('setValue', '');
		$('#t_type').combogrid('setValue', '');
		$('#customer').combobox('setValue', '');
		$('#t_type').combogrid('setText', '');
		$('#customer').combobox('setText', '');
		$('#att').combobox('setValue', '');
		$('#scn_sts').combobox('setValue', 'SCAN');
		$('#subcatnon').hide();
	}
	
	function filterData(){
		//$.messager.progress();
		var ck_cus = 'false';
		var ck_cat = 'false';
		t_qty = 0;
		if ($('#ck_cat').attr('checked')) {
			ck_cat = 'true';
		};
		if ($('#ck_cus').attr('checked')) {
			ck_cus = 'true';
		};

		$('#dg').datagrid('load',{
			periode_awal: $('#s_periode_awal').datebox('getValue'),
			periode_akhir: $('#s_periode_akhir').datebox('getValue'),
			kode_customer: $('#kode_customer').combobox('getValue'),
			ck_cus: ck_cus,
			kategori: $('#jenis_kategori').combobox('getValue'),
			subcategory: $('#subcategory').combobox('getValue'),
			ck_cat: ck_cat
		});
	}
	$('#ck_all').change(function(){
		var total = $('#dg').datagrid('getData').total;
		if ($(this).is(':checked')) {
			//alert('coming soon!');
			
			var qtot = 0;
		    for(i=0;i<total;++i){
		    	$('#dg').datagrid('beginEdit', i);
		    	var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
		    	var qtytot = parseFloat($('#dg').datagrid('getData').rows[i].dos_qtyorder.replace(/,/g,''));
				$(data.target).prop('checked',true);

				qtot = qtot + qtytot;
				$('#t_qty').text(qtot);
				$('#dg').datagrid('endEdit', i);
		    }	
		}else{
			//alert('coming soon!');
			for(i=0;i<total;++i){
		    	$('#dg').datagrid('beginEdit', i);
		    	var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
				$(data.target).prop('checked',false);
				$('#t_qty').text(0);
				$('#dg').datagrid('endEdit', i);
		    }
		}
	});

	var lastdate='';
	var lastcus = '';
	var totc = 0;
	var approval_sts = 'false';
	var t_qty = 0;
/*
	$(function(){
		//alert();
	});
*/
	$('#dg').datagrid({
		url:'create_do_customer_getso.php',
		toolbar:'#toolbar',
		//fitColumns: true,
		singleSelect: true,
		//pagination: true,
		rownumbers: true,
		//remoteFilter: true,
		sortName: '-',
		//selectOnCheck: false,
		sortOrder: 'asc',
		frozenColumns:[[
			{field: 'ck', title: '<br>', align:'center', hidden:false, width: 30, sortable: true, 
			editor: {type:'checkbox', options:{disable:true, on: 'TRUE',off: 'FALSE'}}},
			{field: 'dos_deldate', title: 'Schedule<br/>Deliv. Date', halign:'center', width: 80, sortable: true},
			{field: 'serial_no', title: 'Serial No', halign:'center', width: 100, sortable: true},
			{field: 'dos_partname', title: 'Part No', halign:'center', width: 100, sortable: true},
			{field: 'nama_barang', title: 'Part Name', halign:'center', width: 150, sortable: true},
		]],
		columns: [[
			//{field: 'ck', checkbox:'true'},
			{field: 'id_delivery', title: 'id Delivery', halign:'center', width: 80, sortable: true, hidden:true},
			{field: 'soc_kategori', title: 'KodeCategory', halign:'center', width: 80, sortable: true, hidden:true},
			{field: 'kode_subkategori', title: 'KodeSubCategory', halign:'center', width: 80, sortable: true, hidden:true},
			{field: 'ket_kategori', title: 'Category', halign:'center', width: 80, sortable: true},
			{field: 'nama_subkategori', title: 'Sub Category', halign:'center', width: 80, sortable: true},
			{field: 'ket_satuan', title: 'Uom', halign:'center', width: 70, sortable: true},
			{field: 'qty_', title: 'Qty', halign:'center', align: 'right', width: 70, sortable: true, hidden:true},
			{field: 'dos_qtyorder', title: 'Qty', halign:'center', align: 'right', width: 70, sortable: true, editor:{type:'numberbox',options:{precision:2, groupSeparator:',', value:'12.34'}}},
			{field: 'stock', title: 'Stock WHS', halign:'center', align: 'right', width: 90, sortable: true},
			{field: 'av_stock', title: 'Available Stock', halign:'center', align: 'right', width: 90, sortable: true},
			{field: 'kode_customer', title: 'Kode Customer', halign:'center', width: 120, sortable: true, hidden:true},
			{field: 'soc_docno', title: 'SO No.', halign:'center', width: 100, sortable: true},
			{field: 'nama_customer', title: 'Destination', halign:'center', width: 120, sortable: true},
			{field: 'soc_remarks', title: 'Remarks', halign:'center', width: 100, sortable: true, editor:{type:'textbox'}},
			{field: 'soc_type', title: 'WHS Type', halign:'center', width: 100, sortable: true},
			{field: 'trans_type', title: 'Transaction <br>Type', halign:'center', width: 100, sortable: true},
			{field: 'dos_lastdate', title: 'Last Update', halign:'center', width: 100, sortable: true},
			{field: 'dos_userinput', title: 'User', halign:'center', width: 100, sortable: true},
			{field: 'descript', title: 'Description', halign:'center', width: 100, sortable: true},
			{field: 'scan', title: 'Scan Entry', halign:'center', width: 70, sortable: true,
			editor:{type:'combobox',
					options:{
						url:'json/json_scan.json',
						method:'get',
						panelHeight: 'auto',
						valueField:'scan',
						textField:'scan'
					}}},

			
			{field: 'notes', title: 'Note', halign:'center', width: 100, sortable: true}
		]],
		onLoadSuccess:function(id, row){
			var total = $('#dg').datagrid('getData').total;
			totc = $('#dg').datagrid('getData').total;
			//alert(totc);
			for (var i = 0; i <total; i++) {
				//$('#dg').datagrid('beginEdit',i);
				//var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
				//$(data.target).prop('disabled', true);
				// var part = $('#dg').datagrid('getData').rows[i].part_no;
				// var data = $('#dg').datagrid('getEditor',{index:i, field:'supplier_name'});
				// $(data.target).combobox('reload', 'json/json_srcsupplier.php?part='+part);
			};
		},
		onClickRow: function(id, row){
			$('#dg').datagrid('getData').rows[id].dos_qtyorder.replace(/,/g,'');
			$('#dg').datagrid('beginEdit',id);
			/*$('#dg').datagrid('enableCellSelecting').datagrid('gotoCell', {
                index: id,
                field: 'ck'
            });*/

			var editors = $(this).datagrid('getEditors', id);
			var qty = $(editors[1].target);
			qty.numberbox({
				onChange:function(rec){
					var qty_ = $('#dg').datagrid('getData').rows[id].qty_;
					//alert(qty_);
					var nilai = rec;
					if (parseFloat(rec)>parseFloat(qty_)) {
						//alert("Kelebihan");
						nilai = qty_;

						$.messager.confirm('Confirm','Stock over! <br/>masukan nilai yg benar!',function(r){
							if (r){
								qty.numberbox('setValue', 0);
							}else{
								qty.numberbox('setValue', nilai);
							}
							//qty.numberbox('setValue', nilai);
						});
					};
					//$(this).numberbox('setValue', 0);
				}
			});
			var data = $('#dg').datagrid('getEditor',{index:id, field:'ck'});
			$(data.target).prop('disabled', true);
			var total = $('#dg').datagrid('getData').total;
			var idx = $('#dg').datagrid('getData').rows[id].dos_deldate;
			var ck = $('#dg').datagrid('getEditor',{index:id,field:'ck'});
			var stok = $('#dg').datagrid('getData').rows[id].stock.replace(/,/g,'');
			var cus = $('#dg').datagrid('getData').rows[id].kode_customer;
			var dgqty = parseInt($('#dg').datagrid('getData').rows[id].dos_qtyorder.replace(/,/g,''));
			//alert(ck);
			/*if ($(ck.target).attr('checked')) {
				$(ck.target).prop('checked', false);
				totc=totc+1;
			}else{
				$(ck.target).prop('checked', true);
				totc=totc-1;
			}*/
			//$(this).datagrid({selectOnCheck:$(this).is(':checked')});
			//$(this).is(':checked');
			
/*
			if (total==totc) {
				if ($(ck.target).attr('checked')) {
					$(ck.target).prop('checked', false);
					totc=totc+1;
				}else{
					$(ck.target).prop('checked', true);
					totc=totc-1;
					lastdate=idx;
				}
			}else{
				if (idx==lastdate) {
					//alert('sama');
					if ($(ck.target).attr('checked')) {
						$(ck.target).prop('checked', false);
						totc=totc+1;
					}else{
						$(ck.target).prop('checked', true);
						totc=totc-1;
						lastdate=idx;
					}
				}else{
					$(ck.target).prop('checked', false);
					$.messager.alert('Information','Schedule Deliv. Date not Same','error');
					//lastdate=idx;
					
				}
			}*/
			//alert(stok);
			//alert(lastdate+' '+idx+' '+lastcus+' '+cus+' '+total+' '+totc);
			if (parseInt(stok)>0) {
				if (total==totc) {
					if ($(ck.target).attr('checked')) {
						$(ck.target).prop('checked', false);
						totc=totc+1;
						//alert(totc);
						/*lastdate="";
						lastcus="";*/
						t_qty = t_qty - dgqty;
						$('#t_qty').text(t_qty);
					}else{
						$(ck.target).prop('checked', true);
						totc=totc-1;
						lastdate=idx;
						lastcus=cus;
						t_qty = t_qty + dgqty;
						$('#t_qty').text(t_qty);
					}
				}else if ((total-1)==totc) {
					if (lastdate==idx && lastcus==cus) {
						if ($(ck.target).attr('checked')) {
							$(ck.target).prop('checked', false);
							totc=totc+1;
							lastdate="";
							lastcus="";
							t_qty = t_qty - dgqty;
							$('#t_qty').text(t_qty);
						}else{
							$(ck.target).prop('checked', true);
							totc=totc-1;
							lastdate=idx;
							lastcus=cus;
							t_qty = t_qty + dgqty;
							$('#t_qty').text(t_qty);
						}
					}else{
						$(ck.target).prop('checked', false);
						$.messager.alert('Information','Schedule Deliv. Date not Same','error');
					}
				}else{
					
					if (lastdate==idx && lastcus==cus) {
						if ($(ck.target).attr('checked')) {
							$(ck.target).prop('checked', false);
							totc=totc+1;
							t_qty = t_qty - dgqty;
							$('#t_qty').text(t_qty);
							/*lastdate="";
							lastcus="";*/
						}else{
							$(ck.target).prop('checked', true);
							totc=totc-1;
							lastdate=idx;
							lastcus=cus;
							t_qty = t_qty + dgqty;
							$('#t_qty').text(t_qty);
						}
					}else{
						$(ck.target).prop('checked', false);
						$.messager.alert('Information','Schedule Deliv. Date not Same','error');
					}
				}
			}else{
				//$(ck.target).prop('checked', false);
				//$.messager.alert('Information','stock empty','error');
				if (total==totc) {
					if ($(ck.target).attr('checked')) {
						//alert('checked');
						$(ck.target).prop('checked', false);
						totc=totc+1;
						t_qty = t_qty - dgqty;
						$('#t_qty').text(t_qty);
						/*lastdate="";
						lastcus="";*/
					}else{
						$.messager.confirm('Confirm','Stock empty! <br/>Are you sure want to create DO?',function(r){
							if (r){
								$(ck.target).prop('checked', true);
								totc=totc-1;
								lastdate=idx;
								lastcus=cus;
								approval_sts = 'true';
								t_qty = t_qty + dgqty;
								$('#t_qty').text(t_qty);
							}
						});
					}
				}else if ((total-1)==totc) {
					if (lastdate==idx && lastcus==cus) 
					{
						if ($(ck.target).attr('checked')) {
							//alert('checked');
							$(ck.target).prop('checked', false);
							totc=totc+1;
							t_qty = t_qty - dgqty;
							$('#t_qty').text(t_qty);
							lastdate="";
							lastcus="";
						}else{
							$.messager.confirm('Confirm','Stock empty! <br/>Are you sure want to create DO?',function(r){
								if (r){
									$(ck.target).prop('checked', true);
									totc=totc-1;
									lastdate=idx;
									lastcus=cus;
									approval_sts = 'true';
									t_qty = t_qty + dgqty;
									$('#t_qty').text(t_qty);
								}
							});
						}
					}else{
						$(ck.target).prop('checked', false);
						$.messager.alert('Information','Schedule Delivery Date not Same','error');
					}
				}else{
					
					if (lastdate==idx && lastcus==cus) 
					{
						if ($(ck.target).attr('checked')) {
							//alert('checked');
							$(ck.target).prop('checked', false);
							totc=totc+1;
							t_qty = t_qty - dgqty;
							$('#t_qty').text(t_qty);
							/*lastdate="";
							lastcus="";*/
						}else{
							$.messager.confirm('Confirm','Stock empty! <br/>Are you sure want to create DO?',function(r){
								if (r){
									$(ck.target).prop('checked', true);
									totc=totc-1;
									lastdate=idx;
									lastcus=cus;
									approval_sts = 'true';
									t_qty = t_qty + dgqty;
									$('#t_qty').text(t_qty);
								}
							});
						}
					}else{
						$(ck.target).prop('checked', false);
						$.messager.alert('Information','Schedule Delivery Date not Same','error');
					}
				}
			}
			//alert(t_qty);
			//document.getElementById("t_qty").value = t_qty;
			
		}		

/*			$('#dg').datagrid({
			rowStyler: function(index,row){
				return 'background-color:#6293BB;color:#fff;';
			}
		});
*/
	});
	

		/*$('#ck_all').change(function(){
			var total = $('#dg').datagrid('getData').total; 

			if ($(this).is(':checked')) {
				alert('cek');
				
				alert(total);
			}else{
				alert('not');
			}
		});*/
	$(function(){
		// var dg = $('#dg').datagrid({
//              data: data
//          });
		// dg.datagrid('enableCellEditing').datagrid('gotoCell', {
  //           index: 0,
  //           field: 'ck'
  //       });
		$('#subcat').hide();
		$('#dg').datagrid('enableFilter');
		$('#ck_all').prop('disabled', true);
		$('#kode_customer').combobox('disable');
		$('#jenis_kategori').combobox('disable');

		$('#ck_cus').change(function(){
			var d_awal = $('#s_periode_awal').datebox('getValue');
			var d_akhir = $('#s_periode_akhir').datebox('getValue');
			if ($(this).is(':checked')) {
				$('#kode_customer').combobox('disable');
			}else{
				$('#kode_customer').combobox('enable');
			}

			if ($(this).is(':checked')) {
				$('#ck_all').prop('disabled', true);
			}else{
				if ($('#ck_cat').is(':checked')) {
					//alert();						
					$('#ck_all').prop('disabled', true);
				}else{
					if (d_awal==d_akhir && d_awal !='' && d_akhir !='') {
						$('#ck_all').prop('disabled', false);
					}else{
						$('#ck_all').prop('disabled', true);
					}
				}
			}
		});

		$('#ck_cat').change(function(){
			var d_awal = $('#s_periode_awal').datebox('getValue');
			var d_akhir = $('#s_periode_akhir').datebox('getValue');
			if ($(this).is(':checked')) {
				$('#jenis_kategori').combobox('disable');
				$('#subcategory').combobox('disable');
				$('#subcat').hide();
			}else{
				$('#jenis_kategori').combobox('enable');
				$('#subcategory').combobox('enable');
			}

			if ($(this).is(':checked') ) {
				$('#ck_all').prop('disabled', true);
			}else{
				if ($('#ck_cus').is(':checked')) {
					$('#ck_all').prop('disabled', true);
				}else{
					if (d_awal==d_akhir && d_awal !='' && d_akhir !='') {
						$('#ck_all').prop('disabled', false);
					}else{
						$('#ck_all').prop('disabled', true);
					}
				}
			}
		});
		
		
	});


</script>
	
</body>
</html>
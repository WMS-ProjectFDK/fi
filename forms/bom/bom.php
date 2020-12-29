<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BILL OF MATERIAL</title>
<link rel="icon" type="image/png" href="../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
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
<body>
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>
    <!-- ADD -->
	<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:350px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	   <form id="ff" method="post" novalidate>	
		<div class="fitem">
				<span style="width:110px;display:inline-block;">Item No.</span>
				<select style="width:330px;" name="cmb_item_no_edit" id="cmb_item_no_add" class="easyui-combobox" data-options=" url:'../json/json_item_fg.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
					onSelect:function(rec){
						var integer = parseInt(rec.level_no, 10);
						$('#level_no_add').textbox('setValue', integer + 1);
				}"></select>
			
				<span style="width:100px;display:inline-block;">Level No.</span>
				<input style="width:50px;" name="po_no_edit" id="level_no_add" class="easyui-textbox" disabled=""/>
		</div>
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:235px;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_add" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="add_po_add" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_add()">ADD MATERIAL</a>
			<a href="javascript:void(0)" id="remove_po_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_bom_item()">REMOVE MATERIAL</a>
		</div>
		<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem" data-options="modal:true">
			<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="toolbar_addItem" style="padding: 5px 5px;">
			<span style="width:80px;display:inline-block;">Item No</span>
		
			<input style="width:200px;height: 20px;border-radius: 4px;" name="s_item_add" id="s_item_add" onkeypress="sch_item_add(event)"/>
			<a href="javascript:void(0)" iconCls='icon-search' class="easyui-linkbutton" onclick="search_item_add()">SEARCH ITEM</a>
		</div>
		<div id="dlg_prf_add" class="easyui-dialog" style="width: 880px;height: 300px;" closed="true" buttons="#dlg-buttons-prf_add" data-options="modal:true">
			<table id="dg_prf_add" class="easyui-datagrid" style="width:100%;height:200px;border-radius: 10px;" rownumbers="true" ></table>
		</div>
		<div id="dlg-buttons-prf_add">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="select_prf()" style="width:90px">SELECT</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_prf_add').dialog('close')" style="width:90px">Cancel</a>	
		</div>	

		<div id="dlg_remark_add" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
			<table id="dg_remark_add" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
		</div>

		<div id="dlg-buttons-add">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="saveBOM()" style="width:90px; height: 30px;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END ADD -->

	 <!-- EDIT -->
	 <div id='dlg_edit' class="easyui-dialog" style="width:1100px;height:380px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
	   <form id="ffe" method="post" novalidate>	
	   <div class="fitem" hidden="true">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<input style="width:100px;" name="po_no_edit" id="item_no_edit" class="easyui-textbox" disabled=""/>
			<span style="width:100px;display:inline-block;">Level No.</span>
			<input style="width:50px;" name="po_no_edit" id="level_no_edit" class="easyui-textbox" disabled=""/>
		</div>	
		
		<div style="clear:both;margin-bottom:10px;"></div>
		<table align="center" id="dg_edit" class="easyui-datagrid" toolbar="#toolbar_edit" style="width:1075px;height:auto;padding:10px 10px; margin:5px;"></table>
		<div id="toolbar_edit" style="padding: 5px 5px;">
			<a href="javascript:void(0)" id="add_po_add" iconCls='icon-add' class="easyui-linkbutton" onclick="add_item_edit()">ADD MATERIAL</a>
			<a href="javascript:void(0)" id="remove_po_add" iconCls='icon-cancel' class="easyui-linkbutton" onclick="remove_bom_item_edit()">REMOVE MATERIAL</a>
		</div>
		<div id="dlg_addItem" class="easyui-dialog" style="width: 880px;height: 270px;" closed="true" buttons="#dlg-buttons-addItem" data-options="modal:true">
			<table id="dg_addItem" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
		<div id="dlg_prf_add" class="easyui-dialog" style="width: 880px;height: 300px;" closed="true" buttons="#dlg-buttons-prf_add" data-options="modal:true">
			<table id="dg_prf_add" class="easyui-datagrid" style="width:100%;height:200px;border-radius: 10px;" rownumbers="true" ></table>
		</div>
		<div id="dlg-buttons-prf_add">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="select_prf()" style="width:90px">SELECT</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_prf_add').dialog('close')" style="width:90px">Cancel</a>	
		</div>	

		<div id="dlg_remark_add" class="easyui-dialog" style="width: 450px;height: 250px;" closed="true" data-options="modal:true">
			<table id="dg_remark_add" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
		</div>

		<div id="dlg-buttons-edit">
			<input class="easyui-linkbutton c6" iconCls="icon-ok" type="submit" value="Save" onclick="saveEditBOM()" style="width:90px; height: 30px;">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
		</div>
	  </form>
	</div>
	<!-- END EDIT -->
	 
	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:auto; height:45px; float:left;"><legend><span class="style3"><strong>BILL OF MATERIAL</strong></span></legend>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Item NO. [UPPER]</span>
				<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_fg.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
				<span style="width:100px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">Item NO. [LOWER]</span>
				<select style="width:330px;" name="cmb_item_no" id="cmb_item_low" class="easyui-combobox" data-options=" url:'../json/json_item_material.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_item_low" id="ck_item_low" checked="true">All</input></label>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="add" onclick="addBOM()"><i class="fa fa-plus" aria-hidden="true"></i> Add BOM</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="edit" onclick="editBOM()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit BOM</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="delete" onclick="deleteBOM()"><i class="fa fa-trash" aria-hidden="true"></i> Delete BOM</a>
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="delete" onclick="downloadBOM()"><i class="fa fa-download" aria-hidden="true"></i> Download BOM</a>
		</div>
		<div style="clear:both;"></div>
	</div>

    <div id="dlg_input" class="easyui-dialog" style="width: 300px;height: 40`0px;" closed="true" buttons="#dlg-buttons-qty" data-options="modal:true" align="center">	
		<div class="fitem">
			<span style="width:75px;display:inline-block;">BL DATE</span>
			<input style="width: 200px;" name="bl_date_datebox" id="bl_date_datebox" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
		</div>
	</div>

	<table id="dg" title="BILL OF MATERIAL" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>

	<script type="text/javascript">
		var flagTipe = "";
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

		function access_log(){
			var add = "<?=$exp[0]?>";
			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}
		}

		$(function(){
			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#cmb_item_low').combobox('disable');
			$('#ck_item_low').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_low').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_low').combobox('enable');
				};
			})

			$('#dg').datagrid({
				singleSelect:true,
			    columns:[[
                    {field:'UPPER_ITEM_NO',title:'ITEM NO.',width:55, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION',title:'DESCRIPTION',width:60, halign: 'center'},
					{field:'LEVEL_NO',title:'LEVEL_NO', width:60, halign: 'center'},
					{field:'INPUT_DATE',title:'INPUT_DATE', width:150, halign: 'center'}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'BOM Detail (No: '+row.UPPER_ITEM_NO+')',
	                	url:'get_bom_detail.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						loadMsg:'load data ...',
						height:'auto',
						fitColumns: true,
						columns:[[
							{field:'LINE_NO', hidden: true},
			                {field:'LOWER_ITEM_NO',title:'MATERIAL<br/>NO.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'DESCRIPTION', title:'MATERIAL<br/>NAME', halign:'center', width:150},
							{field:'LW_DRAWING_NO', title:'DRAWING<br/>NO.', width:80, halign: 'center'},
							{field:'MAK', title:'MAKER', width:80, halign: 'center'},
							{field:'QTY', title:'QUANTITY<br/>BASE', halign:'center', align:'right', width:40},
			                {field:'QUANTITY_BASE', hidden: true},
			                {field:'QUANTITY', hidden: true},
			                {field:'FAILURE_RATE', title:'FAILURE<br/>RATE', halign:'center', align:'right', width:70}
						]],
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						}
	                });
				}
				
			})
		})
        
		var get_url='';

		function filterData(){
			var ck_item_no='false';
			var ck_item_low='false';
			flag=0;

			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag+=1;
			}

			if($('#ck_item_low').attr("checked")){
				ck_item_low='true';
				flag+=1;
			}

			if (flag == 2){
				$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			}
			
			$('#dg').datagrid('load', {
				item_no: $('#cmb_item_no').combobox('getValue'),
				ck_item_no: ck_item_no,
				cmb_item_low: $('#cmb_item_low').combobox('getValue'),
				ck_item_low: ck_item_low
			});

			console.log('get_bom.php?item_no='+$('#cmb_item_no').combobox('getValue')+
				'&ck_item_no='+ck_item_no+
				'&cmb_item_low='+$('#cmb_item_low').combobox('getValue')+
				'&ck_item_low='+ck_item_low
			)

			get_url = '?item_no='+$('#cmb_item_no').combobox('getValue')+
						'&ck_item_no='+ck_item_no+
						'&cmb_item_low='+$('#cmb_item_low').combobox('getValue')+
						'&ck_item_low='+ck_item_low;

			$('#dg').datagrid({
				url:'get_bom.php'
			})

			$('#dg').datagrid('enableFilter');
		}

		function deleteBOM(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
						    title:'Please waiting',
						    msg:'removing data...'
						});
						// console.log('delete_bom.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO)
						$.post('delete_bom.php',{item_no: row.UPPER_ITEM_NO,level_no: row.LEVEL_NO},function(result){
							if (result.success){
	                            $('#dg').datagrid('reload');
	                            $.messager.progress('close');
	                        }else{
	                            $.messager.show({
	                                title: 'Error',
	                                msg: result.errorMsg
	                            });
	                            $.messager.progress('close');
	                        }
						},'json');
					}
				});
			}else{
				$.messager.show({title: 'BOM DELETE',msg:'Data Not select'});
			}
		}

		function downloadBOM(){
			if (get_url != ''){
				console.log('bom_download_proses.php'+get_url);
				$.post('bom_download_proses.php'+get_url,{}).done(function(res){
					download_excel();
				});
			}
		}

		function download_excel(){
			url_download = 'bom_download_xls.php';
			window.open(url_download);
		}

		function addBOM(){
			$('#dlg_add').dialog('open').dialog('setTitle','Create BOM');
			$('#level_no_add').textbox('setValue','');
			$('#cmb_item_no_add').combobox('setValue','');
			$('#cmb_item_no_add').combobox({
				editable: true
			});
			$('#dg_add').datagrid('loadData',[]);
			$('#dg_add').datagrid({
			    singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
				    {field:'ITEM', title:'ITEM NAME', width:100, halign: 'center'},//, hidden: true},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
				    {field:'UOM_Q', title:'UNIT', width: 50, halign: 'center'},
					{field:'QUANTITY_BASE',title:'QTY BASE',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
					{field:'QUANTITY',title:'QTY',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
					{field:'FAILURE_RATE',title:'FAILURE RATE',width:100,halign:'center', align: 'right'	,editor:{type:'numberbox',options:{precision:2}}}
			    ]],
			    onClickRow:function(row){
			    	$(this).datagrid('beginEdit', row);
			    }
			});
		}

		function add_item_add(){
			$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
			$('#cmb_search').combobox('setValue','ITEM_NO');
			$('#dg_addItem').datagrid({
				fitColumns: true,
				columns:[[
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'UOM_Q',title:'UOM_Q',width:250,halign:'center'}
	            ]],
	            onDblClickRow:function(id,row){
					var row = $('#dg_addItem').datagrid('getSelected');
					var t = $('#dg_add').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total+1;
						for (j=0; j < total; j++) {
							if (row.ITEM_NO==t[j].ITEM_NO ){
								count++;
							};
						};
					}
					
					if (count>0) {
					  $.messager.alert('Warning','ITEM present','warning');
					}else{
						$('#dg_add').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM,
								UOM_Q: row.UOM_Q
							}
						});
					}
				}
			});

			$('#dg_addItem').datagrid('loadData',[]);
		}

		function add_item_edit(){
			$('#dlg_addItem').dialog('open').dialog('setTitle','Search Item');
			$('#cmb_search').combobox('setValue','ITEM_NO');
			$('#dg_addItem').datagrid({
				fitColumns: true,
				columns:[[
	                {field:'ITEM_NO',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'ITEM',title:'ITEM',width:100,halign:'center'},
	                {field:'DESCRIPTION',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'UOM_Q',title:'UOM_Q',width:250,halign:'center'}
	            ]],
	            onDblClickRow:function(id,row){
					var row = $('#dg_addItem').datagrid('getSelected');
					var t = $('#dg_edit').datagrid('getRows');
					var total = t.length;
				   	var idxfield=0;
				   	var i = 0;
				   	var count = 0;
					if (parseInt(total) == 0) {
						idxfield=0;
					}else{
						idxfield=total+1;
						for (j=0; j < total; j++) {
							if (row.ITEM_NO==t[j].ITEM_NO ){
								count++;
							};
						};
					}
					
					if (count>0) {
					  $.messager.alert('Warning','ITEM present','warning');
					}else{
						$('#dg_edit').datagrid('insertRow',{
							index: idxfield,
							row: {
								ITEM_NO: row.ITEM_NO,
								DESCRIPTION: row.DESCRIPTION,
								ITEM: row.ITEM,
								UOM_Q: row.UOM_Q
							}
						});
					}
				}
			});
			$('#dg_addItem').datagrid('loadData',[]);
		}

		function saveEditBOM(){
			var dataRows = [];
			var t = $('#dg_edit').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_edit').datagrid('endEdit',i);
				dataRows.push({
					upper_item_no: $('#item_no_edit').textbox('getValue'),
					level_no: $('#level_no_edit').textbox('getValue'),
					line_no: jmrow,
					lower_item_no: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
					quantity: $('#dg_edit').datagrid('getData').rows[i].QUANTITY,
					quantity_base: $('#dg_edit').datagrid('getData').rows[i].QUANTITY_BASE,
					failure_rate: $('#dg_edit').datagrid('getData').rows[i].FAILURE_RATE
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('post_bom.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function saveBOM(){
			var dataRows = [];
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);
				dataRows.push({
					upper_item_no: $('#cmb_item_no_add').combobox('getValue'),
					level_no: isNaN($('#level_no_add').textbox('getValue')) ? 0 : $('#level_no_add').textbox('getValue'),
					line_no: jmrow,
					lower_item_no: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					quantity: $('#dg_add').datagrid('getData').rows[i].QUANTITY,
					quantity_base: $('#dg_add').datagrid('getData').rows[i].QUANTITY_BASE,
					failure_rate: $('#dg_add').datagrid('getData').rows[i].FAILURE_RATE
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('post_bom.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function editBOM(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				var lvl = row.LEVEL_NO.toString();
				var item = row.UPPER_ITEM_NO.toString();
				$('#dlg_edit').dialog('open').dialog('setTitle','EDIT BOM ('+item+' - '+row.DESCRIPTION+' [LEVEL-'+lvl+'])');
				$('#item_no_edit').textbox('setValue',item);
				$('#level_no_edit').textbox('setValue',lvl);

				$('#dg_edit').datagrid({
				    url:'get_bom_detail.php?item_no='+item+'&level_no='+lvl,
				    singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
						{field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
						{field:'ITEM', title:'ITEM NAME', width:100, halign: 'center'},//, hidden: true},
						{field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
						{field:'UOM_Q', title:'UNIT', width: 50, halign: 'center'},
						{field:'MAK', title:'MAKER', width:100, halign: 'center'},
						{field:'QUANTITY_BASE',title:'QTY BASE',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
						{field:'QUANTITY',title:'QTY',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
						{field:'FAILURE_RATE',title:'FAILURE RATE',width:100,halign:'center', align: 'right'	,editor:{type:'numberbox',options:{precision:2}}}

				    ]],
				    onClickRow:function(row){
				    	$(this).datagrid('beginEdit', row);
				    }
				});
			}
		}

		function search_item_add(){
			var s_item = document.getElementById('s_item_add').value;
		
			if(s_item != ''){
				$('#dg_addItem').datagrid('load',{item_no: s_item});
				$('#dg_addItem').datagrid({url: 'get_bom_material.php',});
				document.getElementById('s_item_add').value = '';
			}
		}

		function sch_item_add(event){
			var sch_a = document.getElementById('s_item_add').value;
			var search = sch_a.toUpperCase();
			document.getElementById('s_item_add').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				search_item_add();
		    }
		}

		function search_item_edit(){
			var s_item = document.getElementById('s_item_edit').value;
	
			if(s_item != ''){
				$('#dg_addItem').datagrid('load',{item_no: s_item});
				$('#dg_addItem').datagrid({url: 'get_bom_material.php',});
				document.getElementById('s_item_edit').value = '';
			}
		}

		function sch_item_edit(event){
			var sch_a = document.getElementById('s_item_edit').value;
			var search = sch_a.toUpperCase();
			document.getElementById('s_item_edit').value = search;
			
			if(event.keyCode == 13 || event.which == 13){
				search_item_add();
			}
		}

		function remove_bom_item(){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_add").datagrid("getRowIndex", row);
						$('#dg_add').datagrid('deleteRow', idx);
					}	
				});
			}
		}

		function remove_bom_item_edit(){
			var row = $('#dg_edit').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_edit").datagrid("getRowIndex", row);
						$('#dg_edit').datagrid('deleteRow', idx);
					}	
				});
			}
		}
	</script>
</body>
</html>
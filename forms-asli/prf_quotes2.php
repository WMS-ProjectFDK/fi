<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Insert Quotation</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script>
  	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../themes/color.css" />
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
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
	<?php include ('../ico_logout.php'); ?>

	<table id="dg" title="INSERT QUOTATION" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:400px;"></table>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="new_quot()">New Quotation</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" disabled="true" onclick="edit_quot()">Edit Quotation</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="destroy_quot()">Remove Quotation</a>
        <input id="txt_search" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search Quotation...'" style="width:20%;height:22px;" disabled="true">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-filter" disabled="true" onclick="search_quot()">Search</a>
	</div>
	<!-- ADD -->
	<div id="dlg" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons">
        <form id="fm" method="POST" enctype="multipart/form-data" novalidate>
	        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>Search Item</legend>
	        	<div class="fitem" id="CLASS_QUOT_NO">
		        	<span style="width:100px;display:inline-block;">QUOTATION No.</span>
		        	<input style="width:180px;" name="QUOT_NO" id="QUOT_NO" class="easyui-textbox" disabled=""/>
	        	</div>
	        	<div class="fitem">
	        		<input id="txt_search_item" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search item...'" style="width:180px;">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-filter" style="width: 100px;" onclick="search_item()">Search</a>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:98px;display:inline-block;">ITEM No.</span>
					<input style="width:180px;" name="ITEM_NO" id="ITEM_NO" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 1</span>
					<input type="file" id="ITEM_UPLOAD_1" name="ITEM_UPLOAD[]" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Description</span>
					<input style="width:520px;" name="ITEM_NAME" id="ITEM_NAME" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 2</span>
					<input type="file" id="ITEM_UPLOAD_2" name="ITEM_UPLOAD[]" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Specipication</span>
					<input style="width:520px;" name="ITEM_SPEC" id="ITEM_SPEC" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 3</span>
					<input type="file" id="ITEM_UPLOAD_3" name="ITEM_UPLOAD[]" style="width: 250px;">
				</div>
	        </fieldset>
	    </form>
	    <table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:200px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>
        <div id="toolbar_add">
        	<input id="txt_search_vendor" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search Vendor...'" style="width:20%;height:22px;">
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-filter" style="width: 100px;" onclick="search_vendor()">Search</a>
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="destroy_vendor()">Delete Vendor</a>
		</div>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_quot()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- SEARCH ITEM -->
    <div id="dlg_search_item" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true">
		<table id="dg_search_item" class="easyui-datagrid" style="width:100%;height:100%;"></table>
	</div>

    <!-- SEARCH VENDOR -->
    <div id="dlg_search_vendor" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true">
    	<table id="dg_search_vendor" class="easyui-datagrid" style="width:100%;" rownumbers="true" fitColumns="true" singleSelect="true"></table>
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

		/*function search_item(){
			var s = $('#txt_search').textbox('getValue');
			//alert (s);
			$('#dg').datagrid('load',{find: s});
		}*/

		$('#CLASS_QUOT_NO').hide();

		$('#dg').datagrid({
			url: 'prf_quotes_get.php',
			rownumbers:'true',
			singleSelect:'true',
			fitColumns: 'true',
			columns:[[
				{field:'ID_QUOT',title:'QUOTATION<br>No.', halign:'center', width:120},
				{field:'ITEM_NO',title:'ITEM', halign:'center', width:120},
                {field:'ITEM_NAME', title:'DESCRIPTION', halign:'center', width:250},
                {field:'DEPARTEMENT', title:'DEPARTEMENT', halign:'center', width:120},
                {field:'APPROVED', title:'STATUS', halign:'center', align:'center', width:120},
                {field:'NOTE', title:'NOTE', halign:'center', align:"center", width:250}
			]]
		});

		$('#dg_add').datagrid({
			columns: [[
				{field:'VENDOR',title:'VENDOR No.', halign:'center', width:80},
				{field:'DESC_VENDOR',title:'VENDOR NAME', halign:'center', width:120},
				{field:'PRICE',title:'PRICE (IDR)', halign:'center', width:60, editor:{type:'textbox'}}
			]],
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    }
		});

		$('#dg_search_item').datagrid({
			rownumbers:'true',
			fitColumns: 'true',
			singleSelect:'true',
			columns:[[
				{field:'ITEM_NO',title:'Item No.', halign:'center', width:120},
				{field:'ITEM_NAME',title:'DESCRIPTION', halign:'center', width:250}
			]],
			onClickRow:function(id,row){
				$('#ITEM_NO').textbox('setValue',row.ITEM_NO);
				$('#ITEM_NAME').textbox('setValue',row.ITEM_NAME);
				$('#ITEM_SPEC').textbox('setValue',row.ITEM_SPEC);
			}
		});

		$('#dg_search_vendor').datagrid({
			rownumbers:'true',
			fitColumns: 'true',
			singleSelect:'true',
			columns:[[
				{field:'COMPANY_CODE',title:'VENDOR No.', halign:'center', width:120},
				{field:'COMPANY',title:'DESCRIPTION', halign:'center', width:250},
				{field:'PRICE', hidden:true}
			]],
			onDblClickRow:function(id,row){
				var t = $('#dg_add').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
			   	var i = 0;
			   	var count = 0;
				if (parseInt(total) == 0) {
					idxfield=total;
				}else{
					idxfield=total+1;
					for (i=0; i < total; i++) {
						//alert(i);
						var item = $('#dg_add').datagrid('getData').rows[i].VENDOR;
						//alert(item);
						if (item == row.COMPANY_CODE) {
							count++;
						};
					};
				}

				//alert('count = '+count);
				if (count>0) {
					$.messager.alert('Information','Vendor present','warning');
				}else{
					$('#dg_add').datagrid('insertRow',{
						index: idxfield,	// index start with 0
						row: {
							VENDOR: row.COMPANY_CODE,
							DESC_VENDOR: row.COMPANY,
							PRICE: row.PRICE
						}
					});
				}
			}
		});

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})

		var url;
		function new_quot(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Quotation');
            $('#fm').form('clear');
            url = 'prf_item_save.php';
        }

        function search_item(){
        	var txt_s_item = $('#txt_search_item').textbox('getValue');
        	//alert (txt_s_item);
        	if(txt_s_item!=''){
        		$('#dg_search_item').datagrid('reload');
        		$('#dlg_search_item').dialog('open').dialog('center').dialog('setTitle','Search Item');
        		$('#dg_search_item').datagrid('load',{
        			itemno: $('#txt_search_item').textbox('getValue')
        		});
				$('#dg_search_item').datagrid({
					url:'prf_quotes_search_item.php'
				});
				$('#dg_search_item').datagrid('enableFilter');
				$('#txt_search_item').textbox('setValue','');
        	}else{
        		$.messager.alert("warning","Search Item Field Can't Empty!","Warning");
        	}
        }

        function search_vendor(){
        	var txt_s_vendor = $('#txt_search_vendor').textbox('getValue');
        	//alert (txt_s_item);
        	if(txt_s_vendor!=''){
        		$('#dg_search_vendor').datagrid('reload');
        		$('#dlg_search_vendor').dialog('open').dialog('center').dialog('setTitle','Search Vendor');
        		$('#dg_search_vendor').datagrid('load',{
        			vendorno: $('#txt_search_vendor').textbox('getValue')
        		});
				$('#dg_search_vendor').datagrid({
					url:'prf_quotes_search_vendor.php'
				});
				$('#dg_search_vendor').datagrid('enableFilter');
				$('#txt_search_vendor').textbox('setValue','');
        	}else{
        		$.messager.alert("warning","Search vendor Field Can't Empty!","Warning");
        	}
        }

        function destroy_vendor(){
        	var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				var idx = $("#dg_add").datagrid("getRowIndex", row);
				$('#dg_add').datagrid('deleteRow', idx);
			}
        }

        function simpan(){
        	var item_no = $('#ITEM_NO').textbox('getValue');
        	if(item_no!=''){
        		var t = $('#dg_add').datagrid('getRows');
				var total = t.length;
				for(i=0;i<total;i++){
					$('#dg_add').datagrid('endEdit',i);
					$.post('prf_quotes_save2.php',{
						quot: $('#QUOT_NO').textbox('getValue'),
						vndr: $('#dg_add').datagrid('getData').rows[i].VENDOR,
						pric: $('#dg_add').datagrid('getData').rows[i].PRICE
					}).done(function(res){
						alert(res);
					})
				}
        	}
        }

        function save_quot(){
        	var url='';
			$.ajax({
				type: 'GET',
				url: 'json/json_quotation.php',
				data: { kode:'kode' },
				success: function(data){
					$('#QUOT_NO').textbox('setValue', data[0].kode);
					save_file();
					//simpan();
				}
			});
		}

		function save_file(){
			$('#fm').form('submit',{
				url: 'prf_quotes_save.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					//alert(result);
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					}else{
						$('#dlg').dialog('close');
						$('#dg').datagrid('reload');
					}
				}
			});
		}

        /*function destroy_item(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy Item '+row.ITEM_NO+'?',function(r){
                    if (r){
                        $.post('prf_item_destroy.php',{id:row.ITEM_NO},function(result){
                            //alert(result);
                            if (result.success){
                                $('#dg').datagrid('reload');    // reload the user data
                            }else{
                                $.messager.show({    // show error message
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        },'json');
                    }
                });
            }
        }*/
	</script>
    </body>
    </html>

    <!-- 
	<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Name</span>
				<input style="width:522px;" name="ITEM_NAME" id="ITEM_NAME" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">UoM</span>
				<select style="width:180px;" name="ITEM_UOM" id="ITEM_UOM" class="easyui-combobox" data-options=" url:'json/json_uom.php', method:'get', valueField:'idunit', textField:'nmunit', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Specification</span>
				<input style="width:522px;" name="ITEM_SPEC" id="ITEM_SPEC" class="easyui-textbox"/>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Currency</span>
	            <select style="width:180px;" name="ITEM_CURR" id="ITEM_CURR" class="easyui-combobox" data-options=" url:'json/json_currency.php', method:'get', valueField:'idcrc', textField:'nmcrc', panelHeight:'50px'"></select>
			</div>
			<div class="fitem">
	            <span style="width:100px;display:inline-block;">Item Type</span>
				<select style="width:180px;" name="ITEM_TYPE" id="ITEM_TYPE" class="easyui-combobox" data-options=" url:'json/json_typ.php', method:'get', valueField:'idtype', textField:'nmtype', panelHeight:'50px'" disabled=""></select>
				<span style="width:50px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Safety Stock</span>
				<input style="width:180px;" name="ITEM_SAFETY" id="ITEM_SAFETY" class="easyui-textbox" value="0"/>
				<span style="width:50px;display:inline-block;"></span>
				<input type="file" id="ITEM_UPLOAD" name="ITEM_UPLOAD" accept="image/*" style="width: 280px;" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
			</div>
     -->
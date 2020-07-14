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
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit_quot()">Edit Quotation</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="destroy_quot()">Remove Quotation</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="print_quot()">Print RFA</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" disabled="true" onclick="print_quot_dtl()">Print RFA Detail</a>
	</div>
	<!-- ADD -->
	<div id="dlg" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons" data-options="modal:true">
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>QUOTATION HEADER</legend>
        	<div class="fitem">
	        	<span style="width:110px;display:inline-block;">QUOTATION NO.</span>
	        	<input style="width:180px;" name="QUOT_NO" id="QUOT_NO" class="easyui-textbox" disabled="" />
	        	<span style="width:50px;display:inline-block;"></span>
	        	<span style="width:140px;display:inline-block;">QUOTATION DATE</span>
	        	<input style="width:100px;" name="QUOT_DATE" id="QUOT_DATE" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
	        	<span style="width:130px;display:inline-block;"></span>
	        	<a href="javascript:void(0)" style="width: 250px;" id="btn_add_header" class="easyui-linkbutton c5" iconCls="icon-ok" onclick="save_header()">ADD QUOTATION</a>
        	</div>
        </fieldset>
        <form id="fm" method="POST" enctype="multipart/form-data" novalidate>
	        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>Search Item</legend>
	        	<div class="fitem">
	        		<input id="txt_search_item" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search item...'" style="width:180px;">
					<a href="javascript:void(0)" id="btn_search_item" class="easyui-linkbutton" iconCls="icon-filter" style="width: 100px;" onclick="search_item()">Search</a>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:98px;display:inline-block;">ITEM No.</span>
					<input style="width:180px;" name="ITEM_NO" id="ITEM_NO" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 1</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_1" name="ITEM_UPLOAD_1" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Description</span>
					<input style="width:520px;" name="ITEM_NAME" id="ITEM_NAME" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 2</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_2" name="ITEM_UPLOAD_2" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Specipication</span>
					<input style="width:520px;" name="ITEM_SPEC" id="ITEM_SPEC" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 3</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_3" name="ITEM_UPLOAD_3" style="width: 250px;">
				</div>
				<div class="fitem" align="left">
					<span style="width:100px;display:inline-block;">Date Request</span>
					<input style="width:100px;" name="date_req" id="date_req" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
					<div style="width:500px;display:inline-block;" id="item_fm">
						<input style="width:150px;" name="ITEM_NO2" id="ITEM_NO2" class="easyui-textbox"/>
						<input style="width:150px;" name="QUOT_NO22" id="QUOT_NO22" class="easyui-textbox"/>
						<input style="width:150px;" name="QUOT_NO2" id="QUOT_NO2" class="easyui-textbox" disabled="" />
					</div>
					<span style="width:523px;display:inline-block;"></span> 
					<a href="javascript:void(0)" id="btn_add_item" style="width: 250px;" class="easyui-linkbutton c5" iconCls="icon-ok" onclick="save_item()">ADD ITEM</a>
				</div>
	        </fieldset>
    	</form>
	    <table id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:100%;height:200px;" rownumbers="true" singleSelect="true"></table>
        <div id="toolbar_add">
	        <a href="javascript:void(0)" id="btn_delete_item" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="destroy_item()">Delete Item</a>
		</div>
    </div>
    <div id="dlg-buttons">
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_quot()" style="width:90px">Save</a> -->
        <a href="javascript:void(0)" class="easyui-linkbutton c5" iconCls="icon-remove" onclick="javascript:$('#dlg').dialog('close');$('#dg').datagrid('reload');" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- SEARCH ITEM -->
    <div id="dlg_search_item" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true" data-options="modal:true">
		<table id="dg_search_item" class="easyui-datagrid" style="width:100%;height:100%;"></table>
	</div>

	<!-- EDIT -->
	<div id="dlg_e" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons_e" data-options="modal:true">
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>QUOTATION HEADER</legend>
        	<div class="fitem">
	        	<span style="width:110px;display:inline-block;">QUOTATION NO.</span>
	        	<input style="width:180px;" name="QUOT_NO_e" id="QUOT_NO_e" class="easyui-textbox" disabled="" />
	        	<span style="width:50px;display:inline-block;"></span>
	        	<span style="width:140px;display:inline-block;">QUOTATION DATE</span>
	        	<input style="width:100px;" name="QUOT_DATE_e" id="QUOT_DATE_e" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
	        	<span style="width:130px;display:inline-block;"></span>
	        	<a href="javascript:void(0)" style="width: 250px;" id="btn_add_header_e" class="easyui-linkbutton c5" iconCls="icon-ok" onclick="save_header_e()">ADD QUOTATION</a>
        	</div>
        </fieldset>
        <form id="fm_e" method="POST" enctype="multipart/form-data" novalidate>
	        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend>Search Item</legend>
	        	<div class="fitem">
	        		<input id="txt_search_item_e" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search item...'" style="width:180px;">
					<a href="javascript:void(0)" id="btn_search_item_e" class="easyui-linkbutton" iconCls="icon-filter" style="width: 100px;" onclick="search_item_e()">Search</a>
					<span style="width:50px;display:inline-block;"></span>
					<span style="width:98px;display:inline-block;">ITEM No.</span>
					<input style="width:180px;" name="ITEM_NO_e" id="ITEM_NO_e" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 1</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_1_e" name="ITEM_UPLOAD_1_e" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Description</span>
					<input style="width:520px;" name="ITEM_NAME_e" id="ITEM_NAME_e" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 2</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_2_e" name="ITEM_UPLOAD_2_e" style="width: 250px;">
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Specipication</span>
					<input style="width:520px;" name="ITEM_SPEC_e" id="ITEM_SPEC_e" class="easyui-textbox" disabled="" />
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:80px;display:inline-block;">FILE 3</span>
					<input class="easyui-filebox" id="ITEM_UPLOAD_3_e" name="ITEM_UPLOAD_3_e" style="width: 250px;">
				</div>
				<div class="fitem" align="left">
					<span style="width:100px;display:inline-block;">Date Request</span>
					<input style="width:100px;" name="date_req_e" id="date_req_e" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="" />
					<div style="width:500px;display:inline-block;" id="item_fm_e">
						<input style="width:150px;" name="ITEM_NO2_e" id="ITEM_NO2_e" class="easyui-textbox"/>
						<input style="width:150px;" name="QUOT_NO22_e" id="QUOT_NO22_e" class="easyui-textbox"/>
						<input style="width:150px;" name="QUOT_NO2_e" id="QUOT_NO2_e" class="easyui-textbox" disabled="" />
					</div>
					<span style="width:523px;display:inline-block;"></span> 
					<a href="javascript:void(0)" id="btn_add_item_e" style="width: 250px;" class="easyui-linkbutton c5" iconCls="icon-ok" onclick="save_item_e()">ADD ITEM</a>
				</div>
	        </fieldset>
    	</form>
	    <table id="dg_add_e" class="easyui-datagrid" toolbar="#toolbar_add_e" style="width:100%;height:200px;" rownumbers="true" singleSelect="true"></table>
        <div id="toolbar_add_e">
	        <a href="javascript:void(0)" id="btn_delete_item_e" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="destroy_item_e()">Delete Item</a>
		</div>
    </div>
    <div id="dlg-buttons_e">
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_quot()" style="width:90px">Save</a> -->
        <a href="javascript:void(0)" class="easyui-linkbutton c5" iconCls="icon-remove" onclick="javascript:$('#dlg_e').dialog('close');$('#dg').datagrid('reload');" style="width:90px">Cancel</a>
    </div>
    <!-- END EDIT -->

    <!-- SEARCH ITEM -->
    <div id="dlg_search_item_e" class="easyui-dialog" style="width:500px;height:350px;padding:5px 10px" closed="true" data-options="modal:true">
		<table id="dg_search_item_e" class="easyui-datagrid" style="width:100%;height:100%;"></table>
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

		function formatfile_1(val,row){
		    var url = "upload/";
		    return '<a href="'+url + row.FILE_1+'" target="_blank">'+val+'</a>';
		}

		function formatfile_2(val,row){
		    var url = "upload/";
		    return '<a href="'+url + row.FILE_2+'" target="_blank">'+val+'</a>';
		}

		function formatfile_3(val,row){
		    var url = "upload/";
		    return '<a href="'+url + row.FILE_3+'" target="_blank">'+val+'</a>';
		}

		$('#item_fm').hide();
		$('#item_fm_e').hide();

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');

			$('#dg').datagrid({
				url: 'prf_quotes_get.php',
				rownumbers:'true',
				singleSelect:'true',
				fitColumns: 'true',
				columns:[[
					{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120},
					{field:'QUOTATION_DATE',title:'QUOTATION<br>DATE', halign:'center', width:120},
					{field:'APPROVED',hidden: true},
	                {field:'QUOTATION_APPROVED', title:'QUOTATION<br>APPROVE', halign:'center', align:'center', width:120},
	                {field:'J',hidden: true},
	                {field:'STATUS_VENDOR', title:'STATUS<br>VENDOR', halign:'center', align:'center', width:50},
	                {field:'QUOTATION_NOTE', title:'QUOTATION<br>NOTE', halign:'center', width:250},
	                {field:'USER_ENTRY', title:'USER ENTRY', halign:'center', align:'center', width:120},
	                {field:'LAST_UPDATE', title:'LAST UPDATE', halign:'center', align:"center", width:120},
	                {field:'USER_APPROVAL', title:'USER<br>APPROVAL', halign:'center', align:'center', width:120}
				]],
				view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:10px" id="tbdetail'+rowIndex+'"></div><div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listexpand"></table></div>';
				},
				onExpandRow: function(index,row){
					var uri_doc = encodeURIComponent(row.QUOTATION_NO);
					var gcd_doc = $.trim(row.QUOTATION_NO);
					var ddv = $(this).datagrid('getRowDetail',index).find('table.listexpand');

	                ddv.datagrid({
	                	title: 'QUOTATION DETAIL (NO: '+row.QUOTATION_NO+')',
						url:'prf_quotes_detail.php?quotation='+row.QUOTATION_NO,
						toolbar: '#tbdetail'+index,
						singleSelect:true,
						rownumbers:true,
						loadMsg:'load data ...',
						height:'auto',
						rownumbers: true,
						columns:[[
							{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120,hidden: true},
							{field:'ITEM_NO',title:'ITEM<br>NO.', halign:'center', width:120},
							{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250},
							{field:'FILE_1',title:'FILE 1', halign:'center', width:250,formatter:formatfile_1},
							{field:'FILE_2',title:'FILE 2', halign:'center', width:250,formatter:formatfile_2},
							{field:'FILE_3',title:'FILE 3', halign:'center', width:250,formatter:formatfile_3}
						]],
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						},
						view: detailview,
						detailFormatter: function(rowIndex, rowData){
							return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listvendor"></table></div>';
						},
						onExpandRow: function(index,row){
							var ddv2 = $(this).datagrid('getRowDetail',index).find('table.listvendor');
			                var uri_doc = encodeURIComponent(row.ITEM_NO);

			                ddv2.datagrid({
			                	title: 'QUOTATION DETAIL ITEM (NO: '+row.QUOTATION_NO+' / '+row.ITEM_NO+')',
								url:'prf_quotes_detail22.php?item='+row.ITEM_NO+'&quotation='+row.QUOTATION_NO,
								singleSelect:true,
								rownumbers:true,
								loadMsg:'load data ...',
								height:'auto',
								rownumbers: true,
								columns:[[
									{field:'VENDOR',title:'VENDOR<br>NO.', halign:'center', width:120},
									{field:'COMPANY',title:'VENDOR NAME', halign:'center', width:250},
									{field:'PRICE',title:'PRICE', halign:'center', align: 'right', width:250},
									{field:'FLAG_APPROVED',title:'APPROVED', halign:'center', align:'center', width:250}
								]],
								onResize:function(){
									$('#ddv').datagrid('fixDetailRowHeight',index);
									$('#dg').datagrid('fixDetailRowHeight',index);
								},
								onLoadSuccess:function(){
									setTimeout(function(){
										$('#dg').datagrid('fixDetailRowHeight',index);
									},0);
								}
			                });
						}
	                });
	                $('#dg').datagrid('fixDetailRowHeight',index);
				}
			});

			$('#dg_add').datagrid({
				url: 'prf_quotes_item_get.php',
				rownumbers:'true',
				singleSelect:'true',
				frozenColumns: [[
					{field:'QUOTATION_NO',title:'QUOT No.', halign:'center', width:200, hidden: true},
					{field:'ITEM_NO',title:'ITEM No.', halign:'center', width:200},
					{field:'DESCRIPTION',title:'ITEM NAME', halign:'center', width:350},
					{field:'QTY',title:'QTY', halign:'center', width:150, editor:{type:'textbox'},hidden: true}
				]],
				columns: [[
					{field:'FILE_1',title:'FILE-1.', halign:'center', width:180},
					{field:'FILE_2',title:'FILE-2.', halign:'center', width:180},
					{field:'FILE_3',title:'FILE-3.', halign:'center', width:180}
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
					$('#ITEM_NO2').textbox('setValue',row.ITEM_NO);
					$('#ITEM_NAME').textbox('setValue',row.ITEM_NAME);
					$('#ITEM_SPEC').textbox('setValue',row.ITEM_SPEC);
				}
			});

			$('#dg_add_e').datagrid({
				url: 'prf_quotes_item_get.php',
				rownumbers:'true',
				singleSelect:'true',
				frozenColumns: [[
					{field:'QUOTATION_NO',title:'QUOT No.', halign:'center', width:200, hidden: true},
					{field:'ITEM_NO',title:'ITEM No.', halign:'center', width:200},
					{field:'DESCRIPTION',title:'ITEM NAME', halign:'center', width:350},
					{field:'QTY',title:'QTY', halign:'center', width:150, editor:{type:'textbox'},hidden: true}
				]],
				columns: [[
					{field:'FILE_1',title:'FILE-1.', halign:'center', width:180},
					{field:'FILE_2',title:'FILE-2.', halign:'center', width:180},
					{field:'FILE_3',title:'FILE-3.', halign:'center', width:180}
				]],
			    onDblClickRow:function(rowIndex){
			    	$(this).datagrid('beginEdit', rowIndex);
			    }
			});

			$('#dg_search_item_e').datagrid({
				rownumbers:'true',
				fitColumns: 'true',
				singleSelect:'true',
				columns:[[
					{field:'ITEM_NO',title:'Item No.', halign:'center', width:120},
					{field:'ITEM_NAME',title:'DESCRIPTION', halign:'center', width:250}
				]],
				onClickRow:function(id,row){
					$('#ITEM_NO_e').textbox('setValue',row.ITEM_NO);
					$('#ITEM_NO2_e').textbox('setValue',row.ITEM_NO);
					$('#ITEM_NAME_e').textbox('setValue',row.ITEM_NAME);
					$('#ITEM_SPEC_e').textbox('setValue',row.ITEM_SPEC);
				}
			});
		})

		function new_quot(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Quotation');
            $('#QUOT_DATE').datebox('setValue','<?=date('Y-m-d')?>');
            $('#QUOT_DATE').datebox('enable');
            $('#btn_add_header').linkbutton('enable');

            $('#txt_search_item').textbox('disable');
            $('#btn_search_item').linkbutton('disable');
            $('#btn_add_item').linkbutton('disable');
            $('#ITEM_UPLOAD_1').filebox('disable');
            $('#ITEM_UPLOAD_2').filebox('disable');
            $('#ITEM_UPLOAD_3').filebox('disable');
            $('#date_req').datebox('disable');

            $('#btn_delete_item').linkbutton('disable');
            $('#dg_add').datagrid('loadData',[]);

            $('#fm').form('clear');
        }

        function simpan_header(){
        	var q_date = $('#QUOT_DATE').datebox('getValue');
        	//alert(q_date);
        	if(q_date!=''){
        		$.post('prf_quotes_save_header.php',{
					qno: $('#QUOT_NO').textbox('getValue'),
					qdt: $('#QUOT_DATE').datebox('getValue')
				}).done(function(res){
					//alert(res);
					$('#QUOT_DATE').datebox('disable');
					$('#btn_add_header').linkbutton('disable');

					$('#QUOT_NO2').textbox('setValue',$('#QUOT_NO').textbox('getValue'));
					$('#QUOT_NO22').textbox('setValue',$('#QUOT_NO').textbox('getValue'));
					$('#QUOT_NO3').textbox('setValue',$('#QUOT_NO').textbox('getValue'));
					$('#txt_search_item').textbox('enable');
		            $('#btn_search_item').linkbutton('enable');
		            $('#btn_add_item').linkbutton('enable');
		            $('#ITEM_UPLOAD_1').filebox('enable');
		            $('#ITEM_UPLOAD_2').filebox('enable');
		            $('#ITEM_UPLOAD_3').filebox('enable');
		            $('#date_req').datebox('enable');
		            $('#btn_delete_item').linkbutton('enable');
		            $('#dg_add').datagrid('load',{
		            	qno: $('#QUOT_NO').textbox('getValue')
		            });
				})	
        	}else{
        		$.messager.alert("warning","Quotation Date Field Can't Empty!","Warning");
        	}
        }

        function save_header(){
        	$.ajax({
				type: 'GET',
				url: 'json/json_quotation.php',
				data: { kode:'kode' },
				success: function(data){
					$('#QUOT_NO').textbox('setValue', data[0].kode);
					simpan_header();
				}
			});
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

        function save_item(){
        	$('#fm').form('submit',{
				url: 'prf_quotes_save.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					//alert(result);
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.alert('Information','item Already Exist','info');
					}
					$('#dg_add').datagrid('reload');
					$('#fm').form('clear');
					$('#QUOT_NO2').textbox('setValue',$('#QUOT_NO').textbox('getValue'));
					$('#QUOT_NO22').textbox('setValue',$('#QUOT_NO').textbox('getValue'));

				}
			});
        }

        function destroy_item(){
        	var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                    if (r){
                    	$.post('prf_quotes_item_destroy.php',{id:row.ITEM_NO, qt_no:row.QUOTATION_NO},function(result){
                            if (result.success){
                                $('#dg_add').datagrid('reload');
                            }else{
                                $.messager.show({title: 'Error',msg: result.errorMsg});
                            }
                        },'json');
                    }
                });
			}
        }

        function edit_quot(){
        	var row = $('#dg').datagrid('getSelected');
            if (row){
            	if(row.APPROVED=='1'){
            		$.messager.alert('Warning',"Data can't be edited",'warning');
            	}else{
            		$('#dlg_e').dialog('open').dialog('setTitle','Edit Quotation ('+row.QUOTATION_NO+')');
            		$('#QUOT_NO_e').textbox('setValue',row.QUOTATION_NO);
            		$('#QUOT_DATE_e').datebox('setValue',row.QUOTATION_DATE);
            		$('#QUOT_NO2_e').textbox('setValue',row.QUOTATION_NO);
					$('#QUOT_NO22_e').textbox('setValue',row.QUOTATION_NO);
					$('#btn_add_header_e').linkbutton('disable');
					$('#QUOT_DATE_e').datebox('disable');

					$('#dg_add_e').datagrid('load',{
		            	qno: $('#QUOT_NO_e').textbox('getValue')
		            });
            	}
            }
        }

        function search_item_e(){
        	var txt_s_item_e = $('#txt_search_item_e').textbox('getValue');
        	//alert (txt_s_item);
        	if(txt_s_item_e!=''){
        		$('#dg_search_item_e').datagrid('reload');
        		$('#dlg_search_item_e').dialog('open').dialog('center').dialog('setTitle','Search Item');
        		$('#dg_search_item_e').datagrid('load',{
        			itemno: $('#txt_search_item_e').textbox('getValue')
        		});
				$('#dg_search_item_e').datagrid({
					url:'prf_quotes_search_item.php'
				});
				$('#dg_search_item_e').datagrid('enableFilter');
				$('#txt_search_item_e').textbox('setValue','');
        	}else{
        		$.messager.alert("warning","Search Item Field Can't Empty!","Warning");
        	}
        }

        function save_item_e(){
        	$('#fm_e').form('submit',{
				url: 'prf_quotes_save_edit.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					//alert(result);
					var result = eval('('+result+')');
					if (result.errorMsg){
						//$.messager.alert('Information','item Already Exist','info');
						$.messager.alert('Information',result.errorMsg,'info');
					}else{
						$.messager.alert('Information',result.successMsg,'info');
					}
					$('#dg_add_e').datagrid('reload');
					$('#fm_e').form('clear');
					$('#QUOT_NO2_e').textbox('setValue',$('#QUOT_NO_e').textbox('getValue'));
					$('#QUOT_NO22_e').textbox('setValue',$('#QUOT_NO_e').textbox('getValue'));

				}
			});
        }

        function destroy_item_e(){
        	var row = $('#dg_add_e').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                    if (r){
                    	$.post('prf_quotes_item_destroy.php',{id:row.ITEM_NO, qt_no:row.QUOTATION_NO},function(result){
                            if (result.success){
                                $('#dg_add_e').datagrid('reload');
                            }else{
                                $.messager.show({title: 'Error',msg: result.errorMsg});
                            }
                        },'json');
                    }
                });
			}
        }

        function destroy_item(){
        	var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                    if (r){
                    	$.post('prf_quotes_item_destroy.php',{id:row.ITEM_NO, qt_no:row.QUOTATION_NO},function(result){
                            if (result.success){
                                $('#dg_add').datagrid('reload');
                            }else{
                                $.messager.show({title: 'Error',msg: result.errorMsg});
                            }
                        },'json');
                    }
                });
			}
        }

        function destroy_quot(){
        	var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete Quotation No. '+row.QUOTATION_NO+'?',function(r){
                    if (r){
                    	var appr = row.APPROVED;
                    	if(appr=='1'){
                    		$.messager.alert('Warning',"Data can't be deleted",'warning');
                    	}else{
                    		$.post('prf_quotes_destroy.php',{id:row.QUOTATION_NO},function(result){
	                            if(result.successMsg){
	                            	$.messager.alert('Information',result.successMsg,'info');
	                            }else{
	                            	$.messager.alert('Error',result.errorMsg,'warning');
	                            }
	                            $('#dg').datagrid('reload');
	                        },'json');
                    	}
                    }
                });
			}
        }

        function print_quot(){
        	var row = $('#dg').datagrid('getSelected');	
			if (row){
				if(row.J=='N' && row.APPROVED!='1'){
					$.messager.alert('Warning',"Data can't be print",'warning');
				}else{
					window.open('prf_quotes_printpdf.php?quotation='+row.QUOTATION_NO, '_blank');
				}
			}else{
				$.messager.alert('Warning',"Data not selected",'warning');
			}
        }

        function print_quot_dtl(){
        	var row = $('#dg').datagrid('getSelected');	
			if (row){
				if(row.J=='N' && row.APPROVED!='1'){
					$.messager.alert('Warning',"Data can't be print",'warning');
				}else{
					window.open('prf_quotes_printpdf_dtl.php?quotation='+row.QUOTATION_NO, '_blank');
				}
			}else{
				$.messager.alert('Warning',"Data not selected",'warning');
			}
        }
	</script>
    </body>
    </html>
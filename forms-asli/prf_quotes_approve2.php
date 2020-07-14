<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Quotation Approval</title>
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

	<table id="dg" title="QUOTATION APPROVAL" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:400px;"></table>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="select_vendor()">Select Vendor</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="quot_appr()">Approved Quotation</a>
	</div>

	<!-- ADD -->
	<div id="dlg" class="easyui-dialog" style="width:1050px;" closed="true" data-options="position:'bottom'" buttons="#dlg-buttons">
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend> QUOTATION HEADER </legend>
        	<div class="fitem">
	        	<span style="width:110px;display:inline-block;">QUOTATION NO.</span>
	        	<input style="width:180px;" name="QUOT_NO" id="QUOT_NO" class="easyui-textbox" disabled="" />
	        	<span style="width:35px;display:inline-block;"></span>
	        	<span style="width:120px;display:inline-block;">QUOTATION DATE</span>
	        	<input style="width:100px;" name="quot_date" id="quot_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" disabled="" />
        	</div>
        </fieldset>
        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend> LIST ITEM </legend>
	        <table id="dg_appr" class="easyui-datagrid" toolbar="#toolbar_appr" style="width:100%;height:150px;" rownumbers="true" singleSelect="true"></table>
	        <div class="fitem"></div>
	        <table id="dg_v" class="easyui-datagrid" toolbar="#toolbar_v" style="width:100%;height:150px;" rownumbers="true" singleSelect="true"></table>
        </fieldset>

        <fieldset style="width:1000px; margin:2px; padding:15px 15px;"><legend> APPROVAL </legend>
	        <div class="fitem">
		        <input type="checkbox" name="ck_appr" id="ck_appr" required="" checked="true">Approval</input>
		        <span style="width:100px;display:inline-block;"></span>
				<span style="width:50px;display:inline-block;">Notes</span>
		        <input class="easyui-textbox" id="txt_notes" multiline="true" style="width:750px;height: 30px;"/>
		    </div>
        </fieldset>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_quot_appr()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- SELECT VENDOR -->
	<div id="dlg_vendor" class="easyui-dialog" style="width:700px;height:250px;" closed="true">
		<table id="dg_vendor" class="easyui-datagrid" toolbar='#toolbar_vendor' style="width:100%;height:100%;"></table>
		<!-- <div id="toolbar_vendor">
			<div class="fitem">
				<span style="width:100px;display:inline-block;"> PRICE </span>
				<input id="txt_price" class="easyui-textbox" data-options="prompt:'ITEM PRICE (IDR)'" style="width:40%;height:22px;" required="">
			</div>
			<div class="fitem">
				<input id="txt_search_vendor" class="easyui-textbox" data-options="iconCls:'icon-search',iconWidth:28,prompt:'Search Vendor...'" style="width:40%;height:22px;">	
				<a href="javascript:void(0)" id="btn_search_vendor" class="easyui-linkbutton" iconCls="icon-filter" style="width: 100px;" onclick="search_vendor()">Search</a>
			</div>
		</div> -->
	</div>
	<!-- END VENDOR -->

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
						{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120, hidden: true},
						{field:'ITEM_NO',title:'ITEM<br>NO.', halign:'center', width:120},
						{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250},
						{field:'FILE_1',title:'FILE 1', halign:'center', width:250},
						{field:'FILE_2',title:'FILE 2', halign:'center', width:250},
						{field:'FILE_3',title:'FILE 3', halign:'center', width:250}
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

		$('#dg_appr').datagrid({
			frozenColumns:[[
				{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120, hidden: true},
				{field:'ITEM_NO',title:'ITEM<br>NO.', halign:'center', width:120},
				{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250}/*,
				{field:'PRICE',title:'PRICE', halign:'center', align:'right', width:120},
				{field:'CURR',title:'CURRENCY', halign:'center', align:'center', width:70},
				{field:'COMPANY',title:'VENDOR', halign:'center', width:250}*/	//, editor:{type:'textbox'}
			]],
			columns:[[
				{field:'FILE_1',title:'FILE 1', halign:'center', width:250,formatter:formatfile_1},
				{field:'FILE_2',title:'FILE 2', halign:'center', width:250,formatter:formatfile_2},
				{field:'FILE_3',title:'FILE 3', halign:'center', width:250,formatter:formatfile_3},
				{field:'COMPANY_CODE',title:'VENDOR', halign:'center', width:250, hidden: true},
				{field:'CURR_CODE',title:'CURR', halign:'center', width:250, hidden: true}
				
			]],
			onDblClickRow:function(id,row){
		    	var qtt = row.QUOTATION_NO;
		    	var itm = row.ITEM_NO;
		    	var des = row.DESCRIPTION;
		    	//var idx = $('#dg_appr').datagrid('getRowIndex',row);
		    	//alert (idx);
		    	$('#dlg_vendor').dialog('open').dialog('center').dialog('setTitle','SELECT VENDOR AND PRICE ('+itm+' - '+des+')');
		    	$('#dg_vendor').datagrid({
		    		url:'prf_quotes_detail2.php?item='+itm+'&quotno='+qtt+'&desc='+des,
		    		singleSelect:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
						{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120},
						{field:'ITEM_NO',title:'ITEM<br>NO.', halign:'center', width:120},
						{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250},
						{field:'SUPPLIER_CODE',title:'VENDOR<br>NO.', halign:'center', width:120, hidden: true},
						{field:'COMPANY',title:'VENDOR NAME', halign:'center', width:300},
						{field:'ESTIMATE_PRICE',title:'PRICE_LIST', halign:'center', align: 'right', width:150}, //, editor:{type:'textbox'}
						{field:'CURR_SHORT',title:'CURRENCY', halign:'center', align:'center', width:80},
						{field:'CURR_CODE',title:'CURRENCY CODE', halign:'center', align:'center', width:250, hidden: true}
					]],
					onClickRow:function(rowIndex){
						$(this).datagrid('beginEdit', rowIndex);
					},
					onDblClickRow:function(id,row2){
						var t = $('#dg_v').datagrid('getRows');
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
								var COMP = $('#dg_v').datagrid('getData').rows[i].SUPPLIER_CODE;
								var ITEM = $('#dg_v').datagrid('getData').rows[i].ITEM_NO;
								//alert(item);
								if (COMP == row2.SUPPLIER_CODE && ITEM == row2.ITEM_NO) {
									count++;
								};
							};
						}

						//alert('count = '+count);
						if (count>0) {
							$.messager.alert('Information','Item present','warning');
						}else{
							$('#dg_v').datagrid('insertRow',{
								index: idxfield,	// index start with 0
								row: {
									QUOTATION_NO: row2.QUOTATION_NO,
									ITEM_NO: row2.ITEM_NO,
									DESCRIPTION: row2.DESCRIPTION,
									PRICE: row2.ESTIMATE_PRICE,
									COMPANY: row2.COMPANY,
									SUPPLIER_CODE: row2.SUPPLIER_CODE,
									CURR: row2.CURR_SHORT,
									CURR_CODE: row2.CURR_CODE
								}
							});
						}
						/*$('#dg_v').datagrid('insertRow',{
							row: {
								QUOTATION_NO: row2.QUOTATION_NO,
								ITEM_NO: row2.ITEM_NO,
								DESCRIPTION: row2.DESCRIPTION,
								PRICE: row2.ESTIMATE_PRICE,
								COMPANY: row2.COMPANY,
								COMPANY_CODE: row2.SUPPLIER_CODE,
								CURR: row2.CURR_SHORT,
								CURR_CODE: row2.CURR_CODE
							}
						});*/
					}
		    	});
		    }
		});

		$('#dg_v').datagrid({
			columns:[[
				{field:'QUOTATION_NO',title:'QUOTATION<br>NO.', halign:'center', width:120, hidden: true},
				{field:'ITEM_NO',title:'ITEM<br>NO.', halign:'center', width:120},
				{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:350},
				{field:'PRICE',title:'PRICE', halign:'center', align:'right', width:120},
				{field:'CURR',title:'CURRENCY', halign:'center', align:'center', width:85},
				{field:'COMPANY',title:'VENDOR', halign:'center', width:250},	//, editor:{type:'textbox'}
				{field:'SUPPLIER_CODE', halign:'center', width:250, hidden: true},
				{field:'CURR_CODE',title:'CURR', halign:'center', width:350, hidden: true}
			]]
		});

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})

        function quot_appr(){
        	var row = $('#dg').datagrid('getSelected');	
			if (row){
				if (row.APPROVED=='1'){
					$.messager.alert('Information','Quotation sudah di approve','info');	
				}else{
					$('#dlg').dialog('open').dialog('center').dialog('setTitle','Approval ('+row.QUOTATION_NO+')');
					$('#QUOT_NO').textbox('setValue',row.QUOTATION_NO);
					$('#quot_date').datebox('setValue',row.QUOTATION_DATE);
					$('#')
					$('#dg_appr').datagrid({
						url: 'prf_quotes_detail.php?quotation='+row.QUOTATION_NO
					});
				}
			}else{
				 $.messager.alert('Information','Quotation not selected','info');
			}
        }

        function save_quot_appr(){
        	var ck_appr = 'false';
			if ($('#ck_appr').attr("checked")) {
				ck_appr = "true";
			}

			if(ck_appr=='true'){
				var t1 = $('#dg_v').datagrid('getRows');
				var total1 = t1.length;
				for(i=0;i<total1;i++){
					$('#dg_v').datagrid('endEdit',i);
					$.post('prf_quotes_approve_save.php',{
						qtno: $('#dg_v').datagrid('getData').rows[i].QUOTATION_NO,
						itno: $('#dg_v').datagrid('getData').rows[i].ITEM_NO,
						vndr: $('#dg_v').datagrid('getData').rows[i].SUPPLIER_CODE,
						prce: $('#dg_v').datagrid('getData').rows[i].PRICE.replace(/,/g,''),
						curr: $('#dg_v').datagrid('getData').rows[i].CURR_CODE,
						appr: ck_appr,
						note: $('#txt_notes').textbox('getValue')
					}).done(function(res){
						//alert(res);
						$('#dlg').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
				$.messager.alert('Information','Data Saved','info');
			}else{
				$.messager.alert('Warning','Please checked approval','warning');
			}
        }

        /*function search_vendor(){
        	var txt_s_vendor = $('#txt_search_vendor').textbox('getValue');
        	var txt_s_price = $('#txt_price').textbox('getValue');
        	//alert (txt_s_item);
        	if(txt_s_vendor!='' && txt_s_price!=''){
        		$('#dg_vendor').datagrid('load',{
        			search: $('#txt_search_vendor').textbox('getValue'),
        			price: $('#txt_price').textbox('getValue')
        		});
				$('#dg_vendor').datagrid({
					url:'prf_quotes_detail2.php'
				});
				$('#dg_vendor').datagrid('enableFilter');
				$('#txt_search_vendor').textbox('setValue','');
				$('#txt_price').textbox('setValue','');
        	}else{
        		$.messager.alert("warning","Search vendor or item price Field Can't Empty!","warning");
        	}
        }*/
	</script>
    </body>
    </html>
<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>Print Ulang SSCC</title>
   	<link rel="icon" type="image/png" href="../favicon.png">
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
			margin: 0px;
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
		<?php include ('../../ico_logout.php');?>
		<div id="toolbar" style="padding:3px 3px;">
			<div class="fitem" align="center">
		        <input  id= "src" class="easyui-searchbox" data-options="prompt:'Please Input Amazon PO No. or ASIN',searcher:doSearch" style="width:50%">
			</div>
		</div>
		<table id="dg" title="SEARCH SSCC PRINT (NON MPS)" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" rownumbers="true" fitColumns="true" singleSelect="true" showFooter="true"></table>
		<span style="font-size: 8px;color: red">*) double click to view details carton</span>

		<div id="dlg_details" class="easyui-dialog" style="width: 450px;height: 270px;" closed="true" buttons="#dlg-buttons_details" data-options="modal:true">
			<table id="dg_details" class="easyui-datagrid" toolbar="#toolbar_details" style="width:auto;height:auto;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>

		<div id="dlg-buttons_details">
			<span style="font-size: 8px;color: red;">*) Double click to Select to print</span>
			<!-- <a href="javascript:void(0)" id="save_details" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_select()" style="width:90px">Select</a>-->
			<a href="javascript:void(0)" id="cancel_details" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_details').dialog('close')" style="width:90px">Cancel</a>
		</div>


		<hr>
		<table id="dg_result" title="RESULT SELECT PRINT" class="easyui-datagrid" toolbar="#toolbar_result" style="width:100%;height:auto;" rownumbers="true" fitColumns="true" singleSelect="true"></table>
		<hr>
		<a href="javascript:void(0)" id="print_select" class="easyui-linkbutton c6" iconCls="icon-print" onclick="print_select()" style="width:90px">PRINT</a> 
		<script type="text/javascript">
			$(function(){
				$('#dg_result').datagrid({
				    columns:[[
					    {field:'WO',title:'WO NO.', width:100, halign:'center'},
					    {field:'ASIN',title:'ASIN', width:80, halign:'center'},
					    {field:'AMAZON_PO_NO',title:'AMZ PO NO.', width:80, halign:'center'},
		                {field:'FROM_CARTON',title:'FROM<br/>CARTON', width:80, halign:'center', align:'right'},
		                {field:'TO_CARTON',title:'TO<br/>CARTON',width:80,halign:'center', align:'right'},
		                {field:'NO',title:'NO.',width:80,halign:'center', align:'center'},
		                {field:'SSCC_NO',title:'SSCC NO.',width:150,halign:'center', align:'center'},
		                {field:'TOTAL_CARTON', hidden: true},
		                {field:'QUANTITY', hidden: true},
		                {field:'ADDRESS1', hidden: true},
		                {field:'ADDRESS2', hidden: true},
		                {field:'ADDRESS3', hidden: true},
		                {field:'ADDRESS4', hidden: true}
					]]
				})		
			})

		    function doSearch(value){
				$('#dg').datagrid('load', {
					src: value
				});

				$('#dg').datagrid({
					url: 'sscc_non_mps_get.php',
				    columns:[[
					    {field:'WO',title:'WO NO.', width:100, halign:'center'},
					    {field:'ASIN',title:'ASIN', width:80, halign:'center'},
					    {field:'AMAZON_PO_NO',title:'AMZ PO NO.', width:80, halign:'center'},
		                {field:'ITEM',title:'ITEM NO.',width:80,halign:'center', align:'center'},
		                {field:'DESCRIPTION',title:'ITEM DESCRIPTION',width:150,halign:'center', align:'left'},
		                {field:'TOTAL_CARTON',title:'TOTAL<br/>CARTON',width:60,halign:'center', align:'right'},
		                {field:'START_CARTON',title:'START<br/>CARTON',width:60,halign:'center', align:'right'},
		                {field:'QUANTITY',title:'QUANTITY',width:60,halign:'center', align:'right'}
					]],
					onDblClickRow:function(id,row){
						$('#dlg_details').dialog('open').dialog('setTitle','Details SSCC (WO: '+row.WO+')');
						//$('#dg_details').datagrid('loadData',[]);
						console.log(row.WO+' - '+row.ASIN+' - '+row.AMAZON_PO_NO);
						$('#dg_details').datagrid('load', {
							wo: row.WO, 
							asin: row.ASIN, 
							amz_po: row.AMAZON_PO_NO
						});

                        console.log('sscc_non_mps_get_details.php?wo='+row.WO+'&asin='+row.ASIN+'&amz_po='+row.AMAZON_PO_NO);

						$('#dg_details').datagrid({
							url: 'sscc_non_mps_get_details.php',
							columns:[[
								{field:'FROM_CARTON',title:'FROM<br/>CARTON', width:80, halign:'center', align:'right'},
				                {field:'TO_CARTON',title:'TO<br/>CARTON',width:80,halign:'center', align:'right'},
				                {field:'NO',title:'NO.',width:80,halign:'center', align:'center'},
				                {field:'SSCC_NO',title:'SSCC NO.',width:150,halign:'center', align:'center'}
				            ]],
				            onDblClickRow: function(id,row) {
				            	var t = $('#dg_result').datagrid('getRows');
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
										var sscc = $('#dg_result').datagrid('getData').rows[i].SSCC_NO;
										//alert(item);
										if (sscc==row.SSCC_NO) {
											count++;
										};
									};
								}

								//alert('count = '+count);
								if (count>0) {
									$.messager.alert('Warning','SSCC present','warning');
								}else{
									$('#dg_result').datagrid('insertRow',{
										index: idxfield,	// index start with 0
										row: {
											WO: row.WO,
											ASIN: row.ASIN,
											AMAZON_PO_NO: row.AMAZON_PO_NO,
											FROM_CARTON: row.FROM_CARTON,
											TO_CARTON: row.TO_CARTON,
											NO: row.NO,
											SSCC_NO: row.SSCC_NO,
											TOTAL_CARTON: row.TOTAL_CARTON,
											QUANTITY: row.QUANTITY,
											ADDRESS1: row.ADDRESS1,
											ADDRESS2: row.ADDRESS2,
											ADDRESS3: row.ADDRESS3,
											ADDRESS4: row.ADDRESS4
										}
									});
								}
				            }
						});
        			}
        		});
        	}

        	function saveText(text, filename){
			  	var a = document.createElement('a');
			  	a.setAttribute('href', 'data:text/plain;charset=utf-8,'+encodeURIComponent(text));
			  	a.setAttribute('download', filename);
			  	a.click()
			}

        	function print_select() {
        		var dataRows = [];
    			var t = $('#dg_result').datagrid('getRows');
				var total = t.length;
				if (total == 0) {
					$.messager.alert('Warning','Data Print Not Found','warning');
				}else{
					for(i=0;i<total;i++){
						dataRows.push({
							PO: $('#dg_result').datagrid('getData').rows[i].AMAZON_PO_NO,
							ASIN: $('#dg_result').datagrid('getData').rows[i].ASIN,
							TOTAL_CARTON: $('#dg_result').datagrid('getData').rows[i].TOTAL_CARTON,
							FROM_CARTON: $('#dg_result').datagrid('getData').rows[i].FROM_CARTON,
							TO_CARTON: $('#dg_result').datagrid('getData').rows[i].TO_CARTON,
							QTY: $('#dg_result').datagrid('getData').rows[i].QUANTITY,
							SSCC: $('#dg_result').datagrid('getData').rows[i].SSCC_NO,
							ADDRESS1: $('#dg_result').datagrid('getData').rows[i].ADDRESS1,
							ADDRESS2: $('#dg_result').datagrid('getData').rows[i].ADDRESS2,
							ADDRESS3: $('#dg_result').datagrid('getData').rows[i].ADDRESS3,
							ADDRESS4: $('#dg_result').datagrid('getData').rows[i].ADDRESS4,
							WO_NO: $('#dg_result').datagrid('getData').rows[i].WO,
							NO: $('#dg_result').datagrid('getData').rows[i].NO
						})
					}

					var myJSON=JSON.stringify(dataRows);
					var str_unescape=unescape(myJSON);

					$.post('sscc_non_mps_save.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_add').dialog('close');
							$('#dg').datagrid('reload');
							// $.messager.alert('INFORMATION','Process Data Success..!!','info');
							$.messager.confirm('Confirm','Do you want to Print SSCC?',function(r){
								if(r){
									window.open('sscc_non_mps_print.php');
									clear_data();
								}
							});
						}else{
							$.messager.alert('ERROR',res,'warning');
						}
					});

					// saveText( JSON.stringify(dataRows), "sscc_non_mps_result.json" );
				}
        	}

        	function clear_data(){
        		var item = $('#dg_result').datagrid('getRows');  
             	for (var i = item.length - 1; i >= 0; i--) {  
                	var index = $('#dg_result').datagrid('getRowIndex', item[i]);  
                 	$('#dg_result').datagrid('deleteRow', index);  
             	}  
        	}
		</script>
	</body>
</html>
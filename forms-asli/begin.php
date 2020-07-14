<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Setting Beginning Stock</title>
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

	<table id="dg" title="SETTING BEGINNING STOCK" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;"
            url="begin_get.php" rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th halign="center" align="center" field="ID" width="40" >ID</th>
                <th halign="center" align="" field="GR_NO" width="50">GOOD RECEIVE<br>NO.</th>
                <th halign="center" align="center" field="GR_DATE" width="50">GOOD RECEIVE<br>DATE</th>
                <th halign="center" align="" field="COMPANY" width="150">VENDOR</th>
                <th halign="center" align="center" field="LINE_NO" width="20">LINE<br>NO.</th>
                <th halign="center" align="right" field="QTY" width="50" hidden="true">QTY</th>
                <th halign="center" align="right" field="QTY_A" width="40">QTY</th>
                <th halign="center" align="center" field="RACK" width="40">RACK</th>
                <th halign="center" align="center" field="ITEM_NO" width="40">ITEM</th>
                <th halign="center" align="" field="DESCRIPTION" width="130">DESCRIPTION</th>
                <th halign="center" align="center" field="PALLET" width="20">PALLET</th>
                <!-- <th halign="center" align="center" field="TANGGAL" width="50">DATE</th> -->
            </tr>
        </thead>
    </table>

	<div id="toolbar">
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="new_stock()"><i class="fa fa-plus" aria-hidden="true"></i> New</a>
        <a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="edit_stock()"><i class="fa fa-sliders" aria-hidden="true"></i> Edit</a>
        <a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="destroy_stock()"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a>
        <a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="print_stock_select()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
	</div>

	<div id="dlg" class="easyui-dialog" style="width:600px" closed="true" buttons="#dlg-buttons" data-options="modal:true">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <div class="fitem" style="width: 500px;">
				<span style="width:130px;display:inline-block;">Goods Receive No.</span>
                <input name="GR_NO" id="GR_NO" class="easyui-textbox" required="true" style="width:345px;">
            </div>
            <div class="fitem" style="width: 500px;">
				<span style="width:130px;display:inline-block;">Line No.</span>
                <input name="LINE_NO" id="LINE_NO" class="easyui-textbox" required="true" style="width:50px;">
                <span style="width:80px;display:inline-block;">PALLET</span>
                <input name="PALLET" id="PALLET" class="easyui-textbox" required="true" style="width:50px;">
                <span style="width:50px;display:inline-block;">QTY</span>
                <input name="QTY" id="QTY" class="easyui-textbox" required="true" style="width:100px;">
            </div>
            <div class="fitem" style="width: 500px;">
				<span style="width:130px;display:inline-block;">RACK</span>
                <input name="RACK" id="RACK" class="easyui-textbox" required="true" style="width:345px;">
            </div>
            <div class="fitem" style="width: 500px;">
				<span style="width:130px;display:inline-block;">ITEM</span>
                <input name="ITEM_NO" id="ITEM_NO" class="easyui-combobox" required="true" style="width:345px;" data-options="valueField:'id_item',textField:'id_name_item',url:'json/json_item_all.php',panelHeight:'100px',
                onSelect: function(rec){
					$('#DESCRIPTION').textbox('setValue',rec.name_item);                
                }">
            </div>
            <div class="fitem" style="width: 500px;">
            	<span style="width:130px;display:inline-block;"></span>
				<input name="DESCRIPTION" id="DESCRIPTION" class="easyui-textbox" required="true" style="width:345px;">
            </div>
        </form>
    </div>

    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" id="btn_print" iconCls="icon-print" onclick="print_stock()" style="width:90px">Print</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
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

		var url;
		var s;
		var no;

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})

		function new_stock(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Item Stock');
            $('#fm').form('clear');
            $('#btn_print').hide();
            url = 'begin_save.php';
            s = 1;
        }

        function edit_stock(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit Item Stock '+row.ID+'');
                $('#fm').form('load',row);
                $('#btn_print').hide();
                $('#QTY').textbox()
                url = 'begin_update.php?id='+row.ID;
                s = 2;	no=row.ID;
            }
        }

        function saveUser(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						$('#btn_print').show();
						/*if(s!=2){
							$('#dlg').dialog('close');	// close the dialog
						}*/
						$('#dg').datagrid('reload');	// reload the user data
					}
				}
			});
		}

		function print_stock(){
			if(s==2){
				window.open('begin_print.php?id='+no+'&s='+s);
				s='';	no='';
			}else{
				window.open('begin_print.php?id=new&s='+s);
				s='';	no='';
				/*$.ajax({
					type: 'GET',
					url: 'begin_max.php',
					data: { kode:'kode' },
					success: function(data){
						var sn = data[0].id_stock;
						alert(sn);
					}
				});*/
			}
		}

		function print_stock_select(){
			var row = $('#dg').datagrid('getSelected');
            if (row){
            	window.open('begin_print.php?id='+row.ID+'&s=0');
            }
		}

        function destroy_stock(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy this Item?',function(r){
                    if (r){
                        $.post('begin_destroy.php',{id:row.ID},function(result){
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
        }
	</script>
    </body>
    </html>
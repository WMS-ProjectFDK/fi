<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PACKING LIST</title>
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
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
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
<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="margin-left;border-radius:4px;height:45px;width:98%"><legend><span class="style3"><strong> Filter</strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PPBE No.</span>
				<select style="width:120px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" data-options=" url:'json/json_ppbe_no.php', method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="back_to_shipping()" style="width:200px;"><i class="fa fa-undo" aria-hidden="true"></i> Back to Shipping Plan</a>
			</div>
		</div>
	</fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
		<a href="javascript:void(0)" id="add_bdc" class="easyui-linkbutton c2" disabled="true" onClick="add()" style="width:200px;"><i class="fa fa-plus" aria-hidden="true"></i> ADD </a>
		<a href="javascript:void(0)" id="savePL" class="easyui-linkbutton c2" disabled="true" onClick="savePL()" style="width:180px;"><i class="fa fa-save" aria-hidden="true"></i> SAVE </a>
		<a href="javascript:void(0)" id="delPL" class="easyui-linkbutton c2" disabled="true" onClick="deletePL()" style="width:180px;"><i class="fa fa-trash" aria-hidden="true"></i> DELETE </a>
	</div>
</div>

<table id="dg" title="Packing List Setting" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true">
</table>

<div id='dlg_viewItem' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewItem" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>

<script type="text/javascript">
	var link = '';
	$(function(){
		$('#dg').datagrid( {
			singleSelect: true,
			columns:[[
				{field:'STS', title:'SO', halign:'center', width:50, hidden: true},
			    {field:'SO_NO', title:'SO', halign:'center', width:50},
                {field:'WO_NO', title:'WORK NO.', halign:'center', width:100},
                {field:'ITEM_NO', title:'ITEM<br/>NO.', halign:'center', align:'center', width:40},
                {field:'DESCRIPTION', title:'ITEM NAME', halign:'center', align:'left', width:140},
                {field:'START_BOX', title:'STAR<br/>BOX', halign:'center', align:'center', width:40,editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
                {field:'END_BOX', title:'END<br/>BOX', halign:'center', align:'center', width:40, editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
 				{field:'QTY', title:'QTY', width:60, halign:'center', align:'right', editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
                {field:'CARTON', title:'CARTON<br>FULL', halign:'center', align:'right', width:50, editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
                {field:'CARTON_NON_FULL', title:'CARTON<br>NON FULL', halign:'center', align:'right', width:50, editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
                {field:'GROSS', title:'GW<br/>(KGS)', halign:'center', align:'right', width:50, editor:{type:'numberbox',
																				   			 options:{
																				   			 	precision:2,
																				   			 	groupSeparator:',',
																				   			 	disable:true,
																				   			 	decimalSeparator:'.'
																				   			 }
																   				  			}
				},
                {field:'NET', title:'NW<br/>(KGS)', halign:'center', align:'right', width:50, editor:{type:'numberbox',
																				   			 options:{
																				   			 	precision:2,
																				   			 	groupSeparator:',',
																				   			 	disable:true},
																				   			 	decimalSeparator:'.'
																   				  			}
				},
                {field:'MSM', title:'MSM<br/>(CBM)', halign:'center', align:'right', width:50, editor:{type:'numberbox',
																				   			 options:{
																				   			 	precision:3,
																				   			 	groupSeparator:',',
																				   			 	disable:true,
																				   			 	decimalSeparator:'.'
																				   			 }
																   				  			}
				},
                {field:'PALLET', title:'PALLET<br/>QTY', halign:'center', align:'right', width:40, editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
                {field:'ANSWER_NO', width: 40, hidden: true},
                {field:'ROW_ID', width: 40, hidden: true}

			]],
			
		});
		$('#dg').datagrid({
		    rowStyler:function(index,row){
		        if (row.CONTAINER_NO =='TOTAL'){
		            return 'background-color:red;color:white;font-weight:bold;';
		        }
		    }
		});
		$('#dg').datagrid({
			onBeforeSelect:function(index,row){
				var col = $('#dg').datagrid('getColumnOption');
				    if(row.CONTAINER_NO =='TOTAL') return col.editor = null;
			}
		})
	});

	function getRowIndexplan(target){
	    var tr = $(target).closest('tr.datagrid-row');
	    return parseInt(tr.attr('datagrid-row-index'));
	}

	function filterData(){
		$('#dg').datagrid('load', {
			ppbe: $('#cmb_ppbe').combobox('getValue')
		});

		$('#dg').datagrid( {
			url: 'packing_list_get.php',
			onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
		    	$('#add_bdc').linkbutton('enable');
		    	$('#savePL').linkbutton('enable');
		    	$('#delPL').linkbutton('enable');
		    }
		})
		add();
		link = "?ppbe="+$('#cmb_ppbe').combobox('getValue');
	}


	function formatListprice(val,row){
	    if (val > 50){
	        return '<span style="background-color:pink;color:blue;font-weight:bold;">'+val+'</span>';
	    } else {
	        return val;
	    }
	}

	function add(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			var indx = $('#dg').datagrid('getRowIndex',row);
			var idx = $('#dg').datagrid('getRowIndex', row)+1;
			if (row.CONTAINER_NO != 'TOTAL'){
				$('#dg').datagrid('insertRow',{
					index: idx,
					row: {
						STS: 'NEW',
						SO_NO: row.SO_NO, 
						WO_NO: row.WO_NO,
						ITEM_NO: row.ITEM_NO, 
						DESCRIPTION: row.DESCRIPTION,
						START_BOX: row.START_BOX,
						END_BOX: row.END_BOX,
						QTY: row.QTY, 
						CARTON: row.CARTON, 
						CARTON_NON_FULL: row.CARTON_NON_FULL, 
						GROSS: row.GROSS, 
						NET: row.NET, 
						MSM: row.MSM, 
						PALLET: row.PALLET, 
						ANSWER_NO: row.ANSWER_NO,
						ROW_ID: row.ROW_ID,
					}
				});
			};
		}
	}

	function savePL(){
		$.messager.progress({
		    title:'Please waiting',
		    msg:'Save data...'
		});

		var dataRows = [];
		var t = $('#dg').datagrid('getRows');
		var x = 0;
		var total = t.length;
		for(i=0;i<total;i++){
			$('#dg').datagrid('endEdit',i);
			dataRows.push({
				pl_sts:	$('#dg').datagrid('getData').rows[i].STS,
				pl_rowid: $('#dg').datagrid('getData').rows[i].ROW_ID,
				pl_start: $('#dg').datagrid('getData').rows[i].START_BOX.replace(/,/g,''),
				pl_end: $('#dg').datagrid('getData').rows[i].END_BOX.replace(/,/g,''),
				pl_qty: $('#dg').datagrid('getData').rows[i].QTY.replace(/,/g,''),
				pl_carton: $('#dg').datagrid('getData').rows[i].CARTON.replace(/,/g,''),
				pl_carton_non_full: $('#dg').datagrid('getData').rows[i].CARTON_NON_FULL.replace(/,/g,''),
				pl_gw: $('#dg').datagrid('getData').rows[i].GROSS.replace(/,/g,''),
				pl_nw: $('#dg').datagrid('getData').rows[i].NET.replace(/,/g,''),
				pl_msm: $('#dg').datagrid('getData').rows[i].MSM.replace(/,/g,''),
				pl_pallet: $('#dg').datagrid('getData').rows[i].PALLET.replace(/,/g,'')
			});
		}

		var myJSON=JSON.stringify(dataRows);
		var str_unescape=unescape(myJSON);

		console.log(unescape(str_unescape));

		$.post('packing_list_save.php',{
			data: unescape(str_unescape)
		}).done(function(res){
			if(res == '"success"'){
				filterData();
				$.messager.alert('INFORMATION','Update Data Success..!!','info');
				$.messager.progress('close');
			}else{
				$.messager.alert('ERROR',res,'warning');
				$.messager.progress('close');
			}
		});
	}

	function deletePL() {
		var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('Confirm','Are you sure you want to destroy this Packing List Transaction?',function(r){
                if (r){
                	$.messager.progress({
					    title:'Please waiting',
					    msg:'removing data...'
					});

					console.log('packing_list_destroy.php?id='+row.ROW_ID);

                    $.post('packing_list_destroy.php',{id:row.ROW_ID},function(result){
                        if (result.success){
                        	$.messager.progress('close');
                            $('#dg').datagrid('reload');    // reload the user data
                        }else{
                            $.messager.show({    // show error message
                                title: 'Error',
                                msg: result.errorMsg
                            });
                            $.messager.progress('close');
                        }
                    },'json');
                }
            });
        }else{
        	$.messager.show({title: 'Packing List',msg: 'Data not selected'});
        }
	}

	function back_to_shipping(){
		window.location.href = 'shipping_plan.php?id=341';
	}
</script>

</body>
</html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}
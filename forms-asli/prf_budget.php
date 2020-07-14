<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Parameter Budget</title>
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

	<table id="dg" title="Parameter Budget" class="easyui-datagrid" toolbar="#toolbar" style="width:70%;height:400px;"></table>

	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="new_budget()">New</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit_budget()">Edit</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroy_budget()">Remove</a>
	</div>

	<!-- ADD -->
	<div id="dlg_add" class="easyui-dialog" style="width:600px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
        <div class="fitem" style="width: 500px; margin:0; padding:15px 15px;">
			<span style="width:75px;display:inline-block;">Period</span>
            <input style="width:100px;" name="cmb_month_add" id="cmb_month_add" class="easyui-combobox" data-options=" url:'json/json_month.json',method:'get',valueField:'id',textField:'month', panelHeight:'150px', onSelect: function(rec){ }" required/>
            <input style="width:55px;" name="dn_year_add" id="dn_year_add" class="easyui-numberbox" value="<?=date('Y');?>"/ disabled="true" >
        </div>
        <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:540px;margin-left: 15px;"><legend><span class="style3"> Setting Rate </span></legend>
            <div class="fitem" style="width: 500px; margin-left: 15px;margin-top: 0px">
				<span style="width:164px;display:inline-block;">IDR</span>
				<span style="width:164px;display:inline-block;">JPY</span>
				<span style="width:164px;display:inline-block;">SGD</span>
            </div>
            <div class="fitem" style="width: 500px; margin-left: 15px;margin-top: 0px">
            	<input style="width:164px;" name="idr_rate" id="idr_rate" class="easyui-textbox" disabled="" />
            	<input style="width:164px;" name="jpy_rate" id="jpy_rate" class="easyui-textbox" disabled="" />
            	<input style="width:164px;" name="sgd_rate" id="sgd_rate" class="easyui-textbox" disabled="" />
            </div>
        </fieldset>
        <div style="clear:both;margin-bottom:15px;"></div>
		<table id="dg_add" class="easyui-datagrid" style="width:100%;height:210px;margin-left: 15px;"></table>
    </div>

    <div id="dlg-buttons-add">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_add()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END ADD -->

    <!-- EDIT -->
	<div id="dlg_edit" class="easyui-dialog" style="width:600px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
        <div class="fitem" style="width: 500px; margin:0; padding:15px 15px;">
			<span style="width:75px;display:inline-block;">Period</span>
            <input style="width:100px;" name="cmb_month_edit" id="cmb_month_edit" class="easyui-combobox" data-options=" url:'json/json_month.json',method:'get',valueField:'id',textField:'month', panelHeight:'150px', onSelect: function(rec){ }" disabled="true"/>
            <input style="width:55px;" name="dn_year_edit" id="dn_year_edit" class="easyui-numberbox" value="<?=date('Y');?>"/ disabled="true" >
        </div>
        <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:540px;margin-left: 15px;"><legend><span class="style3"> Setting Rate </span></legend>
            <div class="fitem" style="width: 500px; margin-left: 15px;margin-top: 0px">
				<span style="width:164px;display:inline-block;">IDR</span>
				<span style="width:164px;display:inline-block;">JPY</span>
				<span style="width:164px;display:inline-block;">SGD</span>
            </div>
            <div class="fitem" style="width: 500px; margin-left: 15px;margin-top: 0px">
            	<input style="width:164px;" name="idr_rate_edit" id="idr_rate_edit" class="easyui-textbox" disabled="" />
            	<input style="width:164px;" name="jpy_rate_edit" id="jpy_rate_edit" class="easyui-textbox" disabled="" />
            	<input style="width:164px;" name="sgd_rate_edit" id="sgd_rate_edit" class="easyui-textbox" disabled="" />
            </div>
        </fieldset>
        <div style="clear:both;margin-bottom:15px;"></div>
		<table id="dg_edit" class="easyui-datagrid" style="width:100%;height:210px;margin-left: 15px;"></table>
    </div>

    <div id="dlg-buttons-edit">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_edit()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END EDIT -->

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

		$('#dg').datagrid({
			url: 'prf_budget_get.php',
			rownumbers:'true', 
			fitColumns:'true',
			singleSelect:'true',
			columns:[[
				{field:'DOC_NO',title:'DOCUMNET NO.', halign:'center', width:50, hidden:true},
                {field:'DOC', title:'PERIOD', halign:'center', width:70},
                {field:'IDR_RATE',title:'IDR', halign:'center', align:'right', width:70},
                {field:'JPY_RATE', title:'JPY', halign:'center', align:'right', width:70},
                {field:'SGD_RATE', title:'SGD', halign:'center', align:'right', width:70}
			]],
			view: detailview,
			detailFormatter:function(index,row){
				return '<div class="ddv"></div>';
			},
		 	onExpandRow: function(index,row){
		 		var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
                var uri_doc = encodeURIComponent(row.DOC_NO)
                ddv.datagrid({
                	title: 'Parameter Budget Detail (Period: '+row.DOC+')',
					url:'prf_budget_get_detail.php?doc='+uri_doc,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					rowStyler: function(index, row){
						
					},
					columns:[[
						{halign:'center', field:'DEPARTEMENT', title:'DEPARTEMENT', width:200},
						{halign:'center', align:"right", field:'BUDGET', title:'BUDGET (USD)', width:150}
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
		});

		$('#dg_add').datagrid({
		    singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
				{field:'ID_DEPT',title:'ID DEPARTEMENT', halign:'center', width:80, hidden: true},
				{field:'DEPARTEMENT',title:'DEPARTEMENT', halign:'center', width:180},
			    {field:'BUDGET', title:'BUDGET (USD)', halign:'center', width:100, align:'right', editor:{type:'textbox'}}
		    ]],
		    onClickRow:function(rowIndex){
		        if (lastIndex != rowIndex){
		            $(this).datagrid('endEdit', lastIndex);
		        }
		        lastIndex = rowIndex;
		    },
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    },
		    onBeginEdit:function(rowIndex){
		    	var editors = $('#dg_add').datagrid('getEditors', rowIndex);
		       	var n1 = $(editors[0].target);
		        var n2 = $(editors[1].target);
		        var n3 = $(editors[2].target);
		        var temp = $('#dg_add').datagrid('getData').rows[rowIndex].temp;
		        	n1.add(n2).numberbox({
		        		onChange:function(index, row){

			        		if (parseInt(n2.numberbox('getValue')) > parseInt(n1.numberbox('getValue'))) {
			        			var c = 1;
			        			n3.numberbox('setValue', c);
			        			alert("!!!!!");
			        		};
		        			
		        			/*var c = parseInt(n1.numberbox('getValue')) - parseInt(n2.numberbox('getValue'));*/
		        			
		        		}
		        	});
		    }
		});

		$('#dg_edit').datagrid({
		    singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
		    	{field:'ID_DEPT',title:'ID DEPARTEMENT', halign:'center', width:80, hidden: true},
				{field:'DEPARTEMENT',title:'DEPARTEMENT', halign:'center', width:180},
			    {field:'BUDGET', title:'BUDGET (USD)', halign:'center', width:100, align:'right', editor:{type:'textbox'}}
		    ]],
		    onClickRow:function(rowIndex){
		        if (lastIndex != rowIndex){
		            $(this).datagrid('endEdit', lastIndex);
		        }
		        lastIndex = rowIndex;
		    },
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    },
		    onBeginEdit:function(rowIndex){
		    	var editors = $('#dg_edit').datagrid('getEditors', rowIndex);
		       	var n1 = $(editors[0].target);
		        var n2 = $(editors[1].target);
		        var n3 = $(editors[2].target);
		        var temp = $('#dg_edit').datagrid('getData').rows[rowIndex].temp;
		        	n1.add(n2).numberbox({
		        		onChange:function(index, row){

			        		if (parseInt(n2.numberbox('getValue')) > parseInt(n1.numberbox('getValue'))) {
			        			var c = 1;
			        			n3.numberbox('setValue', c);
			        			alert("!!!!!");
			        		};
		        			
		        			/*var c = parseInt(n1.numberbox('getValue')) - parseInt(n2.numberbox('getValue'));*/
		        			
		        		}
		        	});
		    }
		});

		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})

		function new_budget(){
            $('#dlg_add').dialog('open').dialog('center').dialog('setTitle','New Parameter Budget');
            $('#cmb_month_add').textbox('setValue', '');

            $.ajax({
				type: 'GET',
				url: 'json/json_rate_idr.php',
				data: { kode:'kode' },
				success: function(data){
					$('#idr_rate').textbox('setValue',data[0].rate);
				}
			});

			$.ajax({
				type: 'GET',
				url: 'json/json_rate_jpy.php',
				data: { kode:'kode' },
				success: function(data){
					$('#jpy_rate').textbox('setValue',data[0].rate);
				}
			});

			$.ajax({
				type: 'GET',
				url: 'json/json_rate_sgd.php',
				data: { kode:'kode' },
				success: function(data){
					$('#sgd_rate').textbox('setValue',data[0].rate);
				}
			});

            $('#dg_add').datagrid('loadData',[]);
            $('#dg_add').datagrid({
				url:'json/json_dep_budget.php'
			});
        }

        function save_add(){
			var bln = $('#cmb_month_add').combobox('getValue');
			var thn = $('#dn_year_add').textbox('getValue');
			var idr = $('#idr_rate').textbox('getText');
			var jpy = $('#jpy_rate').textbox('getText');
			var sgd = $('#sgd_rate').textbox('getText');

			if(bln=='' || thn=='' || idr=='' || jpy=='' || sgd=='') {
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{
				rows = $('#dg_add').datagrid('getRows');
				for(i=0;i<rows.length;i++){
					$('#dg_add').datagrid('endEdit',i);
					$.post('prf_budget_save.php',{
						bln: $('#cmb_month_add').combobox('getValue'),
						thn: $('#dn_year_add').numberbox('getValue'),
						idr: $('#idr_rate').textbox('getText').replace(/,/g,''),
						jpy: $('#jpy_rate').textbox('getText').replace(/,/g,''),
						sgd: $('#sgd_rate').textbox('getText').replace(/,/g,''),
						idp: $('#dg_add').datagrid('getData').rows[i].ID_DEPT,
						dpt: $('#dg_add').datagrid('getData').rows[i].DEPARTEMENT,
						bdg: $('#dg_add').datagrid('getData').rows[i].BUDGET
					}).done(function(res){
						//alert(res);
						$('#dlg_add').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			}
		}

        function edit_budget(){
        	var bulan = {01: "JANUARY",
					02: "FEBRUARY",
					03: "MARCH",
					04: "APRIL",
					05: "MAY",
					06: "JUNY",
					07: "JULY",
					08: "AUGUST",
					09: "SEPTEMER",
					10: "OCTOBER",
					11: "NOVEMBER",
					12: "DECEMBER"};

            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg_edit').dialog('open').dialog('center').dialog('setTitle','Edit Parameter Budget ('+row.DOC+')');
                var d = row.DOC_NO;
                var val_month = d.substr(4,2);
                var txt = bulan[parseInt(val_month)];
                $('#cmb_month_edit').textbox('setValue', val_month);
                $('#cmb_month_edit').textbox('setText',txt);
                $('#dn_year_edit').numberbox('setValue', d.substr(0,4));
                $('#idr_rate_edit').textbox('setValue',row.IDR_RATE);
                $('#jpy_rate_edit').textbox('setValue',row.JPY_RATE);
                $('#sgd_rate_edit').textbox('setValue',row.SGD_RATE);
                $('#dg_edit').datagrid('loadData', []);
				$('#dg_edit').datagrid({
					url:'prf_budget_getedit.php?doc_no='+row.DOC_NO
				});
            }
        }

        function save_edit(){
			var idr = $('#idr_rate_edit').textbox('getText');
			var jpy = $('#jpy_rate_edit').textbox('getText');
			var sgd = $('#sgd_rate_edit').textbox('getText');

			if(idr=='' || jpy=='' || sgd=='') {
				$.messager.alert("warning","Required Field Can't Empty!","Warning");
			}else{
				rows = $('#dg_edit').datagrid('getRows');
				for(i=0;i<rows.length;i++){
					$('#dg_edit').datagrid('endEdit',i);
					$.post('prf_budget_update.php',{
						bln: $('#cmb_month_edit').combobox('getValue'),
						thn: $('#dn_year_edit').numberbox('getValue'),
						/*idr: $('#idr_rate_edit').textbox('getText').replace(/,/g,''),
						jpy: $('#jpy_rate_edit').textbox('getText').replace(/,/g,''),
						sgd: $('#sgd_rate_edit').textbox('getText').replace(/,/g,''),*/
						idp: $('#dg_edit').datagrid('getData').rows[i].ID_DEPT,
						dpt: $('#dg_edit').datagrid('getData').rows[i].DEPARTEMENT,
						bdg: $('#dg_edit').datagrid('getData').rows[i].BUDGET.replace(/,/g,'')
					}).done(function(res){
						/*alert(res);*/
						$('#dlg_edit').dialog('close');
						$('#dg').datagrid('reload');
					})
				}
			}
        }

        function destroy_budget(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Are you sure you want to destroy this budget?',function(r){
                    if (r){
                        $.post('prf_budget_destroy.php',{id:row.DOC_NO},function(result){
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
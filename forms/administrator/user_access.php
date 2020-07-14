<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>USER ACCESS</title>
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
	<?php include ('../../ico_logout.php'); ?>
	<div id="toolbar">
		<fieldset style="width:330px; height: 80px; border:1px solid #d0d0d0; border-radius:4px; float:left; padding:5px 10px;"><legend>Find Users</legend>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Username</span>
				<select style="width:190px;" name="username_src" id="username_src" class="easyui-combogrid" style="width:142px;"></select>
			</div>
			<div id="nav">
	     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addUser()" disabled="true">Add</a>
	     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()" disabled="true">Edit</a>
	     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()" disabled="true">Delete</a>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:500px;height: 80px; padding:5px 10px;"><legend>User Access</legend>
			<div style="width:360px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Username</span>
					<input name="a_username" id="a_username" class="easyui-textbox" style="width:190px;" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38">
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">User ID</span>
					<input name="a_userid" id="a_userid" class="easyui-textbox" style="width:190px;" data-options="prompt:'User ID',iconCls:'icon-man',iconWidth:38">
				</div>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveAccess()" style="width:100px;">Save</a>
			</div>
			<div style="clear:both; margin-bottom:5px;"></div>
		</fieldset>
	</div>

	<div id="progress" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" data-options="modal:true, collapsible:false, minimizable:false, maximizable:false, closable:false, closed:true">
		<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
	</div>
	
	<table id="dg" title="USER ACCESS" class="easyui-datagrid" style="width:100%;height:490px;"></table>
		
	<script type="text/javascript">
		var nik='';

		function saveAccess(){
			var rows = $('#dg').datagrid('getRows');
			var total = $('#dg').datagrid('getData').total;
			//alert($('.status').val());
			
			var cek = 0;
			var access='';
			if ($('#acs1').prop('checked')) {
				access = 'true';
			}
			else
			{
				access = "false";
			}
			
			for(i=0;i<rows.length;++i){
				$('#dg').datagrid('endEdit', i);
				$.post('user_access_saveacc.php',{
					user_id: $('#a_userid').textbox('getValue'),
					menu_id: $('#dg').datagrid('getData').rows[i].id,
					view: $('#dg').datagrid('getData').rows[i].a_view,
					add: $('#dg').datagrid('getData').rows[i].a_add,
					edit: $('#dg').datagrid('getData').rows[i].a_edit,
					del: $('#dg').datagrid('getData').rows[i].a_del,
					print: $('#dg').datagrid('getData').rows[i].a_print


				}).done(function(res){
					//alert(res);
				});
				cek++;
			}

			progress();
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

		var url;

		$(function(){
			$('#dg').datagrid('enableFilter');
			$('#a_username').textbox('disable');
			$('#a_userid').textbox('disable');
			$('#dept').combobox('disable');
			$('#savebtn').linkbutton('disable');
			$('#updatebtn').linkbutton('disable');
			$('#acs1').prop('disabled', true);
			$('#acs2').prop('disabled', true);
			$('#sts1').prop('disabled', true);
			$('#sts2').prop('disabled', true);

			
		});

		function progress(){
			$('#progress').dialog('open').dialog('setTitle', 'Please Wait...');
			    $('#p').progressbar({
			        value: 0
			    });
			setInterval(function(){
				var value = $('#p').progressbar('getValue');
				//myTimer()
				if (value < 100){
				    value += Math.floor(Math.random() * 10);
				    $('#p').progressbar('setValue', value);
				}

				if (value >=100) {
					$('#progress').dialog('close');
				};
			},1000);
		}
		
		$('#username_src').combogrid({
			url: 'user_access_getuser.php',
			panelHeight: '170px',
			panelWidth: '500',
			idField: 'nik',
			textField: 'username',
			method: 'get',
			columns: [[
				{field:'userid', title:'User ID', width:''},
				{field:'username', title:'Username', width:''},
				{field:'desc', title:'Dept', width:''}

			]],
		    onClickRow: function(rec){
		    	var g = $('#username_src').combogrid('grid');	// get datagrid object
				var r = g.datagrid('getSelected');	// get the selected row
				//$.messager.progress();
		    	//alert(r.status);
		    	
		    	if (r) {
			    	//alert();
			    	$.ajax({
			    		success:function(){
				    	$('#dg').datagrid('reload','user_access_getmenuuser.php?nik='+$.trim(r.userid));
				    	$('#dg').datagrid({
				    		onLoadSuccess:function(){
				    			//alert('loaded');
				    			//var rows = $('#dg').datagrid('getRows');
								//var total = $('#dg').datagrid('getData').total;
							    //alert(total);
							    var rows = $('#dg').datagrid('getRows');
								var total = rows.length;
							    for(i=0;i<total;++i){
							    	$('#dg').datagrid('beginEdit', i);
							    	//var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
									//$(data.target).prop('checked',true);
							    }
							    //$.messager.progress('close');
				    		}

				    	});
						$('#dg').datagrid('enableFilter');
			    		}
			    	});
				    
		    		
			    	$('#sts1').prop('disabled', false);
					$('#sts2').prop('disabled', false);
			    	if (r.status=='Active') {
			    		//alert();
			    		$('#sts1').prop('checked', true);
			    	}else{
			    		$('#sts2').prop('checked', true);
			    	}
			    	//alert(r.type_access);
			    	if (r.type_access) {
			    		$('#updatebtn').linkbutton('enable');
			    		$('#savebtn').linkbutton('disable');
			    	}else{
			    		$('#updatebtn').linkbutton('disable');
			    		$('#savebtn').linkbutton('enable');
			    	}

			    	if (r.type_access=='t'){
			    		$('#acs1').prop('checked', true);
			    		//$('#savebtn').linkbutton('enable');
			    	}else if(r.type_access=='f'){
			    		$('#acs2').prop('checked', true);
			    	}else{

			    	}
			    	$('#a_username').textbox('setValue', r.username);
			    	$('#a_userid').textbox('setValue', r.userid);
			    	$('#dept').combobox('setValue', r.department);
			    	$('#acs1').prop('disabled', false);
					$('#acs2').prop('disabled', false);
			    	nik = r.nik;				    	
		    	};
		    }
		});

		function full(){
			$('#dg').datagrid('load','user_access_getmenuuser.php?nik='+$.trim(nik)+'&sts=full');
		}

		function limited(){
		 	$('#dg').datagrid('reload','user_access_getmenuuser.php?nik='+$.trim(nik)+'&sts=lim');
		}

		$('#dg').datagrid({
		    url:'user_access_getmenu.php',
		    toolbar: '#toolbar',
		    singleSelect: true,
			pagination: false,
			rownumbers: true,
			fitColumns: true,
			sortName: 'username',
			sortOrder: 'asc',
		    columns:[[
			    {field:'id', hidden: true},
			    {field:'menu_parent',title:'PARENT',width:170, halign: 'center'},
			    {field:'menu_sub_parent',title:'PARENT',width:170, halign: 'center'},
			    {field:'menu_name',title:'MENU',width:170, halign: 'center'},
			    {field:'a_view',title:'VIEW', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'a_add',title:'ADD', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'a_edit',title:'EDIT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'a_del',title:'DELETE', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'a_print',title:'PRINT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}}
		    ]],
		    onClickRow:function(rowIndex){
		        // if (lastIndex != rowIndex){
		            $(this).datagrid('endEdit', rowIndex);
		        // }
		        // lastIndex = rowIndex;
		    },
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    }
	    });
		
	</script>
    </body>
    </html>
<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>USER ACCESS</title>
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
	<div id='dlg' class="easyui-dialog" style="background-opacity:0.6; width:460px;height:275px;padding:10px 20px"
				closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Name</span>
				<input name="name" id="name" class="easyui-textbox" style="width:200px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Username</span>
				<input name="username" id="username" class="easyui-textbox" style="width:150px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Email</span>
				<input class="easyui-textbox" name="email" id="email" type="email" style="width:150px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Password</span>
				<input name="password" id="pass" type="password" maxlength="6" class="easyui-textbox" style="width:150px;" required="true" data-options="prompt:'password',iconCls:'icon-lock',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Confirm Password</span>
				<input name="c_password" id="c_pass" type="password" maxlength="6" class="easyui-textbox" style="width:150px;" required="true" data-options="prompt:'confirm password',iconCls:'icon-lock',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Departement</span>
				<select name="dept" id="dept" class="easyui-combobox" style="width:200px;" data-options=" url:'json/json_dept.php',method:'get',valueField:'ID_DEPT',textField:'DEPARTEMENT', panelHeight:'150px'" required="true"> </select>
			</div>
		</form>
		
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<div id="toolbar">
	<fieldset style="width:330px; height: 100px; border:1px solid #d0d0d0; border-radius:4px; float:left;"><legend>Find Users</legend>
		<div class="fitem">
			<span style="width:130px;display:inline-block;">Username</span>
			<select style="width:190px;" name="username_src" id="username_src" class="easyui-combogrid" style="width:142px;"></select>
		</div>
		<div id="nav">
     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addUser()">Add</a>
     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit</a>
     		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Delete</a>
		</div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:710px; height: 100px;"><legend>Information User Access</legend>
		<div style="width:360px; float:left;">
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Username</span>
				<input name="a_username" id="a_username" class="easyui-textbox" style="width:190px;" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">User ID</span>
				<input name="a_userid" id="a_userid" class="easyui-textbox" style="width:190px;" data-options="prompt:'User ID',iconCls:'icon-man',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Dept.</span>
				<select style="width:190px;" name="dept" id="dept" class="easyui-combobox" data-options=" url:'json/json_dept.php',method:'get',valueField:'ID_DEPT',textField:'DEPARTEMENT', panelHeight:'150px'"></select>
			</div>
		</div>
		<div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Type Access</span>
				<label><input type="radio" id="acs1" onClick="full()" name="access" value="t"><span>Full</span></label>
				<label><input type="radio" id="acs2" onClick="limited()" name="access" value="f" style="margin-left:37px;"><span >Limited</span></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Status</span>
				<label><input type="radio" name="a_status" id="sts1" value="t"><span style="width:100px;">Active</span></label>
				<label><input type="radio" name="a_status" id="sts2" value="f" style="margin-left:21px;"><span style="width:100px;">In-Active</span></label>
			</div>
			<div class="fitem" style="margin-top:5px">
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveAccess()" style="width:100px;">Save</a>
			</div>
		</div>
	</fieldset>
		<div style="clear:both; margin-bottom:5px;"></div>
	</div>

	<div id="progress" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false,closed:true">
		<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
	</div>
	<table id="dg" title="User Access" class="easyui-datagrid" style="width:100%;height:500px;">
	</table>
		
	<script type="text/javascript">
		var nik='';

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
			    {field:'ID', hidden: true},
			    {field:'MENU_PARENT',title:'PARENT',width:170, halign: 'center'},
			    {field:'MENU_NAME',title:'MENU',width:170, halign: 'center'},
			    {field:'A_VIEW',title:'VIEW', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'A_ADD',title:'ADD', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'A_EDIT',title:'EDIT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'A_DEL',title:'DELETE', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
			    {field:'A_PRINT',title:'PRINT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}}
		    ]],
		    onClickRow:function(rowIndex){
		        if (lastIndex != rowIndex){
		            $(this).datagrid('endEdit', lastIndex);
		            //$(this).datagrid('beginEdit', rowIndex);
		        }
		        lastIndex = rowIndex;
		    },
		    onDblClickRow:function(rowIndex){
		    	$(this).datagrid('beginEdit', rowIndex);
		    }
	    });

	    $('#username_src').combogrid({
	    	url: 'user_access_getuser.php',
			panelHeight: '170px',
			panelWidth: '400',
			idField: 'nik',
			textField: 'username',
			method: 'get',
			FITcOLUMNS: true,
			columns: [[
				{field:'userid', title:'ID', halign:'center', width:80},
				{field:'username', title:'USERNAME', halign:'center', width:120},
				{field:'desc', title:'DEPT', halign:'center', width:180}

			]],
			onClickRow: function(rec){
				var g = $('#username_src').combogrid('grid');
				var r = g.datagrid('getSelected');
				if(r){
					$.ajax({
			    		success:function(){
				    	$('#dg').datagrid('reload','user_access_getmenuuser.php?nik='+$.trim(r.userid));
				    	$('#dg').datagrid({
				    		onLoadSuccess:function(){
				    			var rows = $('#dg').datagrid('getRows');
								var total = rows.length;
							    //alert(total);
							    for(i=0;i<total;++i){
							    	$('#dg').datagrid('beginEdit', i);
							    }
				    		}
				    	});
						$('#dg').datagrid('enableFilter');
			    		}
			    	});
					$('#a_username').textbox('setValue', r.username);
			    	$('#a_userid').textbox('setValue', r.userid);
			    	$('#dept').combobox('setValue', r.department);
			    	$('#savebtn').linkbutton('enable');
				}
			}
	    })
	</script>
</body>
</html>
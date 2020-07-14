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
	<!-- <div id='dlg' class="easyui-dialog" style="background-opacity:0.6; width:460px;height:500px;padding:10px 20px"
				closed="true" buttons="#dlg-buttons">
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">NIK</span>
				<input name="nik" id="nik" class="easyui-textbox" style="width:142px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">User ID</span>
				<input name="userid" id="userid" class="easyui-textbox" style="width:142px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Username</span>
				<input name="username" id="username" class="easyui-textbox" style="width:142px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Password</span>
				<input name="password" id="pass" type="password" maxlength="6" class="easyui-textbox" style="width:142px;" required="true" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Born Date</span>
				<input name="born_date" id="born_date"  class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Born Place</span>
				<input name="born_place" id="born_place" class="easyui-textbox" style="width:142px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Phone No.</span>
				<input name="phone" id="phone" class="easyui-textbox" style="width:142px;" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Gender</span>
				<select name="gender" id="gender" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_gender.php',method:'get',valueField:'gender',textField:'gender', panelHeight:'auto'" required="true"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Golongan</span>
				<select name="golongan" id="golongan" class="easyui-combobox" style="width:80px;"  data-options=" url:'json/json_golongan.php',method:'get',valueField:'gol',textField:'gol', panelHeight:'auto'" required="true"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Status</span>
				<select name="status" id="status" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_status.php',method:'get',valueField:'status',textField:'status', panelHeight:'auto'" required="true"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Date In</span>
				<input name="date_in" id="date_in"  class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" required="true">
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Department</span>
				<select name="department" id="department" class="easyui-combobox" style="width:142px;" data-options=" url:'json/json_department.php',method:'get',valueField:'id_department',textField:'department', panelHeight:'100px', 
				onSelect: function(rec){
				//alert('json/json_position.php?id_department='+rec.id_department);
				$('#position').combobox('reload','json/json_position.php?id_department='+rec.id_department);

				}" required="true"></select>
			</div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Position</span>
				<select name="position" id="position" class="easyui-combobox" style="width:142px;" data-options=" url:'',method:'get',valueField:'id_position',textField:'position', panelHeight:'100px'" required="true"> </select>
			</div>
		</form>
		
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div> -->
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
			</div><!--
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Password Default</span>
				<input style="width:190px;" name="a_password" maxlength="6" id="a_password" type="password" class="easyui-textbox" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Reset/ New Password</span>
				<input style="width:190px;" name="a_newpassword" maxlength="6" id="a_newpassword" type="password" class="easyui-textbox" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38">
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;"></span>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onClick="resetPassword()">Reset</a>
			</div> -->
		</div><!--
		<div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Dept.</span>
				<select style="width:190px;" name="dept" id="dept" class="easyui-combobox" data-options=" url:'json/json_department.php',method:'get',valueField:'id_department',textField:'department', panelHeight:'150px'"></select>
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Type Access</span>
				<label>
					<input type="radio" id="acs1" onClick="full()" name="access" value="t"><span>Full</span>
				</label>
				<label>
					<input type="radio" id="acs2" onClick="limited()" name="access" value="f" style="margin-left:37px;"><span >Limited</span>
				</label>
			</div>
			<div class="fitem">
				<span style="width:130px;display:inline-block;">Status</span>
				<label>
					<input type="radio" name="a_status" id="sts1" value="t"><span style="width:100px;">Active</span>
				</label>
				<label>
					<input type="radio" name="a_status" id="sts2" value="f" style="margin-left:21px;"><span style="width:100px;">In-Active</span>
				</label>
			</div> -->
			<div class="fitem"><!--  style="margin-top:20px;" -->
				<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveAccess()" style="width:100px;">Save</a> <!-- margin-left:120px; -->
				<!-- <a href="javascript:void(0)" id="updatebtn" class="easyui-linkbutton" iconCls="icon-ok" onClick="updateAccess()" style="width:100px;">Update</a> -->
			</div>
		<!-- </div>
	</fieldset> -->
		<div style="clear:both; margin-bottom:5px;"></div>
	</div>

	<div id="progress" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" data-options="modal:true,collapsible:false,minimizable:false,maximizable:false,closable:false,closed:true">
		<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
	</div>
	<table id="dg" title="USER ACCESS" class="easyui-datagrid" style="width:100%;height:490px;"></table>
		
		<script type="text/javascript">
		var nik='';

			/*function resetPassword(){
				//alert($('#username_src').combobox('getValue'));
				$.post('user_access_reset.php',{
					nik: $('#username_src').combobox('getValue'),
					password: $('#a_newpassword').textbox('getValue'),
					a_password: $('#a_password').textbox('getValue')
				}).done(function(res){
					$.messager.alert('info', res, 'info');
					//alert(res);
				});
			}*/

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
				//alert(status);

				
				for(i=0;i<rows.length;++i){
					$('#dg').datagrid('endEdit', i);
					/*var ck = $('#dg').datagrid('getEditors', i);
					var view = $(ck[0].target);
					var add = $(ck[1].target);
					var edit = $(ck[2].target);
					var del = $(ck[3].target);
					var print = $(ck[4].target);

					if (view.attr('checked')) {
						view = 'TRUE';
					}else{
						view = 'FALSE';
					}

					if (add.attr('checked')) {
						add = "TRUE";
					}else{
						add = "FALSE";
					}

					if (edit.attr('checked')) {
						edit = "TRUE";
					}else{
						edit = "FALSE";
					}

					if (del.attr('checked')) {
						del = "TRUE";
					}else{
						del = "FALSE";
					}

					if (print.attr('checked')) {
						print = "TRUE";
					}else{
						print = "FALSE";
					}*/
					//alert(i);
				   	//$('#dg').datagrid('endEdit', i);
				   	//alert($('#dg').datagrid('getData').rows[i].kode_menu);
					$.post('user_access_saveacc.php',{
						user_id: $('#a_userid').textbox('getValue'),
						menu_id: $('#dg').datagrid('getData').rows[i].ID,
						view: $('#dg').datagrid('getData').rows[i].A_VIEW,
						add: $('#dg').datagrid('getData').rows[i].A_ADD,
						edit: $('#dg').datagrid('getData').rows[i].A_EDIT,
						del: $('#dg').datagrid('getData').rows[i].A_DEL,
						print: $('#dg').datagrid('getData').rows[i].A_PRINT


					}).done(function(res){
						//alert(res);
					});
					cek++;
				}
				//alert(cek);

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

			/*function editUser(){
				var g = $('#username_src').combogrid('grid');
				var r = g.datagrid('getSelected');
				//alert(r.phone);
				if (r) {
					$('#dlg').dialog('open').dialog('setTitle','Add User');
					$('#fm').form('load',r);
					$('#nik').textbox('disable');
					$('#pass').textbox('disable');
					$('#password').textbox('setValue','');
					$('#position').combobox('reload','json/json_position.php?id_department='+r.department);
						url="user_access_update.php?nik="+r.nik;
					};
			}*/

			/*function addUser(){
				$('#dlg').dialog('open').dialog('setTitle','Add User');
				$('#fm').form('clear');
				$('#nik').textbox('enable');
				$('#pass').textbox('enable');
				url = 'user_access_save.php';
			}*/

			/*function destroyUser(){
				var g = $('#username_src').combogrid('grid');
				var row = g.datagrid('getSelected');
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to remove this User?',function(r){
						if (r){
							$.post('user_access_destroy.php',{nik:row.nik},function(result){
								if (result.success){
									$('#dg').datagrid('reload');	// reload data
									$('#username_src').combogrid('grid').datagrid('reload');
									$('#username_src').combogrid('setValue','');
								} else {
									$.messager.show({	// show error message
										title: 'Error',
										msg: result.errorMsg
									});
								}
							},'json');
						}
					});
				}
			}*/

			/*function saveUser(){
				//alert(url);
				$('#fm').form('submit',{
					url: url,
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						//alert(result);
						var result = eval('('+result+')');
						
						if (result.errorMsg){
							$.messager.alert('Product','Error','info');
							$.messager.show({
								title: 'Error',
								msg: result.errorMsg

							});
						} else {
							$('#dlg').dialog('close');		// close the dialog
							$('#username_src').combogrid('grid').datagrid('reload');
							$('#dg').datagrid('reload');	// reload data
							$.messager.alert('User Access','Success','info');
						}
					}
				});
			}*/

			/*function updateAccess(){
				var rows = $('#dg').datagrid('getRows');
				var total = $('#dg').datagrid('getData').total;
				
				var access='';
				
				if ($('#acs1').prop('checked')) {
					access = 'true';
				}
				else
				{
					access = 'false';
				}
				
				//alert(total);
				for(i=0;i<total;++i){
						//alert(i);
				   	$('#dg').datagrid('endEdit', i);*/
				   	//alert($('#dg').datagrid('getData').rows[i].kode_menu);
					/*var ck = $('#dg').datagrid('getEditors', i);
					var view = $(ck[0].target);
					var add = $(ck[1].target);
					var edit = $(ck[2].target);
					var del = $(ck[3].target);
					var print = $(ck[4].target);

					if (view.attr('checked')) {
						view = 'TRUE';
					}else{
						view = 'FALSE';
					}

					if (add.attr('checked')) {
						add = "TRUE";
					}else{
						add = "FALSE";
					}

					if (edit.attr('checked')) {
						edit = "TRUE";
					}else{
						edit = "FALSE";
					}

					if (del.attr('checked')) {
						del = "TRUE";
					}else{
						del = "FALSE";
					}

					if (print.attr('checked')) {
						print = "TRUE";
					}else{
						print = "FALSE";
					}	
*/
					/*$.post('user_access_updateaccess.php',{
						
						nik: nik,
						acs: access,
						username: $('#a_username').textbox('getValue'),
						userid: $('#a_userid').textbox('getValue'),
						kode_menu: $('#dg').datagrid('getData').rows[i].kode_menu,
						view: $('#dg').datagrid('getData').rows[i].view,
						add: $('#dg').datagrid('getData').rows[i].add,
						edit: $('#dg').datagrid('getData').rows[i].edit,
						del: $('#dg').datagrid('getData').rows[i].del,
						print: $('#dg').datagrid('getData').rows[i].print

					}).done(function(rec){
						//alert(rec);
					});
				}
				progress();
				setTimeout(function(){}, 10000);
			}*/

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
						//$.messager.alert('User Access','Success','info');
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
				/*var rows = $('#dg').datagrid('getRows');
				var total = $('#dg').datagrid('getData').total;*/
				//$('#dg').datagrid('loadData', []);
				$('#dg').datagrid('load','user_access_getmenuuser.php?nik='+$.trim(nik)+'&sts=full');
				 	//alert();
				 	/*$('#dg').datagrid({
				 		onLoadSuccess:function(){
				 			//alert('loaded');
				 			//var rows = $('#dg').datagrid('getRows');
							var total = $('#dg').datagrid('getData').total;
						    //alert(total);
						    for(i=0;i<total;++i){
						    	//$('#dg').datagrid('beginEdit', i);
						    	//var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
								//$(data.target).prop('checked',true);
						    }
						    $.messager.progress('close');
				 		}

				 	});
					$('#dg').datagrid('enableFilter');
					}*/
				/*$.ajax({
					success:function(){
				});*/
				/*for(i=0;i<total;++i){
				   	//$('#dg').datagrid('beginEdit', i);
				   	var view = $('#dg').datagrid('getEditor',{index:i, field:'view'});
				   	var add = $('#dg').datagrid('getEditor',{index:i, field:'add'});
				   	var edit = $('#dg').datagrid('getEditor',{index:i, field:'edit'});
				   	var delet = $('#dg').datagrid('getEditor',{index:i, field:'del'});
				   	var print = $('#dg').datagrid('getEditor',{index:i, field:'print'});
				   	$(view.target).prop('checked',true);
					$(add.target).prop('checked',true);
					$(edit.target).prop('checked',true);
					$(delet.target).prop('checked',true);
					$(print.target).prop('checked',true);

					$(view.target).prop('disabled',true);
					$(add.target).prop('disabled',true);
					$(edit.target).prop('disabled',true);
					$(delet.target).prop('disabled',true);
					$(print.target).prop('disabled',true);
				}*/
			}

			function limited(){
				//var rows = $('#dg').datagrid('getRows');
				//var total = $('#dg').datagrid('getData').total;
				 	$('#dg').datagrid('reload','user_access_getmenuuser.php?nik='+$.trim(nik)+'&sts=lim');
				 	/*$('#dg').datagrid({
				 		onLoadSuccess:function(){
				 			//alert('loaded');
				 			//var rows = $('#dg').datagrid('getRows');
							var total = $('#dg').datagrid('getData').total;
						    //alert(total);
						    for(i=0;i<total;++i){
						    	//$('#dg').datagrid('beginEdit', i);
						    	//var data = $('#dg').datagrid('getEditor',{index:i, field:'ck'});
								//$(data.target).prop('checked',true);
						    }
						    $.messager.progress('close');
				 		}

				 	});
					$('#dg').datagrid('enableFilter');
					}*/
				/*$.ajax({
					success:function(){
				});*/
				/*for(i=0;i<total;++i){
				   	//$('#dg').datagrid('beginEdit', i);
				   	var view = $('#dg').datagrid('getEditor',{index:i, field:'view'});
				   	var add = $('#dg').datagrid('getEditor',{index:i, field:'add'});
				   	var edit = $('#dg').datagrid('getEditor',{index:i, field:'edit'});
				   	var delet = $('#dg').datagrid('getEditor',{index:i, field:'del'});
				   	var print = $('#dg').datagrid('getEditor',{index:i, field:'print'});
				   	//$(view.target).prop('checked',false);
					//$(add.target).prop('checked',false);
					//$(edit.target).prop('checked',false);
					//$(delet.target).prop('checked',false);
					//$(print.target).prop('checked',false);

					$(view.target).prop('disabled',false);
					$(add.target).prop('disabled',false);
					$(edit.target).prop('disabled',false);
					$(delet.target).prop('disabled',false);
					$(print.target).prop('disabled',false);
				}*/
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
				    {field:'ID', hidden: true},
				    {field:'MENU_PARENT',title:'PARENT',width:170, halign: 'center'},
				    {field:'MENU_SUB_PARENT',title:'PARENT',width:170, halign: 'center'},
				    {field:'MENU_NAME',title:'MENU',width:170, halign: 'center'},
				    {field:'A_VIEW',title:'VIEW', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
				    {field:'A_ADD',title:'ADD', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
				    {field:'A_EDIT',title:'EDIT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
				    {field:'A_DEL',title:'DELETE', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}},
				    {field:'A_PRINT',title:'PRINT', align:'center', width:70, editor:{type:'checkbox',options:{on: 'TRUE',off: 'FALSE'}}}
			    ]],
			    onLoadSuccess:function(){

			    }
			    ,
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
			
		</script>
    </body>
    </html>
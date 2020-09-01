<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DELIVERY UPDATE/RESTORE</title>
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>

	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:940px; height:90px; float:left;"><legend><span class="style3"><strong>Delivery Update / Restore</strong></span></legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">EX Factory Date</span>
					<input style="width:100px;" name="ex_factory_date" id="ex_factory_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> TO
					<input style="width:100px;" name="ex_factory_date_z" id="ex_factory_date_z" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Delivery Type.</span>
					<select  style="width:300px;" name="cmb_delivery_type" id="cmb_delivery_type" class="easyui-combobox" require="true" " >
                        <option value="None" selected>-- Select --</option>
                        <option value="UPDATE">Delivery Update</option>
                        <option value="RESTORE" >Delivery Restore</option>
                    </select>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left: 970px;border-radius:4px;height:90px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Delivery</a>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="save_approve" class="easyui-linkbutton c2" onClick="save_approve()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update / Restore</a>
			</div>
            <div id="dlg-buttons-qty" align="center">
			    <a href="javascript:void(0)" id="save_con" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveCon()" style="width:90px">SET</a>
		    </div>
		</fieldset>
		<div style="clear:both;"></div>
	</div>

    <div id="dlg_input" class="easyui-dialog" style="width: 550px;height: 40`0px;" closed="true" buttons="#dlg-buttons-qty" data-options="modal:true" align="center">
			
			<div class="fitem">
				<span style="width:100px;display:inline-block;">CONTAINER NO</span>
				<input style="width: 400px;" name="con_no" id="con_no" class="easyui-textbox"/>
			</div>
			
			<div class="fitem" align="center">
				<span style="width:100px;display:inline-block;">SEAL NO</span>
				<input style="width:400px;" name="seal_no" id="seal_no" class="easyui-textbox"/>
			</div>

          
			
		</div>

	<table id="dg" title="DELIVERY UPDATE/RESTORE" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>

	<script type="text/javascript">
		var flagTipe = "";
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

		function access_log(){
			//ADD//UPDATE/T
			//DELETE/T
			//PRINT/T
			var add = "<?=$exp[0]?>";
			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}
		}

		$(function(){
			$('#dg').datagrid({
			    columns:[[
				    {field:'CK', checkbox:true, width:30, halign: 'center'},
                    {field:'DO_NO',title:'INV NO.',width:75, halign: 'center', align: 'center'},
				    {field:'DO_DATE',title:'INV DATE',width:60, halign: 'center', align: 'center'},
				    {field:'ANSWER_NO',title:'ANSWER NO.',width:75, halign: 'center', align: 'center', hidden: true},
				    {field:'SO_NO',title:'SALES ORDER<br/>NO.',width:60, halign: 'center', align: 'center'},
				    {field:'CUSTOMER_PO_NO',title:'PO NO',width:60, halign: 'center', align: 'center'},
				    {field:'EX_FACTORY',title:'EX-FACTORY',width:60, halign: 'center', align: 'center'},
				    {field:'ITEM_NO',title:'ITEM', width:50, halign: 'center'},
                    {field:'DESCRIPTION',title:'DESCRIPTION', width:100, halign: 'center'},
                    {field:'QTY',title:'QUANTITY', width:70, halign: 'center',align: 'right'},
                    {field:'CUSTOMER',title:'CUSTOMER', width:100, halign: 'center'},
                    {field:'INPUT',title:'INPUT', width:40, halign: 'center'},
                    {field:'CONTAINER_NO',title:'CONTAINER', width:80, halign: 'center'},
                    {field:'SEAL_NO',title:'SEAL', width:80, halign: 'center'}
			    ]]
				
			})
		})
        
		function filterData(){
            var tipe = $('#cmb_delivery_type').combobox('getValue');
            flagTipe = tipe;
            if(tipe=='UPDATE'){
                filterDataUpdate()
            }else if(tipe=='RESTORE'){
                filterDataRestore()
            }else{
                $.messager.alert('INFORMATION','Please Choose Type','info');
            }
			
		}

        function filterDataUpdate(){
			$('#dg').datagrid('load', {
				ex_factory: $('#ex_factory_date').datebox('getValue'),
				ex_factory_z: $('#ex_factory_date_z').datebox('getValue')
			});
			$('#dg').datagrid({
				url:'get_delivery_update.php'
			})
		   	$('#dg').datagrid('enableFilter');
		   	$('#save_approve').linkbutton('enable');
		}

        function filterDataRestore(){
			$('#dg').datagrid('load', {
				ex_factory: $('#ex_factory_date').datebox('getValue'),
				ex_factory_z: $('#ex_factory_date_z').datebox('getValue')
			});
			$('#dg').datagrid({
				url:'get_delivery_restore.php'
			})
		   	$('#dg').datagrid('enableFilter');
		   	$('#save_approve').linkbutton('enable');
		}

        function deliveryUpdate(){
           var dataRows = [];
            var rows = $('#dg').datagrid('getSelections');
            for(i=0;i<rows.length;i++){
				$('#dg').datagrid('endEdit',i);
				dataRows.push({
                    answer_no: rows[i].ANSWER_NO,
                    container_no: rows[i].CONTAINER_NO,
                    seal_no: rows[i].SEAL_NO  
                });
			}
            
            var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
            $.post('post_delivery_update.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_add').dialog('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>GR No. : '+$('#gr_no_add').textbox('getValue'),'info');
							$.messager.progress('close');
						}else{
							//$.post('gr_destroy.php',{gr_no: $('#gr_no_add').textbox('getValue')},'json');
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
        }

        function deliveryRestore(){
            var dataRows = [];
            var rows = $('#dg').datagrid('getSelections');
            for(i=0;i<rows.length;i++){
				$('#dg').datagrid('endEdit',i);
				dataRows.push({
                    answer_no: rows[i].ANSWER_NO
                });
			}
            
            var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
            $.post('post_delivery_restore.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_add').dialog('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>GR No. : '+$('#gr_no_add').textbox('getValue'),'info');
							$.messager.progress('close');
						}else{
							//$.post('gr_destroy.php',{gr_no: $('#gr_no_add').textbox('getValue')},'json');
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
        }

		function save_approve(){
            var tipe = $('#cmb_delivery_type').combobox('getValue');

            if(tipe=='UPDATE' && flagTipe == 'UPDATE'){
                deliveryUpdate()
            }else if(tipe=='RESTORE'&& flagTipe == 'RESTORE'){
                deliveryRestore()
            }else{
                $.messager.alert('Warning','Please Choose Type and Retry Filter','warning');
                $('#dg').datagrid('loadData', []); 
            }
		}
        var ans = ''
        function input_container(a){
            var tipe = $('#cmb_delivery_type').combobox('getValue');
            if(tipe=='UPDATE'){
                $('#dlg_input').dialog('open').dialog('setTitle', 'INPUT CONTAINER');	
                var rows = $('#dg').datagrid('getRows');
                var sealNo = '';
                var conNo = '';

                for(i=0;i<rows.length;i++){
                    if(a == rows[i].ANSWER_NO){
                        sealNo = rows[i].SEAL_NO;
                        conNo = rows[i].CONTAINER_NO;
                    }
                }
                $('#con_no').textbox('setValue',conNo);
                $('#seal_no').textbox('setValue',sealNo);
                ans = a;
            }else if(tipe=='RESTORE'){
                $.messager.alert('INFORMATION','Cannot Change Data','info');
            }else{
                $.messager.alert('INFORMATION','Please Choose Type','info');
            }
			
		}
        
        function saveCon(){
            
            var sealNo = $('#seal_no').textbox('getValue');
            var conNo = $('#con_no').textbox('getValue');
            var rows = $('#dg').datagrid('getRows');
            for(i=0;i<rows.length;i++){
                if(ans == rows[i].ANSWER_NO){
                    $('#dg').datagrid('updateRow', {
                        index: i,
                        row: {CONTAINER_NO: conNo,SEAL_NO:sealNo}
                    });
                }
			}
            $('#dlg_input').dialog('close');
           
        }

       
	</script>
</body>
</html>
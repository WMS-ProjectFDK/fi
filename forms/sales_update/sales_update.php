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
<title>SALES UPDATE/RESTORE</title>
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
		<fieldset style="border-radius:4px; border-radius:4px; width:940px; height:90px; float:left;"><legend><span class="style3"><strong> Sales Update / Restore </strong></span></legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Sales Type.</span>
					<select  style="width:300px;" name="cmb_delivery_type" id="cmb_delivery_type" class="easyui-combobox" require="true"
					data-options="panelHeight:'auto', 
						onSelect: function(rec){
							$('#ex_fact_date').combobox('enable');
							sts = '../json/json_inv_date.php?stsNya='+ $('#cmb_delivery_type').combobox('getValue');
							console.log(sts);
							$('#do_date').combobox({
								url: sts,
								valueField:'ex_factory_date',
								textField:'ex_factory_date_text'
							})
						}
					">
						<option value="None" selected>-- Select --</option>
                        <option value="UPDATE">Sales Update</option>
                        <option value="RESTORE" >Sales Restore</option>
                    </select>
				</div>	
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Invoice Date</span>
					<!-- <input style="width:100px;" name="do_date" id="do_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value=""/> -->
					<select style="width:200px;" name="do_date" id="do_date" class="easyui-combobox" 
						data-options=" panelHeight:'150px',
							onChange: function(rec){	
								console.log(rec.ex_factory_date);
							}">
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
			    <a href="javascript:void(0)" id="save_con" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveBL()" style="width:90px">SET</a>
		    </div>
		</fieldset>
		<div style="clear:both;"></div>
	</div>

    <div id="dlg_input" class="easyui-dialog" style="width: 300px;height: 40`0px;" closed="true" buttons="#dlg-buttons-qty" data-options="modal:true" align="center">
		<div class="fitem">
			<span style="width:75px;display:inline-block;">BL DATE</span>
			<input style="width: 200px;" name="bl_date_datebox" id="bl_date_datebox" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
		</div>
	</div>

	<table id="dg" title="SALES UPDATE/RESTORE" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>

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
                    {field:'DO_NO',title:'INV NO.',width:55, halign: 'center', align: 'center'},
				    {field:'DO_DATE',title:'INV DATE',width:60, halign: 'center', align: 'center'},
					{field:'CUSTOMER_CODE',title:'CUSTOMER CODE', width:60, halign: 'center'},
					{field:'CUSTOMER',title:'CUSTOMER', width:150, halign: 'center'},
					{field:'INPUT',title:'CHANGE<br> BL DATE', width:40, halign: 'center'},
				    {field:'BL_DATE',title:'BL DATE.',width:35, halign: 'center', align: 'center'},
				    {field:'CURR_MARK',title:'CURRENCY',width:40, halign: 'center', align: 'center'},
				    {field:'AMT_O',title:'AMOUNT ORIGINAL',width:60, halign: 'center', align: 'right'},
				    {field:'AMT_L',title:'AMOUNT LOCAL',width:60, halign: 'center', align: 'right'}
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
				do_date: $('#do_date').combobox('getValue')
			});
			$('#dg').datagrid({
				url:'get_sales_update.php'
			})
		   	$('#dg').datagrid('enableFilter');
		   	$('#save_approve').linkbutton('enable');
		}

        function filterDataRestore(){
			$('#dg').datagrid('load', {
				do_date: $('#do_date').combobox('getValue')
			});
			$('#dg').datagrid({
				url:'get_sales_restore.php'
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
                    do_no: rows[i].DO_NO,
                    bl_date: rows[i].BL_DATE 
                });
			}
            
            var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
            $.post('post_sales_update.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Data Saved','info');
				}else{
					$.messager.alert('ERROR',res,'warning');
				}

				$.messager.progress('close');
			});
        }

        function deliveryRestore(){
            var dataRows = [];
            var rows = $('#dg').datagrid('getSelections');
            for(i=0;i<rows.length;i++){
				$('#dg').datagrid('endEdit',i);
				dataRows.push({
                    do_no: rows[i].DO_NO,
                    bl_date: rows[i].BL_DATE 
                });
			}
            
            var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
            $.post('post_sales_restore.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Data Saved','info');
				}else{
					$.messager.alert('ERROR',res,'warning');
				}
				$.messager.progress('close');
			});
        }

		function save_approve(){
			$.messager.progress({
				msg:'save data...'
			});

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

        var ans = '';
        
		function input_bl_date(a){
            var tipe = $('#cmb_delivery_type').combobox('getValue');
            if(tipe=='UPDATE'){
                $('#dlg_input').dialog('open').dialog('setTitle', 'INPUT BL DATE');	
                var rows = $('#dg').datagrid('getRows');
                var bldate = '';

                for(i=0;i<rows.length;i++){
                    if(a == rows[i].DO_NO){
                        bldate = rows[i].BL_DATE;
                    }
                }
                $('#bl_date_datebox').datebox('setValue',bldate);
                ans = a;
            }else if(tipe=='RESTORE'){
                $.messager.alert('INFORMATION','Cannot Change Data','info');
            }else{
                $.messager.alert('INFORMATION','Please Choose Type','info');
            }
			
		}
        
        function saveBL(){
            var BL = $('#bl_date_datebox').datebox('getValue');
            var rows = $('#dg').datagrid('getRows');
            for(i=0;i<rows.length;i++){
                if(ans == rows[i].DO_NO){
                    $('#dg').datagrid('updateRow', {
                        index: i,
                        row: {BL_DATE: BL}
                    });
                }
			}
            $('#dlg_input').dialog('close');
        }
	</script>
</body>
</html>
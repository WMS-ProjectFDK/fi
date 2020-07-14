<?php
include("../connect/conn2.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SPAREPARTS BUDGET</title>
<link rel="icon" type="image/png" href="../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
	</script>
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../themes/color.css" />
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>

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
		<fieldset style="margin-left;border-radius:4px;height:50px;width:98%"><legend><span class="style3"><strong> Spareparts Budget </strong></span></legend>
			<div style="width:500px;float:left">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">YEAR</span>
					<select style="width:100px;" name="cmb_tahun" id="cmb_tahun" class="easyui-combobox" data-option = "panelHeight: auto">
						<option value="" selected="selected">--SELECT--</option>
						<?php echo '<option value='.intval(date(Y)-1).'>'.intval(date(Y)-1).'</option>'; ?>
						<?php echo '<option value='.intval(date(Y)).'>'.intval(date(Y)).'</option>'; ?>
						<?php echo '<option value='.intval(date(Y)+1).'>'.intval(date(Y)+1).'</option>'; ?>
					</select>
				</div>
			</div>
		</fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>

		<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="savedata()" style="width:200px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
	</div>

	</div>
	<table id="dg" title="BUDGET ENTRY" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;"fitColumns="true" rownumbers="true" showFooter="true" singleSelect="true">
	</table>
<script type="text/javascript">
	$(function(){
		// ini untuk otomoatis input budget ketika tahunu berjalan belum ada untuk tahun berikutnya
		var date = new Date();
		var day = date.getDate(); // ini hari	
		var month = date.getMonth()+1;	//ini bulan
		var yy = date.getYear();
		var year = (yy < 1000) ? yy + 1900 : yy;	//ini tahun

		// kirim  ke file sp_budget_cek.php melalui ajax
	});

	function filterData(){
		var thn = $('#cmb_tahun').datebox('getValue');

		if (thn == ''){
			$.messager.alert('INFORMATION','YEAR BUDGET NOT SELECTED','info');
		}else{
			$('#dg').datagrid('load', {
				tahun: $('#cmb_tahun').datebox('getValue')
			});
		}
		
		$('#dg').datagrid( {
			url: 'sp_budget_get.php',
			columns:[[
		    	{field:'DEPARTMENT', align:'left', width:40, title:'DEPARTMENT', halign: 'center'},
		    	{field:'STS', align:'left', width:40, title:'DEPARTMENT', halign: 'center'},
		    	{field:'JANUARY', align:'right', width:40, title:'JANUARY', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'FEBRUARY', align:'right', width:40, title:'FEBRUARY', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'MARCH', align:'right', width:40, title:'MARCH', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'APRIL', align:'right', width:40, title:'APRIL', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'MAY', align:'right', width:40, title:'MAY', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'JUNE', align:'right', width:40, title:'JUNE', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'JULY', align:'right', width:40, title:'JULY', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'AUGUST', align:'right', width:40, title:'AUGUST', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'SEPTEMBER', align:'right', width:40, title:'SEPTEMBER', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'OCTOBER', align:'right', width:40, title:'OCTOBER', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'NOVEMBER', align:'right', width:40, title:'NOVEMBER', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}},
		    	{field:'DECEMBER', align:'right', width:40, title:'DECEMBER', halign: 'center',editor:{type:'numberbox',options:{required:false,precision:0,groupSeparator:','}}}
			]],
			onClickRow:function(row){
				$(this).datagrid('beginEdit', row);
			}
		})
	}

	function savedata(){
		var dataRows = [];
		var t = $('#dg').datagrid('getRows');
		var total = t.length;
		var flag = 0;
	
		for(i=0;i<total;i++){
			$('#dg').datagrid('endEdit',i);

			dataRows.push({
				TAHUN:  $('#cmb_tahun').datebox('getValue'),
				DEPARTMENT: $('#dg').datagrid('getData').rows[i].DEPARTMENT,
				STS: $('#dg').datagrid('getData').rows[i].STS,
				JANUARY: parseInt($('#dg').datagrid('getData').rows[i].JANUARY) > 0 ? $('#dg').datagrid('getData').rows[i].JANUARY.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].JANUARY,
				FEBRUARY: parseInt($('#dg').datagrid('getData').rows[i].FEBRUARY) > 0 ? $('#dg').datagrid('getData').rows[i].FEBRUARY.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].FEBRUARY,
				MARCH: parseInt($('#dg').datagrid('getData').rows[i].MARCH) > 0 ? $('#dg').datagrid('getData').rows[i].MARCH.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].MARCH,
				APRIL: parseInt($('#dg').datagrid('getData').rows[i].APRIL) > 0 ? $('#dg').datagrid('getData').rows[i].APRIL.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].APRIL,
				MAY: parseInt($('#dg').datagrid('getData').rows[i].MAY) > 0 ? $('#dg').datagrid('getData').rows[i].MAY.replace(/,/g,''): $('#dg').datagrid('getData').rows[i].MAY,
				JUNE: parseInt($('#dg').datagrid('getData').rows[i].JUNE) > 0 ? $('#dg').datagrid('getData').rows[i].JUNE.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].JUNE,
				JULY: parseInt($('#dg').datagrid('getData').rows[i].JULY) > 0 ? $('#dg').datagrid('getData').rows[i].JULY.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].JULY,
				AUGUST: parseInt($('#dg').datagrid('getData').rows[i].AUGUST) > 0 ? $('#dg').datagrid('getData').rows[i].AUGUST.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].AUGUST,
				SEPTEMBER: parseInt($('#dg').datagrid('getData').rows[i].SEPTEMBER) > 0 ? $('#dg').datagrid('getData').rows[i].SEPTEMBER.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].SEPTEMBER,
				OCTOBER: parseInt($('#dg').datagrid('getData').rows[i].OCTOBER) > 0 ? $('#dg').datagrid('getData').rows[i].OCTOBER.replace(/,/g,''): $('#dg').datagrid('getData').rows[i].OCTOBER,
				NOVEMBER: parseInt($('#dg').datagrid('getData').rows[i].NOVEMBER) > 0 ? $('#dg').datagrid('getData').rows[i].NOVEMBER.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].NOVEMBER,
				DECEMBER: parseInt($('#dg').datagrid('getData').rows[i].DECEMBER) > 0 ? $('#dg').datagrid('getData').rows[i].DECEMBER.replace(/,/g,'') : $('#dg').datagrid('getData').rows[i].DECEMBER
			});
		}

		var myJSON=JSON.stringify(dataRows);
		var str_unescape=unescape(myJSON);

		$.post('sp_budget_save.php',{
			data: unescape(str_unescape)
		}).done(function(res){
			filterData();
		});

		console.log(str_unescape);
	}

	function getRowIndex(target){
	    var tr = $(target).closest('tr.datagrid-row');
	    return parseInt(tr.attr('datagrid-row-index'));
	}

	function addrow(target){
	    $('#dg').datagrid('beginEdit', getRowIndex(target));
	}

	function saverow(target){
	    $('#dg').datagrid('endEdit', getRowIndex(target));
	}
</script>
</body>
</html>

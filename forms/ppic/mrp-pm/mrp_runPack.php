<?php 
include("../../../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>MRP-PACKAGING</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
    <link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../themes/color.css">
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
	<?php 
	include ('../../../ico_logout.php'); 
	?>
	
	<div id="toolbar" style="margin-top: 5px;margin-bottom: 5px;width: 100%;">
		<div align="center" class="fitem" style="width: 100%;">
			<a href="javascript:void(0)" id="start_mrp" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="run_mrp_pack()" ><i class="fa fa-play"></i><br/>START</a>
		</div>
		<br/>
		<div id="progress" class="easyui-progressbar" style="margin-left: 20px; width: 96%;"></div><br/>
	</div>
	<table id="dg" title="MATERIAL REQUIREMENT PLAN PACKAGING" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>

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

		var n = 0;
		
		function cek_jumlah(){
			$.ajax({
				type: 'GET',
				url: 'json/json_jumlah_mrp_pack.php',
				data: { kode:'kode' },
				success: function(data){
					n = data[0].persen;
				}
			});
		}

		function p_bar(){
			var value = $('#progress').progressbar('getValue');
            if (value < 57){
                value = n;
                $('#progress').progressbar('setValue', value);
                setTimeout(arguments.callee, 200);
            }else{
            	$.messager.alert('INFORMATION','MRP PACKAGING FINISH','info');
            	$('#progress').progressbar('setValue', 0);
            	$('#start_mrp').linkbutton('enable');
            	//window.location.href = 'ito_state.php';
            }
		}

		function run_mrp_pack(){
				$('#start_mrp').linkbutton('disable');
				$.post('mrp_runPack_get.php',function(result){
					if (! result.success){
	                    $.messager.alert('ERROR',result.errorMsg,'warning');
	                    /*$.messager.show({
	                        title: 'Error',
	                        msg: result.errorMsg
	                    });*/
	                }
				},'json');

				setInterval(function(){ cek_jumlah() }, 1000);
				p_bar();
        }
	</script>
    </body>
    </html>
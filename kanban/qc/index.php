<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>QC Kanban System</title>
    <link rel="icon" type="../image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	<style>
	*{
	font-size:12px;
	}
	body {
		font-family:verdana,helvetica,arial,sans-serif;
		padding:20px;
		font-size:16px;
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
		<div id="p" class="easyui-panel" title="QC KANBAN SYSTEM" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'" style="width:100%;height:auto;padding:5px;">
			<fieldset style="float: left;margin-left:5px;border-radius:4px;width: 48%;height: auto;">
				<legend><strong>SUSPEND TRANSACTION</strong></legend>
				<div class="fitem" style="padding: 5px;" align="center">
					<a href="http://localhost/wms/kanban/qc/suspend.php" class="easyui-linkbutton c2" style="width:180px; height: 41px;display:inline-block;"><i class="fa fa-exchange" aria-hidden="true"></i> SUSPEND</a>
				</div>
			</fieldset>

			<fieldset style="position:absolute;margin-left:50%;border-radius:4px;width: 44%;height: auto;">
				<legend><strong>UNSUSPEND TRANSACTION</strong></legend>
			    <div class="fitem" style="padding: 5px;" align="center">
					<a href="http://localhost/wms/kanban/qc/unsuspend.php" class="easyui-linkbutton c2" style="width:180px; height: 41px;display:inline-block;"><i class="fa fa-exchange" aria-hidden="true"></i> UNSUSPEND</a>
				</div>
			</fieldset>

			<div style="clear:both;"></div>
			<div id="footer" style="padding:5px;" align="center">
		        <small><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></small>
		    </div>
		</div>
 	</body>
	</html>
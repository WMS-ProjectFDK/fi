
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Footer Rows in DataGrid - jQuery EasyUI Demo</title>
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
</head>
<body>
	<h2>Footer Rows in DataGrid</h2>
	<div class="demo-info">
		<div class="demo-tip icon-tip"></div>
		<div>The summary informations can be displayed in footer rows.</div>
	</div>
	<div style="margin:10px 0;"></div>
	<table class="easyui-datagrid" title="Footer Rows in DataGrid" style="width:700px;height:220px"
			data-options="
				url: 'json/datagrid17_data.json',
				fitColumns: true,
				singleSelect: true,
				rownumbers: true,
				showFooter: true
			">
		<thead>
			<tr>
				<th data-options="field:'itemid',width:80">Item ID</th>
				<th data-options="field:'productid',width:120">Product ID</th>
				<th data-options="field:'listprice',width:80,align:'right'">List Price</th>
				<th data-options="field:'unitcost',width:80,align:'right'">Unit Cost</th>
				<th data-options="field:'attr1',width:250">Attribute</th>
				<th data-options="field:'status',width:60,align:'center'">Status</th>
			</tr>
		</thead>
	</table>

</body>
</html>
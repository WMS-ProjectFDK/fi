<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Load parent/child nodes into tree - jQuery EasyUI Demo</title>
	<link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
	<script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.min.js"></script>
	<script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
</head>
<body>
	<h2>Load parent/child nodes into tree</h2>
	<p>The food categores are load from adjacency list.</p>
	<ul id="tt" class="easyui-tree"></ul>
</body>
	<script type="text/javascript">
		function convert(rows){
			function exists(rows, parentId){
				for(var i=0; i<rows.length; i++){
					if (rows[i].id == parentId) return true;
				}
				return false;
			}
			
			var nodes = [];
			// get the top level nodes
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				if (!exists(rows, row.parentId)){
					nodes.push({
						id:row.id,
						text:row.name,
						state: 'closed'
					});
				}
			}
			
			var toDo = [];
			for(var i=0; i<nodes.length; i++){
				toDo.push(nodes[i]);
			}

			while(toDo.length){
				var node = toDo.shift();	// the parent node
				// get the children nodes
				for(var i=0; i<rows.length; i++){
					var row = rows[i];
					if (row.parentId == node.id){
						if(row.link == ''){
							var child = {id:row.id,
									 	 text: row.name,
									 	 state: 'closed'
										};
						}else{
							var child = {id:row.id,
									 	 text: '<a href="'+row.pnama+'/'+row.link+'" style="text-decoration: none; color: black;">'+row.name+'</a>'
										};
						}
						
						if (node.children){
							node.children.push(child);
						} else {
							node.children = [child];
						}
						toDo.push(child);
					}
				}
			}
			return nodes;
		}
		
		$(function(){
	        $('#tt').tree({
	            url: 'wnx_tree_data.php',
				loadFilter: function(rows){
					return convert(rows);
				},
	            onClick: function(node){
	                if (node.state == 'closed'){
	                $(this).tree('expand', node.target);
	                } else {
	                $(this).tree('collapse', node.target);
	                }
	            }
	        })
		});
	</script>
</html>
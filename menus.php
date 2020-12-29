<?php 
session_start();
if(isset($_SESSION['id_wms'])){
	include("connect/conn.php");
?>
	<html>
	<head>
		<title></title>
		<link rel="StyleSheet" href="css/dtree.css" type="text/css" />
		<script type="text/javascript" src="js/dtree.js"></script>
	    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	    <link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="themes/icon.css">
		<script type="text/javascript" src="js/jquery.easyui.min.js"></script>
	</head>

	<body leftMargin='20'>

	<script>
	$(document).ready(function(){
		$('#listmenu').hide();
		$('body').css("background-color","#0E76BC"); 
		var xx = window.top.document.getElementsByTagName("frameset")[1];
		var sideopen = false;
		$(window.top.document.getElementsByTagName("frame")[1]).mouseover(function(){
			$('body').css("background-color","#FFFFFF"); 
			sideopen = true;
			var defside=20;
			var sideEffect = setInterval(function(){
				defside =  defside+50;
				xx.cols = defside+",*";
				if (sideopen==false){
					clearInterval(sideEffect);
					xx.cols = "20,*";
				}
				if (defside>=250){
					clearInterval(sideEffect);
				}
			}, 1);
			$('#listmenu').show();
		}).mouseout(function() {
			sideopen=false;
			xx.cols = "20,*";
			$('#listmenu').hide();
			$('body').css("background-color","#0E76BC"); 
		});
		$('#listmenu').click(function(){
			$.post("sessionvalidation.php")
				.done(function(res) {
					if(res=='no_session_variable'){
						alert('Session Expired!');
						var topwindow = window.top.document;
						topwindow.location.href = 'index.php';
					}
				}
			);
		});
	});
	</script>
	<style>
	.arrow-right {
		width: 0; 
		height: 0; 
		border-top: 10px solid transparent;
		border-bottom: 10px solid transparent; 
		border-left:10px solid gray;
	}
	body{
		background-color: #F4F4F4;
	}
	</style>
	<div style="position:fixed;right:5px;top:auto;bottom:auto;">
		<div class="arrow-right"></div>
	</div>

	<div class="dtree" id="listmenu">
	<script type="text/javascript">

d = new dTree('d');
d.config.target="right";		
d.config.folderLinks = false;

d.add(0,-1,'<B>RAYOVAC BATTERY INDONESIA</B>','','WMS Version 2.1');

<?php
$user_name = $_SESSION['id_wms'];

$sql_parent = "select distinct a.id_parent, a.menu_parent, len(a.id_parent) as jum from ztb_menu a
	left join ztb_user_access b on a.id = b.menu_id
	where a.id_parent !='0' and b.person_code = '$user_name' and b.access_view='T'
	order by len(a.id_parent), a.id_parent asc";
$data_parent = sqlsrv_query($connect, $sql_parent);

while ($dt_parent = sqlsrv_fetch_object($data_parent)) {
	
	echo "d.add(".$dt_parent->id_parent.",0,'".$dt_parent->menu_parent."','example01.html');";

	$sql_sub_parent = "select distinct a.id_sub_parent, a.menu_sub_parent
		from ztb_menu a
		left join ztb_user_access b on a.id = b.menu_id
		where a.id_parent='".$dt_parent->id_parent."' and b.person_code = '$user_name' and b.access_view='T'
		order by id_sub_parent asc";

	$data_sub_parent = sqlsrv_query($connect, $sql_sub_parent);

	while ($dt_sub_parent = sqlsrv_fetch_object($data_sub_parent)) {
		if (! IS_NULL($dt_sub_parent->id_sub_parent)){
			echo "d.add(".$dt_sub_parent->id_sub_parent.",".$dt_parent->id_parent.",'".$dt_sub_parent->menu_sub_parent."','example01.html');";
			$sql_menu = "select a.menu_name, a.link, a.id_menu from ztb_menu a
					left join ztb_user_access b on a.id = b.menu_id
					where a.id_sub_parent ='".$dt_sub_parent->id_sub_parent."' and b.person_code = '$user_name' and b.access_view='T'
					order by a.id_menu asc";
		}else{
			$sql_menu = "select a.menu_name, a.link, a.id_menu from ztb_menu a
					left join ztb_user_access b on a.id = b.menu_id
					where a.id_parent ='".$dt_parent->id_parent."' and b.person_code = '$user_name' and b.access_view='T'
					and a.id_sub_parent is null
					order by a.id_menu asc";
		}

		$data_menu = sqlsrv_query($connect, $sql_menu);

		while ($dt_menu = sqlsrv_fetch_object($data_menu)) {
			if (! IS_NULL($dt_sub_parent->id_sub_parent)){
				echo "d.add(".$dt_menu->id_menu.",".$dt_sub_parent->id_sub_parent.",'".$dt_menu->menu_name."','".$dt_menu->link."');";
			}else{
				echo "d.add(".$dt_menu->id_menu.",".$dt_parent->id_parent.",'".$dt_menu->menu_name."','".$dt_menu->link."');";
			}
		}					
	}
}
?>
document.write(d);
</script>
</div>
</body>
</html>
<?php } ?>
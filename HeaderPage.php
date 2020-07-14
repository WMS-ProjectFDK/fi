
<!DOCTYPE>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
<script>
$(document).ready(function(){
	$('#logo').show()
	var value = 0;
	var wid=120;
	var splash=0;
	var spopacity=50;
	setInterval(function() {
		if(value<=100){
			value=value+1;
			$('#logo').css({
				"opacity": (value/100),
				"filter": "alpha(opacity="+value+")"
			});
		}
		if(value>=100){
			if(wid<=512){
				$('#logo').css("width", wid);
				wid=wid+2;
			}
		}
		if(wid>=512){
			if(splash<=512){
				$('#splash').show();
				$('#splash').css("margin-left", splash);
				splash=splash+4;
			}
			if(splash>512){
				splash=splash+2;
				if(splash>=1000){
					splash=0;
				}
			}
		}
	}, 10);
});
</script>

<style>
	.footer{ 
		border: 10px solid green; 
		margin-bottom: 2px;
		width: 100%;
		height: 2px;
	};
</style>
</head>
<body leftmargin="0" topmargin="0">
	<div style="width:100%;position:absolute;background:#272729;border-bottom:2px solid #ED1C24;">
		<div style="float:left;background:url('images/logo-fdk-1.png') no-repeat;width:40%;height:90px;background-size:50% 100%;display:none;" id="logo">
			<div style="width:25px;background:#272729;height:100%;opacity:0.3;filter:alpha(opacity=30);display:none;" id="splash"></div>
		</div>
		<div style="float:right;background:url('images/fid.jpg') no-repeat;width:250px;height:80px;background-size:100% 100%;"></div>
		<!-- <image src="images/photo/<?php //echo $row_users[0]; ?>" width="90px" height="90px" align="right" /> -->
	</div>
	<div class="footer"></div>
</body>
</html>

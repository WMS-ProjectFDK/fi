
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
	<div style="width:100%;position:absolute;background:#0E76BC;">
		<div style="float:left;width:10%;height:30px;background-size:50% 100%;display:none;" id="logo"></div>
	</div>
	<div class="footer"></div>
</body>
</html>

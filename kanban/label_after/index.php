<?php 
session_start();
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php"); 
$usr = 0;
?>
<!DOCTYPE html>
<head>
    <link href="../../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
	<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
	<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
    <title>KANBAN LABEL SYSTEM [OUT]</title>
    <link rel="icon" type="../../image/png" href="../../favicon.png">
    <style>
    
        body {
            text-shadow: 4px 4px 4px #aaa;
        }
         
        .form-signin
        {
            max-width: 700px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading, .form-signin .checkbox
        {
            margin-bottom: 10px;
        }
        .form-signin .checkbox
        {
            font-weight: normal;
        }
        .form-signin .form-control
        {
            position: relative;
            font-size: 30px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form-signin .form-control:focus
        {
            z-index: 2;
        }
        .form-signin input[type="text"]
        {
            margin-bottom: 10px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        .account-wall
        {
            margin-top: 15px;
            padding: 15px 0px 15px 0px;
            background-color: #f7f7f7;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }
        .login-title
        {
            color: #555;
            font-size: 22px;
            font-weight: 400;
            display: block;
        }
        .profile-img
        {
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="container" style="position:fixed;width:100%;height:100%;background:url('../../images/fdk_bg1.png') no-repeat;background-size: 100% 100%;">
    <div align="right" style="width:100%; margin-top: 5px;"><input style="border-radius: 5px;" type="button" value="Go Fullscreen" onclick="requestFullScreen()"></div>
    <div class="row" style="margin-top: 0px;">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 class="text-center login-title"><font face="Comic Sans MS"><strong>LOGIN TO KANBAN SYSTEM<br/>PT. FDK INDONESIA</strong></font></h1>
            <div class="account-wall">
                <img class="profile-img" src="../../images/logo-fdk.png">
                <div class="form-signin">
                    <input type="text" id="usr" name="usr" onkeypress="log(event)" class="form-control" placeholder="username" autofocus="autofocus" autocomplete="off">
                    <input type="text" id="name" name="name" class="form-control" placeholder="worker name" disabled="true">
					<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:auto;padding:10px 10px;">
					<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="shift" id="shift1" value="shift1" disabled="true" />SHIFT-1</span>
					<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="shift" id="shift2" value="shift2" disabled="true" />SHIFT-2</span>
					<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="shift" id="shift3" value="shift3" disabled="true" />SHIFT-3</span>
					<hr>
					<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="label" id="line3" onclick="MeClick(this);" value="line3"/>LR03</span>
					<span style="width:100px;display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="label" id="line6" onclick="MeClick(this);" value="line6"/>LR6</span>
					<span style="width:100px;display:inline-block;"></span>
					<span id='span_ln1' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln1" value="ln1"/>#1</span>
					<span id='span_ln2' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln2" value="ln2"/>#2</span>
					<span id='span_ln3' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln3" value="ln3"/>#3</span>
					<span id='span_ln4' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln4" value="ln4"/>#4</span>
					<span id='span_ln5' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln5" value="ln5"/>#5</span>
					<span id='span_ln6' style="display:inline-block;font-size: 18px;font-weight: bold;"><input type="radio" name="lbl" id="ln6" value="ln6"/>#6</span>
					</fieldset><br/>
					<button class="btn btn-lg btn-danger btn-block" type="submit" id="submit" name="submit" onclick="login();"><strong>LOGIN</strong></button>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div style="padding:5px;" align="center">
        <small><font face="Comic Sans MS"><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></font></small>
    </div>
</div>

<script type="text/javascript">
	function MeClick(label){
		if(label.value == 'line3'){
			document.getElementById('span_ln3').style.display = 'none';
			document.getElementById('span_ln4').style.display = 'none';
			document.getElementById('span_ln5').style.display = 'none';
			document.getElementById('span_ln6').style.display = 'none';
		}else{
			document.getElementById('span_ln3').style.display = 'inline';
			document.getElementById('span_ln4').style.display = 'inline';
			document.getElementById('span_ln5').style.display = 'inline';
			document.getElementById('span_ln6').style.display = 'inline';
		}
	}

	$(function(){
		window.onload = function() {
    		requestFullScreen();
    	}	
	});
    
    var user = '';
    var name = '';
    var shift = 0;
    
    function log(event){
        user = document.getElementById('usr').value;
        var search = user.toUpperCase();
        document.getElementById('usr').value = search;
        
        if(event.keyCode == 13 || event.which == 13){
            user = document.getElementById('usr').value;
            $.ajax({
				type: 'GET',
				url: 'cek_login.php?usr='+user,
				data: { kode:'kode' },
				success: function(data){
					name = data[0].NAME.toUpperCase();
					document.getElementById('name').value = name;
                	var d = new Date();
				  	var n = parseInt(d.getHours());

				  	if (n >= 7 && n < 15){
				  		document.getElementById('shift1').checked = true;
				  		shift = 1;
				  	}else if (n >= 15 && n < 23){
				  		document.getElementById('shift2').checked = true;
				  		shift = 2;
				  	}else{
				  		document.getElementById('shift3').checked = true;
				  		shift = 3;
				  	}
				}
			})
        }
    }

    function login(){
    	var url;
    	var lb1 = '';
    	if(document.getElementById('line3').checked == true){
    		lb1 = 'LR03-';
    	}else if(document.getElementById('line6').checked == true){
    		lb1 = 'LR6-';
    	}

    	var lb2 = 0;
		if(document.getElementById('ln1').checked == true){
    		lb2 = 1;
    	}else if(document.getElementById('ln2').checked == true){
    		lb2 = 2;
    	}else if(document.getElementById('ln3').checked == true){
    		lb2 = 3;
    	}else if(document.getElementById('ln4').checked == true){
    		lb2 = 4;
    	}else if(document.getElementById('ln5').checked == true){
    		lb2 = 5;
    	}else if(document.getElementById('ln6').checked == true){
    		lb2 = 6;
    	}

    	if (lb1 == '' || lb2 == 0){
    		$.messager.alert('Warning','Please select Label line','warning');
    	}else{
    		url = '?user='+user+'&name='+name+'&shift='+shift+'&line='+lb1+lb2;
			location.href='session_on.php'+url;
    	}
    }

    function requestFullScreen() {
      var el = document.body;

      // Supports most browsers and their versions.
      var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen 
      || el.mozRequestFullScreen || el.msRequestFullScreen;

      if (requestMethod) {

        // Native full screen.
        requestMethod.call(el);

      } else if (typeof window.ActiveXObject !== "undefined") {

        // Older IE.
        var wscript = new ActiveXObject("WScript.Shell");

        if (wscript !== null) {
          wscript.SendKeys("{F11}");
        }
      }
    }
</script>

</body>
</html>
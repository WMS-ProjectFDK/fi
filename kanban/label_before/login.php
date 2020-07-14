<?php 
session_start();
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php"); 
$usr = 0;
?>
<!DOCTYPE html>
<head>
    <link href="../../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <title>KANBAN SYSTEM</title>
    <link rel="icon" type="../../image/png" href="../favicon.png">
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
            margin-top: 20px;
            padding: 40px 0px 20px 0px;
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
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 class="text-center login-title"><font face="Comic Sans MS"><strong>LOGIN TO KANBAN SYSTEM<br/>PT. FDK INDONESIA</strong></font></h1>
            <div class="account-wall">
                <img class="profile-img" src="../../images/logo-fdk.png">
                <form class="form-signin" method="get"><!-- method="post" -->
                    <input type="text" id="usr" name="usr" onkeypress="log(event)" class="form-control" placeholder="username" autofocus="autofocus" autocomplete="off">
                    <div id="popUp">
                        <div class="row">
                            <div class="col-md-4">
                                <span style="display:inline-block;font-size: 18px;">SHIFT</span>
                            </div>
                            <div class="col-md-8">
                                <span style="display:inline-block;font-size: 18px;">
                                    <input type="radio" name="SHIFT" id="shift1" value="1"/> SHIFT - 1
                                    <input type="radio" name="SHIFT" id="shift2" value="2"/> SHIFT - 2
                                    <input type="radio" name="SHIFT" id="shift3" value="3"/> SHIFT - 3
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><span style="display:inline-block;font-size: 18px;">LABEL LINE</span></div>
                            <div class="col-md-8">
                                <span style="display:inline-block;font-size: 18px;">
                                    <input type="radio" name="lr" id="lr03" value="lr03"/> LR03
                                    <input type="radio" name="lr" id="lr6" value="lr6"/> LR6
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><span style="display:inline-block;font-size: 18px;"></span></div>
                            <div class="col-md-8">
                                <span style="display:inline-block;font-size: 18px;">
                                    <input type="radio" name="line" id="line1" value="1"/> #1
                                    <input type="radio" name="line" id="line2" value="2"/> #2
                                    <input type="radio" name="line" id="line3" value="3"/> #3
                                    <input type="radio" name="line" id="line4" value="4"/> #4
                                    <input type="radio" name="line" id="line5" value="5"/> #5
                                    <input type="radio" name="line" id="line6" value="6"/> #6
                                </span>
                            </div>
                        </div>
                    </div>  
                    <br>  
                    <button class="btn btn-lg btn-danger btn-block" type="submit" id="submit" name="submit"><strong>LOGIN</strong></button>
                    <div style="height: 50px;"></div>

                    <?php 
                    if(isset($_GET['usr'])){
                        $usr = $_GET['usr'];

                        if($usr == ''){
                            echo "<script type='text/javascript'>alert('Maaf username salah 0');self.location.href = 'index.php?usr=0';</script>";
                        }else{
                            if($usr != 0){
                                $sql = "select * from ztb_worker where worker_id=$usr ";
                                $result = oci_parse($connect, $sql);
                                oci_execute($result);

                                $row = oci_fetch_object($result);

                                if(trim($usr) == trim($row->WORKER_ID) AND ($row->NAME != '' OR ! is_null($row->NAME))) {
                                    $_SESSION['kanban_label'] = $row->WORKER_ID;

                                    echo "<script type='text/javascript'>location.href='qc/index.php';</script>";
                                }else{
                                    echo "<script type='text/javascript'>alert('Maaf username salah');self.location.href = 'login.php?usr=0';</script>";
                                }
                            }
                        }
                    }
                    ?>

                </form>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div style="padding:5px;" align="center">
        <small><font face="Comic Sans MS"><i>&copy; Copyright 2017, PT. FDK INDONESIA</i></font></small>
    </div>
</div>

<script type="text/javascript">
    window.onload = function() {
     var myInput = document.getElementById('usr');
     myInput.onpaste = function(e) {
       e.preventDefault();
     }
    }
    
    function log(event){
        var user = document.getElementById('usr').value;
        var search = user.toUpperCase();
        document.getElementById('usr').value = search;
        
        if(event.keyCode == 13 || event.which == 13){
            var user = document.getElementById('usr').value;
            user.submit();
            requestFullScreen();
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
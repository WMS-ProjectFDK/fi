<!DOCTYPE html>
<html>
    <head>
        <title>MONITORING</title>
        <link rel="icon" type="image/png" href="../../favicon.png">
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
          <script src="../../plugins/bootstrap/js/jquery.min.js"></script>
          <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="../../plugins/bootstrap/css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
        <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
        <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/datagrid-filter.js"></script>
        <script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
        <script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
    </head>
    <style>
        *{
            margin:0;
            padding:0;
        }
        body{
            font-family: Georgia, serif;
            font-size: 20px;
            font-style: italic;
            font-weight: normal;
            letter-spacing: normal;
            background: #f0f0f0;
        }
        #content{
            background-color:#fff;
            width:750px;
            padding:40px;
            margin:0 auto;
            border-left:30px solid #1D81B6;
            border-right:1px solid #ddd;
            -moz-box-shadow:0px 0px 16px #aaa;
        }
        .head{
            font-family:Helvetica,Arial,Verdana;
            text-transform:uppercase;
            font-weight:bold;
            font-size:12px;
            font-style:normal;
            letter-spacing:3px;
            color:#888;
            border-bottom:3px solid #f0f0f0;
            padding-bottom:10px;
            margin-bottom:10px;
        }
        .head a{
            color:#1D81B6;
            text-decoration:none;
            float:right;
            text-shadow:1px 1px 1px #888;
        }
        .head a:hover{
            color:#f0f0f0;
        }
        #content h1{
            font-family:"Trebuchet MS",sans-serif;
            color:#1D81B6;
            font-weight:normal;
            font-style:normal;
            font-size:56px;
            text-shadow:1px 1px 1px #aaa;
        }
        #content h2{
            font-family:"Trebuchet MS",sans-serif;
            font-size:34px;
            font-style:normal;
            background-color:#f0f0f0;
            margin:40px 0px 30px -40px;
            padding:0px 40px;
            clear:both;
            float:left;
            width:100%;
            color:#aaa;
            text-shadow:1px 1px 1px #fff;
        }
        .profile-img
        {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

    </style>
    <body>
        <div>
            <div style="margin-top:0;"><h4 align="center">MONITORING ASSEMBLY</h4></div>
        </div>
        <table class="table1" align="center">
            <thead>
                <tr>
                    <td align="center" style="font-size: 12px;"><span id='demo'></span></td>
                    <th style="font-size: 20px; color: #000000;">LR01#1</th>
                    <th style="font-size: 20px; color: #800000;">LR03#1</th>
                    <th style="font-size: 20px; color: #FF0000;">LR03#2</th><!-- 
                    <th style="font-size: 20px; color: #00AAFF;">LR03#3</th> -->
                    <th style="font-size: 20px; color: #FFAA00;">LR06#1</th>
                    <th style="font-size: 20px; color: #FFFF00;">LR06#2</th>
                    <th style="font-size: 20px; color: #00FF00;">LR06#3</th>
                    <th style="font-size: 20px; color: #0000FF;">LR06#4(T)</th>
                    <th style="font-size: 20px; color: #AA00FF;">LR06#5</th>
                    <th style="font-size: 20px; color: #00FFFF;">LR06#6</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">USERNAME</th>
                    <td style="font-size: 12px; color: #000000;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr01_1"></div></td>
                    <td style="font-size: 12px; color: #800000;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr03_1"></div></td>
                    <td style="font-size: 12px; color: #FF0000;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr03_2"></div></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr03_3"></div></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_1"></div></td>
                    <td style="font-size: 12px; color: #FFFF00;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_2"></div></td>
                    <td style="font-size: 12px; color: #00FF00;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_3"></div></td>
                    <td style="font-size: 12px; color: #0000FF;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_4"></div></td>
                    <td style="font-size: 12px; color: #AA00FF;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_5"></div></td>
                    <td style="font-size: 12px; color: #00FFFF;"><img class="profile-img" src="../../images/user.png"><div class="account-wall" id="usr_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">TANGGAL PRODUKSI</th>
                    <td style="font-size: 12px; color: #000000;"><b><div id="tgl_prod_lr01_1"></div></b></td>
                    <td style="font-size: 12px; color: #800000;"><b><div id="tgl_prod_lr03_1"></div></b></td>
                    <td style="font-size: 12px; color: #FF0000;"><b><div id="tgl_prod_lr03_2"></div></b></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><b><div id="tgl_prod_lr03_3"></div></b></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><b><div id="tgl_prod_lr06_1"></div></b></td>
                    <td style="font-size: 12px; color: #FFFF00;"><b><div id="tgl_prod_lr06_2"></div></b></td>
                    <td style="font-size: 12px; color: #00FF00;"><b><div id="tgl_prod_lr06_3"></div></b></td>
                    <td style="font-size: 12px; color: #0000FF;"><b><div id="tgl_prod_lr06_4"></div></b></td>
                    <td style="font-size: 12px; color: #AA00FF;"><b><div id="tgl_prod_lr06_5"></div></b></td>
                    <td style="font-size: 12px; color: #00FFFF;"><b><div id="tgl_prod_lr06_6"></div></b></td>
                </tr>
                <tr>
                    <th scope="row">CELL TYPE</th>
                    <td style="font-size: 12px; color: #000000;"><div id="cell_type_lr01_1"></div></td>
                    <td style="font-size: 12px; color: #800000;"><div id="cell_type_lr03_1"></div></td>
                    <td style="font-size: 12px; color: #FF0000;"><div id="cell_type_lr03_2"></div></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><div id="cell_type_lr03_3"></div></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><div id="cell_type_lr06_1"></div></td>
                    <td style="font-size: 12px; color: #FFFF00;"><div id="cell_type_lr06_2"></div></td>
                    <td style="font-size: 12px; color: #00FF00;"><div id="cell_type_lr06_3"></div></td>
                    <td style="font-size: 12px; color: #0000FF;"><div id="cell_type_lr06_4"></div></td>
                    <td style="font-size: 12px; color: #AA00FF;"><div id="cell_type_lr06_5"></div></td>
                    <td style="font-size: 12px; color: #00FFFF;"><div id="cell_type_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">MULAI</th>
                    <td style="font-size: 11px; color: #000000;"><div id="mulai_lr01_1"></div></td>
                    <td style="font-size: 11px; color: #800000;"><div id="mulai_lr03_1"></div></td>
                    <td style="font-size: 11px; color: #FF0000;"><div id="mulai_lr03_2"></div></td><!-- 
                    <td style="font-size: 11px; color: #00AAFF;"><div id="mulai_lr03_3"></div></td> -->
                    <td style="font-size: 11px; color: #FFAA00;"><div id="mulai_lr06_1"></div></td>
                    <td style="font-size: 11px; color: #FFFF00;"><div id="mulai_lr06_2"></div></td>
                    <td style="font-size: 11px; color: #00FF00;"><div id="mulai_lr06_3"></div></td>
                    <td style="font-size: 11px; color: #0000FF;"><div id="mulai_lr06_4"></div></td>
                    <td style="font-size: 11px; color: #AA00FF;"><div id="mulai_lr06_5"></div></td>
                    <td style="font-size: 11px; color: #00FFFF;"><div id="mulai_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">AKHIR</th>
                    <td style="font-size: 11px; color: #000000;"><div id="akhir_lr01_1"></div></td>
                    <td style="font-size: 11px; color: #800000;"><div id="akhir_lr03_1"></div></td>
                    <td style="font-size: 11px; color: #FF0000;"><div id="akhir_lr03_2"></div></td><!-- 
                    <td style="font-size: 11px; color: #00AAFF;"><div id="akhir_lr03_3"></div></td> -->
                    <td style="font-size: 11px; color: #FFAA00;"><div id="akhir_lr06_1"></div></td>
                    <td style="font-size: 11px; color: #FFFF00;"><div id="akhir_lr06_2"></div></td>
                    <td style="font-size: 11px; color: #00FF00;"><div id="akhir_lr06_3"></div></td>
                    <td style="font-size: 11px; color: #0000FF;"><div id="akhir_lr06_4"></div></td>
                    <td style="font-size: 11px; color: #AA00FF;"><div id="akhir_lr06_5"></div></td>
                    <td style="font-size: 11px; color: #00FFFF;"><div id="akhir_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">QTY BOX</th>
                    <td style="font-size: 12px; color: #000000;"><div id="qty_box_lr01_1"></div></td>
                    <td style="font-size: 12px; color: #800000;"><div id="qty_box_lr03_1"></div></td>
                    <td style="font-size: 12px; color: #FF0000;"><div id="qty_box_lr03_2"></div></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><div id="qty_box_lr03_3"></div></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><div id="qty_box_lr06_1"></div></td>
                    <td style="font-size: 12px; color: #FFFF00;"><div id="qty_box_lr06_2"></div></td>
                    <td style="font-size: 12px; color: #00FF00;"><div id="qty_box_lr06_3"></div></td>
                    <td style="font-size: 12px; color: #0000FF;"><div id="qty_box_lr06_4"></div></td>
                    <td style="font-size: 12px; color: #AA00FF;"><div id="qty_box_lr06_5"></div></td>
                    <td style="font-size: 12px; color: #00FFFF;"><div id="qty_box_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">QTY PALLET</th>
                    <td style="font-size: 12px; color: #000000;"><div id="qty_pallet_lr01_1"></div></td>
                    <td style="font-size: 12px; color: #800000;"><div id="qty_pallet_lr03_1"></div></td>
                    <td style="font-size: 12px; color: #FF0000;"><div id="qty_pallet_lr03_2"></div></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><div id="qty_pallet_lr03_3"></div></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><div id="qty_pallet_lr06_1"></div></td>
                    <td style="font-size: 12px; color: #FFFF00;"><div id="qty_pallet_lr06_2"></div></td>
                    <td style="font-size: 12px; color: #00FF00;"><div id="qty_pallet_lr06_3"></div></td>
                    <td style="font-size: 12px; color: #0000FF;"><div id="qty_pallet_lr06_4"></div></td>
                    <td style="font-size: 12px; color: #AA00FF;"><div id="qty_pallet_lr06_5"></div></td>
                    <td style="font-size: 12px; color: #00FFFF;"><div id="qty_pallet_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">PALLET NO.</th>
                    <td style="font-size: 12px; color: #000000;"><div id="pallet_no_lr01_1"></div></td>
                    <td style="font-size: 12px; color: #800000;"><div id="pallet_no_lr03_1"></div></td>
                    <td style="font-size: 12px; color: #FF0000;"><div id="pallet_no_lr03_2"></div></td><!-- 
                    <td style="font-size: 12px; color: #00AAFF;"><div id="pallet_no_lr03_3"></div></td> -->
                    <td style="font-size: 12px; color: #FFAA00;"><div id="pallet_no_lr06_1"></div></td>
                    <td style="font-size: 12px; color: #FFFF00;"><div id="pallet_no_lr06_2"></div></td>
                    <td style="font-size: 12px; color: #00FF00;"><div id="pallet_no_lr06_3"></div></td>
                    <td style="font-size: 12px; color: #0000FF;"><div id="pallet_no_lr06_4"></div></td>
                    <td style="font-size: 12px; color: #AA00FF;"><div id="pallet_no_lr06_5"></div></td>
                    <td style="font-size: 12px; color: #00FFFF;"><div id="pallet_no_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">TROUBLE (MENIT)</th>
                    <td style="font-size: 8px; color: #000000;"><div id="trouble_lr01_1"></div></td>
                    <td style="font-size: 8px; color: #800000;"><div id="trouble_lr03_1"></div></td>
                    <td style="font-size: 8px; color: #FF0000;"><div id="trouble_lr03_2"></div></td><!-- 
                    <td style="font-size: 8px; color: #00AAFF;"><div id="trouble_lr03_3"></div></td> -->
                    <td style="font-size: 8px; color: #FFAA00;"><div id="trouble_lr06_1"></div></td>
                    <td style="font-size: 8px; color: #FFFF00;"><div id="trouble_lr06_2"></div></td>
                    <td style="font-size: 8px; color: #00FF00;"><div id="trouble_lr06_3"></div></td>
                    <td style="font-size: 8px; color: #0000FF;"><div id="trouble_lr06_4"></div></td>
                    <td style="font-size: 8px; color: #AA00FF;"><div id="trouble_lr06_5"></div></td>
                    <td style="font-size: 8px; color: #00FFFF;"><div id="trouble_lr06_6"></div></td>
                </tr>
                <tr>
                    <th scope="row">Total QTY Hari ini (ribu)</th>
                    <td style="font-size: 20px; color: #000000;"><div id="qty_total_today_lr01_1"></div></td>
                    <td style="font-size: 20px; color: #800000;"><div id="qty_total_today_lr03_1"></div></td>
                    <td style="font-size: 20px; color: #FF0000;"><div id="qty_total_today_lr03_2"></div></td><!-- 
                    <td style="font-size: 20px; color: #00AAFF;"><div id="qty_total_today_lr03_3"></div></td> -->
                    <td style="font-size: 20px; color: #FFAA00;"><div id="qty_total_today_lr06_1"></div></td>
                    <td style="font-size: 20px; color: #FFFF00;"><div id="qty_total_today_lr06_2"></div></td>
                    <td style="font-size: 20px; color: #00FF00;"><div id="qty_total_today_lr06_3"></div></td>
                    <td style="font-size: 20px; color: #0000FF;"><div id="qty_total_today_lr06_4"></div></td>
                    <td style="font-size: 20px; color: #AA00FF;"><div id="qty_total_today_lr06_5"></div></td>
                    <td style="font-size: 20px; color: #00FFFF;"><div id="qty_total_today_lr06_6"></div></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row">Total QTY per Bulan (ribu)</th>
                    <td style="color: #000000;"><div id="qty_total_month_lr01_1"></div></td>
                    <td style="color: #800000;"><div id="qty_total_month_lr03_1"></div></td>
                    <td style="color: #FF0000;"><div id="qty_total_month_lr03_2"></div></td><!-- 
                    <td style="color: #00AAFF;"><div id="qty_total_month_lr03_3"></div></td> -->
                    <td style="color: #FFAA00;"><div id="qty_total_month_lr06_1"></div></td>
                    <td style="color: #FFFF00;"><div id="qty_total_month_lr06_2"></div></td>
                    <td style="color: #00FF00;"><div id="qty_total_month_lr06_3"></div></td>
                    <td style="color: #0000FF;"><div id="qty_total_month_lr06_4"></div></td>
                    <td style="color: #AA00FF;"><div id="qty_total_month_lr06_5"></div></td>
                    <td style="color: #00FFFF;"><div id="qty_total_month_lr06_6"></div></td>
                </tr>
            </tfoot>
        </table>
        
        <script type="text/javascript">
            
            $(function(){
                var lr1_1 = setInterval(function(){ my_lr1_1() }, 5000);
                var lr3_1 = setInterval(function(){ my_lr3_1() }, 5000);
                var lr3_2 = setInterval(function(){ my_lr3_2() }, 5000);
                var lr3_3 = setInterval(function(){ my_lr3_3() }, 5000);
                var lr6_1 = setInterval(function(){ my_lr6_1() }, 5000);
                var lr6_2 = setInterval(function(){ my_lr6_2() }, 5000);
                var lr6_3 = setInterval(function(){ my_lr6_3() }, 5000);
                var lr6_4 = setInterval(function(){ my_lr6_4() }, 5000);
                var lr6_5 = setInterval(function(){ my_lr6_5() }, 5000);
                var lr6_6 = setInterval(function(){ my_lr6_6() }, 5000);
                setTimeout(function(){
                   window.location.reload(1);
                }, 60000);
            })

            var myVar = setInterval(function(){myTimer()},1000);
            function myTimer() {
                $.ajax({
                    type: 'GET',
                    url: '../../forms/json/json_time_now.php',
                    data: { kode:'kode'},
                    success: function(data){
                        document.getElementById("demo").innerHTML = data[0].kode;
                    }
                });
                
            }

            function my_lr1_1(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr1_1.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr01_1").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr01_1").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr01_1").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr01_1").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr01_1").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr01_1").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr01_1").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr01_1").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr01_1").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr01_1").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr01_1").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr3_1(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr3_1.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr03_1").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr03_1").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr03_1").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr03_1").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr03_1").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr03_1").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr03_1").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr03_1").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr03_1").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr03_1").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr03_1").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr3_2(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr3_2.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr03_2").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr03_2").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr03_2").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr03_2").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr03_2").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr03_2").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr03_2").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr03_2").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr03_2").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr03_2").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr03_2").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr3_3(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr3_3.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr03_3").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr03_3").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr03_3").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr03_3").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr03_3").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr03_3").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr03_3").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr03_3").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr03_3").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr03_3").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr03_3").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_1(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_1.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_1").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_1").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_1").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_1").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_1").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_1").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_1").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_1").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_1").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_1").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_1").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_2(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_2.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_2").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_2").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_2").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_2").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_2").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_2").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_2").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_2").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_2").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_2").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_2").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_3(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_3.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_3").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_3").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_3").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_3").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_3").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_3").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_3").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_3").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_3").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_3").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_3").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_4(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_4.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_4").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_4").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_4").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_4").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_4").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_4").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_4").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_4").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_4").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_4").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_4").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_5(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_5.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_5").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_5").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_5").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_5").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_5").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_5").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_5").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_5").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_5").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_5").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_5").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

            function my_lr6_6(){
                $.ajax({
                    type: 'GET',
                    url: 'view_lr6_6.php',
                    dataType: 'json',
                    success: function(data){
                        document.getElementById("usr_lr06_6").innerHTML = data[0].worker_id+'<br/>'+data[0].nama;
                        document.getElementById("tgl_prod_lr06_6").innerHTML = data[0].tanggal_produksi;
                        document.getElementById("cell_type_lr06_6").innerHTML = data[0].cell_type;
                        document.getElementById("mulai_lr06_6").innerHTML = data[0].start_date;
                        document.getElementById("akhir_lr06_6").innerHTML = data[0].end_date;
                        document.getElementById("qty_box_lr06_6").innerHTML = data[0].qty_perbox;
                        document.getElementById("qty_pallet_lr06_6").innerHTML = data[0].qty_perpallet;
                        document.getElementById("pallet_no_lr06_6").innerHTML = data[0].pallet;
                        document.getElementById("trouble_lr06_6").innerHTML = data[0].trouble;
                        document.getElementById("qty_total_today_lr06_6").innerHTML = data[0].qty_harian;
                        document.getElementById("qty_total_month_lr06_6").innerHTML = data[0].qty_bulanan;
                    }
                });
            }

        </script>
    </body>
</html>
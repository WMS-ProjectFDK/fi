<?php include("../connect/conn.php");
$email = "";
$dept = "";
session_start();
$user_name = $_SESSION['id_wms'];
$nama = $_SESSION['name_wms'];
$qry = "select a.person_code, a.person, a.email, a.password, b.id_dept, b.description from person a left join ztb_person_dept b on a.person_code=b.person_code 
    where a.person_code='$user_name'";
$data = sqlsrv_query($connect, $qry);

$dt = sqlsrv_fetch_object($data);
$email = $dt->email;
$dept = $dt->id_dept;

$sql = "select a.person, b.description from person a inner join person_type b on a.person_type=b.type
    where a.person_code='$user_name' ";
$data_sql = sqlsrv_query($connect, $sql);
$dt_sql = sqlsrv_fetch_object($data_sql);

if($dt_sql->description == '' OR is_null($dt_sql->description)) {
    $ty = 'ADMINISTRATOR';
}else{
    $ty = $dt_sql->description;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Dashboard</title>
 <script language="javascript">
 function confirmLogOut(){
    var is_confirmed;
    is_confirmed = window.confirm("End current session?");
    return is_confirmed;
 }
  function showNote(jab,id_dept){
    sList = window.open("notifikasi.php?jab_login="+jab+"&id_dept="+id_dept, "list", "width=1020,height=520, scrollbars=1, resizeable=1");
}

</script>
<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../themes/icon.css">
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script src="../js/highcharts/highcharts.js"></script>
<script src="../js/highcharts/modules/exporting.js"></script>
<script src="../js/highcharts/modules/data.js"></script>
<script src="../js/highcharts/modules/drilldown.js"></script>

<script type="text/javascript">
    $(function () {
        $('#socontainer').highcharts({
            chart: {
                type: 'area'
            },
            title: {
                text: 'Kurs'
            },
            subtitle: {
                text: 'Kurs History'
            },
            xAxis: {
                categories: ['2014 22/12', '2014 23/12', '2014 24/12', '2014 25/12', '2014 26/12']
            },
            yAxis: {
                title: {
                    text: 'Amount'
                }
            },
            plotOptions: {
                area: {
                    marker: {
                        enabled: true,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'USD',
                data: [12301, 12390, 12988, 12612, 12100]
            }, {
                name: 'SGD',
                data: [9200, 9130, 9230, 9112, 9121]
            }, {
                name: 'JPY',
                data: [4090, 4093, 4500, 4202, 4540]
            }]
        });
    });

    $(function () {
        $('#fccontainer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'POTENTIAL SALES CHART'
            },
            subtitle: {
                text: 'Forecast Periode January 2015'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'PT. A',
                data: [49, 71, 106, 192, 144, 176]

            }, {
                name: 'PT. B',
                data: [83, 78, 98, 93, 106, 84]

            }, {
                name: 'PT. C',
                data: [48, 38, 39, 41, 47, 48]

            }]
        });
    });

    $(function () {
        $('#spcontainer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'SALES PERFORMANCE - AMOUNT IN MILLION(IDR)'
            },
            subtitle: {
                text: 'Years Of 2015'
            },
            xAxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'PT. A',
                data: [499, 715, 1064, 1292, 1440, 1760, 1356, 1485, 2164, 1941, 956, 544]

            }, {
                name: 'PT. B',
                data: [836, 788, 985, 934, 1060, 845, 1050, 1043, 912, 835, 1066, 923]

            }, {
                name: 'PT. C',
                data: [489, 388, 393, 414, 470, 483, 590, 596, 524, 652, 593, 512]

            }, {
                name: 'PT. D',
                data: [2424, 332, 345, 397, 526, 755, 574, 604, 476, 391, 468, 511]

            },{
                name: 'PT. E',
                data: [3240, 331, 395, 397, 506, 755, 571, 620, 400, 391, 680, 521]

            },{
                name: 'PT. F',
                data: [4320, 1332, 3345, 1397, 2526, 2755, 574, 1604, 1476, 3191, 1468, 5110]

            },{
                name: 'PT. G',
                data: [2424, 3332, 1345, 2397, 1526, 1505, 3574, 2604, 1406, 2391, 1568, 1511]

            },{
                name: 'PT. H',
                data: [1424, 2332, 2345, 3397, 3526, 1505, 1574, 1614, 1470, 1491, 1608, 1111]

            },{
                name: 'PT. I',
                data: [2424, 3320, 2325, 2702, 2260, 1755, 1704, 1604, 2406, 2390, 2608, 2511]

            }]
        });
    });

    $(function () {
        $('#mscontainer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'MONTHLY SALES CHART - AMOUNT IDR'
            },
            subtitle: {
                text: 'Based On Delivery Periode January 2015'
            },
            xAxis: {
                categories: [
                    'PT. A',
                    'PT. B',
                    'PT. C',
                    'PT. D',
                    'PT. E',
                    'PT. F',
                    'PT. G',
                    'PT. H',
                    'PT. I'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'JAN',
                data: [499, 715, 1064, 1292, 1440, 1760, 1356, 1485, 2164]

            }, {
                name: 'FEB',
                data: [836, 788, 985, 934, 1060, 845, 1050, 1043, 912]

            }, {
                name: 'MAR',
                data: [489, 388, 393, 414, 470, 483, 590, 596, 524]

            }, {
                name: 'APR',
                data: [2424, 332, 345, 397, 526, 755, 574, 604, 476]

            },{
                name: 'MAY',
                data: [3240, 331, 395, 397, 506, 755, 571, 620, 400]

            },{
                name: 'JUN',
                data: [4320, 1332, 3345, 1397, 2526, 2755, 574, 1604, 1476]

            },{
                name: 'JUL',
                data: [2424, 3332, 1345, 2397, 1526, 1505, 3574, 2604, 1406]

            }]
        });
    });


    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'POTENTIAL SALES CHART - AMOUNT IDR'
            },
            subtitle: {
                text: 'Based On Sales Order Periode January 2015'
            },
            xAxis: {
                categories: [
                    'PT. A',
                    'PT. B',
                    'PT. C',
                    'PT. D',
                    'PT. E',
                    'PT. F',
                    'PT. G',
                    'PT. H',
                    'PT. I'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'JAN',
                data: [499, 715, 1064, 1292, 1440, 1760, 1356, 1485, 2164]

            }]
        });
    });


    function changePass(){
       //alert();
        $('#dlg').dialog('open').dialog('setTitle','Your Profile');
        $('#fm').form('clear');
        $('#userid').textbox('setValue','<?=$user_name;?>');
        $('#nameUSR').textbox('setValue','<?=$nama;?>');
        $('#txt_email').textbox('setValue','<?=$email;?>');
        $('#cmb_dept').combobox('setValue','<?=$dept;?>');
    }

    function savePass(){
        //alert('save_password.php');
        $('#fm').form('submit',{
            url: 'save_password.php?nik='+'<?=$user_name;?>',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                //alert(result);
                var result = eval('('+result+')');
                
                if (result.errorMsg){
                    $.messager.alert('Error',result.errorMsg,'info');
                } else {
                    $('#dlg').dialog('close');
                    $.messager.alert('Success',result.successMsg,'info');
                }
                logout();
            }
        });
    }

    function logout(){
        $.messager.confirm('Confirmation','End current session?',function(r){
            if (r){
                $.post('../logout.php');
            }
        })
    }

    var myVar = setInterval(function(){myTimer()},1000);
    function myTimer() {
        var d = new Date();
        document.getElementById("demo").innerHTML = d.toLocaleTimeString().replace('.',':').replace('.',':');
    }

</script>
<style type="text/css">
    #commentForm {width: 100%;}
    #commentForm label { width: 240px; }
    #commentForm label.error, #commentForm button.error, #commentForm input.submit { margin-left: 5px; }
    .style1 {color: #0000FF}
    .style3 {
        font-size: 13px;
        color: #000000;
        font-weight: bold;
    }
    .style4 {
        font-size: 11px;
        color: #CC0000;
    }
    * { font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        font-size: 11px; }

    .mainboard {
        width: 60%;
        height: 500px;
        border-style: solid;
        border-width: 1px
    }
    .board_fb {
        position: absolute;
        top: 33px;
        padding:2px;
        margin-left:1130px;
        width: 160px;
        height: 550.5px;
        border-style: solid;
        border-width: 1px;
        border-color: #a8a8a8;
    }
    .boardfoto {
        position: absolute;
        top: 40px;
        width: 160px;
        height: 120x;
        border-style: solid;
        border-width: 0px;
    }   
    .board_bio {
        position: absolute;
        top: 200px;
        width: 160px;
        height: 200;
        border-style: solid;
        border-width: 0px;
    }   
    .board_1 {
        position: absolute;
        top: 360px;
        width: 160px;
        height: 200;
        border-style: solid;
        border-width: 0px;
    }   
</style>
</head>
<body> <!-- onload="changePass()" -->
<?php
    //if($dt->PERSON=='' OR $dt->EMAIL=='' or $dt->ID_DEPT=='' OR $dt->DESCRIPTION==''){
    //    echo "<script type='text/javascript'>$.messager.alert('USER NAME',Data user Mohon di lengkapi,'info');</script>";
    //}
?>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePass()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:auto;height:auto;padding:10px 20px" closed="true" buttons="#dlg-buttons" data-options="modal:true, position: 'center'">
    <form id="fm" method="post" novalidate>
        <fieldset style="margin: 2px;"><legend>Profile</legend>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">UserID</span>
            <input name="userid" id="userid" class="easyui-textbox" style="width:200px;" required="true" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38" disabled="">
        </div>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">Name</span>
            <input name="nameUSR" id="nameUSR" class="easyui-textbox" style="width:200px;" required="true" data-options="prompt:'your name',iconCls:'icon-man',iconWidth:38">
        </div>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">E-mail</span>
            <input name="txt_email" id="txt_email" class="easyui-textbox" style="width:200px;" required="true" data-options="prompt:'your email...', iconCls:'icon-man', iconWidth:38">
        </div>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">Dept</span>
            <select style="width:200px;" name="cmb_dept" id="cmb_dept" class="easyui-combobox" data-options=" url:'../forms/json/json_dep_budget.php', method:'get', valueField:'ID_DEPT', textField:'DEPARTEMENT', panelHeight:'50px', required:true"></select>
        </div>
        </fieldset>
        <fieldset><legend>Change Password</legend>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">Old Password</span>
            <input name="password" id="password" type="password" class="easyui-textbox" style="width:200px;" required="true" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38">
        </div>
        <div class="fitem" style="margin-bottom:10px;">
            <span style="width:100px;display:inline-block;">New Password</span>
            <input name="newpassword" id="newpassword" type="password" maxlength="10" class="easyui-textbox" style="width:200px;" required="true" data-options="prompt:'New Password (max:8 char)',iconCls:'icon-lock',iconWidth:38">
        </div>
        </fieldset>
    </form>
</div>
<div style="margin: 0px 0px;">
    <table width="100%">
        <tr>
           <td align="left" width="92%" valign="middle"><marquee><span class="style4"><?php echo "Welcome ".$nama.", you are login as ".$ty." PT. FDK INDONESIA";?></span></marquee></td>
           <td align="right" width="8%"><span id='demo'></span></td>       
           <td style="width: 19px;height: 19px;" class="ico" nowrap="nowrap" align="right"><a href="javascript:void(0)" onclick="changePass()"><i class="fa fa-user fa-2x" aria-hidden="true"></i></a></td>
            <?php
                //$sql="select * from rfq where rfq_end_user='".$user_name."' and rfq_keterangan is null";
                /*$sql="select * from rfq where rfq_keterangan is null";
                $query=pg_query($sql);
                $jml=pg_num_rows($query);
                if($jml==0){*/
                ?>
                    <!-- <td class="ico" nowrap="nowrap" align="right"><a href="../forms/purchasing_notification.php"><img src="../images/message.png" alt="End current session" width="19" height="19" border="0" title="Notification" /></a></td>       -->
            <?php
                /*}
                else{*/
                    ?>
                    <!-- <td class="ico" nowrap="nowrap" align="right"><a href="../forms/purchasing_notification.php"><img src="../images/message2.png" alt="End current session" width="19" height="19" border="0" title="Anda Memiliki Pemberitahuan" /></a></td> -->
                    <?php
                //}
            
            ?>
            <td align="right" width="2%" valign="middle"><a href="../logout.php" onClick="return confirmLogOut()" target="_top" title="Sign-Out"><i class="fa  fa-sign-out fa-2x" aria-hidden="true"></i></a></td>
        </tr>
    </table>
</div>
<fieldset style="width:auto;height:510px;"><legend><span class="style3">Dashboard</span></legend>
    <div style="width:auto; height:510px; background:url('../images/fdki-3.png') no-repeat; background-size: 100% 97%;"></div>
    <!-- <div style="both:clear;">
    </div>
    <div style="width:640px; height:250px; border:1px solid #d0d0d0; float:left;">
        <div id="fccontainer" style="min-width: 310px; height: 250px; margin: 0 auto"></div>
    </div>
    <div style="margin-left:10px;width:640px; height:250px; border:1px solid #d0d0d0; float:left;">
        <div id="mscontainer" style="min-width: 310px; height: 250px; margin: 0 auto"></div>
    </div>
    <div style="margin-top:10px; width:640px; height:250px; border:1px solid #d0d0d0; float:left;">
        <div id="container" style="min-width: 310px; height: 250px; margin: 0 auto"></div>
    </div>
    <div style="margin-top:10px; margin-left:10px;width:640px; height:250px; border:1px solid #d0d0d0; float:left;">
        <div id="spcontainer" style="min-width: 310px; height: 250px; margin: 0 auto"></div>
    </div>
    <div style="both:clear;">
    </div> -->
</fieldset>
</body>
</html>
<!--  -->

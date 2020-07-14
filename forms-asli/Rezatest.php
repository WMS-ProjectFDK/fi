<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>


<!DOCTYPE html>
 <html>
<head>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/demo/demo.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="http://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
    </head>
    <body>
    
    <div class="demo-info">
    <div class="demo-tip icon-tip"></div>
    </div>
    <div style="margin:10px 0;"></div>
    <table id="dg" class="easyui-datagrid" title="Tambah Item" style="width:700px;height:auto"
    data-options="
    iconCls: 'icon-edit',
    singleSelect: true,
    toolbar: '#tb',
    url: 'datagrid_data.json',
    method: 'get',
    onClickRow: onClickRow
    ">
    <thead>
    <tr>
    <th data-options="field:'itemid',width:80,editor:'text'" onchange="getItem()">Item ID</th>
    <input style="width:135px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" data-options="url:'json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line', panelHeight:'180px'" required="" />
    <th data-options="field:'listprice',width:80,align:'right',editor:{type:'numberbox',options:{precision:1}}">List Price</th>
    <th data-options="field:'unitcost',width:80,align:'right',editor:'numberbox'">Unit Cost</th>
    <th data-options="field:'attr1',width:250,editor:'text'">Attribute</th>
    <th data-options="field:'status',width:60,align:'center',editor:{type:'checkbox',options:{on:'P',off:''}}">Status</th>
    </tr>
    </thead>
    </table>

    <div id="tb" style="height:auto">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Append</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Remove</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Accept</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Reject</a>

    </div>
    <script type="text/javascript">
                    var editIndex = undefined;
                    function endEditing(){
                        if (editIndex == undefined){return true}
                        if ($('#dg').datagrid('validateRow', editIndex)){
                            // var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'productid'});
                            // var productname = $(ed.target).combobox('getText');
                            // $('#dg').datagrid('getRows')[editIndex]['productname'] = productname;
                            // $('#dg').datagrid('endEdit', editIndex);
                            // editIndex = undefined;
                            return true;
                        } else {
                            return false;
                        }
                    }
                    function onClickRow(index){
                        if (editIndex != index){
                            if (endEditing()){
                                $('#dg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index);
                                editIndex = index;
                            } else {
                                $('#dg').datagrid('selectRow', editIndex);
                            }
                        }
                    }
                    function append(){
                        if (endEditing()){
                            $('#dg').datagrid('appendRow',{status:'P'});
                            editIndex = $('#dg').datagrid('getRows').length-1;
                            $('#dg').datagrid('selectRow', editIndex)
                            .datagrid('beginEdit', editIndex);
                        }
                    }
                    function removeit(){
                        if (editIndex == undefined){return}
                        $('#dg').datagrid('cancelEdit', editIndex)
                        .datagrid('deleteRow', editIndex);
                        editIndex = undefined;
                    }
                    function accept(){
                        if (endEditing()){
                            $('#dg').datagrid('acceptChanges');
                        }
                    }
                    function reject(){
                        $('#dg').datagrid('rejectChanges');
                        editIndex = undefined;
                    }
                    function getChanges(){
                        var rows = $('#dg').datagrid('getChanges');
                        alert(rows.length+' rows are changed!');
                    }

                    $('#listcombo').combobox({
                        url:'get_lists.php',
                        valueField:'id',
                        textField:'listName',
                        panelHeight:'auto', 
                        onSelect:function(record){
                    $('#dg').datagrid({
                    url:"get_users.php?id="+record.id 
                        });
                    }
}); 
   </script>
    </body>
    </html>

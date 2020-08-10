<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%

'-- 2015/04/21 NTTk)Hino  項目追加対応（CAPACITY他 計28項目）


KEYWORD = Request("KEYWORD")


  call html_header("Item Seek Results",KEYWORD)
%>
<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword
%>
<%
'***********************
' KEY CHECK
'***********************
'mod 2015/04/21 Start
' IF Request.form("key") = "" THEN
 KEY = Request.Form("key")
 If KEY = "" Then
   KEY = Request.QueryString("KEY")
 End If
 If KEY = "" Then
 'mod 2015/04/21 End
   MESSAGE = "INPUT SEEK KEY."
 End If
 
 SEEK_KEY = Request.Form("SEEK_KEY")
 If SEEK_KEY = "" Then
 	SEEK_KEY = Request.QueryString("SEEK_KEY")
 End If
%>
<%
'************************
' DATA GET SQL MAKE
'************************
 IF MESSAGE = "" AND SEEK_KEY = "ITEM NO" THEN
    SQL1 = "SELECT DELETE_TYPE, ITEM_NO, ITEM,DESCRIPTION, ITEM_CODE ,MAK " & _
          "FROM ITEM " & _
          "WHERE TO_CHAR(ITEM_NO, '99999999') LIKE '%" & KEY & "%' " & _
          "ORDER BY ITEM_NO"
 END IF
 IF MESSAGE = "" AND SEEK_KEY = "ITEM NAME or DESCRIPTION" THEN
    SQL1 = "SELECT DELETE_TYPE, ITEM_NO, ITEM,DESCRIPTION, ITEM_CODE ,MAK " & _
          "FROM ITEM " & _
          "WHERE ITEM LIKE '%" & UCase(KEY) & "%' " & _
          "   OR DESCRIPTION LIKE '%" & UCase(KEY) & "%' " & _
          "ORDER BY ITEM"
 END IF
'response.write SQL1
'response.end
 IF MESSAGE = "" THEN
   Set REC1 = DB.Execute(SQL1)
 END IF
%>

<!-- add 2015/04/21 Start -->
<SCRIPT>
<!--
// BACKボタン押下処理
function go_back(){
	document.forms[0].method = "post";
	document.forms[0].action = "Item_Main.asp?KEYWORD=<%=KEYWORD%>&KEY=<%=KEY%>&SEEK_KEY=<%=SEEK_KEY%>";
	document.forms[0].submit();
}
//-->
</SCRIPT>
<!-- add 2015/04/21 End -->

<Div Align="left"><H3>Select Item that You Want Change or Delete.</H3></Div><Br>
<Div Align="center">
<Font Size="5" Color="blue" >
<B>Seek with<%= " " & SEEK_KEY & ":" %>
<U><% IF KEY = "" THEN %>
       &nbsp;
   <% ELSE
       RESPONSE.WRITE UCase(KEY)
      END IF %></U>
</B>
</Font>
</Div><Br>
<Div Align="center">
<Font Size="5" Color="#ff0000" ><B>
<% IF MESSAGE <> "" THEN
    RESPONSE.WRITE MESSAGE
   END IF%>
</B></Font>
</Div><Br>
<!-- DATA GET START -->
<Div Align="center">
<Form Method="post" Action="Item_Main.asp?KEYWORD=<%=KEYWORD%>" Name="myform">
 <!--<Input Type="Button" Value="BACK" onClick='history.back()'><BR>-->
 <Input Type="Button" Value="BACK" onClick='go_back()'><BR><!-- mod 2015/04/21 -->
</Form>
<Table Border="2" Width="80%" CellSpacing="1" BorderColor="#000000">
<Tr>
<Td Align="center" BgColor="#0080ff">Delete Type</Td>
<Td Align="center" BgColor="#0080ff">Item No</Td>
<Td Align="center" BgColor="#0080ff">Item Name<BR>Description</Td>
<Td Align="center" BgColor="#0080ff">Maker</Td>
<Td Align="center" BgColor="#0080ff">Item Code</Td>
<Td Align="center" BgColor="#0080ff">Update</Td>
<Td Align="center" BgColor="#0080ff">Delete</Td>
</Tr>

<% IF MESSAGE = "" THEN %>

<% IF REC1.EOF OR REC1.BOF THEN %>
 <Div Align="center">
 <B>
 <I>
 <Font Size="6"><Font Color="#ff0000">N</Font><Font Color="#ff2a00">o</Font>&nbsp;<Font Color="#ff7e00">D</Font><Font Color="#ffa800">a</Font><Font Color="#ffd200">t</Font><Font Color="#ffff00">a</Font>&nbsp;<Font Color="#abff00">F</Font><Font Color="#81ff00">o</Font><Font Color="#57ff00">u</Font><Font Color="#2dff00">n</Font><Font Color="#00ff00">d</Font>
 </I>
 </B>
 </Div>
<% ELSE
    DO WHILE NOT REC1.EOF
%>
<Tr>
<Td Align="center" BgColor="#ffffff"><%= REC1.FIELDS("DELETE_TYPE") %>&nbsp;</Td>
<Td Align="right" BgColor="#ffffff"><%= REC1.FIELDS("ITEM_NO") %></Td>
<Td Align="left" BgColor="#ffffff"><%= REC1.FIELDS("ITEM") %><BR><%= REC1.FIELDS("DESCRIPTION") %></Td>
<Td Align="left" BgColor="#ffffff"><%=REC1.FIELDS("MAK")%><BR></Td>
<Td Align="left" BgColor="#ffffff"><%= REC1.FIELDS("ITEM_CODE") %>&nbsp;</Td>
<Td Align="center" Valign="middle" BgColor="#FFFFFF">
<Form Method="post" Action="Item_Entry.asp?KEYWORD=<%=KEYWORD%>" Name="<%= "change" %>" Target="_self">
<Input Type="hidden" Name="type" Value="CHANGE">
<Input Type="hidden" Name="delete_type" Value="<%= REC1.FIELDS("DELETE_TYPE") %>">
<Input Type="hidden" Name="item_no" Value="<%= REC1.FIELDS("ITEM_NO") %>">
<Input Type="hidden" Name="grp_flg" Value=" ">
<Input Type="hidden" Name="KEY" Value="<%= KEY %>"><!-- add 2015/04/21 -->
<Input Type="hidden" Name="SEEK_KEY" Value="<%= SEEK_KEY %>"><!-- add 2015/04/21 -->
<Input Type="submit" Name="change_button" Value="Change">
</Form>
</Td>
<Td Align="center" Valign="middle" BgColor="#FFFFFF">
<Form Method="post" Action="Item_Entry.asp?KEYWORD=<%=KEYWORD%>" Name="<%= "delete" %>" Target="_self">
<Input Type="hidden" Name="type" Value="DELETE">
<Input Type="hidden" Name="delete_type" Value="<%= REC1.FIELDS("DELETE_TYPE") %>">
<Input Type="hidden" Name="item_no" Value="<%= REC1.FIELDS("ITEM_NO") %>">
<Input Type="hidden" Name="grp_flg" Value=" ">
<Input Type="hidden" Name="KEY" Value="<%= KEY %>"><!-- add 2015/04/21 -->
<Input Type="hidden" Name="SEEK_KEY" Value="<%= SEEK_KEY %>"><!-- add 2015/04/21 -->
<Input Type="submit" Name="delete_button" Value="Delete">
</Form>
</Td>
</Tr>


<% REC1.MOVENEXT
   LOOP
%>
<% END IF %>

<% END IF %>

</Table>
</Div>
</Body>
</Html>
<% DB.Close %>

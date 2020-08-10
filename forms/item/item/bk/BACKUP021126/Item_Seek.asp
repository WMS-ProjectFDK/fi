<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
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
 IF Request.form("key") = "" THEN
  MESSAGE = "INPUT SEEK KEY."
 END IF
%>
<%
'************************
' DATA GET SQL MAKE
'************************
 IF MESSAGE = "" AND REQUEST.FORM("SEEK_KEY") = "ITEM NO" THEN
    SQL1 = "SELECT DELETE_TYPE, ITEM_NO, ITEM,DESCRIPTION, ITEM_CODE " & _
          "FROM ITEM " & _
          "WHERE TO_CHAR(ITEM_NO, '99999999') LIKE '%" & REQUEST.FORM("KEY") & "%' " & _
          "ORDER BY ITEM_NO"
 END IF
 IF MESSAGE = "" AND REQUEST.FORM("SEEK_KEY") = "ITEM NAME or DESCRIPTION" THEN
    SQL1 = "SELECT DELETE_TYPE, ITEM_NO, ITEM,DESCRIPTION, ITEM_CODE " & _
          "FROM ITEM " & _
          "WHERE ITEM LIKE '%" & UCase(REQUEST.FORM("KEY")) & "%' " & _
          "   OR DESCRIPTION LIKE '%" & UCase(REQUEST.FORM("KEY")) & "%' " & _
          "ORDER BY ITEM"
 END IF
'response.write SQL1
'response.end
 IF MESSAGE = "" THEN
   Set REC1 = DB.Execute(SQL1)
 END IF
%>

<Div Align="left"><H3>Select Item that You Want Change or Delete.</H3></Div><Br>
<Div Align="center">
<Font Size="5" Color="blue" >
<B>Seek with<%= " " & request.form("seek_key") & ":" %>
<U><% IF request.form("key") = "" THEN %>
       &nbsp;
   <% ELSE
       RESPONSE.WRITE UCase(REQUEST.FORM("key"))
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
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
</Form>
<Table Border="2" Width="80%" CellSpacing="1" BorderColor="#000000">
<Tr>
<Td Align="center" BgColor="#0080ff">Delete Type</Td>
<Td Align="center" BgColor="#0080ff">Item No</Td>
<Td Align="center" BgColor="#0080ff">Item Name</Td>
<Td Align="center" BgColor="#0080ff">Description</Td>
<Td Align="center" BgColor="#0080ff">Item Code</Td>
<Td Align="center" BgColor="#0080ff">Update</Td>
<!-- ‘å™•ÏX
<Td Align="center" BgColor="#0080ff">Delete</Td>
-->
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
<Td Align="left" BgColor="#ffffff"><%= REC1.FIELDS("ITEM") %></Td>
<Td Align="left" BgColor="#ffffff"><%= REC1.FIELDS("DESCRIPTION") %>&nbsp;</Td>
<Td Align="left" BgColor="#ffffff"><%= REC1.FIELDS("ITEM_CODE") %>&nbsp;</Td>
<Td Align="center" Valign="middle" BgColor="#FFFFFF">
<Form Method="post" Action="Item_Entry.asp?KEYWORD=<%=KEYWORD%>" Name="<%= "change" %>" Target="_self">
<Input Type="hidden" Name="type" Value="CHANGE">
<Input Type="hidden" Name="delete_type" Value="<%= REC1.FIELDS("DELETE_TYPE") %>">
<Input Type="hidden" Name="item_no" Value="<%= REC1.FIELDS("ITEM_NO") %>">
<Input Type="hidden" Name="grp_flg" Value=" ">
<Input Type="submit" Name="change_button" Value="Change">
</Form>
</Td>
<Td Align="center" Valign="middle" BgColor="#FFFFFF">
<Form Method="post" Action="Item_Entry.asp?KEYWORD=<%=KEYWORD%>" Name="<%= "delete" %>" Target="_self">
<Input Type="hidden" Name="type" Value="DELETE">
<Input Type="hidden" Name="delete_type" Value="<%= REC1.FIELDS("DELETE_TYPE") %>">
<Input Type="hidden" Name="item_no" Value="<%= REC1.FIELDS("ITEM_NO") %>">
<Input Type="hidden" Name="grp_flg" Value=" ">
<!-- ‘å™•ÏX
<Input Type="submit" Name="delete_button" Value="Delete">
-->
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

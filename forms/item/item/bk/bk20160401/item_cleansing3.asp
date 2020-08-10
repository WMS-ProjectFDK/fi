<%@ LANGUAGE="VBScript" %>
<%
'ITEM MASTER CLEANSING
'DB操作プログラム
'2013.02.15 M.KAMIYAMA
%>
<!--#include file="../../common.inc"-->
<%
response.Charset="Shift_JIS"	'文字化け対策
response.Contenttype="text/html"	'省略時default

KEYWORD = request("KEYWORD")
SYSDATE = NOW

ERRORMSG = ""
ERRORSTEP = ""
MSG = "ITEM MASTER CLEANSING HAS DONE."

Set db = Server.CreateObject("ADODB.Connection")	'DBオブジェクトの作成
db.open server_name,server_user,server_psword		'DB接続のオープン

'DML TRANSACTION START
On Error Resume Next
db.Errors.Clear
db.BeginTrans

'BACKUP
ERRORSTEP = "BACKUP"
SQL = "INSERT INTO ITEM_REMOVED("
SQL = SQL & "UPTO_DATE,REG_DATE,DELETE_TYPE,ITEM_NO,ITEM_CODE,"
SQL = SQL & "ITEM,ITEM_FLAG,ORIGIN_CODE,DESCRIPTION,CLASS_CODE,"
SQL = SQL & "UOM_Q,UOM_W,UOM_L,SUPPLIER_CODE,EXTERNAL_UNIT_NUMBER,"
SQL = SQL & "STANDARD_PRICE,NEXT_TERM_PRICE,SUPPLIERS_PRICE,CURR_CODE,WEIGHT,"
SQL = SQL & "STOCK_SUBJECT_CODE,COST_SUBJECT_CODE,COST_PROCESS_CODE,MANUFACT_LEADTIME,PURCHASE_LEADTIME,"
SQL = SQL & "ADJUSTMENT_LEADTIME,REORDER_POINT,LLC_CODE,LEVEL_CONT_KEY,DRAWING_NO,"
SQL = SQL & "DRAWING_REV,APPLICABLE_MODEL,CATALOG_NO,ISSUE_POLICY,SECTION_CODE,"
SQL = SQL & "MANUFACT_FAIL_RATE,MAKER_FLAG,UNIT_STOCK,UNIT_STOCK_RATE,ISSUE_LOT,"
SQL = SQL & "SAFETY_STOCK,ORDER_POLICY,MAK,UNIT_ENGINEERING,UNIT_ENGINEER_RATE,"
SQL = SQL & "ITEM_TYPE1,ITEM_TYPE2,LAST_TERM_PRICE,ITEM_REMARK1,STOCK_ISSUE_FLAG,"
SQL = SQL & "RECEIVE_DATE,ISSUE_DATE,PACKAGE_UNIT_NUMBER,UNIT_PACKAGE,UNIT_PRICE_O,"
SQL = SQL & "UNIT_PRICE_RATE,UNIT_CURR_CODE,UPTO_PERSON_CODE,CUSTOMER_ITEM_NO,IS_SONY,"
SQL = SQL & "OPERATION_DATE,PERSON_CODE"
SQL = SQL & ")(SELECT "
SQL = SQL & "UPTO_DATE,REG_DATE,DELETE_TYPE,ITEM_NO,ITEM_CODE,"
SQL = SQL & "ITEM,ITEM_FLAG,ORIGIN_CODE,DESCRIPTION,CLASS_CODE,"
SQL = SQL & "UOM_Q,UOM_W,UOM_L,SUPPLIER_CODE,EXTERNAL_UNIT_NUMBER,"
SQL = SQL & "STANDARD_PRICE,NEXT_TERM_PRICE,SUPPLIERS_PRICE,CURR_CODE,WEIGHT,"
SQL = SQL & "STOCK_SUBJECT_CODE,COST_SUBJECT_CODE,COST_PROCESS_CODE,MANUFACT_LEADTIME,PURCHASE_LEADTIME,"
SQL = SQL & "ADJUSTMENT_LEADTIME,REORDER_POINT,LLC_CODE,LEVEL_CONT_KEY,DRAWING_NO,"
SQL = SQL & "DRAWING_REV,APPLICABLE_MODEL,CATALOG_NO,ISSUE_POLICY,SECTION_CODE,"
SQL = SQL & "MANUFACT_FAIL_RATE,MAKER_FLAG,UNIT_STOCK,UNIT_STOCK_RATE,ISSUE_LOT,"
SQL = SQL & "SAFETY_STOCK,ORDER_POLICY,MAK,UNIT_ENGINEERING,UNIT_ENGINEER_RATE,"
SQL = SQL & "ITEM_TYPE1,ITEM_TYPE2,LAST_TERM_PRICE,ITEM_REMARK1,STOCK_ISSUE_FLAG,"
SQL = SQL & "RECEIVE_DATE,ISSUE_DATE,PACKAGE_UNIT_NUMBER,UNIT_PACKAGE,UNIT_PRICE_O,"
SQL = SQL & "UNIT_PRICE_RATE,UNIT_CURR_CODE,UPTO_PERSON_CODE,CUSTOMER_ITEM_NO,IS_SONY,"
SQL = SQL & "to_date('" & SYSDATE & "', 'YYYY/MM/DD HH24:MI:SS'),'" & KEYWORD & "'"
SQL = SQL & " FROM ITEM"
SQL = SQL & " WHERE ITEM_NO IN (SELECT ITEM_NO FROM ITEM_CLEANSING_TMP WHERE REMOVABLE_FLAG = '0'))"

'response.write SQL
'response.end
db.execute(SQL)
if db.Errors.Count > 0 then
	Call ErrCatch()
end if

'DATA REMOVE
ERRORSTEP = "REMOVE"
SQL = "DELETE FROM ITEM WHERE ITEM_NO IN (SELECT ITEM_NO FROM ITEM_CLEANSING_TMP WHERE REMOVABLE_FLAG = '0')"

db.execute(SQL)
if db.Errors.Count > 0 then
	Call ErrCatch()
end if

if ERRORMSG = "" then
	Call commitTrans
end if
Call makeHTML
%>
<% 
Sub makeHTML()
	'***********************************************************
	' HTML生成
	'***********************************************************

	'冒頭の<html>～<body>タグは、html_headerが生成する（common.inc参照）
	call html_header("Item Cleansing Result", KEYWORD)
%>
<form method="post" action="./item_cleansing1.asp?KEYWORD=<%=KEYWORD%>" name="frm1" target="_self" id="frm1">
<div align="left">
	<div id="div-comment">
		<h3 align="left">Item Cleansing Result :</h3>
	</div>
	<div id="div-main">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="20px">&nbsp;</td>
		<td>
		<table border="1" cellpadding="10" cellspacing="1">
		<tr>
		<td>
			<% If ERRORMSG <> "" Then %>
				<font color="red"><%= ERRORMSG %></font>
			<% Else %>
				<font><%= MSG %></font>
			<% End If %>
		</td>
		</tr>
		</table>
		</td>
		</tr>
	</table>
	</div>
<br>
<br>
<input type=submit name="btnback" id="btnback" value="RETURN to first screen of ITEM CLEANSING">
</form>
<br>
</div>
</body>
</html>
<%
End Sub

Function cN(val)
	'数値型が""はnullをセットします。
	if val = "" then
		cN = "NULL"
	else
		cN = val
	end if
End Function

Function GET_SQL_VALUE(value1,value2)	
	IF value1 = "" OR ISNULL(value1) THEN
		GET_SQL_VALUE = "NULL"
	ELSE
		GET_SQL_VALUE = "'" & replace(value1,"'","''") & "'"
	End If
End Function

Sub ErrCatch()
	'エラーをハンドリングします。
	'response.write "error:" & Conn.Errors.Count
	db.RollbackTrans
	Db.close
	if ERRORSTEP = "BACKUP" then
		ERRORMSG = "ERROR HAS OCCURED IN BACKUP-PROCESS. PLEASE CONNTACT TO SYSTEM-DEPT."
	end if
	if ERRORSTEP = "REMOVE" then
		ERRORMSG = "ERROR HAS OCCURED IN REMOVE-PROCESS. PLEASE CONNTACT TO SYSTEM-DEPT."
	end if
End Sub

Sub CommitTrans()
	db.commit
	Db.close
End Sub

%>

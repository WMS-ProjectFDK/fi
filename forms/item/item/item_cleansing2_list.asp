<%@ LANGUAGE="VBScript" %>
<%
'   プログラム名 ITEM_CLEANSING2_LIST.asp
'   GET LIST FROM ITEM_CLEANSING_TMP
'   2013.02.15 M.KAMIYAMA
'2013.02.19 remove可能件数も返すように追加
%>
<!--#include file="../../common.inc"-->
<%

response.Charset="Shift_JIS"	'文字化け対策
response.Contenttype="text/html"	'省略時default

%>

<%
'****************************************
'   POSTデータ読み込み
'****************************************
DATATYPE = request("type")
ORDERBY = request("orderby")

'dbに接続します。
Set db = Server.CreateObject("ADODB.Connection")	'DBオブジェクトの作成
db.open server_name,server_user,server_psword		'DB接続のオープン


SQL = "SELECT A.ITEM_NO,A.SALES_CNT,A.LAST_SALES_DATE,A.PURCHASE_CNT,"
SQL = SQL & "A.LAST_PURCHASE_DATE,A.SO_CNT,A.LAST_SO_DATE,A.PO_CNT,A.LAST_PO_DATE,"
SQL = SQL & "A.TRN_CNT,A.LAST_SLIP_DATE,A.INQ_CNT,A.LAST_INQ_DATE,A.PRF_CNT,"
SQL = SQL & "A.LAST_PRF_DATE,A.WHI_CNT,A.REMOVABLE_FLAG,"
SQL = SQL & "B.ITEM,B.DESCRIPTION"
SQL = SQL & " FROM ITEM_CLEANSING_TMP A,ITEM B"
SQL = SQL & " WHERE B.ITEM_NO = A.ITEM_NO"
if DATATYPE = "OK" then
	SQL = SQL & " AND A.REMOVABLE_FLAG = '0'"
end if
if DATATYPE = "NG" then
	SQL = SQL & " AND A.REMOVABLE_FLAG = '1'"
end if

if ORDERBY = "ITEM NO" then
	SQL = SQL & " ORDER BY A.ITEM_NO"
end if
if ORDERBY = "ITEM" then
	SQL = SQL & " ORDER BY B.ITEM"
end if
if ORDERBY = "DESCRIPTION" then
	SQL = SQL & " ORDER BY B.DESCRIPTION"
end if
if ORDERBY = "SALES COUNT" then
	SQL = SQL & " ORDER BY A.SALES_CNT"
end if
if ORDERBY = "LAST SALES DATE" then
	SQL = SQL & " ORDER BY A.LAST_SALES_DATE"
end if
if ORDERBY = "PURCHASE COUNT" then
	SQL = SQL & " ORDER BY A.PURCHASE_CNT"
end if
if ORDERBY = "LAST PURCHASE DATE" then
	SQL = SQL & " ORDER BY A.LAST_PURCHASE_DATE"
end if
if ORDERBY = "SO COUNT" then
	SQL = SQL & " ORDER BY A.SO_CNT"
end if
if ORDERBY = "LAST SO DATE" then
	SQL = SQL & " ORDER BY A.LAST_SO_DATE"
end if
if ORDERBY = "PO COUNT" then
	SQL = SQL & " ORDER BY A.LAST_PO_DATE"
end if
if ORDERBY = "IO COUNT" then
	SQL = SQL & " ORDER BY A.TRN_CNT"
end if
if ORDERBY = "LAST IO DATE" then
	SQL = SQL & " ORDER BY A.LAST_SLIP_DATE"
end if
if ORDERBY = "INQ COUNT" then
	SQL = SQL & " ORDER BY A.INQ_CNT"
end if
if ORDERBY = "LAST INQ DATE" then
	SQL = SQL & " ORDER BY A.LAST_INQ_DATE"
end if
if ORDERBY = "PRF COUNT" then
	SQL = SQL & " ORDER BY A.PRF_CNT"
end if
if ORDERBY = "LAST PRF DATE" then
	SQL = SQL & " ORDER BY A.LAST_PRF_DATE"
end if
if ORDERBY = "WHI COUNT" then
	SQL = SQL & " ORDER BY A.WHI_CNT"
end if

'response.write SQL
'response.end
set rec = db.execute(SQL)
'該当するデータがない場合
If rec.BOF And rec.EOF Then
	rec.Close
	Set rec = Nothing
	response.write "NO DATA FOUND."
'2013.02.19 add m.kamiyama start
	response.write "<S>0"
'2013.02.19 add m.kamiyama end
	Db.close
	response.end
End If
%>
<font color="#FF0000">(SORTED:<%= ORDERBY %>)</font>&nbsp;Attention:COUNT-COLUMN'S number is DATA COUNT. It is not SUMMARY OF QUANTITY.
<table border="1" cellspacing="0" cellpadding="2" style="font-size:10pt;">
<tr style="background-color:#ADD8E6;">
<td style="text-align:center;" rowspan="2">SEQ</td>
<td style="text-align:center;" rowspan="2"><A HREF="JavaScript:sortList('ITEM NO')">ITEM NO</A></td>
<td style="text-align:center;" rowspan="2"><A HREF="JavaScript:sortList('ITEM')">ITEM</A></td>
<td style="text-align:center;" rowspan="2"><A HREF="JavaScript:sortList('DESCRIPTION')">DESCRIPTION</A></td>
<td style="text-align:center;" colspan="2">SALES</A></td>
<td style="text-align:center;" colspan="2">PURCHASE</A></td>
<td style="text-align:center;" colspan="4">REMAINDER</A></td>
<td style="text-align:center;" colspan="2">IN/OUT</A></td>
<td style="text-align:center;" colspan="2">INQ</A></td>
<td style="text-align:center;" colspan="2">PRF</A></td>
<td style="text-align:center;">WAREHOUSE</A></td>
</tr>
<tr style="background-color:#ADD8E6;">
<td style="text-align:center;"><A HREF="JavaScript:sortList('SALES COUNT')">COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST SALES DATE')">LAST DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('PURCHASE COUNT')">COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST PURCHASE DATE')">LAST DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('SO COUNT')">SO COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST SO DATE')">LAST SO DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('PO COUNT')">PO COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST PO DATE')">LAST PO DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('IO COUNT')">COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST IO DATE')">LAST DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('INQ COUNT')">COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST INQ DATE')">LAST DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('PRF COUNT')">COUNT</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('LAST PRF DATE')">LAST DATE</A></td>
<td style="text-align:center;"><A HREF="JavaScript:sortList('WHI COUNT')">COUNT</A></td>
</tr>
<%
num = 1
Do While Not rec.EOF

%>
	<tr>
	<td style="text-align:center;"><%= num %></td>
<%
if rec("REMOVABLE_FLAG") = "0" then
%>
	<td style="text-align:center;"><%= rec("ITEM_NO") %></td>
<%
else
%>
	<td style="text-align:center;background-color:#FF0000;"><%= rec("ITEM_NO") %></td>
<%
end if
if rec("REMOVABLE_FLAG") = "0" then
%>
	<td>
<%
else
%>
	<td style="background-color:#FF0000;">
<%
end if
	if IsNull(rec("ITEM")) then
		response.write "&nbsp;"
	else
		response.write "<pre style='display:inline;'>"&rec("ITEM")&"</pre>"
	end if
%>
	</td>
<%
if rec("REMOVABLE_FLAG") = "0" then
%>
	<td>
<%
else
%>
	<td style="background-color:#FF0000;">
<%
end if
	if IsNull(rec("DESCRIPTION")) then
		response.write "&nbsp;"
	else
		response.write "<pre style='display:inline;'>"&rec("DESCRIPTION")&"</pre>"
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("SALES_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("SALES_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_SALES_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_SALES_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("PURCHASE_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("PURCHASE_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_PURCHASE_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_PURCHASE_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("SO_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("SO_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_SO_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_SO_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("PO_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("PO_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_PO_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_PO_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("TRN_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("TRN_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_SLIP_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_SLIP_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("INQ_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("INQ_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_INQ_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_INQ_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("PRF_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("PRF_CNT")
	end if
%>
	</td>
	<td>
<%
	if IsNull(rec("LAST_PRF_DATE")) then
		response.write "&nbsp;"
	else
		response.write rec("LAST_PRF_DATE")
	end if
%>
	</td>
	<td style="text-align:right;">
<%
	if IsNull(rec("WHI_CNT")) then
		response.write "&nbsp;"
	else
		response.write rec("WHI_CNT")
	end if
%>
	</td>
	</tr>
<%
	num = num + 1
	rec.MoveNext
Loop
rec.Close
Set rec = Nothing
%>
</table>

<%
'2013.02.19 ADD M.KAMIYAMA START
SQL = "SELECT COUNT(A.ITEM_NO) CNT FROM ITEM_CLEANSING_TMP A WHERE REMOVABLE_FLAG = '0'"
set rec = db.execute(SQL)
response.write "<S>" & rec("cnt")
'2013.02.19 ADD M.KAMIYAMA END
Db.close
Function GET_SQL_VALUE_LIKE(value1)	
	GET_SQL_VALUE_LIKE = "'%" & replace(value1,"'","''") & "%'"
End Function

%>

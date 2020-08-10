<%@ LANGUAGE="VBScript" %>
<%
'   プログラム名 ITEM_CLEANSING_DL.asp
'   2013.02.14 M.KAMIYAMA
'EXCELで読み取れるXMLスプレッドシート形式のドキュメントを作成し、
'ダウンロードをさせます。拡張子はxlsでダウンロードされますが、中身はXMLです。
%>
<!--#include file="../../common.inc"-->

<%
'****************************************
'   POSTデータ読み込み
'****************************************
'dbに接続します。
Set db = Server.CreateObject("ADODB.Connection")	'DBオブジェクトの作成
db.open server_name,server_user,server_psword		'DB接続のオープン


filename = "remove_item_format.xls"

'HTML ヘッダを変更し、Excel の MIME コンテンツの種類を指定します。
'Response.Buffer = TRUE
Response.ContentType = "application/vnd.ms-excel"
response.AddHeader "content-disposition", "inline; filename=" & filename

%>
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author></Author>
  <LastAuthor></LastAuthor>
  <Created></Created>
  <LastSaved></LastSaved>
  <Company></Company>
  <Version>11.9999</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>7995</WindowHeight>
  <WindowWidth>17115</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>75</WindowTopY>
  <ActiveSheet>1</ActiveSheet>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font x:Family="Swiss"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s21">
   <NumberFormat ss:Format="General Date"/>
  </Style>
  <Style ss:ID="s25">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#FFFF00" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s26">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Interior ss:Color="#00FF00" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s27">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <NumberFormat ss:Format="@"/>
  </Style>
  <Style ss:ID="s28">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="s29">
   <NumberFormat ss:Format="@"/>
  </Style>
 </Styles>

<%
'VLOOKUP用ITEMマスタシートを作成します。
ITEM_R_CNT = 2
SQL = "SELECT A.ITEM_NO,A.ITEM,A.DESCRIPTION,A.DELETE_TYPE,A.REG_DATE,A.UPTO_DATE FROM ITEM A"
SQL = SQL & " ORDER BY A.ITEM_NO"
set rec = db.execute(SQL)
R_CNT = 1
do while not rec.eof
	R_CNT = R_CNT + 1
	rec.movenext
loop
ITEM_CNT = R_CNT
rec.movefirst
%>
 <Worksheet ss:Name="ITEM">
  <Table ss:ExpandedColumnCount="6" ss:ExpandedRowCount="<%= R_CNT %>" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="54" ss:DefaultRowHeight="13.5">
   <Column ss:Width="57"/>
   <Column ss:Width="228.75"/>
   <Column ss:Width="222"/>
   <Column ss:Width="78.75"/>
   <Column ss:Width="103.5" ss:Span="1"/>
   <Row>
    <Cell><Data ss:Type="String">ITEM_NO</Data></Cell>
    <Cell><Data ss:Type="String">ITEM</Data></Cell>
    <Cell><Data ss:Type="String">DESCRIPTION</Data></Cell>
    <Cell><Data ss:Type="String">DELETE_TYPE</Data></Cell>
    <Cell><Data ss:Type="String">REG_DATE</Data></Cell>
    <Cell><Data ss:Type="String">UPTO_DATE</Data></Cell>
   </Row>
<%
do while not rec.eof
%>
   <Row>
    <Cell ss:StyleID="s29"><Data ss:Type="String"><%= rec("ITEM_NO") %></Data></Cell>
    <Cell><Data ss:Type="String"><%= rec("ITEM") %></Data></Cell>
    <Cell><Data ss:Type="String"><%= rec("DESCRIPTION") %></Data></Cell>
    <Cell><Data ss:Type="String"><%= rec("DELETE_TYPE") %></Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String"><%= rec("REG_DATE") %></Data></Cell>
    <Cell ss:StyleID="s21"><Data ss:Type="String"><%= rec("UPTO_DATE") %></Data></Cell>
   </Row>
<%
	ITEM_R_CNT = ITEM_R_CNT + 1
	rec.movenext
loop
rec.close
set rec = nothing
%>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.51200000000000001"/>
    <Footer x:Margin="0.51200000000000001"/>
    <PageMargins x:Bottom="0.98399999999999999" x:Left="0.78700000000000003"
     x:Right="0.78700000000000003" x:Top="0.98399999999999999"/>
   </PageSetup>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
<%
'REMOVE_LISTシートを作成します。
WCNT = CInt(200)
%>
 <Worksheet ss:Name="REMOVE_LIST">
  <Table ss:ExpandedColumnCount="4" ss:ExpandedRowCount="<%= WCNT + 1 %>" x:FullColumns="1"
   x:FullRows="1" ss:DefaultColumnWidth="54"
   ss:DefaultRowHeight="13.5">
   <Column ss:StyleID="s25" ss:AutoFitWidth="0" ss:Width="63"/>
   <Column ss:AutoFitWidth="0" ss:Width="147.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="197.25"/>
   <Column ss:Width="78.75"/>
   <Row>
    <Cell ss:StyleID="s25"><Data ss:Type="String">ITEM NO</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">ITEM</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">DESCRIPTION</Data></Cell>
    <Cell ss:StyleID="s26"><Data ss:Type="String">DELETE TYPE</Data></Cell>
   </Row>
<%
Call add200rows(1)
%>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.51200000000000001"/>
    <Footer x:Margin="0.51200000000000001"/>
    <PageMargins x:Bottom="0.98399999999999999" x:Left="0.78700000000000003"
     x:Right="0.78700000000000003" x:Top="0.98399999999999999"/>
   </PageSetup>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
<%
Db.close

'-------------functions------------------------------------------------------
Function GET_SQL_VALUE_LIKE(value1)	
	GET_SQL_VALUE_LIKE = "'%" & replace(value1,"'","''") & "%'"
End Function

Function GET_SQL_VALUE(value1,value2)	
	IF value1 = "" OR ISNULL(value1) THEN
		GET_SQL_VALUE = "NULL"
	ELSE
		GET_SQL_VALUE = "'" & replace(value1,"'","''") & "'"
	End If
End Function

Sub add200rows(startrow)
	'空行を追加します
	for i = startrow to 200 step 1
%>
   <Row>
    <Cell ss:StyleID="s27"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s28" ss:Index="2" ss:Formula="=IF(RC1&lt;&gt;&quot;&quot;,VLOOKUP(RC1,ITEM!R2C1:R<%= ITEM_R_CNT %>C6,2,FALSE),&quot;&quot;)"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s28" ss:Formula="=IF(RC1&lt;&gt;&quot;&quot;,VLOOKUP(RC1,ITEM!R2C1:R<%= ITEM_R_CNT %>C6,3,FALSE),&quot;&quot;)"><Data ss:Type="String"></Data></Cell>
    <Cell ss:StyleID="s28" ss:Formula="=IF(RC1&lt;&gt;&quot;&quot;,VLOOKUP(RC1,ITEM!R2C1:R<%= ITEM_R_CNT %>C6,4,FALSE)&amp;&quot;&quot;,&quot;&quot;)"><Data ss:Type="String"></Data></Cell>
   </Row>
<%
	next
End Sub


%>
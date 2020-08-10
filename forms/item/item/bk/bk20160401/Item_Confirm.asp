<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'-- 2014/11/18 Y.Hagai    GRADE_CODE,CUSTOMER_TYPE,PACKAGE_TYPEの追加対応
'-- 2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）、「Package Type」をSelectBoxに変更
'-- 2015/06/26 NTTk)Hino  PACKING INFORMATIONのタイトル背景色を緑から薄青に変更
'-- 2015/10/28 NTTk)Hino  項目追加（Labeling Packaging Date他 計4項目）

KEYWORD = Request("KEYWORD")

'背景色
COLOR_PI = "#87CEEB" '(skyblue) 'add 2015/06/26

  call html_header("Item Entry Confirmation",KEYWORD)
%>
<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword
%>
<%
'-----------------------
' 小数以下桁数チェック
'-----------------------
Sub DECIMAL_CHECK(I_NUMBER, L_NUMBER, CHK_CODE)
 STR_I_NUMBER = CStr(I_NUMBER)
 STR_SPLIT = SPLIT(STR_I_NUMBER, ".")
 IF UBound(STR_SPLIT) > 0 THEN
  IF Len(STR_SPLIT(1)) <= L_NUMBER THEN
   CHK_CODE = "0"
  ELSE
   CHK_CODE = "1"
  END IF
 ELSE
  CHK_CODE = "0"
 END IF
End Sub

'--------------------------------------
' 小数桁数チェック (add 2015/04/21)
'   I_NUMBER: チェック対象の文字列
'   KETA1:    整数部分の桁数
'   KETA2:    小数部分の桁数
'   CHK_CODE: チェック結果格納変数
'--------------------------------------
Sub DECIMAL_CHECK_FULL(I_NUMBER, KETA1, KETA2, CHK_CODE)
 CHK_CODE = "0" '初期値
 
 STR_I_NUMBER = CStr(I_NUMBER)
 STR_SPLIT    = SPLIT(STR_I_NUMBER, ".")
 
 '小数点が存在する場合
 IF UBound(STR_SPLIT) > 0 THEN
 
  '整数部分のチェック
  IF Len(STR_SPLIT(0)) <= KETA1 THEN
   
   '小数部分のチェック
   IF UBound(STR_SPLIT) > 1 THEN
     IF Len(STR_SPLIT(1)) > KETA2 THEN
       CHK_CODE = "1"
     END IF
   END IF
   
  ELSE
   CHK_CODE = "1"
  END IF
 
 '小数点が存在しない場合
 ELSEIF UBound(STR_SPLIT) = 0 THEN
   
  '整数部分のチェック
  IF Len(STR_I_NUMBER) > KETA1 THEN
    CHK_CODE = "1"
  END IF
 END IF
End Sub


'---------------------------------
' 背景色セット (add 2015/04/21)
'---------------------------------
Function SET_BGCOLOR( p_FLG )
	If p_FLG = 1 Then
		SET_BGCOLOR = "#FF0000"
	Else
		SET_BGCOLOR = ""
	End If
End Function
%>
<%
'----------------------
' POST DATA CHECK
'----------------------
'*****************
' CHECK FLAG
'*****************
ERROR_MESSAGE = ""
ITEM_NO_FLG = 0
ITEM_FLG = 0
ITEM_FLAG_FLG = 0
ORIGIN_CODE_FLG = 0
CLASS_CODE_FLG = 0
'UOM_Q_FLG = 0
UOM_W_FLG =0
UOM_L_FLG = 0
EX_UNIT_NO_FLG = 0
STD_PRICE_FLG = 0
NEXT_TERM_PRICE_FLG = 0
SUPPLIERS_PRICE_FLG = 0
CURR_CODE_FLG = 0
WEIGHT_FLG = 0
STOCK_SUBJECT_FLG = 0
COST_SUBJECT_FLG = 0
COST_PROCESS_FLG = 0
MANU_LEAD_TIME_FLG = 0
PUR_LEAD_TIME_FLG = 0
ADJ_LEAD_TIME_FLG = 0
LAB_TO_PACK_DAY_FLG = 0  '(add 2015/10/28)
ASS_TO_LAB_DAY_FLG  = 0  '(add 2015/10/28)
SECTION_CODE_FLG = 0
MANU_FAIL_RATE_FLG = 0
MAKER_FLAG_FLG = 0
UNIT_STOCK_FLG = 0
UNIT_STOCK_R_FLG = 0
ISSUE_LOT_FLG = 0
SAFETY_STOCK_FLG = 0
UNIT_ENG_FLG = 0
UNIT_ENG_RATE_FLG = 0
PACKAGE_UNIT_FLG = 0
'-- add 2015/04/21 Start
CAPACITY_FLG = 0
DATE_CODE_TYPE_FLG = 0
DATE_CODE_MONTH_FLG = 0
LABEL_TYPE_FLG = 0
MEASUREMENT_FLG = 0
INNER_BOX_HEIGHT_FLG = 0
INNER_BOX_WIDTH_FLG = 0
INNER_BOX_DEPTH_FLG = 0
INNER_BOX_UNIT_NUMBER_FLG = 0
MEDIUM_BOX_HEIGHT_FLG = 0
MEDIUM_BOX_WIDTH_FLG = 0
MEDIUM_BOX_DEPTH_FLG = 0
MEDIUM_BOX_UNIT_NUMBER_FLG = 0
OUTER_BOX_HEIGHT_FLG = 0
OUTER_BOX_WIDTH_FLG = 0
OUTER_BOX_DEPTH_FLG = 0
PALLET_HEIGHT_FLG = 0
PALLET_WIDTH_FLG = 0
PALLET_DEPTH_FLG = 0
PALLET_UNIT_NUMBER_FLG = 0
PALLET_CTN_NUMBER_FLG = 0
PALLET_STEP_CTN_NUMBER_FLG = 0
PI_NO_FLG = 0
PLT_SPEC_NO_FLG = 0
OPERATION_TIME_FLG = 0
MAN_POWER_FLG = 0
PALLET_SIZE_TYPE_FLG = 0
AGING_DAY_FLG = 0
'-- add 2015/04/21 End
'************************
' ITEM NUMBER
'************************
IF Request.Form("item_no") = "" THEN
 ITEM_NO_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NO.<Br>"
ELSE
 IF REQUEST.FORM("type") = "NEW" THEN
  IF IsNumeric(Request.Form("item_no")) THEN
   SQL_item = "SELECT * FROM ITEM " & _
              "WHERE ITEM_NO = " & CLng(Request.Form("item_no"))
   SET Rec_item = DB.Execute(SQL_item)
   IF Rec_item.eof or Rec_item.bof THEN
    ITEM_NO_FLG = 0
   ELSE
    ITEM_NO_FLG = 1
    ERROR_MESSAGE = ERROR_MESSAGE & "ITEM_NO ALREADY EXIST.<Br>"
   END IF
  ELSE
   ITEM_NO_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NO ONLY NUMBER.<Br>"
  END IF
 END IF
END IF
'******************
' ITEM
'******************
IF REQUEST.FORM("item") = "" THEN
 ITEM_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NAME.<Br>"
END IF

'------------------------------------------------
IF REQUEST.FORM("type") <> "DELETE" THEN '(add 2015/10/28)
'------------------------------------------------

'******************
' ITEM_FLAG
'******************
IF REQUEST.FORM("item_flag") = "" THEN
 ITEM_FLAG_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT ITEM_FLAG.<Br>"
ELSE
 SQL_ITEMFLAG = "SELECT FLAG_NAME " & _
                "  FROM ITEMFLAG " & _
                " WHERE ITEM_FLAG = '" & CStr(REQUEST.FORM("ITEM_FLAG")) & "' "
 SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
 ITEM_FLAG = REC_ITEMFLAG.FIELDS("FLAG_NAME")
END IF
'*********************
' ORIGIN(COUNTRY CODE) GET
'*********************
IF REQUEST.FORM("origin_code") = "" THEN
 ORIGIN_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT ORIGIN.<Br>"
ELSE
  SQL_origin = "SELECT  COUNTRY " & _
 	" FROM COUNTRY " & _
 	" WHERE DELETE_TYPE IS NULL AND " & _
 	"       COUNTRY_CODE = " & CLng(Request.form("origin_code")) & " "
  Set Rec_origin = DB.Execute(SQL_origin)
   ORIGIN =  Rec_origin.Fields("COUNTRY")
END IF
'*********************
' CLASS CODE GET
'*********************
IF REQUEST.FORM("class_code") = "" THEN
 CLASS_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT CLASS.<Br>"
ELSE
  SQL_class = "SELECT  CLASS_1||CLASS_2||CLASS_3 AS CLASS " & _
 	" FROM CLASS " & _
 	" WHERE CLASS_CODE = " & CLng(Request.form("class_code")) & " "
  Set Rec_class = DB.Execute(SQL_class)
   CLASS1 = Rec_class.Fields("CLASS")
END IF
'*********************
' UNIT OF QUANTITY GET
'*********************
'IF REQUEST.FORM("uom_q") = "" THEN
' UOM_Q_FLG = 1
' ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF QTY.<Br>"
'ELSE
' SQL_uoq = "SELECT  UNIT " & _
' 	 " FROM UNIT " & _
' 	 " WHERE DELETE_TYPE IS NULL AND" & _
' 	 "       UNIT_CODE = " & CLng(Request.form("uom_q")) & " "
' Set Rec_uoq = DB.Execute(SQL_uoq)
'  UNIT_Q =  Rec_uoq.Fields("UNIT")
'END IF
'*********************
' UNIT OF WEIGHT GET
'*********************
IF REQUEST.FORM("uom_w") = "" THEN
 UOM_W_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF WEIGHT.<Br>"
ELSE
 SQL_uow = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("uom_w")) & " "
 Set Rec_uow = DB.Execute(SQL_uow)
  UNIT_W =  Rec_uow.Fields("UNIT")
END IF
'*********************
' UNIT OF LENGTH GET
'*********************
IF REQUEST.FORM("uom_l") = "" THEN
 UOM_L_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF LENGTH.<Br>"
ELSE
 SQL_uol = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("uom_l")) & " "
 Set Rec_uol = DB.Execute(SQL_uol)
  UNIT_L =  Rec_uol.Fields("UNIT")
END IF
'************************************
' EXTERNAL UNIT NUMBER
'************************************
IF Request.Form("external_unit_number") <> "" THEN
 IF IsNumeric(Request.Form("external_unit_number")) THEN
   EX_UNIT_NO_FLG = 0
 ELSE
  EX_UNIT_NO_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT EXTERNAL_UNIT_NUMBER ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' STANDARD PRICE
'************************************
IF REQUEST.FORM("standard_price") = "" THEN
 STD_PRICE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT STANDARD PRICE.<Br>"
ELSE
 IF IsNumeric(Request.Form("standard_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("standard_price"), 8, RESULT)
  IF RESULT = "0" THEN
   STD_PRICE_FLG = 0
  ELSE
   STD_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF STANDARD_PRICE IS 8.<Br>"
  END IF
 ELSE
  STD_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT STANDARD_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' NEXT TERM PRICE
'************************************
IF Request.Form("next_term_price") <> "" THEN
 IF IsNumeric(Request.Form("next_term_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("next_term_price"), 8, RESULT)
  IF RESULT = "0" THEN
   NEXT_TERM_PRICE_FLG = 0
  ELSE
   NEXT_TERM_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF NEXT_TERM_PRICE IS 8.<Br>"
  END IF
 ELSE
  NEXT_TERM_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT NEXT_TERM_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' SUPPLIERS PRICE
'************************************
IF Request.Form("suppliers_price") <> "" THEN
 IF IsNumeric(Request.Form("suppliers_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("suppliers_price"), 8, RESULT)
  IF RESULT = "0" THEN
   SUPPLIERS_PRICE_FLG = 0
  ELSE
   SUPPLIERS_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF SUPPLIERS_PRICE IS 8.<Br>"
  END IF
 ELSE
  SUPPLIERS_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT SUPPLIERS_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' CURRENCY CODE
'************************************
IF REQUEST.FORM("curr_code") = "" THEN
 CURR_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT CURRENCY MARK.<Br>"
ELSE
 SQL_currency = "SELECT  CURR_MARK " & _
	 " FROM CURRENCY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       CURR_CODE = " & CLng(Request.Form("curr_code")) & " "
 Set Rec_currency = DB.Execute(SQL_currency)
  CURR_MARK =  Rec_currency.Fields("CURR_MARK")
END IF
'************************************
' WEIGHT
'************************************
IF Request.Form("weight") <> "" THEN
 IF IsNumeric(Request.Form("weight")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("weight"), 5, RESULT)
  IF RESULT = "0" THEN
   WEIGHT_FLG = 0
  ELSE
   WEIGHT_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF WEIGHT IS 5.<Br>"
  END IF
 ELSE
  WEIGHT_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT WEIGHT ONLY NUMBER.<Br>"
 END IF
END IF
'*********************
' STOCK SUBJECT CODE GET
'*********************
IF REQUEST.FORM("stock_subject_code") = "" THEN
 STOCK_SUBJECT_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT STOCK SUBJECT.<Br>"
ELSE
 SQL_stock_subject = "SELECT  STOCK_SUBJECT " & _
 	 " FROM STOCK_SUBJECT " & _
 	 " WHERE STOCK_SUBJECT_CODE = " & CLng(Request.form("stock_subject_code")) & " "
 Set Rec_stock_subject = DB.Execute(SQL_stock_subject)
  STOCK_SUBJECT =  Rec_stock_subject.Fields("STOCK_SUBJECT")
END IF
'*********************
' SHORT NAME(SECTION) GET
'*********************
IF REQUEST.FORM("section_code") = "" THEN
 SECTION_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT SECTION SHORT NAME.<Br>"
ELSE
  SQL_section = "SELECT  SHORT_NAME " & _
 	" FROM SECTION " & _
 	" WHERE SECTION_CODE = " & CLng(Request.form("section_code")) & " "
  Set Rec_section = DB.Execute(SQL_section)
   SECTION =  Rec_section.Fields("SHORT_NAME")
END IF
'*********************
' COST SUBJECT CODE GET
'*********************
IF REQUEST.FORM("cost_subject_code") = "" THEN
 COST_SUBJECT_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT COST SUBJECT.<Br>"
ELSE
 SQL_costsubject = "SELECT  COST_SUBJECT_NAME " & _
 	 " FROM COSTSUBJECT " & _
 	 " WHERE COST_SUBJECT_CODE = '" & Request.form("cost_subject_code") & "' "
 Set Rec_costsubject = DB.Execute(SQL_costsubject)
  COST_SUBJECT =  Rec_costsubject.Fields("COST_SUBJECT_NAME")
END IF
'*********************
' COST PROCESS CODE GET
'*********************
IF REQUEST.FORM("cost_process_code") = "" THEN
 COST_PROCESS_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT COST PROCESS.<Br>"
ELSE
 SQL_costprocess = "SELECT  COST_PROCESS_NAME " & _
 	 " FROM COSTPROCESS " & _
 	 " WHERE COST_PROCESS_CODE = '" & Request.form("cost_process_code") & "' "
 Set Rec_costprocess = DB.Execute(SQL_costprocess)
  COST_PROCESS =  Rec_costprocess.Fields("COST_PROCESS_NAME")
END IF
'************************************
' MANUFACT LEAD TIME
'************************************
IF Request.Form("manufact_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("manufact_leadtime")) THEN
   MANU_LEAD_TIME_FLG = 0
 ELSE
  MANU_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT MANUFACT LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' PURCHASE LEAD TIME
'************************************
IF Request.Form("purchase_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("purchase_leadtime")) THEN
   PUR_LEAD_TIME_FLG = 0
 ELSE
  PUR_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT PURCHASE LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' ADJUSTMENT LEAD TIME
'************************************
IF Request.Form("adjustment_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("adjustment_leadtime")) THEN
   ADJ_LEAD_TIME_FLG = 0
 ELSE
  ADJ_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ADJUSTMENT LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'(add 2015/10/28 start)
'************************************
' Labeling To Packaging Day
'************************************
IF Request.Form("lab_to_pack_day") <> "" THEN
 IF IsNumeric(Request.Form("lab_to_pack_day")) THEN
  LAB_TO_PACK_DAY_FLG = 0
 ELSE
  LAB_TO_PACK_DAY_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT Labeling To Packaging Day ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' Assembling To Labeling Day
'************************************
IF Request.Form("assembling_to_lab_day") <> "" THEN
 IF IsNumeric(Request.Form("assembling_to_lab_day")) THEN
  ASS_TO_LAB_DAY_FLG = 0
 ELSE
  ASS_TO_LAB_DAY_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT Assembling To Labeling Day ONLY NUMBER.<Br>"
 END IF
END IF
'(add 2015/10/28 end)
'************************************
' ISSUE LOT 
'************************************
IF REQUEST.FORM("issue_policy") = "D" THEN
 IF REQUEST.FORM("issue_lot") = "" THEN
  ISSUE_LOT_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ISSUE LOT WHEN ISSUE POLICY IS &quot;Gloss&quot;.<Br>"
 ELSE
  IF IsNumeric(REQUEST.FORM("ISSUE_LOT")) THEN
   ISSUE_LOT_FLG = 0
  ELSE
   ISSUE_LOT_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ISSUE LOT ONLY NUMBER.<Br>"
  END IF
 END IF
END IF
'************************************
' MANUFACT FAIL RATE
'************************************
IF Request.Form("manufact_fail_rate") <> "" THEN
 IF IsNumeric(Request.Form("manufact_fail_rate")) THEN
   MANU_FAIL_RATE_FLG = 0
 ELSE
  MANU_FAIL_RATE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT MANUFACT FAIL RATE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' MAKER FLAG
'************************************
'RESPONSE.WRITE REQUEST.FORM("MAKER_FLAG")
'RESPONSE.END
IF Request.Form("maker_flag") = "" THEN
' MAKER_FLAG_FLG = 1
'  ERROR_MESSAGE = ERROR_MESSAGE & "SELECT MAKER FLAG.<Br>"
 MAKERFLAG = ""
ELSE
 '*************************
 'maker flag
 '*************************
 SQL_MAKERFLAG = "SELECT MAKER_FLAG " & _
                 "FROM MAKERFLAG " & _
                 "WHERE MAKER_FLAG_CODE = " & CLng(REQUEST.FORM("MAKER_FLAG"))
 SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
  MAKERFLAG = REC_MAKERFLAG.FIELDS("MAKER_FLAG")
END IF
'*********************
' UNIT STOCK GET
'*********************
IF REQUEST.FORM("unit_stock") = "" THEN
 UNIT_STOCK_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT STOCK.<Br>"
ELSE
 SQL_unit_stock = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("unit_stock")) & " "
 Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
  UNIT_STOCK =  Rec_unit_stock.Fields("UNIT")
END IF
'************************************
' UNIT STOCK RATE
'************************************
IF Request.Form("unit_stock_rate") <> "" THEN
 IF IsNumeric(Request.Form("unit_stock_rate")) THEN
  UNIT_STOCK_R_FLG = 0
 ELSE
  UNIT_STOCK_R_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT STOCK RATE ONLY NUMBER.<Br>"
 END IF
ELSE
 UNIT_STOCK_R_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT STOCK RATE.<Br>"
END IF
'*********************
' SAFETY STOCK
'*********************
IF REQUEST.FORM("SAFETY_STOCK") <> "" THEN
 IF IsNumeric(Request.Form("safety_stock")) THEN
  SAFETY_STOCK_FLG = 0
 ELSE
  SAFETY_STOCK_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT SAFETY STOCK ONLY NUMBER.<Br>"
 END IF
END IF
'*********************
' ISSUE POLICY
'*********************
IF CStr(REQUEST.FORM("ISSUE_POLICY")) <> "" THEN
 SQL_ISSUEPOLICY = "SELECT POLICY_NAME " & _
                   "  FROM ISSUEPOLICY " & _
                   " WHERE ISSUE_POLICY = '" & CStr(REQUEST.FORM("ISSUE_POLICY")) & "' "
 SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
 ISSUE_POLICY = REC_ISSUEPOLICY.FIELDS("POLICY_NAME")
ELSE
 ISSUE_POLICY = " "
END IF
'*********************
' UNIT OF ENGINEERING GET
'*********************
IF REQUEST.FORM("unit_engineering") = "" THEN
 UNIT_ENG_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF ENGINEERING.<Br>"
ELSE
 SQL_unit_eng = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("unit_engineering")) & " "
 Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
  UNIT_ENG =  Rec_unit_eng.Fields("UNIT")
END IF
'************************************
' UNIT ENGINEERING RATE
'************************************
IF Request.Form("unit_engineer_rate") <> "" THEN
 IF IsNumeric(Request.Form("unit_engineer_rate")) THEN
  UNIT_ENG_RATE_FLG = 0
 ELSE
  UNIT_ENG_RATE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT ENGINEER RATE ONLY NUMBER.<Br>"
 END IF
ELSE
 UNIT_ENG_RATE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT ENGINEER RATE.<Br>"
END IF

'************************************
' CUSTOMER PACKAGE UNIT
'    入力拠点はFIのみ
'    STOCK_SUBJECT_CODE = 5 (製品)の場合は必須入力
'************************************
If Session("FI_PACKAGE_UNIT") = "1" Then
    '--小数点以下が入力されていないかチェック
    CALL DECIMAL_CHECK(Request("package_unit_number"), 0, RESULT)
    
    '--入力は必須
    If (Request("stock_subject_code") = "5")  And  (Request("package_unit_number") = ""  Or  IsNull(Request("package_unit_number"))) Then
        PACKAGE_UNIT_FLG = 1
        ERROR_MESSAGE = ERROR_MESSAGE & "INPUT PACKAGE UNIT NUMBER.<Br>"

    '--数値以外はエラー
    ElseIf (Request("package_unit_number") <> "")  And  (IsNumeric(Request("package_unit_number")) = False) Then
        PACKAGE_UNIT_FLG = 1
        ERROR_MESSAGE = ERROR_MESSAGE & "INPUT PACKAGE UNIT NUMBER ONLY NUMBER.<BR>"

    '--整数以外はエラー
    ElseIf Result <> "0" Then
        PACKAGE_UNIT_FLG = 1
        ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF PACKING UNIT NUMBER IS 0.<Br>"

    '--単位の入力は必須
    ElseIf (Request("stock_subject_code") = "5")  And  (Request("unit_package") = ""  Or  IsNull(Request("unit_package"))) Then
        PACKAGE_UNIT_FLG = 1
        ERROR_MESSAGE = ERROR_MESSAGE & "INPUT PACKAGE UNIT.<BR>"
    End If

    '--単位を取得
    SQL = " select unit from unit where unit_code = '" & Request("unit_package") & "' "
    Set rec_package_unit = db.Execute(SQL)
    If Not rec_package_unit.EOF  Or Not rec_package_unit.BOF Then
         unit_package_disp = rec_package_unit("unit")
    End If
End If

'-- add 2015/04/21 Start

'--「Finished Goods：製品」判定フラグ
isFinGoods = False
If Request("stock_subject_code") = "5" Then
	isFinGoods = True
End If

'************************************
' CAPACITY <NUMBER(24,8)>■
'************************************
Val = Request.Form("capacity")
Ttl = "Capacity"

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		CAPACITY_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			CAPACITY_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		CAPACITY_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' DATE_CODE_TYPE <NUMBER(6)>
'************************************
Val = Request.Form("date_code_type")
Ttl = "Date Code Type"

'--数値以外はエラー
'If Val <> ""  Then
'	If Not IsNumeric(Val) Then
'		DATE_CODE_TYPE_FLG = 1
'		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
'	End If
'End If

'************************************
' DATE_CODE_MONTH <NUMBER(6)>
'************************************
Val = Request.Form("date_code_month")
Ttl = "Date Code Month"

'--数値以外はエラー
If Val <> ""  Then
	If Not IsNumeric(Val) Then
		DATE_CODE_MONTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' LABEL_TYPE <VARCHAR2(1)>■
'************************************
Val = Request.Form("label_type")
Ttl = "Label Type"

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		LABEL_TYPE_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'************************************
' INNER_BOX_UNIT_NUMBER <NUMBER(24,8)>
'************************************
Val = Request.Form("i_unit_number")
Ttl = "Inner Box (Unit Number)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			INNER_BOX_UNIT_NUMBER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		INNER_BOX_UNIT_NUMBER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' INNER_BOX_HEIGHT <NUMBER(24,8)>
'************************************
Val = Request.Form("i_height")
Ttl = "Inner Box (Height)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			INNER_BOX_HEIGHT_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		INNER_BOX_HEIGHT_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' INNER_BOX_WIDTH <NUMBER(24,8)>
'************************************
Val = Request.Form("i_width")
Ttl = "Inner Box (Width)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			INNER_BOX_WIDTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		INNER_BOX_WIDTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' INNER_BOX_DEPTH <NUMBER(24,8)>
'************************************
Val = Request.Form("i_depth")
Ttl = "Inner Box (Depth)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			INNER_BOX_DEPTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		INNER_BOX_DEPTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MEDIUM_BOX_UNIT_NUMBER <NUMBER(24,8)>
'************************************
Val = Request.Form("m_unit_number")
Ttl = "Medium Box (Unit Number)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MEDIUM_BOX_UNIT_NUMBER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MEDIUM_BOX_UNIT_NUMBER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MEDIUM_BOX_HEIGHT <NUMBER(24,8)>
'************************************
Val = Request.Form("m_height")
Ttl = "Medium Box (Height)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MEDIUM_BOX_HEIGHT_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MEDIUM_BOX_HEIGHT_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MEDIUM_BOX_WIDTH <NUMBER(24,8)>
'************************************
Val = Request.Form("m_width")
Ttl = "Medium Box (Width)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MEDIUM_BOX_WIDTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MEDIUM_BOX_WIDTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MEDIUM_BOX_DEPTH <NUMBER(24,8)>
'************************************
Val = Request.Form("m_depth")
Ttl = "Medium Box(Depth)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MEDIUM_BOX_DEPTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MEDIUM_BOX_DEPTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' OUTER_BOX_HEIGHT <NUMBER(24,8)>
'************************************
Val = Request.Form("o_height")
Ttl = "Outer Box(Height)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			OUTER_BOX_HEIGHT_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		OUTER_BOX_HEIGHT_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' OUTER_BOX_WIDTH <NUMBER(24,8)>
'************************************
Val = Request.Form("o_width")
Ttl = "Outer Box(Width)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			OUTER_BOX_WIDTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		OUTER_BOX_WIDTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' OUTER_BOX_DEPTH <NUMBER(24,8)>
'************************************
Val = Request.Form("o_depth")
Ttl = "Outer Box(Depth)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			OUTER_BOX_DEPTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		OUTER_BOX_DEPTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MEASUREMENT <NUMBER(24,8)>
'************************************
Val = Request.Form("measurement")
Ttl = "Measurement"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MEASUREMENT_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MEASUREMENT_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PI_NO <VARCHAR2(20)>■
'************************************
Val = Request.Form("packing_information")
Ttl = "Packing Information No."

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		PI_NO_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'************************************
' PLT_SPEC_NO <VARCHAR2(20)>■
'************************************
Val = Request.Form("plt_spec_no")
Ttl = "PLT Spec No."

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		PLT_SPEC_NO_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'************************************
' OPERATION_TIME <NUMBER(24,8)>■
'************************************
Val = Request.Form("operation_time")
Ttl = "Operation Time"

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		OPERATION_TIME_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			OPERATION_TIME_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		OPERATION_TIME_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' MAN_POWER <NUMBER(24,8)>
'************************************
Val = Request.Form("man_power")
Ttl = "Man Power"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			MAN_POWER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		MAN_POWER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_UNIT_NUMBER <NUMBER(24,8)>
'************************************
Val = Request.Form("p_unit_number")
Ttl = "Pallet (Unit Number)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_UNIT_NUMBER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_UNIT_NUMBER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_CTN_NUMBER <NUMBER(24,8)>
'************************************
Val = Request.Form("p_ctn_number")
Ttl = "Pallet (Ctn Number)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_CTN_NUMBER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_CTN_NUMBER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_STEP_CTN_NUMBER <NUMBER(24,8)>
'************************************
Val = Request.Form("p_step_ctn_number")
Ttl = "Pallet (Step Ctn Numbetr)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_STEP_CTN_NUMBER_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_STEP_CTN_NUMBER_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_HEIGHT <NUMBER(24,8)>
'************************************
Val = Request.Form("p_height")
Ttl = "Pallet (Height)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_HEIGHT_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_HEIGHT_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_WIDTH <NUMBER(24,8)>
'************************************
Val = Request.Form("p_width")
Ttl = "Pallet (Width)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_WIDTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_WIDTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_DEPTH <NUMBER(24,8)>
'************************************
Val = Request.Form("p_depth")
Ttl = "Pallet (Depth)"

'--数値以外はエラー
If Val <> ""  Then
	If IsNumeric(Val) Then
		'--小数は8桁以内でなければエラー
		Call DECIMAL_CHECK_FULL(Val, 16, 8, RESULT)
		If RESULT <> "0" Then
			PALLET_DEPTH_FLG = 1
			ERROR_MESSAGE = ERROR_MESSAGE & "LENGTH OF " & Ttl & " IS (16, 8). (INTEGER, DECIMAL)<Br>"
		End If
	Else
		PALLET_DEPTH_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' PALLET_SIZE_TYPE <NUMBER(3)>■
'************************************
Val = Request.Form("pallet_size_type")
Ttl = "Pallet Size Type"

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		PALLET_SIZE_TYPE_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'--数値以外はエラー
If Val <> ""  Then
	If Not IsNumeric(Val) Then
		PALLET_SIZE_TYPE_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'************************************
' AGING_DAY <NUMBER(3)>■
'************************************
Val = Request.Form("aging_day")
Ttl = "Aging Day"

'--「Finished Goods：製品」の場合は、入力必須
If isFinGoods = True Then
	If Val = "" Then
		AGING_DAY_FLG = 1
  		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & ".<Br>"
	End If
End If

'--数値以外はエラー
If Val <> ""  Then
	If Not IsNumeric(Val) Then
		AGING_DAY_FLG = 1
		ERROR_MESSAGE = ERROR_MESSAGE & "INPUT " & Ttl & " ONLY NUMBER.<Br>"
	End If
End If

'-- add 2015/04/21 End

'------------------------------------------------
END IF   ' IF REQUEST.FORM("type") <> "DELETE" <END> '(add 2015/10/28)
'------------------------------------------------
%>

<Font Size="5" Color="#ff0000" ><B>
<% IF ERROR_MESSAGE <> "" THEN
    RESPONSE.WRITE ERROR_MESSAGE
   END IF%>
</B></Font>
<Div Align="center">
<Form Method="post" Action="Item_Result.asp?KEYWORD=<%=KEYWORD%>" Name="data_send" Target="_self">
<% IF ERROR_MESSAGE = "" THEN %>
 <Input Type="submit" Name="entry_button" Value="Entry">
<% END IF %>
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
Mode:<% response.write request.form("type") %>
<!-- DATA SET TO INPUT(HIDDEN) START-->
<Input Type="hidden" Name="type" Value="<%= request.form("type") %>">
<Input Type="hidden" Name="ngmc" Value="<%= request.form("ngmc") %>">
<Input Type="hidden" Name="item_no" Value="<%= Request.form("item_no") %>" >
<Input Type="hidden" Name="item_code" Value="<%= replace(Request.form("item_code") & "","""","&quot;") %>" >
<Input Type="hidden" Name="item" Value="<%= replace(UCase(Request.form("item")) & "","""","&quot;") %>" >
<Input Type="hidden" Name="item_flag" Value="<%= UCase(Request.form("item_flag")) %>" >
<Input Type="hidden" Name="origin_code" Value="<%= Request.form("origin_code") %>" >
<Input Type="hidden" Name="description" Value="<%= replace(Request.form("description") & "","""","&quot;") %>" >
<Input Type="hidden" Name="class_code" Value="<%= Request.form("class_code") %>" >
<!-- 
<Input Type="hidden" Name="uom_q" Value="<%'= Request.form("uom_q") %>" >
 -->
<Input Type="hidden" Name="uom_w" Value="<%= Request.form("uom_w") %>" >
<Input Type="hidden" Name="uom_l" Value="<%= Request.form("uom_l") %>" >
<Input Type="hidden" Name="external_unit_number" Value="<%= Request.form("external_unit_number") %>" >
<Input Type="hidden" Name="standard_price" Value="<%= Request.form("standard_price") %>" >
<Input Type="hidden" Name="next_term_price" Value="<%= Request.form("next_term_price") %>" >
<Input Type="hidden" Name="suppliers_price" Value="<%= Request.form("suppliers_price") %>" >
<Input Type="hidden" Name="curr_code" Value="<%= Request.form("curr_code") %>" >
<Input Type="hidden" Name="weight" Value="<%= Request.form("weight") %>" >
<Input Type="hidden" Name="stock_subject_code" Value="<%= Request.form("stock_subject_code") %>" >
<Input Type="hidden" Name="section_code" Value="<%= Request.form("section_code") %>" >
<Input Type="hidden" Name="cost_subject_code" Value="<%= Request.form("cost_subject_code") %>" >
<Input Type="hidden" Name="cost_process_code" Value="<%= Request.form("cost_process_code") %>" >
<Input Type="hidden" Name="manufact_leadtime" Value="<%= Request.form("manufact_leadtime") %>" >
<Input Type="hidden" Name="purchase_leadtime" Value="<%= Request.form("purchase_leadtime") %>" >
<Input Type="hidden" Name="adjustment_leadtime" Value="<%= Request.form("adjustment_leadtime") %>" >
<Input Type="hidden" Name="lab_to_pack_day" Value="<%= Request.form("lab_to_pack_day") %>" ><!-- (add 2015/10/28) -->
<Input Type="hidden" Name="assembling_to_lab_day" Value="<%= Request.form("assembling_to_lab_day") %>" ><!-- (add 2015/10/28) -->
<Input Type="hidden" Name="issue_policy" Value="<%= Request.form("issue_policy") %>" >
<Input Type="hidden" Name="issue_lot" Value="<%= Request.form("issue_lot") %>">
<Input Type="hidden" Name="manufact_fail_rate" Value="<%= Request.form("manufact_fail_rate") %>" >
<Input Type="hidden" Name="maker_flag" Value="<%= Request.form("maker_flag") %>" >
<Input Type="hidden" Name="unit_stock" Value="<%= Request.form("unit_stock") %>" >
<Input Type="hidden" Name="unit_stock_rate" Value="<%= Request.form("unit_stock_rate") %>" >
<Input Type="hidden" Name="drawing_no" Value="<%= Replace(UCase(REQUEST.FORM("drawing_no")) &"","""","&quot;") %>">
<Input Type="hidden" Name="drawing_rev" Value="<%= Replace(UCase(REQUEST.FORM("drawing_rev")) &"","""","&quot;") %>">
<Input Type="hidden" Name="applicable_model" Value="<%= Replace(UCase(REQUEST.FORM("applicable_model")) &"","""","&quot;") %>">
<Input Type="hidden" Name="catalog_no" Value="<%= Replace(UCase(REQUEST.FORM("catalog_no")) &"","""","&quot;") %>">
<Input Type="hidden" Name="safety_stock" Value="<%= REQUEST.FORM("SAFETY_STOCK") %>">
<Input Type="hidden" Name="cust_item_no" Value="<%= Request.form("cust_item_no") %>" ><!-- (add 2015/10/28) -->
<Input Type="hidden" Name="cust_item_name" Value="<%= Request.form("cust_item_name") %>" ><!-- (add 2015/10/28) -->
<Input Type="hidden" Name="order_policy" Value="<%= REQUEST.FORM("ORDER_POLICY") %>">
<Input Type="hidden" Name="mak" Value="<%= UCase(REQUEST.FORM("MAK")) %>">
<Input Type="hidden" Name="unit_engineering" Value="<%= REQUEST.FORM("UNIT_ENGINEERING") %>">
<Input Type="hidden" Name="unit_engineer_rate" Value="<%= REQUEST.FORM("UNIT_ENGINEER_RATE") %>">
<Input Type="hidden" Name="item_type1" Value="<%= UCase(REQUEST.FORM("item_type1"))%>">
<Input Type="hidden" Name="item_type2" Value="<%= UCase(REQUEST.FORM("item_type2"))%>">
<!-- FOR ORDER/FORCAST START -->
<Input Type="hidden" Name="GRP_FLG" Value="<%= request("GRP_FLG") %>">
<Input Type="hidden" Name="customer_code" Value="<%=REQUEST("CUSTOMER_CODE")%>">
<Input Type="hidden" Name="customer_po_no" Value="<%=REQUEST("CUSTOMER_PO_NO")%>">
<Input Type="hidden" Name="package_unit_number" Value="<% =Request("package_unit_number") %>">
<Input Type="hidden" Name="unit_package"        Value="<% =Request("unit_package")        %>">

<Input Type="hidden" Name="unit_price_o"        Value="<% =Request("unit_price_o")        %>">
<Input Type="hidden" Name="unit_price_rate"     Value="<% =Request("unit_price_rate")        %>">
<Input Type="hidden" Name="unit_curr_mark"      Value="<% =Request("unit_curr_mark")        %>">
<Input Type="hidden" Name="unit_curr_code"      Value="<% =Request("unit_curr_code")        %>">

<Input Type="hidden" Name="grade_code"          Value="<% =Request("grade_code")        %>">
<Input Type="hidden" Name="customer_type"       Value="<% =Request("customer_type")        %>">
<Input Type="hidden" Name="package_type"        Value="<% =Request("package_type")        %>">
<!-- FOR ORDER/FORCAST END -->

<!-- add 2015/04/21 START -->
<Input Type="hidden" Name="capacity"             Value="<% =Request("capacity")        %>">
<Input Type="hidden" Name="date_code_type"       Value="<% =Request("date_code_type")        %>">
<Input Type="hidden" Name="date_code_month"      Value="<% =Request("date_code_month")        %>">
<Input Type="hidden" Name="label_type"           Value="<% =Request("label_type")        %>">
<Input Type="hidden" Name="measurement"          Value="<% =Request("measurement")        %>">
<Input Type="hidden" Name="i_height"             Value="<% =Request("i_height")        %>">
<Input Type="hidden" Name="i_width"              Value="<% =Request("i_width")        %>">
<Input Type="hidden" Name="i_depth"              Value="<% =Request("i_depth")        %>">
<Input Type="hidden" Name="i_unit_number"        Value="<% =Request("i_unit_number")        %>">
<Input Type="hidden" Name="m_height"             Value="<% =Request("m_height")        %>">
<Input Type="hidden" Name="m_width"              Value="<% =Request("m_width")        %>">
<Input Type="hidden" Name="m_depth"              Value="<% =Request("m_depth")        %>">
<Input Type="hidden" Name="m_unit_number"        Value="<% =Request("m_unit_number")        %>">
<Input Type="hidden" Name="o_height"             Value="<% =Request("o_height")        %>">
<Input Type="hidden" Name="o_width"              Value="<% =Request("o_width")        %>">
<Input Type="hidden" Name="o_depth"              Value="<% =Request("o_depth")        %>">
<Input Type="hidden" Name="p_height"             Value="<% =Request("p_height")        %>">
<Input Type="hidden" Name="p_width"              Value="<% =Request("p_width")        %>">
<Input Type="hidden" Name="p_depth"              Value="<% =Request("p_depth")        %>">
<Input Type="hidden" Name="p_unit_number"        Value="<% =Request("p_unit_number")        %>">
<Input Type="hidden" Name="p_ctn_number"         Value="<% =Request("p_ctn_number")        %>">
<Input Type="hidden" Name="p_step_ctn_number"    Value="<% =Request("p_step_ctn_number")        %>">
<Input Type="hidden" Name="packing_information"  Value="<% =Request("packing_information")        %>">
<Input Type="hidden" Name="plt_spec_no"          Value="<% =Request("plt_spec_no")        %>">
<Input Type="hidden" Name="operation_time"       Value="<% =Request("operation_time")        %>">
<Input Type="hidden" Name="man_power"            Value="<% =Request("man_power")        %>">
<Input Type="hidden" Name="pallet_size_type"     Value="<% =Request("pallet_size_type")        %>">
<Input Type="hidden" Name="aging_day"            Value="<% =Request("aging_day")        %>">
<!-- add 2015/04/21 END -->

</Form>
<!-- DATA SET TO INPUT(HIDDEN) END -->
<Table Border Align="center" Width="100%" Height="100%" CellSpacing="1" BorderColor="black">
<Tr>
<Td BgColor="#00FF00">Item No</Td>
<% IF ITEM_NO_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><%= Request.form("item_no") %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= Request.form("item_no") %></Td>
<% END IF %>
<Td BgColor="#00ff00">Item Code</Td>
<Td ColSpan="2"><%= Request.form("item_code")%>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Name</Td>
<% IF ITEM_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><%= UCase(Request.form("item")) %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= UCase(Request.form("item")) %></Td>
<% END IF %>
<Td BgColor="#00ff00">Item Flag</Td>
<% IF ITEM_FLAG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><% RESPONSE.WRITE ITEM_FLAG %>&nbsp;</Td>
<% ELSE %>
<Td ColSpan="2"><% RESPONSE.WRITE ITEM_FLAG %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Description</Td><Td ColSpan="2"><%= Request.form("description") %>&nbsp;</Td>
<Td BgColor="#00ff00">Class Code</Td>
<% IF CLASS_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% RESPONSE.WRITE CLASS1 %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Origin Code</Td>
<% IF ORIGIN_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write ORIGIN %>&nbsp;</Td>
<Td BgColor="#00ff00">Curr Code</Td>
<% IF CURR_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write CURR_MARK %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">External Unit Number</Td>
<% IF EX_UNIT_NO_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000"><%= Request.form("external_unit_number") %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= Request.form("external_unit_number") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Safety Stock</Td>
<% IF SAFETY_STOCK_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="red"><%= REQUEST.FORM("SAFETY_STOCK") %>
<% ELSE%>
 <Td ColSpan="2"><%= REQUEST.FORM("SAFETY_STOCK") %>
<% END IF %>
&nbsp;</Td>
</Tr>
<Tr>
<!-- 
<Td BgColor="#00ff00">Unit of Quantity</Td>
<%' IF UOM_Q_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<%' ELSE %>
 <Td>
<%' END IF %>
<%' Response.write UNIT_Q %>&nbsp;</Td>
 -->
<Td BgColor="#00ff00">Unit Stock</Td>
<% IF UNIT_STOCK_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write UNIT_STOCK %>&nbsp;</Td>
<Td BgColor="#00ff00">Unit of Engineering</Td>
<% IF UNIT_ENG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write UNIT_ENG %>&nbsp;</Td>
</Tr>
<Tr>
<Td ColSpan="3" BgColor="#00FF00">
<Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
</Td>
<% IF UNIT_STOCK_R_FLG = 1 OR UNIT_ENG_RATE_FLG = 1 THEN %>
 <Td ColSpan="3" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="3">
<% END IF %>
<%= Request.form("unit_stock_rate") %>&nbsp;
:
<%= Request.form("unit_engineer_rate") %>&nbsp;
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Weight</Td>
<% IF WEIGHT_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("weight") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("weight") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Unit of Weight</Td>
<% IF UOM_W_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<% ELSE %>
 <Td>
<% END IF %>
<% Response.write UNIT_W %>&nbsp;</Td>
<Td BgColor="#00ff00">Unit of Measurement</Td>
<% IF UOM_L_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<% ELSE %>
 <Td>
<% END IF %>
<% Response.write UNIT_L %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">DRAWING NO</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("drawing_no")) %>&nbsp;</Td>
<Td BgColor="#00ff00">DRAWING REV</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("drawing_rev")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">APPLICABLE MODEL</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("applicable_model")) %>&nbsp;</Td>
<Td BgColor="#00ff00">CATALOG NO</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("catalog_no")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Standard Price</Td>
<% IF STD_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("standard_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("standard_price") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Next Term Price</Td>
<% IF NEXT_TERM_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("next_term_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("next_term_price") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Suppliers Price</Td>
<% IF SUPPLIERS_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("suppliers_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("suppliers_price") %>&nbsp;</Td>
<% END IF %>
</Tr>

<Tr>
<Td BgColor="#00ff00">Manufact Lead Time</Td>
<% IF MANU_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("manufact_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("manufact_leadtime") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Purchase Lead Time</Td>
<% IF PUR_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("purchase_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("purchase_leadtime") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Adjustment Lead Time</Td>
<% IF ADJ_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("adjustment_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("adjustment_leadtime") %>&nbsp;</Td>
<% END IF %>
</Tr>

<!-- add 2015/10/28 start -->
<Tr>
<Td BgColor="#00ff00">Labeling To Packaging Day</Td>
<% IF LAB_TO_PACK_DAY_FLG = 1 THEN %>
  <Td BgColor="#ff0000"><%= Request.form("lab_to_pack_day") %>&nbsp;</Td>
<% ELSE %>
  <Td><%= Request.form("lab_to_pack_day") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Assembling To Labeling Day</Td>
<% IF ASS_TO_LAB_DAY_FLG = 1 THEN %>
  <Td BgColor="#ff0000" colspan="3"><%= Request.form("assembling_to_lab_day") %>&nbsp;</Td>
<% ELSE %>
  <Td colspan="3"><%= Request.form("assembling_to_lab_day") %>&nbsp;</Td>
<% END IF %>
</Tr>
<!-- add 2015/10/28 end -->

<Tr>
<Td BgColor="#00ff00">Issue Policy</Td>
<Td>
<% RESPONSE.WRITE ISSUE_POLICY %>&nbsp;</Td>
<Td BgColor="#00ff00">Issue Lot</Td>
<% IF ISSUE_LOT_FLG = 1 THEN %>
 <Td BgColor="red"><%= REQUEST.FORM("issue_lot") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= REQUEST.FORM("issue_lot") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Manufact Fail Rate</Td>
<% IF MANU_FAIL_RATE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("manufact_fail_rate") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("manufact_fail_rate") %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Section Code</Td>
<% IF SECTION_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write SECTION %>&nbsp;</Td>
<Td BgColor="#00ff00">Stock Subject Code</Td>
<% IF STOCK_SUBJECT_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write STOCK_SUBJECT %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Cost Process Code</Td>
<% IF COST_PROCESS_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write COST_PROCESS %>&nbsp;</Td>
<Td BgColor="#00ff00">Cost Subject Code</Td>
<% IF COST_SUBJECT_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write COST_SUBJECT %>&nbsp;</Td>
</Tr>

<!-- add 2015/10/28 start -->
<Tr>
 <Td BgColor="#00ff00">Customer Item Code</Td>
 <Td ColSpan="2"><%= REQUEST.FORM("cust_item_no") %>&nbsp;</Td>
 <Td BgColor="#00ff00">Customer Item Name</Td>
 <Td ColSpan="2"><%= REQUEST.FORM("cust_item_name") %>&nbsp;</Td>
</Tr>
<!-- add 2015/10/28 end -->

<Tr>
<Td BgColor="#00ff00">Order Policy</Td>
<Td ColSpan="5">
<%
IF REQUEST.FORM("ORDER_POLICY") <> "" THEN
 '********************
 ' order policy get
 '********************
 SQL_ORDER_POLICY = "SELECT POLICY_NAME " & _
                    "  FROM ORDER_POLICY " & _
                    " WHERE ORDER_POLICY = '" & CStr(REQUEST.FORM("ORDER_POLICY")) & "' "
 SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY) %>
 <%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
<%END IF%>
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Maker Flag</Td>
<% IF MAKER_FLAG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<%
RESPONSE.WRITE MAKERFLAG
%>
&nbsp;</Td>
<Td BgColor="#00ff00">Maker</Td>
<Td ColSpan="2"><%= UCase(Request.form("mak")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Type1</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("item_type1"))%>&nbsp;</Td>
<Td BgColor="#00ff00">Item Type2</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("item_type2"))%>&nbsp;</Td>
</Tr>
<!-- 
<Tr>
<Td BgColor="#00ff00">Supplier Code</Td><Td ColSpan="5"><%= Request.form("supplier_code")%>&nbsp;</Td>
</Tr>
 -->
<!-- <Tr>
<Td BgColor="#00ff00">Reorder Point</Td><Td ColSpan="2"><%= Request.form("reorder_point")%>&nbsp;</Td>
<Td BgColor="#00ff00">Llc Code</Td><Td ColSpan="2"><%= Request.form("llc_code")%>&nbsp;</Td>
</Tr> -->

<%
  '--BgColorを指定
  If PACKAGE_UNIT_FLG = 1 Then
      Bg_PACKAGE_UNIT = "#FF0000"
  End If
%>
<TR>
   <TD BgColor="#00ff00">PACKAGE UNIT NUMBER</TD>
   <TD ColSpan="2" BgColor="<% =Bg_PACKAGE_UNIT %>">
      <% =Request("package_unit_number") %>&nbsp;
      <% =unit_package_disp              %>&nbsp;
   </TD>
   <TD BgColor="#00ff00"><BR></TD>
   <TD ColSpan="2"><BR></TD>
</TR>
<TR>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Unit Price(ORG)</FONT></TD>
   <TD><%= Request.form("unit_price_o") %>&nbsp;</TD>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Unit Price Rate</FONT></TD>
   <TD><%= Request.form("unit_price_rate") %>&nbsp;</TD>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Unit Curr Code</FONT></TD>
   <TD><%= Request.form("unit_curr_mark") %>&nbsp;</TD>
</TR>
<TR>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Grade Code</FONT></TD>
   <TD><%= Request.form("GRADE_CODE") %>&nbsp;</TD>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Customer Type</FONT></TD>
   <TD><%= Request.form("CUSTOMER_TYPE") %>&nbsp;</TD>
   <TD BgColor="#00ff00"><FONT color='#0000ff'>Package Type</FONT></TD>
   <!-- mod 2015/04/21 Start -->
   <!--<TD><%= Request.form("PACKAGE_TYPE") %>&nbsp;</TD>-->
   <TD>
     <%
     If Request("PACKAGE_TYPE") <> "" Then
       '********************
       ' Label Type GET
       '********************
       SQL_PACKAGE_TYPE = "select PACKING_TYPE_COMMENT " & _
                          "from   PACKING_TYPE " & _
                          "where  PACKING_TYPE_JPN = '" & Request("PACKAGE_TYPE") & "' "
       Set REC_PACKAGE_TYPE = DB.Execute(SQL_PACKAGE_TYPE) %>
     <%= REC_PACKAGE_TYPE.Fields("PACKING_TYPE_COMMENT") %>&nbsp;
     <%
     End If
     %>
   </TD>
   <!-- mod 2015/04/21 End -->
</TR>

<!-- add 2015/04/21 Start -->
<TR>
   <TD bgcolor="#00ff00">Capacity</TD>
   <TD bgcolor="<%= SET_BGCOLOR( CAPACITY_FLG ) %>">
      <%= Request("capacity") %>&nbsp;
   </TD>
   <TD bgcolor="#00ff00">Date Code Type</TD>
   <TD bgcolor="<%= SET_BGCOLOR( DATE_CODE_TYPE_FLG ) %>">
      <%= Request("date_code_type") %>&nbsp;
   </TD>
   <TD bgcolor="#00ff00">Date Code Month</TD>
   <TD bgcolor="<%= SET_BGCOLOR( DATE_CODE_MONTH_FLG ) %>">
      <%= Request("date_code_month") %>&nbsp;
   </TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">Label Type</TD>
   <TD bgcolor="<%= SET_BGCOLOR( LABEL_TYPE_FLG ) %>">
     <%
     If Request("label_type") <> "" Then
       '********************
       ' Label Type GET
       '********************
       SQL_LABEL_TYPE = "select LABEL_TYPE_NAME " & _
                        "from   LABEL_TYPE " & _
                        "where  LABEL_TYPE_CODE = '" & Request("label_type") & "' "
       Set REC_LABEL_TYPE = DB.Execute(SQL_LABEL_TYPE) %>
     <%= REC_LABEL_TYPE.Fields("LABEL_TYPE_NAME") %>&nbsp;
     <%
     End If
     %>
   </TD>
   <TD bgcolor="#00ff00">Measurement</TD>
   <TD bgcolor="<%= SET_BGCOLOR( MEASUREMENT_FLG ) %>" colspan="3">
      <%= Request("measurement") %>&nbsp;
   </TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">&nbsp;</TD>
   <TD bgcolor="#00ff00" align="center">Height</TD>
   <TD bgcolor="#00ff00" align="center">Width</TD>
   <TD bgcolor="#00ff00" align="center">Depth</TD>
   <TD bgcolor="#00ff00" align="center" colspan="2">Unit Number</TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">Inner Box</TD>
   <TD bgcolor="<%= SET_BGCOLOR( INNER_BOX_HEIGHT_FLG ) %>" align="right">
      <%= Request("i_height") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( INNER_BOX_WIDTH_FLG ) %>" align="right">
      <%= Request("i_width") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( INNER_BOX_DEPTH_FLG ) %>" align="right">
      <%= Request("i_depth") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( INNER_BOX_UNIT_NUMBER_FLG ) %>" align="right" colspan="2">
      <%= Request("i_unit_number") %>&nbsp;PC
   </TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">Medium Box</TD>
   <TD bgcolor="<%= SET_BGCOLOR( MEDIUM_BOX_HEIGHT_FLG ) %>" align="right">
      <%= Request("m_height") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( MEDIUM_BOX_WIDTH_FLG ) %>" align="right">
      <%= Request("m_width") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( MEDIUM_BOX_DEPTH_FLG ) %>" align="right">
      <%= Request("m_depth") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( MEDIUM_BOX_UNIT_NUMBER_FLG ) %>" align="right" colspan="2">
      <%= Request("m_unit_number") %>&nbsp;PC
   </TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">Outer Box</TD>
   <TD bgcolor="<%= SET_BGCOLOR( OUTER_BOX_HEIGHT_FLG ) %>" align="right">
      <%= Request("o_height") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( OUTER_BOX_WIDTH_FLG ) %>" align="right">
      <%= Request("o_width") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( OUTER_BOX_DEPTH_FLG ) %>" align="right">
      <%= Request("o_depth") %>&nbsp;mm
   </TD>
   <TD align="right" colspan="2">
      <%= Request("o_unit_number") %>&nbsp;PC
   </TD>
</TR>
<TR>
   <TD bgcolor="<%= COLOR_PI %>">Packing Information No.</TD>
   <TD bgcolor="<%= SET_BGCOLOR( PI_NO_FLG ) %>">
      <%= Request("packing_information") %>&nbsp;
   </TD>
   <TD bgcolor="<%= COLOR_PI %>">PLT Spec No.</TD>
   <TD bgcolor="<%= SET_BGCOLOR( PLT_SPEC_NO_FLG ) %>">
      <%= Request("plt_spec_no") %>&nbsp;
   </TD>
   <TD bgcolor="<%= COLOR_PI %>">Pallet Size Type</TD>
   <TD bgcolor="<%= SET_BGCOLOR( PLT_SPEC_NO_FLG ) %>">
     <%
     If Request("pallet_size_type") <> "" Then
       '********************
       ' Pallet Size Type GET
       '********************
       SQL_LABEL_TYPE = "select PALLET_SIZE_TYPE_NAME " & _
                        "from   PALLET_SIZE_TYPE " & _
                        "where  PALLET_SIZE_TYPE_CODE = '" & Request("pallet_size_type") & "' "
       Set REC_PALLET_SIZE_TYPE = DB.Execute(SQL_LABEL_TYPE) %>
     <%= REC_PALLET_SIZE_TYPE.Fields("PALLET_SIZE_TYPE_NAME") %>&nbsp;
     <%
     End If
     %>
   </TD>
</TR>
<TR>
   <TD bgcolor="<%= COLOR_PI %>" rowspan="4">Pallet</TD>
   <TD bgcolor="<%= COLOR_PI %>" align="center">Height</TD>
   <TD bgcolor="<%= COLOR_PI %>" align="center">Width</TD>
   <TD bgcolor="<%= COLOR_PI %>" align="center">Depth</TD>
   <TD bgcolor="<%= COLOR_PI %>" align="center" colspan="2">Unit Number</TD>
</TR>
<TR>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_HEIGHT_FLG ) %>" align="right" rowspan="3">
      <%= Request("p_height") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_WIDTH_FLG ) %>" align="right" rowspan="3">
      <%= Request("p_width") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_DEPTH_FLG ) %>" align="right" rowspan="3">
      <%= Request("p_depth") %>&nbsp;mm
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_UNIT_NUMBER_FLG ) %>" align="right" colspan="2">
      <%= Request("p_unit_number") %>&nbsp;PC
   </TD>
</TR>
<TR>
   <TD bgcolor="<%= COLOR_PI %>">Ctn Number</TD>
   <TD bgcolor="<%= COLOR_PI %>">Step Ctn Numbetr</TD>
</TR>
<TR>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_CTN_NUMBER_FLG ) %>" align="right">
      <%= Request("p_ctn_number") %>&nbsp;
   </TD>
   <TD bgcolor="<%= SET_BGCOLOR( PALLET_STEP_CTN_NUMBER_FLG ) %>" align="right">
      <%= Request("p_step_ctn_number") %>&nbsp;
   </TD>
</TR>
<TR>
   <TD bgcolor="#00ff00">Operation Time</TD>
   <TD bgcolor="<%= SET_BGCOLOR( OPERATION_TIME_FLG ) %>">
      <%= Request("operation_time") %>&nbsp;
   </TD>
   <TD bgcolor="#00ff00">Man Power</TD>
   <TD bgcolor="<%= SET_BGCOLOR( MAN_POWER_FLG ) %>">
      <%= Request("man_power") %>&nbsp;
   </TD>
   <TD bgcolor="#00ff00">Aging Day</TD>
   <TD bgcolor="<%= SET_BGCOLOR( AGING_DAY_FLG ) %>">
      <%= Request("aging_day") %>&nbsp;
   </TD>
</TR>
<!-- add 2015/04/21 End -->
</Table>


</Div>
</Body>
</Html>
<% DB.Close %>


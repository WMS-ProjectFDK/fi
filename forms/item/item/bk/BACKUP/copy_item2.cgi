# ＳＩＭＳ【マスタ】
# ［IMEJ0003.cgi］品目登録（国内）
# ［作成日］2002/11/05
# ［作成者］松本一義
#
# ［変更履歴］9999/99/99 ＮＮＮＮ ＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮ
#======================================================================================

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
$stock_subject_code = $in{'stock_subject_code'} ;
$item_no            = $in{'item_no'} ;
$TYPES              = $in{'TYPES'} ;

if($TYPES eq ""){$TYPES="新規登録" ;}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "変更"){
	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_MST err $DBI::err .... $DBI::errstr") ;
	@datas = $cur->fetchrow() ;
	for($j=0;$j<@datas;$j++){
		$cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
		${$cur->{NAME}->[$j]} = $datas[$j] ;
	}
}

	#COMPANY
	$sql = "select country_code,curr_code from company where company_type = 0";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COMPANY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COMPANY err $DBI::err .... $DBI::errstr") ;
	($my_country_code,$my_curr_code) = $cur->fetchrow() ;
	$cur->finish;

	#ITEMFLAG
	$sql = "select item_flag,flag_name from itemflag order by flag_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$IMF_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$IMF[$IMF_NUM][0] = $datas[0] ;
		$IMF[$IMF_NUM][1] = $datas[1] ;
		$IMF_NUM ++ ;
	}
	$cur->finish;

	#COUNTRY
	$sql = "select country_code,country from country order by area_code,country";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$COU_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$COU[$COU_NUM][0] = $datas[0] ;
		$COU[$COU_NUM][1] = $datas[1] ;
		$COU_NUM ++ ;
	}
	$cur->finish;

	#UNIT
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'QUANTITY' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNQ_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNQ[$UNQ_NUM][0] = $datas[0] ;
		$UNQ[$UNQ_NUM][1] = $datas[1] ;
		$UNQ_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'WEIGHT' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNW_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNW[$UNW_NUM][0] = $datas[0] ;
		$UNW[$UNW_NUM][1] = $datas[1] ;
		$UNW_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'LENGTH' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNL_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNL[$UNL_NUM][0] = $datas[0] ;
		$UNL[$UNL_NUM][1] = $datas[1] ;
		$UNL_NUM ++ ;
	}
	$cur->finish;

	#ISSUEPOLICY
	$sql = "select issue_policy,policy_name from issuepolicy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$ISS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ISS[$ISS_NUM][0] = $datas[0] ;
		$ISS[$ISS_NUM][1] = $datas[1] ;
		$ISS_NUM ++ ;
	}
	$cur->finish;

	#SECTION
	$sql = "select section_code,section from section order by short_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_SECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_SECTION err $DBI::err .... $DBI::errstr") ;
	$SEC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SEC[$SEC_NUM][0] = $datas[0] ;
		$SEC[$SEC_NUM][1] = $datas[1] ;
		$SEC_NUM ++ ;
	}
	$cur->finish;

	#STOCK_SUBJECT
	$sql = "select stock_subject_code,stock_subject from stock_subject order by stock_subject_code";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$SSC_NUM][0] = $datas[0] ;
		$SSC[$SSC_NUM][1] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish;

	#COSTPROCESS
	$sql = "select cost_process_code,cost_process_name from costprocess order by cost_process_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$CPC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CPC[$CPC_NUM][0] = $datas[0] ;
		$CPC[$CPC_NUM][1] = $datas[1] ;
		$CPC_NUM ++ ;
	}
	$cur->finish;

	#COSTSUBJECT
	$sql = "select cost_subject_code,cost_subject_name from costsubject order by cost_subject_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$CSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CSC[$CSC_NUM][0] = $datas[0] ;
		$CSC[$CSC_NUM][1] = $datas[1] ;
		$CSC_NUM ++ ;
	}
	$cur->finish;

	#ORDER_POLICY
	$sql = "select order_policy,policy_name from order_policy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$ORP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ORP[$ORP_NUM][0] = $datas[0] ;
		$ORP[$ORP_NUM][1] = $datas[1] ;
		$ORP_NUM ++ ;
	}
	$cur->finish;

	#MAKERFLAG
	$sql = "select maker_flag_code,maker_flag from makerflag order by maker_flag";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$MAK_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$MAK[$MAK_NUM][0] = $datas[0] ;
		$MAK[$MAK_NUM][1] = $datas[1] ;
		$MAK_NUM ++ ;
	}
	$cur->finish;

	#CURRENCY
	$sql = "select curr_code,curr_mark from currency order by curr_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$CURR_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CURR[$CURR_NUM][0] = $datas[0] ;
		$CURR[$CURR_NUM][1] = $datas[1] ;
		$CURR_NUM ++ ;
	}
	$cur->finish;

	#INSPECTIONTYPE
	$sql = "select inspection_type,inspection_name from inspectiontype order by inspection_type";
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
	$INSP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$INSP[$INSP_NUM][0] = $datas[0] ;
		$INSP[$INSP_NUM][1] = $datas[1] ;
		$INSP_NUM ++ ;
	}
	$cur->finish;

#-------------------------
# HTML
#-------------------------
&html_header("品目マスタメンテナンス");
&title('品目マスタメンテナンス','IMEJ0003');
print <<ENDOFTEXT ;
<SCRIPT LANGUAGE=javascript SRC=../../lib/jslib.js></SCRIPT>
<SCRIPT LANGUAGE=javascript>
	function checkInput(theForm){
		if (!isNumber(theForm.selling_unit_qty.value    )){ theForm.selling_unit_qty.value = 0     ;}
		if (!isNumber(theForm.external_unit_number.value)){ theForm.external_unit_number.value = 0 ;}
		if (!isNumber(theForm.safety_stock.value        )){ theForm.safety_stock.value = 0         ;}
		if (!isNumber(theForm.weight.value              )){ theForm.weight.value = 0               ;}
		if (!isNumber(theForm.suppliers_price.value     )){ theForm.suppliers_price.value = 0      ;}
		if (!isNumber(theForm.manufact_leadtime.value   )){ theForm.manufact_leadtime.value = 0    ;}
		if (!isNumber(theForm.purchase_leadtime.value   )){ theForm.purchase_leadtime.value = 0    ;}
		if (!isNumber(theForm.adjustment_leadtime.value )){ theForm.adjustment_leadtime.value = 0  ;}
		if (!isNumber(theForm.manufact_fail_rate.value  )){ theForm.manufact_fail_rate.value = 0   ;}

		if (theForm.weight.value           > 999999.99999     ){ alert("重量は「999999.99999」以下入力です。");			return false; }
		if (theForm.standard_price.value   > 999999999.9999   ){ alert("当期基準単価は「999999999.9999」以下入力です。");	return false; }
		if (theForm.next_term_price.value  > 999999999.9999   ){ alert("翌期基準単価は「999999999.9999」以下入力です。");	return false; }
		if (theForm.suppliers_price.value  > 999999999.9999   ){ alert("支給単価は「999999999.9999」以下入力です。");		return false; }
		if (theForm.selling_unit_qty.value > 999999999.999999 ){ alert("販売単位数量は「999999999.999999」以下入力です。");	return false; }

		if (!isNumber(theForm.item_no.value             )){ alert("「品目コード」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.selling_unit_qty.value    )){ alert("「販売単位数量」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.external_unit_number.value)){ alert("「外装箱梱包数」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.safety_stock.value        )){ alert("「安全在庫数」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.weight.value              )){ alert("「重量」に数字以外が入力されています。");			return false; }
		if (!isNumber(theForm.unit_engineer_rate.value  )){ alert("「単位変換率（技術）」に数字以外が入力されています。");	return false; }
		if (!isNumber(theForm.unit_stock_rate.value     )){ alert("「単位変換率（在庫）」に数字以外が入力されています。");	return false; }
		if (!isNumber(theForm.standard_price.value      )){ alert("「当期基準単価」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.next_term_price.value     )){ alert("「翌期基準単価」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.manufact_leadtime.value   )){ alert("「製造手番」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.purchase_leadtime.value   )){ alert("「調達手番」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.adjustment_leadtime.value )){ alert("「調整手番」に数字以外が入力されています。");		return false; }
		if (!isNumber(theForm.manufact_fail_rate.value  )){ alert("「加工仕損率」に数字以外が入力されています。");		return false; }
	}
	function isNumber(str){
		if (str.length == 0){ return false; }
			for (var i=0; i< str.length; i++){
				if ( "0123456789.".indexOf(str.charAt(i))== -1){ return false; }
			}
			return true;
	}

</SCRIPT>
ENDOFTEXT
if($TYPES eq "新規登録"){
	print "　【新規登録】<BR><BR>" ;
 	print "　---<FONT COLOR=RED><B>赤字項目</B></FONT>は必須項目です----" ;
}else{
	print "　【$TYPES】<BR>" ;
}
print "<BR>" ;
print "<FORM METHOD=Post NAME=myform ACTION=IMEJ0004.cgi?$KEYWORD onSubmit=\"return checkInput(this)\">" ;
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen><FONT COLOR=RED><B>品目コード</B></FONT></TD>\n" ;
if($TYPES eq "新規登録"){
	print "   <TD><INPUT TYPE=Text NAME=item_no VALUE=\"$item_no\" SIZE=8 MAXLENGTH=8 onChange= 'chkNum(this.form,this.value,\"8,0\")'>(半角数字８文字以内)</TD>\n" ;
}else{
	print "   <TD> $item_no<INPUT TYPE=Hidden NAME=item_no VALUE=\"$item_no\"></TD>\n" ;
}
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>資産区分</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=stock_subject_code>" ;
		if($SSC_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$SSC_NUM;$i++){
			if($SSC[$i][0] eq $stock_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SSC[$i][0]\" $SEL>$SSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>品目名</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item VALUE=\"$item\" SIZE=41 MAXLENGTH=40></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>分類区分</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=item_flag>" ;
		if($IMF_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$IMF_NUM;$i++){
			if($IMF[$i][0] eq $item_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$IMF[$i][0]\" $SEL>$IMF[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>規格</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=description VALUE=\"$description\" SIZE=51 MAXLENGTH=50></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>原産国</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=origin_code>" ;
		if($COU_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		if($origin_code eq ""){ $origin_code = $my_country_code ; }
		for($i=0;$i<$COU_NUM;$i++){
			if($COU[$i][0] eq $origin_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$COU[$i][0]\" $SEL>$COU[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>メーカー</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=mak VALUE=\"$mak\" SIZE=31 MAXLENGTH=30></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>通貨</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=curr_code>" ;
		if($CURR_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		if($curr_code eq ""){ $curr_code = $my_curr_code ; }
		for($i=0;$i<$CURR_NUM;$i++){
			if($CURR[$i][0] eq $curr_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CURR[$i][0]\" $SEL>$CURR[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>販売単位数量</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=selling_unit_qty VALUE=\"$selling_unit_qty\" SIZE=17 MAXLENGTH=16 onChange='chkNum(this.form,this.value,\"15,6\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>外装箱梱包数</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=external_unit_number VALUE=\"$external_unit_number\" SIZE=6 MAXLENGTH=5 onChange='chkNum(this.form,this.value,\"5,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>安全在庫数</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=safety_stock VALUE=\"$safety_stock\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>重量</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=weight VALUE=\"$weight\" SIZE=13 MAXLENGTH=12 onChange='chkNum(this.form,this.value,\"11,5\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>重量単位</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_w>" ;
		if($UNW_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$UNW_NUM;$i++){
			if($UNW[$i][0] eq $uom_w){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNW[$i][0]\" $SEL>$UNW[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>容積単位</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_l>" ;
		if($UNL_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$UNL_NUM;$i++){
			if($UNL[$i][0] eq $uom_l){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNL[$i][0]\" $SEL>$UNL[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3><FONT COLOR=RED><B>単位変換率（対在庫単位）:単位変換率（対技術単位）</B></FONT></TD>\n" ;
		if($unit_stock_rate    eq ""){ $unit_stock_rate = 1 ; }
		if($unit_engineer_rate eq ""){ $unit_engineer_rate = 1 ; }

print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=unit_stock_rate VALUE=\"$unit_stock_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_stock>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_stock){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print " ： <INPUT TYPE=Text NAME=unit_engineer_rate VALUE=\"$unit_engineer_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_engineering>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_engineering){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>カタログ番号</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=catalog_no VALUE=\"$catalog_no\" SIZE=29 MAXLENGTH=28></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>ＪＡＮコード（物品番号）</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item_code VALUE=\"$item_code\" SIZE=25 MAXLENGTH=24></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>当期基準単価</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=standard_price VALUE=\"$standard_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>翌期基準単価</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=next_term_price VALUE=\"$next_term_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>支給単価</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=suppliers_price VALUE=\"$suppliers_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD>　<FONT SIZE = -1>※翌期基準単価が決まっていない場合は当期基準単価と同額を登録して下さい。</FONT><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>製造手番</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_leadtime VALUE=\"$manufact_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>日</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>調達手番</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=purchase_leadtime VALUE=\"$purchase_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>日</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>調整手番</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=adjustment_leadtime VALUE=\"$adjustment_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>日</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>払出区分</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=issue_policy>" ;
		if($ISS_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$ISS_NUM;$i++){
			if($ISS[$i][0] eq $issue_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ISS[$i][0]\" $SEL>$ISS[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>払出ロット</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=issue_lot VALUE=\"$issue_lot\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>加工仕損率</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_fail_rate VALUE=\"$manufact_fail_rate\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>％</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>部門コード</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=section_code>" ;
		if($SEC_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$SEC_NUM;$i++){
			if($SEC[$i][0] eq $section_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SEC[$i][0]\" $SEL>$SEC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>原価コード</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_process_code>" ;
		if($CPC_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$CPC_NUM;$i++){
			if($CPC[$i][0] eq $cost_process_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CPC[$i][0]\" $SEL>$CPC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>科目コード</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_subject_code>" ;
		if($CSC_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$CSC_NUM;$i++){
			if($CSC[$i][0] eq $cost_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CSC[$i][0]\" $SEL>$CSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>発注方式</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=order_policy>" ;
		if($ORP_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$ORP_NUM;$i++){
			if($ORP[$i][0] eq $order_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ORP[$i][0]\" $SEL>$ORP[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>手配先区分</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=maker_flag>" ;
		if($MAK_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$MAK_NUM;$i++){
			if($MAK[$i][0] eq $maker_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$MAK[$i][0]\" $SEL>$MAK[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>分類</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item_type1 VALUE=\"$item_type1\" SIZE=31 MAXLENGTH=30></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>検索区分</TD>\n" ;
print "   <TD COLSPAN=5>";
print "   <SELECT NAME=inspection_type>" ;
		if($INSP_NUM == 0){ print "<OPTION VALUE=''>マスタ登録なし" ; }
		for($i=0;$i<$INSP_NUM;$i++){
			if($INSP[$i][0] eq $inspection_type){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$INSP[$i][0]\" $SEL>$INSP[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;
print<<ENDOFTEXT;
<P>
<INPUT TYPE=Hidden NAME=TYPES VALUE='$TYPES'>
<input type=submit name=submit value="次へ">
<input type=button value='戻る' onClick='javascript:history.back()'>
<input type=reset name=submit value="入力リセット">
</FORM>
<HR>
</BODY>
</HTML>
ENDOFTEXT

exit;
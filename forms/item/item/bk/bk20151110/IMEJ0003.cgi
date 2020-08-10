#
# 2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）
# 2015/06/26 NTTk)Hino  必須項目の入力欄の背景を黄色に変更、PACKING INFORMATIONのタイトル背景色を緑から薄青に変更
#
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
$key_word           = $in{'key_word'} ;#add 2015/04/21

#add 2015/04/21 Start
$FLG = $in{'FLG'} ;  # 動作フラグ(1=PACKING_INFORMATION再読込)(add 2015/04/21)

# PackingInformation再読込時
if ($FLG eq "1") {
    $pi_no_input = $in{'pi_no'} ;
	foreach(keys (%in)){
	  $$_ = $in{$_} ;
	  $$_ =~ s/\r//g ;
	}
}

#add 2015/06/26 Start
#背景色
$COLOR_NOT_NULL = '#FFFF00' ; #(yellow)
$COLOR_PI       = '#87CEEB' ; #(skyblue)
#add 2015/06/26 End


#add 2015/04/21 End

if($TYPES eq ""){$TYPES="NEW" ;}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "CHANGE"){
	#ITEM
	# mod 2015/04/21 Start
	#$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	#$sql .= "where  i.item_no            = '$item_no'";
	#$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	$sql  = "select i.*, s.stock_subject, " ;
	$sql .= "       p.plt_spec_no, p.pallet_unit_number, p.pallet_ctn_number, " ;
	$sql .= "       p.pallet_step_ctn_number, p.pallet_height, p.pallet_width, pallet_depth, " ;
	$sql .= "       p.pallet_size_type, " ;
	$sql .= "       i.external_unit_number as outer_box_unit_number " ;
	$sql .= "from   item i, " ;
	$sql .= "       stock_subject s, " ;
	$sql .= "       packing_information p " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	$sql .= "  and  i.pi_no              = p.pi_no(+) ";
	# mod 2015/04/21 End
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

	#CLASS
	$sql = "SELECT CLASS_CODE,CLASS_1,CLASS_2,CLASS_3 FROM CLASS ORDER BY CLASS_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_CLASS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_CLASS err $DBI::err .... $DBI::errstr") ;
	$CLAS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CLAS[$CLAS_NUM][0] = $datas[0] ;
		$CLAS[$CLAS_NUM][1] = $datas[1] . "-" . $datas[2] . "-" . $datas[3];
		$CLAS_NUM ++ ;
	}
	$cur->finish;

    #add 2015/04/21 Start
	#LABEL_TYPE
	$sql = "SELECT LABEL_TYPE_CODE, LABEL_TYPE_NAME FROM LABEL_TYPE ORDER BY LABEL_TYPE_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_LABEL_TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_LABEL_TYPE err $DBI::err .... $DBI::errstr") ;
	$LABEL_TYPE_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$LABEL_TYPE[$LABEL_TYPE_NUM][0] = $datas[0] ;
		$LABEL_TYPE[$LABEL_TYPE_NUM][1] = $datas[1] ;
		$LABEL_TYPE_NUM ++ ;
	}
	$cur->finish;
	
	#PALLET_SIZE_TYPE
	$sql = "SELECT PALLET_SIZE_TYPE_CODE, PALLET_SIZE_TYPE_NAME FROM PALLET_SIZE_TYPE ORDER BY PALLET_SIZE_TYPE_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_PALLET_SIZE_TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_PALLET_SIZE_TYPE err $DBI::err .... $DBI::errstr") ;
	$PALLET_SIZE_TYPE_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$PALLET_SIZE_TYPE[$PALLET_SIZE_TYPE_NUM][0] = $datas[0] ;
		$PALLET_SIZE_TYPE[$PALLET_SIZE_TYPE_NUM][1] = $datas[1] ;
		$PALLET_SIZE_TYPE_NUM ++ ;
	}
	$cur->finish;
	
	# PackingInformation再読込時
	if($FLG eq "1"){
		#PACKING_INFORMATION
		$sql  = "select PI_NO, PLT_SPEC_NO, PALLET_UNIT_NUMBER, PALLET_CTN_NUMBER, " ;
		$sql .= "       PALLET_STEP_CTN_NUMBER, PALLET_HEIGHT, PALLET_WIDTH, PALLET_DEPTH, ";
		$sql .= "       PALLET_SIZE_TYPE ";
		$sql .= "from   PACKING_INFORMATION ";
		$sql .= "where  PI_NO = trim('$pi_no_input') ";
		$cur = $dbh->prepare($sql) || &err("PACKING_INFORMATION err $DBI::err .... $DBI::errstr") ;
		$cur->execute() || &err("PACKING_INFORMATION err $DBI::err .... $DBI::errstr") ;
		@datas = $cur->fetchrow() ;
		if (@datas > 0) {
			$pi_no                  = $datas[0] ;
			$plt_spec_no            = $datas[1] ;
			$pallet_unit_number     = $datas[2] ;
			$pallet_ctn_number      = $datas[3] ;
			$pallet_step_ctn_number = $datas[4] ;
			$pallet_height          = $datas[5] ;
			$pallet_width           = $datas[6] ;
			$pallet_depth           = $datas[7] ;
			$pallet_size_type       = $datas[8] ;
		} else {
			$pi_no                  = $pi_no_input ;
			$plt_spec_no            = "" ;
			$pallet_unit_number     = "" ;
			$pallet_ctn_number      = "" ;
			$pallet_step_ctn_number = "" ;
			$pallet_height          = "" ;
			$pallet_width           = "" ;
			$pallet_depth           = "" ;
			$pallet_size_type       = "" ;
		}
	}
	
    #add 2015/04/21 End

#	#INSPECTIONTYPE
#	$sql = "select inspection_type,inspection_name from inspectiontype order by inspection_type";
#	#print $sql . "<P>";
#	$cur = $dbh->prepare($sql) || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
#	$cur->execute() || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
#	$INSP_NUM = 0 ;
#	while(@datas = $cur->fetchrow){
#		$INSP[$INSP_NUM][0] = $datas[0] ;
#		$INSP[$INSP_NUM][1] = $datas[1] ;
#		$INSP_NUM ++ ;
#	}
#	$cur->finish;

#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0003');
print <<ENDOFTEXT ;
<SCRIPT LANGUAGE=javascript SRC=../../lib/jslib.js></SCRIPT>
<SCRIPT LANGUAGE=javascript>
	function checkInput(theForm){
		// if (!isNumber(theForm.external_unit_number.value)){ theForm.external_unit_number.value = 0 ;}
		if (!isNumber(theForm.external_unit_number.value)){ theForm.external_unit_number.value = 0 ; theForm.outer_unit_number.value = 0 ;} // mod 2015/04/21
		if (!isNumber(theForm.safety_stock.value        )){ theForm.safety_stock.value = 0         ;}
		if (!isNumber(theForm.weight.value              )){ theForm.weight.value = 0               ;}
		if (!isNumber(theForm.suppliers_price.value     )){ theForm.suppliers_price.value = 0      ;}
		if (!isNumber(theForm.manufact_leadtime.value   )){ theForm.manufact_leadtime.value = 0    ;}
		if (!isNumber(theForm.purchase_leadtime.value   )){ theForm.purchase_leadtime.value = 0    ;}
		if (!isNumber(theForm.adjustment_leadtime.value )){ theForm.adjustment_leadtime.value = 0  ;}
		if (!isNumber(theForm.manufact_fail_rate.value  )){ theForm.manufact_fail_rate.value = 0   ;}

		if (theForm.weight.value           > 999999.99999     ){ alert("Weight is the input less than '999999.99999'.");			return false; }
		if (theForm.standard_price.value   > 999999999.9999   ){ alert("Standard price is the input less than '999999999.9999'.");	return false; }
		if (theForm.next_term_price.value  > 999999999.9999   ){ alert("Next_term_price is the input less than '999999999.9999'.");	return false; }
		if (theForm.suppliers_price.value  > 999999999.9999   ){ alert("Suppliers_price is the input less than '999999999.9999'.");	return false; }

		if (!isNumber(theForm.item_no.value             )){ alert("Data input into 'ITEM NO' is not right.");			return false; }
		if (!isNumber(theForm.external_unit_number.value)){ alert("Data input into 'EXTERNAL UNIT NUMBER' is not right.");	return false; }
		if (!isNumber(theForm.safety_stock.value        )){ alert("Data input into 'SAFETY STOCK' is not right.");		return false; }
		if (!isNumber(theForm.weight.value              )){ alert("Data input into 'WEIGHT' is not right.");			return false; }
		if (!isNumber(theForm.unit_engineer_rate.value  )){ alert("Data input into 'UNIT ENGINEER RATE' is not right.");	return false; }
		if (!isNumber(theForm.unit_stock_rate.value     )){ alert("Data input into 'UNIT STOCK RATE' is not right.");		return false; }
		if (!isNumber(theForm.standard_price.value      )){ alert("Data input into 'STANDARD PRICE' is not right.");		return false; }
		if (!isNumber(theForm.next_term_price.value     )){ alert("Data input into 'NEXT TERM PRICE' is not right.");		return false; }
		if (!isNumber(theForm.manufact_leadtime.value   )){ alert("Data input into 'MANUFACT LEADTIME' is not right.");	return false; }
		if (!isNumber(theForm.purchase_leadtime.value   )){ alert("Data input into 'PURCHASE LEADTIME' is not right.");	return false; }
		if (!isNumber(theForm.adjustment_leadtime.value )){ alert("Data input into 'ADJUSTMENT LEADTIME' is not right.");	return false; }
		if (!isNumber(theForm.manufact_fail_rate.value  )){ alert("Data input into 'MANUFACT FAIL RATE' is not right.");	return false; }
	}
	function isNumber(str){
		if (str.length == 0){ return false; }
			for (var i=0; i< str.length; i++){
				if ( "0123456789.".indexOf(str.charAt(i))== -1){ return false; }
			}
			return true;
	}
    
    // External Unit Number の値を、Outer Box(Unit Number) にコピー (add 2015/04/21)
    function copy(){
    	document.forms[0].outer_box_unit_number.value = document.forms[0].external_unit_number.value;
    }
    
    // PackingInformation情報取得（リロード）
    function get_pack_info(){
	    document.forms[0].method = "post";
	    document.forms[0].action = "IMEJ0003.cgi?$KEYWORD";
	    document.forms[0].submit();
    }
    
    // BACKボタン押下処理
    function go_back(){
    	var URL;
		if( "$TYPES" == "CHANGE" ){
			URL = "IMEJ0002.cgi";
		} else {
			URL = "IMEJ0001.cgi";
		}
	    document.forms[0].method = "post";
	    document.forms[0].action = URL + "?$KEYWORD";
	    document.forms[0].submit();
    }
</SCRIPT>
ENDOFTEXT
if($TYPES eq "NEW"){
	print "<FONT SIZE = +1 COLOR='blue'>[<B>$TYPES</B>] " ;
 	print "--- <FONT COLOR=RED><B>Red letter item</B> </FONT>is a required item ----" ;
}else{
	print "<FONT SIZE = +1 COLOR='blue'>[<B>$TYPES</B>]<BR>" ;
}
print "<BR>" ;
print "<FORM METHOD=Post NAME=myform ACTION=IMEJ0004.cgi?$KEYWORD onSubmit=\"return checkInput(this)\">" ;
print "  <INPUT TYPE=Hidden NAME=FLG VALUE=\"1\">\n" ; #add 2015/04/21
print "  <INPUT TYPE=Hidden NAME=key_word VALUE=\"$key_word\">\n" ; #add 2015/04/21
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>ITEM_NO</B></FONT></TD>\n" ;
if($TYPES eq "NEW"){
	print "   <TD><INPUT TYPE=Text NAME=item_no VALUE=\"$item_no\" SIZE=9 MAXLENGTH=8 onChange= 'chkNum(this.form,this.value,\"8,0\")' style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
}else{
	print "   <TD> $item_no<INPUT TYPE=Hidden NAME=item_no VALUE=\"$item_no\"></TD>\n" ;
}
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>STOCK_SUBJECT_CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=stock_subject_code>" ;
		if($SSC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$SSC_NUM;$i++){
			if($SSC[$i][0] eq $stock_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SSC[$i][0]\" $SEL>$SSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>ITEM NAME</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item VALUE=\"$item\" SIZE=41 MAXLENGTH=40 style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ITEM FLAG</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=item_flag>" ;
		if($IMF_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$IMF_NUM;$i++){
			if($IMF[$i][0] eq $item_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$IMF[$i][0]\" $SEL>$IMF[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DESCRIPTION</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=description VALUE=\"$description\" SIZE=51 MAXLENGTH=50></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>ORIGIN</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=origin_code>" ;
		if($COU_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		if($origin_code eq ""){ $origin_code = $my_country_code ; }
		for($i=0;$i<$COU_NUM;$i++){
			if($COU[$i][0] eq $origin_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$COU[$i][0]\" $SEL>$COU[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MAKER</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=mak VALUE=\"$mak\" SIZE=31 MAXLENGTH=30></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>CURRENCY</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=curr_code>" ;
		if($CURR_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
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
print "   <TD BGCOLOR=LightGreen>CLASS CODE</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=class_code style='background-color:$COLOR_NOT_NULL'>" ;
		if($CLAS_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$CLAS_NUM;$i++){
			if($CLAS[$i][0] eq $class_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CLAS[$i][0]\" $SEL>$CLAS[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>EXTERNAL_UNIT_NUMBER</TD>\n" ;
#print "   <TD><INPUT TYPE=Text NAME=external_unit_number VALUE=\"$external_unit_number\" SIZE=6 MAXLENGTH=5 onChange='chkNum(this.form,this.value,\"5,0\")'></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=external_unit_number VALUE=\"$external_unit_number\" SIZE=6 MAXLENGTH=5 onChange='copy(); chkNum(this.form,this.value,\"5,0\")'></TD>\n" ; #mod 2015/04/21
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>SAFETY_STOCK</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=safety_stock VALUE=\"$safety_stock\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>WEIGHT</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=weight VALUE=\"$weight\" SIZE=13 MAXLENGTH=12 onChange='chkNum(this.form,this.value,\"11,5\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>UNIT of WEIGH</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_w style='background-color:$COLOR_NOT_NULL'>" ;
		if($UNW_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$UNW_NUM;$i++){
			if($UNW[$i][0] eq $uom_w){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNW[$i][0]\" $SEL>$UNW[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>UNIT of MEASUREMENT</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_l style='background-color:$COLOR_NOT_NULL'>" ;
		if($UNL_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$UNL_NUM;$i++){
			if($UNL[$i][0] eq $uom_l){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNL[$i][0]\" $SEL>$UNL[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
#print "   <TD BGCOLOR=LightGreen COLSPAN=3 ALIGN='Right'><FONT COLOR=RED><B>UNIT STOCK RATE(UNIT STOCK):UNIT ENGINEER RATE(UNIT ENGINEERING)</B></FONT></TD>\n" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3 ALIGN='Right'><FONT COLOR=RED><B>UNIT STOCK RATE:UNIT ENGINEER RATE</B></FONT></TD>\n" ;
		if($unit_stock_rate    eq ""){ $unit_stock_rate = 1 ; }
		if($unit_engineer_rate eq ""){ $unit_engineer_rate = 1 ; }

print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=unit_stock_rate VALUE=\"$unit_stock_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")' style='background-color:$COLOR_NOT_NULL'>\n" ;
print "   <SELECT NAME=unit_stock style='background-color:$COLOR_NOT_NULL'>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_stock){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print " ： <INPUT TYPE=Text NAME=unit_engineer_rate VALUE=\"$unit_engineer_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")' style='background-color:$COLOR_NOT_NULL'>\n" ;
print "   <SELECT NAME=unit_engineering style='background-color:$COLOR_NOT_NULL'>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
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
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>CATALOG_NO</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=catalog_no VALUE=\"$catalog_no\" SIZE=29 MAXLENGTH=28></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ITEM CODE</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item_code VALUE=\"$item_code\" SIZE=25 MAXLENGTH=24></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>SUPPLIERS_PRICE</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=suppliers_price VALUE=\"$suppliers_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DRAWING_NO</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=drawing_no VALUE=\"$drawing_no\" SIZE=21 MAXLENGTH=20></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DRAWING_REV</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=drawing_rev VALUE=\"$drawing_rev\" SIZE=2 MAXLENGTH=1></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>APPLICABLE_MODEL</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=applicable_model VALUE=\"$applicable_model\" SIZE=21 MAXLENGTH=20></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 WIDTH='100%'></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='16%'><B>LAST_TERM_PRICE</B></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='16%'>$last_term_price<BR><INPUT TYPE=Hidden NAME=last_term_price VALUE=$last_term_price></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED><B>STANDARD_PRICE</B></FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%'><INPUT TYPE=Text NAME=standard_price VALUE=\"$standard_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")' style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED><B>NEXT_TERM_PRICE</B></FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%'><INPUT TYPE=Text NAME=next_term_price VALUE=\"$next_term_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD> <FONT SIZE = -1 COLOR='RED'>When \"NEXT_TERM_PRICE\" is not decided, please register \"STANDARD_PRICE\" and the same amount.</FONT><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MANUFACT LEADTIME</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_leadtime VALUE=\"$manufact_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>PURCHASE LEADTIME</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=purchase_leadtime VALUE=\"$purchase_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ADJUSTMENT LEADTIME</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=adjustment_leadtime VALUE=\"$adjustment_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>Days</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ISSUE POLICY</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=issue_policy>" ;
		if($ISS_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$ISS_NUM;$i++){
			if($ISS[$i][0] eq $issue_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ISS[$i][0]\" $SEL>$ISS[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ISSUE LOT</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=issue_lot VALUE=\"$issue_lot\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MANUFACT_FAIL_RATE</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_fail_rate VALUE=\"$manufact_fail_rate\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>%</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>SECTION_CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=section_code style='background-color:$COLOR_NOT_NULL'>" ;
		if($SEC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$SEC_NUM;$i++){
			if($SEC[$i][0] eq $section_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SEC[$i][0]\" $SEL>$SEC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>COST PROCESS CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_process_code style='background-color:$COLOR_NOT_NULL'>" ;
		if($CPC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$CPC_NUM;$i++){
			if($CPC[$i][0] eq $cost_process_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CPC[$i][0]\" $SEL>$CPC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>COST_SUBJECT_CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_subject_code style='background-color:$COLOR_NOT_NULL'>" ;
		if($CSC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$CSC_NUM;$i++){
			if($CSC[$i][0] eq $cost_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CSC[$i][0]\" $SEL>$CSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><BR></TD>" ;
print "   <TD><BR></TD>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ORDER_POLICY</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=order_policy>" ;
		if($ORP_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$ORP_NUM;$i++){
			if($ORP[$i][0] eq $order_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ORP[$i][0]\" $SEL>$ORP[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MAKER_FLAG</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=maker_flag>" ;
		if($MAK_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$MAK_NUM;$i++){
			if($MAK[$i][0] eq $maker_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$MAK[$i][0]\" $SEL>$MAK[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>ITEM_TYPE</TD>\n" ;
print "   <TD COLSPAN=5><INPUT TYPE=Text NAME=item_type1 VALUE=\"$item_type1\" SIZE=31 MAXLENGTH=30> \n" ;
print "                 <INPUT TYPE=Text NAME=item_type2 VALUE=\"$item_type2\" SIZE=51 MAXLENGTH=50></TD>\n" ;
print " </TR>" ;
 # FIのみ、客先梱包単位を入力できるようにする
 if ($CONF{'FI_PACKAGE_UNIT'} eq "1") {
     print " <TR>" ;
     print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>PACKAGE UNIT NUMBER</B></FONT></TD>\n" ;
     print "   <TD COLSPAN=5>\n" ;
     print "      <INPUT TYPE=Text NAME=package_unit_number VALUE=\"$package_unit_number\" SIZE=7 MAXLENGTH=6 style='background-color:$COLOR_NOT_NULL'> \n" ;
     print "      <SELECT NAME=unit_package style='background-color:$COLOR_NOT_NULL'>\n" ;
     print "          <OPTION VALUE=\"\">\n" ;
                      for ($i=0 ; $i<$UNQ_NUM ; $i++){
                          undef $selected ;
                          $selected = "selected" if ($UNQ[$i][0] eq $unit_package) ;
                          print " <OPTION VALUE=\"$UNQ[$i][0]\" $selected>$UNQ[$i][1]" ;
                      }
     print "      </SELECT>\n" ;
     print " </TR>" ;
 }
print "</TBODY></TABLE>" ;
#--------------------------------------------------------------------------------------------------------------------------
# add 2015/04/21 Start
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>CAPACITY</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=capacity VALUE=\"$capacity\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DATE CODE TYPE</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=date_code_type VALUE=\"$date_code_type\" SIZE=8 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DATE CODE MONTH</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=date_code_month VALUE=\"$date_code_month\" SIZE=8 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>PACKAGING GROUPING</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=label_type style='background-color:$COLOR_NOT_NULL'>" ;
		  print "<OPTION VALUE=''>" ;
          for( $i=0; $i<$LABEL_TYPE_NUM; $i++ ){
            if( $LABEL_TYPE[$i][0] eq $label_type ){
              $SEL = "SELECTED" ;
            }else{ 
              $SEL = "" ;
            }
            print " <OPTION VALUE=\"$LABEL_TYPE[$i][0]\" $SEL>$LABEL_TYPE[$i][1]" ;
          }
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MEASUREMENT</TD>\n" ;
print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=measurement VALUE=\"$measurement\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>&nbsp;</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>HEIGHT</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>WIDTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>DEPTH</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center' COLSPAN=2>UNIT NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>INNER BOX</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=inner_box_height VALUE=\"$inner_box_height\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=inner_box_width VALUE=\"$inner_box_width\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=inner_box_depth VALUE=\"$inner_box_depth\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=inner_box_unit_number VALUE=\"$inner_box_unit_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MEDIUM BOX</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=medium_box_height VALUE=\"$medium_box_height\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=medium_box_width VALUE=\"$medium_box_width\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=medium_box_depth VALUE=\"$medium_box_depth\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=medium_box_unit_number VALUE=\"$medium_box_unit_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>OUTER BOX</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=outer_box_height VALUE=\"$outer_box_height\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=outer_box_width VALUE=\"$outer_box_width\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=outer_box_depth VALUE=\"$outer_box_depth\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=outer_box_unit_number VALUE=\"$outer_box_unit_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right; border:none;' readonly>&nbsp;PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'><FONT COLOR=RED><B>PACKING<BR>INFORMATION NO</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=pi_no VALUE=\"$pi_no\" SIZE=18 MAXLENGTH=25 onChange=\"get_pack_info();\" style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'><FONT COLOR=RED><B>PLT SPEC NO</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=plt_spec_no VALUE=\"$plt_spec_no\" SIZE=22 MAXLENGTH=20 style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'><FONT COLOR=RED><B>PALLET SIZE TYPE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=pallet_size_type style='background-color:$COLOR_NOT_NULL'>" ;
          print "<OPTION VALUE=''>" ;
          for( $i=0; $i<$PALLET_SIZE_TYPE_NUM; $i++ ){
            if( $PALLET_SIZE_TYPE[$i][0] eq $pallet_size_type ){
              $SEL = "SELECTED" ;
            }else{ 
              $SEL = "" ;
            }
            print " <OPTION VALUE=\"$PALLET_SIZE_TYPE[$i][0]\" $SEL>$PALLET_SIZE_TYPE[$i][1]" ;
          }
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center' ROWSPAN=4>PALLET</TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'>HEIGHT</TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'>WIDTH</TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'>DEPTH</TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center' COLSPAN=2>UNIT NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD ROWSPAN=3><INPUT TYPE=Text NAME=pallet_height VALUE=\"$pallet_height\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD ROWSPAN=3><INPUT TYPE=Text NAME=pallet_width VALUE=\"$pallet_width\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD ROWSPAN=3><INPUT TYPE=Text NAME=pallet_depth VALUE=\"$pallet_depth\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=pallet_unit_number VALUE=\"$pallet_unit_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;PC</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'>CTN NUMBER</TD>\n" ;
print "   <TD BGCOLOR='$COLOR_PI' ALIGN='Center'>STEP CTN NUMBER</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD><INPUT TYPE=Text NAME=pallet_ctn_number VALUE=\"$pallet_ctn_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=pallet_step_ctn_number VALUE=\"$pallet_step_ctn_number\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='text-align:right;'>&nbsp;mm</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>OPERATION TIME</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=operation_time VALUE=\"$operation_time\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"24,8\")' style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>MAN POWER</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=man_power VALUE=\"$man_power\" SIZE=18 MAXLENGTH=25 onChange='chkNum(this.form,this.value,\"6,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>AGING DAY</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=aging_day VALUE=\"$aging_day\" SIZE=5 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"6,0\")' style='background-color:$COLOR_NOT_NULL'></TD>\n" ;
print " </TR>" ;

print "</TBODY></TABLE>" ;
# add 2015/04/21 End

print "</TD></TR></TBODY></TABLE>" ;
print<<ENDOFTEXT;
<P>
<INPUT TYPE=Hidden NAME=TYPES VALUE='$TYPES'>
<!-- mod 2015/04/21 Start
<input type=submit name=submit value="NEXT">
<input type=button value='BACK' onClick='javascript:history.back()'>
<input type=reset name=submit value="RESET">
-->
<input type="submit" name="btn_submit" value="NEXT">
<input type="button" name="btn_back"   value="BACK" onClick="javascript:go_back()">
<input type="reset"  name="btn_reset"  value="RESET">
<!-- mod  2015/04/21 End -->

</FORM>
<HR>
</BODY>
</HTML>
ENDOFTEXT

exit;
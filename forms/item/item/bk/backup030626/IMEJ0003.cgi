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

if($TYPES eq ""){$TYPES="NEW" ;}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "CHANGE"){
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
		if (!isNumber(theForm.external_unit_number.value)){ theForm.external_unit_number.value = 0 ;}
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
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>ITEM_NO</B></FONT></TD>\n" ;
if($TYPES eq "NEW"){
	print "   <TD><INPUT TYPE=Text NAME=item_no VALUE=\"$item_no\" SIZE=9 MAXLENGTH=8 onChange= 'chkNum(this.form,this.value,\"8,0\")'></TD>\n" ;
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
print "   <TD><INPUT TYPE=Text NAME=item VALUE=\"$item\" SIZE=41 MAXLENGTH=40></TD>\n" ;
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
print "   <SELECT NAME=class_code>" ;
		if($CLAS_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$CLAS_NUM;$i++){
			if($CLAS[$i][0] eq $class_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CLAS[$i][0]\" $SEL>$CLAS[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>EXTERNAL_UNIT_NUMBER</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=external_unit_number VALUE=\"$external_unit_number\" SIZE=6 MAXLENGTH=5 onChange='chkNum(this.form,this.value,\"5,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>SAFETY_STOCK</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=safety_stock VALUE=\"$safety_stock\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'>WEIGHT</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=weight VALUE=\"$weight\" SIZE=13 MAXLENGTH=12 onChange='chkNum(this.form,this.value,\"11,5\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>UNIT of WEIGH</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_w>" ;
		if($UNW_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$UNW_NUM;$i++){
			if($UNW[$i][0] eq $uom_w){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNW[$i][0]\" $SEL>$UNW[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>UNIT of MEASUREMENT</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_l>" ;
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

print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=unit_stock_rate VALUE=\"$unit_stock_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_stock>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_stock){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print " ÅF <INPUT TYPE=Text NAME=unit_engineer_rate VALUE=\"$unit_engineer_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_engineering>" ;
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
print "   <TD ALIGN='Right' WIDTH='17%'><INPUT TYPE=Text NAME=standard_price VALUE=\"$standard_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
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
print "   <SELECT NAME=section_code>" ;
		if($SEC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$SEC_NUM;$i++){
			if($SEC[$i][0] eq $section_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SEC[$i][0]\" $SEL>$SEC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>COST PROCESS CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_process_code>" ;
		if($CPC_NUM == 0){ print "<OPTION VALUE=''>A master does not have registration." ; }
		for($i=0;$i<$CPC_NUM;$i++){
			if($CPC[$i][0] eq $cost_process_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CPC[$i][0]\" $SEL>$CPC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Center'><FONT COLOR=RED><B>COST_SUBJECT_CODE</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_subject_code>" ;
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
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;
print<<ENDOFTEXT;
<P>
<INPUT TYPE=Hidden NAME=TYPES VALUE='$TYPES'>
<input type=submit name=submit value="NEXT">
<input type=button value='BACK' onClick='javascript:history.back()'>
<input type=reset name=submit value="RESET">
</FORM>
<HR>
</BODY>
</HTML>
ENDOFTEXT

exit;
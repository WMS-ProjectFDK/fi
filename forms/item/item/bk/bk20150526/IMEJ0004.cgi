#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
foreach(keys (%in)){
  $$_ = $in{$_} ;
  $$_ =~ s/\r//g ;
}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "NEW"){
	# 重複確認
	$sql = "SELECT count(*) FROM ITEM where ITEM_NO ='$item_no' ";
	$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	($COUNT) = $cur->fetchrow() ;
	$cur->finish;
	if($COUNT >0){ &err("There is already this item no.") ;}
}

if($TYPES eq "DELETE" or $TYPES eq "UNDELETION"){
	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	@datas = $cur->fetchrow() ;
	for($j=0;$j<@datas;$j++){
		$cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
		${$cur->{NAME}->[$j]} = $datas[$j] ;
	}
}

	#ITEMFLAG
	$sql = "select item_flag,flag_name from itemflag order by flag_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$IMF_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$IMF[$datas[0]] = $datas[1] ;
		$IMF_NUM ++ ;
	}
	$cur->finish;

	#COUNTRY
	$sql = "select country_code,country from country order by area_code,country";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$COU_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$COU[$datas[0]] = $datas[1] ;
		$COU_NUM ++ ;
	}
	$cur->finish;

	#UNIT
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'QUANTITY' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNQ_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNQ[$datas[0]] = $datas[1] ;
		$UNQ_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'WEIGHT' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNW_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNW[$datas[0]] = $datas[1] ;
		$UNW_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'LENGTH' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNL_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNL[$datas[0]] = $datas[1] ;
		$UNL_NUM ++ ;
	}
	$cur->finish;

	#ISSUEPOLICY
	$sql = "select issue_policy,policy_name from issuepolicy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$ISS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ISS[$datas[0]] = $datas[1] ;
		$ISS_NUM ++ ;
	}
	$cur->finish;

	#SECTION
	$sql = "select section_code,section from section order by short_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$SEC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SEC[$datas[0]] = $datas[1] ;
		$SEC_NUM ++ ;
	}
	$cur->finish;

	#STOCK_SUBJECT
	$sql = "select stock_subject_code,stock_subject from stock_subject order by stock_subject_code";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$datas[0]] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish;

	#COSTPROCESS
	$sql = "select cost_process_code,cost_process_name from costprocess order by cost_process_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$CPC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CPC[$datas[0]] = $datas[1] ;
		$CPC_NUM ++ ;
	}
	$cur->finish;

	#COSTSUBJECT
	$sql = "select cost_subject_code,cost_subject_name from costsubject order by cost_subject_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$CSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CSC[$datas[0]] = $datas[1] ;
		$CSC_NUM ++ ;
	}
	$cur->finish;

	#ORDER_POLICY
	$sql = "select order_policy,policy_name from order_policy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$ORP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ORP[$datas[0]] = $datas[1] ;
		$ORP_NUM ++ ;
	}
	$cur->finish;

	#MAKERFLAG
	$sql = "select maker_flag_code,maker_flag from makerflag order by maker_flag";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$MAK_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$MAK[$datas[0]] = $datas[1] ;
		$MAK_NUM ++ ;
	}
	$cur->finish;

	#CURRENCY
	$sql = "select curr_code,curr_mark from currency order by curr_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$CURR_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CURR[$datas[0]] = $datas[1] ;
		$CURR_NUM ++ ;
	}
	$cur->finish;

	#CLASS
	$sql = "SELECT CLASS_CODE,CLASS_1,CLASS_2,CLASS_3 FROM CLASS ORDER BY CLASS_CODE";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CLASS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CLASS err $DBI::err .... $DBI::errstr") ;
	$CLAS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CLAS[$datas[0]] = $datas[1] . "-" . $datas[2] . "-" . $datas[3];
		$CLAS_NUM ++ ;
	}
	$cur->finish;

	# ERROR CHECK
	$ERR_MSG			= "" ;
	$BG_ITEM_NO		= "WHITE";
	$BG_STOCK_SUBJECT	= "WHITE";
	$BG_ITEM			= "WHITE";
	$BG_ORIGIN_CODE	= "WHITE";
	$BG_CURR_CODE		= "WHITE";
	$BG_UOM_W			= "WHITE";
	$BG_UOM_L			= "WHITE";
	$BG_UNIT			= "WHITE";
	$BG_SECTION		= "WHITE";
	$BG_PROCESS		= "WHITE";
	$BG_SUBJECT		= "WHITE";
	$BG_STP			= "WHITE";
	$BG_NTP			= "WHITE";
	$BG_SUP			= "WHITE";
	$BG_EUN			= "WHITE";
	$BG_SAF			= "WHITE";
	$BG_WEI			= "WHITE";
	$BG_MAN			= "WHITE";
	$BG_PUR			= "WHITE";
	$BG_ADJ			= "WHITE";
	$BG_MFR			= "WHITE";
	$BG_ISS			= "WHITE";
	$BG_PUN         = "WHITE";

if($TYPES ne "DELETE"){
		&err("Item No is not found.") if($item_no eq "");
		if($item_no =~ /[^0-9 .]/){ $ERROR .=" 'STANDARD PRICE' is not right.<BR>" ;				$BG_ITEM_NO       = "lightpink"; $ERR_MSG = "ERR"; }
		if($stock_subject_code eq ""){ $ERROR .=" 'STOCK SUBJECT CODE' is not right.<BR>";			$BG_STOCK_SUBJECT = "lightpink"; $ERR_MSG = "ERR"; }
		if($item               eq ""){ $ERROR .=" 'ITEM' is not right.。<BR>" ;					$BG_ITEM          = "lightpink"; $ERR_MSG = "ERR"; }
		if($origin_code        eq ""){ $ERROR .=" 'ORIGIN' is not right.<BR>";					$BG_ORIGIN_CODE   = "lightpink"; $ERR_MSG = "ERR"; }
		if($curr_code          eq ""){ $ERROR .=" 'CURRENCY' is not right.<BR>";					$BG_CURR_CODE     = "lightpink"; $ERR_MSG = "ERR"; }
		if($uom_w              eq ""){ $ERROR .=" 'UNIT of WEIGH' is not right.<BR>";				$BG_UOM_W         = "lightpink"; $ERR_MSG = "ERR"; }
		if($uom_l              eq ""){ $ERROR .=" 'UNIT of MEASUREMENT' is not right.<BR>";			$BG_UOM_L         = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_stock         eq ""){ $ERROR .=" 'UNIT_STOCK' is not right.<BR>";					$BG_UNIT          = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_engineering   eq ""){ $ERROR .=" 'UNIT ENGINEERING' is not right.<BR>";			$BG_UNIT          = "lightpink"; $ERR_MSG = "ERR"; }
		if($section_code       eq ""){ $ERROR .=" 'SECTION CODE' is not right.<BR>";				$BG_SECTION       = "lightpink"; $ERR_MSG = "ERR"; }
		if($cost_process_code  eq ""){ $ERROR .=" 'COST PROCESS CODE' is not right.<BR>";			$BG_PROCESS       = "lightpink"; $ERR_MSG = "ERR"; }
		if($cost_subject_code  eq ""){ $ERROR .=" 'COST SUBJECT CODE' is not right.<BR>";			$BG_SUBJECT       = "lightpink"; $ERR_MSG = "ERR"; }

		if($unit_stock_rate      =~ /[^0-9 .]/){ $ERROR .=" 'UNIT STOCK RATE' is not right.<BR>";		$BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($unit_engineer_rate   =~ /[^0-9 .]/){ $ERROR .=" 'UNIT ENGINEER RATE' is not right.<BR>";	$BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($standard_price       =~ /[^0-9 .]/){ $ERROR .=" 'STANDARD PRICE' is not right.<BR>";		$BG_STP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($next_term_price      =~ /[^0-9 .]/){ $ERROR .=" 'NEXT TERM PRICE' is not right.<BR>";		$BG_NTP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($suppliers_price      =~ /[^0-9 .]/){ $ERROR .=" 'SUPPLIERS PRICE' is not right.<BR>";		$BG_SUP  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($external_unit_number =~ /[^0-9 .]/){ $ERROR .=" 'EXTERNAL UNIT NUMBER' is not right.<BR>";	$BG_EUN  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($safety_stock         =~ /[^0-9 .]/){ $ERROR .=" 'SAFETY STOCK' is not right.<BR>";		$BG_SAF  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($weight               =~ /[^0-9 .]/){ $ERROR .=" 'WEIGHT' is not right.<BR>";			$BG_WEI  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($manufact_leadtime    =~ /[^0-9 .]/){ $ERROR .=" 'MANUFACT LEADTIME' is not right.<BR>";	$BG_MAN  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($purchase_leadtime    =~ /[^0-9 .]/){ $ERROR .=" 'PURCHASE LEADTIME' is not right.<BR>";	$BG_PUR  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($adjustment_leadtime  =~ /[^0-9 .]/){ $ERROR .=" 'ADJUSTMENT LEADTIME' is not right.<BR>";	$BG_ADJ  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($manufact_fail_rate   =~ /[^0-9 .]/){ $ERROR .=" 'MANUFACT FAIL RATE' is not right.<BR>";	$BG_MFR  ="lightpink" ; $ERR_MSG = "ERR"; }
		if($issue_lot            =~ /[^0-9 .]/){ $ERROR .=" 'ISSUE LOT' is not right.<BR>";			$BG_ISS  ="lightpink" ; $ERR_MSG = "ERR"; }

        # FIでSTOCK_SUBJECT_CODE(製品)の場合のみ必須入力項目
        if ($CONF{'FI_PACKAGE_UNIT'} eq "1") {
            $package_flg = "err"   if ($package_unit_number eq ""  and  $stock_subject_code == 5) ;
            $package_flg = "err"   if ($package_unit_number ne ""  and  $package_unit_number =~ /[\D+]/) ;
            $package_flg = "err"   if ($package_unit_number ne ""  and  length($package_unit_number) > 6) ;
            $package_flg = "err"   if (!$unit_package  and  $stock_subject_code == 5) ; 

            if ($package_flg eq "err") {
                $ERROR .=" 'PACKAGE UNIT NUUMBER or PACKAGE UNIT' is not right.<BR>";
                $BG_PUN ="lightpink" ; 
                $ERR_MSG = "ERR";
            }
        }
}
#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0004');

print "<FONT SIZE = +1 COLOR='bLUE'>[<B>$TYPES</B>] " ;
if($ERR_MSG eq ""){
 	print " --- Please push a button to register the following contents with after confirmation. --- " ;
}else{
 	print "<FONT COLOR='RED'> --- The data which you input include an error. Please confirm an error point, and change it. --- </FONT>" ;
}

print "<BR>" ;
print "<FORM METHOD=Post ACTION=IMEJ0005.cgi?$KEYWORD>" ;
foreach(keys (%in)){
	print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
}
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ITEM NO</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM_NO>$item_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>STOCK_SUBJECT_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_STOCK_SUBJECT>$SSC[$stock_subject_code]</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ITEM NAME</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM>$item<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM FLAG</TD>\n" ;
print "   <TD>$IMF[$item_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DESCRIPTION</TD>\n" ;
print "   <TD>$description<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>ORIGIN</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ORIGIN_CODE>$COU[$origin_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MAKER</TD>\n" ;
print "   <TD>$mak<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>CURRENCY</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_CURR_CODE>$CURR[$curr_code]<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>CLASS CODE</TD>\n" ;
print "   <TD>$CLAS[$class_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>EXTERNAL_UNIT_NUMBER</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_EUN>" . &commas("$external_unit_number") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>SAFETY_STOCK</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_SAF>" . &commas("$safety_stock") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>WEIGHT</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_WEI>" . &commas("$weight") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>UNIT of WEIGH</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_W>$UNW[$uom_w]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>UNIT of MEASUREMENT</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_L>$UNL[$uom_l]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3 ALIGN=Right><FONT COLOR=RED>UNIT STOCK RATE:UNIT ENGINEER RATE</FONT></TD>\n" ;
print "   <TD COLSPAN=3 ALIGN=Center BGCOLOR=$BG_UNIT>$unit_stock_rate $UNQ[$unit_stock] ： $unit_engineer_rate $UNQ[$unit_engineering]</TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>CATALOG NO</TD>\n" ;
print "   <TD>$catalog_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM CODE</TD>\n" ;
print "   <TD>$item_code<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>SUPPLIERS_PRICE</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_SUP>" . &commas("$suppliers_price") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DRAWING NO</TD>\n" ;
print "   <TD>$drawing_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>DRAWING REV</TD>\n" ;
print "   <TD>$drawing_rev<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>APPLICABLE_MODEL</TD>\n" ;
print "   <TD>$applicable_model<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='16%'>LAST_TERM_PRICE</TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='16%'>$last_term_price<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED>STANDARD_PRICE</FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%' BGCOLOR=$BG_STP>$standard_price<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN='Right' WIDTH='17%'><FONT COLOR=RED>NEXT_TERM_PRICE</FONT></TD>\n" ;
print "   <TD ALIGN='Right' WIDTH='17%' BGCOLOR=$BG_NTP>$next_term_price<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MANUFACT LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_MAN>$manufact_leadtime Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>PURCHASE LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_PUR>$purchase_leadtime Days</TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ADJUSTMENT LEADTIME</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_ADJ>$adjustment_leadtime Days</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ISSUE POLICY</TD>\n" ;
print "   <TD>$ISS[$issue_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ISSUE LOT</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_ISS>$issue_lot<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MANUFACT FAIL RATE</TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_MFR>$manufact_fail_rate \%</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>SECTION_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SECTION>$SEC[$section_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>COST PROCESS CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PROCESS>$CPC[$cost_process_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right><FONT COLOR=RED>COST_SUBJECT_CODE</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SUBJECT>$CSC[$cost_subject_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><BR></TD>" ;
print "   <TD><BR></TD>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ORDER_POLICY</TD>\n" ;
print "   <TD>$ORP[$order_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>MAKER_FLAG</TD>\n" ;
print "   <TD>$MAK[$maker_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen ALIGN=Right>ITEM TYPE</TD>\n" ;
print "   <TD COLSPAN=2>$item_type1<BR></TD>\n" ;
print "   <TD COLSPAN=3>$item_type2<BR></TD>\n" ;
print " </TR>" ;
 # FIのみ、客先梱包単位を入力できるようにする
 if ($CONF{'FI_PACKAGE_UNIT'} eq "1") {
     print " <TR>" ;
     print "   <TD BGCOLOR=LightGreen ALIGN='Right'>PACKAGE UNIT NUMBER<BR></TD>\n" ;
     print "   <TD BGCOLOR=$BG_PUN COLSPAN=5>$package_unit_number $UNQ[$unit_package]<BR></TD>\n" ;
     print " </TR>" ;
 }
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;

print "<P>\n";
if($ERR_MSG eq ""){print "<input type=submit name=submit value= $TYPES >\n"; }
print<<ENDOFTEXT;
<input type=button value='BACK' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/usd_pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT


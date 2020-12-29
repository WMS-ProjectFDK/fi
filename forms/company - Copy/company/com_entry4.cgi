#!/usr/local/bin/perl
#
# Modify
#   2006/06/19   HTML タグの抜けを修正 (HTML,TITLE)
#   2006/06/19   入力項目追加 (COMPANY_SHORT_NAME,TAXPAYER_No) …… FIの税務署向けの資料に表示が必要な為
#   2006/06/21   入力項目追加 (E MAIL) FDKランカ依頼
#   2014/06/14   ACCPACカンバニーコード
#   2014/10/07   BONDED_TYPEの追加#
#

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
    #
    # ダブりチェック
    #
    $sql = "select count(*) from company where company_code ='$company_code' ";
    $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
    $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
    ($COUNT) = $cur->fetchrow() ;
    $cur->finish;
    if($COUNT >0){ &err("THIS CODE IS ALREADY USED.") ;}
}

if($TYPES eq "DELETE" or $TYPES eq "RESTORE"){
   $sql  = "select ";
   $sql .= " com.upto_date     ,";
   $sql .= " com.reg_date      ,";
   $sql .= " com.delete_type   ,";
   $sql .= " com.company_code  ,";
   $sql .= " com.company_type  ,";
   $sql .= " com.company       ,";
   $sql .= " com.address1      ,";
   $sql .= " com.address2      ,";
   $sql .= " com.address3      ,";
   $sql .= " com.address4      ,";
   $sql .= " com.attn          ,";
   $sql .= " com.tel_no        ,";
   $sql .= " com.fax_no        ,";
   $sql .= " com.zip_code      ,";
   $sql .= " com.country_code  ,";
   $sql .= " cou.country  ,";
   $sql .= " com.curr_code     ,";
   $sql .= " com.tterm         ,";
   $sql .= " com.pdays         ,";
   $sql .= " com.pdesc         ,";
   $sql .= " com.case_mark     ,";
   $sql .= " com.edi_code      ,";
   $sql .= " com.vat           ,";
   $sql .= " com.supply_type   ,";
   $sql .= " com.transport_days,";
   $sql .= " com.company_short ,";
   $sql .= " com.taxpayer_no   ,";
   $sql .= " com.e_mail        ,";
   $sql .= " com.accpac_company_code        ,"; 
   $sql .= " cou.country,";
   $sql .= " bnd.type_description,";
   chop($sql);
   $sql .= " from company com,country cou,bonded_type bnd ";
   $sql .= " where com.country_code = cou.country_code(+) ";
   $sql .= "  and com.company_code = '$company_code' " ;
   $sql .= "  and com.bonded_type =  bnd.bonded_type(+) ";
       
   print $sql ;
   $cur = $dbh->prepare($sql) || &err("COM err $DBI::err .... $DBI::errstr") ;
   $cur->execute() || &err("COM err $DBI::err .... $DBI::errstr") ;
   @datas = $cur->fetchrow() ;
         for($j=0;$j<@datas;$j++){
            $cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
            ${$cur->{NAME}->[$j]} = $datas[$j] ;
         }
}

#
# company type
#
$sql = "select type_description from company_type where company_type ='$company_type' ";
$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
($type_description) = $cur->fetchrow() ;
$cur->finish;

#
# country
#
$sql = "select country from country where country_code ='$country_code' ";
$cur = $dbh->prepare($sql) || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
($country) = $cur->fetchrow() ;
$cur->finish;

#
# CURRENCY
#
$sql = "select curr_mark from currency where curr_code ='$curr_code' ";
$cur = $dbh->prepare($sql) || &err("CURRENCY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("CURRENCY err $DBI::err .... $DBI::errstr") ;
($curr_mark) = $cur->fetchrow() ;
$cur->finish;


#
# BONDED_TYPE
#
$sql = "select type_description from bonded_type  where bonded_type ='$bonded_type' ";
$cur = $dbh->prepare($sql) || &err("BONDED_TYPE err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("BONDED_TYPE err $DBI::err .... $DBI::errstr") ;
($bnd_desc) = $cur->fetchrow() ;
$cur->finish;

#
# ERROR CHECK
#
  $BG_COM_CODE ="White" ;
  $BG_COM_NAME ="White" ;
  $BG_COM_TYPE ="White" ;
  $BG_COM_ADDR ="White" ;
  $BG_COM_COU ="White" ;
  $BG_COM_CURR ="White" ;
  $BG_COM_TTERM ="White" ;
  $BG_COM_PAY ="White" ;
  $BG_COM_ACC ="White" ;
  $BG_BND_TYPE ="White" ;

 if($TYPES ne "DELETE"){
    if($company_code eq ""){ $BG_COM_CODE ="Pink" ;}
    if($company eq ""){ $BG_COM_NAME ="Pink" ;}
    if($company_type eq ""){ $BG_COM_TYPE ="Pink" ;}
    if($address1 eq ""){ $BG_COM_ADDR ="Pink" ;}
    if($country_code eq ""){ $BG_COM_COU ="Pink" ;}
    if($accpac_company_code eq ""){
      if($company_type eq 1 or $company_type eq 2 or $company_type eq 3){
         $BG_COM_ACC ="Pink" ;
      }
    }
    if($country_code eq "118"){
      if($bonded_type eq "A"){
         $BG_BND_TYPE ="Pink" ;
      }
    }
    if($country_code ne "118"){
      if($bonded_type eq "B" or $bonded_type eq "C"){
         $BG_BND_TYPE ="Pink" ;
      }
    }
   
   if($TYPES eq "NEW"){
    if($curr_code eq ""){ $BG_COM_CURR ="Pink" ;}
#    if($tterm eq ""){ $BG_COM_TTERM ="Pink" ;}
#    if($pdays eq "" || $pdays =~  /[^0-9]/){ $BG_COM_PAY ="Pink" ;}
#    if($pdesc eq ""){ $BG_COM_PAY ="Pink" ;}
   }
}
#-------------------------
# HTML
#-------------------------
 &html_header("COMPANY MAINTENANCE");
 &title();
 print "COMPANY MAINTENANCE<BR>\n" ;
 #print "COMPANY MAINTENANCE</H2>" ;
 #print "<HR>" ;
 print "<FORM METHOD=Post ACTION=com_entry5.cgi?$KEYWORD>" ;
 foreach(keys (%in)){
    print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
 }
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>CODE</TD>" ;
 print "   <TD COLSPAN=3 BGCOLOR=$BG_COM_CODE>$company_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>NAME</TD>" ;
 print "   <TD COLSPAN=3 BGCOLOR=$BG_COM_NAME>$company<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>TYPE</TD>" ;
 print "   <TD COLSPAN=3 BGCOLOR=$BG_COM_TYPE>$type_description<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>ADDRESS</TD>" ;
 print "   <TD COLSPAN=3  BGCOLOR=$BG_COM_ADDR>" ;
 print "     $address1<BR>" ;
 print "     $address2<BR>" ;
 print "     $address3<BR>" ;
 print "     $address4<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>TEL</TD>" ;
 print "   <TD>$tel_no<BR></TD>" ;
 print "   <TD BGCOLOR=LightGreen>FAX</TD>" ;
 print "   <TD>$fax_no<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>ATTN</TD>" ;
 print "   <TD>$attn<BR></TD>" ;
 print "   <TD BGCOLOR=LightGreen>ZIP CODE</TD>" ;
 print "   <TD>$zip_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>COUNTRY</TD>" ;
 print "   <TD  BGCOLOR=$BG_COM_COU>$country<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>EDI CODE</TD>" ;
 print "   <TD COLSPAN=3>$edi_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>SUPPLY TYPE</TD>" ;
 print "   <TD COLSPAN=3>$supply_type<BR></TD>" ;
 print " </TR>" ;
 print "   <TD BGCOLOR=LightGreen>DAYS OF TRANSPORT</TD>" ;
    $transport_days = $transport_days * 1 ;
 print "   <TD COLSPAN=3>$transport_days DAYS<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>BONDED TYPE</TD>" ;
 print "   <TD  BGCOLOR=$BG_BND_TYPE>$bnd_desc<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " </TABLE>" ;

if($TYPES eq "NEW"){
 print "<P>" ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>CURRENCY</TD>\n" ;
 print " <TD COLSPAN=3 BGCOLOR=$BG_COM_CURR>$curr_mark<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>PAYMENT</TD>\n" ;
 print " <TD COLSPAN=3 BGCOLOR=$BG_COM_PAY>\n" ;
 print "  $pdays\n" ;
 print "  $pdesc\n" ;
 print " <BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TRADE TERMS</TD>\n" ;
 print " <TD COLSPAN=3  BGCOLOR=$BG_COM_TTERM>$tterm<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>LOADING PORT</TD>\n" ;
 print " <TD>\n" ;
 print "  $loading_port<BR>\n" ;
 print " </TD>\n" ;
 print " <TD BGCOLOR=LightGreen>DISCHARGE PORT</TD>\n" ;
 print " <TD>\n" ;
 print "  $discharge_port<BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>FINAL DESTINATION</TD>\n" ;
 print " <TD COLSPAN=3>\n" ;
 print "  $final_dest<BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD VALIGN=Top  BGCOLOR=LightGreen>SHIPPING MARK</TD>\n" ;
 print " <TD COLSPAN=3><PRE>$marks</PRE><BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "</TABLE>\n" ;
} 

# 2006/06/19 入力項目追加 
 print "<P>" ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>COMPANY SHORT NAME</TD>\n" ;
 print " <TD>$company_short<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TAXPAYER No.</TD>\n" ;
 print " <TD>$taxpayer_no<BR></TD>\n" ;
 print "</TR>\n" ;
# 2006/06/21 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>E MAIL</TD>\n" ;
 print " <TD>$e_mail<BR></TD>\n" ;
 print "</TR>\n" ;
# 2014/06/14 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>ACCPAC COMPANY CODE</TD>\n" ;
 print " <TD BGCOLOR=$BG_COM_ACC>$accpac_company_code<BR></TD>\n" ;
 print "</TR>\n" ;
if($TYPES ne "NEW"){
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>PAYMENT</TD>\n" ;
 print " <TD COLSPAN=3 BGCOLOR=$BG_COM_PAY>\n" ;
 print "  $pdays\n" ;
 print "  $pdesc\n" ;
 print " <BR></TD>\n" ;
 print "</TR>\n" ;
}
 print "</TABLE>\n" ;
 print "<P>\n" ;
 if($accpac_company_code ne ""){
    print "<input type=submit name=submit value='CONFIRM'>\n" ;
 }
print<<ENDOFTEXT;
<input type=button value='Back' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT


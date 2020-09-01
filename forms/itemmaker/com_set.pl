#
# Programed by T.Ohsugi 
# 2000/07/04 

#-------------------------
#--- Require init file ---
#-------------------------
require '/pglosas/init.pl' ;

$KEYWORD = $in{'KEYWORD'} ;
$form_name = $in{'form_name'} ;
$company_type = $in{'company_type'} ;
$word = $in{'word'} ;
$word =~ tr/[a-z]/[A-Z]/ ;

#-------------------------
# SQL
#-------------------------
$dbh= &db_open();

 #COMPANY TYPE
  $sql  = " select " ;
  $sql .= "  company_type," ;
  $sql .= "  type_description " ;
  $sql .= " from company_type " ;
  $sql .= " where company_type in (2,3) ";
  $sql .= " order by company_type " ;
  $cur = $dbh->prepare($sql) || &err("MF err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("MF err $DBI::err .... $DBI::errstr") ;
  while(@datas = $cur->fetchrow){
     $TYPE{$datas[0]} = $datas[1] ;
  }

 #COMPANY
  $sql  = " select distinct" ;
  $sql .= "  decode(c.company_type,2,3,c.company_type) company_type," ;
  $sql .= "  c.company_code," ;
  $sql .= "  c.company " ;
  $sql .= " from company c" ;
  $sql .= " where c.delete_type is null  " ;
  $sql .= "   and (   upper(c.company)      like '%$word%' " ;
  $sql .= "        or c.company_type          = '$word' " if($word !~ /[^0-9]/);
  $sql .= "        )" ;
  $sql .= "   and  decode(c.company_type,2,3,c.company_type) ='$company_type' " if($company_type ne "") ;
  $sql .= "   and company_type in (2,3) ";
  $sql .= " order by company_type,c.company,c.company_code " ;

  #print $sql;

  $cur = $dbh->prepare($sql) || &err("ITEM err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("ITEM err $DBI::err .... $DBI::errstr") ;
  $ITM_NUM =0 ;
  while(@datas = $cur->fetchrow){
    for($i=0;$i<scalar(@datas);$i++){
       $cur->{NAME}->[$i] =~ tr/[A-Z]/[a-z]/ ;
       $ITM{$cur->{NAME}->[$i] . $ITM_NUM} = $datas[$i] ;
    }
     $ITM_NUM ++ ;
  }
  $count = $DBI::rows ;
  $cur->finish ;

#-------------------------
# HTML
#-------------------------
&html_header("company select");

print "<SCRIPT LANGUAGE=Javascript>\n" ;
print "  self.focus() ;\n" ;
print "  function setData(itm_num){ ;\n" ;
   $opener = "opener.document.inputform" ;
   $self   = "document.myform" ;
   print "    $opener.station_code.value = $self\['company_code' + itm_num].value ;\n" ;
   print "    $opener.company.value = $self\['company' + itm_num].value ;\n" ;
   print "    self.close() ;\n" ;
print "  }\n" ;
print "</SCRIPT>\n" ;

print "<FORM METHOD=post NAME=myform>\n" ;
print "SEARCH <INPUT TYPE=Text NAME=word VALUE='$word'>" ;
print "<FONT COLOR=Gray>(COMPANY CODE,NAME)</FONT><BR>" ;
print "<INPUT TYPE=hidden NAME=KEYWORD VALUE='$KEYWORD'>\n" ;
print "<INPUT TYPE=hidden NAME=form_name VALUE='$form_name'>\n" ;
print "<SCRIPT LANGUAGE=javascript>document.myform.word.focus() ;</SCRIPT>\n" ;
print "TYPE <SELECT NAME=company_type>\n" ;
 print "  <OPTION VALUE=''>" ;
 foreach(sort keys (%TYPE)){
   print "  <OPTION VALUE='$_'>$TYPE{$_}" ;
   $SSS++ ;
   if($SSS >20){exit ;}
 }
print "</SELECT><BR>\n" ;
print "<INPUT TYPE=Submit VALUE='Search'><P>\n" ;

if($count >0 && $count<=300){
  print "$count records hit! Now loading...<P>\n" ;
}elsif($count > 300){
  print "Too many COMPANY (COUNT: $count). select search words. EX. CODE or NAME.  " ;
  exit ;
}else{
  print "Not found.<P>\n" ;
  print "<FORM><INPUT TYPE=Button VALUE='CLOSE' onClick='self.close()'></FORM>\n" ;
  exit ;
}

print "<TABLE BORDER=1 CELLPADDING=1 CELLSPACING=0>" ;
print "<TR BGCOLOR=powderblue>" ;
print "<TH>CODE</TH>" ;
print "<TH>DESCRIPTITON</TH>" ;
print "</TR>\n" ;

 for($i=0;$i<$ITM_NUM;$i++){
   if($ITM{'company_type'.($i-1)} ne $ITM{'company_type'.$i}){
      print "<TR BGCOLOR=#DDDDDD><TD COLSPAN=2>$TYPE{$ITM{'company_type'.$i}}</TD>" ;
   }
   print "<TR BGCOLOR=White VALIGN=Top>" ;
      print "<TD nowrap><A HREF=javascript:setData($i)>$ITM{'company_code'.$i}</A><BR></TD>" ;
      print "<TD nowrap>$ITM{'company'.$i}<BR></TD>" ;
   print "<INPUT TYPE=Hidden NAME=company_code$i VALUE='$ITM{'company_code'.$i}'>\n" ;
   print "<INPUT TYPE=Hidden NAME=company$i VALUE='$ITM{'company'.$i}'>\n" ;
   print "</TR>\n" ;
 }
print "</TABLE>\n" ;
print "<FORM><INPUT TYPE=Button VALUE='CLOSE' onClick='self.close()'></FORM>\n" ;
print "</FORM>\n" ;
print "</BODY>\n" ;
print "</HTML>\n" ;



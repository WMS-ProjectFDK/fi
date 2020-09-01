#
# Programed by T.Ohsugi 
# 2000/07/04 

#-------------------------
#--- Require init file ---
#-------------------------
require '/pglosas/init.pl' ;

$KEYWORD = $in{'KEYWORD'} ;
$nos = $in{'nos'} ;

$word = $in{'word'} ;
$word =~ tr/[a-z]/[A-Z]/ ;
$word =~ tr/'/''/ ;



#-------------------------
# SQL
#-------------------------
$dbh= &db_open();

 #item
  $sql  = " select " ;
  $sql .= "  i.delete_type," ;
  $sql .= "  i.item_no," ;
  $sql .= "  i.item," ;
  $sql .= "  i.description," ;
  $sql .= "  i.class_code," ;
  $sql .= "  i.origin_code," ;
  $sql .= "  cou.country origin," ;
  $sql .= "  s.stock_subject stock_subject," ;
  $sql .= "  u.unit " ;
  $sql .= " from item i," ;
  $sql .= "      stock_subject s," ;
  $sql .= "      unit u," ;
  $sql .= "      country cou " ;
  $sql .= " where i.origin_code = cou.country_code(+) "  ;
  $sql .= "   and i.stock_subject_code = s.stock_subject_code(+) "  ;
  $sql .= "   and i.uom_q = u.unit_code(+) "  ;
  $sql .= "   and i.delete_type is null"  ;
  $sql .= "   and (   upper(i.item) like '%$word%' " ;
  $sql .= "        or upper(i.description) like '%$word%' " ;
  $sql .= "        or cou.country like '%$word%' " ;
  $sql .= "        or i.item_no = '$word' " if($word !~ /[^0-9]/);
  $sql .= "       ) " ;
  $sql .= "   and s.stock_subject_code in (1,2,4,7) " ; #•”Þ‚©”¼»•i‚Ì‚Ý•\Ž¦
  $sql .= " order by i.description " ;
  $cur = $dbh->prepare($sql) || &err("CUSTOMER err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("COMPANY err $DBI::err .... $DBI::errstr") ;

  for($i=0;$i<$cur->{NUM_OF_FIELDS};$i++){
     $field[$i] = $cur->{NAME}->[$i] ;
  }

  $ITM_NUM =0 ;
  while(@datas = $cur->fetchrow){
    for($i=0;$i<scalar(@datas);$i++){
       $ITM{$cur->{NAME}->[$i] . $ITM_NUM} = $datas[$i] ;
    }
     $ITM_NUM ++ ;
  }

#-------------------------
# HTML
#-------------------------
&html_header("parts select");

print "<SCRIPT LANGUAGE=Javascript>\n" ;
print "  self.focus() ;\n" ;
print "  function setData(itm_num){ ;\n" ;
   $opener = "opener.document.inputform" ;
   $self   = "document.myform" ;
print "    $opener.edpkey.value = $self\['ITEM_NO' + itm_num].value ;\n" ;
#print "    opener.SEARCH_ITEM() ; \n" ;
print "    self.close() \n" ;
print "  }\n" ;
print "</SCRIPT>\n" ;

print "<FORM METHOD=post NAME=myform>\n" ;
print "SEARCH WORD <INPUT TYPE=Text NAME=word VALUE='$word'>" ;
print "<INPUT TYPE=hidden NAME=KEYWORD VALUE='$KEYWORD'>\n" ;
print "<SCRIPT LANGUAGE=javascript>document.myform.word.focus() ;</SCRIPT>\n" ;
print "<INPUT TYPE=hidden NAME=nos VALUE='$nos'>\n" ;
print "<INPUT TYPE=Submit VALUE='Search'>" ;
print "<FONT COLOR=Gray>(PART NO,DESCRIPTION or COUNTRY)</FONT>" ;
print "<BR>\n" ;
if($word eq ""){ 
  print "<FORM><INPUT TYPE=Button VALUE='CLOSE' onClick='self.close()'></FORM>\n" ;
  exit ;
}
if($ITM_NUM >300){print "$ITM_NUM records hit. Please input another search word" ; exit ;}
print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0 WIDTH=100%>" ;
print "<TR BGCOLOR=powderblue>" ;
print "<TH>CODE</TH>" ;
print "<TH>NAME</TH>" ;
print "<TH>DESCRIPTITON</TH>" ;
print "<TH>UOM</TH>" ;
print "<TH>ORIGN</TH>" ;
print "<TH>STOCK_SUBJECT</TH>" ;
print "</TR>\n" ;

 for($i=0;$i<$ITM_NUM;$i++){
    foreach(@field){
       $$_ = $ITM{$_ . $i} ;
       $$_ =~ s/$word/\<B\>$word\<\/B\>/g ;
   }
   if($OLD_ITEM eq ""){ $BGC="lightyellow" ;}else{  $BGC="Silver" ;}
   print "<TR BGCOLOR=$BGC>" ;
   print "<TD><A HREF='javascript:setData($i)'>$ITEM_NO</A><BR></TD>" ;
   print "<TD NOWRAP>$ITEM<BR></TD>" ;
   print "<TD NOWRAP>$DESCRIPTION<BR></TD>" ;
   print "<TD>$UNIT<BR></TD>" ;
   print "<TD>$ORIGIN<BR></TD>" ;
   print "<TD>$STOCK_SUBJECT<BR></TD>" ;
   print "<INPUT TYPE=Hidden NAME=ITEM_NO$i VALUE='$ITM{'ITEM_NO'.$i}'>\n" ;
   print "<INPUT TYPE=Hidden NAME=ITEM$i VALUE='$ITM{'ITEM'.$i}'>\n" ;
   print "<INPUT TYPE=Hidden NAME=DESCRIPTION$i VALUE='$ITM{'DESCRIPTION'.$i}'>\n" ;
   print "<INPUT TYPE=Hidden NAME=ORIGIN_CODE$i VALUE='$ITM{'ORIGIN_CODE'.$i}'>\n" ;
   print "<INPUT TYPE=Hidden NAME=ORIGIN$i VALUE='$ITM{'ORIGIN'.$i}'>\n" ;
   print "</TR>\n" ;
 }
print "</TABLE>\n" ;
print "<FORM><INPUT TYPE=Button VALUE='CLOSE' onClick='self.close()'></FORM>\n" ;
print "</FORM>\n" ;
print "</BODY>\n" ;
print "</HTML>\n" ;



#
# Programed 
# 2002/01/10
#

#-------------------------
#    Require init file 
#-------------------------
require '/pglosas/init.pl' ;

$KEYWORD = $in{'KEYWORD'} ;
$f_name = $in{'f_name'} ;
$f_code = $in{'f_code'} ;
$word = $in{'word'} ;

#-------------------------
# SQL
#-------------------------
$dbh= &db_open();

 #
 # PORT
 #
  $sql  = " select distinct " ;
  $sql .= "  port_code, " ;
  $sql .= "  port  " ;
  $sql .= " from port " ;
  $sql .= " where upper(port) like upper('%$word%')" if($word ne "");
  $sql .= " order by port" ;
  $cur = $dbh->prepare($sql) || &err("SUPPLIER err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("SUPPLIER err $DBI::err .... $DBI::errstr") ;
#  print $sql ;
  $i = 1 ;
  while(@datas = $cur->fetchrow){
     for ($j=0 ; $j<scalar(@datas) ; $j++){
         $datas[$j] =~ s/\"/&quot;/g ;
         ${$cur->{NAME}->[$j] .$i} = $datas[$j] ;
     }
     $i ++ ;
  }
  $count = $DBI::rows + 1 ;      

#-------------------------
# HTML
#-------------------------
&html_header("PORT SELECT");
&title() ;

$opener = "opener.document.myform" ;
$self   = "document.myform" ;
   
print "<SCRIPT LANGUAGE=Javascript>\n" ;
print "  self.focus() ;\n" ;
print "  function setData(itm_num){ ;\n" ;
print "          $opener.$f_name.value    = $self\['PORT'     + itm_num].value ;\n" ;
print "          $opener.$f_code.value    = $self\['PORT_CODE'+ itm_num].value ;\n" ;
print "          self.close() \n" ;
print "  }\n" ;
print "</SCRIPT>\n" ;

print "<FORM METHOD=post NAME=myform>\n" ;
print "SEARCH WORD<INPUT TYPE=Text NAME=word VALUE='$word'><INPUT TYPE=Submit VALUE='SEARCH'>\n" ;
print "<INPUT TYPE=Hidden NAME=f_name VALUE='$f_name'>" ;
print "<INPUT TYPE=Hidden NAME=f_code VALUE='$f_code'>" ;



print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0 WIDTH=100%>" ;
print "<TR BGCOLOR=powderblue>" ;
print "   <TH>CODE</TH>" ;
print "   <TH>NAME</TH>" ;
print "</TR>\n" ;
print "<TR BGCOLOR=LightYellow>" ;
print "   <TD ALIGN=Right><A HREF='javascript:setData(0)'>BLANK</A></TD>" ;
print "   <TD NOWRAP><BR></TD>" ;
print "   <INPUT TYPE=Hidden NAME=PORT_CODE0 VALUE=\"\">\n" ;
print "   <INPUT TYPE=Hidden NAME=PORT0      VALUE=\"\">\n" ;
print "</TR>\n" ;
for ($i=1;$i<$count;$i++) { 
  print "<TR BGCOLOR=LightYellow>" ;
    print "   <TD Align='Right' NOWRAP><A HREF='javascript:setData($i)'>${'PORT_CODE'.$i}</A></TD>" ;
    print "   <TD NOWRAP>${'PORT'.$i}" ;
    print "   <INPUT TYPE=Hidden NAME=PORT_CODE$i VALUE=\"${'PORT_CODE'  .$i}\">\n" ;
    print "   <INPUT TYPE=Hidden NAME=PORT$i      VALUE=\"${'PORT' .$i}\">\n" ;
    print " </TD>" ;
  print "</TR>\n" ;
}
print "</TABLE>\n" ;
print "<HR>\n" ;
print "<INPUT TYPE=Button VALUE='CLOSE' onClick='self.close()'>\n" ;
print "</FORM>\n" ;
&html_fooder() ;

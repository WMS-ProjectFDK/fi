# m_company.pl
require '/pglosas/init.pl';


#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;


 #COMPANY TYPE
  $sql  = " select " ;
  $sql .= "  c.company_type," ;
  $sql .= "  c.type_description" ;
  $sql .= " from company_type c " ;
 # $sql .= " where c.company_type != 0 " ;
  $sql .= " order by c.company_type " ;

  $cur = $dbh->prepare($sql) || &err("CUSTOMER err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("CUSOTMER err $DBI::err .... $DBI::errstr") ;
  $TYP_NUM =0 ;
  while(@datas = $cur->fetchrow){
    $TYP[$TYP_NUM][0] = $datas[0] ;
    $TYP[$TYP_NUM][1] = $datas[1] ;
     $TYP_NUM ++ ;
  }
  $cur->finish();

#-------------------------
# HTML
#-------------------------
&html_header("COMPANY MAINTENANCE");
&title();

print<<ENDOFTEXT;
<FONT SIZE=4>COMPANY MAINTENANCE</font>
<FORM METHOD=Post ACTION=com_entry2.cgi?$KEYWORD>
<OL>
<LI><A HREF=com_entry3.cgi?$KEYWORD>NEW COMPANY ENTRY</A><P>
<LI><A HREF=com_entry3_self.cgi?$KEYWORD>NEW COMPANY ENTRYÅiType is Self Only !Åj</A><!--Å®<font color=red> Under construction !!</font>--><P>
<LI>CHANGE or DELETE<BR>
 <UL>
  <LI>Search Word:<input type=text name=key size=20> <FONT COLOR=Gray>(CODE or NAME etc.)</FONT>
  <LI>Type:
ENDOFTEXT

  for($i=0;$i<$TYP_NUM;$i++){
    if($i==0){ $CHKD= "CHECKED" ;}else{ $CHKD = "" ; }
    print "<INPUT TYPE=Radio NAME=company_type value=$TYP[$i][0] $CHKD>$TYP[$i][1]\n" ;
  }

print <<ENDOFTEXT ;
 </UL>
 <!--<font color=red> Self is Under construction !!</font>-->
<P>
<input type=submit name=submit value="Search">
<input type=reset name=submit value="Reset">
</OL>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

	#STOCK_SUBJECT
	$sql  = "select stock_subject_code,Stock_subject from stock_subject order by stock_subject_code                                      " ;
	$cur = $dbh->prepare($sql) || &err("COMEJ0001_COMTYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("COMEJ0001_COMTYPE err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM =0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$SSC_NUM][0] = $datas[0] ;
		$SSC[$SSC_NUM][1] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish();

#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0001');
print<<ENDOFTEXT;
<FORM METHOD=Post ACTION=IMEJ0002.cgi?$KEYWORD>
<OL>
<TABLE BORDER="0" CELLPADDING="2" CELLSPACING="0"><TBODY>
	<TR><TD colspan="4">1:<A HREF=IMEJ0003.cgi?$KEYWORD><FONT SIZE = +1>New Item Entry</FONT></A><P></TD></TR>
	<TR>
		<TD colspan="4">2:<FONT SIZE = +1>Item Change or Delete</FONT></TD>
	</TR><TR>
		<TD WIDTH="10"><BR></TD>
		<TD ALIGN='Right'>ITEM SEARCH :</TD>
		<TD colspan="2"> <input type=text name=key_word size=20> <FONT COLOR=Gray>(example:ITEM_NO,ITEM_NAME, DESCRIPTION)</FONT></TD>
	</TR>
	<TR>
		<TD WIDTH="10"><BR></TD>
		<TD ALIGN='Right'>STOCK SUBJECTE :</TD>
		<TD colspan="2">
ENDOFTEXT

for($i=0;$i<$SSC_NUM;$i++){
	if($i==0){ $CHKD= "CHECKED" ;}else{ $CHKD = "" ; }
	if($i==5 or $i==10){
		print "<INPUT TYPE=Radio NAME=stock_subject_code value=$SSC[$i][0] $CHKD>$SSC[$i][1]</TD>\n" ;
		print "</TR><TR><TD colspan='2'><BR></TD><TD colspan='2'>";
	}else{
		print "<INPUT TYPE=Radio NAME=stock_subject_code value=$SSC[$i][0] $CHKD>$SSC[$i][1]\n" ;
	}
}

print <<ENDOFTEXT ;
</TD>
    </TR>
  </TBODY>
</TABLE>
<P>
<input type=submit name=submit value="SESRCH">
<input type=reset name=submit value="RESET">
</OL>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>BACK</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
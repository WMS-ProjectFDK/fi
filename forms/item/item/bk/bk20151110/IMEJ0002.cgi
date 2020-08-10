#
# 2015/04/21 NTTk)Hino  ���ڒǉ��iCAPACITY�� �v28���ځj
#
#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
$stock_subject_code = $in{'stock_subject_code'} ;
$key_word           = $in{'key_word'} ;

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

	#stock_subject_code
	$sql  = "select stock_subject from stock_subject where stock_subject_code = $stock_subject_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0002_SSC err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0002_SSC err $DBI::err .... $DBI::errstr") ;
	($stock_subject) = $cur->fetchrow ;

	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s where 1 = 1 " ;
	$sql .= " and ( i.item_no like '%$key_word%' or upper(i.item) like '%$key_word%' or upper(i.description) like '%$key_word%' or upper(i.mak) like '%$key_word%')" if($key_word ne "") ;
	$sql .= " and i.stock_subject_code = s.stock_subject_code(+) ";
	$sql .= " and i.stock_subject_code = $stock_subject_code     ";
	$sql .= " order by item ";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0002_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0002_MST err $DBI::err .... $DBI::errstr") ;

#-------------------------
# HTML
#-------------------------
&html_header('ITEM MAINTENANCE');
&title('ITEM MAINTENANCE','IMEJ0002');

print <<ENDOFTEXT ;
<SCRIPT LANGUAGE=javascript>
    // BACK�{�^���������� (add 2015/04/21)
    function go_back(){
	    document.forms[0].method = "post";
	    document.forms[0].action = "IMEJ0001.cgi?$KEYWORD";
	    document.forms[0].submit();
    }
</SCRIPT>
ENDOFTEXT

print "<FONT SIZE='+1' COLOR='blue'>SEARCH WORD [<B> $key_word </B>] STOCK_SUBJECT_CODE [<B> $stock_subject </B>]</FONT><P>";
print "<IMG SRC=$IMG_DIR/blueball.gif> When it is not found with a list, please change a search key word.<BR>";
print "<IMG SRC=$IMG_DIR/blueball.gif> The data that a \"UNDELETION\" button is displayed are eliminated data.<BR><BR>";

# add 2015/04/21 Start
print "<FORM METHOD=post NAME=myForm>\n";
print "<INPUT TYPE=\"Hidden\" name=\"key_word\" VALUE=\"$key_word\">\n";
print "<INPUT TYPE=\"Hidden\" name=\"stock_subject_code\" VALUE=\"$stock_subject_code\">\n";
print "<INPUT TYPE=\"Hidden\" name=\"item_no\">\n";
# add 2015/04/21 End

print<<ENDOFTEXT;
<TABLE border=1 CELLPADDING=1 CELLSPACING=0>
<TR>
<TH bgcolor="LightGreen">ITEM NO</TH>
<TH bgcolor="LightGreen">ITEM NAME</TH>
<TH bgcolor="LightGreen">DESCRIPTION</TH>
<TH bgcolor="LightGreen">MAKER</TH>
<TH bgcolor="LightGreen"><br></TH>
</tr>
ENDOFTEXT

undef @item;
while((@datas) = $cur->fetchrow){
      for($j=0;$j<@datas;$j++){
         $ref->{$cur->{NAME}->[$j]} = $datas[$j] ;
         print 
      }
	if($ref->{DELETE_TYPE} eq ""){
	   $BGC="#FFFFFF" ;
	}else{
	   $BGC="#DDDDDD" ;
	}
	print "<TR BGCOLOR=$BGC>\n";
		print "<TD>$ref->{ITEM_NO}<BR></TD>\n";
		print "<TD>$ref->{ITEM}<BR></TD>\n";
		print "<TD>$ref->{DESCRIPTION}<BR></TD>\n";
		print "<TD>$ref->{MAK}<BR></TD>\n";
		print "<TD>\n";
		# mod 2015/04/21 Start
		#print "<FORM METHOD=post NAME=myForm>\n";
		#print "<INPUT TYPE=\"Hidden\" name=\"item_no\" VALUE=\"$ref->{ITEM_NO}\">\n";
		#if($ref->{DELETE_TYPE} eq ""){
		#	print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"CHANGE\" onClick='this.form.action=\"IMEJ0003.cgi?$KEYWORD\"'>\n";
		#	print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"DELETE\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\"'>\n";
		#}else{
		#	print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"UNDELETION\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\"'>\n";
		#}
		
		$p_item_no = $ref->{ITEM_NO} ;
		
		if($ref->{DELETE_TYPE} eq ""){
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"CHANGE\" onClick='this.form.action=\"IMEJ0003.cgi?$KEYWORD\";this.form.item_no.value=\"$p_item_no\";'>\n";
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"DELETE\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\";this.form.item_no.value=\"$p_item_no\";'>\n";
		}else{
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"UNDELETION\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\"; this.form.item_no.value=\"$p_item_no\";'>\n";
		}
		# mod 2015/04/21 End
		
	print "</TR>\n";
	# print "</FORM>\n"; # del 2015/04/21
}
print<<ENDOFTEXT;
</table>
<HR>
ENDOFTEXT

# mod 2015/04/21 Start
#&html_footer("javascript:history.back()","BACK");
print<<ENDOFTEXT;
<BR>
[<A HREF=javascript:go_back()>BACK</A>]
</FORM>
</BODY></HTML>

ENDOFTEXT
# mod 2015/04/21 End

exit ;
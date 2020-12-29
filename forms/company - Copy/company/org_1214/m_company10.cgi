#m_company10.cgi
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';
&make_desc;

&html_header("Company Maintenance:Change Registration");
&title;

$submit = $in{'submit'};
if ($submit eq "No"){
 print<<ENDOFTEXT;
 	Delete was canceled.
  	<HR>
	[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
	</BODY>
	</HTML>
ENDOFTEXT
	exit;
}
$dbh = &db_open();

#
# Form Check
#
&err("Company Code is null") if ($in{'COMPANY_CODE'} eq "");

#
# select target
#
$sql  = " select company from company";
$sql .= " where company_code = $in{'COMPANY_CODE'} ";

&do_sql($sql);

($company) = $cur->fetchrow;

print<<ENDOFTEXT;
<H2>Company Maintenance:Delete</H2>
<HR>
<FORM METHOD=Post ACTION=m_company7.cgi?$KEYWORD>
ENDOFTEXT

$sql = "update company set delete_type = 'D'";
$sql .= " where company_code = $in{'COMPANY_CODE'} ";

#print $sql ;
&do_sql($sql);

$sql  = "delete from contract where company_code = $in{'COMPANY_CODE'} ";
#print $sql ;

&do_sql($sql);

$dbh->commit;
$dbh->disconnect;

print<<ENDOFTEXT;
$company($in{'COMPANY_CODE'}) was deleted successfully.
<P>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
# m_company.pl
require '/pglosas/init.pl';
#require 'init.pl';

&html_header("Company Maintenance");
&title();

print<<ENDOFTEXT;
<FONT SIZE=4>Company Maintenance</font>
<FORM METHOD=Post ACTION=m_company2.cgi?$KEYWORD>
<OL>
<LI>Company Maintenance
<A HREF=m_company3.cgi?$KEYWORD>Create New Company</A><P>
<LI>Change or Delete
<input type=text name=key size=20>
</OL>
Company Types...
<input type=radio name=company_type value="1" Checked>Customer
<input type=radio name=company_type value="3">Supplier
<input type=radio name=company_type value="4">Warehouse
<P>
<input type=submit name=submit value="Search">
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
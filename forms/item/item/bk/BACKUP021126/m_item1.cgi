# m_item1.pl
require '/pglosas/init.pl';
#require 'init.pl';

&html_header("Parts Maintenance");
&title();

print<<ENDOFTEXT;
<FORM METHOD=Post ACTION=m_item2.cgi?$KEYWORD>
<OL>
<LI>Parts Maintenance
<A HREF=m_item3.cgi?$KEYWORD>Entry New Parts</A><P>
<LI>Change or Delete
<input type=text name=key size=20>
</OL>
<input type=submit name=submit value="Search">
<input type=button value='Back' onClick='javascript:history.back()'>
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
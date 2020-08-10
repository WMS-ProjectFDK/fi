# list_item1.cgi
require '/pglosas/init.pl';
#require 'init.pl';
require '/pglosas/master/item/item_desc.pl';

&html_header("Parts Master Listing");
&title();

print<<ENDOFTEXT;
<OL>
<LI>Input KEY WORD and press "Search and List" button.
<FORM METHOD=Post ACTION=list_item2.cgi?$KEYWORD>
<input type=text name=keyword size=20><br>
<input type=submit name=submit1 value="Search and List">
<input type=reset name=submit value="Reset">
<P>
<LI>Or List
<select name=class_code>
ENDOFTEXT

#
# db open
#
$dbh = &db_open;

$sql  = " select class_code,description";
$sql .= "  from class ";
$sql .= " order by class_code";

&do_sql($sql);

while(($class_code,$description) = $cur->fetchrow){

   print "<option value=$class_code>$description \n";

}
print<<ENDOFTEXT;
</select>
All.<br>
<input type=submit name=submit2 value="List">
<P>
<li>Or List ALL Parts.<br>
<input type=submit name=submit3 value="List">
</FORM>
</OL>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
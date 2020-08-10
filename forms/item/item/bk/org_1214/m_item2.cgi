# m_item2.pl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

#
# Check Formed Value
#
$key = $in{'key'};
&err("No key.") if ($key eq "");

&html_header("Parts Maintenance:Search Key = $key");
&title();
#
# DB Open
#
$dbh = &db_open();

#
# SQL
#
$sql  = "select ";
for $i (0 .. $#desc){
$sql .= " i.$desc[$i][0],";
}
$sql .= " c.class_1,";
$sql .= " c.class_2,";
$sql .= " c.class_3,";
$sql .= " c.description,";
chop($sql);
$sql .= " from item i,class c";
$sql .= " where (i.item_no like '%$key%' ";
$sql .= " or    i.description 	 like '%$key%') ";
$sql .= "and c.class_code = i.class_code ";

&do_sql($sql);

print<<ENDOFTEXT;
<TABLE border=1>
<tr>
<TH bgcolor="LightGreen">Code</TH>
<TH bgcolor="LightGreen">Group</TH>
<TH bgcolor="LightGreen">ORIGIN</TH>
<TH bgcolor="LightGreen">DESCRIPTION</TH>
<TH bgcolor="LightGreen"><br></TH>
</tr>
ENDOFTEXT

undef @item;
while((@datas) = $cur->fetchrow){

   $rec = {};

$i = 0;
      foreach $ref (@desc){
		$rec->{$$ref[0]} = $datas[$i];
		$i++;
	  }
	  $rec->{class_1} = $datas[$i]; $i++;
	  $rec->{class_2} = $datas[$i]; $i++;
	  $rec->{class_3} = $datas[$i]; $i++;
	  $rec->{class_desc} = $datas[$i]; $i++;

   push @item,$rec;
}

foreach $ref (@item){
	print "<tr>\n";
		print "<td>$ref->{ITEM_NO}</td>\n";
		print "<td>$ref->{class_1}-$ref->{class_2}-$ref->{class_3}($ref->{class_desc})</td>\n" ;
		print "<td>$ref->{ORIGIN_CODE}</td>\n";
		print "<td>$ref->{DESCRIPTION}</td>\n";
		print "<td>\n";
		print "<form method=post name=myForm>\n";
		print "<input type=\"hidden\" name=\"item_no\" value=\"$ref->{ITEM_NO}\">\n";
		print "<input type=\"hidden\" name=\"origin_code\" value=\"$ref->{ORIGIN_CODE}\">\n";
		print "	<input type=\"submit\" name=\"Change\" value=\"Change\" onClick='this.form.action=\"m_item6.cgi?$KEYWORD\"'>\n";
		print "	<input type=\"submit\" name=\"Delete\" value=\"Delete\" onClick='this.form.action=\"m_item9.cgi?$KEYWORD\"'>\n";
		print "</form>\n";
	print "</tr>\n";
}
print<<ENDOFTEXT;
</table>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to MainMenu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
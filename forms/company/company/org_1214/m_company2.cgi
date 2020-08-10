# m_company2.pl
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';
&make_desc;

#
# Check Formed Value
#
$key = $in{'key'};
&err("No key.") if ($key eq "");

$company_type = $in{'company_type'} ;
$company_type = 1 if ($company_type eq "");

#
# DB Open
#
$dbh = &db_open();

$sql  = "select description from company_type where type = $company_type";

&do_sql($sql);

undef @company_types;
($type_description) = $cur->fetchrow ;

&html_header("Company Maintenance:Search Key = $key ($type_description)");
&title();

#
# SQL
#
$sql  = "select ";
for $i (0 .. $#desc){
$sql .= " com.$desc[$i][0]," if ($desc[$i][4] eq "COMPANY" or $desc[$i][4] eq "");
}
$sql .= " cou.country,";
chop($sql);
$sql .= " from company com,country cou";
$sql .= " where (com.company_code like '%$key%' ";
$sql .= " or    com.company 	 like '%$key%')";
$sql .= "and com.delete_type is null ";
$sql .= "and cou.country_code = com.country_code ";
$sql .= "and com.company_type = $company_type ";
$sql .= "order by com.company ";

&do_sql($sql);

print<<ENDOFTEXT;
<FONT SIZE=4>Company Maintenance:Search Results. Search Key = $key (as $type_description)</FONT>
<TABLE border=1>
<tr>
<TH bgcolor="LightGreen">Code</TH>
<TH bgcolor="LightGreen">Type</TH>
<TH bgcolor="LightGreen">Name</TH>
<TH bgcolor="LightGreen">Country</TH>
<TH bgcolor="LightGreen"><br></TH>
</tr>
ENDOFTEXT

undef @company;
while((@datas) = $cur->fetchrow){

   $rec = {};

	$i = 0;
      foreach $ref (@desc){
       if ($$ref[4] eq "COMPANY" or $$ref[4] eq ""){
		$rec->{$$ref[0]} = $datas[$i];
		$i++;
	   }
	  }
	  $rec->{dist_country} = $datas[$i];

   push @company,$rec;
}

foreach $ref (@company){
	print "<tr>\n";
		print "<td>$ref->{COMPANY_CODE}</td>\n";
		foreach $key (@company_types){
			print "<td>$key->{description}</td>\n" if ($key->{type} eq $ref->{COMPANY_TYPE});
		}
		print "<td>$ref->{COMPANY}</td>\n";
		print "<td>$ref->{dist_country}</td>\n";
		print "<td>\n";
		print "<form method=post name=myForm>\n";
		print "<input type=\"hidden\" name=\"company_code\" value=\"$ref->{COMPANY_CODE}\">\n";
		print "	<input type=\"submit\" name=\"Change\" value=\"CHANGE\" onClick='this.form.action=\"m_company6.cgi?$KEYWORD\"'>\n";
		print "	<input type=\"submit\" name=\"Change Contract\" value=\"CHANGE CONTRACT\" onClick='this.form.action=\"/pglosas/master/contract/ct_entry1.cgi?$KEYWORD\"'>\n";
		print "	<input type=\"submit\" name=\"Delete\" value=\"DELETE\" onClick='this.form.action=\"m_company9.cgi?$KEYWORD\"'>\n";
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
sub make_desc{
@desc = ( ["DELETE_TYPE","X",1],
		  ["COMPANY_CODE","N",8],
		  ["COMPANY_TYPE","N",2],
		  ["COMPANY","X",40],
		  ["ADDRESS1","X",40],
		  ["ADDRESS2","X",40],
		  ["ADDRESS3","X",40],
		  ["ATTN","X",40],
		  ["COUNTRY_CODE","N",3],
	      ["CURR_CODE","X",3],
		  ["TTERM","X",20],
		  ["PDAYS","N",3],
		  ["PDESC","X",40],
		  ["CRLMT","N",3],
		  ["INIT","X",3],
		  ["COMPANY_CODE_OLD","X",8],
		  ["EDI_CODE","X",12],
		  ["UPTO_DATE","D",0],
		  ["REG_DATE","D",0]	);

}

exit ;
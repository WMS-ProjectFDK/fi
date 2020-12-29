# list_company2.pl
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';

&html_header("Company Master Listing");
&title();

#
# check formed value and make sql
#
if ($in{'submit1'} ne ""){

	$submit_type = "Search";
	if ($in{'keyword'} eq ""){

		&err("Keyword is not specified.");

	} else {

		$keyword = $in{'keyword'};
		$sql  = " select t.description,";
		$sql .= " 		 c.company,";
		$sql .= " 		 c.address1,";
		$sql .= " 		 c.address2,";
		$sql .= " 		 c.address3,";
		$sql .= " 		 c.attn,";
		$sql .= " 		 u.country,";
		$sql .= " 		 c.curr_code,";
		$sql .= " 		 c.tterm,";
		$sql .= " 		 c.pdays,";
		$sql .= " 		 c.pdesc,";
		$sql .= " 		 c.init,";
		chop($sql);
		$sql .= " from company_type t,";
		$sql .= "	   company c,";
		$sql .= "	   country u,";
		chop($sql);
		$sql .= " where c.delete_type is null ";
		$sql .= " and ";
		$sql .= "       (company like '%$keyword%'";
		$sql .= " 		or ";
		$sql .= "		 address1 like '%$keyword%'";
		$sql .= " 		or ";
		$sql .= "		 address2 like '%$keyword%'";
		$sql .= " 		or ";
		$sql .= "		 address3 like '%$keyword%'";
		$sql .= " 		or ";
		$sql .= "		 attn like '%$keyword%'";
		$sql .= "		) ";
		$sql .= " and   u.country_code = c.country_code ";
		$sql .= " and   t.type = c.company_type ";

	}

} elsif ($in{'submit2'} ne ""){

	$submit_type = "Select";
	if ($in{'company_type'} eq ""){

		&err("Company Type is not specified.");

	} else {

		$company_type = $in{'company_type'};
		$sql  = " select t.description,";
		$sql .= " 		 c.company,";
		$sql .= " 		 c.address1,";
		$sql .= " 		 c.address2,";
		$sql .= " 		 c.address3,";
		$sql .= " 		 c.attn,";
		$sql .= " 		 u.country,";
		$sql .= " 		 c.curr_code,";
		$sql .= " 		 c.tterm,";
		$sql .= " 		 c.pdays,";
		$sql .= " 		 c.pdesc,";
		$sql .= " 		 c.init,";
		chop($sql);
		$sql .= " from company_type t,";
		$sql .= "	   company c,";
		$sql .= "	   country u,";
		chop($sql);
		$sql .= " where c.delete_type is null ";
		$sql .= " and   c.company_type = $company_type ";
		$sql .= " and   u.country_code = c.country_code ";
		$sql .= " and   t.type = c.company_type ";

	}

} elsif ($in{'submit3'} ne ""){

	$submit_type = "All";
		$sql  = " select t.description,";
		$sql .= " 		 c.company,";
		$sql .= " 		 c.address1,";
		$sql .= " 		 c.address2,";
		$sql .= " 		 c.address3,";
		$sql .= " 		 c.attn,";
		$sql .= " 		 u.country,";
		$sql .= " 		 c.curr_code,";
		$sql .= " 		 c.tterm,";
		$sql .= " 		 c.pdays,";
		$sql .= " 		 c.pdesc,";
		$sql .= " 		 c.init,";
		chop($sql);
		$sql .= " from company_type t,";
		$sql .= "	   company c,";
		$sql .= "	   country u,";
		chop($sql);
		$sql .= " where c.delete_type is null ";
		$sql .= " and   u.country_code = c.country_code ";
		$sql .= " and   t.type = c.company_type ";

} else {

	&err("No list.");

}
#
# db open
#
$dbh = &db_open();

&do_sql($sql);

@coimpanies;
while((@datas) = $cur->fetchrow){

   $rec = {};
		$rec->{company_type} = $datas[0];
		$rec->{company} 	= $datas[1];
		$rec->{address1} 	= $datas[2];
		$rec->{address2} 	= $datas[3];
		$rec->{address3} 	= $datas[4];
		$rec->{attn} 		= $datas[5];
		$rec->{country} 	= $datas[6];
		$rec->{curr_code} 	= $datas[7];
		$rec->{tterm} 		= $datas[8];
		$rec->{pdays} 		= $datas[9];
		$rec->{pdesc} 		= $datas[10];
		$rec->{init} 		= $datas[11];

	push @companies,$rec;

}

if (defined @companies){

print<<ENDOFTEXT;
	<table border=1>
	<tr bgcolor="LightGreen">
	<th>TYPE</th>
	<th>NAME(INITIAL)</th>
	<th>ADDRESS</th>
	<th>TERM</th>
	<th>PAYMENT</th>
	</tr>
ENDOFTEXT
   foreach $key (@companies){
    print <<ENDOFTEXT;
   	<tr>
   	<td>$key->{company_type}</td>
   	<td>$key->{company}($key->{init})</td>
   	<td>$key->{address1} $key->{address2} $key->{addres3}<BR>$key->{attn}</td>
   	<td>$key->{tterm}</td>
   	<td>$key->{pdays} $key->{pdesc}</td>
   	</tr>
ENDOFTEXT
   }
   print "</table>\n";

} else {

	print "No data was found";
}

print<<ENDOFTEXT;
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;
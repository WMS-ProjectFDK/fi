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
print<<ENDOFTEXT;
<font size=4>SEARCH FOR "$keyword"</font>
ENDOFTEXT
		$sql  = " select ";
		$sql .= " 		 i.description,";	# ITEM DESCRIPTION
		$sql .= " 		 u.country,";		# ORIGIN COUNTRY
		$sql .= " 		 c.description,";	# CLASS
		$sql .= " 		 i.uom_q,";
		$sql .= " 		 i.uom_w,";
		$sql .= " 		 i.uom_l,";
		#$sql .= " 		 i.item_no_old,";
		$sql .= " 		 s.company,";		# SUPPLIER CODE
		#$sql .= " 		 i.packet_unit,";
		#$sql .= " 		 i.external_unit_no,";
		chop($sql);
		$sql .= " from class c,";
		$sql .= "	   item i,";
		$sql .= "	   country u,";
		$sql .= "	   company s,";
		chop($sql);
		$sql .= " where i.delete_type is null ";
		$sql .= " and ";
		$sql .= "       i.description like '%$keyword%'";
		$sql .= " and   u.country_code = i.origin_code ";
		$sql .= " and   s.company_code = i.supplier_code ";
		$sql .= " and   c.class_code = i.class_code ";

	}

} elsif ($in{'submit2'} ne ""){

	$submit_type = "Select";
	if ($in{'class_code'} eq ""){

		&err("Classification is not specified.");

	} else {

		$class_code = $in{'class_code'};

		$sql = "select description from class where class_code = $class_code ";

		&do_sql($sql);

		($class) = $cur->fetchrow();

print<<ENDOFTEXT;
<font size=4>PARTS LIST FOR $class</font>
ENDOFTEXT

		$sql  = " select ";
		$sql .= " 		 i.description,";	# ITEM DESCRIPTION
		$sql .= " 		 u.country,";		# ORIGIN COUNTRY
		$sql .= " 		 c.description,";	# CLASS
		$sql .= " 		 i.uom_q,";
		$sql .= " 		 i.uom_w,";
		$sql .= " 		 i.uom_l,";
		#$sql .= " 		 i.item_no_old,";
		$sql .= " 		 s.company,";		# SUPPLIER CODE
		#$sql .= " 		 i.packet_unit,";
		#$sql .= " 		 i.external_unit_no,";
		chop($sql);
		$sql .= " from class c,";
		$sql .= "	   item i,";
		$sql .= "	   country u,";
		$sql .= "	   company s,";
		chop($sql);
		$sql .= " where i.delete_type is null ";
		$sql .= " and ";
		$sql .= "       i.class_code = $class_code ";
		$sql .= " and   u.country_code = i.origin_code ";
		$sql .= " and   s.company_code = i.supplier_code ";
		$sql .= " and   c.class_code = i.class_code ";

	}

} elsif ($in{'submit3'} ne ""){

	$submit_type = "All";
print<<ENDOFTEXT;
<font size=4>ALL PARTS LIST</font>
ENDOFTEXT
		$sql  = " select ";
		$sql .= " 		 i.description,";	# ITEM DESCRIPTION
		$sql .= " 		 u.country,";		# ORIGIN COUNTRY
		$sql .= " 		 c.description,";	# CLASS
		$sql .= " 		 i.uom_q,";
		$sql .= " 		 i.uom_w,";
		$sql .= " 		 i.uom_l,";
		#$sql .= " 		 i.item_no_old,";
		$sql .= " 		 s.company,";		# SUPPLIER CODE
		#$sql .= " 		 i.packet_unit,";
		#$sql .= " 		 i.external_unit_no,";
		chop($sql);
		$sql .= " from class c,";
		$sql .= "	   item i,";
		$sql .= "	   country u,";
		$sql .= "	   company s,";
		chop($sql);
		$sql .= " where i.delete_type is null ";
		$sql .= " and   u.country_code = i.origin_code ";
		$sql .= " and   s.company_code = i.supplier_code ";
		$sql .= " and   c.class_code = i.class_code ";

} else {

	&err("No list.");

}
#
# db open
#
$dbh = &db_open();

&do_sql($sql);

@items;
while((@datas) = $cur->fetchrow){

   $rec = {};
		$rec->{description} = $datas[0];
		$rec->{country} 	= $datas[1];
		$rec->{class} 	= $datas[2];
		$rec->{uom_q} 	= $datas[3];
		$rec->{uom_w} 	= $datas[4];
		$rec->{uom_l} 		= $datas[5];
		$rec->{supplier} 	= $datas[6];

	push @items,$rec;

}

if (defined @items){

print<<ENDOFTEXT;
	<table border=1>
	<tr bgcolor="LightGreen">
	<th>NAME</th>
	<th>ORIGINAL</th>
	<th>GROUP</th>
	<th>UOM Q</th>
	<th>UOM W</th>
	<th>UOM L</th>
	<th>SUPPLIER</th>
	</tr>
ENDOFTEXT
   foreach $key (@items){
    print <<ENDOFTEXT;
   	<tr>
   	<td>$key->{description}</td>
   	<td>$key->{country}</td>
   	<td>$key->{class}</td>
   	<td>$key->{uom_q}</td>
   	<td>$key->{uom_w}</td>
   	<td>$key->{uom_l}</td>
   	<td>$key->{supplier}</td>
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
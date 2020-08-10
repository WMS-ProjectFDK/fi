#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance:Delete");
&title;

$dbh = &db_open();

#
# Form check
#
&err("Parts No. is null") if ($in{'item_no'} eq "");
&err("Origin Code is null") if ($in{'origin_code'} eq "");

$sql  = "select description from item ";
$sql .= " where item_no = $in{'item_no'} ";
$sql .= " and   origin_code = $in{'origin_code'} ";

&do_sql($sql);

($item) = $cur->fetchrow;

print<<ENDOFTEXT;
<H2>Parts Maintenance:Delete</H2>
<HR>
<table border=1>
ENDOFTEXT

#$sql  = "delete item ";
#$sql .= " where item_no = $in{'item_no'} ";
#$sql .= " and   origin_code = $in{'origin_code'} ";

$sql  = "update item set delete_type = 'D' ";
$sql .= " where item_no = $in{'item_no'} ";
$sql .= " and   origin_code = $in{'origin_code'} ";

&do_sql($sql);

$dbh->commit;

print "$item $item_no ($in{'origin_code'}) was deleted successfully.";

$dbh->disconnect;

print<<ENDOFTEXT;
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
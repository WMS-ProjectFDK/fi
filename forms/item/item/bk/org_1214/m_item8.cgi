#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance:Registration");
&title;

$dbh = &db_open();

#
# class code
#
$sql  = "select class_code,";
$sql .= " 		class_1,";
$sql .= "		class_2,";
$sql .= "		class_3,";
$sql .= "		description,";
chop($sql);
$sql .= " from class";

&do_sql($sql);

undef @class;
while ((@datas) = $cur->fetchrow){
   $rec = {};
      $rec->{class_code} = $datas[0];
      $rec->{class_1} = $datas[1];
      $rec->{class_2} = $datas[2];
      $rec->{class_3} = $datas[3];
      $rec->{description} = $datas[4];
	push @class,$rec ;
}
$cur->finish;

#
# unit
#
$sql = "select unit_code,unit,use from unit ";

&do_sql($sql);

undef @unit;
while ((@datas) = $cur->fetchrow){
  $rec = {};
  $rec->{unit_code} = $datas[0];
  $rec->{unit} = $datas[1];
  $rec->{use} = $datas[2];
	push @unit,$rec ;
}
$cur->finish;

#
# origin country
#
$sql = "select country_code,country from country ";

&do_sql($sql);

undef @country;
while ((@datas) = $cur->fetchrow){
 $rec = {};
  $rec->{country_code} = $datas[0];
  $rec->{country} = $datas[1];
	push @country,$rec ;
}
$cur->finish;

$sql = " select ";
foreach $key (@desc){
$sql .= " $$key[0],";
}
chop($sql);
$sql .= " from item ";
$sql .= " where ";
$sql .= "     item_no = $in{'ITEM_NO'}";
$sql .= " and origin_code = $in{'ORIGIN_CODE'}";

&do_sql($sql);

while((@datas) = $cur->fetchrow){

   $target_item = {};

	$i = 0;
   	foreach $key (@desc){
   		$target_item->{$$key[0]} = $datas[$i];
   		$i ++;
   	}
}

print<<ENDOFTEXT;
<H2>Parts Maintenance:Registration</H2>
<HR>
<table border=1>
ENDOFTEXT

$str = "";
foreach $key (@desc){
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">";
   	  print "<input type=hidden name=$$key[0] value=$in{$$key[0]}>\n";
      foreach $type (@country){
         if ($in{$$key[0]} eq $type->{country_code}){;
         	print "$type->{country_code} $type->{country}\n";
         }
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">";
      foreach $type (@class){
       if ($type->{class_code} eq $in{$$key[0]}){
         print "$type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n";
       }
      }
      print "<input type=hidden name=$$key[0] value=$in{$$key[0]}>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "UOM_Q"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">$in{$$key[0]}</td>";
      print "<input type=hidden name=$$key[0] value=$in{$$key[0]}>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_L"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">$in{$$key[0]}</td>";
      print "<input type=hidden name=$$key[0] value=$in{$$key[0]}>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_W"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">$in{$$key[0]}</td>";
      print "<input type=hidden name=$$key[0] value=$in{$$key[0]}>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td";
   	  if ($target_item->{$$key[0]} ne $in{$$key[0]}){
   	     $str .= "$$key[0] = $in{$$key[0]},";
   	  }
   	  print ">$in{$$key[0]}<BR></td>";
      print " <input type=hidden name=$$key[0] size=$$key[2] value='$target_item->{$$key[0]}'>";
      print "</tr>\n";
   }
}

if ($str eq ""){
  print "No changed.";
} else {
  $sql  = "update item ";
  $sql .= "set ";
  $sql .= $str;
  chop($sql);
  $sql .= " where item_no = $in{'ITEM_NO'} ";

  &do_sql($sql);

  $dbh->commit;

  print "Changed successfully.";

  $dbh->disconnect;
}

print<<ENDOFTEXT;
</table>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
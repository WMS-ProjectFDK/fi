#!/usr/local/bin/perl
require '/pglosas/init.pl';
#require 'init.pl';
require '/pglosas/master/item/item_desc.pl';
#require 'c:/inetpub/wwwroot/master/item/item_desc.pl' ;
&make_desc;

&html_header("Parts Maintenance");
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
$sql .= " order by class_code ";

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


print<<ENDOFTEXT;
<H2>Parts Maintenance:Registration</H2>
<HR>
<FORM METHOD=Post ACTION=m_item4.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

undef @str;
foreach $key (@desc){
   $rec = {};
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@country){
         print "$type->{country_code} $type->{country}\n" if ($type->{country_code} eq $in{$$key[0]});
      }
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@class){
         print "$type->{class_code} $type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n" if ($type->{class_code} eq $in{$$key[0]});
      }
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
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
   	  print " <td>";
      print "$in{$$key[0]}\n";
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_L"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}";
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_W"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}";
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
      print "</select>\n";
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
   	  print " <td>";
      print " $in{$$key[0]}";
      $rec->{str1} = $$key[0];
      $rec->{str2} = $in{$$key[0]};
      $rec->{type} = $$key[1];
      print "<br></td>\n";
      print "</tr>\n";
   }
   push @str,$rec;
}
print<<ENDOFTEXT;
</table>
ENDOFTEXT

$sql  = "insert into item(";
$sql1 = "";$sql2 = "";
foreach $key (@str){
   if ($key->{str1} ne "" and $key->{str2} ne ""){
      $sql1 .= "$key->{str1},"; 
      if ($key->{type} eq "X"){
         $sql2 .= "'$key->{str2}',"; 
      } else {
         $sql2 .= "$key->{str2},"; 
      }
   }
}
chop($sql1); chop($sql2);
if ($sql1 ne ""){
   $sql .= $sql1;
   $sql .= ") values(";
   $sql .= $sql2;
   $sql .= ")";

   &do_sql($sql);
   $dbh->commit;
   $dbh->disconnect;
   print "$in{DESCRIPTION} was registered successfully.";
}
print<<ENDOFTEXT;
<P>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

#COMPANY TYPE SUBJECT
  $sql  = " select  distinct" ;
  $sql .= "  company_type," ;
  $sql .= "  type_description " ;
  $sql .= " from company_type" ;
  $sql .= " where  1=1" ;
  $sql .= " order by company_type " ;

  $cur = $dbh->prepare($sql) || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
  $cur->execute() || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
  $TYP_NUM =0 ;
  while(@datas = $cur->fetchrow){
    $TYP[$TYP_NUM][0] = $datas[0] ;
    $TYP[$TYP_NUM][1] = $datas[1] ;
     $TYP_NUM ++ ;
  }
  $cur->finish();



#-------------------------
# HTML
#-------------------------
&html_header("ITEM MASTER VIEW");
&title();

print "COMPANY MASTER VIEW" ;
print "<FORM METHOD=Post NAME=myform ACTION=list_company2.cgi?$KEYWORD>\n" ;
print "KEYWORD:<input type=text name=word size=20><FONT COLOR=Gray>(NO.,NAME)</FONT>\n" ;

print "<P>" ;
print "TYPE:" ;
print "<SELECT NAME=company_type>\n" ;
print "<OPTION VALUE=''>\n" ;
for($i=0;$i<$TYP_NUM;$i++){
   print "<OPTION VALUE='$TYP[$i][0]'>$TYP[$i][1]\n" ;
}
print "</SELECT>" ;
print "<P>" ;
print "<INPUT TYPE=Submit VALUE='VIEW'>" ;
print "<HR>" ;
print "[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back to Main Menu</A>]" ;
print "</BODY>" ;
print "</HTML>" ;

#
# Class select
#
sub select_class{
 local($num,$form_name) = @_ ;
 if(!$num){ $num = 3 ;}
 if(!$form_name){ $form_name = "myform" ;}

 $sql  = " select distinct class_1,class_2,class_3,class_1,class_2,class_3 from class" ;
 $cur = $dbh->prepare($sql) || &err("sql prepare error $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("sql execute error  $DBI::err .... $DBI::errstr") ;
 local($class_num) = 0 ;
 while(@datas = $cur->fetchrow){
         for($k=0;$k<$num*2;$k++){ $CLASS[$k][$class_num] = $datas[$k] }
         $class_num ++ ;
 }
 $cur->finish();

 for($j=1;$j<=$num;$j++){
     if($j eq "$num"){
             print "<SELECT NAME=class_$j LENGTH=6>\n" ;
     }else{
             print "<SELECT NAME=class_$j onChange=\"changed_class$j(this.form)\" LENGTH=3>\n" ;
     }
     for($k=0;$k<=5;$k++){
             print "<OPTION VALUE=\"\">\n" ;
     }
             print "<OPTION VALUE=\"\">________\n" ;
     print "</SELECT>\n" ;
     if($j ne "$num"){ print " Å| " ;}
 }

 print "<SCRIPT LANGUAGE=\"JavaScript\">\n" ;
 print "  allclass = new Array(\n" ;
    for($j=0;$j<$class_num;$j++){
         if($j ne "0"){ print ",\n" ;}
         print "        new Array(" ;
            for($k=0;$k<$num*2;$k++){
                print "\"$CLASS[$k][$j]\"" ;
                if($k != ($num * 2 - 1)){ print "," ;}
            }
         print ")" ;
         }
 print "   );\n\n";

 for($i=0;$i<$num;$i++){
     print "function changed_class" . $i . "(thisFrom){ \n" ;
     for($j=$i;$j<$num;$j++){ print " thisFrom.class_" . ($j + 1) . ".length = 1; \n" ; }
     print "  var old_data ; \n" ;
     print "  for (i = 0; i < allclass.length; i++){ \n" ;
     if($i ne "0"){
         print "  if (\n" ;
            for($j=0;$j<$i;$j++){
              if($j ne "0"){ print " && \n" ; }
               print "   allclass[i][" . ($i - 1 - $j) . "] == " ;
               print "thisFrom.class_" . ($i - $j) . ".options[thisFrom.class_" . ($i - $j) . ".selectedIndex].value" ;
            }
         print "){ \n" ;
     }
     print "   if (old_data != allclass[i][" . $i . "] && allclass[i][" . ($i + 1) . "] != \"\" ){ \n" ;
         print "   thisFrom.class_" . ($i + 1) . ".options[thisFrom.class_" . ($i + 1) . ".length] = \n" ;
         print "                new Option(allclass[i][" . ($i + $num) . "], allclass[i][" . $i .  "]); \n" ;
     print "   } \n" ;
     if($i ne "0"){ print "  } \n" ; }
     print "    old_data = allclass[i][" . $i . "] ; \n" ;
     print "  } \n" ;
     print "  thisFrom.class_" . ($i + 1) . ".selectedIndex = 0; \n" ;
     print "} \n" ;
 }

 print "  changed_class0(document.$form_name) ;\n" ;
 if($def_class ne ""){
    for($i=1;$i<=$num;$i++){
      print " for(i=0;i<document.$form_name.class_$i.options.length;i++){\n" ;
      print "   if(document.$form_name.class_$i.options[i].value == \"${'def_class' . $i}\"){\n" ;
      print "    document.$form_name.class_$i.options[i].selected = true ;\n" ;
      print "    changed_class$i(document.$form_name) ;\n" if($i ne $num);
      print "   }\n" ;
      print " }\n" ;
    }
 }
 print "</SCRIPT>\n" ;
}

exit ;
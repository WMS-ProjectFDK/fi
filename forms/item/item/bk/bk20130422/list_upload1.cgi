#
# Programed by Y.Hagai
# 2010/02/11
#
#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;


#-------------------------
# POST
#-------------------------

#-------------------------
# DB OPEN
#-------------------------
 $dbh = &db_open() ;

#-------------------------
# SQL
#-------------------------

#-------------------------
# HTML
#-------------------------
&html_header('LIST ITEM');
&title() ;

print "LIST ITEM DATA UPLOAD<P>\n" ;
print <<ENDOFTEXT ;
<SCRIPT LANGUAGE=javascript>
   function chkSection(){
      if(document.myform1.section_code.options[document.myform1.section_code.selectedIndex].value == ''){
        alert("Please select section") ;
        return false ;
      }else{
        return true ;
      }
   }
</SCRIPT>
ENDOFTEXT


print "<FORM METHOD=Post NAME=myform1 ENCTYPE='multipart/form-data' ACTION='list_upload2.cgi?$KEYWORD'>\n" ;
print "<FONT COLOR=DarkBlue>UPLOAD:</FONT>" ;
print "<TABLE BORDER=0>" ;
print "<TR>\n" ;
print "<TR>\n" ;
print " <TD></TD>\n" ;
print " <TD COLSPAN=2><FONT COLOR=Blue>UPFILE </FONT><INPUT TYPE=File NAME=upfile SIZE=30></TD>" ;
print "</TR>" ;
print " <TD></TD>\n" ;
print " <TD><INPUT TYPE=Submit VALUE='UPLOAD'></TD>\n" ;
print "</TR>\n" ;
print "</TABLE>" ;
print "<P>\n" ;
print "</FORM>" ;
print "<a href='list_item_up_sample.csv' target='_blank'>Sample File(Click the right and store a file.)</a><br>\n" ;


&html_fooder("../item/list_item1.cgi?$KEYWORD","BACK") ;


#
# Programed by T.Ohsugi 
# 2000/07/04 

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

$form_name = $in{'form_name'} ;
#  if($form_name eq ""){ &err("System error")
$format = $in{'format'} ;
  if($format eq ""){ $format = 'ddddmmyyyy' ;}
$default_val = $in{'default_val'} ;
$add_num = $in{'add_num'} ;
  if($add_num eq ""){ $add_num = 0 ;}

#-------------------------
# ì˙ïtê›íË
#-------------------------
 $local_today = &get_date('yyyy/mm/dd');
 # ç°ì˙
  $local_today =~ /(\w+)\/(\w+)\/(\w+)/ ;
  $local_year = $1 ;
  $local_mon  = $2 ;
  $local_day  = $3 ;

 # ëIëÇ≥ÇÍÇƒÇ¢ÇÈì˙ït
  $format =~ tr/[A-Z]/[a-z]/ ;
  $default_year = substr($default_val,index($format,'yyyy'),4) ;
  $default_mon = substr($default_val,index($format,'mm'),2) ;
  $default_day = substr($default_val,index($format,'dd'),2) ;
  if($default_year < 1999 || $default_year >= 2036){ $default_val = "" ; }
  if($default_mon < 1 || $default_mon > 12){ $default_val = "" ; }
  if($default_day < 1 || $default_day > 31){ $default_val = "" ; }
  if($default_val ne ""){
    $def_num = (($default_year * 12) + $default_mon) - (($local_year * 12) + $local_mon)  ;
  }
  

 # ìñåé
  $yearmonth =~ /(\w+)\/(\w+)/ ;
  $this_year = $1 ;
  $this_mon = $2 ;

  $this_year = $local_year ;
  $this_mon = $local_mon ;
   for($i=1;$i<=$add_num + $def_num;$i++){
     $this_mon += 1;
     if($this_mon == 13){ $this_mon =1 ; $this_year ++ ;}
   }
   for($i=0;$i>$add_num + $def_num;$i--){
     $this_mon -= 1;
     if($this_mon == 0){ $this_mon =12 ; $this_year -- ;}
   }
   $this_mon = substr("0".$this_mon,-2,2) ;
   

 # óÇåé
  $next_year = $this_year ;
  $next_mon = $this_mon + 1 ;
  if($next_mon > 12){ $next_mon = 1 ; $next_year ++ ;}

 @weeks = (Sun,Mon,Tue,Wed,Thu,Fri,Sat) ;

 # add_num
  $next_add_num = $add_num + 1 ;
  $pre_add_num = $add_num - 1 ;

 # åéÇÃÇPì˙ÇÃójì˙ÇéÊìæ
  use Time::Local ;
  $Stime = timelocal(0,0,0,1,$this_mon -1,$this_year -1900) ;
  $Swday = (localtime($Stime))[6] ;

  $Etime = timelocal(0,0,0,1,$next_mon -1,$next_year -1900)  - 1;
  $Emday = (localtime($Etime))[3] ;

#-------------------------
# HTML
#-------------------------
&html_header("CALENDER");

print "<STYLE TYPE='text/css'>\n" ;
print "<!--\n" ;
print "A       {text-decoration:none;COLOR:blue;}\n" ;
print "TD      {font-size:10pt;}\n" ;
print "TH      {font-size:10pt;}\n" ;
print "-->\n" ;
print "</STYLE>\n" ;

print "<SCRIPT LANGUAGE=Javascript>\n" ;
print "   function set_data(theData){\n" ;
print "      opener.document.inputform.$form_name.value = theData ;\n" ;
print "      self.close() ;\n" ;
print "   }\n" ;
print "\n" ;
print "   function window_close(){\n" ;
print "      self.close() ;\n" ;
print "   }\n" ;
print "</SCRIPT>\n" ;

print "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
print "<TR BGCOLOR=#FFFFFF><TD>" ;
 print "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=1 WIDTH=100%>\n";
 print "<TR>" ;

 $Param  = "&form_name=$form_name" ;
 $Param .= "&default_val=$default_val" ;
 $Param .= "&format=$format" ;

 print "<TD ALIGN=Left>[<A HREF='calender.pl?KEYWORD=$KEYWORD&add_num=$pre_add_num$Param'>PRE</A>] </TD>" ;
 print "<TH ALIGN=Center>$this_mon/$this_year</TH>" ;
 print "<TD ALIGN=Right>[<A HREF='calender.pl?KEYWORD=$KEYWORD&add_num=$next_add_num$Param'>NEXT</A>]</TD>" ;
 print "</TR>" ;
 print "</TABLE>\n" ;
print "</TD></TR>\n" ;
print "<TR BGCOLOR=#000000><TD>\n" ;
 print "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=1>\n";
 print "<TR BGCOLOR=LightGreen>" ;
 for($i=0;$i<7;$i++){
    if   ($i==0){$bgcolor = "#FF9999" ;}
    elsif($i==6){$bgcolor = "#9999FF" ;}
    else        {$bgcolor = "#99FF99" ;}

    print "<TH BGCOLOR=$bgcolor WIDTH=30>$weeks[$i]</TH>" ;
 }
 print "</TR>\n" ;
 $s_flag = 0 ; $e_flag = 0 ; $disp_days = 1 ;
 for($j=0;$j<6;$j++){
     print "<TR BGCOLOR=White ALIGN=Center>" ;
     for($i=0;$i<7;$i++){
        if($s_flag == 0){
           if($i == $Swday){ $s_flag = 1;}
        }
        if($Emday < $disp_days){
           $e_flag = 1 ;
        }
        if($s_flag == 1 && $e_flag == 0){
          $bgcolor = "White" ;
          if($this_year==$local_year && $this_mon==$local_mon && $disp_days == $local_day){
             $bgcolor = "#FFFF77" ;
          }
          if($this_year==$default_year && $this_mon==$default_mon && $disp_days == $default_day){
             $bgcolor = "#99CCFF" ;
          }
          print "<TD BGCOLOR=$bgcolor>" ;
             $dates = $format ;
             $dates =~ s/yyyy/$this_year/ ;
             $dates =~ s/mm/$this_mon/ ;
              $set_day = substr("0".$disp_days,-2,2) ;
             $dates =~ s/dd/$set_day/ ;
          print "<A HREF=\"javascript:set_data('$dates')\">" ;
          print "$disp_days" ;
          print "</A>" ;
          print "</TD>" ;
          $disp_days ++ ;
        }else{
          print "<TD BGCOLOR=#CCCCCC>-<BR></TD>" ;
        }
     }
     print "</TR>\n" ;
     if($e_flag == 1){last ;}
 }
 print "</TABLE>\n" ;
 print "</TD></TR>\n" ;
print "<TR><TD>" ;
 print "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=1 WIDTH=100%>\n";
 print "<TR>" ;
 print "<TD ALIGN=Center>[<A HREF=\"javascript:window_close()\">CLOSE</A>]</TD>" ;
 print "</TR>" ;
 print "</TABLE>\n" ;
print "</TD></TR>" ;
print "</TABLE>\n" ;
print "<SCRIPT LANGUAGE=javascript>\n" ;
print "  self.focus() ;\n" ;
print "</SCRIPT>" ;
print "</BODY></HTML>\n" ;
         

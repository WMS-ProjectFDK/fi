<?php
include("../../connect/conn3.php");
$sql = "select Line,
        jalan  Starts,
       stops,
       cast(rasio as varchar) +  ' %' rasio ,
       cast(rasio as varchar)  rasio1 ,
       case when sts_start = 0 and sts_stop = 0 then 'PERMISIBLE STOP' else 
            case when sts_start = 1 and sts_stop = 0 then 'RUNNING'
             else case when STS_STOP = 1 then 'STOP'
            end
        end  end as sts,
        case line 
            when 'LR3#1' then '31'
            when 'LR3#2' then '32'
            when 'LR06#1' then '61'
            when 'LR06#2' then '62'
            when 'LR06#3' then '63'
            when 'LR06#4' then '64'
            when 'LR06#6' then '66'
        end link,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),105)) TGL,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),108)) WKT
        ,assylr31.dbo.cekrasio(getdate()) 'RASIO31'
        ,assylr32.dbo.cekrasio(getdate()) 'RASIO32'
        ,assylr61.dbo.cekrasio(getdate()) 'RASIO61'
        ,assylr62.dbo.cekrasio(getdate()) 'RASIO62'
        ,assylr63.dbo.cekrasio(getdate()) 'RASIO63'
        ,assylr64.dbo.cekrasio(getdate()) 'RASIO64'
        ,assylr66.dbo.cekrasio(getdate()) 'RASIO66'
from ( 
    
select 'LR3#1' Line,assylr31.dbo.cekjalan(getdate()) JALAN,cast(assylr31.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr31.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr31.dbo.line_master where id_line = 17) STS_START
        ,(select status from assylr31.dbo.line_master where id_line = 15) STS_STOP
UNION ALL
select 'LR3#2' Line,assylr32.dbo.cekjalan(getdate()) JALAN,cast(assylr32.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr32.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr32.dbo.line_master where assylr32.dbo.line_master.id_line = 12) STS_START
        ,(select status from assylr32.dbo.line_master where assylr32.dbo.line_master.id_line = 11) STS_STOP
UNION ALL
select 'LR06#1' Line,assylr61.dbo.cekjalan(getdate()) JALAN,cast(assylr61.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr61.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr61.dbo.line_master where assylr61.dbo.line_master.id_line = 14) STS_START
        ,(select status from assylr61.dbo.line_master where assylr61.dbo.line_master.id_line = 12) STS_STOP
UNION ALL

select 'LR06#2' Line,assylr62.dbo.cekjalan(getdate()) JALAN,cast(assylr62.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr62.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr62.dbo.line_master where assylr62.dbo.line_master.id_line = 15) STS_START
        ,(select status from assylr62.dbo.line_master where assylr62.dbo.line_master.id_line = 12) STS_STOP
UNION ALL
select 'LR06#3' Line,assylr63.dbo.cekjalan(getdate()) JALAN,cast(assylr63.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr63.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr63.dbo.line_master where assylr63.dbo.line_master.id_line = 16) STS_START
        ,(select status from assylr63.dbo.line_master where assylr63.dbo.line_master.id_line = 13) STS_STOP
UNION ALL
select 'LR06#4' Line,assylr64.dbo.cekjalan(getdate()) JALAN,cast(assylr64.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr64.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr64.dbo.line_master where assylr64.dbo.line_master.id_line = 18) STS_START
        ,(select status from assylr64.dbo.line_master where assylr64.dbo.line_master.id_line = 15) STS_STOP
UNION ALL
select 'LR06#6' Line,assylr66.dbo.cekjalan(getdate()) JALAN,cast(assylr66.dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,assylr66.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr66.dbo.line_master where assylr66.dbo.line_master.id_line = 13) STS_START
        ,(select status from assylr66.dbo.line_master where assylr66.dbo.line_master.id_line = 09) STS_STOP
)aa";
$rs=odbc_exec($con32,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
   
   $arrData[$arrNo] = array("LINE"=>odbc_result($rs,"LINE"),
                             "STOPS"=>foo(odbc_result($rs,"STOPS")),
                             "STARTS"=>foo(odbc_result($rs,"STARTS")),
                             "STS"=>odbc_result($rs,"STS"),
                             "WKT"=>odbc_result($rs,"WKT"),
                             "TGL"=>odbc_result($rs,"TGL"),
                             "LINK"=>'<a target="_blank" href="http://172.23.225.85/wms/kanban/monitoring/index'.odbc_result($rs,"LINK").'.php" style="text-decoration: none; color: blue;font-size:20px">Go to '.odbc_result($rs,"LINE").'</a>',
                             "RASIO31"=>odbc_result($rs,"RASIO31"),
                             "RASIO32"=>odbc_result($rs,"RASIO32"),
                             "RASIO62"=>odbc_result($rs,"RASIO62"),
                             "RASIO63"=>odbc_result($rs,"RASIO63"),
                             "RASIO64"=>odbc_result($rs,"RASIO64"),
                             "RASIO61"=>odbc_result($rs,"RASIO61"),
                             "RASIO1"=>odbc_result($rs,"RASIO1"),
                             "RASIO"=>odbc_result($rs,"RASIO")
                    );
   
$arrNo++;

}

echo json_encode($arrData);

function foo($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
?>
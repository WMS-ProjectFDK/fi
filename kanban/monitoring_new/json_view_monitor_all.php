<?php
include("../../connect/conn_kanbansys.php");
$sql = "SELECT LINE, JALAN AS STARTS, STOPS,
    CAST(RASIO AS VARCHAR)+' %' RASIO,
    CAST(RASIO AS VARCHAR) RASIO1,
    CASE WHEN S_START = 0 AND S_STOP = 0 THEN 'PERMISIBLE STOP' ELSE 
    CASE WHEN S_START = 1 AND S_STOP = 0 THEN 'RUNNING' ELSE
    CASE WHEN S_STOP = 1 THEN 'STOP' END END END AS STS,
    CASE LINE 
        WHEN 'LR03#1' THEN '31'
        WHEN 'LR03#2' THEN '32'
        WHEN 'LR6#1' THEN '61'
        WHEN 'LR6#2' THEN '62'
        WHEN 'LR6#3' THEN '63'
        WHEN 'LR6#4' THEN '64'
        WHEN 'LR6#6' THEN '66'
    END LINK,
    (SELECT CONVERT(VARCHAR(20), GETDATE(), 105)) AS TGL,
    (SELECT CONVERT(VARCHAR(20), GETDATE(), 108)) AS WKT,
    ISNULL(dbo.cekrasio('LR03-1',getdate()), 0) AS 'RASIO31',
    ISNULL(dbo.cekrasio('LR03-2',getdate()), 0) AS 'RASIO32',
    ISNULL(dbo.cekrasio('LR6-1',getdate()), 0) AS 'RASIO61',
    ISNULL(dbo.cekrasio('LR6-2',getdate()), 0) AS 'RASIO62',
    ISNULL(dbo.cekrasio('LR6-3',getdate()), 0) AS 'RASIO63',
    ISNULL(dbo.cekrasio('LR6-4',getdate()), 0) AS 'RASIO64',
    ISNULL(dbo.cekrasio('LR6-6',getdate()), 0) AS 'RASIO66'
    FROM (
    SELECT 'LR03#1' as line,
    dbo.cekStart('LR03-1', GETDATE()) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR03-1', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    dbo.cekStop('LR03-1', GETDATE()) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=16 AND REPLACE(line, '#', '-') = 'LR03-1') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=17 AND REPLACE(line, '#', '-') = 'LR03-1') AS S_STOP

    UNION ALL

    SELECT 'LR03#2' as line,
    ISNULL(dbo.cekStart('LR03-2', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR03-2', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR03-2', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=13 AND REPLACE(line, '#', '-') = 'LR03-2') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=14 AND REPLACE(line, '#', '-') = 'LR03-2') AS S_STOP

    UNION ALL

    SELECT 'LR6#1' as line,
    ISNULL(dbo.cekStart('LR6-1', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR6-1', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR6-1', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=13 AND REPLACE(line, '#', '-') = 'LR6-1') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=14 AND REPLACE(line, '#', '-') = 'LR6-1') AS S_STOP

    UNION ALL

    SELECT 'LR6#2' as line,
    ISNULL(dbo.cekStart('LR6-2', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR6-2', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR6-2', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=14 AND REPLACE(line, '#', '-') = 'LR6-2') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=15 AND REPLACE(line, '#', '-') = 'LR6-2') AS S_STOP

    UNION ALL

    SELECT 'LR6#3' as line,
    ISNULL(dbo.cekStart('LR6-3', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR6-3', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR6-3', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=15 AND REPLACE(line, '#', '-') = 'LR6-3') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=16 AND REPLACE(line, '#', '-') = 'LR6-3') AS S_STOP

    UNION ALL

    SELECT 'LR6#4' as line,
    ISNULL(dbo.cekStart('LR6-4', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR6-4', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR6-4', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=17 AND REPLACE(line, '#', '-') = 'LR6-4') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=18 AND REPLACE(line, '#', '-') = 'LR6-4') AS S_STOP

    UNION ALL

    SELECT 'LR6#6' as line,
    ISNULL(dbo.cekStart('LR6-6', GETDATE()),0) AS JALAN,
    ISNULL(CAST(dbo.cekRasio('LR6-6', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
    ISNULL(dbo.cekStop('LR6-6', GETDATE()),0) AS STOPS,
    (SELECT status FROM assembly_line_master WHERE id_machine=12 AND REPLACE(line, '#', '-') = 'LR6-6') AS S_START,
    (SELECT status FROM assembly_line_master WHERE id_machine=13 AND REPLACE(line, '#', '-') = 'LR6-6') AS S_STOP

    )aa";
$rs=odbc_exec($connect,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
   
   $arrData[$arrNo] = array("LINE"=>odbc_result($rs,"LINE"),
                             "STOPS"=>foo(odbc_result($rs,"STOPS")),
                             "STARTS"=>foo(odbc_result($rs,"STARTS")),
                             "STS"=>odbc_result($rs,"STS"),
                             "WKT"=>odbc_result($rs,"WKT"),
                             "TGL"=>odbc_result($rs,"TGL"),
                             "LINK"=>'<a target="_blank" href="http://172.23.225.85/wms/kanban/monitoring_new/index'.odbc_result($rs,"LINK").'.php" style="text-decoration: none; color: blue;font-size:20px">Go to '.odbc_result($rs,"LINE").'</a>',
                             "RASIO31"=>odbc_result($rs,"RASIO31"),
                             "RASIO32"=>odbc_result($rs,"RASIO32"),
                             "RASIO61"=>odbc_result($rs,"RASIO61"),
                             "RASIO62"=>odbc_result($rs,"RASIO62"),
                             "RASIO63"=>odbc_result($rs,"RASIO63"),
                             "RASIO64"=>odbc_result($rs,"RASIO64"),
                             "RASIO66"=>odbc_result($rs,"RASIO66"),
                             "RASIO1"=>odbc_result($rs,"RASIO1"),
                             "RASIO"=>odbc_result($rs,"RASIO")
                    );

    $response[] = array("LINE"=>odbc_result($rs,"LINE"),
                             "STOPS"=>foo(odbc_result($rs,"STOPS")),
                             "STARTS"=>foo(odbc_result($rs,"STARTS")),
                             "STS"=>odbc_result($rs,"STS"),
                             "WKT"=>odbc_result($rs,"WKT"),
                             "TGL"=>odbc_result($rs,"TGL"),
                             "LINK"=>'<a target="_blank" href="http://172.23.225.85/wms/kanban/monitoring_new/index'.odbc_result($rs,"LINK").'.php" style="text-decoration: none; color: blue;font-size:20px">Go to '.odbc_result($rs,"LINE").'</a>',
                             "RASIO31"=>odbc_result($rs,"RASIO31"),
                             "RASIO32"=>odbc_result($rs,"RASIO32"),
                             "RASIO61"=>odbc_result($rs,"RASIO61"),
                             "RASIO62"=>odbc_result($rs,"RASIO62"),
                             "RASIO63"=>odbc_result($rs,"RASIO63"),
                             "RASIO64"=>odbc_result($rs,"RASIO64"),
                             "RASIO66"=>odbc_result($rs,"RASIO66"),
                             "RASIO1"=>odbc_result($rs,"RASIO1"),
                             "RASIO"=>odbc_result($rs,"RASIO")
                );

    $fp = fopen('result.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
$arrNo++;

}

echo json_encode($arrData);

function foo($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
?>
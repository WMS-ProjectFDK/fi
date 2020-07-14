<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
include("../../connect/conn_kanbansys.php");

$sql = "SELECT LINE, JALAN AS STARTS, STOPS,
    CAST(RASIO AS VARCHAR)+' %' RASIO,
    CAST(RASIO AS VARCHAR) RASIO1,
    CASE WHEN S_START = 0 AND S_STOP = 0 THEN 'PERMISIBLE STOP' ELSE 
    CASE WHEN S_START = 1 AND S_STOP = 0 THEN 'RUNNING' ELSE
    CASE WHEN S_STOP = 1 THEN 'STOP' END END END AS STS,
    (SELECT CONVERT(VARCHAR(20), GETDATE(), 105)) AS TGL,
    (SELECT CONVERT(VARCHAR(20), GETDATE(), 108)) AS WKT,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_1A', getdate()), 0) as RASIO_LR03_1_1A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_1B', getdate()), 0) as RASIO_LR03_1_1B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_2A', getdate()), 0) as RASIO_LR03_1_2A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_2B', getdate()), 0) as RASIO_LR03_1_2B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_3A', getdate()), 0) as RASIO_LR03_1_3A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_3B', getdate()), 0) as RASIO_LR03_1_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_4A', getdate()), 0) as RASIO_LR03_1_4A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_4B', getdate()), 0) as RASIO_LR03_1_4B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_5A', getdate()), 0) as RASIO_LR03_1_5A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR03#1_5B', getdate()), 0) as RASIO_LR03_1_5B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_1A', getdate()), 0) as RASIO_LR6_1_1A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_1B', getdate()), 0) as RASIO_LR6_1_1B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_2A', getdate()), 0) as RASIO_LR6_1_2A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_2B', getdate()), 0) as RASIO_LR6_1_2B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_3A', getdate()), 0) as RASIO_LR6_1_3A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_3B', getdate()), 0) as RASIO_LR6_1_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_4A', getdate()), 0) as RASIO_LR6_1_4A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#1_4B', getdate()), 0) as RASIO_LR6_1_4B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_1A', getdate()), 0) as RASIO_LR6_2_1A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_1B', getdate()), 0) as RASIO_LR6_2_1B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_2A', getdate()), 0) as RASIO_LR6_2_2A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_2B', getdate()), 0) as RASIO_LR6_2_2B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_3A', getdate()), 0) as RASIO_LR6_2_3A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_3B', getdate()), 0) as RASIO_LR6_2_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_4A', getdate()), 0) as RASIO_LR6_2_4A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#2_4B', getdate()), 0) as RASIO_LR6_2_4B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_1A', getdate()), 0) as RASIO_LR6_3_1A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_1B', getdate()), 0) as RASIO_LR6_3_1B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_2A', getdate()), 0) as RASIO_LR6_3_2A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_2B', getdate()), 0) as RASIO_LR6_3_2B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_3A', getdate()), 0) as RASIO_LR6_3_3A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_3B', getdate()), 0) as RASIO_LR6_3_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_4A', getdate()), 0) as RASIO_LR6_3_4A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#3_4B', getdate()), 0) as RASIO_LR6_3_4B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_1A', getdate()), 0) as RASIO_LR6_4_1A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_1B', getdate()), 0) as RASIO_LR6_4_1B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_2A', getdate()), 0) as RASIO_LR6_4_2A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_2B', getdate()), 0) as RASIO_LR6_4_2B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_3A', getdate()), 0) as RASIO_LR6_4_3A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_3B', getdate()), 0) as RASIO_LR6_4_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_3B', getdate()), 0) as RASIO_LR6_4_3B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_4A', getdate()), 0) as RASIO_LR6_4_4A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_4B', getdate()), 0) as RASIO_LR6_4_4B,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_5A', getdate()), 0) as RASIO_LR6_4_5A,
    ISNULL(dbo.cekRasio_separator('SEPA_LR6#4_5B', getdate()), 0) as RASIO_LR6_4_5B
    FROM (

--SEPA_LR03#1_1A
select 'SEPA_LR03#1_1A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_1A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_1A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_1A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_1A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_1A') AS S_STOP

UNION ALL

--SEPA_LR03#1_1B
select 'SEPA_LR03#1_1B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_1B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_1B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_1B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_1B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_1B') AS S_STOP

UNION ALL

--SEPA_LR03#1_2A
select 'SEPA_LR03#1_2A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_2A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_2A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_2A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_2A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_2A') AS S_STOP

UNION ALL

--SEPA_LR03#1_2B
select 'SEPA_LR03#1_2B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_2B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_2B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_2B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_2B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_2B') AS S_STOP

UNION ALL

--SEPA_LR03#1_3A
select 'SEPA_LR03#1_3A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_3A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_3A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_3A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_3A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_3A') AS S_STOP

UNION ALL

--SEPA_LR03#1_3B
select 'SEPA_LR03#1_3B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_3B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_3B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_3B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_3B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_3B') AS S_STOP

UNION ALL

--SEPA_LR03#1_4A
select 'SEPA_LR03#1_4A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_4A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_4A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_4A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_4A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_4A') AS S_STOP

UNION ALL

--SEPA_LR03#1_4B
select 'SEPA_LR03#1_4B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_4B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_4B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_4B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_4B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_4B') AS S_STOP

UNION ALL

--SEPA_LR03#1_5A
select 'SEPA_LR03#1_5A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_5A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_5A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_5A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_5A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_5A') AS S_STOP

UNION ALL

--SEPA_LR03#1_5B
select 'SEPA_LR03#1_5B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR03#1_5B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR03#1_5B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR03#1_5B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR03#1_5B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR03#1_5B') AS S_STOP

UNION ALL

--SEPA_LR6#1_1A
select 'SEPA_LR6#1_1A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_1A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_1A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_1A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_1A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_1A') AS S_STOP

UNION ALL

--SEPA_LR6#1_1B
select 'SEPA_LR6#1_1B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_1B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_1B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_1B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_1B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_1B') AS S_STOP

UNION ALL

--SEPA_LR6#1_2A
select 'SEPA_LR6#1_2A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_2A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_2A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_2A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_2A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_2A') AS S_STOP

UNION ALL

--SEPA_LR6#1_2B
select 'SEPA_LR6#1_2B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_2B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_2B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_2B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_2B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_2B') AS S_STOP

UNION ALL

--SEPA_LR6#1_3A
select 'SEPA_LR6#1_3A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_3A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_3A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_3A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_3A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_3A') AS S_STOP

UNION ALL

--SEPA_LR6#1_3B
select 'SEPA_LR6#1_3B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_3B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_3B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_3B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_3B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_3B') AS S_STOP

UNION ALL

--SEPA_LR6#1_4A
select 'SEPA_LR6#1_4A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_4A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_4A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_4A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_4A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_4A') AS S_STOP

UNION ALL

--SEPA_LR6#1_4B
select 'SEPA_LR6#1_4B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#1_4B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#1_4B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#1_4B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#1_4B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#1_4B') AS S_STOP

UNION ALL

--SEPA_LR6#2_1A
select 'SEPA_LR6#2_1A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_1A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_1A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_1A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_1A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_1A') AS S_STOP

UNION ALL

--SEPA_LR6#2_1B
select 'SEPA_LR6#2_1B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_1B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_1B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_1B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_1B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_1B') AS S_STOP

UNION ALL

--SEPA_LR6#2_2A
select 'SEPA_LR6#2_2A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_2A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_2A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_2A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_2A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_2A') AS S_STOP

UNION ALL

--SEPA_LR6#2_2B
select 'SEPA_LR6#2_2B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_2B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_2B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_2B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_2B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_2B') AS S_STOP

UNION ALL

--SEPA_LR6#2_3A
select 'SEPA_LR6#2_3A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_3A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_3A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_3A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_3A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_3A') AS S_STOP

UNION ALL

--SEPA_LR6#2_3B
select 'SEPA_LR6#2_3B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_3B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_3B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_3B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_3B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_3B') AS S_STOP

UNION ALL

--SEPA_LR6#2_4A
select 'SEPA_LR6#2_4A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_4A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_4A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_4A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_4A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_4A') AS S_STOP

UNION ALL

--SEPA_LR6#2_4B
select 'SEPA_LR6#2_4B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#2_4B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#2_4B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#2_4B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#2_4B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#2_4B') AS S_STOP

UNION ALL

--SEPA_LR6#3_1A
select 'SEPA_LR6#3_1A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_1A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_1A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_1A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_1A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_1A') AS S_STOP

UNION ALL

--SEPA_LR6#3_1B
select 'SEPA_LR6#3_1B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_1B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_1B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_1B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_1B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_1B') AS S_STOP

UNION ALL

--SEPA_LR6#3_2A
select 'SEPA_LR6#3_2A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_2A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_2A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_2A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_2A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_2A') AS S_STOP

UNION ALL

--SEPA_LR6#3_2B
select 'SEPA_LR6#3_2B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_2B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_2B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_2B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_2B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_2B') AS S_STOP

UNION ALL

--SEPA_LR6#3_3A
select 'SEPA_LR6#3_3A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_3A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_3A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_3A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_3A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_3A') AS S_STOP

UNION ALL

--SEPA_LR6#3_3B
select 'SEPA_LR6#3_3B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_3B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_3B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_3B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_3B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_3B') AS S_STOP

UNION ALL

--SEPA_LR6#3_4A
select 'SEPA_LR6#3_4A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_4A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_4A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_4A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_4A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_4A') AS S_STOP

UNION ALL

--SEPA_LR6#3_4B
select 'SEPA_LR6#3_4B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#3_4B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#3_4B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#3_4B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#3_4B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#3_4B') AS S_STOP

UNION ALL

--SEPA_LR6#4_1A
select 'SEPA_LR6#4_1A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_1A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_1A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_1A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_1A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_1A') AS S_STOP

UNION ALL

--SEPA_LR6#4_1B
select 'SEPA_LR6#4_1B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_1B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_1B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_1B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_1B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_1B') AS S_STOP

UNION ALL

--SEPA_LR6#4_2A
select 'SEPA_LR6#4_2A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_2A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_2A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_2A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_2A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_2A') AS S_STOP

UNION ALL

--SEPA_LR6#4_2B
select 'SEPA_LR6#4_2B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_2B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_2B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_2B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_2B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_2B') AS S_STOP

UNION ALL

--SEPA_LR6#4_3A
select 'SEPA_LR6#4_3A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_3A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_3A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_3A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_3A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_3A') AS S_STOP

UNION ALL

--SEPA_LR6#4_3B
select 'SEPA_LR6#4_3B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_3B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_3B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_3B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_3B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_3B') AS S_STOP

UNION ALL

--SEPA_LR6#4_4A
select 'SEPA_LR6#4_4A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_4A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_4A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_4A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_4A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_4A') AS S_STOP

UNION ALL

--SEPA_LR6#4_4B
select 'SEPA_LR6#4_4B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_4B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_4B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_4B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_4B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_4B') AS S_STOP

UNION ALL

--SEPA_LR6#4_5A
select 'SEPA_LR6#4_5A' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_5A', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_5A', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_5A', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_5A') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_5A') AS S_STOP

UNION ALL

--SEPA_LR6#4_5B
select 'SEPA_LR6#4_5B' as line,
ISNULL(dbo.cekStart_separator('SEPA_LR6#4_5B', GETDATE()),0) AS JALAN,
ISNULL(CAST(dbo.cekRasio_separator('SEPA_LR6#4_5B', GETDATE()) AS decimal(18, 2)), 0) AS RASIO,
ISNULL(dbo.cekStop_separator('SEPA_LR6#4_5B', GETDATE()),0) AS STOPS,
(SELECT status FROM separator_line_master WHERE id_machine=1 AND line = 'SEPA_LR6#4_5B') AS S_START,
(SELECT status FROM separator_line_master WHERE id_machine=2 AND line = 'SEPA_LR6#4_5B') AS S_STOP

)aa
order by line asc";
$rs=odbc_exec($connect,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
    $arrData[$arrNo] = array("LINE"=>odbc_result($rs,"LINE"),
                            "STOPS"=>foo(odbc_result($rs,"STOPS")),
                            "STARTS"=>foo(odbc_result($rs,"STARTS")),
                            "STS"=>odbc_result($rs,"STS"),
                            "WKT"=>odbc_result($rs,"WKT"),
                            "TGL"=>odbc_result($rs,"TGL"),
                            "RASIO"=>odbc_result($rs,"RASIO"),
                            "RASIO1"=>odbc_result($rs,"RASIO1"),
                            "RASIO_LR03_1_1A"=>odbc_result($rs,"RASIO_LR03_1_1A"),
                            "RASIO_LR03_1_1B"=>odbc_result($rs,"RASIO_LR03_1_1B"),
                            "RASIO_LR03_1_2A"=>odbc_result($rs,"RASIO_LR03_1_2A"),
                            "RASIO_LR03_1_2B"=>odbc_result($rs,"RASIO_LR03_1_2B"),
                            "RASIO_LR03_1_3A"=>odbc_result($rs,"RASIO_LR03_1_3A"),
                            "RASIO_LR03_1_3B"=>odbc_result($rs,"RASIO_LR03_1_3B"),
                            "RASIO_LR03_1_4A"=>odbc_result($rs,"RASIO_LR03_1_4A"),
                            "RASIO_LR03_1_4B"=>odbc_result($rs,"RASIO_LR03_1_4B"),
                            "RASIO_LR03_1_5A"=>odbc_result($rs,"RASIO_LR03_1_5A"),
                            "RASIO_LR03_1_5B"=>odbc_result($rs,"RASIO_LR03_1_5B"),
                            "RASIO_LR6_1_1A"=>odbc_result($rs,"RASIO_LR6_1_1A"),
                            "RASIO_LR6_1_1B"=>odbc_result($rs,"RASIO_LR6_1_1B"),
                            "RASIO_LR6_1_2A"=>odbc_result($rs,"RASIO_LR6_1_2A"),
                            "RASIO_LR6_1_2B"=>odbc_result($rs,"RASIO_LR6_1_2B"),
                            "RASIO_LR6_1_3A"=>odbc_result($rs,"RASIO_LR6_1_3A"),
                            "RASIO_LR6_1_3B"=>odbc_result($rs,"RASIO_LR6_1_3B"),
                            "RASIO_LR6_1_4A"=>odbc_result($rs,"RASIO_LR6_1_4A"),
                            "RASIO_LR6_1_4B"=>odbc_result($rs,"RASIO_LR6_1_4B"),
                            "RASIO_LR6_2_1A"=>odbc_result($rs,"RASIO_LR6_2_1A"),
                            "RASIO_LR6_2_1B"=>odbc_result($rs,"RASIO_LR6_2_1B"),
                            "RASIO_LR6_2_2A"=>odbc_result($rs,"RASIO_LR6_2_2A"),
                            "RASIO_LR6_2_2B"=>odbc_result($rs,"RASIO_LR6_2_2B"),
                            "RASIO_LR6_2_3A"=>odbc_result($rs,"RASIO_LR6_2_3A"),
                            "RASIO_LR6_2_3B"=>odbc_result($rs,"RASIO_LR6_2_3B"),
                            "RASIO_LR6_2_4A"=>odbc_result($rs,"RASIO_LR6_2_4A"),
                            "RASIO_LR6_2_4B"=>odbc_result($rs,"RASIO_LR6_2_4B"),
                            "RASIO_LR6_3_1A"=>odbc_result($rs,"RASIO_LR6_3_1A"),
                            "RASIO_LR6_3_1B"=>odbc_result($rs,"RASIO_LR6_3_1B"),
                            "RASIO_LR6_3_2A"=>odbc_result($rs,"RASIO_LR6_3_2A"),
                            "RASIO_LR6_3_2B"=>odbc_result($rs,"RASIO_LR6_3_2B"),
                            "RASIO_LR6_3_3A"=>odbc_result($rs,"RASIO_LR6_3_3A"),
                            "RASIO_LR6_3_3B"=>odbc_result($rs,"RASIO_LR6_3_3B"),
                            "RASIO_LR6_3_4A"=>odbc_result($rs,"RASIO_LR6_3_4A"),
                            "RASIO_LR6_3_4B"=>odbc_result($rs,"RASIO_LR6_3_4B"),
                            "RASIO_LR6_4_1A"=>odbc_result($rs,"RASIO_LR6_4_1A"),
                            "RASIO_LR6_4_1B"=>odbc_result($rs,"RASIO_LR6_4_1B"),
                            "RASIO_LR6_4_2A"=>odbc_result($rs,"RASIO_LR6_4_2A"),
                            "RASIO_LR6_4_2B"=>odbc_result($rs,"RASIO_LR6_4_2B"),
                            "RASIO_LR6_4_3A"=>odbc_result($rs,"RASIO_LR6_4_3A"),
                            "RASIO_LR6_4_3B"=>odbc_result($rs,"RASIO_LR6_4_3B"),
                            "RASIO_LR6_4_3B"=>odbc_result($rs,"RASIO_LR6_4_3B"),
                            "RASIO_LR6_4_4A"=>odbc_result($rs,"RASIO_LR6_4_4A"),
                            "RASIO_LR6_4_4B"=>odbc_result($rs,"RASIO_LR6_4_4B"),
                            "RASIO_LR6_4_5A"=>odbc_result($rs,"RASIO_LR6_4_5A"),
                            "RASIO_LR6_4_5B"=>odbc_result($rs,"RASIO_LR6_4_5B")
                    );

    $response[] = array("LINE"=>odbc_result($rs,"LINE"),
                            "STOPS"=>foo(odbc_result($rs,"STOPS")),
                            "STARTS"=>foo(odbc_result($rs,"STARTS")),
                            "STS"=>odbc_result($rs,"STS"),
                            "WKT"=>odbc_result($rs,"WKT"),
                            "TGL"=>odbc_result($rs,"TGL"),
                            "RASIO"=>odbc_result($rs,"RASIO"), 
                            "RASIO1"=>odbc_result($rs,"RASIO1"),
                            "RASIO_LR03_1_1A"=>odbc_result($rs,"RASIO_LR03_1_1A"),
                            "RASIO_LR03_1_1B"=>odbc_result($rs,"RASIO_LR03_1_1B"),
                            "RASIO_LR03_1_2A"=>odbc_result($rs,"RASIO_LR03_1_2A"),
                            "RASIO_LR03_1_2B"=>odbc_result($rs,"RASIO_LR03_1_2B"),
                            "RASIO_LR03_1_3A"=>odbc_result($rs,"RASIO_LR03_1_3A"),
                            "RASIO_LR03_1_3B"=>odbc_result($rs,"RASIO_LR03_1_3B"),
                            "RASIO_LR03_1_4A"=>odbc_result($rs,"RASIO_LR03_1_4A"),
                            "RASIO_LR03_1_4B"=>odbc_result($rs,"RASIO_LR03_1_4B"),
                            "RASIO_LR03_1_5A"=>odbc_result($rs,"RASIO_LR03_1_5A"),
                            "RASIO_LR03_1_5B"=>odbc_result($rs,"RASIO_LR03_1_5B"),
                            "RASIO_LR6_1_1A"=>odbc_result($rs,"RASIO_LR6_1_1A"),
                            "RASIO_LR6_1_1B"=>odbc_result($rs,"RASIO_LR6_1_1B"),
                            "RASIO_LR6_1_2A"=>odbc_result($rs,"RASIO_LR6_1_2A"),
                            "RASIO_LR6_1_2B"=>odbc_result($rs,"RASIO_LR6_1_2B"),
                            "RASIO_LR6_1_3A"=>odbc_result($rs,"RASIO_LR6_1_3A"),
                            "RASIO_LR6_1_3B"=>odbc_result($rs,"RASIO_LR6_1_3B"),
                            "RASIO_LR6_1_4A"=>odbc_result($rs,"RASIO_LR6_1_4A"),
                            "RASIO_LR6_1_4B"=>odbc_result($rs,"RASIO_LR6_1_4B"),
                            "RASIO_LR6_2_1A"=>odbc_result($rs,"RASIO_LR6_2_1A"),
                            "RASIO_LR6_2_1B"=>odbc_result($rs,"RASIO_LR6_2_1B"),
                            "RASIO_LR6_2_2A"=>odbc_result($rs,"RASIO_LR6_2_2A"),
                            "RASIO_LR6_2_2B"=>odbc_result($rs,"RASIO_LR6_2_2B"),
                            "RASIO_LR6_2_3A"=>odbc_result($rs,"RASIO_LR6_2_3A"),
                            "RASIO_LR6_2_3B"=>odbc_result($rs,"RASIO_LR6_2_3B"),
                            "RASIO_LR6_2_4A"=>odbc_result($rs,"RASIO_LR6_2_4A"),
                            "RASIO_LR6_2_4B"=>odbc_result($rs,"RASIO_LR6_2_4B"),
                            "RASIO_LR6_3_1A"=>odbc_result($rs,"RASIO_LR6_3_1A"),
                            "RASIO_LR6_3_1B"=>odbc_result($rs,"RASIO_LR6_3_1B"),
                            "RASIO_LR6_3_2A"=>odbc_result($rs,"RASIO_LR6_3_2A"),
                            "RASIO_LR6_3_2B"=>odbc_result($rs,"RASIO_LR6_3_2B"),
                            "RASIO_LR6_3_3A"=>odbc_result($rs,"RASIO_LR6_3_3A"),
                            "RASIO_LR6_3_3B"=>odbc_result($rs,"RASIO_LR6_3_3B"),
                            "RASIO_LR6_3_4A"=>odbc_result($rs,"RASIO_LR6_3_4A"),
                            "RASIO_LR6_3_4B"=>odbc_result($rs,"RASIO_LR6_3_4B"),
                            "RASIO_LR6_4_1A"=>odbc_result($rs,"RASIO_LR6_4_1A"),
                            "RASIO_LR6_4_1B"=>odbc_result($rs,"RASIO_LR6_4_1B"),
                            "RASIO_LR6_4_2A"=>odbc_result($rs,"RASIO_LR6_4_2A"),
                            "RASIO_LR6_4_2B"=>odbc_result($rs,"RASIO_LR6_4_2B"),
                            "RASIO_LR6_4_3A"=>odbc_result($rs,"RASIO_LR6_4_3A"),
                            "RASIO_LR6_4_3B"=>odbc_result($rs,"RASIO_LR6_4_3B"),
                            "RASIO_LR6_4_3B"=>odbc_result($rs,"RASIO_LR6_4_3B"),
                            "RASIO_LR6_4_4A"=>odbc_result($rs,"RASIO_LR6_4_4A"),
                            "RASIO_LR6_4_4B"=>odbc_result($rs,"RASIO_LR6_4_4B"),
                            "RASIO_LR6_4_5A"=>odbc_result($rs,"RASIO_LR6_4_5A"),
                            "RASIO_LR6_4_5B"=>odbc_result($rs,"RASIO_LR6_4_5B")
                    );

    $fp = fopen('result.json', 'w');
    fwrite($fp, json_encode($response));
    fclose($fp);
   
$arrNo++;

}

echo json_encode($response);

function foo($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
?>
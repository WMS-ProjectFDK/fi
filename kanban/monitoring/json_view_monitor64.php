<?php
include("../../connect/conn3.php");
$sql = "
        select assylr64.dbo.cekjalan(getdate()) JALAN,ISNULL(cast(assylr64.dbo.cekrasio(getdate()) as decimal(18,2)),0) RASIO,assylr64.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr64.dbo.line_master where id_line = 18) STS_START
        ,(select status from assylr64.dbo.line_master where id_line = 15) STS_STOP,
        case when (select status from assylr64.dbo.line_master where id_line = 15) = 0 then assylr64.dbo.cekStop(getdate()) 
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 15 order by start desc) end STOPSx,
        case when (select status from assylr64.dbo.line_master where id_line = 1) = 0 then MOULD1 
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 1 order by start desc) end MOULD1,
        case when (select status from assylr64.dbo.line_master where id_line = 2) = 0 then MOULD2 
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 2 order by start desc) end MOULD2,
        case when (select status from assylr64.dbo.line_master where id_line = 3) = 0 then can_supply 
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line =3 order by start desc) end can_supply,

        case when (select status from assylr64.dbo.line_master where id_line = 4) = 0 then MIXINSERT
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 4 order by start desc) end  MIXINSERT,
        case when (select status from assylr64.dbo.line_master where id_line = 5) = 0 then BEADING
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 5 order by start desc) end  BEADING,
        case when (select status from assylr64.dbo.line_master where id_line = 6) = 0 then ADHESIVE1
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 6 order by start desc) end  ADHESIVE1,
        case when (select status from assylr64.dbo.line_master where id_line = 7) = 0 then ADHESIVE2
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 7 order by start desc) end  ADHESIVE2,
        case when (select status from assylr64.dbo.line_master where id_line =8) = 0 then SEPARATORINSERT1
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 8 order by start desc) end  SEPARATORINSERT1,
         case when (select status from assylr64.dbo.line_master where id_line =9) = 0 then SEPARATORINSERT2
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 9 order by start desc) end  SEPARATORINSERT2,
        case when (select status from assylr64.dbo.line_master where id_line = 10) = 0 then ELECFILLING
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 10 order by start desc) end  ELECFILLING,
        case when (select status from assylr64.dbo.line_master where id_line = 11) = 0 then TrayStock
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 11 order by start desc) end TrayStock,
        case when (select status from assylr64.dbo.line_master where id_line = 12) = 0 then Vacuum
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 12 order by start desc) end Vacuum,
        case when (select status from assylr64.dbo.line_master where id_line = 13) = 0 then AnodeGel
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 13 order by start desc) end AnodeGel,

        case when (select status from assylr64.dbo.line_master where id_line = 14) = 0 then CCAInsert
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 14 order by start desc) end CCAInsert,
        case when (select status from assylr64.dbo.line_master where id_line = 15) = 0 then STOP
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 15 order by start desc) end STOP,
        case when (select status from assylr64.dbo.line_master where id_line = 16) = 0 then WeightChck
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 16 order by start desc) end WeightChck,
        case when (select status from assylr64.dbo.line_master where id_line = 17) = 0 then CasingLoad
            else  (select top 1 detik from assylr64.dbo.line_report where  id_line = 17 order by start desc) end CasingLoad,
        (select status from assylr64.dbo.line_master where id_line = 1) MOULDING1STAT1,
        (select status from assylr64.dbo.line_master where id_line = 2) MOULDING2STAT2,
        (select status from assylr64.dbo.line_master where id_line = 3) CAN_SUPPLYSTAT,
        (select status from assylr64.dbo.line_master where id_line = 4) MIXINSERTSTAT,
        (select status from assylr64.dbo.line_master where id_line = 5) BEADINGSTAT,
        (select status from assylr64.dbo.line_master where id_line = 6) ADHESIVE1STAT,
         (select status from assylr64.dbo.line_master where id_line = 7) ADHESIVE2STAT,
        (select status from assylr64.dbo.line_master where id_line = 8) SEPARATORINSERT1STAT,
        (select status from assylr64.dbo.line_master where id_line = 9) SEPARATORINSERT2STAT,
        (select status from assylr64.dbo.line_master where id_line = 10) ELECFILLINGSTAT,
        (select status from assylr64.dbo.line_master where id_line = 13) ANODEGELSTAT,
        (select status from assylr64.dbo.line_master where id_line = 11) TRAYSTOCKSTAT,
        (select status from assylr64.dbo.line_master where id_line = 12) VACUUMSTAT,
        (select status from assylr64.dbo.line_master where id_line = 14) CCAINSERTSTAT,
        (select status from assylr64.dbo.line_master where id_line = 16) WEIGHTCHCKSTAT,
        (select status from assylr64.dbo.line_master where id_line = 17) CASINGLOADSTAT,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),105)) TGL,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),108)) WKT,
        MOULD1 MOULD12,
        MOULD2 MOULD22,
        CAN_SUPPLY  CAN_SUPPLY2,
        MIXINSERT   MIXINSERT2,
        BEADING  BEADING2,
        ADHESIVE1  ADHESIVE12,
        ADHESIVE2  ADHESIVE22,
        SEPARATORINSERT1  SEPARATORINSERT12,
        SEPARATORINSERT2  SEPARATORINSERT22,
        ELECFILLING ELECFILLING2,
        ANODEGEL  ANODEGEL2,
        TRAYSTOCK TRAYSTOCK2,
        WeightChck WCHECKER2,
        CCAINSERT CCAINSERT2,
        CasingLoad CasingLoad2,
        Vacuum VACUUM2
        from assylr64.dbo.Line_Time";
$rs=odbc_exec($con64,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
   
   $arrData[$arrNo] = array("JALAN"=>odbc_result($rs,"JALAN"),
                             "STOPS"=>odbc_result($rs,"STOPS"),
                             "STS_START"=>odbc_result($rs,"STS_START"),
                              "STOPSx"=>odbc_result($rs,"STOPSx"),
                             "STS_STOP"=>odbc_result($rs,"STS_STOP"),
                             "RASIO"=>odbc_result($rs,"RASIO"),
                             "MOULD1"=>odbc_result($rs,"MOULD1"),
                             "MOULD2"=>odbc_result($rs,"MOULD2"),
                             "CAN_SUPPLY"=>odbc_result($rs,"CAN_SUPPLY"),
                             "MIXINSERT"=>odbc_result($rs,"MIXINSERT"),
                             "BEADING"=>odbc_result($rs,"BEADING"),
                             "ADHESIVE1"=>odbc_result($rs,"ADHESIVE1"),
                             "ADHESIVE2"=>odbc_result($rs,"ADHESIVE2"),
                             "SEPARATORINSERT1"=>odbc_result($rs,"SEPARATORINSERT1"),
                             "SEPARATORINSERT2"=>odbc_result($rs,"SEPARATORINSERT2"),
                             "ELECFILLING"=>odbc_result($rs,"ELECFILLING"), 

                             "VACUUM"=>odbc_result($rs,"VACUUM"),
                           
                             "CCAINSERT"=>odbc_result($rs,"CCAINSERT"),
                             "ANODEGEL"=>odbc_result($rs,"ANODEGEL"),
                             "TRAYSTOCK"=>odbc_result($rs,"TRAYSTOCK"),
                             "WEIGHTCHCK"=>odbc_result($rs,"WEIGHTCHCK"),
                             "CASINGLOAD"=>odbc_result($rs,"CASINGLOAD"),


                             "MOULD12"=>odbc_result($rs,"MOULD12"),
                             "MOULD22"=>odbc_result($rs,"MOULD22"),
                             "CAN_SUPPLY2"=>odbc_result($rs,"CAN_SUPPLY2"),
                             "MIXINSERT2"=>odbc_result($rs,"MIXINSERT2"),
                             "BEADING2"=>odbc_result($rs,"BEADING2"),
                             "ADHESIVE12"=>odbc_result($rs,"ADHESIVE12"),
                              "ADHESIVE22"=>odbc_result($rs,"ADHESIVE22"),
                             "SEPARATORINSERT12"=>odbc_result($rs,"SEPARATORINSERT12"),
                             "SEPARATORINSERT22"=>odbc_result($rs,"SEPARATORINSERT22"),
                             "ELECFILLING2"=>odbc_result($rs,"ELECFILLING2"),
                             "ANODEGEL2"=>odbc_result($rs,"ANODEGEL2"),
                             "TRAYSTOCK2"=>odbc_result($rs,"TRAYSTOCK2"),
                             "WCHECKER2"=>odbc_result($rs,"WCHECKER2"),
                            
                             "CASINGLOAD2"=>odbc_result($rs,"CASINGLOAD2"),
                            
                             "VACUUM2"=>odbc_result($rs,"VACUUM2"),
                             "CCAINSERT2"=>odbc_result($rs,"CCAINSERT2"),


                             "TGL"=>odbc_result($rs,"TGL"),
                             "WKT"=>odbc_result($rs,"WKT"),


                             "MOULDING1STAT1"=>odbc_result($rs,"MOULDING1STAT1"),
                              "MOULDING2STAT2"=>odbc_result($rs,"MOULDING2STAT2"),

                             "CAN_SUPPLYSTAT"=>odbc_result($rs,"CAN_SUPPLYSTAT"),
                             "MIXINSERTSTAT"=>odbc_result($rs,"MIXINSERTSTAT"),
                             "BEADINGSTAT"=>odbc_result($rs,"BEADINGSTAT"),
                             "ADHESIVE1STAT"=>odbc_result($rs,"ADHESIVE1STAT"),
                             "SEPARATORINSERT1STAT"=>odbc_result($rs,"SEPARATORINSERT1STAT"),

                              "ADHESIVE2STAT"=>odbc_result($rs,"ADHESIVE2STAT"),
                             "SEPARATORINSERT2STAT"=>odbc_result($rs,"SEPARATORINSERT2STAT"),
                             "ELECFILLINGSTAT"=>odbc_result($rs,"ELECFILLINGSTAT"),
                             "ANODEGELSTAT"=>odbc_result($rs,"ANODEGELSTAT"),
                             "TRAYSTOCKSTAT"=>odbc_result($rs,"TRAYSTOCKSTAT"),
                            
                             "CASINGLOADSTAT"=>odbc_result($rs,"CASINGLOADSTAT"),
                             "WEIGHTCHCKSTAT"=>odbc_result($rs,"WEIGHTCHCKSTAT"),
                              "VACUUMSTAT"=>odbc_result($rs,"VACUUMSTAT"),
                             "CCAINSERTSTAT"=>odbc_result($rs,"CCAINSERTSTAT")
                    );
   
$arrNo++;

}

$SUBJECT = $items[$rowno]->STOCK_SUBJECT;
        $items[$rowno]->STOCK_SUBJECT = str_replace(' ', '<br/>', strtoupper($SUBJECT));
echo json_encode($arrData);
?>
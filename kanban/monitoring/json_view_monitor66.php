<?php
include("../../connect/conn3.php");
$sql = "
 select assylr66.dbo.cekjalan(getdate()) JALAN,ISNULL(cast(assylr66.dbo.cekrasio(getdate()) as decimal(18,2)),0) RASIO,assylr66.dbo.cekStop(getdate())  STOPS
        ,(select status from assylr66.dbo.line_master where id_line = 13) STS_START
        ,(select status from assylr66.dbo.line_master where id_line = 9) STS_STOP,
        case when (select status from assylr66.dbo.line_master where id_line = 9) = 0 then assylr66.dbo.cekStop(getdate()) 
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 9 order by start desc) end STOPSx,
        case when (select status from assylr66.dbo.line_master where id_line = 1) = 0 then MOULD1 
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 1 order by start desc) end MOULD1,
        case when (select status from assylr66.dbo.line_master where id_line = 2) = 0 then MOULD2
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 2 order by start desc) end MOULD2,
        case when (select status from assylr66.dbo.line_master where id_line = 3) = 0 then MIXINSERT
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 3 order by start desc) end  MIXINSERT,
        case when (select status from assylr66.dbo.line_master where id_line = 4) = 0 then BEADING
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 4 order by start desc) end  BEADING,
        case when (select status from assylr66.dbo.line_master where id_line = 5) = 0 then ADHESIVE
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 5 order by start desc) end  ADHESIVE,
        case when (select status from assylr66.dbo.line_master where id_line = 6) = 0 then ESFilling
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 6 order by start desc) end  ESFilling,
        case when (select status from assylr66.dbo.line_master where id_line = 7) = 0 then jigStock
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 7 order by start desc) end jigStock,
        case when (select status from assylr66.dbo.line_master where id_line = 8) = 0 then gelDraw
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 8 order by start desc) end gelDraw,
        case when (select status from assylr66.dbo.line_master where id_line = 9) = 0 then Stop
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 9 order by start desc) end Stop,
        case when (select status from assylr66.dbo.line_master where id_line = 10) = 0 then WeightChk
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 10 order by start desc) end WeightChk,
        case when (select status from assylr66.dbo.line_master where id_line = 11) = 0 then CasingLoad
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 11 order by start desc) end CasingLoad,
        case when (select status from assylr66.dbo.line_master where id_line = 12) = 0 then Palleting
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 12 order by start desc) end Palleting,
        case when (select status from assylr66.dbo.line_master where id_line = 13) = 0 then Start
            else  (select top 1 detik from assylr66.dbo.line_report where  id_line = 13 order by start desc) end Start,
        (select status from assylr66.dbo.line_master where id_line = 1) MOULDINGSTAT1,
        (select status from assylr66.dbo.line_master where id_line = 2) MOULDINGSTAT2,
        (select status from assylr66.dbo.line_master where id_line = 3) MIXINSERTSTAT,
        (select status from assylr66.dbo.line_master where id_line = 4) BEADINGSTAT,
        (select status from assylr66.dbo.line_master where id_line = 5) ADHESIVESTAT,
        (select status from assylr66.dbo.line_master where id_line = 6) ESFILLINGSTAT,
        (select status from assylr66.dbo.line_master where id_line = 7) JIGSTOCKSTAT,
        (select status from assylr66.dbo.line_master where id_line = 8) GELDRAWSTAT,
        (select status from assylr66.dbo.line_master where id_line = 9) STOPSTAT, 
        (select status from assylr66.dbo.line_master where id_line = 10) WEIGHTCHKSTAT,
        (select status from assylr66.dbo.line_master where id_line = 11) CASINGLOADSTAT,
        (select status from assylr66.dbo.line_master where id_line = 12) PALLETINGSTAT,
        (select status from assylr66.dbo.line_master where id_line = 13) STARTSTAT,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),105)) TGL,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),108)) WKT
        from assylr66.dbo.Line_Time";
$rs=odbc_exec($con66,$sql);
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
                            "MIXINSERT"=>odbc_result($rs,"MIXINSERT"),
                            "BEADING"=>odbc_result($rs,"BEADING"),
                            "ADHESIVE"=>odbc_result($rs,"ADHESIVE"),
                            "ESFILLING"=>odbc_result($rs,"ESFILLING"), 
                            "JIGSTOCK"=>odbc_result($rs,"jigStock"),
                            "GELDRAW"=>odbc_result($rs,"GELDRAW"),
                            "WEIGHTCHK"=>odbc_result($rs,"WeightChk"),
                            "CASINGLOAD"=>odbc_result($rs,"CASINGLOAD"),
                            "PALLETING"=>odbc_result($rs,"Palleting"),
                            
                             //"CCAINSERT"=>odbc_result($rs,"CCAINSERT"),
                             //"ANODEGEL"=>odbc_result($rs,"ANODEGEL"),
                             //"TRAYSTOCK"=>odbc_result($rs,"TRAYSTOCK"),
                            
                             //"CASINGLOAD"=>odbc_result($rs,"CASINGLOAD"),


                             //"MOULD2"=>odbc_result($rs,"MOULD2"),
                             //"MIXINSERT2"=>odbc_result($rs,"MIXINSERT2"),
                             //"BEADING2"=>odbc_result($rs,"BEADING2"),
                             
                             //"SEPARATORINSERT2"=>odbc_result($rs,"SEPARATORINSERT2"),
                             //"ELECFILLING2"=>odbc_result($rs,"ELECFILLING2"),
                             //"ANODEGEL2"=>odbc_result($rs,"ANODEGEL2"),
                             //"TRAYSTOCK2"=>odbc_result($rs,"TRAYSTOCK2"),
                            
                            
                            
                             //"UNCASER2"=>odbc_result($rs,"UNCASER2"),
                             //"VACUUM2"=>odbc_result($rs,"VACUUM2"),
                             //"CCAINSERT2"=>odbc_result($rs,"CCAINSERT2"),


                            "TGL"=>odbc_result($rs,"TGL"),
                            "WKT"=>odbc_result($rs,"WKT"),


                            "MOULDINGSTAT1"=>odbc_result($rs,"MOULDINGSTAT1"),
                            "MOULDINGSTAT2"=>odbc_result($rs,"MOULDINGSTAT2"),
                            "MIXINSERTSTAT"=>odbc_result($rs,"MIXINSERTSTAT"),
                            "BEADINGSTAT"=>odbc_result($rs,"BEADINGSTAT"),
                            "ADHESIVESTAT"=>odbc_result($rs,"ADHESIVESTAT"),
                            "JIGSTOCKSTAT"=>odbc_result($rs,"JIGSTOCKSTAT"),
                            "ESFILLINGSTAT"=>odbc_result($rs,"ESFILLINGSTAT"),
                            "GELDRAWSTAT"=>odbc_result($rs,"GELDRAWSTAT"),
                            "WEIGHTCHKSTAT"=>odbc_result($rs,"WEIGHTCHKSTAT"),
                            "CASINGLOADSTAT"=>odbc_result($rs,"CASINGLOADSTAT"),
                            "PALLETINGSTAT"=>odbc_result($rs,"PALLETINGSTAT")
                    );
   
$arrNo++;

}

$SUBJECT = $items[$rowno]->STOCK_SUBJECT;
        $items[$rowno]->STOCK_SUBJECT = str_replace(' ', '<br/>', strtoupper($SUBJECT));
echo json_encode($arrData);
?>
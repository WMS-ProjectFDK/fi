<?php
include("../../connect/conn3.php");
$sql = "select dbo.cekjalan(getdate()) JALAN,ISNULL(cast(dbo.cekrasio(getdate()) as decimal(18,2)),0) RASIO,dbo.cekStop(getdate())  STOPS
        ,(select status from line_master where id_line = 17) STS_START
        ,(select status from line_master where id_line = 15) STS_STOP,
        case when (select status from line_master where id_line = 15) = 0 then dbo.cekStop(getdate()) 
            else  (select top 1 detik from line_report where  id_line = 15 order by start desc) end STOPSx,
        case when (select status from line_master where id_line = 1) = 0 then MOULD1 
            else  (select top 1 detik from line_report where  id_line = 1 order by start desc) end MOULD1,
        case when (select status from line_master where id_line = 2) = 0 then MOULD2 
            else  (select top 1 detik from line_report where  id_line = 2 order by start desc) end MOULD2,

        case when (select status from line_master where id_line = 3) = 0 then MIXINSERT
            else  (select top 1 detik from line_report where  id_line = 3 order by start desc) end  MIXINSERT,
        case when (select status from line_master where id_line = 4) = 0 then BEADING
            else  (select top 1 detik from line_report where  id_line = 4 order by start desc) end  BEADING,
        case when (select status from line_master where id_line = 5) = 0 then ADHESIVE
            else  (select top 1 detik from line_report where  id_line = 5 order by start desc) end  ADHESIVE,

        case when (select status from line_master where id_line =6) = 0 then SEPARATORINSERT
            else  (select top 1 detik from line_report where  id_line = 6 order by start desc) end  SEPARATORINSERT,
        case when (select status from line_master where id_line = 7) = 0 then ELECFILLING
            else  (select top 1 detik from line_report where  id_line = 7 order by start desc) end  ELECFILLING,
        case when (select status from line_master where id_line = 8) = 0 then JIGSTOCK
            else  (select top 1 detik from line_report where  id_line = 8 order by start desc) end JIGSTOCK,
        case when (select status from line_master where id_line = 9) = 0 then Vacuum
            else  (select top 1 detik from line_report where  id_line = 9 order by start desc) end Vacuum,
        case when (select status from line_master where id_line = 10) = 0 then AnodeGel
            else  (select top 1 detik from line_report where  id_line = 10 order by start desc) end AnodeGel,
        case when (select status from line_master where id_line = 11) = 0 then Uncaser
            else  (select top 1 detik from line_report where  id_line = 11 order by start desc) end Uncaser,
        case when (select status from line_master where id_line = 12) = 0 then CCAInsert
            else  (select top 1 detik from line_report where  id_line = 12 order by start desc) end CCAInsert,
        case when (select status from line_master where id_line = 13) = 0 then Curling
            else  (select top 1 detik from line_report where  id_line = 13 order by start desc) end Curling,
        case when (select status from line_master where id_line = 14) = 0 then WeightChck
            else  (select top 1 detik from line_report where  id_line = 14 order by start desc) end WeightChck,
        case when (select status from line_master where id_line = 16) = 0 then CasingLoad
            else  (select top 1 detik from line_report where  id_line = 16 order by start desc) end CasingLoad,
        (select status from line_master where id_line = 1) moulding1stat,
        (select status from line_master where id_line = 2) moulding2stat,
        (select status from line_master where id_line = 3) MixInsertstat,
        (select status from line_master where id_line = 4) beadingstat,
        (select status from line_master where id_line = 5) adhesivestat,
        (select status from line_master where id_line = 6) separatorstat,
        (select status from line_master where id_line = 7) esfillingstat,
        (select status from line_master where id_line = 10) anodegelstat,
        (select status from line_master where id_line = 8) jigstockstat,
        (select status from line_master where id_line = 9) vacuumstat,
        (select status from line_master where id_line = 11) uncaserstat,
        (select status from line_master where id_line = 12) ccainsertstat,
        (select status from line_master where id_line = 13) curlingstat,
        (select status from line_master where id_line = 14) weightcheckstat,
        (select status from line_master where id_line = 16) casingloaderstat,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),105)) TGL,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),108)) WKT,
        MOULD1 MOULD12,
        MOULD2  MOULD22,
        MIXINSERT   MIXINSERT2,
        BEADING  BEADING2,
        ADHESIVE  ADHESIVE2,
        SEPARATORINSERT  SEPARATORINSERT2,
        ELECFILLING ELECFILLING2,
        ANODEGEL  ANODEGEL2,
        JIGSTOCK JIGSTOCK2,
        WeightChck WCHECKER2,
        Curling CURLINGDRAW2,
        CasingLoad CASINGLOADER2,
        Uncaser UNCASER2,
        Vacuum VACUUM2,
        CCAInsert CCAINSERT2
        from Line_Time";
$rs=odbc_exec($con31,$sql);
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
                             "SEPARATORINSERT"=>odbc_result($rs,"SEPARATORINSERT"),
                             "ELECFILLING"=>odbc_result($rs,"ELECFILLING"), 
                             "VACUUM"=>odbc_result($rs,"VACUUM"),
                             "UNCASER"=>odbc_result($rs,"UNCASER"),
                             "CCAINSERT"=>odbc_result($rs,"CCAINSERT"),
                             "ANODEGEL"=>odbc_result($rs,"ANODEGEL"),
                             "JIGSTOCK"=>odbc_result($rs,"JIGSTOCK"),
                             "WEIGHTCHCK"=>odbc_result($rs,"WEIGHTCHCK"),
                             "CURLING"=>odbc_result($rs,"CURLING"),
                             "CASINGLOAD"=>odbc_result($rs,"CASINGLOAD"),


                             "MOULD12"=>odbc_result($rs,"MOULD12"),
                             "MOULD22"=>odbc_result($rs,"MOULD22"),
                             "MIXINSERT2"=>odbc_result($rs,"MIXINSERT2"),
                             "BEADING2"=>odbc_result($rs,"BEADING2"),
                             "ADHESIVE2"=>odbc_result($rs,"ADHESIVE2"),
                             "SEPARATORINSERT2"=>odbc_result($rs,"SEPARATORINSERT2"),
                             "ELECFILLING2"=>odbc_result($rs,"ELECFILLING2"),
                             "ANODEGEL2"=>odbc_result($rs,"ANODEGEL2"),
                             "JIGSTOCK2"=>odbc_result($rs,"JIGSTOCK2"),
                             "WCHECKER2"=>odbc_result($rs,"WCHECKER2"),
                             "CURLINGDRAW2"=>odbc_result($rs,"CURLINGDRAW2"),
                             "CASINGLOADER2"=>odbc_result($rs,"CASINGLOADER2"),
                             "UNCASER2"=>odbc_result($rs,"UNCASER2"),
                             "VACUUM2"=>odbc_result($rs,"VACUUM2"),
                             "CCAINSERT2"=>odbc_result($rs,"CCAINSERT2"),


                             "TGL"=>odbc_result($rs,"TGL"),
                             "WKT"=>odbc_result($rs,"WKT"),


                             "moulding1stat"=>odbc_result($rs,"moulding1stat"),

                             "moulding2stat"=>odbc_result($rs,"moulding2stat"),
                             "MixInsertstat"=>odbc_result($rs,"MixInsertstat"),
                             "beadingstat"=>odbc_result($rs,"beadingstat"),
                             "adhesivestat"=>odbc_result($rs,"adhesivestat"),
                             "separatorstat"=>odbc_result($rs,"separatorstat"),
                             "esfillingstat"=>odbc_result($rs,"esfillingstat"),
                             "anodegelstat"=>odbc_result($rs,"anodegelstat"),
                             "jigstockstat"=>odbc_result($rs,"jigstockstat"),
                             "CURLINGSTAT"=>odbc_result($rs,"CURLINGSTAT"),
                             "casingloaderstat"=>odbc_result($rs,"casingloaderstat"),
                             "weightcheckstat"=>odbc_result($rs,"weightcheckstat"),
                              "VACUUMSTAT"=>odbc_result($rs,"VACUUMSTAT"),
                             "UNCASERSTAT"=>odbc_result($rs,"UNCASERSTAT"),
                             "CCAINSERTSTAT"=>odbc_result($rs,"CCAINSERTSTAT")
                    );
   
$arrNo++;

}

$SUBJECT = $items[$rowno]->STOCK_SUBJECT;
        $items[$rowno]->STOCK_SUBJECT = str_replace(' ', '<br/>', strtoupper($SUBJECT));
echo json_encode($arrData);
?>
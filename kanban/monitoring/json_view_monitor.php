<?php
include("../../connect/conn3.php");
$sql = "select dbo.cekjalan(getdate()) JALAN,cast(dbo.cekrasio(getdate()) as decimal(18,2)) RASIO,dbo.cekStop(getdate())  STOPS
		,(select status from line_master where id_line = 12) STS_START
		,(select status from line_master where id_line = 11) STS_STOP,
        case when (select status from line_master where id_line = 11) = 0 then dbo.cekStop(getdate()) 
            else  (select top 1 detik from line_report where  id_line = 11 order by start desc) end STOPSx,
		case when (select status from line_master where id_line = 1) = 0 then MOULD 
            else  (select top 1 detik from line_report where  id_line = 1 order by start desc) end MOULD,
		case when (select status from line_master where id_line = 2) = 0 then MIXINSERT
            else  (select top 1 detik from line_report where  id_line = 2 order by start desc) end  MIXINSERT,
		case when (select status from line_master where id_line = 3) = 0 then BEADING
            else  (select top 1 detik from line_report where  id_line = 3 order by start desc) end  BEADING,
		case when (select status from line_master where id_line = 4) = 0 then ADHESIVE
            else  (select top 1 detik from line_report where  id_line = 4 order by start desc) end  ADHESIVE,
		case when (select status from line_master where id_line = 5) = 0 then SEPARATORINSERT
            else  (select top 1 detik from line_report where  id_line = 5 order by start desc) end  SEPARATORINSERT,
		case when (select status from line_master where id_line = 6) = 0 then ELECFILLING
            else  (select top 1 detik from line_report where  id_line = 6 order by start desc) end  ELECFILLING,
		case when (select status from line_master where id_line = 7) = 0 then ANODEGEL  
            else  (select top 1 detik from line_report where  id_line = 7 order by start desc) end ANODEGEL,
		case when (select status from line_master where id_line = 8) = 0 then JIGSTOCK
            else  (select top 1 detik from line_report where  id_line = 8 order by start desc) end JIGSTOCK,
		case when (select status from line_master where id_line = 9) = 0 then WCHECKER
            else  (select top 1 detik from line_report where  id_line = 9 order by start desc) end WCHECKER,
		case when (select status from line_master where id_line = 10) = 0 then CURLINGDRAW
            else  (select top 1 detik from line_report where  id_line = 10 order by start desc) end CURLINGDRAW,
		case when (select status from line_master where id_line = 13) = 0 then CASINGLOADER
            else  (select top 1 detik from line_report where  id_line = 13 order by start desc) end CASINGLOADER,
		case when (select status from line_master where id_line = 14) = 0 then CANSUPPLY
            else  (select top 1 detik from line_report where  id_line = 14 order by start desc) end CANSUPPLY,
        (select status from line_master where id_line = 1) mouldingstat,
        (select status from line_master where id_line = 2) MixInsertstat,
        (select status from line_master where id_line = 3) beadingstat,
        (select status from line_master where id_line = 4) adhesivestat,
        (select status from line_master where id_line = 5) separatorstat,
        (select status from line_master where id_line = 6) esfillingstat,
        (select status from line_master where id_line = 7) anodegelstat,
        (select status from line_master where id_line = 8) jigstockstat,
        (select status from line_master where id_line = 9) weighcheckstat,
        (select status from line_master where id_line = 10) curdrawstat,
        (select status from line_master where id_line = 13) casingloadstat,
        (select status from line_master where id_line = 14) cansupplystat,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),105)) TGL,
        (SELECT CONVERT(VARCHAR(20),GETDATE(),108)) WKT,
        MOULD  MOULD2,
        MIXINSERT   MIXINSERT2,
        BEADING  BEADING2,
        ADHESIVE  ADHESIVE2,
        SEPARATORINSERT  SEPARATORINSERT2,
        ELECFILLING ELECFILLING2,
        ANODEGEL  ANODEGEL2,
        JIGSTOCK JIGSTOCK2,
        WCHECKER WCHECKER2,
         CURLINGDRAW CURLINGDRAW2,
         CASINGLOADER CASINGLOADER2,
         CANSUPPLY CANSUPPLY2
        from Line_Time";
$rs=odbc_exec($con32,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
   
   $arrData[$arrNo] = array("JALAN"=>odbc_result($rs,"JALAN"),
                             "STOPS"=>odbc_result($rs,"STOPS"),
                             "STS_START"=>odbc_result($rs,"STS_START"),
                              "STOPSx"=>odbc_result($rs,"STOPSx"),
                             "STS_STOP"=>odbc_result($rs,"STS_STOP"),
                             "RASIO"=>odbc_result($rs,"RASIO"),
                             "MOULD"=>odbc_result($rs,"MOULD"),
                             "MIXINSERT"=>odbc_result($rs,"MIXINSERT"),
                             "BEADING"=>odbc_result($rs,"BEADING"),
                             "ADHESIVE"=>odbc_result($rs,"ADHESIVE"),
                             "SEPARATORINSERT"=>odbc_result($rs,"SEPARATORINSERT"),
                             "ELECFILLING"=>odbc_result($rs,"ELECFILLING"),
                             "ANODEGEL"=>odbc_result($rs,"ANODEGEL"),
                             "JIGSTOCK"=>odbc_result($rs,"JIGSTOCK"),
                             "WCHECKER"=>odbc_result($rs,"WCHECKER"),
                             "CURLINGDRAW"=>odbc_result($rs,"CURLINGDRAW"),
                             "CANSUPPLY"=>odbc_result($rs,"CANSUPPLY"),
                             "CASINGLOADER"=>odbc_result($rs,"CASINGLOADER"),


                             "MOULD2"=>odbc_result($rs,"MOULD2"),
                             "MIXINSERT2"=>odbc_result($rs,"MIXINSERT2"),
                             "BEADING2"=>odbc_result($rs,"BEADING2"),
                             "ADHESIVE2"=>odbc_result($rs,"ADHESIVE2"),
                             "SEPARATORINSERT2"=>odbc_result($rs,"SEPARATORINSERT2"),
                             "ELECFILLING2"=>odbc_result($rs,"ELECFILLING2"),
                             "ANODEGEL2"=>odbc_result($rs,"ANODEGEL2"),
                             "JIGSTOCK2"=>odbc_result($rs,"JIGSTOCK2"),
                             "WCHECKER2"=>odbc_result($rs,"WCHECKER2"),
                             "CURLINGDRAW2"=>odbc_result($rs,"CURLINGDRAW2"),
                             "CANSUPPLY2"=>odbc_result($rs,"CANSUPPLY2"),
                             "CASINGLOADER2"=>odbc_result($rs,"CASINGLOADER2"),



                             "TGL"=>odbc_result($rs,"TGL"),
                             "WKT"=>odbc_result($rs,"WKT"),


                             "mouldingstat"=>odbc_result($rs,"mouldingstat"),
                             "MixInsertstat"=>odbc_result($rs,"MixInsertstat"),
                             "beadingstat"=>odbc_result($rs,"beadingstat"),
                             "adhesivestat"=>odbc_result($rs,"adhesivestat"),
                             "separatorstat"=>odbc_result($rs,"separatorstat"),
                             "esfillingstat"=>odbc_result($rs,"esfillingstat"),
                             "anodegelstat"=>odbc_result($rs,"anodegelstat"),
                             "jigstockstat"=>odbc_result($rs,"jigstockstat"),
                             "curdrawstat"=>odbc_result($rs,"curdrawstat"),
                             "casingloadstat"=>odbc_result($rs,"casingloadstat"),
                             "cansupplystat"=>odbc_result($rs,"cansupplystat"),
                            "weighcheckstat"=>odbc_result($rs,"weighcheckstat")
                    );
   
$arrNo++;

}

$SUBJECT = $items[$rowno]->STOCK_SUBJECT;
        $items[$rowno]->STOCK_SUBJECT = str_replace(' ', '<br/>', strtoupper($SUBJECT));
echo json_encode($arrData);
?>
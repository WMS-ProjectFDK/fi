sub do_sql{

        local ($sql) = @_ ;

        if ($sql eq ""){

           &err("in sub do_sql: No sql");

        } else {

           $cur = $dbh->prepare($sql)
                 || &err("SQL error : $DBI::err .... $DBI::errstr") ;
           $cur->execute()
                 || &err("SQL execute error : $DBI::err .... $DBI::errstr") ;
        }
}
sub make_desc{
@desc = ( ["ITEM_NO",		"N",8,"PARTS No."],
		  ["ORIGIN_CODE",	"N",3,"ORIGIN"],
		  ["DESCRIPTION",	"X",30,"DESCRPITION"],
		  ["CLASS_CODE",	"N",4,"GROUP"],
		  ["UOM_Q",			"N",3,"UOM Q"],
		  ["UOM_W",			"N",3,"UOM W"],
		  ["UOM_L",			"N",3,"UOM L"],
		  ["ITEM_NO_OLD",	"X",8,"PARTS No. OLD"],
		  ["SUPPLIER_CODE",	"N",8,"SUPPLIER"],
		  ["PACKET_UNIT",	"X",7,"PACKET UNIT"],
		  ["EXTERNAL_UNIT_NUMBER","N",5,"EXTERNAL UNIT NUMBER"],
		  ["UPTO_DATE",		"D",0],
		  ["REG_DATE",		"D",0]	);

}
1;

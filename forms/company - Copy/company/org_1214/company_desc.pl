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
@desc = ( # ["DELETE_TYPE",	"X",1,"DELETE TYPE"],
		  ["COMPANY_CODE",	"N",8,"CODE"],
		  ["COMPANY_TYPE",	"N",2,"COMPANY TYPE","COMPANY"],
		  ["COMPANY",		"X",40,"NAME",		"COMPANY"],
		  ["ADDRESS1",		"X",40,"ADDRESS 1",	"COMPANY"],
		  ["ADDRESS2",		"X",40,"ADDRESS 2",	"COMPANY"],
		  ["ADDRESS3",		"X",40,"ADDRESS 3",	"COMPANY"],
		  ["ADDRESS4",		"X",40,"ADDRESS 4",	"COMPANY"],
		  ["ATTN",			"X",40,"ATTN",		"COMPANY"],
		  ["COUNTRY_CODE",	"N",3, "COUNTRY",	"COMPANY"],
	      ["CURR_CODE",		"N",3, "CURRENCY"],
		  ["TTERM",			"X",5,"TRADE TERMS"],
		  ["PDAYS",			"N",3,"PAYMENT"],
		  ["PDESC",			"X",40,"PAYMENT"],
#		  ["CRLMT",			"N",3,"CRLMT"],
		  ["INIT",			"X",3,"INITIAL","COMPANY"],
		  ["COMPANY_CODE_OLD","X",8,"CODE (OLD)","COMPANY"],
		  ["EDI_CODE",		"X",12,"EDI CODE","COMPANY"],
		  ["LOADING_PORT",	"X",25,"LOADING PORT","CONTRACT"],
		  ["DISCHARGE_PORT","X",25,"PORT OF DISCHARGE","CONTRACT"],
		  ["FINAL_DEST",	"X",25,"FINAL DESTINATION","CONTRACT"],
		  ["CONSIGNEE_CODE","N",8,"CONSIGNEE","CONTRACT"],
#		  ["NOTIFY_CODE",	"N",8,"NOTIFY"],
		  ["MARKS",			"X",300,"MARKS","CONTRACT"],
		  ["UPTO_DATE",		"D",0,"UPTO DATE"],
		  ["REG_DATE",		"D",0,"REG DATE"]	);

}
1;

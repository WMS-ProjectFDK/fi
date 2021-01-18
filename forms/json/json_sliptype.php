<?
session_start();
$user_name = $_SESSION['id_wms'];

if($user_name == 'FI0122'){
?>

[
{"id": "01", "name": "[01] PURCHASE (Import) COMMERCIAL"},
{"id": "02", "name": "[02] PURCHASE (Import) NO-COMMER."},
{"id": "03", "name": "[03] INVOICE QUANTITY LACK"},
{"id": "04", "name": "[04] INVOICE QUANTITY EXCESS"},
{"id": "05", "name": "[05] OTHER RECEIVE"},
{"id": "10", "name": "[10] INSPECTION"},
{"id": "11", "name": "[11] PURCHASE (Local) COMMERCIAL"},
{"id": "12", "name": "[12] PURCHASE (Local) NO-COMMER."},
{"id": "20", "name": "[20] RETURN"},
{"id": "21", "name": "[21] ISSUE TO MANUFACT IN FACTORY"},
{"id": "25", "name": "[25] OTHER ISSUE"},
{"id": "26", "name": "[26] ISSUE FROM YUSEN TO FI(WH)"},
{"id": "30", "name": "[30] ISSUE MATERIAL COMMERCIAL"},
{"id": "31", "name": "[31] ISSUE TO SUBCONTRACT (PAY)"},
{"id": "32", "name": "[32] ISSUE TO SUBCONTRACT (NO PAY)"},
{"id": "41", "name": "[41] PURCHASE RETURN (PAY)"},
{"id": "42", "name": "[42] PURCHASE RETURN (NO PAY)"},
{"id": "51", "name": "[51] INTERNAL SALES"},
{"id": "52", "name": "[52] SALES PROMOTION COSTS"},
{"id": "61", "name": "[61] ISSUE RETURN SUBCON. (PAY)"},
{"id": "62", "name": "[62] ISSUE RETURN SUBCON. (NO PAY)"},
{"id": "71", "name": "[71] BAD DUMP"},
{"id": "80", "name": "[80] PRODUCTION INCOME/ (KURAIRE)"},
{"id": "81", "name": "[81] SUBCONTRACT FINISHED GOODS"},
{"id": "82", "name": "[82] SECTION TRANSFER RECEIVE"},
{"id": "83", "name": "[83] OTHER RECEIVE"},
{"id": "84", "name": "[84] SHIPPING (EXPORT) COMMERCIAL"},
{"id": "85", "name": "[85] SHIPPING (LOCAL) COMMERCIAL"},
{"id": "86", "name": "[86] SECTION TRANSFER ISSUE"},
{"id": "87", "name": "[87] ISSUE (KURADASHI)"},
{"id": "88", "name": "[88] OTHER ISSUE"},
{"id": "89", "name": "[89] DISPOSAL"},
{"id": "90", "name": "[90] CONSUME BY STOCK TAKE"},
{"id": "91", "name": "[91] SOLD OUT"},
{"id": "92", "name": "[92] Sub Assy RECEIVE&ISSUE TO PROD"},
{"id": "93", "name": "[93] RECEIVE"},
{"id": "95", "name": "[95] Issue Changing Status "},
{"id": "96", "name": "[96] Receive Changing Status"},
{"id": "98", "name": "[98] DISPOSAL"},
{"id": "99", "name": "[99] ISSUE (STOCK TAKE SHORTAGE)"}
]

<?php 
}else{
?>

[
{"id": "05", "name": "[05] OTHER RECEIVE"},
{"id": "20", "name": "[20] RETURN"},
{"id": "21", "name": "[21] ISSUE TO MANUFACT IN FACTORY"},
{"id": "25", "name": "[25] OTHER ISSUE"},
{"id": "41", "name": "[41] PURCHASE RETURN (PAY)"},
{"id": "95", "name": "[95] ISSUE CHANGING STATUS"},
{"id": "96", "name": "[96] RECEIVE CHANGING STATUS"},
{"id": "98", "name": "[98] DISPOSAL"},
{"id": "80", "name": "[80] KURAIRE"}
]

<?php } ?>
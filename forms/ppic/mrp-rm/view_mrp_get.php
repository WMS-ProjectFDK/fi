<?php
  
    
    session_start();  

    include("../../../connect/conn.php");
    $item_no = isset($_REQUEST['item_no']) ? strval($_REQUEST['item_no']) : '';
    $flag = isset($_REQUEST['flag']) ? strval($_REQUEST['flag']) : '';
    $items = array();
    $rowno=0;

   if ($flag==0){
      $where = "ITEM_TYPE = '$item_no' and";
   }else{
      $where = "ITEM_NO = '$item_no' and";
   };
   
    $sql = "select 
                  ITEM_TYPE as ITEM_DESC,
                  SUM(N_1) as N_1,
SUM(N_2) as N_2,
SUM(N_3) as N_3,
SUM(N_4) as N_4,
SUM(N_5) as N_5,
SUM(N_6) as N_6,
SUM(N_7) as N_7,
SUM(N_8) as N_8,
SUM(N_9) as N_9,
SUM(N_10) as N_10,
SUM(N_11) as N_11,
SUM(N_12) as N_12,
SUM(N_13) as N_13,
SUM(N_14) as N_14,
SUM(N_15) as N_15,
SUM(N_16) as N_16,
SUM(N_17) as N_17,
SUM(N_18) as N_18,
SUM(N_19) as N_19,
SUM(N_20) as N_20,
SUM(N_21) as N_21,
SUM(N_22) as N_22,
SUM(N_23) as N_23,
SUM(N_24) as N_24,
SUM(N_25) as N_25,
SUM(N_26) as N_26,
SUM(N_27) as N_27,
SUM(N_28) as N_28,
SUM(N_29) as N_29,
SUM(N_30) as N_30,
SUM(N_31) as N_31,
SUM(N_32) as N_32,
SUM(N_33) as N_33,
SUM(N_34) as N_34,
SUM(N_35) as N_35,
SUM(N_36) as N_36,
SUM(N_37) as N_37,
SUM(N_38) as N_38,
SUM(N_39) as N_39,
SUM(N_40) as N_40,
SUM(N_41) as N_41,
SUM(N_42) as N_42,
SUM(N_43) as N_43,
SUM(N_44) as N_44,
SUM(N_45) as N_45,
SUM(N_46) as N_46,
SUM(N_47) as N_47,
SUM(N_48) as N_48,
SUM(N_49) as N_49,
SUM(N_50) as N_50,
SUM(N_51) as N_51,
SUM(N_52) as N_52,
SUM(N_53) as N_53,
SUM(N_54) as N_54,
SUM(N_55) as N_55,
SUM(N_56) as N_56,
SUM(N_57) as N_57,
SUM(N_58) as N_58,
SUM(N_59) as N_59,
SUM(N_60) as N_60,
SUM(N_61) as N_61,
SUM(N_62) as N_62,
SUM(N_63) as N_63,
SUM(N_64) as N_64,
SUM(N_65) as N_65,
SUM(N_66) as N_66,
SUM(N_67) as N_67,
SUM(N_68) as N_68,
SUM(N_69) as N_69,
SUM(N_70) as N_70,
SUM(N_71) as N_71,
SUM(N_72) as N_72,
SUM(N_73) as N_73,
SUM(N_74) as N_74,
SUM(N_75) as N_75,
SUM(N_76) as N_76,
SUM(N_77) as N_77,
SUM(N_78) as N_78,
SUM(N_79) as N_79,
SUM(N_80) as N_80,
SUM(N_81) as N_81,
SUM(N_82) as N_82,
SUM(N_83) as N_83,
SUM(N_84) as N_84,
SUM(N_85) as N_85,
SUM(N_86) as N_86,
SUM(N_87) as N_87,
SUM(N_88) as N_88,
SUM(N_89) as N_89,
SUM(N_90) as N_90


                   from ztb_mrp_data where $where no_id in (4,5,6,7,8) 
                   group by ITEM_TYPE,no_id
                   order by no_id";

  
    $result = sqlsrv_query($connect, strtoupper($sql));
    
    $arrData = array();
    $arrNo = 0;
    while ($row=sqlsrv_fetch_array($result)){
        $arrData[$arrNo] = array(
            "ITEM_DESC"=>rtrim($row[0]),
            "N_1"=>rtrim($row[1]),
            "N_2"=>rtrim($row[2]),
            "N_3"=>rtrim($row[3]),
            "N_4"=>rtrim($row[4]),
            "N_5"=>rtrim($row[5]),
            "N_6"=>rtrim($row[6]),
            "N_7"=>rtrim($row[8]),
            "N_8"=>rtrim($row[8]),
            "N_9"=>rtrim($row[9]),
            "N_10"=>rtrim($row[10]),
            "N_11"=>rtrim($row[11]),
            "N_12"=>rtrim($row[12]),
            "N_13"=>rtrim($row[13]),
            "N_14"=>rtrim($row[14]),
            "N_15"=>rtrim($row[15]),
            "N_16"=>rtrim($row[16]),
            "N_17"=>rtrim($row[17]),
            "N_18"=>rtrim($row[18]),
            "N_19"=>rtrim($row[19]),
            "N_20"=>rtrim($row[20]),
            "N_21"=>rtrim($row[21]),
            "N_22"=>rtrim($row[22]),
            "N_23"=>rtrim($row[23]),
            "N_24"=>rtrim($row[24]),
            "N_25"=>rtrim($row[25]),
            "N_26"=>rtrim($row[26]),
            "N_27"=>rtrim($row[27]),
            "N_28"=>rtrim($row[28]),
            "N_29"=>rtrim($row[29]),
            "N_30"=>rtrim($row[30]),
            "N_31"=>rtrim($row[31]),
            "N_32"=>rtrim($row[32]),
            "N_33"=>rtrim($row[33]),
            "N_34"=>rtrim($row[34]),
            "N_35"=>rtrim($row[35]),
            "N_36"=>rtrim($row[36]),
            "N_37"=>rtrim($row[37]),
            "N_38"=>rtrim($row[38]),
            "N_39"=>rtrim($row[39]),
            "N_40"=>rtrim($row[40]),
            "N_41"=>rtrim($row[41]),
            "N_42"=>rtrim($row[42]),
            "N_43"=>rtrim($row[43]),
            "N_44"=>rtrim($row[44]),
            "N_45"=>rtrim($row[45]),
            "N_46"=>rtrim($row[46]),
            "N_47"=>rtrim($row[47]),
            "N_48"=>rtrim($row[48]),
            "N_49"=>rtrim($row[49]),
            "N_50"=>rtrim($row[50]),
            "N_51"=>rtrim($row[51]),
            "N_52"=>rtrim($row[52]),
            "N_53"=>rtrim($row[53]),
            "N_54"=>rtrim($row[54]),
            "N_55"=>rtrim($row[55]),
            "N_56"=>rtrim($row[56]),
            "N_57"=>rtrim($row[57]),
            "N_58"=>rtrim($row[58]),
            "N_59"=>rtrim($row[59]),
            "N_60"=>rtrim($row[60]),
            "N_61"=>rtrim($row[61]),
            "N_62"=>rtrim($row[62]),
            "N_63"=>rtrim($row[63]),
            "N_64"=>rtrim($row[64]),
            "N_65"=>rtrim($row[65]),
            "N_66"=>rtrim($row[66]),
            "N_67"=>rtrim($row[67]),
            "N_68"=>rtrim($row[68]),
            "N_69"=>rtrim($row[69]),
            "N_70"=>rtrim($row[70]),
            "N_71"=>rtrim($row[71]),
            "N_72"=>rtrim($row[72]),
            "N_73"=>rtrim($row[73]),
            "N_74"=>rtrim($row[74]),
            "N_75"=>rtrim($row[75]),
            "N_76"=>rtrim($row[76]),
            "N_77"=>rtrim($row[77]),
            "N_78"=>rtrim($row[78]),
            "N_79"=>rtrim($row[79]),
            "N_70"=>rtrim($row[70]),
            "N_81"=>rtrim($row[81]),
            "N_82"=>rtrim($row[82]),
            "N_83"=>rtrim($row[83]),
            "N_84"=>rtrim($row[84]),
            "N_85"=>rtrim($row[85]),
            "N_86"=>rtrim($row[86]),
            "N_87"=>rtrim($row[87]),
            "N_88"=>rtrim($row[88]),
            "N_89"=>rtrim($row[89]),
            "N_90"=>rtrim($row[90])






        );
        $arrNo++;
    }
    echo json_encode($arrData);
?>
<?php
	session_start();
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_day = isset($_REQUEST['cmb_day']) ? strval($_REQUEST['cmb_day']) : '';
	$ck_day = isset($_REQUEST['ck_day']) ? strval($_REQUEST['ck_day']) : '';
	$sts_ito = isset($_REQUEST['sts_ito']) ? strval($_REQUEST['sts_ito']) : '';
	$ck_ai = isset($_REQUEST['ck_ai']) ? strval($_REQUEST['ck_ai']) : '';

	$exp_ito = explode('-', $sts_ito);

	if($ck_item_no != 'true'){
		$item = "item_no = $cmb_item_no and ";
	}else{
		$item = " ";
	}

	if ($ck_day != "true"){
		$j = $cmb_day;
		if ($cmb_day == 5){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5)/5) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min_days OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min) OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) 
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) 
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 10){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10)/10) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min) OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 20){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20)/20) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min) OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 30){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30)/30) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min) OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}	
		}elseif($cmb_day == 40){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40)/40) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 50){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
						 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50)/50) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
				coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
				coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
									    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
									    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
									    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
									    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
									    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
									    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
									    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
									    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
									    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
									    (ceiling(n_50) > min AND ceiling(n_50) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
									   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
									   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
						            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
						            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
							        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
							        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
							        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
							        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
							        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
							        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
							        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
							        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
							        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
							        (ceiling(n_50) > min AND ceiling(n_50) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
							        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
							        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
							        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
							        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
							        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
							        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
							        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
							        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
							        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
							        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
							        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
							        (ceiling(n_50) > min AND ceiling(n_50) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 60){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
						 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
						 n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60)/60) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
				coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
				coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50,
				coalesce(ceiling(a.n_51),0) n_51, coalesce(ceiling(a.n_52),0) n_52, coalesce(ceiling(a.n_53),0) n_53, coalesce(ceiling(a.n_54),0) n_54, coalesce(ceiling(a.n_55),0) n_55,
				coalesce(ceiling(a.n_56),0) n_56, coalesce(ceiling(a.n_57),0) n_57, coalesce(ceiling(a.n_58),0) n_58, coalesce(ceiling(a.n_59),0) n_59, coalesce(ceiling(a.n_60),0) n_60 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
									    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
									    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
									    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
									    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
									    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
									    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
									    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
									    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
									    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
									    (ceiling(n_50) > min AND ceiling(n_50) < max) OR
									    (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
									    (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
									    (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
									    (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
									    (ceiling(n_55) > min AND ceiling(n_55) < max) OR
									    (ceiling(n_56) > min AND ceiling(n_56) < max) OR
									    (ceiling(n_57) > min AND ceiling(n_57) < max) OR
									    (ceiling(n_58) > min AND ceiling(n_58) < max) OR
									    (ceiling(n_59) > min AND ceiling(n_59) < max) OR
									    (ceiling(n_60) > min AND ceiling(n_60) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
									   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
									   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
									   ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
									   ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
						            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
						            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
						            ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
						            ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
							        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
							        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
							        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
							        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
							        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
							        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
							        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
							        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
							        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
							        (ceiling(n_50) > min AND ceiling(n_50) < max) OR
							        (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
							        (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
							        (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
							        (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
							        (ceiling(n_55) > min AND ceiling(n_55) < max) OR
							        (ceiling(n_56) > min AND ceiling(n_56) < max) OR
							        (ceiling(n_57) > min AND ceiling(n_57) < max) OR
							        (ceiling(n_58) > min AND ceiling(n_58) < max) OR
							        (ceiling(n_59) > min AND ceiling(n_59) < max) OR
							        (ceiling(n_60) > min AND ceiling(n_60) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
							        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
							        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
							        ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
							        ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
							        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
							        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
							        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
							        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
							        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
							        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
							        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
							        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
							        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
							        (ceiling(n_50) > min AND ceiling(n_50) < max) AND
							        (ceiling(n_51) > min AND ceiling(n_51) < max) AND
							        (ceiling(n_52) > min AND ceiling(n_52) < max) AND
							        (ceiling(n_53) > min AND ceiling(n_53) < max) AND
							        (ceiling(n_54) > min AND ceiling(n_54) < max) AND
							        (ceiling(n_55) > min AND ceiling(n_55) < max) AND
							        (ceiling(n_56) > min AND ceiling(n_56) < max) AND
							        (ceiling(n_57) > min AND ceiling(n_57) < max) AND
							        (ceiling(n_58) > min AND ceiling(n_58) < max) AND
							        (ceiling(n_59) > min AND ceiling(n_59) < max) AND
							        (ceiling(n_60) > min AND ceiling(n_60) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
						            ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
						            ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 70){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
						 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
						 n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
						 n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70)/70) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
				coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
				coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50,
				coalesce(ceiling(a.n_51),0) n_51, coalesce(ceiling(a.n_52),0) n_52, coalesce(ceiling(a.n_53),0) n_53, coalesce(ceiling(a.n_54),0) n_54, coalesce(ceiling(a.n_55),0) n_55,
				coalesce(ceiling(a.n_56),0) n_56, coalesce(ceiling(a.n_57),0) n_57, coalesce(ceiling(a.n_58),0) n_58, coalesce(ceiling(a.n_59),0) n_59, coalesce(ceiling(a.n_60),0) n_60,
				coalesce(ceiling(a.n_61),0) n_61, coalesce(ceiling(a.n_62),0) n_62, coalesce(ceiling(a.n_63),0) n_63, coalesce(ceiling(a.n_64),0) n_64, coalesce(ceiling(a.n_65),0) n_65,
				coalesce(ceiling(a.n_66),0) n_66, coalesce(ceiling(a.n_67),0) n_67, coalesce(ceiling(a.n_68),0) n_68, coalesce(ceiling(a.n_69),0) n_69, coalesce(ceiling(a.n_70),0) n_70 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_^0) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_^5) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
									    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
									    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
									    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
									    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
									    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
									    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
									    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
									    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
									    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
									    (ceiling(n_50) > min AND ceiling(n_50) < max) OR
									    (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
									    (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
									    (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
									    (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
									    (ceiling(n_55) > min AND ceiling(n_55) < max) OR
									    (ceiling(n_56) > min AND ceiling(n_56) < max) OR
									    (ceiling(n_57) > min AND ceiling(n_57) < max) OR
									    (ceiling(n_58) > min AND ceiling(n_58) < max) OR
									    (ceiling(n_59) > min AND ceiling(n_59) < max) OR
									    (ceiling(n_60) > min AND ceiling(n_60) < max) OR
									    (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
									    (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
									    (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
									    (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
									    (ceiling(n_65) > min AND ceiling(n_65) < max) OR
									    (ceiling(n_66) > min AND ceiling(n_66) < max) OR
									    (ceiling(n_67) > min AND ceiling(n_67) < max) OR
									    (ceiling(n_68) > min AND ceiling(n_68) < max) OR
									    (ceiling(n_69) > min AND ceiling(n_69) < max) OR
									    (ceiling(n_70) > min AND ceiling(n_70) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
									   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
									   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
									   ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
									   ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
									   ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
									   ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
						            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
						            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
						            ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
						            ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
						            ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
						            ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
							        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
							        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
							        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
							        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
							        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
							        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
							        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
							        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
							        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
							        (ceiling(n_50) > min AND ceiling(n_50) < max) OR
							        (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
							        (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
							        (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
							        (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
							        (ceiling(n_55) > min AND ceiling(n_55) < max) OR
							        (ceiling(n_56) > min AND ceiling(n_56) < max) OR
							        (ceiling(n_57) > min AND ceiling(n_57) < max) OR
							        (ceiling(n_58) > min AND ceiling(n_58) < max) OR
							        (ceiling(n_59) > min AND ceiling(n_59) < max) OR
							        (ceiling(n_60) > min AND ceiling(n_60) < max) OR
							        (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
							        (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
							        (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
							        (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
							        (ceiling(n_65) > min AND ceiling(n_65) < max) OR
							        (ceiling(n_66) > min AND ceiling(n_66) < max) OR
							        (ceiling(n_67) > min AND ceiling(n_67) < max) OR
							        (ceiling(n_68) > min AND ceiling(n_68) < max) OR
							        (ceiling(n_69) > min AND ceiling(n_69) < max) OR
							        (ceiling(n_70) > min AND ceiling(n_70) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
							        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
							        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
							        ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
							        ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
							        ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
							        ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
							        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
							        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
							        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
							        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
							        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
							        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
							        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
							        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
							        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
							        (ceiling(n_50) > min AND ceiling(n_50) < max) AND
							        (ceiling(n_51) > min AND ceiling(n_51) < max) AND
							        (ceiling(n_52) > min AND ceiling(n_52) < max) AND
							        (ceiling(n_53) > min AND ceiling(n_53) < max) AND
							        (ceiling(n_54) > min AND ceiling(n_54) < max) AND
							        (ceiling(n_55) > min AND ceiling(n_55) < max) AND
							        (ceiling(n_56) > min AND ceiling(n_56) < max) AND
							        (ceiling(n_57) > min AND ceiling(n_57) < max) AND
							        (ceiling(n_58) > min AND ceiling(n_58) < max) AND
							        (ceiling(n_59) > min AND ceiling(n_59) < max) AND
							        (ceiling(n_60) > min AND ceiling(n_60) < max) AND
							        (ceiling(n_61) > min AND ceiling(n_61) < max) AND
							        (ceiling(n_62) > min AND ceiling(n_62) < max) AND
							        (ceiling(n_63) > min AND ceiling(n_63) < max) AND
							        (ceiling(n_64) > min AND ceiling(n_64) < max) AND
							        (ceiling(n_65) > min AND ceiling(n_65) < max) AND
							        (ceiling(n_66) > min AND ceiling(n_66) < max) AND
							        (ceiling(n_67) > min AND ceiling(n_67) < max) AND
							        (ceiling(n_68) > min AND ceiling(n_68) < max) AND
							        (ceiling(n_69) > min AND ceiling(n_69) < max) AND
							        (ceiling(n_70) > min AND ceiling(n_70) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
						            ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
						            ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
						            ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
						            ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 80){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
						 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
						 n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
						 n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70,
						 n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80)/80) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
				coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
				coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50,
				coalesce(ceiling(a.n_51),0) n_51, coalesce(ceiling(a.n_52),0) n_52, coalesce(ceiling(a.n_53),0) n_53, coalesce(ceiling(a.n_54),0) n_54, coalesce(ceiling(a.n_55),0) n_55,
				coalesce(ceiling(a.n_56),0) n_56, coalesce(ceiling(a.n_57),0) n_57, coalesce(ceiling(a.n_58),0) n_58, coalesce(ceiling(a.n_59),0) n_59, coalesce(ceiling(a.n_60),0) n_60,
				coalesce(ceiling(a.n_61),0) n_61, coalesce(ceiling(a.n_62),0) n_62, coalesce(ceiling(a.n_63),0) n_63, coalesce(ceiling(a.n_64),0) n_64, coalesce(ceiling(a.n_65),0) n_65,
				coalesce(ceiling(a.n_66),0) n_66, coalesce(ceiling(a.n_67),0) n_67, coalesce(ceiling(a.n_68),0) n_68, coalesce(ceiling(a.n_69),0) n_69, coalesce(ceiling(a.n_70),0) n_70,
				coalesce(ceiling(a.n_71),0) n_71, coalesce(ceiling(a.n_72),0) n_72, coalesce(ceiling(a.n_73),0) n_73, coalesce(ceiling(a.n_74),0) n_74, coalesce(ceiling(a.n_75),0) n_75,
				coalesce(ceiling(a.n_76),0) n_76, coalesce(ceiling(a.n_77),0) n_77, coalesce(ceiling(a.n_78),0) n_78, coalesce(ceiling(a.n_79),0) n_79, coalesce(ceiling(a.n_80),0) n_80 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
									   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
									   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
									    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
									    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
									    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
									    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
									    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
									    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
									    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
									    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
									    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
									    (ceiling(n_50) > min AND ceiling(n_50) < max) OR
									    (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
									    (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
									    (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
									    (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
									    (ceiling(n_55) > min AND ceiling(n_55) < max) OR
									    (ceiling(n_56) > min AND ceiling(n_56) < max) OR
									    (ceiling(n_57) > min AND ceiling(n_57) < max) OR
									    (ceiling(n_58) > min AND ceiling(n_58) < max) OR
									    (ceiling(n_59) > min AND ceiling(n_59) < max) OR
									    (ceiling(n_60) > min AND ceiling(n_60) < max) OR
									    (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
									    (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
									    (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
									    (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
									    (ceiling(n_65) > min AND ceiling(n_65) < max) OR
									    (ceiling(n_66) > min AND ceiling(n_66) < max) OR
									    (ceiling(n_67) > min AND ceiling(n_67) < max) OR
									    (ceiling(n_68) > min AND ceiling(n_68) < max) OR
									    (ceiling(n_69) > min AND ceiling(n_69) < max) OR
									    (ceiling(n_70) > min AND ceiling(n_70) < max) OR
									    (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
									    (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
									    (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
									    (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
									    (ceiling(n_75) > min AND ceiling(n_75) < max) OR
									    (ceiling(n_76) > min AND ceiling(n_76) < max) OR
									    (ceiling(n_77) > min AND ceiling(n_77) < max) OR
									    (ceiling(n_78) > min AND ceiling(n_78) < max) OR
									    (ceiling(n_79) > min AND ceiling(n_79) < max) OR
									    (ceiling(n_80) > min AND ceiling(n_80) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
									   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
									   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
									   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
									   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
									   ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
									   ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
									   ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
									   ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
									   ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
									   ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
						            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
						            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
						            ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
						            ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
						            ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
						            ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
						            ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
						            ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
							        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
							        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
							        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
							        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
							        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
							        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
							        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
							        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
							        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
							        (ceiling(n_50) > min AND ceiling(n_50) < max) OR
							        (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
							        (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
							        (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
							        (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
							        (ceiling(n_55) > min AND ceiling(n_55) < max) OR
							        (ceiling(n_56) > min AND ceiling(n_56) < max) OR
							        (ceiling(n_57) > min AND ceiling(n_57) < max) OR
							        (ceiling(n_58) > min AND ceiling(n_58) < max) OR
							        (ceiling(n_59) > min AND ceiling(n_59) < max) OR
							        (ceiling(n_60) > min AND ceiling(n_60) < max) OR
							        (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
							        (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
							        (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
							        (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
							        (ceiling(n_65) > min AND ceiling(n_65) < max) OR
							        (ceiling(n_66) > min AND ceiling(n_66) < max) OR
							        (ceiling(n_67) > min AND ceiling(n_67) < max) OR
							        (ceiling(n_68) > min AND ceiling(n_68) < max) OR
							        (ceiling(n_69) > min AND ceiling(n_69) < max) OR
							        (ceiling(n_70) > min AND ceiling(n_70) < max) OR
							        (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
							        (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
							        (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
							        (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
							        (ceiling(n_75) > min AND ceiling(n_75) < max) OR
							        (ceiling(n_76) > min AND ceiling(n_76) < max) OR
							        (ceiling(n_77) > min AND ceiling(n_77) < max) OR
							        (ceiling(n_78) > min AND ceiling(n_78) < max) OR
							        (ceiling(n_79) > min AND ceiling(n_79) < max) OR
							        (ceiling(n_80) > min AND ceiling(n_80) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
							        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
							        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
							        ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
							        ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
							        ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
							        ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
							        ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
							        ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
							        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
							        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
							        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
							        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
							        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
							        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
							        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
							        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
							        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
							        (ceiling(n_50) > min AND ceiling(n_50) < max) AND
							        (ceiling(n_51) > min AND ceiling(n_51) < max) AND
							        (ceiling(n_52) > min AND ceiling(n_52) < max) AND
							        (ceiling(n_53) > min AND ceiling(n_53) < max) AND
							        (ceiling(n_54) > min AND ceiling(n_54) < max) AND
							        (ceiling(n_55) > min AND ceiling(n_55) < max) AND
							        (ceiling(n_56) > min AND ceiling(n_56) < max) AND
							        (ceiling(n_57) > min AND ceiling(n_57) < max) AND
							        (ceiling(n_58) > min AND ceiling(n_58) < max) AND
							        (ceiling(n_59) > min AND ceiling(n_59) < max) AND
							        (ceiling(n_60) > min AND ceiling(n_60) < max) AND
							        (ceiling(n_61) > min AND ceiling(n_61) < max) AND
							        (ceiling(n_62) > min AND ceiling(n_62) < max) AND
							        (ceiling(n_63) > min AND ceiling(n_63) < max) AND
							        (ceiling(n_64) > min AND ceiling(n_64) < max) AND
							        (ceiling(n_65) > min AND ceiling(n_65) < max) AND
							        (ceiling(n_66) > min AND ceiling(n_66) < max) AND
							        (ceiling(n_67) > min AND ceiling(n_67) < max) AND
							        (ceiling(n_68) > min AND ceiling(n_68) < max) AND
							        (ceiling(n_69) > min AND ceiling(n_69) < max) AND
							        (ceiling(n_70) > min AND ceiling(n_70) < max) AND
							        (ceiling(n_71) > min AND ceiling(n_71) < max) AND
							        (ceiling(n_72) > min AND ceiling(n_72) < max) AND
							        (ceiling(n_73) > min AND ceiling(n_73) < max) AND
							        (ceiling(n_74) > min AND ceiling(n_74) < max) AND
							        (ceiling(n_75) > min AND ceiling(n_75) < max) AND
							        (ceiling(n_76) > min AND ceiling(n_76) < max) AND
							        (ceiling(n_77) > min AND ceiling(n_77) < max) AND
							        (ceiling(n_78) > min AND ceiling(n_78) < max) AND
							        (ceiling(n_79) > min AND ceiling(n_79) < max) AND
							        (ceiling(n_80) > min AND ceiling(n_80) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
						            ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
						            ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
						            ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
						            ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
						            ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
						            ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}else{
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
						 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
						 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
						 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
						 n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
						 n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70,
						 n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80,
						 n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
						  n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";
			$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
				coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
				coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
				coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
				coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
				coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
				coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
				coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
				coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
				coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50,
				coalesce(ceiling(a.n_51),0) n_51, coalesce(ceiling(a.n_52),0) n_52, coalesce(ceiling(a.n_53),0) n_53, coalesce(ceiling(a.n_54),0) n_54, coalesce(ceiling(a.n_55),0) n_55,
				coalesce(ceiling(a.n_56),0) n_56, coalesce(ceiling(a.n_57),0) n_57, coalesce(ceiling(a.n_58),0) n_58, coalesce(ceiling(a.n_59),0) n_59, coalesce(ceiling(a.n_60),0) n_60,
				coalesce(ceiling(a.n_61),0) n_61, coalesce(ceiling(a.n_62),0) n_62, coalesce(ceiling(a.n_63),0) n_63, coalesce(ceiling(a.n_64),0) n_64, coalesce(ceiling(a.n_65),0) n_65,
				coalesce(ceiling(a.n_66),0) n_66, coalesce(ceiling(a.n_67),0) n_67, coalesce(ceiling(a.n_68),0) n_68, coalesce(ceiling(a.n_69),0) n_69, coalesce(ceiling(a.n_70),0) n_70,
				coalesce(ceiling(a.n_71),0) n_71, coalesce(ceiling(a.n_72),0) n_72, coalesce(ceiling(a.n_73),0) n_73, coalesce(ceiling(a.n_74),0) n_74, coalesce(ceiling(a.n_75),0) n_75,
				coalesce(ceiling(a.n_76),0) n_76, coalesce(ceiling(a.n_77),0) n_77, coalesce(ceiling(a.n_78),0) n_78, coalesce(ceiling(a.n_79),0) n_79, coalesce(ceiling(a.n_80),0) n_80,
				coalesce(ceiling(a.n_81),0) n_81, coalesce(ceiling(a.n_82),0) n_82, coalesce(ceiling(a.n_83),0) n_83, coalesce(ceiling(a.n_84),0) n_84, coalesce(ceiling(a.n_85),0) n_85, 
				coalesce(ceiling(a.n_86),0) n_86, coalesce(ceiling(a.n_87),0) n_87, coalesce(ceiling(a.n_88),0) n_88, coalesce(ceiling(a.n_89),0) n_89, coalesce(ceiling(a.n_90),0) n_90 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_25) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_30) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_35) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_40) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_45) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_50) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_55) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_60) < min OR
									   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_65) < min OR
									   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_70) < min OR
									   ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_75) < min OR
									   ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_80) < min OR
									   ceiling(n_91) < min OR ceiling(n_92) < min OR ceiling(n_93) < min OR ceiling(n_94) < min OR ceiling(n_85) < min OR
									   ceiling(n_96) < min OR ceiling(n_97) < min OR ceiling(n_98) < min OR ceiling(n_99) < min OR ceiling(n_90) < min) OR 
									  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
									    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
									    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
									    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
									    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
									    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
									    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
									    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
									    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
									    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
									    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
									    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
									    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
									    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
									    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
									    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
									    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
									    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
									    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
									    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
									    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
									    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
									    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
									    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
									    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
									    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
									    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
									    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
									    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
									    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
									    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
									    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
									    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
									    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
									    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
									    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
									    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
									    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
									    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
									    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
									    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
									    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
									    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
									    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
									    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
									    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
									    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
									    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
									    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
									    (ceiling(n_50) > min AND ceiling(n_50) < max) OR
									    (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
									    (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
									    (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
									    (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
									    (ceiling(n_55) > min AND ceiling(n_55) < max) OR
									    (ceiling(n_56) > min AND ceiling(n_56) < max) OR
									    (ceiling(n_57) > min AND ceiling(n_57) < max) OR
									    (ceiling(n_58) > min AND ceiling(n_58) < max) OR
									    (ceiling(n_59) > min AND ceiling(n_59) < max) OR
									    (ceiling(n_60) > min AND ceiling(n_60) < max) OR
									    (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
									    (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
									    (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
									    (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
									    (ceiling(n_65) > min AND ceiling(n_65) < max) OR
									    (ceiling(n_66) > min AND ceiling(n_66) < max) OR
									    (ceiling(n_67) > min AND ceiling(n_67) < max) OR
									    (ceiling(n_68) > min AND ceiling(n_68) < max) OR
									    (ceiling(n_69) > min AND ceiling(n_69) < max) OR
									    (ceiling(n_70) > min AND ceiling(n_70) < max) OR
									    (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
									    (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
									    (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
									    (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
									    (ceiling(n_75) > min AND ceiling(n_75) < max) OR
									    (ceiling(n_76) > min AND ceiling(n_76) < max) OR
									    (ceiling(n_77) > min AND ceiling(n_77) < max) OR
									    (ceiling(n_78) > min AND ceiling(n_78) < max) OR
									    (ceiling(n_79) > min AND ceiling(n_79) < max) OR
									    (ceiling(n_80) > min AND ceiling(n_80) < max) OR
									    (ceiling(n_81) > min AND ceiling(n_81) < max) OR 
									    (ceiling(n_82) > min AND ceiling(n_82) < max) OR 
									    (ceiling(n_83) > min AND ceiling(n_83) < max) OR 
									    (ceiling(n_84) > min AND ceiling(n_84) < max) OR 
									    (ceiling(n_85) > min AND ceiling(n_85) < max) OR
									    (ceiling(n_86) > min AND ceiling(n_86) < max) OR
									    (ceiling(n_87) > min AND ceiling(n_87) < max) OR
									    (ceiling(n_88) > min AND ceiling(n_88) < max) OR
									    (ceiling(n_89) > min AND ceiling(n_89) < max) OR
									    (ceiling(n_90) > min AND ceiling(n_90) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
									   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
									   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
									   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
									   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
									   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
									   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
									   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
									   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
									   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
									   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
									   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
									   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
									   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
									   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
									   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min OR
									   ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_85) < min OR
									   ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_90) < min) 
									   OR 
									  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
									   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
									   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
									   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
									   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
									   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
									   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
									   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
									   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
									   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
									   ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
									   ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
									   ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
									   ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
									   ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
									   ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max OR
									   ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_85) > max OR
									   ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
						            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
						            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
						            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
						            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
						            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
						            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
						            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
						            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
						            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
						            ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
						            ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
						            ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
						            ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
						            ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
						            ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min OR
						            ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_85) < min OR
						            ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_90) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
							        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
							        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
							        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
							        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
							        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
							        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
							        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
							        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
							        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
							        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
							        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
							        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
							        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
							        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
							        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
							        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
							        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
							        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
							        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
							        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
							        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
							        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
							        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
							        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
							        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
							        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
							        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
							        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
							        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
							        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
							        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
							        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
							        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
							        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
							        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
							        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
							        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
							        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
							        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
							        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
							        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
							        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
							        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
							        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
							        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
							        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
							        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
							        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
							        (ceiling(n_50) > min AND ceiling(n_50) < max) OR
							        (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
							        (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
							        (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
							        (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
							        (ceiling(n_55) > min AND ceiling(n_55) < max) OR
							        (ceiling(n_56) > min AND ceiling(n_56) < max) OR
							        (ceiling(n_57) > min AND ceiling(n_57) < max) OR
							        (ceiling(n_58) > min AND ceiling(n_58) < max) OR
							        (ceiling(n_59) > min AND ceiling(n_59) < max) OR
							        (ceiling(n_60) > min AND ceiling(n_60) < max) OR
							        (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
							        (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
							        (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
							        (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
							        (ceiling(n_65) > min AND ceiling(n_65) < max) OR
							        (ceiling(n_66) > min AND ceiling(n_66) < max) OR
							        (ceiling(n_67) > min AND ceiling(n_67) < max) OR
							        (ceiling(n_68) > min AND ceiling(n_68) < max) OR
							        (ceiling(n_69) > min AND ceiling(n_69) < max) OR
							        (ceiling(n_70) > min AND ceiling(n_70) < max) OR
							        (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
							        (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
							        (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
							        (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
							        (ceiling(n_75) > min AND ceiling(n_75) < max) OR
							        (ceiling(n_76) > min AND ceiling(n_76) < max) OR
							        (ceiling(n_77) > min AND ceiling(n_77) < max) OR
							        (ceiling(n_78) > min AND ceiling(n_78) < max) OR
							        (ceiling(n_79) > min AND ceiling(n_79) < max) OR
							        (ceiling(n_80) > min AND ceiling(n_80) < max) OR
							        (ceiling(n_81) > min AND ceiling(n_81) < max) OR 
							        (ceiling(n_82) > min AND ceiling(n_82) < max) OR 
							        (ceiling(n_83) > min AND ceiling(n_83) < max) OR 
							        (ceiling(n_84) > min AND ceiling(n_84) < max) OR 
							        (ceiling(n_85) > min AND ceiling(n_85) < max) OR
							        (ceiling(n_86) > min AND ceiling(n_86) < max) OR
							        (ceiling(n_87) > min AND ceiling(n_87) < max) OR
							        (ceiling(n_88) > min AND ceiling(n_88) < max) OR
							        (ceiling(n_89) > min AND ceiling(n_89) < max) OR
							        (ceiling(n_90) > min AND ceiling(n_90) < max)
							       ) OR 
							       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
							        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
							        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
							        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
							        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
							        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
							        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
							        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
							        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
							        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
							        ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
							        ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
							        ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
							        ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
							        ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
							        ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max OR
							        ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_85) > max OR
							        ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max 
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
							        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
							        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
							        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
							        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
							        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
							        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
							        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
							        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
							        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
							        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
							        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
							        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
							        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
							        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
							        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
							        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
							        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
							        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
							        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
							        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
							        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
							        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
							        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
							        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
							        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
							        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
							        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
							        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
							        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
							        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
							        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
							        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
							        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
							        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
							        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
							        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
							        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
							        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
							        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
							        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
							        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
							        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
							        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
							        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
							        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
							        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
							        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
							        (ceiling(n_50) > min AND ceiling(n_50) < max) AND
							        (ceiling(n_51) > min AND ceiling(n_51) < max) AND
							        (ceiling(n_52) > min AND ceiling(n_52) < max) AND
							        (ceiling(n_53) > min AND ceiling(n_53) < max) AND
							        (ceiling(n_54) > min AND ceiling(n_54) < max) AND
							        (ceiling(n_55) > min AND ceiling(n_55) < max) AND
							        (ceiling(n_56) > min AND ceiling(n_56) < max) AND
							        (ceiling(n_57) > min AND ceiling(n_57) < max) AND
							        (ceiling(n_58) > min AND ceiling(n_58) < max) AND
							        (ceiling(n_59) > min AND ceiling(n_59) < max) AND
							        (ceiling(n_60) > min AND ceiling(n_60) < max) AND
							        (ceiling(n_61) > min AND ceiling(n_61) < max) AND
							        (ceiling(n_62) > min AND ceiling(n_62) < max) AND
							        (ceiling(n_63) > min AND ceiling(n_63) < max) AND
							        (ceiling(n_64) > min AND ceiling(n_64) < max) AND
							        (ceiling(n_65) > min AND ceiling(n_65) < max) AND
							        (ceiling(n_66) > min AND ceiling(n_66) < max) AND
							        (ceiling(n_67) > min AND ceiling(n_67) < max) AND
							        (ceiling(n_68) > min AND ceiling(n_68) < max) AND
							        (ceiling(n_69) > min AND ceiling(n_69) < max) AND
							        (ceiling(n_70) > min AND ceiling(n_70) < max) AND
							        (ceiling(n_71) > min AND ceiling(n_71) < max) AND
							        (ceiling(n_72) > min AND ceiling(n_72) < max) AND
							        (ceiling(n_73) > min AND ceiling(n_73) < max) AND
							        (ceiling(n_74) > min AND ceiling(n_74) < max) AND
							        (ceiling(n_75) > min AND ceiling(n_75) < max) AND
							        (ceiling(n_76) > min AND ceiling(n_76) < max) AND
							        (ceiling(n_77) > min AND ceiling(n_77) < max) AND
							        (ceiling(n_78) > min AND ceiling(n_78) < max) AND
							        (ceiling(n_79) > min AND ceiling(n_79) < max) AND
							        (ceiling(n_80) > min AND ceiling(n_80) < max) AND
							        (ceiling(n_81) > min AND ceiling(n_81) < max) AND
							        (ceiling(n_82) > min AND ceiling(n_82) < max) AND
							        (ceiling(n_83) > min AND ceiling(n_83) < max) AND
							        (ceiling(n_84) > min AND ceiling(n_84) < max) AND
							        (ceiling(n_85) > min AND ceiling(n_85) < max) AND
							        (ceiling(n_86) > min AND ceiling(n_86) < max) AND
							        (ceiling(n_87) > min AND ceiling(n_87) < max) AND
							        (ceiling(n_88) > min AND ceiling(n_88) < max) AND
							        (ceiling(n_89) > min AND ceiling(n_89) < max) AND
							        (ceiling(n_90) > min AND ceiling(n_90) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
						            ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
						            ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
						            ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
						            ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
						            ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
						            ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_30) > max OR
						            ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_25) > max OR
						            ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}
	}else{
		$j = 90;
		$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
					 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20,
					 n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30,
					 n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40,
					 n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50,
					 n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60,
					 n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70,
					 n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80,
					 n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90 ";
		$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
						  n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";
		$filedNya ="coalesce(ceiling(a.n_1),0) n_1, coalesce(ceiling(a.n_2),0) n_2, coalesce(ceiling(a.n_3),0) n_3, coalesce(ceiling(a.n_4),0) n_4, coalesce(ceiling(a.n_5),0) n_5, 
			coalesce(ceiling(a.n_6),0) n_6, coalesce(ceiling(a.n_7),0) n_7, coalesce(ceiling(a.n_8),0) n_8, coalesce(ceiling(a.n_9),0) n_9, coalesce(ceiling(a.n_10),0) n_10,
			coalesce(ceiling(a.n_11),0) n_11, coalesce(ceiling(a.n_12),0) n_12, coalesce(ceiling(a.n_13),0) n_13, coalesce(ceiling(a.n_14),0) n_14, coalesce(ceiling(a.n_15),0) n_15, 
			coalesce(ceiling(a.n_16),0) n_16, coalesce(ceiling(a.n_17),0) n_17, coalesce(ceiling(a.n_18),0) n_18, coalesce(ceiling(a.n_19),0) n_19, coalesce(ceiling(a.n_20),0) n_20,
			coalesce(ceiling(a.n_21),0) n_21, coalesce(ceiling(a.n_22),0) n_22, coalesce(ceiling(a.n_23),0) n_23, coalesce(ceiling(a.n_24),0) n_24, coalesce(ceiling(a.n_25),0) n_25, 
			coalesce(ceiling(a.n_26),0) n_26, coalesce(ceiling(a.n_27),0) n_27, coalesce(ceiling(a.n_28),0) n_28, coalesce(ceiling(a.n_29),0) n_29, coalesce(ceiling(a.n_30),0) n_30,
			coalesce(ceiling(a.n_31),0) n_31, coalesce(ceiling(a.n_32),0) n_32, coalesce(ceiling(a.n_33),0) n_33, coalesce(ceiling(a.n_34),0) n_34, coalesce(ceiling(a.n_35),0) n_35, 
			coalesce(ceiling(a.n_36),0) n_36, coalesce(ceiling(a.n_37),0) n_37, coalesce(ceiling(a.n_38),0) n_38, coalesce(ceiling(a.n_39),0) n_39, coalesce(ceiling(a.n_40),0) n_40,
			coalesce(ceiling(a.n_41),0) n_41, coalesce(ceiling(a.n_42),0) n_42, coalesce(ceiling(a.n_43),0) n_43, coalesce(ceiling(a.n_44),0) n_44, coalesce(ceiling(a.n_45),0) n_45, 
			coalesce(ceiling(a.n_46),0) n_46, coalesce(ceiling(a.n_47),0) n_47, coalesce(ceiling(a.n_48),0) n_48, coalesce(ceiling(a.n_49),0) n_49, coalesce(ceiling(a.n_50),0) n_50,
			coalesce(ceiling(a.n_51),0) n_51, coalesce(ceiling(a.n_52),0) n_52, coalesce(ceiling(a.n_53),0) n_53, coalesce(ceiling(a.n_54),0) n_54, coalesce(ceiling(a.n_55),0) n_55,
			coalesce(ceiling(a.n_56),0) n_56, coalesce(ceiling(a.n_57),0) n_57, coalesce(ceiling(a.n_58),0) n_58, coalesce(ceiling(a.n_59),0) n_59, coalesce(ceiling(a.n_60),0) n_60,
			coalesce(ceiling(a.n_61),0) n_61, coalesce(ceiling(a.n_62),0) n_62, coalesce(ceiling(a.n_63),0) n_63, coalesce(ceiling(a.n_64),0) n_64, coalesce(ceiling(a.n_65),0) n_65,
			coalesce(ceiling(a.n_66),0) n_66, coalesce(ceiling(a.n_67),0) n_67, coalesce(ceiling(a.n_68),0) n_68, coalesce(ceiling(a.n_69),0) n_69, coalesce(ceiling(a.n_70),0) n_70,
			coalesce(ceiling(a.n_71),0) n_71, coalesce(ceiling(a.n_72),0) n_72, coalesce(ceiling(a.n_73),0) n_73, coalesce(ceiling(a.n_74),0) n_74, coalesce(ceiling(a.n_75),0) n_75,
			coalesce(ceiling(a.n_76),0) n_76, coalesce(ceiling(a.n_77),0) n_77, coalesce(ceiling(a.n_78),0) n_78, coalesce(ceiling(a.n_79),0) n_79, coalesce(ceiling(a.n_80),0) n_80,
			coalesce(ceiling(a.n_81),0) n_81, coalesce(ceiling(a.n_82),0) n_82, coalesce(ceiling(a.n_83),0) n_83, coalesce(ceiling(a.n_84),0) n_84, coalesce(ceiling(a.n_85),0) n_85, 
			coalesce(ceiling(a.n_86),0) n_86, coalesce(ceiling(a.n_87),0) n_87, coalesce(ceiling(a.n_88),0) n_88, coalesce(ceiling(a.n_89),0) n_89, coalesce(ceiling(a.n_90),0) n_90 ";
		
		if ($exp_ito[0] == 'T') {
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-T-T";
					$wheNya = " ";
				}else{
					//$wheNya = "T-T-F";
					$wheNya = " (
								  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
								   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
								   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
								   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
								   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
								   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
								   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
								   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
								   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
								   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
								   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
								   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
								   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
								   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
								   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
								   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min OR
								   ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_85) < min OR
								   ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_90) < min) OR 
								  ( (ceiling(n_1) > min AND ceiling(n_1) < max) OR 
								    (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
								    (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
								    (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
								    (ceiling(n_5) > min AND ceiling(n_5) < max) OR
								    (ceiling(n_6) > min AND ceiling(n_6) < max) OR
								    (ceiling(n_7) > min AND ceiling(n_7) < max) OR
								    (ceiling(n_8) > min AND ceiling(n_8) < max) OR
								    (ceiling(n_9) > min AND ceiling(n_9) < max) OR
								    (ceiling(n_10) > min AND ceiling(n_10) < max) OR
								    (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
								    (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
								    (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
								    (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
								    (ceiling(n_15) > min AND ceiling(n_15) < max) OR
								    (ceiling(n_16) > min AND ceiling(n_16) < max) OR
								    (ceiling(n_17) > min AND ceiling(n_17) < max) OR
								    (ceiling(n_18) > min AND ceiling(n_18) < max) OR
								    (ceiling(n_19) > min AND ceiling(n_19) < max) OR
								    (ceiling(n_20) > min AND ceiling(n_20) < max) OR
								    (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
								    (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
								    (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
								    (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
								    (ceiling(n_25) > min AND ceiling(n_25) < max) OR
								    (ceiling(n_26) > min AND ceiling(n_26) < max) OR
								    (ceiling(n_27) > min AND ceiling(n_27) < max) OR
								    (ceiling(n_28) > min AND ceiling(n_28) < max) OR
								    (ceiling(n_29) > min AND ceiling(n_29) < max) OR
								    (ceiling(n_30) > min AND ceiling(n_30) < max) OR
								    (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
								    (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
								    (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
								    (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
								    (ceiling(n_35) > min AND ceiling(n_35) < max) OR
								    (ceiling(n_36) > min AND ceiling(n_36) < max) OR
								    (ceiling(n_37) > min AND ceiling(n_37) < max) OR
								    (ceiling(n_38) > min AND ceiling(n_38) < max) OR
								    (ceiling(n_39) > min AND ceiling(n_39) < max) OR
								    (ceiling(n_40) > min AND ceiling(n_40) < max) OR
								    (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
								    (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
								    (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
								    (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
								    (ceiling(n_45) > min AND ceiling(n_45) < max) OR
								    (ceiling(n_46) > min AND ceiling(n_46) < max) OR
								    (ceiling(n_47) > min AND ceiling(n_47) < max) OR
								    (ceiling(n_48) > min AND ceiling(n_48) < max) OR
								    (ceiling(n_49) > min AND ceiling(n_49) < max) OR
								    (ceiling(n_50) > min AND ceiling(n_50) < max) OR
								    (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
								    (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
								    (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
								    (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
								    (ceiling(n_55) > min AND ceiling(n_55) < max) OR
								    (ceiling(n_56) > min AND ceiling(n_56) < max) OR
								    (ceiling(n_57) > min AND ceiling(n_57) < max) OR
								    (ceiling(n_58) > min AND ceiling(n_58) < max) OR
								    (ceiling(n_59) > min AND ceiling(n_59) < max) OR
								    (ceiling(n_60) > min AND ceiling(n_60) < max) OR
								    (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
								    (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
								    (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
								    (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
								    (ceiling(n_65) > min AND ceiling(n_65) < max) OR
								    (ceiling(n_66) > min AND ceiling(n_66) < max) OR
								    (ceiling(n_67) > min AND ceiling(n_67) < max) OR
								    (ceiling(n_68) > min AND ceiling(n_68) < max) OR
								    (ceiling(n_69) > min AND ceiling(n_69) < max) OR
								    (ceiling(n_70) > min AND ceiling(n_70) < max) OR
								    (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
								    (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
								    (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
								    (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
								    (ceiling(n_75) > min AND ceiling(n_75) < max) OR
								    (ceiling(n_76) > min AND ceiling(n_76) < max) OR
								    (ceiling(n_77) > min AND ceiling(n_77) < max) OR
								    (ceiling(n_78) > min AND ceiling(n_78) < max) OR
								    (ceiling(n_79) > min AND ceiling(n_79) < max) OR
								    (ceiling(n_80) > min AND ceiling(n_80) < max) OR
								    (ceiling(n_81) > min AND ceiling(n_81) < max) OR 
								    (ceiling(n_82) > min AND ceiling(n_82) < max) OR 
								    (ceiling(n_83) > min AND ceiling(n_83) < max) OR 
								    (ceiling(n_84) > min AND ceiling(n_84) < max) OR 
								    (ceiling(n_85) > min AND ceiling(n_85) < max) OR
								    (ceiling(n_86) > min AND ceiling(n_86) < max) OR
								    (ceiling(n_87) > min AND ceiling(n_87) < max) OR
								    (ceiling(n_88) > min AND ceiling(n_88) < max) OR
								    (ceiling(n_89) > min AND ceiling(n_89) < max) OR
								    (ceiling(n_90) > min AND ceiling(n_90) < max)
								  )
								) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-F-T";
					$wheNya = " (
								  (ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
								   ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
								   ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
								   ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
								   ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
								   ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
								   ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
								   ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
								   ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
								   ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
								   ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
								   ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
								   ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
								   ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
								   ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
								   ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min OR
								   ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_85) < min OR
								   ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_90) < min) 
								   OR 
								  (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
								   ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
								   ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
								   ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
								   ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
								   ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
								   ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
								   ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
								   ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
								   ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
								   ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
								   ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
								   ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
								   ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
								   ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
								   ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max OR
								   ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_85) > max OR
								   ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max)
								) and ";
				}else{
					//$wheNya = "T-F-F";
					$wheNya = "(ceiling(n_1) < min OR ceiling(n_2) < min OR ceiling(n_3) < min OR ceiling(n_4) < min OR ceiling(n_5) < min OR
					            ceiling(n_6) < min OR ceiling(n_7) < min OR ceiling(n_8) < min OR ceiling(n_9) < min OR ceiling(n_10) < min OR
					            ceiling(n_11) < min OR ceiling(n_12) < min OR ceiling(n_13) < min OR ceiling(n_14) < min OR ceiling(n_15) < min OR
					            ceiling(n_16) < min OR ceiling(n_17) < min OR ceiling(n_18) < min OR ceiling(n_19) < min OR ceiling(n_20) < min OR
					            ceiling(n_21) < min OR ceiling(n_22) < min OR ceiling(n_23) < min OR ceiling(n_24) < min OR ceiling(n_25) < min OR
					            ceiling(n_26) < min OR ceiling(n_27) < min OR ceiling(n_28) < min OR ceiling(n_29) < min OR ceiling(n_30) < min OR
					            ceiling(n_31) < min OR ceiling(n_32) < min OR ceiling(n_33) < min OR ceiling(n_34) < min OR ceiling(n_35) < min OR
					            ceiling(n_36) < min OR ceiling(n_37) < min OR ceiling(n_38) < min OR ceiling(n_39) < min OR ceiling(n_40) < min OR
					            ceiling(n_41) < min OR ceiling(n_42) < min OR ceiling(n_43) < min OR ceiling(n_44) < min OR ceiling(n_45) < min OR
					            ceiling(n_46) < min OR ceiling(n_47) < min OR ceiling(n_48) < min OR ceiling(n_49) < min OR ceiling(n_50) < min OR
					            ceiling(n_51) < min OR ceiling(n_52) < min OR ceiling(n_53) < min OR ceiling(n_54) < min OR ceiling(n_55) < min OR
					            ceiling(n_56) < min OR ceiling(n_57) < min OR ceiling(n_58) < min OR ceiling(n_59) < min OR ceiling(n_60) < min OR
					            ceiling(n_61) < min OR ceiling(n_62) < min OR ceiling(n_63) < min OR ceiling(n_64) < min OR ceiling(n_65) < min OR
					            ceiling(n_66) < min OR ceiling(n_67) < min OR ceiling(n_68) < min OR ceiling(n_69) < min OR ceiling(n_70) < min OR
					            ceiling(n_71) < min OR ceiling(n_72) < min OR ceiling(n_73) < min OR ceiling(n_74) < min OR ceiling(n_75) < min OR
					            ceiling(n_76) < min OR ceiling(n_77) < min OR ceiling(n_78) < min OR ceiling(n_79) < min OR ceiling(n_80) < min OR
					            ceiling(n_81) < min OR ceiling(n_82) < min OR ceiling(n_83) < min OR ceiling(n_84) < min OR ceiling(n_85) < min OR
					            ceiling(n_86) < min OR ceiling(n_87) < min OR ceiling(n_88) < min OR ceiling(n_89) < min OR ceiling(n_90) < min) and ";
				}
			}
		}else{
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-T-T";
					$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) OR 
						        (ceiling(n_2) > min AND ceiling(n_2) < max) OR 
						        (ceiling(n_3) > min AND ceiling(n_3) < max) OR 
						        (ceiling(n_4) > min AND ceiling(n_4) < max) OR 
						        (ceiling(n_5) > min AND ceiling(n_5) < max) OR
						        (ceiling(n_6) > min AND ceiling(n_6) < max) OR
						        (ceiling(n_7) > min AND ceiling(n_7) < max) OR
						        (ceiling(n_8) > min AND ceiling(n_8) < max) OR
						        (ceiling(n_9) > min AND ceiling(n_9) < max) OR
						        (ceiling(n_10) > min AND ceiling(n_10) < max) OR
						        (ceiling(n_11) > min AND ceiling(n_11) < max) OR 
						        (ceiling(n_12) > min AND ceiling(n_12) < max) OR 
						        (ceiling(n_13) > min AND ceiling(n_13) < max) OR 
						        (ceiling(n_14) > min AND ceiling(n_14) < max) OR 
						        (ceiling(n_15) > min AND ceiling(n_15) < max) OR
						        (ceiling(n_16) > min AND ceiling(n_16) < max) OR
						        (ceiling(n_17) > min AND ceiling(n_17) < max) OR
						        (ceiling(n_18) > min AND ceiling(n_18) < max) OR
						        (ceiling(n_19) > min AND ceiling(n_19) < max) OR
						        (ceiling(n_20) > min AND ceiling(n_20) < max) OR
						        (ceiling(n_21) > min AND ceiling(n_21) < max) OR 
						        (ceiling(n_22) > min AND ceiling(n_22) < max) OR 
						        (ceiling(n_23) > min AND ceiling(n_23) < max) OR 
						        (ceiling(n_24) > min AND ceiling(n_24) < max) OR 
						        (ceiling(n_25) > min AND ceiling(n_25) < max) OR
						        (ceiling(n_26) > min AND ceiling(n_26) < max) OR
						        (ceiling(n_27) > min AND ceiling(n_27) < max) OR
						        (ceiling(n_28) > min AND ceiling(n_28) < max) OR
						        (ceiling(n_29) > min AND ceiling(n_29) < max) OR
						        (ceiling(n_30) > min AND ceiling(n_30) < max) OR
						        (ceiling(n_31) > min AND ceiling(n_31) < max) OR 
						        (ceiling(n_32) > min AND ceiling(n_32) < max) OR 
						        (ceiling(n_33) > min AND ceiling(n_33) < max) OR 
						        (ceiling(n_34) > min AND ceiling(n_34) < max) OR 
						        (ceiling(n_35) > min AND ceiling(n_35) < max) OR
						        (ceiling(n_36) > min AND ceiling(n_36) < max) OR
						        (ceiling(n_37) > min AND ceiling(n_37) < max) OR
						        (ceiling(n_38) > min AND ceiling(n_38) < max) OR
						        (ceiling(n_39) > min AND ceiling(n_39) < max) OR
						        (ceiling(n_40) > min AND ceiling(n_40) < max) OR
						        (ceiling(n_41) > min AND ceiling(n_41) < max) OR 
						        (ceiling(n_42) > min AND ceiling(n_42) < max) OR 
						        (ceiling(n_43) > min AND ceiling(n_43) < max) OR 
						        (ceiling(n_44) > min AND ceiling(n_44) < max) OR 
						        (ceiling(n_45) > min AND ceiling(n_45) < max) OR
						        (ceiling(n_46) > min AND ceiling(n_46) < max) OR
						        (ceiling(n_47) > min AND ceiling(n_47) < max) OR
						        (ceiling(n_48) > min AND ceiling(n_48) < max) OR
						        (ceiling(n_49) > min AND ceiling(n_49) < max) OR
						        (ceiling(n_50) > min AND ceiling(n_50) < max) OR
						        (ceiling(n_51) > min AND ceiling(n_51) < max) OR 
						        (ceiling(n_52) > min AND ceiling(n_52) < max) OR 
						        (ceiling(n_53) > min AND ceiling(n_53) < max) OR 
						        (ceiling(n_54) > min AND ceiling(n_54) < max) OR 
						        (ceiling(n_55) > min AND ceiling(n_55) < max) OR
						        (ceiling(n_56) > min AND ceiling(n_56) < max) OR
						        (ceiling(n_57) > min AND ceiling(n_57) < max) OR
						        (ceiling(n_58) > min AND ceiling(n_58) < max) OR
						        (ceiling(n_59) > min AND ceiling(n_59) < max) OR
						        (ceiling(n_60) > min AND ceiling(n_60) < max) OR
						        (ceiling(n_61) > min AND ceiling(n_61) < max) OR 
						        (ceiling(n_62) > min AND ceiling(n_62) < max) OR 
						        (ceiling(n_63) > min AND ceiling(n_63) < max) OR 
						        (ceiling(n_64) > min AND ceiling(n_64) < max) OR 
						        (ceiling(n_65) > min AND ceiling(n_65) < max) OR
						        (ceiling(n_66) > min AND ceiling(n_66) < max) OR
						        (ceiling(n_67) > min AND ceiling(n_67) < max) OR
						        (ceiling(n_68) > min AND ceiling(n_68) < max) OR
						        (ceiling(n_69) > min AND ceiling(n_69) < max) OR
						        (ceiling(n_70) > min AND ceiling(n_70) < max) OR
						        (ceiling(n_71) > min AND ceiling(n_71) < max) OR 
						        (ceiling(n_72) > min AND ceiling(n_72) < max) OR 
						        (ceiling(n_73) > min AND ceiling(n_73) < max) OR 
						        (ceiling(n_74) > min AND ceiling(n_74) < max) OR 
						        (ceiling(n_75) > min AND ceiling(n_75) < max) OR
						        (ceiling(n_76) > min AND ceiling(n_76) < max) OR
						        (ceiling(n_77) > min AND ceiling(n_77) < max) OR
						        (ceiling(n_78) > min AND ceiling(n_78) < max) OR
						        (ceiling(n_79) > min AND ceiling(n_79) < max) OR
						        (ceiling(n_80) > min AND ceiling(n_80) < max) OR
						        (ceiling(n_81) > min AND ceiling(n_81) < max) OR 
						        (ceiling(n_82) > min AND ceiling(n_82) < max) OR 
						        (ceiling(n_83) > min AND ceiling(n_83) < max) OR 
						        (ceiling(n_84) > min AND ceiling(n_84) < max) OR 
						        (ceiling(n_85) > min AND ceiling(n_85) < max) OR
						        (ceiling(n_86) > min AND ceiling(n_86) < max) OR
						        (ceiling(n_87) > min AND ceiling(n_87) < max) OR
						        (ceiling(n_88) > min AND ceiling(n_88) < max) OR
						        (ceiling(n_89) > min AND ceiling(n_89) < max) OR
						        (ceiling(n_90) > min AND ceiling(n_90) < max)
						       ) OR 
						       (ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
						        ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
						        ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
						        ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
						        ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
						        ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
						        ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
						        ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
						        ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
						        ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
						        ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
						        ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
						        ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
						        ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
						        ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
						        ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_80) > max OR
						        ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_85) > max OR
						        ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max 
						       ) and ";
				}else{
					//$wheNya = "F-T-F";
					$wheNya = "((ceiling(n_1) > min AND ceiling(n_1) < max) AND
						        (ceiling(n_2) > min AND ceiling(n_2) < max) AND
						        (ceiling(n_3) > min AND ceiling(n_3) < max) AND
						        (ceiling(n_4) > min AND ceiling(n_4) < max) AND
						        (ceiling(n_5) > min AND ceiling(n_5) < max) AND
						        (ceiling(n_6) > min AND ceiling(n_6) < max) AND
						        (ceiling(n_7) > min AND ceiling(n_7) < max) AND
						        (ceiling(n_8) > min AND ceiling(n_8) < max) AND
						        (ceiling(n_9) > min AND ceiling(n_9) < max) AND
						        (ceiling(n_10) > min AND ceiling(n_10) < max) AND
						        (ceiling(n_11) > min AND ceiling(n_11) < max) AND
						        (ceiling(n_12) > min AND ceiling(n_12) < max) AND
						        (ceiling(n_13) > min AND ceiling(n_13) < max) AND
						        (ceiling(n_14) > min AND ceiling(n_14) < max) AND
						        (ceiling(n_15) > min AND ceiling(n_15) < max) AND
						        (ceiling(n_16) > min AND ceiling(n_16) < max) AND
						        (ceiling(n_17) > min AND ceiling(n_17) < max) AND
						        (ceiling(n_18) > min AND ceiling(n_18) < max) AND
						        (ceiling(n_19) > min AND ceiling(n_19) < max) AND
						        (ceiling(n_20) > min AND ceiling(n_20) < max) AND
						        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
						        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
						        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
						        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
						        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
						        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
						        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
						        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
						        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
						        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
						        (ceiling(n_21) > min AND ceiling(n_21) < max) AND
						        (ceiling(n_22) > min AND ceiling(n_22) < max) AND
						        (ceiling(n_23) > min AND ceiling(n_23) < max) AND
						        (ceiling(n_24) > min AND ceiling(n_24) < max) AND
						        (ceiling(n_25) > min AND ceiling(n_25) < max) AND
						        (ceiling(n_26) > min AND ceiling(n_26) < max) AND
						        (ceiling(n_27) > min AND ceiling(n_27) < max) AND
						        (ceiling(n_28) > min AND ceiling(n_28) < max) AND
						        (ceiling(n_29) > min AND ceiling(n_29) < max) AND
						        (ceiling(n_30) > min AND ceiling(n_30) < max) AND
						        (ceiling(n_31) > min AND ceiling(n_31) < max) AND
						        (ceiling(n_32) > min AND ceiling(n_32) < max) AND
						        (ceiling(n_33) > min AND ceiling(n_33) < max) AND
						        (ceiling(n_34) > min AND ceiling(n_34) < max) AND
						        (ceiling(n_35) > min AND ceiling(n_35) < max) AND
						        (ceiling(n_36) > min AND ceiling(n_36) < max) AND
						        (ceiling(n_37) > min AND ceiling(n_37) < max) AND
						        (ceiling(n_38) > min AND ceiling(n_38) < max) AND
						        (ceiling(n_39) > min AND ceiling(n_39) < max) AND
						        (ceiling(n_40) > min AND ceiling(n_40) < max) AND
						        (ceiling(n_41) > min AND ceiling(n_41) < max) AND
						        (ceiling(n_42) > min AND ceiling(n_42) < max) AND
						        (ceiling(n_43) > min AND ceiling(n_43) < max) AND
						        (ceiling(n_44) > min AND ceiling(n_44) < max) AND
						        (ceiling(n_45) > min AND ceiling(n_45) < max) AND
						        (ceiling(n_46) > min AND ceiling(n_46) < max) AND
						        (ceiling(n_47) > min AND ceiling(n_47) < max) AND
						        (ceiling(n_48) > min AND ceiling(n_48) < max) AND
						        (ceiling(n_49) > min AND ceiling(n_49) < max) AND
						        (ceiling(n_50) > min AND ceiling(n_50) < max) AND
						        (ceiling(n_51) > min AND ceiling(n_51) < max) AND
						        (ceiling(n_52) > min AND ceiling(n_52) < max) AND
						        (ceiling(n_53) > min AND ceiling(n_53) < max) AND
						        (ceiling(n_54) > min AND ceiling(n_54) < max) AND
						        (ceiling(n_55) > min AND ceiling(n_55) < max) AND
						        (ceiling(n_56) > min AND ceiling(n_56) < max) AND
						        (ceiling(n_57) > min AND ceiling(n_57) < max) AND
						        (ceiling(n_58) > min AND ceiling(n_58) < max) AND
						        (ceiling(n_59) > min AND ceiling(n_59) < max) AND
						        (ceiling(n_60) > min AND ceiling(n_60) < max) AND
						        (ceiling(n_61) > min AND ceiling(n_61) < max) AND
						        (ceiling(n_62) > min AND ceiling(n_62) < max) AND
						        (ceiling(n_63) > min AND ceiling(n_63) < max) AND
						        (ceiling(n_64) > min AND ceiling(n_64) < max) AND
						        (ceiling(n_65) > min AND ceiling(n_65) < max) AND
						        (ceiling(n_66) > min AND ceiling(n_66) < max) AND
						        (ceiling(n_67) > min AND ceiling(n_67) < max) AND
						        (ceiling(n_68) > min AND ceiling(n_68) < max) AND
						        (ceiling(n_69) > min AND ceiling(n_69) < max) AND
						        (ceiling(n_70) > min AND ceiling(n_70) < max) AND
						        (ceiling(n_71) > min AND ceiling(n_71) < max) AND
						        (ceiling(n_72) > min AND ceiling(n_72) < max) AND
						        (ceiling(n_73) > min AND ceiling(n_73) < max) AND
						        (ceiling(n_74) > min AND ceiling(n_74) < max) AND
						        (ceiling(n_75) > min AND ceiling(n_75) < max) AND
						        (ceiling(n_76) > min AND ceiling(n_76) < max) AND
						        (ceiling(n_77) > min AND ceiling(n_77) < max) AND
						        (ceiling(n_78) > min AND ceiling(n_78) < max) AND
						        (ceiling(n_79) > min AND ceiling(n_79) < max) AND
						        (ceiling(n_80) > min AND ceiling(n_80) < max) AND
						        (ceiling(n_81) > min AND ceiling(n_81) < max) AND
						        (ceiling(n_82) > min AND ceiling(n_82) < max) AND
						        (ceiling(n_83) > min AND ceiling(n_83) < max) AND
						        (ceiling(n_84) > min AND ceiling(n_84) < max) AND
						        (ceiling(n_85) > min AND ceiling(n_85) < max) AND
						        (ceiling(n_86) > min AND ceiling(n_86) < max) AND
						        (ceiling(n_87) > min AND ceiling(n_87) < max) AND
						        (ceiling(n_88) > min AND ceiling(n_88) < max) AND
						        (ceiling(n_89) > min AND ceiling(n_89) < max) AND
						        (ceiling(n_90) > min AND ceiling(n_90) < max)
						       ) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-F-T";
					$wheNya = "(ceiling(n_1) > max OR ceiling(n_2) > max OR ceiling(n_3) > max OR ceiling(n_4) > max OR ceiling(n_5) > max OR
					            ceiling(n_6) > max OR ceiling(n_7) > max OR ceiling(n_8) > max OR ceiling(n_9) > max OR ceiling(n_10) > max OR
					            ceiling(n_11) > max OR ceiling(n_12) > max OR ceiling(n_13) > max OR ceiling(n_14) > max OR ceiling(n_15) > max OR
					            ceiling(n_16) > max OR ceiling(n_17) > max OR ceiling(n_18) > max OR ceiling(n_19) > max OR ceiling(n_20) > max OR
					            ceiling(n_21) > max OR ceiling(n_22) > max OR ceiling(n_23) > max OR ceiling(n_24) > max OR ceiling(n_25) > max OR
					            ceiling(n_26) > max OR ceiling(n_27) > max OR ceiling(n_28) > max OR ceiling(n_29) > max OR ceiling(n_30) > max OR
					            ceiling(n_31) > max OR ceiling(n_32) > max OR ceiling(n_33) > max OR ceiling(n_34) > max OR ceiling(n_35) > max OR
					            ceiling(n_36) > max OR ceiling(n_37) > max OR ceiling(n_38) > max OR ceiling(n_39) > max OR ceiling(n_40) > max OR
					            ceiling(n_41) > max OR ceiling(n_42) > max OR ceiling(n_43) > max OR ceiling(n_44) > max OR ceiling(n_45) > max OR
					            ceiling(n_46) > max OR ceiling(n_47) > max OR ceiling(n_48) > max OR ceiling(n_49) > max OR ceiling(n_50) > max OR
					            ceiling(n_51) > max OR ceiling(n_52) > max OR ceiling(n_53) > max OR ceiling(n_54) > max OR ceiling(n_55) > max OR
					            ceiling(n_56) > max OR ceiling(n_57) > max OR ceiling(n_58) > max OR ceiling(n_59) > max OR ceiling(n_60) > max OR
					            ceiling(n_61) > max OR ceiling(n_62) > max OR ceiling(n_63) > max OR ceiling(n_64) > max OR ceiling(n_65) > max OR
					            ceiling(n_66) > max OR ceiling(n_67) > max OR ceiling(n_68) > max OR ceiling(n_69) > max OR ceiling(n_70) > max OR
					            ceiling(n_71) > max OR ceiling(n_72) > max OR ceiling(n_73) > max OR ceiling(n_74) > max OR ceiling(n_75) > max OR
					            ceiling(n_76) > max OR ceiling(n_77) > max OR ceiling(n_78) > max OR ceiling(n_79) > max OR ceiling(n_30) > max OR
					            ceiling(n_81) > max OR ceiling(n_82) > max OR ceiling(n_83) > max OR ceiling(n_84) > max OR ceiling(n_25) > max OR
					            ceiling(n_86) > max OR ceiling(n_87) > max OR ceiling(n_88) > max OR ceiling(n_89) > max OR ceiling(n_90) > max) and ";
				}else{
					//$wheNya = "F-F-F";
					$wheNya = " ";
				}
			}
		}	
	}


	if($ck_ai != 'true'){
		$ai = " and remark <> 100 ";
	}else{
		$ai = "  ";
	}

	$where ="where $item 
			$wheNya 
			item_desc is not null  $ai";
    

    

	
	 
	include("../../../connect/conn.php");

	$sql = "select item_desc, min, max, std, jum_item,
		case when jum_item = '1' then 
		(select item_no from ztb_config_rm where tipe=item_desc ) 
		else '-' end item_no,
		$avg $fieldNya
		from
		(
			select  item_desc, min, max, average as std, count ( distinct item_no) as jum_item,
			$avg $fieldNya,remark
			from (
			select  a.item_type as item_desc, b.item_no,
			$filedNya
			from zvw_mrp_head a
			left join ztb_mrp_data b on a.item_type = b.item_type and no_id = 1) aa
			left outer join 
			(
			select item_type,n_1 as min  from ztb_mrp_data where no_id in (5)
			) bb on aa.item_desc = bb.item_type
			left outer join
			(
			select item_type,n_1 as max from ztb_mrp_data where no_id in (7)
			) cc on aa.item_desc = cc.item_type
			left OUTER join
			(
			select tipe, average,remark from ztb_config_rm 
			) dd on aa.item_desc=dd.tipe
			$where
			group by item_desc, min, max, average, $fieldNya,remark)aa 
			order by cast(remark as int),item_desc asc";


	// echo $sql;
	$data = sqlsrv_query($connect, strtoupper($sql));

	$items = array();
	$rowno=0;
	while($row = sqlsrv_fetch_object($data)){
		array_push($items, $row);
		$a = intval($items[$rowno]->MIN);
		$z = intval($items[$rowno]->MAX);
		$s = intval($items[$rowno]->STD);
		$r = intval($items[$rowno]->AVG);

		for ($i=1; $i <= $j ; $i++) {
			$n = 'N_'.$i;
			$v = intval($items[$rowno]->$n);
			if($v > $a){
				if($v <= $z){
					$items[$rowno]->$n = '<span style="color: green;font-size:11px;"><b>'.$v.'</b></span>';	
				}else{
					$items[$rowno]->$n = '<span style="color: blue;font-size:11px;"><b>'.$v.'</b></span>';	
				}
			}else{
				$items[$rowno]->$n = '<span style="color:red;font-size:11px;"><b>'.$v.'</b></span>';
			}
		}

		if($r>=$a AND $r<=$z){
			$items[$rowno]->AVG = '<span style="color:green;font-size:11px;"><b>'.$r.'</b></span>';
		}elseif($r<$a){
			$items[$rowno]->AVG = '<span style="color:red;font-size:11px;"><b>'.$r.'</b></span>';
		}elseif($r>$z){
			$items[$rowno]->AVG = '<span style="color:blue;font-size:11px;"><b>'.$r.'</b></span>';
		}

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
	//echo $sql;
?>
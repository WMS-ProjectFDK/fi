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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5)/5) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min_days OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min) OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) 
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND 
							        (ceil(n_2) > min AND ceil(n_2) < max) AND 
							        (ceil(n_3) > min AND ceil(n_3) < max) AND 
							        (ceil(n_4) > min AND ceil(n_4) < max) AND 
							        (ceil(n_5) > min AND ceil(n_5) < max) 
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 10){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10)/10) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min) OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND 
							        (ceil(n_2) > min AND ceil(n_2) < max) AND 
							        (ceil(n_3) > min AND ceil(n_3) < max) AND 
							        (ceil(n_4) > min AND ceil(n_4) < max) AND 
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 20){
			$fieldNya = "n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10,
						 n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20)/20) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min) OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND 
							        (ceil(n_2) > min AND ceil(n_2) < max) AND 
							        (ceil(n_3) > min AND ceil(n_3) < max) AND 
							        (ceil(n_4) > min AND ceil(n_4) < max) AND 
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30)/30) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min) OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND 
							        (ceil(n_12) > min AND ceil(n_12) < max) AND 
							        (ceil(n_13) > min AND ceil(n_13) < max) AND 
							        (ceil(n_14) > min AND ceil(n_14) < max) AND 
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND 
							        (ceil(n_22) > min AND ceil(n_22) < max) AND 
							        (ceil(n_23) > min AND ceil(n_23) < max) AND 
							        (ceil(n_24) > min AND ceil(n_24) < max) AND 
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40)/40) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50)/50) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
				nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
				nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max) OR
									    (ceil(n_41) > min AND ceil(n_41) < max) OR 
									    (ceil(n_42) > min AND ceil(n_42) < max) OR 
									    (ceil(n_43) > min AND ceil(n_43) < max) OR 
									    (ceil(n_44) > min AND ceil(n_44) < max) OR 
									    (ceil(n_45) > min AND ceil(n_45) < max) OR
									    (ceil(n_46) > min AND ceil(n_46) < max) OR
									    (ceil(n_47) > min AND ceil(n_47) < max) OR
									    (ceil(n_48) > min AND ceil(n_48) < max) OR
									    (ceil(n_49) > min AND ceil(n_49) < max) OR
									    (ceil(n_50) > min AND ceil(n_50) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
									   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
									   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
						            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
						            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max) OR
							        (ceil(n_41) > min AND ceil(n_41) < max) OR 
							        (ceil(n_42) > min AND ceil(n_42) < max) OR 
							        (ceil(n_43) > min AND ceil(n_43) < max) OR 
							        (ceil(n_44) > min AND ceil(n_44) < max) OR 
							        (ceil(n_45) > min AND ceil(n_45) < max) OR
							        (ceil(n_46) > min AND ceil(n_46) < max) OR
							        (ceil(n_47) > min AND ceil(n_47) < max) OR
							        (ceil(n_48) > min AND ceil(n_48) < max) OR
							        (ceil(n_49) > min AND ceil(n_49) < max) OR
							        (ceil(n_50) > min AND ceil(n_50) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
							        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
							        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max) AND
							        (ceil(n_41) > min AND ceil(n_41) < max) AND
							        (ceil(n_42) > min AND ceil(n_42) < max) AND
							        (ceil(n_43) > min AND ceil(n_43) < max) AND
							        (ceil(n_44) > min AND ceil(n_44) < max) AND
							        (ceil(n_45) > min AND ceil(n_45) < max) AND
							        (ceil(n_46) > min AND ceil(n_46) < max) AND
							        (ceil(n_47) > min AND ceil(n_47) < max) AND
							        (ceil(n_48) > min AND ceil(n_48) < max) AND
							        (ceil(n_49) > min AND ceil(n_49) < max) AND
							        (ceil(n_50) > min AND ceil(n_50) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60)/60) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
				nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
				nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50,
				nvl(ceil(a.n_51),0) n_51, nvl(ceil(a.n_52),0) n_52, nvl(ceil(a.n_53),0) n_53, nvl(ceil(a.n_54),0) n_54, nvl(ceil(a.n_55),0) n_55,
				nvl(ceil(a.n_56),0) n_56, nvl(ceil(a.n_57),0) n_57, nvl(ceil(a.n_58),0) n_58, nvl(ceil(a.n_59),0) n_59, nvl(ceil(a.n_60),0) n_60 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max) OR
									    (ceil(n_41) > min AND ceil(n_41) < max) OR 
									    (ceil(n_42) > min AND ceil(n_42) < max) OR 
									    (ceil(n_43) > min AND ceil(n_43) < max) OR 
									    (ceil(n_44) > min AND ceil(n_44) < max) OR 
									    (ceil(n_45) > min AND ceil(n_45) < max) OR
									    (ceil(n_46) > min AND ceil(n_46) < max) OR
									    (ceil(n_47) > min AND ceil(n_47) < max) OR
									    (ceil(n_48) > min AND ceil(n_48) < max) OR
									    (ceil(n_49) > min AND ceil(n_49) < max) OR
									    (ceil(n_50) > min AND ceil(n_50) < max) OR
									    (ceil(n_51) > min AND ceil(n_51) < max) OR 
									    (ceil(n_52) > min AND ceil(n_52) < max) OR 
									    (ceil(n_53) > min AND ceil(n_53) < max) OR 
									    (ceil(n_54) > min AND ceil(n_54) < max) OR 
									    (ceil(n_55) > min AND ceil(n_55) < max) OR
									    (ceil(n_56) > min AND ceil(n_56) < max) OR
									    (ceil(n_57) > min AND ceil(n_57) < max) OR
									    (ceil(n_58) > min AND ceil(n_58) < max) OR
									    (ceil(n_59) > min AND ceil(n_59) < max) OR
									    (ceil(n_60) > min AND ceil(n_60) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
									   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
									   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
									   ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
									   ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
						            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
						            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
						            ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
						            ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max) OR
							        (ceil(n_41) > min AND ceil(n_41) < max) OR 
							        (ceil(n_42) > min AND ceil(n_42) < max) OR 
							        (ceil(n_43) > min AND ceil(n_43) < max) OR 
							        (ceil(n_44) > min AND ceil(n_44) < max) OR 
							        (ceil(n_45) > min AND ceil(n_45) < max) OR
							        (ceil(n_46) > min AND ceil(n_46) < max) OR
							        (ceil(n_47) > min AND ceil(n_47) < max) OR
							        (ceil(n_48) > min AND ceil(n_48) < max) OR
							        (ceil(n_49) > min AND ceil(n_49) < max) OR
							        (ceil(n_50) > min AND ceil(n_50) < max) OR
							        (ceil(n_51) > min AND ceil(n_51) < max) OR 
							        (ceil(n_52) > min AND ceil(n_52) < max) OR 
							        (ceil(n_53) > min AND ceil(n_53) < max) OR 
							        (ceil(n_54) > min AND ceil(n_54) < max) OR 
							        (ceil(n_55) > min AND ceil(n_55) < max) OR
							        (ceil(n_56) > min AND ceil(n_56) < max) OR
							        (ceil(n_57) > min AND ceil(n_57) < max) OR
							        (ceil(n_58) > min AND ceil(n_58) < max) OR
							        (ceil(n_59) > min AND ceil(n_59) < max) OR
							        (ceil(n_60) > min AND ceil(n_60) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
							        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
							        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
							        ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
							        ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max) AND
							        (ceil(n_41) > min AND ceil(n_41) < max) AND
							        (ceil(n_42) > min AND ceil(n_42) < max) AND
							        (ceil(n_43) > min AND ceil(n_43) < max) AND
							        (ceil(n_44) > min AND ceil(n_44) < max) AND
							        (ceil(n_45) > min AND ceil(n_45) < max) AND
							        (ceil(n_46) > min AND ceil(n_46) < max) AND
							        (ceil(n_47) > min AND ceil(n_47) < max) AND
							        (ceil(n_48) > min AND ceil(n_48) < max) AND
							        (ceil(n_49) > min AND ceil(n_49) < max) AND
							        (ceil(n_50) > min AND ceil(n_50) < max) AND
							        (ceil(n_51) > min AND ceil(n_51) < max) AND
							        (ceil(n_52) > min AND ceil(n_52) < max) AND
							        (ceil(n_53) > min AND ceil(n_53) < max) AND
							        (ceil(n_54) > min AND ceil(n_54) < max) AND
							        (ceil(n_55) > min AND ceil(n_55) < max) AND
							        (ceil(n_56) > min AND ceil(n_56) < max) AND
							        (ceil(n_57) > min AND ceil(n_57) < max) AND
							        (ceil(n_58) > min AND ceil(n_58) < max) AND
							        (ceil(n_59) > min AND ceil(n_59) < max) AND
							        (ceil(n_60) > min AND ceil(n_60) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
						            ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
						            ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70)/70) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
				nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
				nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50,
				nvl(ceil(a.n_51),0) n_51, nvl(ceil(a.n_52),0) n_52, nvl(ceil(a.n_53),0) n_53, nvl(ceil(a.n_54),0) n_54, nvl(ceil(a.n_55),0) n_55,
				nvl(ceil(a.n_56),0) n_56, nvl(ceil(a.n_57),0) n_57, nvl(ceil(a.n_58),0) n_58, nvl(ceil(a.n_59),0) n_59, nvl(ceil(a.n_60),0) n_60,
				nvl(ceil(a.n_61),0) n_61, nvl(ceil(a.n_62),0) n_62, nvl(ceil(a.n_63),0) n_63, nvl(ceil(a.n_64),0) n_64, nvl(ceil(a.n_65),0) n_65,
				nvl(ceil(a.n_66),0) n_66, nvl(ceil(a.n_67),0) n_67, nvl(ceil(a.n_68),0) n_68, nvl(ceil(a.n_69),0) n_69, nvl(ceil(a.n_70),0) n_70 ";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_^0) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_^5) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max) OR
									    (ceil(n_41) > min AND ceil(n_41) < max) OR 
									    (ceil(n_42) > min AND ceil(n_42) < max) OR 
									    (ceil(n_43) > min AND ceil(n_43) < max) OR 
									    (ceil(n_44) > min AND ceil(n_44) < max) OR 
									    (ceil(n_45) > min AND ceil(n_45) < max) OR
									    (ceil(n_46) > min AND ceil(n_46) < max) OR
									    (ceil(n_47) > min AND ceil(n_47) < max) OR
									    (ceil(n_48) > min AND ceil(n_48) < max) OR
									    (ceil(n_49) > min AND ceil(n_49) < max) OR
									    (ceil(n_50) > min AND ceil(n_50) < max) OR
									    (ceil(n_51) > min AND ceil(n_51) < max) OR 
									    (ceil(n_52) > min AND ceil(n_52) < max) OR 
									    (ceil(n_53) > min AND ceil(n_53) < max) OR 
									    (ceil(n_54) > min AND ceil(n_54) < max) OR 
									    (ceil(n_55) > min AND ceil(n_55) < max) OR
									    (ceil(n_56) > min AND ceil(n_56) < max) OR
									    (ceil(n_57) > min AND ceil(n_57) < max) OR
									    (ceil(n_58) > min AND ceil(n_58) < max) OR
									    (ceil(n_59) > min AND ceil(n_59) < max) OR
									    (ceil(n_60) > min AND ceil(n_60) < max) OR
									    (ceil(n_61) > min AND ceil(n_61) < max) OR 
									    (ceil(n_62) > min AND ceil(n_62) < max) OR 
									    (ceil(n_63) > min AND ceil(n_63) < max) OR 
									    (ceil(n_64) > min AND ceil(n_64) < max) OR 
									    (ceil(n_65) > min AND ceil(n_65) < max) OR
									    (ceil(n_66) > min AND ceil(n_66) < max) OR
									    (ceil(n_67) > min AND ceil(n_67) < max) OR
									    (ceil(n_68) > min AND ceil(n_68) < max) OR
									    (ceil(n_69) > min AND ceil(n_69) < max) OR
									    (ceil(n_70) > min AND ceil(n_70) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
									   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
									   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
									   ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
									   ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
									   ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
									   ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
						            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
						            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
						            ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
						            ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
						            ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
						            ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max) OR
							        (ceil(n_41) > min AND ceil(n_41) < max) OR 
							        (ceil(n_42) > min AND ceil(n_42) < max) OR 
							        (ceil(n_43) > min AND ceil(n_43) < max) OR 
							        (ceil(n_44) > min AND ceil(n_44) < max) OR 
							        (ceil(n_45) > min AND ceil(n_45) < max) OR
							        (ceil(n_46) > min AND ceil(n_46) < max) OR
							        (ceil(n_47) > min AND ceil(n_47) < max) OR
							        (ceil(n_48) > min AND ceil(n_48) < max) OR
							        (ceil(n_49) > min AND ceil(n_49) < max) OR
							        (ceil(n_50) > min AND ceil(n_50) < max) OR
							        (ceil(n_51) > min AND ceil(n_51) < max) OR 
							        (ceil(n_52) > min AND ceil(n_52) < max) OR 
							        (ceil(n_53) > min AND ceil(n_53) < max) OR 
							        (ceil(n_54) > min AND ceil(n_54) < max) OR 
							        (ceil(n_55) > min AND ceil(n_55) < max) OR
							        (ceil(n_56) > min AND ceil(n_56) < max) OR
							        (ceil(n_57) > min AND ceil(n_57) < max) OR
							        (ceil(n_58) > min AND ceil(n_58) < max) OR
							        (ceil(n_59) > min AND ceil(n_59) < max) OR
							        (ceil(n_60) > min AND ceil(n_60) < max) OR
							        (ceil(n_61) > min AND ceil(n_61) < max) OR 
							        (ceil(n_62) > min AND ceil(n_62) < max) OR 
							        (ceil(n_63) > min AND ceil(n_63) < max) OR 
							        (ceil(n_64) > min AND ceil(n_64) < max) OR 
							        (ceil(n_65) > min AND ceil(n_65) < max) OR
							        (ceil(n_66) > min AND ceil(n_66) < max) OR
							        (ceil(n_67) > min AND ceil(n_67) < max) OR
							        (ceil(n_68) > min AND ceil(n_68) < max) OR
							        (ceil(n_69) > min AND ceil(n_69) < max) OR
							        (ceil(n_70) > min AND ceil(n_70) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
							        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
							        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
							        ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
							        ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
							        ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
							        ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max) AND
							        (ceil(n_41) > min AND ceil(n_41) < max) AND
							        (ceil(n_42) > min AND ceil(n_42) < max) AND
							        (ceil(n_43) > min AND ceil(n_43) < max) AND
							        (ceil(n_44) > min AND ceil(n_44) < max) AND
							        (ceil(n_45) > min AND ceil(n_45) < max) AND
							        (ceil(n_46) > min AND ceil(n_46) < max) AND
							        (ceil(n_47) > min AND ceil(n_47) < max) AND
							        (ceil(n_48) > min AND ceil(n_48) < max) AND
							        (ceil(n_49) > min AND ceil(n_49) < max) AND
							        (ceil(n_50) > min AND ceil(n_50) < max) AND
							        (ceil(n_51) > min AND ceil(n_51) < max) AND
							        (ceil(n_52) > min AND ceil(n_52) < max) AND
							        (ceil(n_53) > min AND ceil(n_53) < max) AND
							        (ceil(n_54) > min AND ceil(n_54) < max) AND
							        (ceil(n_55) > min AND ceil(n_55) < max) AND
							        (ceil(n_56) > min AND ceil(n_56) < max) AND
							        (ceil(n_57) > min AND ceil(n_57) < max) AND
							        (ceil(n_58) > min AND ceil(n_58) < max) AND
							        (ceil(n_59) > min AND ceil(n_59) < max) AND
							        (ceil(n_60) > min AND ceil(n_60) < max) AND
							        (ceil(n_61) > min AND ceil(n_61) < max) AND
							        (ceil(n_62) > min AND ceil(n_62) < max) AND
							        (ceil(n_63) > min AND ceil(n_63) < max) AND
							        (ceil(n_64) > min AND ceil(n_64) < max) AND
							        (ceil(n_65) > min AND ceil(n_65) < max) AND
							        (ceil(n_66) > min AND ceil(n_66) < max) AND
							        (ceil(n_67) > min AND ceil(n_67) < max) AND
							        (ceil(n_68) > min AND ceil(n_68) < max) AND
							        (ceil(n_69) > min AND ceil(n_69) < max) AND
							        (ceil(n_70) > min AND ceil(n_70) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
						            ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
						            ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
						            ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
						            ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80)/80) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
				nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
				nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50,
				nvl(ceil(a.n_51),0) n_51, nvl(ceil(a.n_52),0) n_52, nvl(ceil(a.n_53),0) n_53, nvl(ceil(a.n_54),0) n_54, nvl(ceil(a.n_55),0) n_55,
				nvl(ceil(a.n_56),0) n_56, nvl(ceil(a.n_57),0) n_57, nvl(ceil(a.n_58),0) n_58, nvl(ceil(a.n_59),0) n_59, nvl(ceil(a.n_60),0) n_60,
				nvl(ceil(a.n_61),0) n_61, nvl(ceil(a.n_62),0) n_62, nvl(ceil(a.n_63),0) n_63, nvl(ceil(a.n_64),0) n_64, nvl(ceil(a.n_65),0) n_65,
				nvl(ceil(a.n_66),0) n_66, nvl(ceil(a.n_67),0) n_67, nvl(ceil(a.n_68),0) n_68, nvl(ceil(a.n_69),0) n_69, nvl(ceil(a.n_70),0) n_70,
				nvl(ceil(a.n_71),0) n_71, nvl(ceil(a.n_72),0) n_72, nvl(ceil(a.n_73),0) n_73, nvl(ceil(a.n_74),0) n_74, nvl(ceil(a.n_75),0) n_75,
				nvl(ceil(a.n_76),0) n_76, nvl(ceil(a.n_77),0) n_77, nvl(ceil(a.n_78),0) n_78, nvl(ceil(a.n_79),0) n_79, nvl(ceil(a.n_80),0) n_80 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
									   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
									   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max) OR
									    (ceil(n_41) > min AND ceil(n_41) < max) OR 
									    (ceil(n_42) > min AND ceil(n_42) < max) OR 
									    (ceil(n_43) > min AND ceil(n_43) < max) OR 
									    (ceil(n_44) > min AND ceil(n_44) < max) OR 
									    (ceil(n_45) > min AND ceil(n_45) < max) OR
									    (ceil(n_46) > min AND ceil(n_46) < max) OR
									    (ceil(n_47) > min AND ceil(n_47) < max) OR
									    (ceil(n_48) > min AND ceil(n_48) < max) OR
									    (ceil(n_49) > min AND ceil(n_49) < max) OR
									    (ceil(n_50) > min AND ceil(n_50) < max) OR
									    (ceil(n_51) > min AND ceil(n_51) < max) OR 
									    (ceil(n_52) > min AND ceil(n_52) < max) OR 
									    (ceil(n_53) > min AND ceil(n_53) < max) OR 
									    (ceil(n_54) > min AND ceil(n_54) < max) OR 
									    (ceil(n_55) > min AND ceil(n_55) < max) OR
									    (ceil(n_56) > min AND ceil(n_56) < max) OR
									    (ceil(n_57) > min AND ceil(n_57) < max) OR
									    (ceil(n_58) > min AND ceil(n_58) < max) OR
									    (ceil(n_59) > min AND ceil(n_59) < max) OR
									    (ceil(n_60) > min AND ceil(n_60) < max) OR
									    (ceil(n_61) > min AND ceil(n_61) < max) OR 
									    (ceil(n_62) > min AND ceil(n_62) < max) OR 
									    (ceil(n_63) > min AND ceil(n_63) < max) OR 
									    (ceil(n_64) > min AND ceil(n_64) < max) OR 
									    (ceil(n_65) > min AND ceil(n_65) < max) OR
									    (ceil(n_66) > min AND ceil(n_66) < max) OR
									    (ceil(n_67) > min AND ceil(n_67) < max) OR
									    (ceil(n_68) > min AND ceil(n_68) < max) OR
									    (ceil(n_69) > min AND ceil(n_69) < max) OR
									    (ceil(n_70) > min AND ceil(n_70) < max) OR
									    (ceil(n_71) > min AND ceil(n_71) < max) OR 
									    (ceil(n_72) > min AND ceil(n_72) < max) OR 
									    (ceil(n_73) > min AND ceil(n_73) < max) OR 
									    (ceil(n_74) > min AND ceil(n_74) < max) OR 
									    (ceil(n_75) > min AND ceil(n_75) < max) OR
									    (ceil(n_76) > min AND ceil(n_76) < max) OR
									    (ceil(n_77) > min AND ceil(n_77) < max) OR
									    (ceil(n_78) > min AND ceil(n_78) < max) OR
									    (ceil(n_79) > min AND ceil(n_79) < max) OR
									    (ceil(n_80) > min AND ceil(n_80) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
									   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
									   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
									   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
									   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
									   ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
									   ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
									   ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
									   ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
									   ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
									   ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
						            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
						            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
						            ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
						            ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
						            ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
						            ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
						            ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
						            ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max) OR
							        (ceil(n_41) > min AND ceil(n_41) < max) OR 
							        (ceil(n_42) > min AND ceil(n_42) < max) OR 
							        (ceil(n_43) > min AND ceil(n_43) < max) OR 
							        (ceil(n_44) > min AND ceil(n_44) < max) OR 
							        (ceil(n_45) > min AND ceil(n_45) < max) OR
							        (ceil(n_46) > min AND ceil(n_46) < max) OR
							        (ceil(n_47) > min AND ceil(n_47) < max) OR
							        (ceil(n_48) > min AND ceil(n_48) < max) OR
							        (ceil(n_49) > min AND ceil(n_49) < max) OR
							        (ceil(n_50) > min AND ceil(n_50) < max) OR
							        (ceil(n_51) > min AND ceil(n_51) < max) OR 
							        (ceil(n_52) > min AND ceil(n_52) < max) OR 
							        (ceil(n_53) > min AND ceil(n_53) < max) OR 
							        (ceil(n_54) > min AND ceil(n_54) < max) OR 
							        (ceil(n_55) > min AND ceil(n_55) < max) OR
							        (ceil(n_56) > min AND ceil(n_56) < max) OR
							        (ceil(n_57) > min AND ceil(n_57) < max) OR
							        (ceil(n_58) > min AND ceil(n_58) < max) OR
							        (ceil(n_59) > min AND ceil(n_59) < max) OR
							        (ceil(n_60) > min AND ceil(n_60) < max) OR
							        (ceil(n_61) > min AND ceil(n_61) < max) OR 
							        (ceil(n_62) > min AND ceil(n_62) < max) OR 
							        (ceil(n_63) > min AND ceil(n_63) < max) OR 
							        (ceil(n_64) > min AND ceil(n_64) < max) OR 
							        (ceil(n_65) > min AND ceil(n_65) < max) OR
							        (ceil(n_66) > min AND ceil(n_66) < max) OR
							        (ceil(n_67) > min AND ceil(n_67) < max) OR
							        (ceil(n_68) > min AND ceil(n_68) < max) OR
							        (ceil(n_69) > min AND ceil(n_69) < max) OR
							        (ceil(n_70) > min AND ceil(n_70) < max) OR
							        (ceil(n_71) > min AND ceil(n_71) < max) OR 
							        (ceil(n_72) > min AND ceil(n_72) < max) OR 
							        (ceil(n_73) > min AND ceil(n_73) < max) OR 
							        (ceil(n_74) > min AND ceil(n_74) < max) OR 
							        (ceil(n_75) > min AND ceil(n_75) < max) OR
							        (ceil(n_76) > min AND ceil(n_76) < max) OR
							        (ceil(n_77) > min AND ceil(n_77) < max) OR
							        (ceil(n_78) > min AND ceil(n_78) < max) OR
							        (ceil(n_79) > min AND ceil(n_79) < max) OR
							        (ceil(n_80) > min AND ceil(n_80) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
							        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
							        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
							        ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
							        ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
							        ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
							        ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
							        ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
							        ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max) AND
							        (ceil(n_41) > min AND ceil(n_41) < max) AND
							        (ceil(n_42) > min AND ceil(n_42) < max) AND
							        (ceil(n_43) > min AND ceil(n_43) < max) AND
							        (ceil(n_44) > min AND ceil(n_44) < max) AND
							        (ceil(n_45) > min AND ceil(n_45) < max) AND
							        (ceil(n_46) > min AND ceil(n_46) < max) AND
							        (ceil(n_47) > min AND ceil(n_47) < max) AND
							        (ceil(n_48) > min AND ceil(n_48) < max) AND
							        (ceil(n_49) > min AND ceil(n_49) < max) AND
							        (ceil(n_50) > min AND ceil(n_50) < max) AND
							        (ceil(n_51) > min AND ceil(n_51) < max) AND
							        (ceil(n_52) > min AND ceil(n_52) < max) AND
							        (ceil(n_53) > min AND ceil(n_53) < max) AND
							        (ceil(n_54) > min AND ceil(n_54) < max) AND
							        (ceil(n_55) > min AND ceil(n_55) < max) AND
							        (ceil(n_56) > min AND ceil(n_56) < max) AND
							        (ceil(n_57) > min AND ceil(n_57) < max) AND
							        (ceil(n_58) > min AND ceil(n_58) < max) AND
							        (ceil(n_59) > min AND ceil(n_59) < max) AND
							        (ceil(n_60) > min AND ceil(n_60) < max) AND
							        (ceil(n_61) > min AND ceil(n_61) < max) AND
							        (ceil(n_62) > min AND ceil(n_62) < max) AND
							        (ceil(n_63) > min AND ceil(n_63) < max) AND
							        (ceil(n_64) > min AND ceil(n_64) < max) AND
							        (ceil(n_65) > min AND ceil(n_65) < max) AND
							        (ceil(n_66) > min AND ceil(n_66) < max) AND
							        (ceil(n_67) > min AND ceil(n_67) < max) AND
							        (ceil(n_68) > min AND ceil(n_68) < max) AND
							        (ceil(n_69) > min AND ceil(n_69) < max) AND
							        (ceil(n_70) > min AND ceil(n_70) < max) AND
							        (ceil(n_71) > min AND ceil(n_71) < max) AND
							        (ceil(n_72) > min AND ceil(n_72) < max) AND
							        (ceil(n_73) > min AND ceil(n_73) < max) AND
							        (ceil(n_74) > min AND ceil(n_74) < max) AND
							        (ceil(n_75) > min AND ceil(n_75) < max) AND
							        (ceil(n_76) > min AND ceil(n_76) < max) AND
							        (ceil(n_77) > min AND ceil(n_77) < max) AND
							        (ceil(n_78) > min AND ceil(n_78) < max) AND
							        (ceil(n_79) > min AND ceil(n_79) < max) AND
							        (ceil(n_80) > min AND ceil(n_80) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
						            ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
						            ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
						            ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
						            ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
						            ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
						            ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max) and ";
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
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
						  n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";
			$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
				nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
				nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
				nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
				nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
				nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
				nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
				nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
				nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
				nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50,
				nvl(ceil(a.n_51),0) n_51, nvl(ceil(a.n_52),0) n_52, nvl(ceil(a.n_53),0) n_53, nvl(ceil(a.n_54),0) n_54, nvl(ceil(a.n_55),0) n_55,
				nvl(ceil(a.n_56),0) n_56, nvl(ceil(a.n_57),0) n_57, nvl(ceil(a.n_58),0) n_58, nvl(ceil(a.n_59),0) n_59, nvl(ceil(a.n_60),0) n_60,
				nvl(ceil(a.n_61),0) n_61, nvl(ceil(a.n_62),0) n_62, nvl(ceil(a.n_63),0) n_63, nvl(ceil(a.n_64),0) n_64, nvl(ceil(a.n_65),0) n_65,
				nvl(ceil(a.n_66),0) n_66, nvl(ceil(a.n_67),0) n_67, nvl(ceil(a.n_68),0) n_68, nvl(ceil(a.n_69),0) n_69, nvl(ceil(a.n_70),0) n_70,
				nvl(ceil(a.n_71),0) n_71, nvl(ceil(a.n_72),0) n_72, nvl(ceil(a.n_73),0) n_73, nvl(ceil(a.n_74),0) n_74, nvl(ceil(a.n_75),0) n_75,
				nvl(ceil(a.n_76),0) n_76, nvl(ceil(a.n_77),0) n_77, nvl(ceil(a.n_78),0) n_78, nvl(ceil(a.n_79),0) n_79, nvl(ceil(a.n_80),0) n_80,
				nvl(ceil(a.n_81),0) n_81, nvl(ceil(a.n_82),0) n_82, nvl(ceil(a.n_83),0) n_83, nvl(ceil(a.n_84),0) n_84, nvl(ceil(a.n_85),0) n_85, 
				nvl(ceil(a.n_86),0) n_86, nvl(ceil(a.n_87),0) n_87, nvl(ceil(a.n_88),0) n_88, nvl(ceil(a.n_89),0) n_89, nvl(ceil(a.n_90),0) n_90 ";
			
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_25) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_30) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_35) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_40) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_45) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_50) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_55) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_60) < min OR
									   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_65) < min OR
									   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_70) < min OR
									   ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_75) < min OR
									   ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_80) < min OR
									   ceil(n_91) < min OR ceil(n_92) < min OR ceil(n_93) < min OR ceil(n_94) < min OR ceil(n_85) < min OR
									   ceil(n_96) < min OR ceil(n_97) < min OR ceil(n_98) < min OR ceil(n_99) < min OR ceil(n_90) < min) OR 
									  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
									    (ceil(n_2) > min AND ceil(n_2) < max) OR 
									    (ceil(n_3) > min AND ceil(n_3) < max) OR 
									    (ceil(n_4) > min AND ceil(n_4) < max) OR 
									    (ceil(n_5) > min AND ceil(n_5) < max) OR
									    (ceil(n_6) > min AND ceil(n_6) < max) OR
									    (ceil(n_7) > min AND ceil(n_7) < max) OR
									    (ceil(n_8) > min AND ceil(n_8) < max) OR
									    (ceil(n_9) > min AND ceil(n_9) < max) OR
									    (ceil(n_10) > min AND ceil(n_10) < max) OR
									    (ceil(n_11) > min AND ceil(n_11) < max) OR 
									    (ceil(n_12) > min AND ceil(n_12) < max) OR 
									    (ceil(n_13) > min AND ceil(n_13) < max) OR 
									    (ceil(n_14) > min AND ceil(n_14) < max) OR 
									    (ceil(n_15) > min AND ceil(n_15) < max) OR
									    (ceil(n_16) > min AND ceil(n_16) < max) OR
									    (ceil(n_17) > min AND ceil(n_17) < max) OR
									    (ceil(n_18) > min AND ceil(n_18) < max) OR
									    (ceil(n_19) > min AND ceil(n_19) < max) OR
									    (ceil(n_20) > min AND ceil(n_20) < max) OR
									    (ceil(n_21) > min AND ceil(n_21) < max) OR 
									    (ceil(n_22) > min AND ceil(n_22) < max) OR 
									    (ceil(n_23) > min AND ceil(n_23) < max) OR 
									    (ceil(n_24) > min AND ceil(n_24) < max) OR 
									    (ceil(n_25) > min AND ceil(n_25) < max) OR
									    (ceil(n_26) > min AND ceil(n_26) < max) OR
									    (ceil(n_27) > min AND ceil(n_27) < max) OR
									    (ceil(n_28) > min AND ceil(n_28) < max) OR
									    (ceil(n_29) > min AND ceil(n_29) < max) OR
									    (ceil(n_30) > min AND ceil(n_30) < max) OR
									    (ceil(n_31) > min AND ceil(n_31) < max) OR 
									    (ceil(n_32) > min AND ceil(n_32) < max) OR 
									    (ceil(n_33) > min AND ceil(n_33) < max) OR 
									    (ceil(n_34) > min AND ceil(n_34) < max) OR 
									    (ceil(n_35) > min AND ceil(n_35) < max) OR
									    (ceil(n_36) > min AND ceil(n_36) < max) OR
									    (ceil(n_37) > min AND ceil(n_37) < max) OR
									    (ceil(n_38) > min AND ceil(n_38) < max) OR
									    (ceil(n_39) > min AND ceil(n_39) < max) OR
									    (ceil(n_40) > min AND ceil(n_40) < max) OR
									    (ceil(n_41) > min AND ceil(n_41) < max) OR 
									    (ceil(n_42) > min AND ceil(n_42) < max) OR 
									    (ceil(n_43) > min AND ceil(n_43) < max) OR 
									    (ceil(n_44) > min AND ceil(n_44) < max) OR 
									    (ceil(n_45) > min AND ceil(n_45) < max) OR
									    (ceil(n_46) > min AND ceil(n_46) < max) OR
									    (ceil(n_47) > min AND ceil(n_47) < max) OR
									    (ceil(n_48) > min AND ceil(n_48) < max) OR
									    (ceil(n_49) > min AND ceil(n_49) < max) OR
									    (ceil(n_50) > min AND ceil(n_50) < max) OR
									    (ceil(n_51) > min AND ceil(n_51) < max) OR 
									    (ceil(n_52) > min AND ceil(n_52) < max) OR 
									    (ceil(n_53) > min AND ceil(n_53) < max) OR 
									    (ceil(n_54) > min AND ceil(n_54) < max) OR 
									    (ceil(n_55) > min AND ceil(n_55) < max) OR
									    (ceil(n_56) > min AND ceil(n_56) < max) OR
									    (ceil(n_57) > min AND ceil(n_57) < max) OR
									    (ceil(n_58) > min AND ceil(n_58) < max) OR
									    (ceil(n_59) > min AND ceil(n_59) < max) OR
									    (ceil(n_60) > min AND ceil(n_60) < max) OR
									    (ceil(n_61) > min AND ceil(n_61) < max) OR 
									    (ceil(n_62) > min AND ceil(n_62) < max) OR 
									    (ceil(n_63) > min AND ceil(n_63) < max) OR 
									    (ceil(n_64) > min AND ceil(n_64) < max) OR 
									    (ceil(n_65) > min AND ceil(n_65) < max) OR
									    (ceil(n_66) > min AND ceil(n_66) < max) OR
									    (ceil(n_67) > min AND ceil(n_67) < max) OR
									    (ceil(n_68) > min AND ceil(n_68) < max) OR
									    (ceil(n_69) > min AND ceil(n_69) < max) OR
									    (ceil(n_70) > min AND ceil(n_70) < max) OR
									    (ceil(n_71) > min AND ceil(n_71) < max) OR 
									    (ceil(n_72) > min AND ceil(n_72) < max) OR 
									    (ceil(n_73) > min AND ceil(n_73) < max) OR 
									    (ceil(n_74) > min AND ceil(n_74) < max) OR 
									    (ceil(n_75) > min AND ceil(n_75) < max) OR
									    (ceil(n_76) > min AND ceil(n_76) < max) OR
									    (ceil(n_77) > min AND ceil(n_77) < max) OR
									    (ceil(n_78) > min AND ceil(n_78) < max) OR
									    (ceil(n_79) > min AND ceil(n_79) < max) OR
									    (ceil(n_80) > min AND ceil(n_80) < max) OR
									    (ceil(n_81) > min AND ceil(n_81) < max) OR 
									    (ceil(n_82) > min AND ceil(n_82) < max) OR 
									    (ceil(n_83) > min AND ceil(n_83) < max) OR 
									    (ceil(n_84) > min AND ceil(n_84) < max) OR 
									    (ceil(n_85) > min AND ceil(n_85) < max) OR
									    (ceil(n_86) > min AND ceil(n_86) < max) OR
									    (ceil(n_87) > min AND ceil(n_87) < max) OR
									    (ceil(n_88) > min AND ceil(n_88) < max) OR
									    (ceil(n_89) > min AND ceil(n_89) < max) OR
									    (ceil(n_90) > min AND ceil(n_90) < max)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
									   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
									   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
									   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
									   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
									   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
									   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
									   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
									   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
									   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
									   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
									   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
									   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
									   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
									   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
									   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min OR
									   ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_85) < min OR
									   ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_90) < min) 
									   OR 
									  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
									   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
									   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
									   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
									   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
									   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
									   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
									   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
									   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
									   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
									   ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
									   ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
									   ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
									   ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
									   ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
									   ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max OR
									   ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_85) > max OR
									   ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
						            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
						            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
						            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
						            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
						            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
						            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
						            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
						            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
						            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
						            ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
						            ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
						            ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
						            ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
						            ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
						            ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min OR
						            ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_85) < min OR
						            ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_90) < min) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
							        (ceil(n_2) > min AND ceil(n_2) < max) OR 
							        (ceil(n_3) > min AND ceil(n_3) < max) OR 
							        (ceil(n_4) > min AND ceil(n_4) < max) OR 
							        (ceil(n_5) > min AND ceil(n_5) < max) OR
							        (ceil(n_6) > min AND ceil(n_6) < max) OR
							        (ceil(n_7) > min AND ceil(n_7) < max) OR
							        (ceil(n_8) > min AND ceil(n_8) < max) OR
							        (ceil(n_9) > min AND ceil(n_9) < max) OR
							        (ceil(n_10) > min AND ceil(n_10) < max) OR
							        (ceil(n_11) > min AND ceil(n_11) < max) OR 
							        (ceil(n_12) > min AND ceil(n_12) < max) OR 
							        (ceil(n_13) > min AND ceil(n_13) < max) OR 
							        (ceil(n_14) > min AND ceil(n_14) < max) OR 
							        (ceil(n_15) > min AND ceil(n_15) < max) OR
							        (ceil(n_16) > min AND ceil(n_16) < max) OR
							        (ceil(n_17) > min AND ceil(n_17) < max) OR
							        (ceil(n_18) > min AND ceil(n_18) < max) OR
							        (ceil(n_19) > min AND ceil(n_19) < max) OR
							        (ceil(n_20) > min AND ceil(n_20) < max) OR
							        (ceil(n_21) > min AND ceil(n_21) < max) OR 
							        (ceil(n_22) > min AND ceil(n_22) < max) OR 
							        (ceil(n_23) > min AND ceil(n_23) < max) OR 
							        (ceil(n_24) > min AND ceil(n_24) < max) OR 
							        (ceil(n_25) > min AND ceil(n_25) < max) OR
							        (ceil(n_26) > min AND ceil(n_26) < max) OR
							        (ceil(n_27) > min AND ceil(n_27) < max) OR
							        (ceil(n_28) > min AND ceil(n_28) < max) OR
							        (ceil(n_29) > min AND ceil(n_29) < max) OR
							        (ceil(n_30) > min AND ceil(n_30) < max) OR
							        (ceil(n_31) > min AND ceil(n_31) < max) OR 
							        (ceil(n_32) > min AND ceil(n_32) < max) OR 
							        (ceil(n_33) > min AND ceil(n_33) < max) OR 
							        (ceil(n_34) > min AND ceil(n_34) < max) OR 
							        (ceil(n_35) > min AND ceil(n_35) < max) OR
							        (ceil(n_36) > min AND ceil(n_36) < max) OR
							        (ceil(n_37) > min AND ceil(n_37) < max) OR
							        (ceil(n_38) > min AND ceil(n_38) < max) OR
							        (ceil(n_39) > min AND ceil(n_39) < max) OR
							        (ceil(n_40) > min AND ceil(n_40) < max) OR
							        (ceil(n_41) > min AND ceil(n_41) < max) OR 
							        (ceil(n_42) > min AND ceil(n_42) < max) OR 
							        (ceil(n_43) > min AND ceil(n_43) < max) OR 
							        (ceil(n_44) > min AND ceil(n_44) < max) OR 
							        (ceil(n_45) > min AND ceil(n_45) < max) OR
							        (ceil(n_46) > min AND ceil(n_46) < max) OR
							        (ceil(n_47) > min AND ceil(n_47) < max) OR
							        (ceil(n_48) > min AND ceil(n_48) < max) OR
							        (ceil(n_49) > min AND ceil(n_49) < max) OR
							        (ceil(n_50) > min AND ceil(n_50) < max) OR
							        (ceil(n_51) > min AND ceil(n_51) < max) OR 
							        (ceil(n_52) > min AND ceil(n_52) < max) OR 
							        (ceil(n_53) > min AND ceil(n_53) < max) OR 
							        (ceil(n_54) > min AND ceil(n_54) < max) OR 
							        (ceil(n_55) > min AND ceil(n_55) < max) OR
							        (ceil(n_56) > min AND ceil(n_56) < max) OR
							        (ceil(n_57) > min AND ceil(n_57) < max) OR
							        (ceil(n_58) > min AND ceil(n_58) < max) OR
							        (ceil(n_59) > min AND ceil(n_59) < max) OR
							        (ceil(n_60) > min AND ceil(n_60) < max) OR
							        (ceil(n_61) > min AND ceil(n_61) < max) OR 
							        (ceil(n_62) > min AND ceil(n_62) < max) OR 
							        (ceil(n_63) > min AND ceil(n_63) < max) OR 
							        (ceil(n_64) > min AND ceil(n_64) < max) OR 
							        (ceil(n_65) > min AND ceil(n_65) < max) OR
							        (ceil(n_66) > min AND ceil(n_66) < max) OR
							        (ceil(n_67) > min AND ceil(n_67) < max) OR
							        (ceil(n_68) > min AND ceil(n_68) < max) OR
							        (ceil(n_69) > min AND ceil(n_69) < max) OR
							        (ceil(n_70) > min AND ceil(n_70) < max) OR
							        (ceil(n_71) > min AND ceil(n_71) < max) OR 
							        (ceil(n_72) > min AND ceil(n_72) < max) OR 
							        (ceil(n_73) > min AND ceil(n_73) < max) OR 
							        (ceil(n_74) > min AND ceil(n_74) < max) OR 
							        (ceil(n_75) > min AND ceil(n_75) < max) OR
							        (ceil(n_76) > min AND ceil(n_76) < max) OR
							        (ceil(n_77) > min AND ceil(n_77) < max) OR
							        (ceil(n_78) > min AND ceil(n_78) < max) OR
							        (ceil(n_79) > min AND ceil(n_79) < max) OR
							        (ceil(n_80) > min AND ceil(n_80) < max) OR
							        (ceil(n_81) > min AND ceil(n_81) < max) OR 
							        (ceil(n_82) > min AND ceil(n_82) < max) OR 
							        (ceil(n_83) > min AND ceil(n_83) < max) OR 
							        (ceil(n_84) > min AND ceil(n_84) < max) OR 
							        (ceil(n_85) > min AND ceil(n_85) < max) OR
							        (ceil(n_86) > min AND ceil(n_86) < max) OR
							        (ceil(n_87) > min AND ceil(n_87) < max) OR
							        (ceil(n_88) > min AND ceil(n_88) < max) OR
							        (ceil(n_89) > min AND ceil(n_89) < max) OR
							        (ceil(n_90) > min AND ceil(n_90) < max)
							       ) OR 
							       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
							        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
							        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
							        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
							        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
							        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
							        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
							        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
							        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
							        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
							        ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
							        ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
							        ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
							        ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
							        ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
							        ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max OR
							        ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_85) > max OR
							        ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max 
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
							        (ceil(n_2) > min AND ceil(n_2) < max) AND
							        (ceil(n_3) > min AND ceil(n_3) < max) AND
							        (ceil(n_4) > min AND ceil(n_4) < max) AND
							        (ceil(n_5) > min AND ceil(n_5) < max) AND
							        (ceil(n_6) > min AND ceil(n_6) < max) AND
							        (ceil(n_7) > min AND ceil(n_7) < max) AND
							        (ceil(n_8) > min AND ceil(n_8) < max) AND
							        (ceil(n_9) > min AND ceil(n_9) < max) AND
							        (ceil(n_10) > min AND ceil(n_10) < max) AND
							        (ceil(n_11) > min AND ceil(n_11) < max) AND
							        (ceil(n_12) > min AND ceil(n_12) < max) AND
							        (ceil(n_13) > min AND ceil(n_13) < max) AND
							        (ceil(n_14) > min AND ceil(n_14) < max) AND
							        (ceil(n_15) > min AND ceil(n_15) < max) AND
							        (ceil(n_16) > min AND ceil(n_16) < max) AND
							        (ceil(n_17) > min AND ceil(n_17) < max) AND
							        (ceil(n_18) > min AND ceil(n_18) < max) AND
							        (ceil(n_19) > min AND ceil(n_19) < max) AND
							        (ceil(n_20) > min AND ceil(n_20) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_21) > min AND ceil(n_21) < max) AND
							        (ceil(n_22) > min AND ceil(n_22) < max) AND
							        (ceil(n_23) > min AND ceil(n_23) < max) AND
							        (ceil(n_24) > min AND ceil(n_24) < max) AND
							        (ceil(n_25) > min AND ceil(n_25) < max) AND
							        (ceil(n_26) > min AND ceil(n_26) < max) AND
							        (ceil(n_27) > min AND ceil(n_27) < max) AND
							        (ceil(n_28) > min AND ceil(n_28) < max) AND
							        (ceil(n_29) > min AND ceil(n_29) < max) AND
							        (ceil(n_30) > min AND ceil(n_30) < max) AND
							        (ceil(n_31) > min AND ceil(n_31) < max) AND
							        (ceil(n_32) > min AND ceil(n_32) < max) AND
							        (ceil(n_33) > min AND ceil(n_33) < max) AND
							        (ceil(n_34) > min AND ceil(n_34) < max) AND
							        (ceil(n_35) > min AND ceil(n_35) < max) AND
							        (ceil(n_36) > min AND ceil(n_36) < max) AND
							        (ceil(n_37) > min AND ceil(n_37) < max) AND
							        (ceil(n_38) > min AND ceil(n_38) < max) AND
							        (ceil(n_39) > min AND ceil(n_39) < max) AND
							        (ceil(n_40) > min AND ceil(n_40) < max) AND
							        (ceil(n_41) > min AND ceil(n_41) < max) AND
							        (ceil(n_42) > min AND ceil(n_42) < max) AND
							        (ceil(n_43) > min AND ceil(n_43) < max) AND
							        (ceil(n_44) > min AND ceil(n_44) < max) AND
							        (ceil(n_45) > min AND ceil(n_45) < max) AND
							        (ceil(n_46) > min AND ceil(n_46) < max) AND
							        (ceil(n_47) > min AND ceil(n_47) < max) AND
							        (ceil(n_48) > min AND ceil(n_48) < max) AND
							        (ceil(n_49) > min AND ceil(n_49) < max) AND
							        (ceil(n_50) > min AND ceil(n_50) < max) AND
							        (ceil(n_51) > min AND ceil(n_51) < max) AND
							        (ceil(n_52) > min AND ceil(n_52) < max) AND
							        (ceil(n_53) > min AND ceil(n_53) < max) AND
							        (ceil(n_54) > min AND ceil(n_54) < max) AND
							        (ceil(n_55) > min AND ceil(n_55) < max) AND
							        (ceil(n_56) > min AND ceil(n_56) < max) AND
							        (ceil(n_57) > min AND ceil(n_57) < max) AND
							        (ceil(n_58) > min AND ceil(n_58) < max) AND
							        (ceil(n_59) > min AND ceil(n_59) < max) AND
							        (ceil(n_60) > min AND ceil(n_60) < max) AND
							        (ceil(n_61) > min AND ceil(n_61) < max) AND
							        (ceil(n_62) > min AND ceil(n_62) < max) AND
							        (ceil(n_63) > min AND ceil(n_63) < max) AND
							        (ceil(n_64) > min AND ceil(n_64) < max) AND
							        (ceil(n_65) > min AND ceil(n_65) < max) AND
							        (ceil(n_66) > min AND ceil(n_66) < max) AND
							        (ceil(n_67) > min AND ceil(n_67) < max) AND
							        (ceil(n_68) > min AND ceil(n_68) < max) AND
							        (ceil(n_69) > min AND ceil(n_69) < max) AND
							        (ceil(n_70) > min AND ceil(n_70) < max) AND
							        (ceil(n_71) > min AND ceil(n_71) < max) AND
							        (ceil(n_72) > min AND ceil(n_72) < max) AND
							        (ceil(n_73) > min AND ceil(n_73) < max) AND
							        (ceil(n_74) > min AND ceil(n_74) < max) AND
							        (ceil(n_75) > min AND ceil(n_75) < max) AND
							        (ceil(n_76) > min AND ceil(n_76) < max) AND
							        (ceil(n_77) > min AND ceil(n_77) < max) AND
							        (ceil(n_78) > min AND ceil(n_78) < max) AND
							        (ceil(n_79) > min AND ceil(n_79) < max) AND
							        (ceil(n_80) > min AND ceil(n_80) < max) AND
							        (ceil(n_81) > min AND ceil(n_81) < max) AND
							        (ceil(n_82) > min AND ceil(n_82) < max) AND
							        (ceil(n_83) > min AND ceil(n_83) < max) AND
							        (ceil(n_84) > min AND ceil(n_84) < max) AND
							        (ceil(n_85) > min AND ceil(n_85) < max) AND
							        (ceil(n_86) > min AND ceil(n_86) < max) AND
							        (ceil(n_87) > min AND ceil(n_87) < max) AND
							        (ceil(n_88) > min AND ceil(n_88) < max) AND
							        (ceil(n_89) > min AND ceil(n_89) < max) AND
							        (ceil(n_90) > min AND ceil(n_90) < max)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
						            ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
						            ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
						            ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
						            ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
						            ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
						            ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_30) > max OR
						            ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_25) > max OR
						            ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max) and ";
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
		$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
						  n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";
		$filedNya ="nvl(ceil(a.n_1),0) n_1, nvl(ceil(a.n_2),0) n_2, nvl(ceil(a.n_3),0) n_3, nvl(ceil(a.n_4),0) n_4, nvl(ceil(a.n_5),0) n_5, 
			nvl(ceil(a.n_6),0) n_6, nvl(ceil(a.n_7),0) n_7, nvl(ceil(a.n_8),0) n_8, nvl(ceil(a.n_9),0) n_9, nvl(ceil(a.n_10),0) n_10,
			nvl(ceil(a.n_11),0) n_11, nvl(ceil(a.n_12),0) n_12, nvl(ceil(a.n_13),0) n_13, nvl(ceil(a.n_14),0) n_14, nvl(ceil(a.n_15),0) n_15, 
			nvl(ceil(a.n_16),0) n_16, nvl(ceil(a.n_17),0) n_17, nvl(ceil(a.n_18),0) n_18, nvl(ceil(a.n_19),0) n_19, nvl(ceil(a.n_20),0) n_20,
			nvl(ceil(a.n_21),0) n_21, nvl(ceil(a.n_22),0) n_22, nvl(ceil(a.n_23),0) n_23, nvl(ceil(a.n_24),0) n_24, nvl(ceil(a.n_25),0) n_25, 
			nvl(ceil(a.n_26),0) n_26, nvl(ceil(a.n_27),0) n_27, nvl(ceil(a.n_28),0) n_28, nvl(ceil(a.n_29),0) n_29, nvl(ceil(a.n_30),0) n_30,
			nvl(ceil(a.n_31),0) n_31, nvl(ceil(a.n_32),0) n_32, nvl(ceil(a.n_33),0) n_33, nvl(ceil(a.n_34),0) n_34, nvl(ceil(a.n_35),0) n_35, 
			nvl(ceil(a.n_36),0) n_36, nvl(ceil(a.n_37),0) n_37, nvl(ceil(a.n_38),0) n_38, nvl(ceil(a.n_39),0) n_39, nvl(ceil(a.n_40),0) n_40,
			nvl(ceil(a.n_41),0) n_41, nvl(ceil(a.n_42),0) n_42, nvl(ceil(a.n_43),0) n_43, nvl(ceil(a.n_44),0) n_44, nvl(ceil(a.n_45),0) n_45, 
			nvl(ceil(a.n_46),0) n_46, nvl(ceil(a.n_47),0) n_47, nvl(ceil(a.n_48),0) n_48, nvl(ceil(a.n_49),0) n_49, nvl(ceil(a.n_50),0) n_50,
			nvl(ceil(a.n_51),0) n_51, nvl(ceil(a.n_52),0) n_52, nvl(ceil(a.n_53),0) n_53, nvl(ceil(a.n_54),0) n_54, nvl(ceil(a.n_55),0) n_55,
			nvl(ceil(a.n_56),0) n_56, nvl(ceil(a.n_57),0) n_57, nvl(ceil(a.n_58),0) n_58, nvl(ceil(a.n_59),0) n_59, nvl(ceil(a.n_60),0) n_60,
			nvl(ceil(a.n_61),0) n_61, nvl(ceil(a.n_62),0) n_62, nvl(ceil(a.n_63),0) n_63, nvl(ceil(a.n_64),0) n_64, nvl(ceil(a.n_65),0) n_65,
			nvl(ceil(a.n_66),0) n_66, nvl(ceil(a.n_67),0) n_67, nvl(ceil(a.n_68),0) n_68, nvl(ceil(a.n_69),0) n_69, nvl(ceil(a.n_70),0) n_70,
			nvl(ceil(a.n_71),0) n_71, nvl(ceil(a.n_72),0) n_72, nvl(ceil(a.n_73),0) n_73, nvl(ceil(a.n_74),0) n_74, nvl(ceil(a.n_75),0) n_75,
			nvl(ceil(a.n_76),0) n_76, nvl(ceil(a.n_77),0) n_77, nvl(ceil(a.n_78),0) n_78, nvl(ceil(a.n_79),0) n_79, nvl(ceil(a.n_80),0) n_80,
			nvl(ceil(a.n_81),0) n_81, nvl(ceil(a.n_82),0) n_82, nvl(ceil(a.n_83),0) n_83, nvl(ceil(a.n_84),0) n_84, nvl(ceil(a.n_85),0) n_85, 
			nvl(ceil(a.n_86),0) n_86, nvl(ceil(a.n_87),0) n_87, nvl(ceil(a.n_88),0) n_88, nvl(ceil(a.n_89),0) n_89, nvl(ceil(a.n_90),0) n_90 ";
		
		if ($exp_ito[0] == 'T') {
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-T-T";
					$wheNya = " ";
				}else{
					//$wheNya = "T-T-F";
					$wheNya = " (
								  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
								   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
								   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
								   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
								   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
								   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
								   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
								   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
								   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
								   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
								   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
								   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
								   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
								   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
								   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
								   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min OR
								   ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_85) < min OR
								   ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_90) < min) OR 
								  ( (ceil(n_1) > min AND ceil(n_1) < max) OR 
								    (ceil(n_2) > min AND ceil(n_2) < max) OR 
								    (ceil(n_3) > min AND ceil(n_3) < max) OR 
								    (ceil(n_4) > min AND ceil(n_4) < max) OR 
								    (ceil(n_5) > min AND ceil(n_5) < max) OR
								    (ceil(n_6) > min AND ceil(n_6) < max) OR
								    (ceil(n_7) > min AND ceil(n_7) < max) OR
								    (ceil(n_8) > min AND ceil(n_8) < max) OR
								    (ceil(n_9) > min AND ceil(n_9) < max) OR
								    (ceil(n_10) > min AND ceil(n_10) < max) OR
								    (ceil(n_11) > min AND ceil(n_11) < max) OR 
								    (ceil(n_12) > min AND ceil(n_12) < max) OR 
								    (ceil(n_13) > min AND ceil(n_13) < max) OR 
								    (ceil(n_14) > min AND ceil(n_14) < max) OR 
								    (ceil(n_15) > min AND ceil(n_15) < max) OR
								    (ceil(n_16) > min AND ceil(n_16) < max) OR
								    (ceil(n_17) > min AND ceil(n_17) < max) OR
								    (ceil(n_18) > min AND ceil(n_18) < max) OR
								    (ceil(n_19) > min AND ceil(n_19) < max) OR
								    (ceil(n_20) > min AND ceil(n_20) < max) OR
								    (ceil(n_21) > min AND ceil(n_21) < max) OR 
								    (ceil(n_22) > min AND ceil(n_22) < max) OR 
								    (ceil(n_23) > min AND ceil(n_23) < max) OR 
								    (ceil(n_24) > min AND ceil(n_24) < max) OR 
								    (ceil(n_25) > min AND ceil(n_25) < max) OR
								    (ceil(n_26) > min AND ceil(n_26) < max) OR
								    (ceil(n_27) > min AND ceil(n_27) < max) OR
								    (ceil(n_28) > min AND ceil(n_28) < max) OR
								    (ceil(n_29) > min AND ceil(n_29) < max) OR
								    (ceil(n_30) > min AND ceil(n_30) < max) OR
								    (ceil(n_31) > min AND ceil(n_31) < max) OR 
								    (ceil(n_32) > min AND ceil(n_32) < max) OR 
								    (ceil(n_33) > min AND ceil(n_33) < max) OR 
								    (ceil(n_34) > min AND ceil(n_34) < max) OR 
								    (ceil(n_35) > min AND ceil(n_35) < max) OR
								    (ceil(n_36) > min AND ceil(n_36) < max) OR
								    (ceil(n_37) > min AND ceil(n_37) < max) OR
								    (ceil(n_38) > min AND ceil(n_38) < max) OR
								    (ceil(n_39) > min AND ceil(n_39) < max) OR
								    (ceil(n_40) > min AND ceil(n_40) < max) OR
								    (ceil(n_41) > min AND ceil(n_41) < max) OR 
								    (ceil(n_42) > min AND ceil(n_42) < max) OR 
								    (ceil(n_43) > min AND ceil(n_43) < max) OR 
								    (ceil(n_44) > min AND ceil(n_44) < max) OR 
								    (ceil(n_45) > min AND ceil(n_45) < max) OR
								    (ceil(n_46) > min AND ceil(n_46) < max) OR
								    (ceil(n_47) > min AND ceil(n_47) < max) OR
								    (ceil(n_48) > min AND ceil(n_48) < max) OR
								    (ceil(n_49) > min AND ceil(n_49) < max) OR
								    (ceil(n_50) > min AND ceil(n_50) < max) OR
								    (ceil(n_51) > min AND ceil(n_51) < max) OR 
								    (ceil(n_52) > min AND ceil(n_52) < max) OR 
								    (ceil(n_53) > min AND ceil(n_53) < max) OR 
								    (ceil(n_54) > min AND ceil(n_54) < max) OR 
								    (ceil(n_55) > min AND ceil(n_55) < max) OR
								    (ceil(n_56) > min AND ceil(n_56) < max) OR
								    (ceil(n_57) > min AND ceil(n_57) < max) OR
								    (ceil(n_58) > min AND ceil(n_58) < max) OR
								    (ceil(n_59) > min AND ceil(n_59) < max) OR
								    (ceil(n_60) > min AND ceil(n_60) < max) OR
								    (ceil(n_61) > min AND ceil(n_61) < max) OR 
								    (ceil(n_62) > min AND ceil(n_62) < max) OR 
								    (ceil(n_63) > min AND ceil(n_63) < max) OR 
								    (ceil(n_64) > min AND ceil(n_64) < max) OR 
								    (ceil(n_65) > min AND ceil(n_65) < max) OR
								    (ceil(n_66) > min AND ceil(n_66) < max) OR
								    (ceil(n_67) > min AND ceil(n_67) < max) OR
								    (ceil(n_68) > min AND ceil(n_68) < max) OR
								    (ceil(n_69) > min AND ceil(n_69) < max) OR
								    (ceil(n_70) > min AND ceil(n_70) < max) OR
								    (ceil(n_71) > min AND ceil(n_71) < max) OR 
								    (ceil(n_72) > min AND ceil(n_72) < max) OR 
								    (ceil(n_73) > min AND ceil(n_73) < max) OR 
								    (ceil(n_74) > min AND ceil(n_74) < max) OR 
								    (ceil(n_75) > min AND ceil(n_75) < max) OR
								    (ceil(n_76) > min AND ceil(n_76) < max) OR
								    (ceil(n_77) > min AND ceil(n_77) < max) OR
								    (ceil(n_78) > min AND ceil(n_78) < max) OR
								    (ceil(n_79) > min AND ceil(n_79) < max) OR
								    (ceil(n_80) > min AND ceil(n_80) < max) OR
								    (ceil(n_81) > min AND ceil(n_81) < max) OR 
								    (ceil(n_82) > min AND ceil(n_82) < max) OR 
								    (ceil(n_83) > min AND ceil(n_83) < max) OR 
								    (ceil(n_84) > min AND ceil(n_84) < max) OR 
								    (ceil(n_85) > min AND ceil(n_85) < max) OR
								    (ceil(n_86) > min AND ceil(n_86) < max) OR
								    (ceil(n_87) > min AND ceil(n_87) < max) OR
								    (ceil(n_88) > min AND ceil(n_88) < max) OR
								    (ceil(n_89) > min AND ceil(n_89) < max) OR
								    (ceil(n_90) > min AND ceil(n_90) < max)
								  )
								) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-F-T";
					$wheNya = " (
								  (ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
								   ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
								   ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
								   ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
								   ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
								   ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
								   ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
								   ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
								   ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
								   ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
								   ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
								   ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
								   ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
								   ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
								   ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
								   ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min OR
								   ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_85) < min OR
								   ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_90) < min) 
								   OR 
								  (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
								   ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
								   ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
								   ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
								   ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
								   ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
								   ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
								   ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
								   ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
								   ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
								   ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
								   ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
								   ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
								   ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
								   ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
								   ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max OR
								   ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_85) > max OR
								   ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max)
								) and ";
				}else{
					//$wheNya = "T-F-F";
					$wheNya = "(ceil(n_1) < min OR ceil(n_2) < min OR ceil(n_3) < min OR ceil(n_4) < min OR ceil(n_5) < min OR
					            ceil(n_6) < min OR ceil(n_7) < min OR ceil(n_8) < min OR ceil(n_9) < min OR ceil(n_10) < min OR
					            ceil(n_11) < min OR ceil(n_12) < min OR ceil(n_13) < min OR ceil(n_14) < min OR ceil(n_15) < min OR
					            ceil(n_16) < min OR ceil(n_17) < min OR ceil(n_18) < min OR ceil(n_19) < min OR ceil(n_20) < min OR
					            ceil(n_21) < min OR ceil(n_22) < min OR ceil(n_23) < min OR ceil(n_24) < min OR ceil(n_25) < min OR
					            ceil(n_26) < min OR ceil(n_27) < min OR ceil(n_28) < min OR ceil(n_29) < min OR ceil(n_30) < min OR
					            ceil(n_31) < min OR ceil(n_32) < min OR ceil(n_33) < min OR ceil(n_34) < min OR ceil(n_35) < min OR
					            ceil(n_36) < min OR ceil(n_37) < min OR ceil(n_38) < min OR ceil(n_39) < min OR ceil(n_40) < min OR
					            ceil(n_41) < min OR ceil(n_42) < min OR ceil(n_43) < min OR ceil(n_44) < min OR ceil(n_45) < min OR
					            ceil(n_46) < min OR ceil(n_47) < min OR ceil(n_48) < min OR ceil(n_49) < min OR ceil(n_50) < min OR
					            ceil(n_51) < min OR ceil(n_52) < min OR ceil(n_53) < min OR ceil(n_54) < min OR ceil(n_55) < min OR
					            ceil(n_56) < min OR ceil(n_57) < min OR ceil(n_58) < min OR ceil(n_59) < min OR ceil(n_60) < min OR
					            ceil(n_61) < min OR ceil(n_62) < min OR ceil(n_63) < min OR ceil(n_64) < min OR ceil(n_65) < min OR
					            ceil(n_66) < min OR ceil(n_67) < min OR ceil(n_68) < min OR ceil(n_69) < min OR ceil(n_70) < min OR
					            ceil(n_71) < min OR ceil(n_72) < min OR ceil(n_73) < min OR ceil(n_74) < min OR ceil(n_75) < min OR
					            ceil(n_76) < min OR ceil(n_77) < min OR ceil(n_78) < min OR ceil(n_79) < min OR ceil(n_80) < min OR
					            ceil(n_81) < min OR ceil(n_82) < min OR ceil(n_83) < min OR ceil(n_84) < min OR ceil(n_85) < min OR
					            ceil(n_86) < min OR ceil(n_87) < min OR ceil(n_88) < min OR ceil(n_89) < min OR ceil(n_90) < min) and ";
				}
			}
		}else{
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-T-T";
					$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) OR 
						        (ceil(n_2) > min AND ceil(n_2) < max) OR 
						        (ceil(n_3) > min AND ceil(n_3) < max) OR 
						        (ceil(n_4) > min AND ceil(n_4) < max) OR 
						        (ceil(n_5) > min AND ceil(n_5) < max) OR
						        (ceil(n_6) > min AND ceil(n_6) < max) OR
						        (ceil(n_7) > min AND ceil(n_7) < max) OR
						        (ceil(n_8) > min AND ceil(n_8) < max) OR
						        (ceil(n_9) > min AND ceil(n_9) < max) OR
						        (ceil(n_10) > min AND ceil(n_10) < max) OR
						        (ceil(n_11) > min AND ceil(n_11) < max) OR 
						        (ceil(n_12) > min AND ceil(n_12) < max) OR 
						        (ceil(n_13) > min AND ceil(n_13) < max) OR 
						        (ceil(n_14) > min AND ceil(n_14) < max) OR 
						        (ceil(n_15) > min AND ceil(n_15) < max) OR
						        (ceil(n_16) > min AND ceil(n_16) < max) OR
						        (ceil(n_17) > min AND ceil(n_17) < max) OR
						        (ceil(n_18) > min AND ceil(n_18) < max) OR
						        (ceil(n_19) > min AND ceil(n_19) < max) OR
						        (ceil(n_20) > min AND ceil(n_20) < max) OR
						        (ceil(n_21) > min AND ceil(n_21) < max) OR 
						        (ceil(n_22) > min AND ceil(n_22) < max) OR 
						        (ceil(n_23) > min AND ceil(n_23) < max) OR 
						        (ceil(n_24) > min AND ceil(n_24) < max) OR 
						        (ceil(n_25) > min AND ceil(n_25) < max) OR
						        (ceil(n_26) > min AND ceil(n_26) < max) OR
						        (ceil(n_27) > min AND ceil(n_27) < max) OR
						        (ceil(n_28) > min AND ceil(n_28) < max) OR
						        (ceil(n_29) > min AND ceil(n_29) < max) OR
						        (ceil(n_30) > min AND ceil(n_30) < max) OR
						        (ceil(n_31) > min AND ceil(n_31) < max) OR 
						        (ceil(n_32) > min AND ceil(n_32) < max) OR 
						        (ceil(n_33) > min AND ceil(n_33) < max) OR 
						        (ceil(n_34) > min AND ceil(n_34) < max) OR 
						        (ceil(n_35) > min AND ceil(n_35) < max) OR
						        (ceil(n_36) > min AND ceil(n_36) < max) OR
						        (ceil(n_37) > min AND ceil(n_37) < max) OR
						        (ceil(n_38) > min AND ceil(n_38) < max) OR
						        (ceil(n_39) > min AND ceil(n_39) < max) OR
						        (ceil(n_40) > min AND ceil(n_40) < max) OR
						        (ceil(n_41) > min AND ceil(n_41) < max) OR 
						        (ceil(n_42) > min AND ceil(n_42) < max) OR 
						        (ceil(n_43) > min AND ceil(n_43) < max) OR 
						        (ceil(n_44) > min AND ceil(n_44) < max) OR 
						        (ceil(n_45) > min AND ceil(n_45) < max) OR
						        (ceil(n_46) > min AND ceil(n_46) < max) OR
						        (ceil(n_47) > min AND ceil(n_47) < max) OR
						        (ceil(n_48) > min AND ceil(n_48) < max) OR
						        (ceil(n_49) > min AND ceil(n_49) < max) OR
						        (ceil(n_50) > min AND ceil(n_50) < max) OR
						        (ceil(n_51) > min AND ceil(n_51) < max) OR 
						        (ceil(n_52) > min AND ceil(n_52) < max) OR 
						        (ceil(n_53) > min AND ceil(n_53) < max) OR 
						        (ceil(n_54) > min AND ceil(n_54) < max) OR 
						        (ceil(n_55) > min AND ceil(n_55) < max) OR
						        (ceil(n_56) > min AND ceil(n_56) < max) OR
						        (ceil(n_57) > min AND ceil(n_57) < max) OR
						        (ceil(n_58) > min AND ceil(n_58) < max) OR
						        (ceil(n_59) > min AND ceil(n_59) < max) OR
						        (ceil(n_60) > min AND ceil(n_60) < max) OR
						        (ceil(n_61) > min AND ceil(n_61) < max) OR 
						        (ceil(n_62) > min AND ceil(n_62) < max) OR 
						        (ceil(n_63) > min AND ceil(n_63) < max) OR 
						        (ceil(n_64) > min AND ceil(n_64) < max) OR 
						        (ceil(n_65) > min AND ceil(n_65) < max) OR
						        (ceil(n_66) > min AND ceil(n_66) < max) OR
						        (ceil(n_67) > min AND ceil(n_67) < max) OR
						        (ceil(n_68) > min AND ceil(n_68) < max) OR
						        (ceil(n_69) > min AND ceil(n_69) < max) OR
						        (ceil(n_70) > min AND ceil(n_70) < max) OR
						        (ceil(n_71) > min AND ceil(n_71) < max) OR 
						        (ceil(n_72) > min AND ceil(n_72) < max) OR 
						        (ceil(n_73) > min AND ceil(n_73) < max) OR 
						        (ceil(n_74) > min AND ceil(n_74) < max) OR 
						        (ceil(n_75) > min AND ceil(n_75) < max) OR
						        (ceil(n_76) > min AND ceil(n_76) < max) OR
						        (ceil(n_77) > min AND ceil(n_77) < max) OR
						        (ceil(n_78) > min AND ceil(n_78) < max) OR
						        (ceil(n_79) > min AND ceil(n_79) < max) OR
						        (ceil(n_80) > min AND ceil(n_80) < max) OR
						        (ceil(n_81) > min AND ceil(n_81) < max) OR 
						        (ceil(n_82) > min AND ceil(n_82) < max) OR 
						        (ceil(n_83) > min AND ceil(n_83) < max) OR 
						        (ceil(n_84) > min AND ceil(n_84) < max) OR 
						        (ceil(n_85) > min AND ceil(n_85) < max) OR
						        (ceil(n_86) > min AND ceil(n_86) < max) OR
						        (ceil(n_87) > min AND ceil(n_87) < max) OR
						        (ceil(n_88) > min AND ceil(n_88) < max) OR
						        (ceil(n_89) > min AND ceil(n_89) < max) OR
						        (ceil(n_90) > min AND ceil(n_90) < max)
						       ) OR 
						       (ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
						        ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
						        ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
						        ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
						        ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
						        ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
						        ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
						        ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
						        ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
						        ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
						        ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
						        ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
						        ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
						        ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
						        ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
						        ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_80) > max OR
						        ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_85) > max OR
						        ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max 
						       ) and ";
				}else{
					//$wheNya = "F-T-F";
					$wheNya = "((ceil(n_1) > min AND ceil(n_1) < max) AND
						        (ceil(n_2) > min AND ceil(n_2) < max) AND
						        (ceil(n_3) > min AND ceil(n_3) < max) AND
						        (ceil(n_4) > min AND ceil(n_4) < max) AND
						        (ceil(n_5) > min AND ceil(n_5) < max) AND
						        (ceil(n_6) > min AND ceil(n_6) < max) AND
						        (ceil(n_7) > min AND ceil(n_7) < max) AND
						        (ceil(n_8) > min AND ceil(n_8) < max) AND
						        (ceil(n_9) > min AND ceil(n_9) < max) AND
						        (ceil(n_10) > min AND ceil(n_10) < max) AND
						        (ceil(n_11) > min AND ceil(n_11) < max) AND
						        (ceil(n_12) > min AND ceil(n_12) < max) AND
						        (ceil(n_13) > min AND ceil(n_13) < max) AND
						        (ceil(n_14) > min AND ceil(n_14) < max) AND
						        (ceil(n_15) > min AND ceil(n_15) < max) AND
						        (ceil(n_16) > min AND ceil(n_16) < max) AND
						        (ceil(n_17) > min AND ceil(n_17) < max) AND
						        (ceil(n_18) > min AND ceil(n_18) < max) AND
						        (ceil(n_19) > min AND ceil(n_19) < max) AND
						        (ceil(n_20) > min AND ceil(n_20) < max) AND
						        (ceil(n_21) > min AND ceil(n_21) < max) AND
						        (ceil(n_22) > min AND ceil(n_22) < max) AND
						        (ceil(n_23) > min AND ceil(n_23) < max) AND
						        (ceil(n_24) > min AND ceil(n_24) < max) AND
						        (ceil(n_25) > min AND ceil(n_25) < max) AND
						        (ceil(n_26) > min AND ceil(n_26) < max) AND
						        (ceil(n_27) > min AND ceil(n_27) < max) AND
						        (ceil(n_28) > min AND ceil(n_28) < max) AND
						        (ceil(n_29) > min AND ceil(n_29) < max) AND
						        (ceil(n_30) > min AND ceil(n_30) < max) AND
						        (ceil(n_21) > min AND ceil(n_21) < max) AND
						        (ceil(n_22) > min AND ceil(n_22) < max) AND
						        (ceil(n_23) > min AND ceil(n_23) < max) AND
						        (ceil(n_24) > min AND ceil(n_24) < max) AND
						        (ceil(n_25) > min AND ceil(n_25) < max) AND
						        (ceil(n_26) > min AND ceil(n_26) < max) AND
						        (ceil(n_27) > min AND ceil(n_27) < max) AND
						        (ceil(n_28) > min AND ceil(n_28) < max) AND
						        (ceil(n_29) > min AND ceil(n_29) < max) AND
						        (ceil(n_30) > min AND ceil(n_30) < max) AND
						        (ceil(n_31) > min AND ceil(n_31) < max) AND
						        (ceil(n_32) > min AND ceil(n_32) < max) AND
						        (ceil(n_33) > min AND ceil(n_33) < max) AND
						        (ceil(n_34) > min AND ceil(n_34) < max) AND
						        (ceil(n_35) > min AND ceil(n_35) < max) AND
						        (ceil(n_36) > min AND ceil(n_36) < max) AND
						        (ceil(n_37) > min AND ceil(n_37) < max) AND
						        (ceil(n_38) > min AND ceil(n_38) < max) AND
						        (ceil(n_39) > min AND ceil(n_39) < max) AND
						        (ceil(n_40) > min AND ceil(n_40) < max) AND
						        (ceil(n_41) > min AND ceil(n_41) < max) AND
						        (ceil(n_42) > min AND ceil(n_42) < max) AND
						        (ceil(n_43) > min AND ceil(n_43) < max) AND
						        (ceil(n_44) > min AND ceil(n_44) < max) AND
						        (ceil(n_45) > min AND ceil(n_45) < max) AND
						        (ceil(n_46) > min AND ceil(n_46) < max) AND
						        (ceil(n_47) > min AND ceil(n_47) < max) AND
						        (ceil(n_48) > min AND ceil(n_48) < max) AND
						        (ceil(n_49) > min AND ceil(n_49) < max) AND
						        (ceil(n_50) > min AND ceil(n_50) < max) AND
						        (ceil(n_51) > min AND ceil(n_51) < max) AND
						        (ceil(n_52) > min AND ceil(n_52) < max) AND
						        (ceil(n_53) > min AND ceil(n_53) < max) AND
						        (ceil(n_54) > min AND ceil(n_54) < max) AND
						        (ceil(n_55) > min AND ceil(n_55) < max) AND
						        (ceil(n_56) > min AND ceil(n_56) < max) AND
						        (ceil(n_57) > min AND ceil(n_57) < max) AND
						        (ceil(n_58) > min AND ceil(n_58) < max) AND
						        (ceil(n_59) > min AND ceil(n_59) < max) AND
						        (ceil(n_60) > min AND ceil(n_60) < max) AND
						        (ceil(n_61) > min AND ceil(n_61) < max) AND
						        (ceil(n_62) > min AND ceil(n_62) < max) AND
						        (ceil(n_63) > min AND ceil(n_63) < max) AND
						        (ceil(n_64) > min AND ceil(n_64) < max) AND
						        (ceil(n_65) > min AND ceil(n_65) < max) AND
						        (ceil(n_66) > min AND ceil(n_66) < max) AND
						        (ceil(n_67) > min AND ceil(n_67) < max) AND
						        (ceil(n_68) > min AND ceil(n_68) < max) AND
						        (ceil(n_69) > min AND ceil(n_69) < max) AND
						        (ceil(n_70) > min AND ceil(n_70) < max) AND
						        (ceil(n_71) > min AND ceil(n_71) < max) AND
						        (ceil(n_72) > min AND ceil(n_72) < max) AND
						        (ceil(n_73) > min AND ceil(n_73) < max) AND
						        (ceil(n_74) > min AND ceil(n_74) < max) AND
						        (ceil(n_75) > min AND ceil(n_75) < max) AND
						        (ceil(n_76) > min AND ceil(n_76) < max) AND
						        (ceil(n_77) > min AND ceil(n_77) < max) AND
						        (ceil(n_78) > min AND ceil(n_78) < max) AND
						        (ceil(n_79) > min AND ceil(n_79) < max) AND
						        (ceil(n_80) > min AND ceil(n_80) < max) AND
						        (ceil(n_81) > min AND ceil(n_81) < max) AND
						        (ceil(n_82) > min AND ceil(n_82) < max) AND
						        (ceil(n_83) > min AND ceil(n_83) < max) AND
						        (ceil(n_84) > min AND ceil(n_84) < max) AND
						        (ceil(n_85) > min AND ceil(n_85) < max) AND
						        (ceil(n_86) > min AND ceil(n_86) < max) AND
						        (ceil(n_87) > min AND ceil(n_87) < max) AND
						        (ceil(n_88) > min AND ceil(n_88) < max) AND
						        (ceil(n_89) > min AND ceil(n_89) < max) AND
						        (ceil(n_90) > min AND ceil(n_90) < max)
						       ) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-F-T";
					$wheNya = "(ceil(n_1) > max OR ceil(n_2) > max OR ceil(n_3) > max OR ceil(n_4) > max OR ceil(n_5) > max OR
					            ceil(n_6) > max OR ceil(n_7) > max OR ceil(n_8) > max OR ceil(n_9) > max OR ceil(n_10) > max OR
					            ceil(n_11) > max OR ceil(n_12) > max OR ceil(n_13) > max OR ceil(n_14) > max OR ceil(n_15) > max OR
					            ceil(n_16) > max OR ceil(n_17) > max OR ceil(n_18) > max OR ceil(n_19) > max OR ceil(n_20) > max OR
					            ceil(n_21) > max OR ceil(n_22) > max OR ceil(n_23) > max OR ceil(n_24) > max OR ceil(n_25) > max OR
					            ceil(n_26) > max OR ceil(n_27) > max OR ceil(n_28) > max OR ceil(n_29) > max OR ceil(n_30) > max OR
					            ceil(n_31) > max OR ceil(n_32) > max OR ceil(n_33) > max OR ceil(n_34) > max OR ceil(n_35) > max OR
					            ceil(n_36) > max OR ceil(n_37) > max OR ceil(n_38) > max OR ceil(n_39) > max OR ceil(n_40) > max OR
					            ceil(n_41) > max OR ceil(n_42) > max OR ceil(n_43) > max OR ceil(n_44) > max OR ceil(n_45) > max OR
					            ceil(n_46) > max OR ceil(n_47) > max OR ceil(n_48) > max OR ceil(n_49) > max OR ceil(n_50) > max OR
					            ceil(n_51) > max OR ceil(n_52) > max OR ceil(n_53) > max OR ceil(n_54) > max OR ceil(n_55) > max OR
					            ceil(n_56) > max OR ceil(n_57) > max OR ceil(n_58) > max OR ceil(n_59) > max OR ceil(n_60) > max OR
					            ceil(n_61) > max OR ceil(n_62) > max OR ceil(n_63) > max OR ceil(n_64) > max OR ceil(n_65) > max OR
					            ceil(n_66) > max OR ceil(n_67) > max OR ceil(n_68) > max OR ceil(n_69) > max OR ceil(n_70) > max OR
					            ceil(n_71) > max OR ceil(n_72) > max OR ceil(n_73) > max OR ceil(n_74) > max OR ceil(n_75) > max OR
					            ceil(n_76) > max OR ceil(n_77) > max OR ceil(n_78) > max OR ceil(n_79) > max OR ceil(n_30) > max OR
					            ceil(n_81) > max OR ceil(n_82) > max OR ceil(n_83) > max OR ceil(n_84) > max OR ceil(n_25) > max OR
					            ceil(n_86) > max OR ceil(n_87) > max OR ceil(n_88) > max OR ceil(n_89) > max OR ceil(n_90) > max) and ";
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
    

    

	
	 
	include("../connect/conn.php");

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
			group by item_desc, min, max, average, $fieldNya,remark 
			order by cast(remark as int),item_desc asc
		)";


	//echo $sql;
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
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
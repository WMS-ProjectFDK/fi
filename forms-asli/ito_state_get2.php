<?php
	session_start();
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
	$cmb_day = isset($_REQUEST['cmb_day']) ? strval($_REQUEST['cmb_day']) : '';
	$ck_day = isset($_REQUEST['ck_day']) ? strval($_REQUEST['ck_day']) : '';
	$sts_ito = isset($_REQUEST['sts_ito']) ? strval($_REQUEST['sts_ito']) : '';

	if($ck_item_no != 'true'){
		$item = "a.item_no = $cmb_item_no and ";
	}else{
		$item = " ";
	}

	$exp_ito = explode('-', $sts_ito);

	if ($ck_day != "true"){
		$j = $cmb_day;
		if ($cmb_day == 5){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5 ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5)/5) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days) OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) 
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) 
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 10){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR 
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days) and ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10)/10) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days) OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 20){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR 
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20)/20) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days) OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 30){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30)/30) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days) OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 40){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40)/40) as avg,";
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 50){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
						nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
			//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
			//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50)/50) as avg,";
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
									    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
									    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
									    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
									    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
									    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
									    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
									    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
									    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
									    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
									    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
									   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
									   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
						            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
						            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
							        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
							        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 60){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
						nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50,
						nvl(ceil(n_51),0) n_51, nvl(ceil(n_52),0) n_52, nvl(ceil(n_53),0) n_53, nvl(ceil(n_54),0) n_54, nvl(ceil(n_55),0) n_55, nvl(ceil(n_56),0) n_56, nvl(ceil(n_57),0) n_57, nvl(ceil(n_58),0) n_58, nvl(ceil(n_59),0) n_59, nvl(ceil(n_60),0) n_60 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
			//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
			//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
			//		   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR 
			//		   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60)/60) as avg,";
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
									    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
									    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
									    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
									    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
									    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
									    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
									    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
									    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
									    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
									    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
									    (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
									    (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
									    (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
									    (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
									    (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
									    (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
									    (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
									    (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
									    (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
									    (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
									   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
									   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
									   ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
									   ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
						            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
						            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
						            ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
						            ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
							        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
							        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
							        ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
							        ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) AND
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) AND
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) AND
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) AND
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) AND
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) AND
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) AND
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) AND
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) AND
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) AND
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
						            ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
						            ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 70){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
						nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50,
						nvl(ceil(n_51),0) n_51, nvl(ceil(n_52),0) n_52, nvl(ceil(n_53),0) n_53, nvl(ceil(n_54),0) n_54, nvl(ceil(n_55),0) n_55, nvl(ceil(n_56),0) n_56, nvl(ceil(n_57),0) n_57, nvl(ceil(n_58),0) n_58, nvl(ceil(n_59),0) n_59, nvl(ceil(n_60),0) n_60,
						nvl(ceil(n_61),0) n_61, nvl(ceil(n_62),0) n_62, nvl(ceil(n_63),0) n_63, nvl(ceil(n_64),0) n_64, nvl(ceil(n_65),0) n_65, nvl(ceil(n_66),0) n_66, nvl(ceil(n_67),0) n_67, nvl(ceil(n_68),0) n_68, nvl(ceil(n_69),0) n_69, nvl(ceil(n_70),0) n_70 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
			//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
			//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
			//		   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR 
			//		   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
			//		   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR 
			//		   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70)/70) as avg,";
			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_^0) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_^5) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
									    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
									    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
									    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
									    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
									    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
									    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
									    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
									    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
									    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
									    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
									    (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
									    (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
									    (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
									    (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
									    (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
									    (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
									    (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
									    (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
									    (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
									    (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
									    (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
									    (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
									    (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
									    (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
									    (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
									    (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
									    (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
									    (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
									    (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
									    (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
									   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
									   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
									   ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
									   ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
									   ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
									   ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
						            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
						            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
						            ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
						            ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
						            ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
						            ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
							        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
							        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
							        ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
							        ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
							        ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
							        ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) AND
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) AND
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) AND
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) AND
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) AND
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) AND
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) AND
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) AND
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) AND
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) AND
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) AND
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) AND
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) AND
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) AND
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) AND
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) AND
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) AND
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) AND
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) AND
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) AND
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
						            ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
						            ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
						            ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
						            ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 80){
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
						nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50,
						nvl(ceil(n_51),0) n_51, nvl(ceil(n_52),0) n_52, nvl(ceil(n_53),0) n_53, nvl(ceil(n_54),0) n_54, nvl(ceil(n_55),0) n_55, nvl(ceil(n_56),0) n_56, nvl(ceil(n_57),0) n_57, nvl(ceil(n_58),0) n_58, nvl(ceil(n_59),0) n_59, nvl(ceil(n_60),0) n_60,
						nvl(ceil(n_61),0) n_61, nvl(ceil(n_62),0) n_62, nvl(ceil(n_63),0) n_63, nvl(ceil(n_64),0) n_64, nvl(ceil(n_65),0) n_65, nvl(ceil(n_66),0) n_66, nvl(ceil(n_67),0) n_67, nvl(ceil(n_68),0) n_68, nvl(ceil(n_69),0) n_69, nvl(ceil(n_70),0) n_70,
						nvl(ceil(n_71),0) n_71, nvl(ceil(n_72),0) n_72, nvl(ceil(n_73),0) n_73, nvl(ceil(n_74),0) n_74, nvl(ceil(n_75),0) n_75, nvl(ceil(n_76),0) n_76, nvl(ceil(n_77),0) n_77, nvl(ceil(n_78),0) n_78, nvl(ceil(n_79),0) n_79, nvl(ceil(n_80),0) n_80 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
			//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
			//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
			//		   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR 
			//		   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
			//		   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR 
			//		   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
			//		   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR 
			//		   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80)/80) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
									   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
									   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
									    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
									    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
									    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
									    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
									    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
									    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
									    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
									    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
									    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
									    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
									    (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
									    (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
									    (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
									    (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
									    (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
									    (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
									    (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
									    (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
									    (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
									    (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
									    (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
									    (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
									    (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
									    (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
									    (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
									    (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
									    (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
									    (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
									    (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
									    (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
									    (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
									    (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
									    (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
									    (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
									    (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
									    (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
									    (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
									    (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
									    (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
									    (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
									   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
									   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
									   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
									   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
									   ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
									   ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
									   ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
									   ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
									   ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
									   ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
						            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
						            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
						            ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
						            ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
						            ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
						            ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
						            ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
						            ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
							        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
							        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
							        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
							        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
							        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
							        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
							        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
							        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
							        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
							        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
							        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
							        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
							        ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
							        ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
							        ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
							        ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
							        ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
							        ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) AND
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) AND
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) AND
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) AND
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) AND
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) AND
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) AND
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) AND
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) AND
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) AND
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) AND
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) AND
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) AND
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) AND
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) AND
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) AND
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) AND
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) AND
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) AND
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) AND
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) AND
							        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) AND
							        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) AND
							        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) AND
							        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) AND
							        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) AND
							        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) AND
							        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) AND
							        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) AND
							        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) AND
							        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
						            ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
						            ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
						            ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
						            ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
						            ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
						            ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}else{
			$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
						nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
						nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
						nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
						nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50,
						nvl(ceil(n_51),0) n_51, nvl(ceil(n_52),0) n_52, nvl(ceil(n_53),0) n_53, nvl(ceil(n_54),0) n_54, nvl(ceil(n_55),0) n_55, nvl(ceil(n_56),0) n_56, nvl(ceil(n_57),0) n_57, nvl(ceil(n_58),0) n_58, nvl(ceil(n_59),0) n_59, nvl(ceil(n_60),0) n_60,
						nvl(ceil(n_61),0) n_61, nvl(ceil(n_62),0) n_62, nvl(ceil(n_63),0) n_63, nvl(ceil(n_64),0) n_64, nvl(ceil(n_65),0) n_65, nvl(ceil(n_66),0) n_66, nvl(ceil(n_67),0) n_67, nvl(ceil(n_68),0) n_68, nvl(ceil(n_69),0) n_69, nvl(ceil(n_70),0) n_70,
						nvl(ceil(n_71),0) n_71, nvl(ceil(n_72),0) n_72, nvl(ceil(n_73),0) n_73, nvl(ceil(n_74),0) n_74, nvl(ceil(n_75),0) n_75, nvl(ceil(n_76),0) n_76, nvl(ceil(n_77),0) n_77, nvl(ceil(n_78),0) n_78, nvl(ceil(n_79),0) n_79, nvl(ceil(n_80),0) n_80,
						nvl(ceil(n_81),0) n_81, nvl(ceil(n_82),0) n_82, nvl(ceil(n_83),0) n_83, nvl(ceil(n_84),0) n_84, nvl(ceil(n_85),0) n_85, nvl(ceil(n_86),0) n_86, nvl(ceil(n_87),0) n_87, nvl(ceil(n_88),0) n_88, nvl(ceil(n_89),0) n_89, nvl(ceil(n_90),0) n_90 ";
			//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
			//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
			//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
			//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
			//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
			//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
			//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
			//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
			//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
			//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
			//		   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR 
			//		   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
			//		   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR 
			//		   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
			//		   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR 
			//		   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
			//		   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR 
			//		   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) AND ";
			$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
						  n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
						  n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
						  n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
						  n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
						  n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
						  n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
						  n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_60) < b.min_days OR
									   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_65) < b.min_days OR
									   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_70) < b.min_days OR
									   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_75) < b.min_days OR
									   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_80) < b.min_days OR
									   ceil(n_91) < b.min_days OR ceil(n_92) < b.min_days OR ceil(n_93) < b.min_days OR ceil(n_94) < b.min_days OR ceil(n_85) < b.min_days OR
									   ceil(n_96) < b.min_days OR ceil(n_97) < b.min_days OR ceil(n_98) < b.min_days OR ceil(n_99) < b.min_days OR ceil(n_90) < b.min_days) OR 
									  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
									    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
									    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
									    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
									    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
									    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
									    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
									    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
									    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
									    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
									    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
									    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
									    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
									    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
									    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
									    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
									    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
									    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
									    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
									    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
									    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
									    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
									    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
									    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
									    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
									    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
									    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
									    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
									    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
									    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
									    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
									    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
									    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
									    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
									    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
									    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
									    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
									    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
									    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
									    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
									    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
									    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
									    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
									    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
									    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
									    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
									    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
									    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
									    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
									    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
									    (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
									    (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
									    (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
									    (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
									    (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
									    (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
									    (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
									    (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
									    (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
									    (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
									    (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
									    (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
									    (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
									    (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
									    (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
									    (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
									    (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
									    (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
									    (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
									    (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
									    (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
									    (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
									    (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
									    (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
									    (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
									    (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
									    (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
									    (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
									    (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
									    (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) OR
									    (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) OR 
									    (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) OR 
									    (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) OR 
									    (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) OR 
									    (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) OR
									    (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) OR
									    (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) OR
									    (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) OR
									    (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) OR
									    (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
									   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
									   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
									   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
									   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
									   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
									   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
									   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
									   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
									   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
									   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
									   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
									   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
									   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
									   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
									   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
									   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR
									   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) 
									   OR 
									  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
									   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
									   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
									   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
									   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
									   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
									   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
									   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
									   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
									   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
									   ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
									   ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
									   ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
									   ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
									   ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
									   ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days OR
									   ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_85) > b.max_days OR
									   ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
						            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
						            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
						            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
						            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
						            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
						            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
						            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
						            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
						            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
						            ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
						            ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
						            ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
						            ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
						            ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
						            ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
						            ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR
						            ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
							        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
							        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
							        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
							        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
							        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
							        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
							        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
							        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
							        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
							        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) OR
							        (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) OR 
							        (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) OR 
							        (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) OR 
							        (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) OR 
							        (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) OR
							        (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) OR
							        (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) OR
							        (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) OR
							        (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) OR
							        (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
							       ) OR 
							       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
							        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
							        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
							        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
							        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
							        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
							        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
							        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
							        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
							        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
							        ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
							        ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
							        ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
							        ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
							        ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
							        ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days OR
							        ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_85) > b.max_days OR
							        ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days 
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
							        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
							        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
							        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
							        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
							        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
							        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
							        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
							        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
							        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
							        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
							        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
							        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
							        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
							        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
							        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
							        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
							        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
							        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
							        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
							        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
							        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
							        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
							        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
							        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
							        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
							        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
							        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
							        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
							        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
							        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
							        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
							        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
							        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
							        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
							        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
							        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
							        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
							        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
							        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
							        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
							        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
							        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
							        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
							        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
							        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
							        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
							        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
							        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) AND
							        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) AND
							        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) AND
							        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) AND
							        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) AND
							        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) AND
							        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) AND
							        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) AND
							        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) AND
							        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) AND
							        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) AND
							        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) AND
							        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) AND
							        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) AND
							        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) AND
							        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) AND
							        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) AND
							        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) AND
							        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) AND
							        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) AND
							        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) AND
							        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) AND
							        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) AND
							        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) AND
							        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) AND
							        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) AND
							        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) AND
							        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) AND
							        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) AND
							        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) AND
							        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) AND
							        (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) AND
							        (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) AND
							        (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) AND
							        (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) AND
							        (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) AND
							        (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) AND
							        (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) AND
							        (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) AND
							        (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) AND
							        (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
						            ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
						            ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
						            ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
						            ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
						            ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
						            ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_30) > b.max_days OR
						            ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_25) > b.max_days OR
						            ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}
	}else{
		$j = 90;
		$filedNya ="nvl(ceil(n_1),0) n_1, nvl(ceil(n_2),0) n_2, nvl(ceil(n_3),0) n_3, nvl(ceil(n_4),0) n_4, nvl(ceil(n_5),0) n_5, nvl(ceil(n_6),0) n_6, nvl(ceil(n_7),0) n_7, nvl(ceil(n_8),0) n_8, nvl(ceil(n_9),0) n_9, nvl(ceil(n_10),0) n_10,
					nvl(ceil(n_11),0) n_11, nvl(ceil(n_12),0) n_12, nvl(ceil(n_13),0) n_13, nvl(ceil(n_14),0) n_14, nvl(ceil(n_15),0) n_15, nvl(ceil(n_16),0) n_16, nvl(ceil(n_17),0) n_17, nvl(ceil(n_18),0) n_18, nvl(ceil(n_19),0) n_19, nvl(ceil(n_20),0) n_20,
					nvl(ceil(n_21),0) n_21, nvl(ceil(n_22),0) n_22, nvl(ceil(n_23),0) n_23, nvl(ceil(n_24),0) n_24, nvl(ceil(n_25),0) n_25, nvl(ceil(n_26),0) n_26, nvl(ceil(n_27),0) n_27, nvl(ceil(n_28),0) n_28, nvl(ceil(n_29),0) n_29, nvl(ceil(n_30),0) n_30,
					nvl(ceil(n_31),0) n_31, nvl(ceil(n_32),0) n_32, nvl(ceil(n_33),0) n_33, nvl(ceil(n_34),0) n_34, nvl(ceil(n_35),0) n_35, nvl(ceil(n_36),0) n_36, nvl(ceil(n_37),0) n_37, nvl(ceil(n_38),0) n_38, nvl(ceil(n_39),0) n_39, nvl(ceil(n_40),0) n_40,
					nvl(ceil(n_41),0) n_41, nvl(ceil(n_42),0) n_42, nvl(ceil(n_43),0) n_43, nvl(ceil(n_44),0) n_44, nvl(ceil(n_45),0) n_45, nvl(ceil(n_46),0) n_46, nvl(ceil(n_47),0) n_47, nvl(ceil(n_48),0) n_48, nvl(ceil(n_49),0) n_49, nvl(ceil(n_50),0) n_50,
					nvl(ceil(n_51),0) n_51, nvl(ceil(n_52),0) n_52, nvl(ceil(n_53),0) n_53, nvl(ceil(n_54),0) n_54, nvl(ceil(n_55),0) n_55, nvl(ceil(n_56),0) n_56, nvl(ceil(n_57),0) n_57, nvl(ceil(n_58),0) n_58, nvl(ceil(n_59),0) n_59, nvl(ceil(n_60),0) n_60,
					nvl(ceil(n_61),0) n_61, nvl(ceil(n_62),0) n_62, nvl(ceil(n_63),0) n_63, nvl(ceil(n_64),0) n_64, nvl(ceil(n_65),0) n_65, nvl(ceil(n_66),0) n_66, nvl(ceil(n_67),0) n_67, nvl(ceil(n_68),0) n_68, nvl(ceil(n_69),0) n_69, nvl(ceil(n_70),0) n_70,
					nvl(ceil(n_71),0) n_71, nvl(ceil(n_72),0) n_72, nvl(ceil(n_73),0) n_73, nvl(ceil(n_74),0) n_74, nvl(ceil(n_75),0) n_75, nvl(ceil(n_76),0) n_76, nvl(ceil(n_77),0) n_77, nvl(ceil(n_78),0) n_78, nvl(ceil(n_79),0) n_79, nvl(ceil(n_80),0) n_80,
					nvl(ceil(n_81),0) n_81, nvl(ceil(n_82),0) n_82, nvl(ceil(n_83),0) n_83, nvl(ceil(n_84),0) n_84, nvl(ceil(n_85),0) n_85, nvl(ceil(n_86),0) n_86, nvl(ceil(n_87),0) n_87, nvl(ceil(n_88),0) n_88, nvl(ceil(n_89),0) n_89, nvl(ceil(n_90),0) n_90 ";
		//$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
		//		   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
		//		   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR 
		//		   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
		//		   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR 
		//		   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
		//		   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR 
		//		   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
		//		   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR 
		//		   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
		//		   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR 
		//		   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
		//		   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR 
		//		   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
		//		   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR 
		//		   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
		//		   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR 
		//		   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) AND ";
		$avg = "ceil((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
			          n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+
			          n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+
			          n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+
			          n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+
			          n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+
			          n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+
			          n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+
			          n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg,";
		
		if ($exp_ito[0] == 'T') {
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-T-T";
					$wheNya = " ";
				}else{
					//$wheNya = "T-T-F";
					$wheNya = " (
								  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
								   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
								   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
								   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
								   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
								   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
								   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
								   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
								   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
								   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
								   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
								   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
								   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
								   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
								   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
								   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
								   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR
								   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) OR 
								  ( (ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
								    (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
								    (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
								    (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
								    (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
								    (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
								    (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
								    (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
								    (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
								    (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
								    (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
								    (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
								    (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
								    (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
								    (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
								    (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
								    (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
								    (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
								    (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
								    (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
								    (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
								    (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
								    (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
								    (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
								    (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
								    (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
								    (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
								    (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
								    (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
								    (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
								    (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
								    (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
								    (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
								    (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
								    (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
								    (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
								    (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
								    (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
								    (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
								    (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
								    (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
								    (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
								    (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
								    (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
								    (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
								    (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
								    (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
								    (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
								    (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
								    (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
								    (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
								    (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
								    (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
								    (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
								    (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
								    (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
								    (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
								    (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
								    (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
								    (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
								    (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
								    (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
								    (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
								    (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
								    (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
								    (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
								    (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
								    (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
								    (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
								    (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
								    (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
								    (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
								    (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
								    (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
								    (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
								    (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
								    (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
								    (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
								    (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
								    (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) OR
								    (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) OR 
								    (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) OR 
								    (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) OR 
								    (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) OR 
								    (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) OR
								    (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) OR
								    (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) OR
								    (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) OR
								    (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) OR
								    (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
								  )
								) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-F-T";
					$wheNya = " (
								  (ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
								   ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
								   ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
								   ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
								   ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
								   ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
								   ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
								   ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
								   ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
								   ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
								   ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
								   ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
								   ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
								   ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
								   ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
								   ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
								   ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR
								   ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) 
								   OR 
								  (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
								   ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
								   ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
								   ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
								   ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
								   ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
								   ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
								   ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
								   ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
								   ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
								   ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
								   ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
								   ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
								   ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
								   ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
								   ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days OR
								   ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_85) > b.max_days OR
								   ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days)
								) and ";
				}else{
					//$wheNya = "T-F-F";
					$wheNya = "(ceil(n_1) < b.min_days OR ceil(n_2) < b.min_days OR ceil(n_3) < b.min_days OR ceil(n_4) < b.min_days OR ceil(n_5) < b.min_days OR
					            ceil(n_6) < b.min_days OR ceil(n_7) < b.min_days OR ceil(n_8) < b.min_days OR ceil(n_9) < b.min_days OR ceil(n_10) < b.min_days OR
					            ceil(n_11) < b.min_days OR ceil(n_12) < b.min_days OR ceil(n_13) < b.min_days OR ceil(n_14) < b.min_days OR ceil(n_15) < b.min_days OR
					            ceil(n_16) < b.min_days OR ceil(n_17) < b.min_days OR ceil(n_18) < b.min_days OR ceil(n_19) < b.min_days OR ceil(n_20) < b.min_days OR
					            ceil(n_21) < b.min_days OR ceil(n_22) < b.min_days OR ceil(n_23) < b.min_days OR ceil(n_24) < b.min_days OR ceil(n_25) < b.min_days OR
					            ceil(n_26) < b.min_days OR ceil(n_27) < b.min_days OR ceil(n_28) < b.min_days OR ceil(n_29) < b.min_days OR ceil(n_30) < b.min_days OR
					            ceil(n_31) < b.min_days OR ceil(n_32) < b.min_days OR ceil(n_33) < b.min_days OR ceil(n_34) < b.min_days OR ceil(n_35) < b.min_days OR
					            ceil(n_36) < b.min_days OR ceil(n_37) < b.min_days OR ceil(n_38) < b.min_days OR ceil(n_39) < b.min_days OR ceil(n_40) < b.min_days OR
					            ceil(n_41) < b.min_days OR ceil(n_42) < b.min_days OR ceil(n_43) < b.min_days OR ceil(n_44) < b.min_days OR ceil(n_45) < b.min_days OR
					            ceil(n_46) < b.min_days OR ceil(n_47) < b.min_days OR ceil(n_48) < b.min_days OR ceil(n_49) < b.min_days OR ceil(n_50) < b.min_days OR
					            ceil(n_51) < b.min_days OR ceil(n_52) < b.min_days OR ceil(n_53) < b.min_days OR ceil(n_54) < b.min_days OR ceil(n_55) < b.min_days OR
					            ceil(n_56) < b.min_days OR ceil(n_57) < b.min_days OR ceil(n_58) < b.min_days OR ceil(n_59) < b.min_days OR ceil(n_60) < b.min_days OR
					            ceil(n_61) < b.min_days OR ceil(n_62) < b.min_days OR ceil(n_63) < b.min_days OR ceil(n_64) < b.min_days OR ceil(n_65) < b.min_days OR
					            ceil(n_66) < b.min_days OR ceil(n_67) < b.min_days OR ceil(n_68) < b.min_days OR ceil(n_69) < b.min_days OR ceil(n_70) < b.min_days OR
					            ceil(n_71) < b.min_days OR ceil(n_72) < b.min_days OR ceil(n_73) < b.min_days OR ceil(n_74) < b.min_days OR ceil(n_75) < b.min_days OR
					            ceil(n_76) < b.min_days OR ceil(n_77) < b.min_days OR ceil(n_78) < b.min_days OR ceil(n_79) < b.min_days OR ceil(n_80) < b.min_days OR
					            ceil(n_81) < b.min_days OR ceil(n_82) < b.min_days OR ceil(n_83) < b.min_days OR ceil(n_84) < b.min_days OR ceil(n_85) < b.min_days OR
					            ceil(n_86) < b.min_days OR ceil(n_87) < b.min_days OR ceil(n_88) < b.min_days OR ceil(n_89) < b.min_days OR ceil(n_90) < b.min_days) and ";
				}
			}
		}else{
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-T-T";
					$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) OR 
						        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) OR 
						        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) OR 
						        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) OR 
						        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) OR
						        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) OR
						        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) OR
						        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) OR
						        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) OR
						        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) OR
						        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) OR 
						        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) OR 
						        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) OR 
						        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) OR 
						        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) OR
						        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) OR
						        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) OR
						        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) OR
						        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) OR
						        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) OR
						        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) OR 
						        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) OR 
						        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) OR 
						        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) OR 
						        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) OR
						        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) OR
						        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) OR
						        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) OR
						        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) OR
						        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) OR
						        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) OR 
						        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) OR 
						        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) OR 
						        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) OR 
						        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) OR
						        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) OR
						        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) OR
						        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) OR
						        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) OR
						        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) OR
						        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) OR 
						        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) OR 
						        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) OR 
						        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) OR 
						        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) OR
						        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) OR
						        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) OR
						        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) OR
						        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) OR
						        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) OR
						        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) OR 
						        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) OR 
						        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) OR 
						        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) OR 
						        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) OR
						        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) OR
						        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) OR
						        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) OR
						        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) OR
						        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) OR
						        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) OR 
						        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) OR 
						        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) OR 
						        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) OR 
						        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) OR
						        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) OR
						        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) OR
						        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) OR
						        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) OR
						        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) OR
						        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) OR 
						        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) OR 
						        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) OR 
						        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) OR 
						        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) OR
						        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) OR
						        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) OR
						        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) OR
						        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) OR
						        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) OR
						        (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) OR 
						        (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) OR 
						        (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) OR 
						        (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) OR 
						        (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) OR
						        (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) OR
						        (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) OR
						        (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) OR
						        (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) OR
						        (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
						       ) OR 
						       (ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
						        ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
						        ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
						        ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
						        ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
						        ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
						        ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
						        ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
						        ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
						        ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
						        ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
						        ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
						        ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
						        ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
						        ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
						        ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_80) > b.max_days OR
						        ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_85) > b.max_days OR
						        ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days 
						       ) and ";
				}else{
					//$wheNya = "F-T-F";
					$wheNya = "((ceil(n_1) > b.min_days AND ceil(n_1) < b.max_days) AND
						        (ceil(n_2) > b.min_days AND ceil(n_2) < b.max_days) AND
						        (ceil(n_3) > b.min_days AND ceil(n_3) < b.max_days) AND
						        (ceil(n_4) > b.min_days AND ceil(n_4) < b.max_days) AND
						        (ceil(n_5) > b.min_days AND ceil(n_5) < b.max_days) AND
						        (ceil(n_6) > b.min_days AND ceil(n_6) < b.max_days) AND
						        (ceil(n_7) > b.min_days AND ceil(n_7) < b.max_days) AND
						        (ceil(n_8) > b.min_days AND ceil(n_8) < b.max_days) AND
						        (ceil(n_9) > b.min_days AND ceil(n_9) < b.max_days) AND
						        (ceil(n_10) > b.min_days AND ceil(n_10) < b.max_days) AND
						        (ceil(n_11) > b.min_days AND ceil(n_11) < b.max_days) AND
						        (ceil(n_12) > b.min_days AND ceil(n_12) < b.max_days) AND
						        (ceil(n_13) > b.min_days AND ceil(n_13) < b.max_days) AND
						        (ceil(n_14) > b.min_days AND ceil(n_14) < b.max_days) AND
						        (ceil(n_15) > b.min_days AND ceil(n_15) < b.max_days) AND
						        (ceil(n_16) > b.min_days AND ceil(n_16) < b.max_days) AND
						        (ceil(n_17) > b.min_days AND ceil(n_17) < b.max_days) AND
						        (ceil(n_18) > b.min_days AND ceil(n_18) < b.max_days) AND
						        (ceil(n_19) > b.min_days AND ceil(n_19) < b.max_days) AND
						        (ceil(n_20) > b.min_days AND ceil(n_20) < b.max_days) AND
						        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
						        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
						        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
						        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
						        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
						        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
						        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
						        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
						        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
						        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
						        (ceil(n_21) > b.min_days AND ceil(n_21) < b.max_days) AND
						        (ceil(n_22) > b.min_days AND ceil(n_22) < b.max_days) AND
						        (ceil(n_23) > b.min_days AND ceil(n_23) < b.max_days) AND
						        (ceil(n_24) > b.min_days AND ceil(n_24) < b.max_days) AND
						        (ceil(n_25) > b.min_days AND ceil(n_25) < b.max_days) AND
						        (ceil(n_26) > b.min_days AND ceil(n_26) < b.max_days) AND
						        (ceil(n_27) > b.min_days AND ceil(n_27) < b.max_days) AND
						        (ceil(n_28) > b.min_days AND ceil(n_28) < b.max_days) AND
						        (ceil(n_29) > b.min_days AND ceil(n_29) < b.max_days) AND
						        (ceil(n_30) > b.min_days AND ceil(n_30) < b.max_days) AND
						        (ceil(n_31) > b.min_days AND ceil(n_31) < b.max_days) AND
						        (ceil(n_32) > b.min_days AND ceil(n_32) < b.max_days) AND
						        (ceil(n_33) > b.min_days AND ceil(n_33) < b.max_days) AND
						        (ceil(n_34) > b.min_days AND ceil(n_34) < b.max_days) AND
						        (ceil(n_35) > b.min_days AND ceil(n_35) < b.max_days) AND
						        (ceil(n_36) > b.min_days AND ceil(n_36) < b.max_days) AND
						        (ceil(n_37) > b.min_days AND ceil(n_37) < b.max_days) AND
						        (ceil(n_38) > b.min_days AND ceil(n_38) < b.max_days) AND
						        (ceil(n_39) > b.min_days AND ceil(n_39) < b.max_days) AND
						        (ceil(n_40) > b.min_days AND ceil(n_40) < b.max_days) AND
						        (ceil(n_41) > b.min_days AND ceil(n_41) < b.max_days) AND
						        (ceil(n_42) > b.min_days AND ceil(n_42) < b.max_days) AND
						        (ceil(n_43) > b.min_days AND ceil(n_43) < b.max_days) AND
						        (ceil(n_44) > b.min_days AND ceil(n_44) < b.max_days) AND
						        (ceil(n_45) > b.min_days AND ceil(n_45) < b.max_days) AND
						        (ceil(n_46) > b.min_days AND ceil(n_46) < b.max_days) AND
						        (ceil(n_47) > b.min_days AND ceil(n_47) < b.max_days) AND
						        (ceil(n_48) > b.min_days AND ceil(n_48) < b.max_days) AND
						        (ceil(n_49) > b.min_days AND ceil(n_49) < b.max_days) AND
						        (ceil(n_50) > b.min_days AND ceil(n_50) < b.max_days) AND
						        (ceil(n_51) > b.min_days AND ceil(n_51) < b.max_days) AND
						        (ceil(n_52) > b.min_days AND ceil(n_52) < b.max_days) AND
						        (ceil(n_53) > b.min_days AND ceil(n_53) < b.max_days) AND
						        (ceil(n_54) > b.min_days AND ceil(n_54) < b.max_days) AND
						        (ceil(n_55) > b.min_days AND ceil(n_55) < b.max_days) AND
						        (ceil(n_56) > b.min_days AND ceil(n_56) < b.max_days) AND
						        (ceil(n_57) > b.min_days AND ceil(n_57) < b.max_days) AND
						        (ceil(n_58) > b.min_days AND ceil(n_58) < b.max_days) AND
						        (ceil(n_59) > b.min_days AND ceil(n_59) < b.max_days) AND
						        (ceil(n_60) > b.min_days AND ceil(n_60) < b.max_days) AND
						        (ceil(n_61) > b.min_days AND ceil(n_61) < b.max_days) AND
						        (ceil(n_62) > b.min_days AND ceil(n_62) < b.max_days) AND
						        (ceil(n_63) > b.min_days AND ceil(n_63) < b.max_days) AND
						        (ceil(n_64) > b.min_days AND ceil(n_64) < b.max_days) AND
						        (ceil(n_65) > b.min_days AND ceil(n_65) < b.max_days) AND
						        (ceil(n_66) > b.min_days AND ceil(n_66) < b.max_days) AND
						        (ceil(n_67) > b.min_days AND ceil(n_67) < b.max_days) AND
						        (ceil(n_68) > b.min_days AND ceil(n_68) < b.max_days) AND
						        (ceil(n_69) > b.min_days AND ceil(n_69) < b.max_days) AND
						        (ceil(n_70) > b.min_days AND ceil(n_70) < b.max_days) AND
						        (ceil(n_71) > b.min_days AND ceil(n_71) < b.max_days) AND
						        (ceil(n_72) > b.min_days AND ceil(n_72) < b.max_days) AND
						        (ceil(n_73) > b.min_days AND ceil(n_73) < b.max_days) AND
						        (ceil(n_74) > b.min_days AND ceil(n_74) < b.max_days) AND
						        (ceil(n_75) > b.min_days AND ceil(n_75) < b.max_days) AND
						        (ceil(n_76) > b.min_days AND ceil(n_76) < b.max_days) AND
						        (ceil(n_77) > b.min_days AND ceil(n_77) < b.max_days) AND
						        (ceil(n_78) > b.min_days AND ceil(n_78) < b.max_days) AND
						        (ceil(n_79) > b.min_days AND ceil(n_79) < b.max_days) AND
						        (ceil(n_80) > b.min_days AND ceil(n_80) < b.max_days) AND
						        (ceil(n_81) > b.min_days AND ceil(n_81) < b.max_days) AND
						        (ceil(n_82) > b.min_days AND ceil(n_82) < b.max_days) AND
						        (ceil(n_83) > b.min_days AND ceil(n_83) < b.max_days) AND
						        (ceil(n_84) > b.min_days AND ceil(n_84) < b.max_days) AND
						        (ceil(n_85) > b.min_days AND ceil(n_85) < b.max_days) AND
						        (ceil(n_86) > b.min_days AND ceil(n_86) < b.max_days) AND
						        (ceil(n_87) > b.min_days AND ceil(n_87) < b.max_days) AND
						        (ceil(n_88) > b.min_days AND ceil(n_88) < b.max_days) AND
						        (ceil(n_89) > b.min_days AND ceil(n_89) < b.max_days) AND
						        (ceil(n_90) > b.min_days AND ceil(n_90) < b.max_days)
						       ) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-F-T";
					$wheNya = "(ceil(n_1) > b.max_days OR ceil(n_2) > b.max_days OR ceil(n_3) > b.max_days OR ceil(n_4) > b.max_days OR ceil(n_5) > b.max_days OR
					            ceil(n_6) > b.max_days OR ceil(n_7) > b.max_days OR ceil(n_8) > b.max_days OR ceil(n_9) > b.max_days OR ceil(n_10) > b.max_days OR
					            ceil(n_11) > b.max_days OR ceil(n_12) > b.max_days OR ceil(n_13) > b.max_days OR ceil(n_14) > b.max_days OR ceil(n_15) > b.max_days OR
					            ceil(n_16) > b.max_days OR ceil(n_17) > b.max_days OR ceil(n_18) > b.max_days OR ceil(n_19) > b.max_days OR ceil(n_20) > b.max_days OR
					            ceil(n_21) > b.max_days OR ceil(n_22) > b.max_days OR ceil(n_23) > b.max_days OR ceil(n_24) > b.max_days OR ceil(n_25) > b.max_days OR
					            ceil(n_26) > b.max_days OR ceil(n_27) > b.max_days OR ceil(n_28) > b.max_days OR ceil(n_29) > b.max_days OR ceil(n_30) > b.max_days OR
					            ceil(n_31) > b.max_days OR ceil(n_32) > b.max_days OR ceil(n_33) > b.max_days OR ceil(n_34) > b.max_days OR ceil(n_35) > b.max_days OR
					            ceil(n_36) > b.max_days OR ceil(n_37) > b.max_days OR ceil(n_38) > b.max_days OR ceil(n_39) > b.max_days OR ceil(n_40) > b.max_days OR
					            ceil(n_41) > b.max_days OR ceil(n_42) > b.max_days OR ceil(n_43) > b.max_days OR ceil(n_44) > b.max_days OR ceil(n_45) > b.max_days OR
					            ceil(n_46) > b.max_days OR ceil(n_47) > b.max_days OR ceil(n_48) > b.max_days OR ceil(n_49) > b.max_days OR ceil(n_50) > b.max_days OR
					            ceil(n_51) > b.max_days OR ceil(n_52) > b.max_days OR ceil(n_53) > b.max_days OR ceil(n_54) > b.max_days OR ceil(n_55) > b.max_days OR
					            ceil(n_56) > b.max_days OR ceil(n_57) > b.max_days OR ceil(n_58) > b.max_days OR ceil(n_59) > b.max_days OR ceil(n_60) > b.max_days OR
					            ceil(n_61) > b.max_days OR ceil(n_62) > b.max_days OR ceil(n_63) > b.max_days OR ceil(n_64) > b.max_days OR ceil(n_65) > b.max_days OR
					            ceil(n_66) > b.max_days OR ceil(n_67) > b.max_days OR ceil(n_68) > b.max_days OR ceil(n_69) > b.max_days OR ceil(n_70) > b.max_days OR
					            ceil(n_71) > b.max_days OR ceil(n_72) > b.max_days OR ceil(n_73) > b.max_days OR ceil(n_74) > b.max_days OR ceil(n_75) > b.max_days OR
					            ceil(n_76) > b.max_days OR ceil(n_77) > b.max_days OR ceil(n_78) > b.max_days OR ceil(n_79) > b.max_days OR ceil(n_30) > b.max_days OR
					            ceil(n_81) > b.max_days OR ceil(n_82) > b.max_days OR ceil(n_83) > b.max_days OR ceil(n_84) > b.max_days OR ceil(n_25) > b.max_days OR
					            ceil(n_86) > b.max_days OR ceil(n_87) > b.max_days OR ceil(n_88) > b.max_days OR ceil(n_89) > b.max_days OR ceil(n_90) > b.max_days) and ";
				}else{
					//$wheNya = "F-F-F";
					$wheNya = " ";
				}
			}
		}	
	}

	$where ="where $item $wheNya a.no_id in(6) ";
			//and it.item in ('CATHODE CAN','EMD','ZINC POWDER','ZINC OXIDE','GRAPHITE') 

	include("../connect/conn.php");

	$sql = "select distinct a.item_no, a.item_desc, a.no_id, a.description, 
		nvl(b.min_days,0) min, nvl(b.max_days,0) max, it.item as tipe, nvl(b.average,0) as std, b.flag_details,
		$avg $filedNya
		from ztb_mrp_data a
		inner join ztb_config_rm b on a.item_no= b.item_no
		inner join item it on a.item_no = it.item_no
		$where 
		order by it.item asc";
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

		if($r>$a AND $r<$z){
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
?>
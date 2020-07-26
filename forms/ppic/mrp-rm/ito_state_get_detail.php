<?php
	session_start();
	$ty = isset($_REQUEST['ty']) ? strval($_REQUEST['ty']) : '';
	$cmb_day = isset($_REQUEST['cmb_day']) ? strval($_REQUEST['cmb_day']) : '';
	$ck_day = isset($_REQUEST['ck_day']) ? strval($_REQUEST['ck_day']) : '';
	$sts_ito = isset($_REQUEST['sts_ito']) ? strval($_REQUEST['sts_ito']) : '';

	$exp_ito = explode('-', $sts_ito);

	if ($ck_day != "true"){
		$j = $cmb_day;
		if ($cmb_day == 5){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5 ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5)/5) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days) OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) 
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) 
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 10){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR 
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days) and ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10)/10) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days) OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 20){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR 
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
						  n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20)/20) as avg,";

			if ($exp_ito[0] == 'T') {
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-T-T";
						$wheNya = " ";
					}else{
						//$wheNya = "T-T-F";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days) OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 30){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days) OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 40){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 50){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
						coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
			//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
			//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
									    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
									    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
									    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
									    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
									    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
									    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
									    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
									    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
									    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
									    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
									   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
									   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
						            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
						            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
							        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
							        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 60){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
						coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50,
						coalesce(ceiling(n_51),0) n_51, coalesce(ceiling(n_52),0) n_52, coalesce(ceiling(n_53),0) n_53, coalesce(ceiling(n_54),0) n_54, coalesce(ceiling(n_55),0) n_55, coalesce(ceiling(n_56),0) n_56, coalesce(ceiling(n_57),0) n_57, coalesce(ceiling(n_58),0) n_58, coalesce(ceiling(n_59),0) n_59, coalesce(ceiling(n_60),0) n_60 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
			//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
			//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
			//		   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR 
			//		   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
									    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
									    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
									    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
									    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
									    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
									    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
									    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
									    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
									    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
									    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
									    (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
									    (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
									    (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
									    (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
									    (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
									    (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
									    (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
									    (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
									    (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
									    (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
									   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
									   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
									   ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
									   ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
						            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
						            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
						            ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
						            ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
							        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
							        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
							        ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
							        ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) AND
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) AND
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) AND
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) AND
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) AND
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) AND
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) AND
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) AND
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) AND
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) AND
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
						            ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
						            ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 70){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
						coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50,
						coalesce(ceiling(n_51),0) n_51, coalesce(ceiling(n_52),0) n_52, coalesce(ceiling(n_53),0) n_53, coalesce(ceiling(n_54),0) n_54, coalesce(ceiling(n_55),0) n_55, coalesce(ceiling(n_56),0) n_56, coalesce(ceiling(n_57),0) n_57, coalesce(ceiling(n_58),0) n_58, coalesce(ceiling(n_59),0) n_59, coalesce(ceiling(n_60),0) n_60,
						coalesce(ceiling(n_61),0) n_61, coalesce(ceiling(n_62),0) n_62, coalesce(ceiling(n_63),0) n_63, coalesce(ceiling(n_64),0) n_64, coalesce(ceiling(n_65),0) n_65, coalesce(ceiling(n_66),0) n_66, coalesce(ceiling(n_67),0) n_67, coalesce(ceiling(n_68),0) n_68, coalesce(ceiling(n_69),0) n_69, coalesce(ceiling(n_70),0) n_70 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
			//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
			//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
			//		   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR 
			//		   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
			//		   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR 
			//		   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_^0) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_^5) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
									    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
									    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
									    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
									    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
									    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
									    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
									    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
									    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
									    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
									    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
									    (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
									    (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
									    (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
									    (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
									    (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
									    (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
									    (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
									    (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
									    (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
									    (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
									    (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
									    (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
									    (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
									    (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
									    (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
									    (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
									    (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
									    (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
									    (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
									    (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
									   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
									   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
									   ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
									   ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
									   ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
									   ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
						            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
						            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
						            ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
						            ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
						            ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
						            ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
							        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
							        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
							        ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
							        ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
							        ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
							        ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) AND
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) AND
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) AND
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) AND
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) AND
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) AND
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) AND
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) AND
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) AND
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) AND
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) AND
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) AND
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) AND
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) AND
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) AND
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) AND
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) AND
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) AND
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) AND
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) AND
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
						            ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
						            ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
						            ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
						            ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}elseif($cmb_day == 80){
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
						coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50,
						coalesce(ceiling(n_51),0) n_51, coalesce(ceiling(n_52),0) n_52, coalesce(ceiling(n_53),0) n_53, coalesce(ceiling(n_54),0) n_54, coalesce(ceiling(n_55),0) n_55, coalesce(ceiling(n_56),0) n_56, coalesce(ceiling(n_57),0) n_57, coalesce(ceiling(n_58),0) n_58, coalesce(ceiling(n_59),0) n_59, coalesce(ceiling(n_60),0) n_60,
						coalesce(ceiling(n_61),0) n_61, coalesce(ceiling(n_62),0) n_62, coalesce(ceiling(n_63),0) n_63, coalesce(ceiling(n_64),0) n_64, coalesce(ceiling(n_65),0) n_65, coalesce(ceiling(n_66),0) n_66, coalesce(ceiling(n_67),0) n_67, coalesce(ceiling(n_68),0) n_68, coalesce(ceiling(n_69),0) n_69, coalesce(ceiling(n_70),0) n_70,
						coalesce(ceiling(n_71),0) n_71, coalesce(ceiling(n_72),0) n_72, coalesce(ceiling(n_73),0) n_73, coalesce(ceiling(n_74),0) n_74, coalesce(ceiling(n_75),0) n_75, coalesce(ceiling(n_76),0) n_76, coalesce(ceiling(n_77),0) n_77, coalesce(ceiling(n_78),0) n_78, coalesce(ceiling(n_79),0) n_79, coalesce(ceiling(n_80),0) n_80 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
			//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
			//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
			//		   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR 
			//		   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
			//		   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR 
			//		   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
			//		   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR 
			//		   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
									   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
									   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
									    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
									    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
									    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
									    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
									    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
									    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
									    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
									    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
									    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
									    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
									    (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
									    (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
									    (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
									    (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
									    (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
									    (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
									    (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
									    (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
									    (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
									    (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
									    (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
									    (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
									    (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
									    (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
									    (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
									    (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
									    (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
									    (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
									    (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
									    (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
									    (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
									    (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
									    (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
									    (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
									    (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
									    (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
									    (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
									    (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
									    (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
									    (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
									   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
									   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
									   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
									   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
									   ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
									   ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
									   ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
									   ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
									   ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
									   ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
						            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
						            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
						            ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
						            ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
						            ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
						            ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
						            ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
						            ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
							        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
							        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
							        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
							        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
							        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
							        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
							        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
							        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
							        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
							        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
							        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
							        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
							        ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
							        ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
							        ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
							        ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
							        ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
							        ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) AND
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) AND
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) AND
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) AND
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) AND
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) AND
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) AND
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) AND
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) AND
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) AND
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) AND
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) AND
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) AND
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) AND
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) AND
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) AND
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) AND
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) AND
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) AND
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) AND
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) AND
							        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) AND
							        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) AND
							        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) AND
							        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) AND
							        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) AND
							        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) AND
							        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) AND
							        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) AND
							        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) AND
							        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
						            ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
						            ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
						            ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
						            ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
						            ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
						            ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}else{
			$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
						coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
						coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
						coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
						coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50,
						coalesce(ceiling(n_51),0) n_51, coalesce(ceiling(n_52),0) n_52, coalesce(ceiling(n_53),0) n_53, coalesce(ceiling(n_54),0) n_54, coalesce(ceiling(n_55),0) n_55, coalesce(ceiling(n_56),0) n_56, coalesce(ceiling(n_57),0) n_57, coalesce(ceiling(n_58),0) n_58, coalesce(ceiling(n_59),0) n_59, coalesce(ceiling(n_60),0) n_60,
						coalesce(ceiling(n_61),0) n_61, coalesce(ceiling(n_62),0) n_62, coalesce(ceiling(n_63),0) n_63, coalesce(ceiling(n_64),0) n_64, coalesce(ceiling(n_65),0) n_65, coalesce(ceiling(n_66),0) n_66, coalesce(ceiling(n_67),0) n_67, coalesce(ceiling(n_68),0) n_68, coalesce(ceiling(n_69),0) n_69, coalesce(ceiling(n_70),0) n_70,
						coalesce(ceiling(n_71),0) n_71, coalesce(ceiling(n_72),0) n_72, coalesce(ceiling(n_73),0) n_73, coalesce(ceiling(n_74),0) n_74, coalesce(ceiling(n_75),0) n_75, coalesce(ceiling(n_76),0) n_76, coalesce(ceiling(n_77),0) n_77, coalesce(ceiling(n_78),0) n_78, coalesce(ceiling(n_79),0) n_79, coalesce(ceiling(n_80),0) n_80,
						coalesce(ceiling(n_81),0) n_81, coalesce(ceiling(n_82),0) n_82, coalesce(ceiling(n_83),0) n_83, coalesce(ceiling(n_84),0) n_84, coalesce(ceiling(n_85),0) n_85, coalesce(ceiling(n_86),0) n_86, coalesce(ceiling(n_87),0) n_87, coalesce(ceiling(n_88),0) n_88, coalesce(ceiling(n_89),0) n_89, coalesce(ceiling(n_90),0) n_90 ";
			//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
			//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
			//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
			//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
			//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
			//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
			//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
			//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
			//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
			//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
			//		   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR 
			//		   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
			//		   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR 
			//		   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
			//		   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR 
			//		   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
			//		   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR 
			//		   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) AND ";
			$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_60) < b.min_days OR
									   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_65) < b.min_days OR
									   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_70) < b.min_days OR
									   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_75) < b.min_days OR
									   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_80) < b.min_days OR
									   ceiling(n_91) < b.min_days OR ceiling(n_92) < b.min_days OR ceiling(n_93) < b.min_days OR ceiling(n_94) < b.min_days OR ceiling(n_85) < b.min_days OR
									   ceiling(n_96) < b.min_days OR ceiling(n_97) < b.min_days OR ceiling(n_98) < b.min_days OR ceiling(n_99) < b.min_days OR ceiling(n_90) < b.min_days) OR 
									  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
									    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
									    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
									    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
									    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
									    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
									    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
									    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
									    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
									    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
									    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
									    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
									    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
									    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
									    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
									    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
									    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
									    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
									    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
									    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
									    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
									    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
									    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
									    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
									    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
									    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
									    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
									    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
									    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
									    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
									    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
									    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
									    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
									    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
									    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
									    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
									    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
									    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
									    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
									    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
									    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
									    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
									    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
									    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
									    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
									    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
									    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
									    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
									    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
									    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
									    (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
									    (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
									    (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
									    (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
									    (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
									    (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
									    (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
									    (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
									    (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
									    (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
									    (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
									    (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
									    (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
									    (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
									    (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
									    (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
									    (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
									    (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
									    (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
									    (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
									    (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
									    (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
									    (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
									    (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
									    (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
									    (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
									    (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
									    (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
									    (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
									    (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) OR
									    (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) OR 
									    (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) OR 
									    (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) OR 
									    (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) OR 
									    (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) OR
									    (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) OR
									    (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) OR
									    (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) OR
									    (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) OR
									    (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
									  )
									) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "T-F-T";
						$wheNya = " (
									  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
									   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
									   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
									   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
									   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
									   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
									   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
									   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
									   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
									   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
									   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
									   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
									   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
									   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
									   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
									   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
									   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR
									   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) 
									   OR 
									  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
									   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
									   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
									   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
									   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
									   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
									   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
									   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
									   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
									   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
									   ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
									   ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
									   ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
									   ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
									   ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
									   ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days OR
									   ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_85) > b.max_days OR
									   ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days)
									) and ";
					}else{
						//$wheNya = "T-F-F";
						$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
						            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
						            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
						            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
						            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
						            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
						            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
						            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
						            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
						            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
						            ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
						            ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
						            ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
						            ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
						            ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
						            ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
						            ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR
						            ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) and ";
					}
				}
			}else{
				if ($exp_ito[1] == 'T') {
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-T-T";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
							        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
							        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
							        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
							        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
							        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
							        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
							        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
							        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
							        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
							        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) OR
							        (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) OR 
							        (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) OR 
							        (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) OR 
							        (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) OR 
							        (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) OR
							        (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) OR
							        (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) OR
							        (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) OR
							        (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) OR
							        (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
							       ) OR 
							       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
							        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
							        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
							        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
							        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
							        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
							        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
							        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
							        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
							        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
							        ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
							        ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
							        ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
							        ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
							        ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
							        ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days OR
							        ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_85) > b.max_days OR
							        ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days 
							       ) and ";
					}else{
						//$wheNya = "F-T-F";
						$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
							        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
							        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
							        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
							        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
							        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
							        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
							        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
							        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
							        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
							        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
							        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
							        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
							        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
							        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
							        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
							        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
							        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
							        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
							        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
							        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
							        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
							        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
							        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
							        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
							        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
							        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
							        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
							        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
							        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
							        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
							        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
							        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
							        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
							        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
							        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
							        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
							        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
							        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
							        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
							        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
							        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
							        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
							        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
							        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
							        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
							        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
							        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
							        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) AND
							        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) AND
							        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) AND
							        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) AND
							        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) AND
							        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) AND
							        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) AND
							        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) AND
							        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) AND
							        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) AND
							        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) AND
							        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) AND
							        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) AND
							        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) AND
							        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) AND
							        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) AND
							        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) AND
							        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) AND
							        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) AND
							        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) AND
							        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) AND
							        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) AND
							        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) AND
							        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) AND
							        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) AND
							        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) AND
							        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) AND
							        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) AND
							        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) AND
							        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) AND
							        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) AND
							        (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) AND
							        (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) AND
							        (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) AND
							        (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) AND
							        (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) AND
							        (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) AND
							        (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) AND
							        (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) AND
							        (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) AND
							        (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
							       ) and ";
					}
				}else{
					if ($exp_ito[2] == 'T') {
						//$wheNya = "F-F-T";
						$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
						            ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
						            ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
						            ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
						            ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
						            ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
						            ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_30) > b.max_days OR
						            ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_25) > b.max_days OR
						            ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days) and ";
					}else{
						//$wheNya = "F-F-F";
						$wheNya = " ";
					}
				}
			}
		}
	}else{
		$j = 90;
		$filedNya ="coalesce(ceiling(n_1),0) n_1, coalesce(ceiling(n_2),0) n_2, coalesce(ceiling(n_3),0) n_3, coalesce(ceiling(n_4),0) n_4, coalesce(ceiling(n_5),0) n_5, coalesce(ceiling(n_6),0) n_6, coalesce(ceiling(n_7),0) n_7, coalesce(ceiling(n_8),0) n_8, coalesce(ceiling(n_9),0) n_9, coalesce(ceiling(n_10),0) n_10,
					coalesce(ceiling(n_11),0) n_11, coalesce(ceiling(n_12),0) n_12, coalesce(ceiling(n_13),0) n_13, coalesce(ceiling(n_14),0) n_14, coalesce(ceiling(n_15),0) n_15, coalesce(ceiling(n_16),0) n_16, coalesce(ceiling(n_17),0) n_17, coalesce(ceiling(n_18),0) n_18, coalesce(ceiling(n_19),0) n_19, coalesce(ceiling(n_20),0) n_20,
					coalesce(ceiling(n_21),0) n_21, coalesce(ceiling(n_22),0) n_22, coalesce(ceiling(n_23),0) n_23, coalesce(ceiling(n_24),0) n_24, coalesce(ceiling(n_25),0) n_25, coalesce(ceiling(n_26),0) n_26, coalesce(ceiling(n_27),0) n_27, coalesce(ceiling(n_28),0) n_28, coalesce(ceiling(n_29),0) n_29, coalesce(ceiling(n_30),0) n_30,
					coalesce(ceiling(n_31),0) n_31, coalesce(ceiling(n_32),0) n_32, coalesce(ceiling(n_33),0) n_33, coalesce(ceiling(n_34),0) n_34, coalesce(ceiling(n_35),0) n_35, coalesce(ceiling(n_36),0) n_36, coalesce(ceiling(n_37),0) n_37, coalesce(ceiling(n_38),0) n_38, coalesce(ceiling(n_39),0) n_39, coalesce(ceiling(n_40),0) n_40,
					coalesce(ceiling(n_41),0) n_41, coalesce(ceiling(n_42),0) n_42, coalesce(ceiling(n_43),0) n_43, coalesce(ceiling(n_44),0) n_44, coalesce(ceiling(n_45),0) n_45, coalesce(ceiling(n_46),0) n_46, coalesce(ceiling(n_47),0) n_47, coalesce(ceiling(n_48),0) n_48, coalesce(ceiling(n_49),0) n_49, coalesce(ceiling(n_50),0) n_50,
					coalesce(ceiling(n_51),0) n_51, coalesce(ceiling(n_52),0) n_52, coalesce(ceiling(n_53),0) n_53, coalesce(ceiling(n_54),0) n_54, coalesce(ceiling(n_55),0) n_55, coalesce(ceiling(n_56),0) n_56, coalesce(ceiling(n_57),0) n_57, coalesce(ceiling(n_58),0) n_58, coalesce(ceiling(n_59),0) n_59, coalesce(ceiling(n_60),0) n_60,
					coalesce(ceiling(n_61),0) n_61, coalesce(ceiling(n_62),0) n_62, coalesce(ceiling(n_63),0) n_63, coalesce(ceiling(n_64),0) n_64, coalesce(ceiling(n_65),0) n_65, coalesce(ceiling(n_66),0) n_66, coalesce(ceiling(n_67),0) n_67, coalesce(ceiling(n_68),0) n_68, coalesce(ceiling(n_69),0) n_69, coalesce(ceiling(n_70),0) n_70,
					coalesce(ceiling(n_71),0) n_71, coalesce(ceiling(n_72),0) n_72, coalesce(ceiling(n_73),0) n_73, coalesce(ceiling(n_74),0) n_74, coalesce(ceiling(n_75),0) n_75, coalesce(ceiling(n_76),0) n_76, coalesce(ceiling(n_77),0) n_77, coalesce(ceiling(n_78),0) n_78, coalesce(ceiling(n_79),0) n_79, coalesce(ceiling(n_80),0) n_80,
					coalesce(ceiling(n_81),0) n_81, coalesce(ceiling(n_82),0) n_82, coalesce(ceiling(n_83),0) n_83, coalesce(ceiling(n_84),0) n_84, coalesce(ceiling(n_85),0) n_85, coalesce(ceiling(n_86),0) n_86, coalesce(ceiling(n_87),0) n_87, coalesce(ceiling(n_88),0) n_88, coalesce(ceiling(n_89),0) n_89, coalesce(ceiling(n_90),0) n_90 ";
		//$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
		//		   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
		//		   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR 
		//		   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
		//		   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR 
		//		   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
		//		   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR 
		//		   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
		//		   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR 
		//		   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
		//		   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR 
		//		   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
		//		   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR 
		//		   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
		//		   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR 
		//		   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
		//		   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR 
		//		   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) AND ";
		$avg = "ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+
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
								  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
								   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
								   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
								   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
								   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
								   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
								   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
								   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
								   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
								   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
								   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
								   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
								   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
								   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
								   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
								   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
								   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR
								   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) OR 
								  ( (ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
								    (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
								    (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
								    (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
								    (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
								    (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
								    (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
								    (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
								    (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
								    (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
								    (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
								    (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
								    (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
								    (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
								    (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
								    (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
								    (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
								    (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
								    (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
								    (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
								    (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
								    (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
								    (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
								    (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
								    (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
								    (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
								    (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
								    (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
								    (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
								    (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
								    (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
								    (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
								    (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
								    (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
								    (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
								    (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
								    (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
								    (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
								    (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
								    (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
								    (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
								    (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
								    (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
								    (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
								    (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
								    (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
								    (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
								    (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
								    (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
								    (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
								    (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
								    (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
								    (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
								    (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
								    (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
								    (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
								    (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
								    (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
								    (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
								    (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
								    (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
								    (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
								    (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
								    (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
								    (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
								    (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
								    (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
								    (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
								    (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
								    (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
								    (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
								    (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
								    (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
								    (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
								    (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
								    (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
								    (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
								    (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
								    (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
								    (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) OR
								    (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) OR 
								    (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) OR 
								    (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) OR 
								    (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) OR 
								    (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) OR
								    (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) OR
								    (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) OR
								    (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) OR
								    (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) OR
								    (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
								  )
								) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "T-F-T";
					$wheNya = " (
								  (ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
								   ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
								   ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
								   ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
								   ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
								   ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
								   ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
								   ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
								   ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
								   ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
								   ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
								   ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
								   ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
								   ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
								   ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
								   ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
								   ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR
								   ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) 
								   OR 
								  (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
								   ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
								   ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
								   ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
								   ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
								   ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
								   ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
								   ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
								   ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
								   ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
								   ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
								   ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
								   ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
								   ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
								   ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
								   ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days OR
								   ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_85) > b.max_days OR
								   ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days)
								) and ";
				}else{
					//$wheNya = "T-F-F";
					$wheNya = "(ceiling(n_1) < b.min_days OR ceiling(n_2) < b.min_days OR ceiling(n_3) < b.min_days OR ceiling(n_4) < b.min_days OR ceiling(n_5) < b.min_days OR
					            ceiling(n_6) < b.min_days OR ceiling(n_7) < b.min_days OR ceiling(n_8) < b.min_days OR ceiling(n_9) < b.min_days OR ceiling(n_10) < b.min_days OR
					            ceiling(n_11) < b.min_days OR ceiling(n_12) < b.min_days OR ceiling(n_13) < b.min_days OR ceiling(n_14) < b.min_days OR ceiling(n_15) < b.min_days OR
					            ceiling(n_16) < b.min_days OR ceiling(n_17) < b.min_days OR ceiling(n_18) < b.min_days OR ceiling(n_19) < b.min_days OR ceiling(n_20) < b.min_days OR
					            ceiling(n_21) < b.min_days OR ceiling(n_22) < b.min_days OR ceiling(n_23) < b.min_days OR ceiling(n_24) < b.min_days OR ceiling(n_25) < b.min_days OR
					            ceiling(n_26) < b.min_days OR ceiling(n_27) < b.min_days OR ceiling(n_28) < b.min_days OR ceiling(n_29) < b.min_days OR ceiling(n_30) < b.min_days OR
					            ceiling(n_31) < b.min_days OR ceiling(n_32) < b.min_days OR ceiling(n_33) < b.min_days OR ceiling(n_34) < b.min_days OR ceiling(n_35) < b.min_days OR
					            ceiling(n_36) < b.min_days OR ceiling(n_37) < b.min_days OR ceiling(n_38) < b.min_days OR ceiling(n_39) < b.min_days OR ceiling(n_40) < b.min_days OR
					            ceiling(n_41) < b.min_days OR ceiling(n_42) < b.min_days OR ceiling(n_43) < b.min_days OR ceiling(n_44) < b.min_days OR ceiling(n_45) < b.min_days OR
					            ceiling(n_46) < b.min_days OR ceiling(n_47) < b.min_days OR ceiling(n_48) < b.min_days OR ceiling(n_49) < b.min_days OR ceiling(n_50) < b.min_days OR
					            ceiling(n_51) < b.min_days OR ceiling(n_52) < b.min_days OR ceiling(n_53) < b.min_days OR ceiling(n_54) < b.min_days OR ceiling(n_55) < b.min_days OR
					            ceiling(n_56) < b.min_days OR ceiling(n_57) < b.min_days OR ceiling(n_58) < b.min_days OR ceiling(n_59) < b.min_days OR ceiling(n_60) < b.min_days OR
					            ceiling(n_61) < b.min_days OR ceiling(n_62) < b.min_days OR ceiling(n_63) < b.min_days OR ceiling(n_64) < b.min_days OR ceiling(n_65) < b.min_days OR
					            ceiling(n_66) < b.min_days OR ceiling(n_67) < b.min_days OR ceiling(n_68) < b.min_days OR ceiling(n_69) < b.min_days OR ceiling(n_70) < b.min_days OR
					            ceiling(n_71) < b.min_days OR ceiling(n_72) < b.min_days OR ceiling(n_73) < b.min_days OR ceiling(n_74) < b.min_days OR ceiling(n_75) < b.min_days OR
					            ceiling(n_76) < b.min_days OR ceiling(n_77) < b.min_days OR ceiling(n_78) < b.min_days OR ceiling(n_79) < b.min_days OR ceiling(n_80) < b.min_days OR
					            ceiling(n_81) < b.min_days OR ceiling(n_82) < b.min_days OR ceiling(n_83) < b.min_days OR ceiling(n_84) < b.min_days OR ceiling(n_85) < b.min_days OR
					            ceiling(n_86) < b.min_days OR ceiling(n_87) < b.min_days OR ceiling(n_88) < b.min_days OR ceiling(n_89) < b.min_days OR ceiling(n_90) < b.min_days) and ";
				}
			}
		}else{
			if ($exp_ito[1] == 'T') {
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-T-T";
					$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) OR 
						        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) OR 
						        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) OR 
						        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) OR 
						        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) OR
						        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) OR
						        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) OR
						        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) OR
						        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) OR
						        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) OR
						        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) OR 
						        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) OR 
						        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) OR 
						        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) OR 
						        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) OR
						        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) OR
						        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) OR
						        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) OR
						        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) OR
						        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) OR
						        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) OR 
						        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) OR 
						        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) OR 
						        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) OR 
						        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) OR
						        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) OR
						        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) OR
						        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) OR
						        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) OR
						        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) OR
						        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) OR 
						        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) OR 
						        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) OR 
						        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) OR 
						        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) OR
						        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) OR
						        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) OR
						        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) OR
						        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) OR
						        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) OR
						        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) OR 
						        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) OR 
						        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) OR 
						        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) OR 
						        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) OR
						        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) OR
						        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) OR
						        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) OR
						        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) OR
						        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) OR
						        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) OR 
						        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) OR 
						        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) OR 
						        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) OR 
						        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) OR
						        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) OR
						        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) OR
						        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) OR
						        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) OR
						        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) OR
						        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) OR 
						        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) OR 
						        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) OR 
						        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) OR 
						        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) OR
						        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) OR
						        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) OR
						        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) OR
						        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) OR
						        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) OR
						        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) OR 
						        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) OR 
						        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) OR 
						        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) OR 
						        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) OR
						        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) OR
						        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) OR
						        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) OR
						        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) OR
						        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) OR
						        (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) OR 
						        (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) OR 
						        (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) OR 
						        (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) OR 
						        (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) OR
						        (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) OR
						        (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) OR
						        (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) OR
						        (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) OR
						        (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
						       ) OR 
						       (ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
						        ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
						        ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
						        ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
						        ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
						        ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
						        ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
						        ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
						        ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
						        ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
						        ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
						        ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
						        ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
						        ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
						        ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
						        ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_80) > b.max_days OR
						        ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_85) > b.max_days OR
						        ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days 
						       ) and ";
				}else{
					//$wheNya = "F-T-F";
					$wheNya = "((ceiling(n_1) > b.min_days AND ceiling(n_1) < b.max_days) AND
						        (ceiling(n_2) > b.min_days AND ceiling(n_2) < b.max_days) AND
						        (ceiling(n_3) > b.min_days AND ceiling(n_3) < b.max_days) AND
						        (ceiling(n_4) > b.min_days AND ceiling(n_4) < b.max_days) AND
						        (ceiling(n_5) > b.min_days AND ceiling(n_5) < b.max_days) AND
						        (ceiling(n_6) > b.min_days AND ceiling(n_6) < b.max_days) AND
						        (ceiling(n_7) > b.min_days AND ceiling(n_7) < b.max_days) AND
						        (ceiling(n_8) > b.min_days AND ceiling(n_8) < b.max_days) AND
						        (ceiling(n_9) > b.min_days AND ceiling(n_9) < b.max_days) AND
						        (ceiling(n_10) > b.min_days AND ceiling(n_10) < b.max_days) AND
						        (ceiling(n_11) > b.min_days AND ceiling(n_11) < b.max_days) AND
						        (ceiling(n_12) > b.min_days AND ceiling(n_12) < b.max_days) AND
						        (ceiling(n_13) > b.min_days AND ceiling(n_13) < b.max_days) AND
						        (ceiling(n_14) > b.min_days AND ceiling(n_14) < b.max_days) AND
						        (ceiling(n_15) > b.min_days AND ceiling(n_15) < b.max_days) AND
						        (ceiling(n_16) > b.min_days AND ceiling(n_16) < b.max_days) AND
						        (ceiling(n_17) > b.min_days AND ceiling(n_17) < b.max_days) AND
						        (ceiling(n_18) > b.min_days AND ceiling(n_18) < b.max_days) AND
						        (ceiling(n_19) > b.min_days AND ceiling(n_19) < b.max_days) AND
						        (ceiling(n_20) > b.min_days AND ceiling(n_20) < b.max_days) AND
						        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
						        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
						        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
						        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
						        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
						        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
						        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
						        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
						        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
						        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
						        (ceiling(n_21) > b.min_days AND ceiling(n_21) < b.max_days) AND
						        (ceiling(n_22) > b.min_days AND ceiling(n_22) < b.max_days) AND
						        (ceiling(n_23) > b.min_days AND ceiling(n_23) < b.max_days) AND
						        (ceiling(n_24) > b.min_days AND ceiling(n_24) < b.max_days) AND
						        (ceiling(n_25) > b.min_days AND ceiling(n_25) < b.max_days) AND
						        (ceiling(n_26) > b.min_days AND ceiling(n_26) < b.max_days) AND
						        (ceiling(n_27) > b.min_days AND ceiling(n_27) < b.max_days) AND
						        (ceiling(n_28) > b.min_days AND ceiling(n_28) < b.max_days) AND
						        (ceiling(n_29) > b.min_days AND ceiling(n_29) < b.max_days) AND
						        (ceiling(n_30) > b.min_days AND ceiling(n_30) < b.max_days) AND
						        (ceiling(n_31) > b.min_days AND ceiling(n_31) < b.max_days) AND
						        (ceiling(n_32) > b.min_days AND ceiling(n_32) < b.max_days) AND
						        (ceiling(n_33) > b.min_days AND ceiling(n_33) < b.max_days) AND
						        (ceiling(n_34) > b.min_days AND ceiling(n_34) < b.max_days) AND
						        (ceiling(n_35) > b.min_days AND ceiling(n_35) < b.max_days) AND
						        (ceiling(n_36) > b.min_days AND ceiling(n_36) < b.max_days) AND
						        (ceiling(n_37) > b.min_days AND ceiling(n_37) < b.max_days) AND
						        (ceiling(n_38) > b.min_days AND ceiling(n_38) < b.max_days) AND
						        (ceiling(n_39) > b.min_days AND ceiling(n_39) < b.max_days) AND
						        (ceiling(n_40) > b.min_days AND ceiling(n_40) < b.max_days) AND
						        (ceiling(n_41) > b.min_days AND ceiling(n_41) < b.max_days) AND
						        (ceiling(n_42) > b.min_days AND ceiling(n_42) < b.max_days) AND
						        (ceiling(n_43) > b.min_days AND ceiling(n_43) < b.max_days) AND
						        (ceiling(n_44) > b.min_days AND ceiling(n_44) < b.max_days) AND
						        (ceiling(n_45) > b.min_days AND ceiling(n_45) < b.max_days) AND
						        (ceiling(n_46) > b.min_days AND ceiling(n_46) < b.max_days) AND
						        (ceiling(n_47) > b.min_days AND ceiling(n_47) < b.max_days) AND
						        (ceiling(n_48) > b.min_days AND ceiling(n_48) < b.max_days) AND
						        (ceiling(n_49) > b.min_days AND ceiling(n_49) < b.max_days) AND
						        (ceiling(n_50) > b.min_days AND ceiling(n_50) < b.max_days) AND
						        (ceiling(n_51) > b.min_days AND ceiling(n_51) < b.max_days) AND
						        (ceiling(n_52) > b.min_days AND ceiling(n_52) < b.max_days) AND
						        (ceiling(n_53) > b.min_days AND ceiling(n_53) < b.max_days) AND
						        (ceiling(n_54) > b.min_days AND ceiling(n_54) < b.max_days) AND
						        (ceiling(n_55) > b.min_days AND ceiling(n_55) < b.max_days) AND
						        (ceiling(n_56) > b.min_days AND ceiling(n_56) < b.max_days) AND
						        (ceiling(n_57) > b.min_days AND ceiling(n_57) < b.max_days) AND
						        (ceiling(n_58) > b.min_days AND ceiling(n_58) < b.max_days) AND
						        (ceiling(n_59) > b.min_days AND ceiling(n_59) < b.max_days) AND
						        (ceiling(n_60) > b.min_days AND ceiling(n_60) < b.max_days) AND
						        (ceiling(n_61) > b.min_days AND ceiling(n_61) < b.max_days) AND
						        (ceiling(n_62) > b.min_days AND ceiling(n_62) < b.max_days) AND
						        (ceiling(n_63) > b.min_days AND ceiling(n_63) < b.max_days) AND
						        (ceiling(n_64) > b.min_days AND ceiling(n_64) < b.max_days) AND
						        (ceiling(n_65) > b.min_days AND ceiling(n_65) < b.max_days) AND
						        (ceiling(n_66) > b.min_days AND ceiling(n_66) < b.max_days) AND
						        (ceiling(n_67) > b.min_days AND ceiling(n_67) < b.max_days) AND
						        (ceiling(n_68) > b.min_days AND ceiling(n_68) < b.max_days) AND
						        (ceiling(n_69) > b.min_days AND ceiling(n_69) < b.max_days) AND
						        (ceiling(n_70) > b.min_days AND ceiling(n_70) < b.max_days) AND
						        (ceiling(n_71) > b.min_days AND ceiling(n_71) < b.max_days) AND
						        (ceiling(n_72) > b.min_days AND ceiling(n_72) < b.max_days) AND
						        (ceiling(n_73) > b.min_days AND ceiling(n_73) < b.max_days) AND
						        (ceiling(n_74) > b.min_days AND ceiling(n_74) < b.max_days) AND
						        (ceiling(n_75) > b.min_days AND ceiling(n_75) < b.max_days) AND
						        (ceiling(n_76) > b.min_days AND ceiling(n_76) < b.max_days) AND
						        (ceiling(n_77) > b.min_days AND ceiling(n_77) < b.max_days) AND
						        (ceiling(n_78) > b.min_days AND ceiling(n_78) < b.max_days) AND
						        (ceiling(n_79) > b.min_days AND ceiling(n_79) < b.max_days) AND
						        (ceiling(n_80) > b.min_days AND ceiling(n_80) < b.max_days) AND
						        (ceiling(n_81) > b.min_days AND ceiling(n_81) < b.max_days) AND
						        (ceiling(n_82) > b.min_days AND ceiling(n_82) < b.max_days) AND
						        (ceiling(n_83) > b.min_days AND ceiling(n_83) < b.max_days) AND
						        (ceiling(n_84) > b.min_days AND ceiling(n_84) < b.max_days) AND
						        (ceiling(n_85) > b.min_days AND ceiling(n_85) < b.max_days) AND
						        (ceiling(n_86) > b.min_days AND ceiling(n_86) < b.max_days) AND
						        (ceiling(n_87) > b.min_days AND ceiling(n_87) < b.max_days) AND
						        (ceiling(n_88) > b.min_days AND ceiling(n_88) < b.max_days) AND
						        (ceiling(n_89) > b.min_days AND ceiling(n_89) < b.max_days) AND
						        (ceiling(n_90) > b.min_days AND ceiling(n_90) < b.max_days)
						       ) and ";
				}
			}else{
				if ($exp_ito[2] == 'T') {
					//$wheNya = "F-F-T";
					$wheNya = "(ceiling(n_1) > b.max_days OR ceiling(n_2) > b.max_days OR ceiling(n_3) > b.max_days OR ceiling(n_4) > b.max_days OR ceiling(n_5) > b.max_days OR
					            ceiling(n_6) > b.max_days OR ceiling(n_7) > b.max_days OR ceiling(n_8) > b.max_days OR ceiling(n_9) > b.max_days OR ceiling(n_10) > b.max_days OR
					            ceiling(n_11) > b.max_days OR ceiling(n_12) > b.max_days OR ceiling(n_13) > b.max_days OR ceiling(n_14) > b.max_days OR ceiling(n_15) > b.max_days OR
					            ceiling(n_16) > b.max_days OR ceiling(n_17) > b.max_days OR ceiling(n_18) > b.max_days OR ceiling(n_19) > b.max_days OR ceiling(n_20) > b.max_days OR
					            ceiling(n_21) > b.max_days OR ceiling(n_22) > b.max_days OR ceiling(n_23) > b.max_days OR ceiling(n_24) > b.max_days OR ceiling(n_25) > b.max_days OR
					            ceiling(n_26) > b.max_days OR ceiling(n_27) > b.max_days OR ceiling(n_28) > b.max_days OR ceiling(n_29) > b.max_days OR ceiling(n_30) > b.max_days OR
					            ceiling(n_31) > b.max_days OR ceiling(n_32) > b.max_days OR ceiling(n_33) > b.max_days OR ceiling(n_34) > b.max_days OR ceiling(n_35) > b.max_days OR
					            ceiling(n_36) > b.max_days OR ceiling(n_37) > b.max_days OR ceiling(n_38) > b.max_days OR ceiling(n_39) > b.max_days OR ceiling(n_40) > b.max_days OR
					            ceiling(n_41) > b.max_days OR ceiling(n_42) > b.max_days OR ceiling(n_43) > b.max_days OR ceiling(n_44) > b.max_days OR ceiling(n_45) > b.max_days OR
					            ceiling(n_46) > b.max_days OR ceiling(n_47) > b.max_days OR ceiling(n_48) > b.max_days OR ceiling(n_49) > b.max_days OR ceiling(n_50) > b.max_days OR
					            ceiling(n_51) > b.max_days OR ceiling(n_52) > b.max_days OR ceiling(n_53) > b.max_days OR ceiling(n_54) > b.max_days OR ceiling(n_55) > b.max_days OR
					            ceiling(n_56) > b.max_days OR ceiling(n_57) > b.max_days OR ceiling(n_58) > b.max_days OR ceiling(n_59) > b.max_days OR ceiling(n_60) > b.max_days OR
					            ceiling(n_61) > b.max_days OR ceiling(n_62) > b.max_days OR ceiling(n_63) > b.max_days OR ceiling(n_64) > b.max_days OR ceiling(n_65) > b.max_days OR
					            ceiling(n_66) > b.max_days OR ceiling(n_67) > b.max_days OR ceiling(n_68) > b.max_days OR ceiling(n_69) > b.max_days OR ceiling(n_70) > b.max_days OR
					            ceiling(n_71) > b.max_days OR ceiling(n_72) > b.max_days OR ceiling(n_73) > b.max_days OR ceiling(n_74) > b.max_days OR ceiling(n_75) > b.max_days OR
					            ceiling(n_76) > b.max_days OR ceiling(n_77) > b.max_days OR ceiling(n_78) > b.max_days OR ceiling(n_79) > b.max_days OR ceiling(n_30) > b.max_days OR
					            ceiling(n_81) > b.max_days OR ceiling(n_82) > b.max_days OR ceiling(n_83) > b.max_days OR ceiling(n_84) > b.max_days OR ceiling(n_25) > b.max_days OR
					            ceiling(n_86) > b.max_days OR ceiling(n_87) > b.max_days OR ceiling(n_88) > b.max_days OR ceiling(n_89) > b.max_days OR ceiling(n_90) > b.max_days) and ";
				}else{
					//$wheNya = "F-F-F";
					$wheNya = " ";
				}
			}
		}	
	}

	$where ="where rtrim(a.item_type) = '$ty' and $wheNya a.no_id in(6) ";

	include("../../../connect/conn.php");

	$sql = "select distinct a.item_no, a.item_desc, a.no_id, a.description, a.item_type,
		coalesce(b.min_days,0) min, coalesce(b.max_days,0) max, it.item as tipe, coalesce(b.average,0) as std, b.flag_details,
		$avg $filedNya
		from ztb_mrp_data a
		inner join ztb_config_rm b on a.item_no= b.item_no
		inner join item it on a.item_no = it.item_no
		$where 
		order by it.item asc";
	$data = sqlsrv_query($connect, strtoupper($sql));
    // echo $sql;
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
<?php
header('Content-Type: text/plain; charset="UTF-8"');
error_reporting(0);
session_start();
ini_set('max_execution_time', -1);
include("../../connect/conn.php");
$user_name = $_SESSION['id_wms'];

$sql = "delete from ztb_plan_M where user_id = '$user_name'";
$data = sqlsrv_query($connect, $sql);
if( $data === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
		foreach( $errors as $error ) {
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
			echo "code: ".$error[ 'code']."<br />";
			echo "message: ".$error[ 'message']."<br />";
			echo $sql;
		}
	}
}

if (isset($_SESSION['id_wms'])){
	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);
	$user = $_SESSION['id_wms'];
	$msg = '';

	foreach($queries as $query){
		$qty= intval($query->qty);
		$tahun = date('Y');
		$bulan = date('n');
		$wo_no = $query->wo_no;
		$item_no = $query->item_no;
		$brand = $query->brand;
		$date_code= $query->date_code;
		$item_type= $query->item_type;
		$grade= $query->grade;
		$qty_order= intval($query->qty_order);
		$cr_date= $query->cr_date;
		$qty_pallet= intval($query->qty_pallet);
		$plt_no= $query->plt_no;
		$plt_tot= $query->plt_tot;
		
		$cek = "insert into ztb_item_book (item_no,qty, wo_no, plt_no, worker_id, waktu)
			select st.lower_item_no as item_no, 
			CEILING((round((quantity * $qty) / quantity_base,0) + 
			CeilING((quantity * $qty) / quantity_base  * failure_rate/100)) / isnull(bundle_qty,1)) * isnull(bundle_qty,1) as QTY_REQ,
			'$wo_no', 
			'$plt_no',
			'$user_name',
			cast(getdate() as datetime)
			from (select * from structure s
	            	  inner join (select max(level_no) level_nos, upper_item_no upper 
	                        	  from structure 
	                        	  group by upper_item_no
	            				 ) ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos) st
			inner join mps_header r on st.upper_item_no = r.item_no
			left join item i on st.lower_item_no=i.item_no
			left join item i_u on st.upper_item_no=i_u.item_no
			left join unit u on i.uom_q=u.unit_code
			left join whinventory w on i.item_no=w.item_no
			left join (select item_no,sum(qty) qty from ztb_item_book group by item_no) zz on i.item_no = zz.item_no
			left outer join ztb_safety_stock c on st.lower_item_no = c.item_no and period = $bulan and year = '$tahun'
			where work_order = '$wo_no'
			and NOT EXISTS (SELECT *
							FROM ztb_item_book
							WHERE wo_no = '$wo_no' and plt_no = '$plt_no' )
			and st.level_no = r.bom_level
			and st.lower_item_no = i.item_no 
			and lower_item_no < '9999999' 
			and lower_item_no > 2000000
			and lower_item_no not in ('2116095'	,'2116102'	,'2126023'	,'2126036'	,'2126040'	,'2126041'	,'2126052'	,'2145001'	,'2145008'	
									   ,'2150002'	,'2150003'	,'2150007'	,'2150009'	,'2216120'	,'2216215'	,'2226017'	,'2226079'	,'2226080'	
									   ,'2245006'	,'2245012'	,'2245013'	,'2245036'	,'2250001'	,'2250002'	,'2250012'	,'2250022'	,'2250023'	
									   ,'2250024'	,'2250025'	,'2250026'	,'2250027'	,'2317002'	,'2322002'	,'2600001'	,'2600006'	,'2600007'	
									   ,'2600009'	,'2600053'	,'2600068'	,'2600164'	,'2600183'  ,'12600165' ,'2150012'  ,'2150013'  ,'2150010'
									   ,'2116013'	,'2216048')
		";
		$data = sqlsrv_query($connect, $cek);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $cek;
				}
			}
		}


		$cek = "insert into ztb_plan_M (Wo_No,Item_No,Brand,Date_Code,Type_item,Grade,Qty_Order,Cr_Date,Qty_Pallet,Date_Prod,Qty_Prod,PLT_No,Plt_Tot,user_id) 
				select '$wo_no',
					  '$item_no',
					  '$brand',
					  '$date_code',
					  '$item_type',
					  '$grade',
					  '$qty_order',
					  '$cr_date',
					  '$qty_pallet',
					  getdate(),
					  '$qty',
					  '$plt_no',
					  '$plt_tot', 
					  '$user_name'
					  ";
		$data = sqlsrv_query($connect, $cek);
		if( $data === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
					echo $cek;
				}
			}
		}
	
		

	}	
}else{
	$msg .= 'Session Expired';
}

if($msg != ''){
	echo $cek;
}else{
	//TARUH PRINT DISINI
	$sql = "insert into ztb_m_plan select *  from ztb_plan_M where user_id = '$user_name'";
	$data = sqlsrv_query($connect, $sql);
	if( $data === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
				echo $sql;
			}
		}
	}
	echo json_encode('success');
}
?>
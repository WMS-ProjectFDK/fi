<?php
	session_start();
	include "../connect/conn.php"; 
	include "../detect.php";

	$usr = htmlspecialchars($_REQUEST['usr']);

	if($usr == ''){
        echo json_encode(array('errorMsg'=>'Maaf username salah'));
    }else{
        if($usr != 0){
            $sql = "select * from ztb_worker where worker_id=$usr ";
            $result = oci_parse($connect, $sql);
            oci_execute($result);
            $row = oci_fetch_object($result);

            if(trim($usr) == trim($row->WORKER_ID) AND ($row->NAME != '' OR ! is_null($row->NAME))) {
                $_SESSION['id_kanban'] = $row->WORKER_ID;
                $_SESSION['name_kanban'] = $row->NAME;

                //insert log_history
                if ($ip_user != '127.0.0.1'){
                    $ins = "insert into ztb_log_history VALUES ('".$row->WORKER_ID."',
                                                                '".$row->NAME."',
                                                                '".$ip_user."',
                                                                '".date('Y-m-d H:i:s')."',
                                                                'ASSEMBLY KANBAN SYSTEM ',
                                                                '".$user_browser."',
                                                                to_date('".date('Y-m-d')."','yyyy-mm-dd'),
                                                                '".$user_os."')";
                    $insert = oci_parse($connect, $ins);
                    oci_execute($insert);
                }
                echo json_encode(array('SuccessMsg'=>'OK'));
                //echo "<script type='text/javascript'>location.href='assy/index.php';</script>";
            }else{
                echo json_encode(array('errorMsg'=>'Maaf username salah'));
                //echo "<script type='text/javascript'>alert('Maaf username salah');self.location.href = 'index.php?usr=0';</script>";
            }
        }
    }
?>
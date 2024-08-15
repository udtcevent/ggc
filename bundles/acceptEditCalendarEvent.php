<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');
$blackNo =  $_POST["blackNo"];
$redNo =  $_POST["redNo"];
$sendHow =  $_POST["sendHow"];
$sendWho =  $_POST["sendWho"];
$courtID =  $_POST["courtID"];
$dateSend = $_POST['dateSendSummon'];
$dateReport = $_POST['dateReportSummon'];
$sendHowDescription = $_POST["sendHowDescription"];

$qGetDataEditSummons = mysqli_query($conn, "SELECT * FROM _summons WHERE id = '" . $_POST["id"] . "'");
$rGetDataEditSummons = mysqli_fetch_assoc($qGetDataEditSummons);

$qEditDataSummon = mysqli_query($conn, "UPDATE _summons
                                            SET 
                                                _black_no = '" . $blackNo . "', 
                                                _red_no = '" . $redNo . "', 
                                                _send_who = '" . $sendWho . "', 
                                                _send_how = '" . $sendHow . "', 
                                                _send_how_description = '" . $sendHowDescription . "', 
                                                _court_send = '" . $courtID . "', 
                                                _date_send = '" . $dateSend . "', 
                                                _date_report = '" . $dateReport . "'
                                            WHERE id = '" . $_POST["id"] . "'");
if ($qEditDataSummon) {
    $validate = [
        'auth' => true,
        "message" => "ดำเนินการแก้ไขข้อมูลหมายข้ามเขต  เลขคดีดำที่ [ " . $rGetDataEditSummons["_black_no"] . " ] เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        "message" => "ไม่สามารถแก้ไขข้อมูลหมายข้ามเขต  เลขคดีดำที่ [ " . $rGetDataEditSummons["_black_no"] . " ] ได้",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

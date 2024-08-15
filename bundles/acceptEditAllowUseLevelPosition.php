<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');
$qDataAllLevelPosition = mysqli_query($conn, "SELECT * FROM _lposition WHERE id = '" . $_POST["id"] . "'");
$rDataAllLevelPosition = mysqli_fetch_array($qDataAllLevelPosition);
mysqli_free_result($qDataAllLevelPosition);

$allSign = ($rDataAllLevelPosition["_use"] == 1) ? 2 : 1;

$qChangeAllowSign = mysqli_query($conn, "UPDATE _lposition SET _use = '" . $allSign . "' WHERE id = '" . $_POST["id"] . "'");

if ($qChangeAllowSign > 0) {
    $validate = [
        'auth' => true,
        'nowSign' => $allSign,
        "message" => "ดำเนินการปรับสถานะอนุญาตใช้งานระดับตำแหน่ง [ " . $rDataAllLevelPosition["_lposition"] . "ในระบบ เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        'nowSign' => $allSign,
        "message" => "ไม่สามารถปรับสถานะอนุญาตใช้งานระดับตำแหน่ง [ " . $rDataAllLevelPosition["_lposition"] . "ในระบบได้",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

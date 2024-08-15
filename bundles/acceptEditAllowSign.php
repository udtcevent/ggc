<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');
$qUser = mysqli_query($conn, "SELECT
                                    CONCAT(f._front_name, u._fname, ' ', u._lname) AS fullName,
                                    u._use AS SettingSign
                                FROM _user AS u
                                LEFT JOIN _front_name AS f ON u._front_name = f.id
                                WHERE u.id = '" . $_POST["id"] . "'");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);

$allSign = ($rUser["SettingSign"] == 1) ? 2 : 1;

$qChangeAllowSign = mysqli_query($conn, "UPDATE _user SET _use = '" . $allSign . "' WHERE id = '" . $_POST["id"] . "'");

if ($qChangeAllowSign > 0) {
    $validate = [
        'auth' => true,
        'nowSign' => $allSign,
        "message" => "ดำเนินการปรับสิทธิการใช้งานโปรแกรมของ [ " . $rUser["fullName"] . "ในระบบ เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        'nowSign' => $allSign,
        "message" => "ไม่สามารถปรับสิทธิการใช้งานโปรแกรมของ [ " . $rUser["fullName"] . "ในระบบได้",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

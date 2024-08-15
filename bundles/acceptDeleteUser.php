<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');

$qUser = mysqli_query($conn, "SELECT
                                CONCAT(f._front_name, u._fname, ' ', u._lname) AS fullName
                            FROM _user AS u
                            LEFT JOIN _front_name AS f ON u._front_name = f.id
                            WHERE u.id = '" . $_POST["id"] . "'");
$rUser = mysqli_fetch_assoc($qUser);

$qDeleteUser = mysqli_query($conn, "DELETE FROM _user WHERE id = '" . $_POST["id"] . "'");
if ($qDeleteUser) {
    $validate = [
        'auth' => true,
        "message" => "ดำเนินการลบชื่อผู้ใช้งาน [ " . $rUser["fullName"] . " ] เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        "message" => "ไม่สามารถลบชื่อผู้ใช้งาน [ " . $rUser["fullName"] . " ] ได้",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

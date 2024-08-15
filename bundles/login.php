<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');
$date = date('Y-m-d');

$qLogin = mysqli_query($conn, "SELECT * FROM _user WHERE _user = '" . $_POST["username"] . "'");
$cLogin = mysqli_num_rows($qLogin);
$rLogin = mysqli_fetch_assoc($qLogin);

if ($cLogin > 0) {
    if($rLogin["_authentication_level"] > 2){
        $validate = [
            'auth' => false,
            'message' => "ชื่อผู้ใช้งานของท่านเป็นสิทธิระดับ ทั่วไป ไม่สามารถเข้าใช้งานระบบหลังบ้านได้...",
        ];
    }else{
        $qCheckPassword = mysqli_query($conn, "SELECT * FROM _user WHERE _user = '" . $_POST["username"] . "' AND _pass = '" . $_POST["password"] . "'");
            $cCheckPassword = mysqli_num_rows($qCheckPassword);
            $rCheckPassword = mysqli_fetch_assoc($qCheckPassword);
            mysqli_free_result($qCheckPassword);
        
        if($rLogin["_authentication_level"] == 1) {
            if ($cCheckPassword > 0) {
                $validate = [
                    'auth' => true,
                    'authen_id'=> $rCheckPassword["id"],
                    'auth_level' => $rLogin["_authentication_level"],
                    'message' => "ยินดีต้อนรับผู้ใช้งานระดับ ผู้ดูแลระบบ  เข้าสู่ระบบ",
                ];    
            } else {
                $validate = [
                    'auth' => false,
                    'message' => "รหัสผ่านไม่ถูกต้อง",
                ];
            }
        }else{
            $validate = [
                'auth' => true,
                'authen_id'=> $rCheckPassword["id"],
                'auth_level' => $rLogin["_authentication_level"],
                'message' => "ยินดีต้อนรับผู้ใช้งานระดับ ผู้กำหนดกิจกรรม  เข้าสู่ระบบ",
            ];
        }
    }
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        'message' => "ไม่พบชื่อผู้ใช้งาน หรือท่านไม่มีสิทธิเข้าใช้งานระบบ...",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

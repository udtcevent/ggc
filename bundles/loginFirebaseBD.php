<?php
include("config.php");
include("firebaseRDB.php");
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$db = new firebaseRDB($databaseURL);

$user = $_POST["username"];
$pass = $_POST["password"];

$chklogin = $db->retrieve("users/$user");
$data = json_decode($chklogin, 1);

if ($date) {
    $validate = [
        'auth' => true,
        'message' => "พบชื่อผู้ใช้งาน " . $user,
    ];
} else {
    $validate = [
        'auth' => false,
        'message' => "ไม่พบชื่อผู้ใช้งาน " . $user,
    ]; 
}

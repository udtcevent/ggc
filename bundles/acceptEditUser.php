<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');
$FrontName =  $_POST["FrontName"];
$fname =  $_POST["fname"];
$lname =  $_POST["lname"];
$uName =  $_POST["uName"];
$pAss =  $_POST["pAss"];
$Position =  $_POST["Position"];
$Lposition = $_POST['Lposition'];
$Part = $_POST['Part'];
$aUthenticationLevel = $_POST["aUthenticationLevel"];

$qGetDataUser = mysqli_query($conn, "SELECT * FROM _user WHERE id = '" . $_POST["id"] . "'");
$rGetDataUser = mysqli_fetch_assoc($qGetDataUser);

$qEditUser = mysqli_query($conn, "UPDATE _user
                                    SET
                                        _user = '" . $uName . "', 
                                        _pass = '" . $pAss . "',
                                        _front_name = '" . $FrontName . "',
                                        _fname = '" . $fname . "',
                                        _lname = '" . $lname . "', 
                                        _position = '" . $Position . "', 
                                        _lposition = '" . $Lposition . "', 
                                        _part = '" . $Part . "', 
                                        _authentication_level = '" . $aUthenticationLevel . "'
                                    WHERE id = '" . $_POST["id"] . "'");
if ($qEditUser) {
    $validate = [
        'auth' => true,
        "message" => "ดำเนินการแก้ไขข้อมูลผู้ใช้งาน  " .  $rGetDataUser["_fname"] . "  " . $rGetDataUser["_lname"] . "  เข้าระบบ  เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        "message" => "ไม่สามารถแก้ไขข้อมูลผู้ใช้งาน  " .  $rGetDataUser["_fname"] . "  " . $rGetDataUser["_lname"] . "  เข้าระบบ  เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

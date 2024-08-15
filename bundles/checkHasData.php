
<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d');
if ($_POST["typeSearchDataSummons"] < 3) {
    if ($_POST["typeSearchDataSummons"] == 1) {
        $dataSearch = "_black_no LIKE('%" . $_POST["searchSummons"] . "%')";
    } else {
        $dataSearch = "_red_no LIKE('%" . $_POST["searchSummons"] . "%')";
    } 
} else {
    $dataSearch = "_court_send = '" . $_POST["searchSummons"] . "'";
}

$qCheckHasData = mysqli_query($conn, "SELECT * FROM _summons WHERE $dataSearch");
$cCheckHasData = mysqli_num_rows($qCheckHasData);

if ($cCheckHasData > 0) {
    $validate = [
        'auth' => true,
        'dataSearch' => $_POST["searchSummons"],
    ]; 
    echo json_encode($validate);   
} else {
    $validate = [
        'auth' => false,
        'dataSearch' => $_POST["searchSummons"],
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

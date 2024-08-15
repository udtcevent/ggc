<?php
include('ggc/config.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$today = date('Y-m-d');
?>

<table class="table table-striped" style="background-color:#212121; color:#fff;">
    <thead>
        <tr>
            <th style="width:70%; vertical-align:middle; font-weight: bold; font-size:20px;"><a href="index.html" style="text-decoration: none; color:#fff;">ระบบบริหารจัดการปฏิทินงานและการแจ้งเตือนผ่านไลน์ - UDTC Announcement</a></th>
            <th style="width: 20%">
                <button type="button" class="btn btn-success btn-lg" style="width: 100%" onclick="ShowCalendar();"><i class="fa fa-calendar"></i> แสดงปฏิทินกิจกรรม (เต็มจอ)</button>
            </th>
            <th style="width:10%">
                <a type="button" class="btn btn-danger btn-lg" style="width:100%;" href="main.html"><i class="fa fa-sign-in"></i> Login</a>
            </th>
        </tr>
    </thead>
</table>

<?php mysqli_close($conn); ?>

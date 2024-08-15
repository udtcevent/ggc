<?php
include('conn.php');
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
            <!-- <th style="width:20%;">
                <div class="form-floating">
                    <select class="form-select" id="typeSearchDataSummons" onchange="ReviewTypeSearch();">
                        <option value="1">1. ค้นจากเลขคดีดำ</option>
                        <option value="2">2. ค้นจากเลขคดีแดง</option>
                        <option value="3">3. ค้นจากศาลที่นำส่ง</option>
                    </select>
                    <label for="floatingSelect" style="color:#212121;">ประเภทการค้นหา</label>
                </div>
            </th>
            <th style="width:37%">
                <div id="reviewTypeSeach" style="width:100%;">
                    <div class="form-floating">
                        <input type="text" id="searchSummons" class="form-control" style="width:100%;" placeholder="ระบุคำค้นหา..." />
                        <label for="floatingInputGrid" style="color:#212121;">ระบุคำค้นหา...</label>
                    </div>
                </div>
            </th>
            <th style="width:10%">
                <button type="button" class="btn btn-primary btn-lg" style="width:100%; height:58px;" onclick="SearchSummon();"><i class="fa fa-search"></i> ค้นข้อมูล</button>
            </th>
            <th style="width:3%; text-align:center; font-size:45px;">
                ||
            </th> -->
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
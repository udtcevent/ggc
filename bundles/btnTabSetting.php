<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');
?>
<div class="row" style="padding:0 2% 1% 2%;">
    <div class="col-4">
        <button type="button" class="<?php echo ($_GET["useTab"] == 1) ? 'btn btn-secondary btn-lg' : 'btn btn-primary btn-lg"'; ?>" style="width:100%;" <?php echo ($_GET["useTab"] == 1) ? 'disabled' : 'onclick="SettingTab(1);"'; ?>><i class="fa fa-users"></i>  รายชื่อ</button>
    </div>
    <div class="col-4">
        <button type="button" class="<?php echo ($_GET["useTab"] == 2) ? 'btn btn-secondary btn-lg' : 'btn btn-primary btn-lg"'; ?>" style="width:100%;" <?php echo ($_GET["useTab"] == 2) ? 'disabled' : 'onclick="SettingTab(2);"'; ?>><i class="fa fa-users"></i>  ตั้งค่าตำแหน่ง</button>
    </div>
    <div class="col-4">
        <button type="button" class="<?php echo ($_GET["useTab"] == 3) ? 'btn btn-secondary btn-lg' : 'btn btn-primary btn-lg"'; ?>" style="width:100%;" <?php echo ($_GET["useTab"] == 3) ? 'disabled' : 'onclick="SettingTab(3);"'; ?>><i class="fa fa-users"></i>  ตั้งค่าระดับตำแหน่ง</button>
    </div>
    <!-- <div class="col-3">
        <button type="button" class="<?php echo ($_GET["useTab"] == 4) ? 'btn btn-secondary btn-lg' : 'btn btn-primary btn-lg"'; ?>" style="width:100%;" <?php echo ($_GET["useTab"] == 4) ? 'disabled' : 'onclick="SettingTab(4);"'; ?>><i class="fa fa-university"></i>  ตั้งค่าศาล</button>
    </div> -->
</div>
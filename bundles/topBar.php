<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
        });
        calendar.render();
    });
</script>
<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$qUser = mysqli_query($conn, "SELECT * FROM _user WHERE id = '" . $_GET["authen_id"] . "'");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);
?>

<table class="table table-striped" style="background-color: #212121; color: #fff">
    <thead>
        <tr>
            <?php
            if ($_GET["curPage"] > 0) { ?>
                <th style="width:10%;">
                    <button type="button" class="btn btn-danger btn-lg" style="width: 100% height:58px;" onclick="Back(0);"><?php echo "<< back"; ?></button>
                </th>
            <?php } ?>
            <th style="<?php echo ($rUser["_authentication_level"] == 1) ? "width: 40%; vertical-align:middle; font-weight: bold; font-size:16px;" : "width: 70%; vertical-align:middle; font-weight: bold; font-size:16px;"; ?>">
                <?php if ($_GET["curPage"] == 0) {
                    echo '<a href="index.html" style="text-decoration: none; color:#fff; font-size:20px;">ระบบบริหารจัดการปฏิทินงานและการแจ้งเตือนผ่านไลน์ - UDTC Announcement</a>';
                } else if ($_GET["curPage"] == 1) {
                    echo "การตั้งค่าโปรแกรม";
                } ?>
            </th>
            <?php
            if ($rUser["_authentication_level"] < 3) { ?>
                <th style="width: 10%">
                    <button type="button" class="btn btn-success btn-lg" style="width: 100%" onclick="ShowCalendar();"><i class="fa fa-calendar"></i> แสดงปฏิทินกิจกรรม</button>
                </th>
            <?php if ($rUser["_authentication_level"] < 2) { ?>
                    <th style="width: 10%">
                        <button type="button" class="btn btn-primary btn-lg" style="width: 100%" onclick="SettingForm(1);"><i class="fa fa-cogs"></i> ตั้งค่าระบบ</button>
                    </th>
                <?php }
                } ?>
            <th style="width: 10%">
                <button type="button" class="btn btn-danger btn-lg" style="width: 100%" onclick="SignOut();"><i class="fa fa-sign-out"></i> ออกจากระบบ</button>
            </th>
        </tr>
    </thead>
</table>

<?php mysqli_close($conn); ?>
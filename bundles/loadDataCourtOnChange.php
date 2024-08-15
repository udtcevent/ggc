<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$today = date('Y-m-d');
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>ชื่อศาล</th>
            <th>ภาค</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $tabID = ($_GET["tabID"] == 1) ? "WHERE _status_use IN(1) " : (($_GET["tabID"] == "") ? "WHERE _status_use IN(1) " : "WHERE _status_use = 2 ");

        if ($_GET["searchKey"] == 1) {
            if ($_GET["dataSearch"] == "") {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 10";
            } else {
                $onCase = $tabID . "AND _court_name LIKE('%" . $_GET["dataSearch"] . "%') ORDER BY _status_use ASC, _id_court_group ASC";
            }
        } else {
            if ($_GET["onCase"] == 1) {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 10";
            } else if ($_GET["onCase"] == 2) {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 50";
            } else if ($_GET["onCase"] == 3) {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 100";
            } else if ($_GET["onCase"] == 4) {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 200";
            } else if ($_GET["onCase"] == 5) {
                $onCase = $tabID . "ORDER BY _status_use ASC, _id_court_group ASC LIMIT 300";
            } else {
                $onCase = "ORDER BY _status_use ASC, _id_court_group ASC";
            }
        }

        $i = 1;
        $qDataAllCourt = mysqli_query($conn, "SELECT * FROM _court $onCase");
        $cDataAllCourt = mysqli_num_rows($qDataAllCourt);
        if ($cDataAllCourt < 1 || $cDataAllCourt == "") { ?>
            <tr>
                <td colspan="5"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูล <?php echo '[ ' . $_GET["dataSearch"] . ' ]'; ?> ที่ท่านต้องการค้นหา..</button></td>
            </tr>
            <?php } else {
            while ($rDataAllCourt = mysqli_fetch_array($qDataAllCourt)) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $rDataAllCourt["_court_name"]; ?></td>
                    <td>
                        <?php
                        if ($rDataAllCourt["_id_court_group"] == "") {
                            echo 'ศาลสูงหรือศาลไม่สังกัดสำนักงานภาค';
                        } else {
                            if ($rDataAllCourt["_id_court_group"] == 0) {
                                echo 'ศาลในส่วนกลาง';
                            } else {
                                echo 'ศาลในสังกัดสำนักงานภาค ' . $rDataAllCourt["_id_court_group"];
                            }
                        } ?>
                    </td>
                    <td>
                        <div id="editAllowUseCourt_<?php echo $rDataAllCourt["id"]; ?>"><button type="button" class="btn btn-<?php echo ($rDataAllCourt['_status_use'] == 1) ? 'success' : 'warning'; ?>" onclick="EditAllowUseCourt(<?php echo $rDataAllCourt['id']; ?>);"><i class="fa fa-edit"></i> ปรับสถานะ [ <?php echo ($rDataAllCourt['_status_use'] == 1) ? 'ไม่ใช้งาน' : 'ใช้งาน'; ?> ]</button></div>
                        <!-- <button type="button" class="btn btn-primary" onclick="EditCourt(<?php echo $rDataAllCourt['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button>
                        <button type="button" class="btn btn-danger" onclick="DeleteCourt(<?php echo $rDataAllCourt['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button> -->
                    </td>
                </tr>
        <?php $i++;
            }
        } ?>
    </tbody>
</table>
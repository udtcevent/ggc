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
        <tr style="text-align:center; background-color: #000; color:#fff;">
            <th>#</th>
            <th>ตำแหน่ง</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($_GET["searchKey"] == 1) {
            if ($_GET["dataSearch"] == "") {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 10";
            } else {
                $onCase = "WHERE _position LIKE('%" . $_GET["dataSearch"] . "%') GROUP BY id ASC";
            }
        } else {
            if ($_GET["onCase"] == 1) {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 10";
            } else if ($_GET["onCase"] == 2) {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 50";
            } else if ($_GET["onCase"] == 3) {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 100";
            } else if ($_GET["onCase"] == 4) {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 200";
            } else if ($_GET["onCase"] == 5) {
                $onCase = "WHERE _use IN(1) GROUP BY id ASC LIMIT 300";
            } else {
                $onCase = "GROUP BY id ASC";
            }
        }

        $i = 1;
        $qDataAllPosition = mysqli_query($conn, "SELECT * FROM _position $onCase");
        $cDataAllPosition = mysqli_num_rows($qDataAllPosition);
        if ($cDataAllPosition < 1) { ?>
            <tr>
                <td colspan="3"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการตำแหน่ง..</button></td>
            </tr>
            <?php } else {
            while ($rDataAllPosition = mysqli_fetch_array($qDataAllPosition)) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $rDataAllPosition["_position"]; ?></td>
                    <td>
                        <div id="editAllowUsePosition_<?php echo $rDataAllPosition["id"]; ?>"><button type="button" class="btn btn-<?php echo ($rDataAllPosition['_use'] == 1) ? 'success' : 'warning'; ?>" onclick="EditAllowUsePosition(<?php echo $rDataAllPosition['id']; ?>);"><i class="fa fa-edit"></i> ปรับสถานะ [ <?php echo ($rDataAllPosition['_use'] == 1) ? 'ไม่ใช้งาน' : 'ใช้งาน'; ?> ]</button></div>
                        <!-- <button type="button" class="btn btn-primary" onclick="EditPosition(<?php echo $rDataAllPosition['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button> -->
                        <!-- <button type="button" class="btn btn-danger" onclick="DeletePosition(<?php echo $rDataAllPosition['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button> -->
                    </td>
                </tr>
        <?php $i++;
            }
        } ?>
    </tbody>
</table>
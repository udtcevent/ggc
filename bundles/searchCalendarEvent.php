<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$today = date('Y-m-d');

$qUser = mysqli_query($conn, "SELECT * FROM _user WHERE id = '" . $_GET["authen_id"] . "'");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);
?>
<table class="table table-striped">
    <thead>
        <tr style="background-color: #000; color:#fff; text-align:center;">
            <th>#</th>
            <th>เลขคดีดำ</th>
            <th>เลขคดีแดง</th>
            <th>หมายของ</th>
            <th>ศาลผู้ส่ง</th>
            <th>วันที่ส่งหมาย</th>
            <th>วันที่รายงานหมาย</th>
            <th>วันที่นำเข้าข้อมูล</th>
            <th>ไฟล์</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $dataSearch = ($_GET["typeSearchDataSummons"] == 1) ? "_black_no LIKE('%" . $_GET["dataSearch"] . "%')" : (($_GET["typeSearchDataSummons"] == 2) ? "_red_no LIKE('%" . $_GET["dataSearch"] . "%')" : "_court_send = '" . $_GET["dataSearch"] . "'");
        $i = 1;
        $qDataSummon = mysqli_query($conn, "SELECT * FROM _summons WHERE $dataSearch ORDER BY id DESC LIMIT 10");
        $cDataSummon = mysqli_num_rows($qDataSummon);
        if ($cDataSummon < 1) { ?>
            <tr>
                <td colspan="8"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการหมายซัมมอนในวันนี้..</button></td>
            </tr>
            <?php } else {
            while ($rDataSummon = mysqli_fetch_array($qDataSummon)) {
                $qDataFileUpload = mysqli_query($conn, "SELECT * FROM _file_upload WHERE id_summons = '" . $rDataSummon["id"] . "'");
                $rDataFileUpload = mysqli_fetch_assoc($qDataFileUpload);
                mysqli_free_result($qDataFileUpload);
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $rDataSummon["_black_no"]; ?></td>
                    <td style="color:red;"><?php echo $rDataSummon["_red_no"]; ?></td>
                    <td><?php echo $rDataSummon["_send_who"]; ?></td>
                    <td><?php echo $rDataSummon["_court_send"]; ?></td>
                    <td><?php echo substr($rDataSummon["_date_send"], 8, 2) . ' ' . Check_Month(substr($rDataSummon["_date_send"], 5, 2)) . ' ' . (substr($rDataSummon["_date_send"], 0, 4) + 543); ?></td>
                    <td><?php echo substr($rDataSummon["_date_report"], 8, 2) . ' ' . Check_Month(substr($rDataSummon["_date_report"], 5, 2)) . ' ' . (substr($rDataSummon["_date_report"], 0, 4) + 543); ?></td>
                    <td><?php echo substr($rDataSummon["_datetime_create"], 8, 2) . ' ' . Check_Month(substr($rDataSummon["_datetime_create"], 5, 2)) . ' ' . (substr($rDataSummon["_datetime_create"], 0, 4) + 543); ?></td>
                    <td>
                        <?php if ($rUser["_authentication_level"] == 1) { ?>
                            <a class="btn <?php echo ($rUser["_authentication_level"] == 1 ? "btn-success" : "btn-danger"); ?>" href="<?php echo $rDataFileUpload["_file_path"] . $rDataFileUpload["_file_encode_name"]; ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> หมาย</a>
                            <!-- <button type="button" class="btn btn-dark" onclick="AddMoreSummon(<?php echo $rDataSummon['id']; ?>);"><i class="fa fa-plus"></i> เพิ่มหมาย</button> -->
                            <button type="button" class="btn btn-primary" onclick="EditDataSummons(<?php echo $rDataSummon['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="DeleteDataSummons(<?php echo $rDataSummon['id'] . ',' . $rDataFileUpload['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button>
                        <?php } else { ?>
                            <a class="btn btn-success" href="<?php echo $rDataFileUpload["_file_path"] . $rDataFileUpload["_file_encode_name"]; ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> หมาย</a>
                            <button type="button" class="btn btn-secondary" onclick="ViewSummons(<?php echo $rDataSummon['id']; ?>);"><i class="fa fa-eye"></i> รายละเอียดหมาย</button>
                        <?php } ?>
                    </td>
                </tr>
        <?php $i++;
            }
        } ?>
    </tbody>
</table>
<?php
mysqli_close($conn);
?>
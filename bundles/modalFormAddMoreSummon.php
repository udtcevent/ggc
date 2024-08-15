<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$Year = date('Y');
$month = date('m');
$day = date('d');
$today = date('Y-m-d');

$qUser = mysqli_query($conn, "SELECT * FROM _user WHERE id = '" . $_GET["authen_id"] . "'");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);

$qDataSummon = mysqli_query($conn, "SELECT
                                        s.id AS id,
                                        s._black_no AS _black_no,
                                        s._red_no AS _red_no,
                                        s._send_how AS _send_how,
                                        s._send_how_description AS _send_how_description,
                                        s._send_who AS _send_who,
                                        s._court_send AS _court_send,
                                        s._date_send AS _date_send,
                                        s._date_report AS _date_report,
                                        fu._file_path AS _file_path,
                                        fu._file_real_name AS _file_real_name,
                                        fu._file_encode_name AS _file_encode_name
                                    FROM _summons AS s
                                    LEFT JOIN _file_upload AS fu ON s._black_no = fu._black_no
                                    WHERE s.id = '" . $_GET["id"] . "'");
$rDataSummon = mysqli_fetch_assoc($qDataSummon);
mysqli_free_result($qDataSummon);

?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title">เพิ่มหมายข้ามเขต => [ เลขคดีดำที่ : <?php echo '<font color="#000"><u>' . $rDataSummon["_black_no"] . '</u></font> ]' . ($rDataSummon["_red_no"] != "" ? ', [ เลขคดีแดงที่ : <font color="red"><u>' . $rDataSummon["_red_no"] . '</u></font> ]' : ''); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">เลขคดีดำ</span>
        <input type="text" id="blackNo" class="form-control" placeholder="เลขคดีดำ" aria-label="เลขคดีดำ" value="<?php echo $rDataSummon["_black_no"]; ?>" disabled>
        <span class="input-group-text" style="background-color:#DD2C00; color:#fff;">เลขคดีแดง</span>
        <input type="text" id="redNo" class="form-control" placeholder="เลขคดีแดง" aria-label="เลขคดีแดง" value="<?php echo $rDataSummon["_red_no"]; ?>"  disabled>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">ผู้รับหมาย</span>
        <input type="text" id="sendWho" class="form-control" placeholder="ผู้รับหมาย เช่น โจทก์ , จำเลย หรืออื่นๆ ตามระบุ" aria-label="ผู้รับหมาย" value="">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">ส่งโดย</span>
        <select class="form-select" id="sendHow" data-live-search="true" onchange="SendHow(<?php echo $_GET['id']; ?>);">
            <?php
            $qSendHow = mysqli_query($conn, "SELECT * FROM _send_how ORDER BY id ASC");
            while ($rSendHow = mysqli_fetch_array($qSendHow)) { ?>
                <option value="<?php echo $rSendHow["id"]; ?>"><?php echo $rSendHow["_send_how"]; ?></option>
            <?php } ?>
        </select>
        <div id="sendHowDescription" style="width:100%;">
            <?php
            if ($rDataSummon["_send_how"] > 6 && $rDataSummon["_send_how"] < 9) { ?>
                <div class="input-group mb-3"><span class="input-group-text">อธิบายเพิ่มเติม</span><textarea class="form-control" id="sendHowDescription" aria-label="อธิบายเพิ่มเติม"></textarea></div>
            <?php } ?>
        </div>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">ศาลที่ส่งหมาย</span>
        <select class="form-select" id="court" data-live-search="true" title="เลือกศาลที่ส่งหมาย">
            <?php
            $qCourtName = mysqli_query($conn, "SELECT * FROM _court WHERE _id_court_group <> '' ORDER BY _id_court_group ASC, _court_name ASC");
            while ($rCourtName = mysqli_fetch_array($qCourtName)) {
                if ($rCourtName["_id_court_group"] == "") {
                    $courtGroup = 'ศาลสูงหรือศาลไม่สังกัดสำนักงานภาค';
                } else {
                    if ($rCourtName["_id_court_group"] == 0) {
                        $courtGroup = 'ส่วนกลาง';
                    } else {
                        $courtGroup = 'สำนักงานภาค ' . $rCourtName["_id_court_group"];
                    }
                } ?>
                <option value="<?php echo $rCourtName["_court_name"]; ?>"><?php echo '[ สังกัด : <strong>' . $courtGroup . '</strong> ] => ' . $rCourtName["_court_name"]; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">วันที่ส่งหมาย</span>
        <select class="form-select" id="dayDateSend" data-live-search="true">
            <?php
            for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>" <?php echo (date('d') == $i ? "selected" : ""); ?>><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="monthDateSend" data-live-search="true">
            <?php
            for ($j = 1; $j <= 12; $j++) { ?>
                <option value="<?php echo ($j < 10) ? ('0' . $j) : $j; ?>" <?php echo (date('m') == $j ? "selected" : ""); ?>><?php echo Check_Month(($j < 10) ? ('0' . $j) : $j); ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="yearDateSend" data-live-search="true">
            <?php
            for ($k = ($Year - 50); $k <= ($Year + 10); $k++) { ?>
                <option value="<?php echo $k; ?>" <?php echo (date('Y') == $k ? "selected" : ""); ?>><?php echo ($k + 543); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">วันที่รายงานหมาย</span>
        <select class="form-select" id="dayDateReport" data-live-search="true">
            <?php
            for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>" <?php echo (date('d') == $i ? "selected" : ""); ?>><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="monthDateReport" data-live-search="true">
            <?php
            for ($j = 1; $j <= 12; $j++) { ?>
                <option value="<?php echo ($j < 10) ? ('0' . $j) : $j; ?>" <?php echo (date('m') == $j ? "selected" : ""); ?>><?php echo Check_Month(($j < 10) ? ('0' . $j) : $j); ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="yearDateReport" data-live-search="true">
            <?php
            for ($k = ($Year - 50); $k <= ($Year + 10); $k++) { ?>
                <option value="<?php echo $k; ?>" <?php echo (date('Y') == $k ? "selected" : ""); ?>><?php echo ($k + 543); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">เลือกไฟล์</span>
        <input type="file" id="file" class="form-control" placeholder="เลือกไฟล์" aria-label="เลือกไฟล์" accept=".pdf">
    </div>
    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="UploadFile()"><i class="fa fa-save"></i> บันทึกเพิ่มหมาย</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>

    <script>
        CloseModal = () => {
            $("#modalFormAddMoreSummon").modal('hide');
        }
    </script>
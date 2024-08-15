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

$qCalendarEvent = mysqli_query($conn, "SELECT
                                        ce.id AS id,
                                        ce._event_title AS eventTitle,
                                        ce._event_detail AS eventDetails,
                                        ce._event_type_id AS eventTypeID,
                                        et._event_type_name AS eventTypeName,
                                        ce._event_startdate AS eventStartDate,
                                        ce._event_enddate AS eventEndDate,
                                        ce._event_starttime AS eventStartTime,
                                        ce._event_endtime AS eventEndTime,
                                        ce._event_createdate AS eventCreateDate,
                                        ce._user_create AS userCreate
                                    FROM _calendar_event AS ce
                                    LEFt JOIN _event_type AS et ON ce._event_type_id = et.id
                                    WHERE ce.id = '" . $_GET["id"] . "'");
$rCalendarEvent = mysqli_fetch_assoc($qCalendarEvent);
mysqli_free_result($qCalendarEvent);

?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title">แบบฟอร์มแก้ไขข้อมูลกิจกรรม => [ เลขคดีดำที่ : <?php echo '<font color="#000"><u>' . $rCalendarEvent["_black_no"] . '</u></font> ]' . ($rCalendarEvent["_red_no"] != "" ? ', [ เลขคดีแดงที่ : <font color="red"><u>' . $rCalendarEvent["_red_no"] . '</u></font> ]' : ''); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff; width:7%;">ประเภทกิจกรรม</span>
        <select class="form-select" id="eventTypeID" style="width:12%;" data-live-search="true" onchange="EventType();">
            <?php
            $i = 1;
            $qEventType = mysqli_query($conn, "SELECT * FROM _event_type ORDER BY id ASC");
            while ($rEventType = mysqli_fetch_array($qEventType)) { ?>
                <option value="<?php echo $rEventType["id"]; ?>"><?php echo $i . ') ' . $rEventType["_event_type_name"]; ?></option>
            <?php $i++;
            } ?>
            <option value="" disabled>-----------------------------------------------------------</option>
            <option value="0">นัดอื่นๆ กรุณาระบุหัวข้อนัดที่ต้องการ...</option>
        </select>
        <span id="checkMoreType" style="width:30%;"><input type="text" id="eventMoreType" class="form-control" placeholder="ระบุหัวข้อนัดประเภทอื่นๆ" aria-label="ระบุหัวข้อนัดประเภทอื่นๆ"></span>
        <span class="input-group-text" style="background-color:#000; color:#fff; width:7%;">หัวข้อกิจกรรม</span>
        <input type="text" id="eventTitle" style="width:43%;" class="form-control" placeholder="หัวข้อกิจกรรม" aria-label="หัวข้อกิจกรรม">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">รายละเอียด หรือชื่อผู้เกี่ยวข้อง(ถ้ามี)</span>
        <!-- <input type="text" id="eventDetails" class="form-control" placeholder="รายละเอียด(ถ้ามี)" aria-label="รายละเอียด(ถ้ามี)"> -->
        <textarea type="text" id="eventDetails" class="form-control" style="height:100px;" placeholder="รายละเอียด หรือชื่อผู้เกี่ยวข้อง(ถ้ามี)" aria-label="รายละเอียด หรือชื่อผู้เกี่ยวข้อง(ถ้ามี)"></textarea>
        <span class="input-group-text" style="background-color:#000; color:#fff;">สถานที่จัดกิจกรรม(ถ้ามี)</span>
        <input type="text" id="eventLocation" class="form-control" placeholder="สถานที่จัดกิจกรรม(ถ้ามี)" aria-label="สถานที่จัดกิจกรรม(ถ้ามี)">
        <!-- <textarea type="text" id="eventLocation" class="form-control" style="height:100px;" placeholder="สถานที่จัดกิจกรรม(ถ้ามี)" aria-label="สถานที่จัดกิจกรรม(ถ้ามี)"></textarea> -->
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">วันที่เริ่มกิจกรรม</span>
        <select class="form-select" id="dayStartDate" data-live-search="true">
            <?php
            for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>"><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="monthStartDate" data-live-search="true">
            <?php
            for ($j = 1; $j <= 12; $j++) { ?>
                <option value="<?php echo ($j < 10) ? ('0' . $j) : $j; ?>" <?php echo (date('m') == $j ? "selected" : ""); ?>><?php echo Check_Month(($j < 10) ? ('0' . $j) : $j); ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="yearStartDate" data-live-search="true">
            <?php
            for ($k = ($Year - 50); $k <= ($Year + 10); $k++) { ?>
                <option value="<?php echo $k; ?>" <?php echo (date('Y') == $k ? "selected" : ""); ?>><?php echo ($k + 543); ?></option>
            <?php } ?>
        </select>
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">วันสุดท้ายกิจกรรม</span>
        <select class="form-select" id="dayEndDate" data-live-search="true">
            <?php
            for ($i = 1; $i <= 31; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>"><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="monthEndDate" data-live-search="true">
            <?php
            for ($j = 1; $j <= 12; $j++) { ?>
                <option value="<?php echo ($j < 10) ? ('0' . $j) : $j; ?>" <?php echo (date('m') == $j ? "selected" : ""); ?>><?php echo Check_Month(($j < 10) ? ('0' . $j) : $j); ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="yearEndDate" data-live-search="true">
            <?php
            for ($k = ($Year - 50); $k <= ($Year + 10); $k++) { ?>
                <option value="<?php echo $k; ?>" <?php echo (date('Y') == $k ? "selected" : ""); ?>><?php echo ($k + 543); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#01579B; color:#fff;">เวลาเริ่มต้น</span>
        <select class="form-select" id="hourStart" data-live-search="true">
            <?php
            for ($i = 0; $i < 24; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>"><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="minuitStart" data-live-search="true">
            <?php
            for ($j = 0; $j < 60; $j++) { ?>
                <option value="<?php echo (strlen($j) < 2) ? ('0' . $j) : $j; ?>"><?php echo ($j < 10) ? ('0' . $j) : $j; ?></option>
            <?php } ?>
        </select>

        <span class="input-group-text" style="background-color:#01579B; color:#fff;">เวลาสิ้นสุด</span>
        <select class="form-select" id="hourEnd" data-live-search="true">
            <?php
            for ($i = 0; $i < 24; $i++) { ?>
                <option value="<?php echo ($i < 10) ? ('0' . $i) : $i; ?>"><?php echo ($i < 10) ? ('0' . $i) : $i; ?></option>
            <?php } ?>
        </select>
        <select class="form-select" id="minuitEnd" data-live-search="true">
            <?php
            for ($j = 0; $j < 60; $j++) { ?>
                <option value="<?php echo (strlen($j) < 2) ? ('0' . $j) : $j; ?>"><?php echo ($j < 10) ? ('0' . $j) : $j; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="modal-footer">
    <div class="row" style="width:100%;">
        <?php if ($_GET["authen_id"] != "") { ?>
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="EditCalendarEvent(<?php echo $_GET['id']; ?>)"><i class="fa fa-save"></i> บันทึกแก้ไขข้อมูล</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        <?php } else {
            $qDataFileUpload = mysqli_query($conn, "SELECT * FROM _file_upload WHERE id_summons = '" . $rCalendarEvent["id"] . "'");
            $rDataFileUpload = mysqli_fetch_assoc($qDataFileUpload);
            mysqli_free_result($qDataFileUpload);
        ?>
            <div class="col-6"><a class="btn btn-success btn-lg" style="width: 100%;" href="<?php echo $rDataFileUpload["_file_path"] . $rDataFileUpload["_file_encode_name"]; ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> ดาวน์โหลด</a></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        <?php } ?>
    </div>
</div>

<script>
    SendHowEdit = (id) => {
        const sendHow = $("select#sendHowEdit").val();
        if (sendHow > 6 && sendHow < 9) {
            $("#sendHowDescriptionEdit").load("bundles/formSendHowDescriptionEdit.php?id=" + id + "&idSendHow=" + sendHow);
        } else {
            $("#sendHowDescriptionEdit").html('');
        }
    }

    EditCalendarEvent = (id) => {
        const data = JSON.parse(sessionStorage.sess);
        const blackNo = $("#blackNoEdit").val();
        const redNo = $("#redNoEdit").val();
        const sendWho = $("#sendWhoEdit").val();
        const sendHow = $("select#sendHowEdit").val();
        const sendHowDescription = $("textarea#sendHowDescriptionEdit").val();
        const courtID = $("select#courtEdit").val();
        const dateSendSummon = $("select#yearDateSendEdit").val() + '-' + $("select#monthDateSendEdit").val() + '-' + $("select#dayDateSendEdit").val();
        const dateReportSummon = $("select#yearDateReportEdit").val() + '-' + $("select#monthDateReportEdit").val() + '-' + $("select#dayDateReportEdit").val();

        if (blackNo == "") {
            alert("กรุณากรอกข้อมูล 'เลขคดีดำ' ก่อนดำเนินการ...");
        } else if (sendWho == "") {
            alert("กรุณากรอกข้อมูล 'ผู้รับหมาย' ก่อนดำเนินการ...");
        } else {
            const dataSend = {
                "authen_id": data.authen_id,
                "id": id,
                "blackNo": blackNo,
                "redNo": redNo,
                "sendHow": sendHow,
                "sendWho": sendWho,
                "sendHowDescription": sendHowDescription,
                "courtID": courtID,
                "dateSendSummon": dateSendSummon,
                "dateReportSummon": dateReportSummon
            };

            // console.log(dataSend);

            $.ajax({
                type: 'POST',
                url: "bundles/acceptEditCalendarEvents.php",
                data: dataSend,
                dataType: 'json',
                success: (result) => {
                    // console.log(result);
                    result.auth == false ?
                        setTimeout(() => {
                            alert(result.message);
                        }, 0) :
                        setTimeout(() => {
                            alert(result.message);
                            $("#fetchDataSearch").load("bundles/loadCalendarEventOnChange.php?authen_id=" + data.authen_id + "&onCase=1");
                            $("#modalFormEditCalendarEvents").modal('hide');
                        }, 0);
                }
            });
        }
    }

    CloseModal = () => {
        $("#modalFormEditCalendarEvents").modal('hide');
    }
</script>
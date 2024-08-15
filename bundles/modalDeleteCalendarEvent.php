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

$qDataEvent = mysqli_query($conn, "SELECT
                                        ce.id AS id,
                                        ce._event_title AS eventTitle,
                                        ce._event_detail AS eventDetails,
                                        ce._event_type_id AS eventTypeID,
                                        et._event_type_name AS eventTypeName,
                                        ce._event_startdate AS eventStartDate,
                                        ce._event_starttime AS eventStartTime,
                                        ce._event_endtime AS eventEndTime,
                                        ce._event_createdate AS eventCreateDate,
                                        ce._user_create AS userCreate
                                    FROM _calendar_event AS ce
                                    LEFt JOIN _event_type AS et ON ce._event_type_id = et.id
                                    WHERE ce.id = '" . $_GET["id"] . "'");
$rDataEvent = mysqli_fetch_assoc($qDataEvent);
mysqli_free_result($qDataEvent);

$dateEvent = ((substr($rDataEvent["eventStartDate"], 8, 1) == 0) ? substr($rDataEvent["eventStartDate"], 9, 1) : substr($rDataEvent["eventStartDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventStartDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventStartDate"], 0, 4) + 543)

?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title"><?php echo 'ลบรายการกิจกรรม => [ หัวข้อ : <font color="#fff"><u>' . $rDataEvent["eventTitle"] . '</u></font> ], [ ประจำวันที่ : <font color="#fff"><u>' . $dateEvent . '</u></font> ]'; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <input type="text" class="form-control" value="ท่านยืนยันจะลบกิจกรรมนี้หรือไม่ ?" disabled>
    </div>

    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="AcceptDeleteCalendarEvent(<?php echo $_GET['id']; ?>);"><i class="fa fa-save"></i> ยืนยันลบหมายข้ามเขตนี้</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>
</div>

<script>
    AcceptDeleteCalendarEvent = (id,) => {
        const data = JSON.parse(sessionStorage.sess);
        const dataSend = {
            "authen_id": data.authen_id,
            "id": id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptDeleteCalendarEvent.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $("#fetchDataSearch").load("bundles/loadDataEventOnChange.php?id=" + idFile + "&authen_id=" + data.authen_id + "&onCase=1");
                        $("#modalFormDeleteSummons").modal('hide');
                    }, 0);
            }
        });
    }

    CloseModal = () => {
        $("#modalFormDeleteSummons").modal('hide');
    }
</script>
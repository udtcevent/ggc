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

$qCourt = mysqli_query($conn, "SELECT * FROM _court WHERE id = '" . $_GET["id"] . "'");
$rCourt = mysqli_fetch_array($qCourt);
mysqli_free_result($qCourt);


?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title">แบบฟอร์มเพิ่มศาลใหม่</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">เลขที่ ศย. ศาล (ถ้ามี)</span>
        <input type="text" id="CourtID" class="form-control" placeholder="เลขที่ ศย. ศาล (ถ้ามี)" aria-label="เลขที่ ศย. ศาล (ถ้ามี)" value="">
        <span class="input-group-text" style="background-color:#000; color:#fff;">ชื่อศาลที่ต้องการเพิ่มใหม่</span>
        <input type="text" id="CourtName" class="form-control" placeholder="ชื่อศาลที่ต้องการเพิ่มใหม่" aria-label="ชื่อศาลที่ต้องการเพิ่มใหม่" value="">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">สังกัด</span>
        <select class="form-select" id="idCourtGroup" data-live-search="true">
            <option value="">ศาลสูงหรือศาลไม่สังกัดสำนักงานภาค</option>
            <option value="0">ศาลในส่วนกลาง</option>
            <option value="1">สำนักงานศาลยุติธรรมประจำภาค 1</option>
            <option value="2">สำนักงานศาลยุติธรรมประจำภาค 2</option>
            <option value="3">สำนักงานศาลยุติธรรมประจำภาค 3</option>
            <option value="4">สำนักงานศาลยุติธรรมประจำภาค 4</option>
            <option value="5">สำนักงานศาลยุติธรรมประจำภาค 5</option>
            <option value="6">สำนักงานศาลยุติธรรมประจำภาค 6</option>
            <option value="7">สำนักงานศาลยุติธรรมประจำภาค 7</option>
            <option value="8">สำนักงานศาลยุติธรรมประจำภาค 8</option>
            <option value="9">สำนักงานศาลยุติธรรมประจำภาค 9</option>
        </select>
    </div>

    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="AcceptAddNewCourt();"><i class="fa fa-save"></i> บันทึกเพิ่มผู้ใช้งานใหม่</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>

    <script>
        AcceptAddNewCourt = () => {
            const data = JSON.parse(sessionStorage.sess);
            const CourtID = $("#CourtID").val();
            const CourtName = $("#CourtName").val();
            const idCourtGroup = $("select#idCourtGroup").val();

            if (CourtName == "") {
                alert("กรุณากรอกข้อมูล 'ชื่อศาล' ก่อนดำเนินการ...");
            } else {
                const dataSend = {
                    "authen_id": data.authen_id,
                    "CourtID": CourtID,
                    "CourtName": CourtName,
                    "idCourtGroup": idCourtGroup
                };

                $.ajax({
                    type: 'POST',
                    url: "bundles/acceptAddNewCourt.php",
                    data: dataSend,
                    dataType: 'json',
                    success: (result) => {
                        result.auth == false ?
                            setTimeout(() => {
                                alert(result.message);
                            }, 0) :
                            setTimeout(() => {
                                alert(result.message);
                                $("#loadContentSetting").load("bundles/allCourt.php?curPage=1&authen_id=" + data.authen_id);
                                $("#modalFormAddNewCourt").modal('hide');
                            }, 0);
                    }
                });
            }
        }

        CloseModal = () => {
            $("#modalFormAddNewCourt").modal('hide');
        }
    </script>
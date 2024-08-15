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
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title">แบบฟอร์มเพิ่มตำแหน่ง</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">ตำแหน่ง</span>
        <input type="text" id="Position" class="form-control" placeholder="ตำแหน่ง" aria-label="ตำแหน่ง" value="">
    </div>

    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="AcceptAddNewPosition();"><i class="fa fa-save"></i> บันทึกเพิ่มตำแหน่งใหม่</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>
</div>
<script>
    AcceptAddNewPosition = () => {
        const data = JSON.parse(sessionStorage.sess);
        const Position = $("#Position").val();

        if (Position == "") {
            alert("กรุณากรอกข้อมูล 'ตำแหน่ง' ก่อนดำเนินการ...");
        } else {
            const dataSend = {
                "authen_id": data.authen_id,
                "Position": Position
            };

            $.ajax({
                type: 'POST',
                url: "bundles/acceptAddNewPosition.php",
                data: dataSend,
                dataType: 'json',
                success: (result) => {
                    result.auth == false ?
                        setTimeout(() => {
                            alert(result.message);
                        }, 0) :
                        setTimeout(() => {
                            alert(result.message);
                            $("#loadContentSetting").load("bundles/allPosition.php?curPage=2&authen_id=" + data.authen_id);
                            $("#modalFormAddNewPosition").modal('hide');
                        }, 0);
                }
            });
        }
    }

    CloseModal = () => {
        $("#modalFormAddNewPosition").modal('hide');
    }
</script>
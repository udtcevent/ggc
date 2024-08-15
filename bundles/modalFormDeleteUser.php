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

$qUser = mysqli_query($conn, "SELECT
                                CONCAT(f._front_name, u._fname, ' ', u._lname) AS fullName
                              FROM _user AS u
                              LEFT JOIN _front_name AS f ON u._front_name = f.id
                              WHERE u.id = '" . $_GET["id"] . "'");
$rUser = mysqli_fetch_assoc($qUser);
mysqli_free_result($qUser);
?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">

    <h5 class="modal-title">ลบผู้ใช้งานชื่อ => [ <?php echo '<u>' . $rUser["fullName"] . '</u>'; ?> ]</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="input-group mb-3">
        <input type="text" class="form-control" value="ท่านยืนยันจะลบชื่อผู้ใช้งานนี้หรือไม่ ?" disabled>
    </div>

    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="AcceptDeleteUser(<?php echo $_GET['id'] . ',' . $_GET['idFile']; ?>);"><i class="fa fa-save"></i> ยืนยันลบหมายข้ามเขตนี้</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>
</div>

<script>
    AcceptDeleteUser = (id) => {
        const data = JSON.parse(sessionStorage.sess);
        const dataSend = {
            "authen_id": data.authen_id,
            "id": id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptDeleteUser.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $("#loadContentSetting").load("bundles/allUser.php?curPage=1&authen_id=" + data.authen_id);
                        $("#modalFormDeleteUser").modal('hide');
                    }, 0);
            }
        });
    }

    CloseModal = () => {
        $("#modalFormDeleteUser").modal('hide');
    }
</script>
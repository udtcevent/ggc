<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');
?>
<h3>รายการชื่อศาล</h3>
<hr />
<div class="row">
    <div class="col-6">
        <div class="input-group mb-3">
            <span class="input-group-text" style="background-color:#000; color:#fff;">ค้นหาจากชื่อศาล</span>
            <input type="text" id="dataSearch" class="form-control" placeholder="ค้นหาจากชื่อศาล" aria-label="ค้นหาจากชื่อศาล" onkeyup="DataSearchCourt();">
        </div>
    </div>
    <div class="col-3" style="text-align: right;">
        <select class="form-select" id="showAllCourtByCase" onchange="ShowAllCourtByCase();">
            <option value="1">แสดงผล 10 รายการ</option>
            <option value="2">แสดงผล 50 รายการ</option>
            <option value="3">แสดงผล 100 รายการ</option>
            <option value="4">แสดงผล 200 รายการ</option>
            <option value="5">แสดงผล 300 รายการ</option>
            <option value="6">แสดงผลทั้งหมด</option>
        </select>
    </div>
    <div class="col-3">
        <button type="button" class="btn btn-success" style="width:100%;" onclick="AddNewCourt();"><i class="fa fa-plus"></i> เพิ่มศาล</button>
    </div>
</div>
<div id="loadBtnTabUseCourt" style="width:100%;">
    <div class="row">
        <div class="col-6">
            <button type="button" class="btn btn-success" style="width:100%;" <?php echo ($_GET["tabID"] == 1 ? 'disabled' : 'onclick="TabUseCourt(1);"'); ?>><i class="fa fa-plus"></i> เปิดใช้งาน</button>
        </div>
        <div class="col-6">
            <button type="button" class="btn btn-warning" style="width:100%;" <?php echo ($_GET["tabID"] == 2 ? 'disabled' : 'onclick="TabUseCourt(2);"'); ?>><i class="fa fa-minus"></i> ปิดใช้งาน</button>
        </div>
    </div>
</div>
<div id="showDataAllCourtOnCase" style="width: 100%;">
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
            $i = 1;
            $qDataAllCourt = mysqli_query($conn, "SELECT * FROM _court ORDER BY _status_use ASC, _id_court_group ASC LIMIT 10");
            $cDataAllCourt = mysqli_num_rows($qDataAllCourt);
            if ($cDataAllCourt < 1) { ?>
                <tr>
                    <td colspan="5"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการหมายซัมมอนในวันนี้..</button></td>
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
                            <!-- <button type="button" class="btn btn-primary" onclick="EditCourt(<?php echo $rDataAllCourt['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button> -->
                            <!-- <button type="button" class="btn btn-danger" onclick="DeleteCourt(<?php echo $rDataAllCourt['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button> -->
                        </td>
                    </tr>
            <?php $i++;
                }
            } ?>
        </tbody>
    </table>
</div>

<?php mysqli_close($conn); ?>

<script>
    ShowAllCourtByCase = () => {
        const cTab = JSON.parse(sessionStorage.court);
        const onCase = $("select#showAllCourtByCase").val();
        $("#showDataAllCourtOnCase").html('');
        $("#showDataAllCourtOnCase").load("bundles/loadDataCourtOnChange.php?searchKey=2&onCase=" + onCase + "&tabID=" + cTab.tabID);
    }

    DataSearchCourt = () => {
        const dataSearch = $("#dataSearch").val();
        $("#showDataAllCourtOnCase").html('');
        $("#showDataAllCourtOnCase").load("bundles/loadDataCourtOnChange.php?searchKey=1&dataSearch=" + dataSearch);
    }

    AddNewCourt = () => {
        const data = JSON.parse(sessionStorage.sess);
        $("#loadContentModalAddNewCourt").html('');
        $("#loadContentModalAddNewCourt").load("bundles/modalFormAddNewCourt.php??authen_id=" + data.authen_id);
        $("#modalFormAddNewCourt").modal("show");
    }

    EditAllowUseCourt = (id) => {
        const dataSend = {
            'id': id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptEditAllowUseCourt.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $(`#editAllowUseCourt_${id}`).html('');
                        $(`#editAllowUseCourt_${id}`).append(`<button type="button" class="btn btn-${result.nowSign ==  1 ? 'success' : 'warning'}" onclick="EditAllowUseCourt(${id})"><i class="fa fa-edit"></i> ปรับสถานะ [ ${result.nowSign ==  1 ? 'ไม่ใช้งาน' : 'ใช้งาน'} ]</button>`);
                    }, 0);
            }
        });
    }

    TabUseCourt = (id) => {
        const onCase = $("select#showAllCourtByCase").val();
        sessionStorage.court = JSON.stringify({ "tabID" : id });
        $("#loadBtnTabUseCourt").html('');
        const btnTabUseCourt = `<div class="row"><div class="col-6"><button type="button" class="btn btn-success" style="width:100%;" ${id == 1 ? 'disabled' : 'onclick="TabUseCourt(1);"'} ><i class="fa fa-plus"></i> เปิดใช้งาน</button></div><div class="col-6"><button type="button" class="btn btn-warning" style="width:100%;" ${id == 2 ? 'disabled' : 'onclick="TabUseCourt(2);"'}><i class="fa fa-minus"></i> ปิดใช้งาน</button></div></div>`;
        $("#loadBtnTabUseCourt").append(btnTabUseCourt);
        $("#showDataAllCourtOnCase").html('');
        $("#showDataAllCourtOnCase").load("bundles/loadDataCourtOnChange.php?searchKey=2&onCase=" + onCase + "&tabID=" + id);
    }
</script>
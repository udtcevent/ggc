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
<h3>รายการตำแน่ง</h3>
<hr />
<div class="row">
    <div class="col-6">
        <div class="input-group mb-3">
            <span class="input-group-text" style="background-color:#000; color:#fff;">ค้นหาจากชื่อระดับตำแหน่ง</span>
            <input type="text" id="dataSearch" class="form-control" placeholder="ค้นหาจากชื่อระดับตำแหน่ง" aria-label="ค้นหาจากชื่อระดับตำแหน่ง" onkeyup="DataSearchAllLevelPosition();">
        </div>
    </div>
    <div class="col-3" style="text-align: right;">
        <select class="form-select" id="showAllLevelPositionByCase" onchange="ShowAllLevelPositionByCase();">
            <option value="1">แสดงผล 10 รายการ</option>
            <option value="2">แสดงผล 50 รายการ</option>
            <option value="3">แสดงผล 100 รายการ</option>
            <option value="4">แสดงผล 200 รายการ</option>
            <option value="5">แสดงผล 300 รายการ</option>
            <option value="6">แสดงผลทั้งหมด</option>
        </select>
    </div>
    <div class="col-3">
        <button type="button" class="btn btn-success" style="width:100%;" onclick="AddNewLevelPosition();"><i class="fa fa-plus"></i> เพิ่มตำแหน่ง</button>
    </div>
</div>
<div id="showDataAllLevelPositionOnCase" style="width: 100%;">
    <table class="table table-striped">
        <thead>
            <tr style="text-align:center; background-color: #000; color:#fff;">
                <th>#</th>
                <th>ระดับตำแหน่ง</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $qDataAllLevelPosition = mysqli_query($conn, "SELECT * FROM _lposition ORDER BY id ASC LIMIT 10");
            $cDataAllLevelPosition = mysqli_num_rows($qDataAllLevelPosition);
            if ($cDataAllLevelPosition < 1) { ?>
                <tr>
                    <td colspan="3"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการตำแหน่ง..</button></td>
                </tr>
                <?php } else {
                while ($rDataAllLevelPosition = mysqli_fetch_array($qDataAllLevelPosition)) { ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo ($rDataAllLevelPosition["_lposition"] == "") ? 'ไม่มีการกำหนดระดับ' : $rDataAllLevelPosition["_lposition"]; ?></td>
                        <td>
                            <div id="editAllowUseLevelPosition_<?php echo $rDataAllLevelPosition["id"]; ?>"><button type="button" class="btn btn-<?php echo ($rDataAllLevelPosition['_use'] == 1) ? 'success' : 'warning'; ?>" onclick="EditAllowUseLevelPosition(<?php echo $rDataAllLevelPosition['id']; ?>);"><i class="fa fa-edit"></i> ปรับสถานะ [ <?php echo ($rDataAllLevelPosition['_use'] == 1) ? 'ไม่ใช้งาน' : 'ใช้งาน'; ?> ]</button></div>
                            <!-- <button type="button" class="btn btn-primary" onclick="EditPosition(<?php echo $rDataAllLevelPosition['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button> -->
                            <!-- <button type="button" class="btn btn-danger" onclick="DeletePosition(<?php echo $rDataAllLevelPosition['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button> -->
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
    ShowAllLevelPositionByCase = () => {
        const onCase = $("select#showAllLevelPositionByCase").val();
        $("#showDataAllLevelPositionOnCase").html('');
        $("#showDataAllLevelPositionOnCase").load("bundles/loadDataAllLevelPositionOnChange.php?searchKey=2&onCase=" + onCase);
    }

    DataSearchAllLevelPosition = () => {
        const dataSearch = $("#dataSearch").val();
        $("#showDataAllLevelPositionOnCase").html('');
        $("#showDataAllLevelPositionOnCase").load("bundles/loadDataAllLevelPositionOnChange.php?searchKey=1&dataSearch=" + dataSearch);
    }

    AddNewLevelPosition = () => {
        console.log("Hear");
        const data = JSON.parse(sessionStorage.sess);
        $("#loadContentModalAddNewLevelPosition").html('');
        $("#loadContentModalAddNewLevelPosition").load("bundles/modalFormAddNewLevelPosition.php?authen_id=" + data.authen_id);
        $("#modalFormAddNewLevelPosition").modal("show");
    }


    EditAllowUseLevelPosition = (id) => {
        const dataSend = {
            'id': id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptEditAllowUseLevelPosition.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $(`#editAllowUseLevelPosition_${id}`).html('');
                        $(`#editAllowUseLevelPosition_${id}`).append(`<button type="button" class="btn btn-${result.nowSign ==  1 ? 'success' : 'warning'}" onclick="EditAllowUseLevelPosition(${id})"><i class="fa fa-edit"></i> ปรับสถานะ [ ${result.nowSign ==  1 ? 'ไม่ใช้งาน' : 'ใช้งาน'} ]</button>`);
                    }, 0);
            }
        });
    }
</script>
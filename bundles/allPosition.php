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
            <span class="input-group-text" style="background-color:#000; color:#fff;">ค้นหาจากชื่อตำแหน่ง</span>
            <input type="text" id="dataSearch" class="form-control" placeholder="ค้นหาจากชื่อตำแหน่ง" aria-label="ค้นหาจากชื่อตำแหน่ง" onkeyup="DataSearchAllPosition();">
        </div>
    </div>
    <div class="col-3" style="text-align: right;">
        <select class="form-select" id="showAllPositionByCase" onchange="ShowAllPositionByCase();">
            <option value="1">แสดงผล 10 รายการ</option>
            <option value="2">แสดงผล 50 รายการ</option>
            <option value="3">แสดงผล 100 รายการ</option>
            <option value="4">แสดงผล 200 รายการ</option>
            <option value="5">แสดงผล 300 รายการ</option>
            <option value="6">แสดงผลทั้งหมด</option>
        </select>
    </div>
    <div class="col-3">
        <button type="button" class="btn btn-success" style="width:100%;" onclick="AddNewPosition();"><i class="fa fa-plus"></i> เพิ่มตำแหน่ง</button>
    </div>
</div>
<div id="showDataAllPositionOnCase" style="width: 100%;">
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
            $i = 1;
            $qDataAllPosition = mysqli_query($conn, "SELECT * FROM _position ORDER BY id ASC LIMIT 10");
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
</div>

<?php mysqli_close($conn); ?>

<script>
    ShowAllPositionByCase = () => {
        const onCase = $("select#showAllPositionByCase").val();
        $("#showDataAllPositionOnCase").html('');
        $("#showDataAllPositionOnCase").load("bundles/loadDataAllPositionOnChange.php?searchKey=2&onCase=" + onCase);
    }

    DataSearchAllPosition = () => {
        const dataSearch = $("#dataSearch").val();
        $("#showDataAllPositionOnCase").html('');
        $("#showDataAllPositionOnCase").load("bundles/loadDataAllPositionOnChange.php?searchKey=1&dataSearch=" + dataSearch);
    }

    AddNewPosition = () => {
        console.log("Hear");
        const data = JSON.parse(sessionStorage.sess);
        $("#loadContentModalAddNewPosition").html('');
        $("#loadContentModalAddNewPosition").load("bundles/modalFormAddNewPosition.php?authen_id=" + data.authen_id);
        $("#modalFormAddNewPosition").modal("show");
    }


    EditAllowUsePosition = (id) => {
        const dataSend = {
            'id': id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptEditAllowUsePosition.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $(`#editAllowUsePosition_${id}`).html('');
                        $(`#editAllowUsePosition_${id}`).append(`<button type="button" class="btn btn-${result.nowSign ==  1 ? 'success' : 'warning'}" onclick="EditAllowUsePosition(${id})"><i class="fa fa-edit"></i> ปรับสถานะ [ ${result.nowSign ==  1 ? 'ไม่ใช้งาน' : 'ใช้งาน'} ]</button>`);
                    }, 0);
            }
        });
    }
</script>
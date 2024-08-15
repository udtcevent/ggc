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
<h3>รายการชื่อผู้ใช้</h3>
<hr />
<div class="row">
    <div class="col-6">
        <div class="input-group mb-3">
            <span class="input-group-text" style="background-color:#000; color:#fff;">ค้นหาจากชื่อ - นามสกุล</span>
            <input type="text" id="dataSearch" class="form-control" placeholder="ค้นหาจากชื่อ - นามสกุล" aria-label="ค้นหาจากชื่อ - นามสกุล" onkeyup="DataSearchAllUser();">
        </div>
    </div>
    <div class="col-3" style="text-align: right;">
        <select class="form-select" id="showAllUserByCase" onchange="ShowAllUserByCase();">
            <option value="1">แสดงผล 10 รายการ</option>
            <option value="2">แสดงผล 50 รายการ</option>
            <option value="3">แสดงผล 100 รายการ</option>
            <option value="4">แสดงผล 200 รายการ</option>
            <option value="5">แสดงผล 300 รายการ</option>
            <option value="6">แสดงผลทั้งหมด</option>
        </select>
    </div>
    <div class="col-3">
        <button type="button" class="btn btn-success" style="width:100%;" onclick="AddNewUser();"><i class="fa fa-plus"></i> เพิ่มผู้ใช้งาน</button>
    </div>
</div>
<div id="showDataAllUserOnCase" style="width: 100%;">
    <table class="table table-striped">
        <thead>
            <tr style="text-align:center; background-color: #000; color:#fff;">
                <th>#</th>
                <th>ชื่อ - นามสกุล</th>
                <th>username</th>
                <th>password</th>
                <th>ตำแหน่ง</th>
                <th>ส่วน</th>
                <th>ระดับสิทธิ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $qDataAllUser = mysqli_query($conn, "SELECT
                                                    u.id AS id,
                                                    u._user AS user,
                                                    u._pass AS pass,
                                                    CONCAT(f._front_name, u._fname, ' ', u._lname) AS fullName,
                                                    CONCAT(p._position, lp._lposition) AS position,
                                                    pt._part AS part,
                                                    al._authentication_level AS authen_level,
                                                    u._authentication_level AS uAuthenLevel,
                                                    u._use AS Setting_use
                                                FROM _user AS u 
                                                LEFT JOIN _front_name AS f ON u._front_name = f.id
                                                LEFT JOIN _position AS p ON u._position = p.id
                                                LEFT JOIN _lposition AS lp ON u._lposition = lp.id
                                                LEFT JOIN _part AS pt ON u._part = pt.id
                                                LEFT JOIN _authentication_level AS al ON u._authentication_level = al.id
                                                WHERE u._use IN(1)
                                                GROUP BY u._fname 
                                                ORDER BY u._authentication_level ASC, p.id ASC 
                                                LIMIT 10");
            $cDataAllUser = mysqli_num_rows($qDataAllUser);
            if ($cDataAllUser < 1) { ?>
                <tr>
                    <td colspan="8"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูลรายการชื่อผู้ใช้..</button></td>
                </tr>
                <?php } else {
                while ($rDataAllUser = mysqli_fetch_array($qDataAllUser)) { ?>
                    <tr <?php if ($rDataAllUser["uAuthenLevel"] < 2) { ?> style="background-color:#455A64; color:#fff;" <?php } ?>>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rDataAllUser["fullName"]; ?></td>
                        <td><?php echo $rDataAllUser["user"]; ?></td>
                        <td><?php echo $rDataAllUser["pass"]; ?></td>
                        <td><?php echo $rDataAllUser["position"]; ?></td>
                        <td><?php echo $rDataAllUser["part"]; ?></td>
                        <td><?php echo $rDataAllUser["authen_level"]; ?></td>
                        <td>
                            <div id="editAllowSign_<?php echo $rDataAllUser["id"]; ?>"><button type="button" class="btn btn-<?php echo ($rDataAllUser['Setting_use'] == 1) ? 'success' : 'warning'; ?>" onclick="EditAllowSign(<?php echo $rDataAllUser['id']; ?>);"><i class="fa fa-edit"></i> ปรับสิทธิ [ <?php echo ($rDataAllUser['Setting_use'] == 1) ? 'ปิด' : 'เปิด'; ?> ]</button></div>
                            <button type="button" class="btn btn-primary" onclick="EditUser(<?php echo $rDataAllUser['id']; ?>);"><i class="fa fa-edit"></i> แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="DeleteUser(<?php echo $rDataAllUser['id']; ?>);"><i class="fa fa-trash"></i> ลบ</button>
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
    ShowAllUserByCase = () => {
        const onCase = $("select#showAllUserByCase").val();
        $("#showDataAllUserOnCase").html('');
        $("#showDataAllUserOnCase").load("bundles/loadDataAllUserOnChange.php?searchKey=2&onCase=" + onCase);
    }

    DataSearchAllUser = () => {
        const dataSearch = $("#dataSearch").val();
        $("#showDataAllUserOnCase").html('');
        $("#showDataAllUserOnCase").load("bundles/loadDataAllUserOnChange.php?searchKey=1&dataSearch=" + dataSearch);
    }

    AddNewUser = () => {
        const data = JSON.parse(sessionStorage.sess);
        $("#loadContentModalAddNewUser").html('');
        $("#loadContentModalAddNewUser").load("bundles/modalFormAddNewUser.php?authen_id=" + data.authen_id);
        $("#modalFormAddNewUser").modal("show");
    }

    EditUser = (id) => {
      const data = JSON.parse(sessionStorage.sess);
      $("#loadContentModalEditUser").html('');
      $("#loadContentModalEditUser").load("bundles/modalFormEditUser.php?authen_id=" + data.authen_id + "&id=" + id);
      $("#modalFormEditUser").modal("show");
    }

    EditAllowSign = (id) => {
        const dataSend = {
            'id' : id
        };

        $.ajax({
            type: 'POST',
            url: "bundles/acceptEditAllowSign.php",
            data: dataSend,
            dataType: 'json',
            success: (result) => {
                result.auth == false ?
                    setTimeout(() => {
                        alert(result.message);
                    }, 0) :
                    setTimeout(() => {
                        alert(result.message);
                        $(`#editAllowSign_${id}`).html('');
                        $(`#editAllowSign_${id}`).append(`<button type="button" class="btn btn-${result.nowSign ==  1 ? 'success' : 'warning'}" onclick="EditAllowSign(${id})"><i class="fa fa-edit"></i> ปรับสิทธิ [ ${result.nowSign ==  1 ? 'ปิด' : 'เปิด'} ]</button>`);
                    }, 0);
            }
        });
    }

    DeleteUser = (id) => {
        const data = JSON.parse(sessionStorage.sess);
        $("#loadContentModalDeleteUser").html('');
        $("#loadContentModalDeleteUser").load("bundles/modalFormDeleteUser.php?id=" + id + "&authen_id=" + data.authen_id);
        $("#modalFormDeleteUser").modal("show");
    }
</script>
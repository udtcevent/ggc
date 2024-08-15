<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$qUser = mysqli_query($conn, "SELECT
                                    u.id AS id,
                                    u._user AS user,
                                    u._pass AS pass,
                                    u._front_name AS front_name,
                                    u._fname As fname, 
                                    u._lname AS lname,
                                    u._position As position,
                                    u._lposition AS lposition,
                                    u._part AS part,
                                    u._authentication_level AS authen_level,
                                    u._authentication_level AS uAuthenLevel,
                                    u._use AS Setting_use
                                FROM _user AS u 
                                LEFT JOIN _front_name AS f ON u._front_name = f.id
                                LEFT JOIN _position AS p ON u._position = p.id
                                LEFT JOIN _lposition AS lp ON u._lposition = lp.id
                                LEFT JOIN _part AS pt ON u._part = pt.id
                                LEFT JOIN _authentication_level AS al ON u._authentication_level = al.id
                                WHERE u.id = '" . $_GET["id"] . "'
                                GROUP BY u._fname 
                                ORDER BY u._authentication_level ASC, p.id ASC");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);


?>
<div class="modal-header" style="background-color:#01579B; color:#fff;">
    <h5 class="modal-title">แบบฟอร์มเพิ่มผู้ใช้งาน</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">คำนำหน้าชื่อ</span>
        <select class="form-select" id="FrontName" data-live-search="true">
            <?php
            $qFrontName = mysqli_query($conn, "SELECT * FROM _front_name ORDER BY id ASC");
            while ($rFrontName = mysqli_fetch_array($qFrontName)) { ?>
                <option value="<?php echo $rFrontName["id"]; ?>" <?php echo ($rUser["front_name"] == $rFrontName["id"]) ? 'selected' : ''; ?>><?php echo $rFrontName["_front_name"]; ?></option>
            <?php } ?>
        </select>
        <span class="input-group-text" style="background-color:#000; color:#fff;">ชื่อ</span>
        <input type="text" id="fname" class="form-control" placeholder="ชื่อ" aria-label="ชื่อ" value="<?php echo $rUser["fname"]; ?>">
        <span class="input-group-text" style="background-color:#000; color:#fff;">นามสกุล</span>
        <input type="text" id="lname" class="form-control" placeholder="นามสกุล" aria-label="นามสกุล" value="<?php echo $rUser["lname"]; ?>">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">USERNAME</span>
        <input type="text" id="uName" class="form-control" placeholder="USERNAME" aria-label="USERNAME" value="<?php echo $rUser["user"]; ?>">
        <span class="input-group-text" style="background-color:#000; color:#fff;">PASSWORD</span>
        <input type="text" id="pAss" class="form-control" placeholder="PASSWORD" aria-label="PASSWORD" value="<?php echo $rUser["pass"]; ?>">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">ตำแหน่ง</span>
        <select class="form-select" id="Position" data-live-search="true">
            <?php
            $qPosition = mysqli_query($conn, "SELECT * FROM _position ORDER BY id ASC");
            while ($rPosition = mysqli_fetch_array($qPosition)) { ?>
                <option value="<?php echo $rPosition["id"]; ?>" <?php echo ($rUser["position"] == $rPosition["id"]) ? 'selected' : ''; ?>><?php echo $rPosition["_position"]; ?></option>
            <?php } ?>
        </select>
        <span class="input-group-text" style="background-color:#000; color:#fff;">ระดับตำแหน่ง</span>
        <select class="form-select" id="Lposition" data-live-search="true">
            <?php
            $qLposition = mysqli_query($conn, "SELECT * FROM _lposition ORDER BY id ASC");
            while ($rLposition = mysqli_fetch_array($qLposition)) { ?>
                <option value="<?php echo $rLposition["id"]; ?>" <?php echo ($rUser["lposition"] == $rLposition["id"]) ? 'selected' : ''; ?>><?php echo $rLposition["_lposition"]; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" style="background-color:#000; color:#fff;">ส่วน</span>
        <select class="form-select" id="Part" data-live-search="true">
            <?php
            $qPart = mysqli_query($conn, "SELECT * FROM _part ORDER BY id ASC");
            while ($rPart = mysqli_fetch_array($qPart)) { ?>
                <option value="<?php echo $rPart["id"]; ?>" <?php echo ($rUser["part"] == $rPart["id"]) ? 'selected' : ''; ?>><?php echo $rPart["_part"]; ?></option>
            <?php } ?>
        </select>
        <span class="input-group-text" style="background-color:#000; color:#fff;">ระดับสิทธิ</span>
        <select class="form-select" id="aUthenticationLevel" data-live-search="true">
            <?php
            $qAuthenticationLevel = mysqli_query($conn, "SELECT * FROM _authentication_level ORDER BY id ASC");
            while ($rAuthenticationLevel = mysqli_fetch_array($qAuthenticationLevel)) { ?>
                <option value="<?php echo $rAuthenticationLevel["id"]; ?>" <?php echo ($rUser["authen_level"] == $rAuthenticationLevel["id"]) ? 'selected' : ''; ?>><?php echo $rAuthenticationLevel["_authentication_level"]; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="modal-footer">
        <div class="row" style="width:100%;">
            <div class="col-6"><button type="button" class="btn btn-success btn-lg" style="width: 100%;" onclick="AcceptEditUser(<?php echo $_GET['id']; ?>);"><i class="fa fa-save"></i> บันทึกเพิ่มผู้ใช้งานใหม่</button></div>
            <div class="col-6"><button type="button" class="btn btn-danger btn-lg" style="width: 100%;" onclick="CloseModal();"><i class="fa fa-times"></i> ปิด</button></div>
        </div>
    </div>
</div>

<script>
    AcceptEditUser = (id) => {
        const data = JSON.parse(sessionStorage.sess);
        const FrontName = $("select#FrontName").val();
        const fname = $("#fname").val();
        const lname = $("#lname").val();
        const uName = $("#uName").val();
        const pAss = $("#pAss").val();
        const Position = $("select#Position").val();
        const Lposition = $("select#Lposition").val();
        const Part = $("select#Part").val();
        const aUthenticationLevel = $("select#aUthenticationLevel").val();

        if (fname == "") {
            alert("กรุณากรอกข้อมูล 'ชื่อ' ก่อนดำเนินการ...");
        } else if (lname == "") {
            alert("กรุณากรอกข้อมูล 'นามสกุล' ก่อนดำเนินการ...");
        } else if (uName == "") {
            alert("กรุณากรอกข้อมูล 'USERNAME' ก่อนดำเนินการ...");
        } else if (pAss == "") {
            alert("กรุณากรอกข้อมูล 'PASSWORD' ก่อนดำเนินการ...");
        } else {
            const dataSend = {
                "authen_id": data.authen_id,
                "id" : id,
                "FrontName": FrontName,
                "fname": fname,
                "lname": lname,
                "uName": uName,
                "pAss": pAss,
                "Position": Position,
                "Lposition": Lposition,
                "Part": Part,
                "aUthenticationLevel": aUthenticationLevel
            };

            $.ajax({
                type: 'POST',
                url: "bundles/acceptEditUser.php",
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
                            $("#modalFormEditUser").modal('hide');
                        }, 0);
                }
            });
        }
    }

    CloseModal = () => {
        $("#modalFormEditUser").modal('hide');
    }
</script>
<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$today = date('Y-m-d');
?>

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
        if ($_GET["searchKey"] == 1) {
            if ($_GET["dataSearch"] == "") {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 10";
            } else {
                $onCase = "WHERE u._fname LIKE('%" . $_GET["dataSearch"] . "%') OR u._lname LIKE('%" . $_GET["dataSearch"] . "%') GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC";
            }
        } else {
            if ($_GET["onCase"] == 1) {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 10";
            } else if ($_GET["onCase"] == 2) {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 50";
            } else if ($_GET["onCase"] == 3) {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 100";
            } else if ($_GET["onCase"] == 4) {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 200";
            } else if ($_GET["onCase"] == 5) {
                $onCase = "WHERE u._use IN(1) GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC LIMIT 300";
            } else {
                $onCase = "GROUP BY u._fname ORDER BY u._authentication_level ASC, p.id ASC";
            }
        }

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
                                            $onCase");
        $cDataAllUser = mysqli_num_rows($qDataAllUser);
        if ($cDataAllUser < 1 || $cDataAllUser == "") { ?>
            <tr>
                <td colspan="8"><button type="button" class="btn btn-default btn-block" style="text-align: center; width: 100%; font-weight:bold;" disabled>ไม่พบข้อมูล <?php echo '[ ' . $_GET["dataSearch"] . ' ]'; ?> ที่ท่านต้องการค้นหา..</button></td>
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
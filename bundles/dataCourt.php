<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$today = date('Y-m-d');
?>

<div class="form-floating">
    <select class="form-select" id="court" data-live-search="true" title="เลือกศาลที่ต้องการค้นหา...">
        <?php
        $qCourtName = mysqli_query($conn, "SELECT * FROM _court WHERE _id_court_group <> '' ORDER BY _id_court_group ASC, _court_name ASC");
        while ($rCourtName = mysqli_fetch_array($qCourtName)) {
            if ($rCourtName["_id_court_group"] == "") {
                $courtGroup = 'ศาลสูงหรือศาลไม่สังกัดสำนักงานภาค';
            } else {
                if ($rCourtName["_id_court_group"] == 0) {
                    $courtGroup = 'ส่วนกลาง';
                } else {
                    $courtGroup = 'สำนักงานภาค ' . $rCourtName["_id_court_group"];
                }
            } ?>
            <option value="<?php echo $rCourtName["_court_name"]; ?>"><?php echo '[ สังกัด : <strong>' . $courtGroup . '</strong> ] => ' . $rCourtName["_court_name"]; ?></option>
        <?php } ?>
    </select>
</div>

<?php mysqli_close($conn); ?>
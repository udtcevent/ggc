<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$qUser = mysqli_query($conn, "SELECT * FROM _user WHERE id = '" . $_GET["authen_id"] . "'");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);

?>
<div id="loadBtnTabSetting" style="width:100%;"></div>
<div class="row" style="padding:0 2% 0 2%;">
    <div id="loadContentSetting" style="width:100%;"></div>
</div>

<?php mysqli_close($conn); ?>

<script>
    $(document).ready(() => {
        const data = JSON.parse(sessionStorage.sess);
        $("#loadBtnTabSetting").load("bundles/btnTabSetting.php?useTab=1&authen_id=" + data.authen_id);
        $("#loadContentSetting").load("bundles/allUser.php?curPage=1&authen_id=" + data.authen_id);
    });
</script>
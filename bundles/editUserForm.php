<?php
include('conn.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$qUser = mysqli_query($conn, "SELECT
                                    u._user AS user,
                                    u._pass AS pass,
                                    CONCAT(f._front_name, u._fname, ' ', u._lname) AS fullName,
                                    CONCAT(p._position, lp._lposition) AS position,
                                    pt._part AS part,
                                    al._authentication_level AS authen_level,
                                    u._authentication_level AS uAuthenLevel
                                FROM _user AS u 
                                LEFT JOIN _front_name AS f ON u._front_name = f.id
                                LEFT JOIN _position AS p ON u._position = p.id
                                LEFT JOIN _lposition AS lp ON u._lposition = lp.id
                                LEFT JOIN _part AS pt ON u._part = pt.id
                                LEFT JOIN _authentication_level AS al ON u._authentication_level = al.id
                                WHERE id = '" . $_GET["id"] . "'
                                ORDER BY u._authentication_level ASC, u.id ASC");
$rUser = mysqli_fetch_array($qUser);
mysqli_free_result($qUser);

?>

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">แก้ไขข้อมูล : <?php echo $rUser["fullName"]; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form>
        <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
        </div>
        <div class="mb-3">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Send message</button>
</div>

<?php mysqli_close($conn); ?>
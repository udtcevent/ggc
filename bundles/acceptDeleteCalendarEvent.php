<?php
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');

$qGetDataDeleteCalendarEvent = mysqli_query($conn, "SELECT
                                        ce.id AS id,
                                        ce._event_title AS eventTitle,
                                        ce._event_detail AS eventDetails,
                                        ce._event_type_id AS eventTypeID,
                                        et._event_type_name AS eventTypeName,
                                        ce._event_startdate AS eventStartDate,
                                        ce._event_starttime AS eventStartTime,
                                        ce._event_endtime AS eventEndTime,
                                        ce._event_createdate AS eventCreateDate,
                                        ce._user_create AS userCreate
                                    FROM _calendar_event AS ce
                                    LEFt JOIN _event_type AS et ON ce._event_type_id = et.id
                                    WHERE ce.id = '" . $_POST["id"] . "'");
$rDataEvent = mysqli_fetch_assoc($qGetDataDeleteCalendarEvent);

$dateEvent = ((substr($rDataEvent["eventStartDate"], 8, 1) == 0) ? substr($rDataEvent["eventStartDate"], 9, 1) : substr($rDataEvent["eventStartDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventStartDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventStartDate"], 0, 4) + 543);

$qDeleteDataCelendar = mysqli_query($conn, "DELETE FROM _calendar_event WHERE id = '" . $_POST["id"] . "'");
if ($qDeleteDataCelendar) {
    $validate = [
        'auth' => true,
        "message" => "ดำเนินการลบกิจกรรม [ " . $rDataEvent["eventTitle"] . " ] เรียบร้อยแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        "message" => "ไม่สามารถลบกิจกรรม [ " . $rDataEvent["eventTitle"] . " ] ได้",
    ];
    echo json_encode($validate);
}

mysqli_close($conn);

<?php
session_start();
include('conn.php');
include('check.php');
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
date_default_timezone_set('Asia/Bangkok');
header("Access-Control-Allow-Origin: http://localhost");   
header("Content-Type: application/json; charset=UTF-8");    
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");    
header("Access-Control-Max-Age: 3600");    
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");    

$date = date('Y-m-d H:i:s');

$eventTitle = $_POST["eventTitle"];
$eventDetails = $_POST["eventDetails"];
$eventLocation = $_POST["eventLocation"];
$eventMoreType = $_POST["eventMoreType"];
$eventStartDate = $_POST["eventStartDate"];
$eventEndDate = $_POST["eventEndDate"];
$eventStartTime = $_POST["eventStartTime"];
$eventEndTime = $_POST["eventEndTime"];
$authenID = $_POST["authen_id"];

if($eventMoreType != "") {
    // Check Event Type Same on database
    $qCHKEventTypeBefore = mysqli_query($conn, "SELECT id, _event_type_name FROM _event_type WHERE _event_type_name LIKE('%" . $eventMoreType . "%') ORDER BY id DESC LIMIT 1");
    $rCHKEventTypeBefore = mysqli_fetch_assoc($qCHKEventTypeBefore);
    $cCHKEventTypeBefore = mysqli_num_rows($qCHKEventTypeBefore);
    if ($cCHKEventTypeBefore > 0) {
        $qUpdateEventType = mysqli_query($conn, "UPDATE _event_type SET _event_type_name = '" . $eventMoreType . "' WHERE id = '" . $rCHKEventTypeBefore["id"] . "'");
        $qGetLastIDEventType = mysqli_query($conn, "SELECT id FROM _event_type ORDER BY id DESC LIMIT 1");
        $rGetLastIDIEventType = mysqli_fetch_assoc($qGetLastIDEventType);
        $eventTypeID = $rGetLastIDIEventType["id"];
    } else {
        $qAddNewEventType = mysqli_query($conn, "INSERT IGNORE INTO _event_type(_event_type_name, _datetime_create, _use)VALUES('" . $_POST["eventMoreType"] . "' , '" . $date . "', 1)");
        $qGetLastIDEventType = mysqli_query($conn, "SELECT id FROM _event_type ORDER BY id DESC LIMIT 1");
        $rGetLastIDIEventType = mysqli_fetch_assoc($qGetLastIDEventType);
        $eventTypeID = $rGetLastIDIEventType["id"];
    }
} else {
    $eventTypeID = $_POST["eventTypeID"];
}

$qAddNewCalendarEvent = mysqli_query($conn, "INSERT IGNORE INTO _calendar_event(_event_title, _event_detail, _event_location, _event_type_id, _event_startdate, _event_enddate, _event_starttime, _event_endtime, _event_createdate, _user_create)
                                                                         VALUES('" . $eventTitle . "','" . $eventDetails . "','" . $eventLocation . "','" . $eventTypeID . "','" . $eventStartDate . "','" . $eventEndDate . "','" . $eventStartTime . "','" . $eventEndTime . "','" . $date . "','" . $authenID . "')");

// mysqli_free_result($qAddNewCalendarEvent);
$qGetLastIDInsertEvent = mysqli_query($conn, "SELECT id FROM _calendar_event WHERE _user_create = '" . $authenID . "' ORDER BY id DESC LIMIT 1");
$rGetLastIDInsertEvent = mysqli_fetch_assoc($qGetLastIDInsertEvent);

if ($qAddNewCalendarEvent) {
    $qUpdateCLDEvent = mysqli_query($conn, "UPDATE _calendar_event SET google_calendar_event_id = '" . $rGetLastIDInsertEvent["id"] . "' WHERE id='" . $rGetLastIDInsertEvent["id"] . "'");
    $qCLDEvent = mysqli_query($conn, "SELECT
                                            ce.id AS id,
                                            ce._event_title AS eventTitle,
                                            ce._event_detail AS eventDetails,
                                            ce._event_location AS eventLocation,
                                            ce._event_type_id AS eventTypeID,
                                            et._event_type_name AS eventTypeName,
                                            ce._event_startdate AS eventStartDate,
                                            ce._event_enddate AS eventEndDate,
                                            ce._event_starttime AS eventStartTime,
                                            ce._event_endtime AS eventEndTime,
                                            ce._event_createdate AS eventCreateDate,
                                            ce._user_create AS userCreate
                                        FROM _calendar_event AS ce
                                        LEFt JOIN _event_type AS et ON ce._event_type_id = et.id
                                        WHERE ce.id = '" . $rGetLastIDInsertEvent["id"] . "'");
    $rCLDEvent = mysqli_fetch_assoc($qCLDEvent);
    // $_SESSION["eventID"] = $rGetLastIDInsertEvent["id"];

    // $dataSend = "?eventID' => $rCLDEvent["id"];
    // $dataSend .= "&eventTitle=" . $rCLDEvent['eventTitle'];
    // $dataSend .= "&eventTypeName=" . $rCLDEvent['eventTypeName'];
    // $dataSend .= "&eventLocation=" . $rCLDEvent['eventLocation'];
    // $dataSend .= "&eventDetails=" . $rCLDEvent['eventDetails'];
    // $dataSend .= "&eventStartDate=" . $rCLDEvent['eventStartDate'];
    // $dataSend .= "&eventEndDate=" . $rCLDEvent['eventEndDate'];
    // $dataSend .= "&eventStartTime=" . $rCLDEvent['eventStartTime'];
    // $dataSend .= "&eventEndTime=" . $rCLDEvent['eventEndTime'];

    $GOOGLE_CLIENT_ID = '746515951882-glui3k5rckb7uapa0pm44rpl6hv4vthm.apps.googleusercontent.com';
    $GOOGLE_CLIENT_SECRE = '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';
    $GOOGLE_OAUTH_SCOPE = 'https://www.googleapis.com/auth/calendar';
    $REDIRECT_URI = 'http://localhost/e-announce/ggc/google_calendar_event_sync.php';
    // $REDIRECT_URI = 'https://ggc-service-f8b2a7b65e09.herokuapp.com/google_calendar_event_sync.php';

    $googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode($GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . $REDIRECT_URI . '&response_type=code&client_id=' . $GOOGLE_CLIENT_ID . '&access_type=online';
    $validate = [
        'auth' => true,
        'eventID' => $rGetLastIDInsertEvent["id"],
        'eventTypeName' => $rCLDEvent['eventTypeName'],
        'eventLocation' => $rCLDEvent['eventLocation'],
        'eventDetails' => $rCLDEvent['eventDetails'],
        'eventStartDate' => $rCLDEvent['eventStartDate'],
        'eventEndDate' => $rCLDEvent['eventEndDate'],
        'eventStartTime' => $rCLDEvent['eventStartTime'],
        'eventEndTime' => $rCLDEvent['eventEndTime'],
        'googleOAuth' => $googleOauthURL,
        'message' => "เพิ่มรายการกิจกรรมใหม่เข้าสู่ระบบแล้ว",
    ];
    echo json_encode($validate);
} else {
    $validate = [
        'auth' => false,
        "message" => "ไม่สามารถเพิ่มรายการกิจกรรมได้  กรุณาติดต่อผู้ดูแลระบบ",
    ];
    echo json_encode($validate);
}
mysqli_close($conn);

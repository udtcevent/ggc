<?php
session_start();
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php';
// Include database configuration file 
require_once '../bundles/conn.php';
$statusMsg = '';
$status = 'danger';
if (isset($_GET['code'])) {
    // Initialize Google Calendar API class 
    $GoogleCalendarApi = new GoogleCalendarApi();

    // Get event ID from session 
    $event_id = $_GET["eventID"];

    if (!empty($event_id)) {
        $qEventData = mysqli_query($conn, "SELECT
                                                ce.id AS id,
                                                ce._event_title AS eventTitle,
                                                ce._event_detail AS eventDetails,
                                                ce._event_type_id AS eventTypeID,
                                                ce._event_location AS eventLocation,
                                                et._event_type_name AS eventTypeName,
                                                ce._event_startdate AS eventStartDate,
                                                ce._event_enddate AS eventEndDate,
                                                ce._event_starttime AS eventStartTime,
                                                ce._event_endtime AS eventEndTime,
                                                ce._event_createdate AS eventCreateDate,
                                                ce._user_create AS userCreate
                                            FROM _calendar_event AS ce
                                            LEFT JOIN _event_type AS et ON ce._event_type_id = et.id
                                            WHERE ce.id = '" . $event_id . "'");
        $eventData = mysqli_fetch_assoc($qEventData);

        if (!empty($eventData)) {
            $calendar_event = array(
                'summary' => ($eventData['eventTitle'] . '(' . $eventData['eventTypeName'] . ')'),
                'location' => $eventData['eventLocation'],
                'description' => $eventData['eventDetails']
            );

            $event_datetime = array(
                'event_date_start' => $eventData['eventStartDate'],
                'event_date_end' => $eventData['eventEndDate'],
                'start_time' => $eventData['eventStartTime'],
                'end_time' => $eventData['eventEndTime']
            );

            // Get the access token 
            $access_token_sess = $_SESSION['google_access_token'];
            if (!empty($access_token_sess)) {
                $access_token = $access_token_sess;
            } else {
                $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
                $access_token = $data['access_token'];
                $_SESSION['google_access_token'] = $access_token;
            }

            if (!empty($access_token)) {
                try {
                    // Get the user's calendar timezone 
                    $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);

                    // Create an event on the primary calendar 
                    $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $user_timezone);

                    //echo json_encode([ 'event_id' => $event_id ]); 
                    if ($google_event_id) {
                        // Update google event reference in the database 
                        $qUpdateCLDEvent = mysqli_query($conn, "UPDATE _calendar_event SET google_calendar_event_id = '" . $event_id . "' WHERE id='" . $event_id . "'");
                        $qCLDEvent = mysqli_query($conn, "SELECT * FROM _calendar_event WHERE id = '" . $event_id . "'");
                        $rCLDEvent = mysqli_fetch_assoc($qCLDEvent);
                        
                        echo '<script>window.close();</script>';
                    }
                } catch (Exception $e) {
                    // header('Bad Request', true, 400); 
                    // echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() )); 
                    $statusMsg = $e->getMessage();
                }
            } else {
                $statusMsg = 'Failed to fetch access token!';
            }
        } else {
            $statusMsg = 'Event data not found!';
        }
    } else {
        // echo "DID NOT HAS EVENT ID";
        $statusMsg = 'Event reference not found!';
    }

    $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg);

    // header("Location: index.php");
    exit();
}

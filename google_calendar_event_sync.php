<?php
session_start();
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php';
// Include database configuration file 
require_once 'config.php';
$statusMsg = '';
$status = 'danger';
if (isset($_GET['code'])) {
    // Initialize Google Calendar API class 
    $GoogleCalendarApi = new GoogleCalendarApi();
    $event_id = $_GET["eventID"];
    $eventTitle = $_GET['eventTitle'];
    $eventTypeName = $_GET['eventTypeName'];
    $eventLocation = $_GET['eventLocation'];
    $eventDetails = $_GET['eventDetails'];
    $eventStartDate = $_GET['eventStartDate'];
    $eventEndDate = $_GET['eventEndDate'];
    $eventStartTime = $_GET['eventStartTime'];
    $eventEndTime = $_GET['eventEndTime'];

    // Get event ID from session 
    // $event_id = $_POST["eventID"];
    // $eventTitle = $_POST['eventTitle'];
    // $eventTypeName = $_POST['eventTypeName'];
    // $eventLocation = $_POST['eventLocation'];
    // $eventDetails = $_POST['eventDetails'];
    // $eventStartDate = $_POST['eventStartDate'];
    // $eventEndDate = $_POST['eventEndDate'];
    // $eventStartTime = $_POST['eventStartTime'];
    // $eventEndTime = $_POST['eventEndTime'];

    if (!empty($event_id)) {
        if (!empty($_GET['code'])) {
            $calendar_event = array(
                'summary' => ($eventTitle . '(' . $eventTypeName . ')'),
                'location' => $eventLocation,
                'description' => $eventDetails
            );

            $event_datetime = array(
                'event_date_start' => $eventStartDate,
                'event_date_end' => $eventEndDate,
                'start_time' => $eventStartTime,
                'end_time' => $eventEndTime
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
                        // $qUpdateCLDEvent = mysqli_query($conn, "UPDATE _calendar_event SET google_calendar_event_id = '" . $event_id . "' WHERE id='" . $event_id . "'");
                        // $qCLDEvent = mysqli_query($conn, "SELECT * FROM _calendar_event WHERE id = '" . $event_id . "'");
                        // $rCLDEvent = mysqli_fetch_assoc($qCLDEvent);
                        
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

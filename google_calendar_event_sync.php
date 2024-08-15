<?php
session_start();
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php';
// Include database configuration file 
define('GOOGLE_CLIENT_ID', '746515951882-glui3k5rckb7uapa0pm44rpl6hv4vthm.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar');
define('REDIRECT_URI', 'https://ggc-service-f8b2a7b65e09.herokuapp.com/google_calendar_event_sync.php');
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
            echo $access_token;
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

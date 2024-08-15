<?php
header("Content-type:text/html; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
//======================================= FOR LOCALHOST  =======================================//
// $DB_HOST = 'localhost';
// $DB_USERNAME = 'erkawit';
// $DB_PASSWORD = 'caogikojt02';
// $DB_NAME = 'e-announce';
// $conn = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
// mysqli_set_charset($conn, 'utf8');




define('GOOGLE_CLIENT_ID', '746515951882-glui3k5rckb7uapa0pm44rpl6hv4vthm.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar');
// define('REDIRECT_URI', 'http://localhost/e-announce/ggc/google_calendar_event_sync.php');
define('REDIRECT_URI', 'https://ggc-service-f8b2a7b65e09.herokuapp.com/google_calendar_event_sync.php');

$googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online';



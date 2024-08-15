<?php
    include('conn.php');
    include('check.php');
    error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
    date_default_timezone_set('Asia/Bangkok');
    header('Content-Type: application/json');
    $date = date('d-m-Y H:i:s');

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    $type = $_POST["type"];
    $authen_id = $_POST["authen_id"];
    $notifyType = $_POST["notifyType"];

    // SET TYPE LINE NOTIFY
    // LOGIN => UDTC Announce หลังบ้าน
    // ADD EVENT => UDTC NEW EVENT

    if($notifyType == "login"){
        $qLogin = mysqli_query($conn, "SELECT
                                            u.id AS id,
                                            u._user AS uName,
                                            CONCAT(fn._front_name, u._fname, ' ', u._lname) AS fullName,
                                            CASE WHEN lp._lposition <> '' THEN CONCAT(p._position, lp._lposition) ELSE p._position END AS position,
                                            pt._part AS part,
                                            atl._authentication_level AS authentication_level,
                                            ln._line_token AS line_token
                                        FROM _user AS u
                                        LEFT JOIN _front_name AS fn ON u._front_name = fn.id
                                        LEFT JOIN _position AS p ON u._position = p.id
                                        LEFT JOIN _lposition AS lp ON u._lposition = lp.id
                                        LEFT JOIN _part AS pt ON u._part = pt.id
                                        LEFT JOIN _authentication_level AS atl ON u._authentication_level = atl.id
                                        LEFT JOIN _line_notify AS ln ON u._line_notify_id = ln.id
                                        WHERE u.id = '" . $authen_id . "'");
        $rLogin = mysqli_fetch_assoc($qLogin);
        $sToken = $rLogin["line_token"];

        if($type == 1){
            $msgType = "เข้าสู่";
        }else{
            $msgType = "ออกจาก";
        }  

        $sMessage = "แจ้งเตือน " . $msgType . " ระบบแจ้งเตือนกิจกรรม\n" . "- DATETIME : " . $date . "\n" . "- ID : " . $rLogin["id"] . "\n" . "- ชื่อ - นามสกุล : " . $rLogin["fullName"] . "\n" . "- ตำแหน่ง : " . $rLogin["position"] . "\n" . "- ส่วน/กลุ่มงาน : " . $rLogin["part"] . "\n" . "- ระดับสิทธิ : " . $rLogin["authentication_level"] . "\n";
    }else{
        $qDataEvent = mysqli_query($conn, "SELECT
                                                ce.id AS id,
                                                ce._event_title AS eventTitle,
                                                ce._event_detail AS eventDetails,
                                                ce._event_type_id AS eventTypeID,
                                                et._event_type_name AS eventTypeName,
                                                ce._event_startdate AS eventStartDate,
                                                ce._event_enddate AS eventEndDate,
                                                ce._event_starttime AS eventStartTime,
                                                ce._event_endtime AS eventEndTime,
                                                ce._event_createdate AS eventCreateDate,
                                                ce._user_create AS userCreate
                                            FROM _calendar_event AS ce
                                            LEFT JOIN _event_type AS et ON ce._event_type_id = et.id
                                            WHERE ce.id = '" . $_POST["eventID"] . "'");
        $rDataEvent = mysqli_fetch_assoc($qDataEvent);
        mysqli_free_result($qDataEvent);
    
        $line_token = "ew0uv0uQ1xIFx8BgtAbnvtNFBahGo2mIVLoDtP7RVqf";
        $sToken = $line_token;

        $msgType = "เพิ่มกิจกรรม : " . $rDataEvent["eventTitle"];
        $sMessage .= "แจ้งเตือน " . $msgType . "\n";
        $sMessage .= "- วันที่แจ้ง : " . ((substr($rDataEvent["eventCreateDate"], 8, 1) == 0 ? substr($rDataEvent["eventCreateDate"], 9, 1) : substr($rDataEvent["eventCreateDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventCreateDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventCreateDate"], 0, 4) + 543)) . "\n";

        if($rDataEvent["eventStartDate"] == $rDataEvent["eventEndDate"]){
            $sMessage .= "- วันกิจกรรม : " . ((substr($rDataEvent["eventStartDate"], 8, 1) == 0 ? substr($rDataEvent["eventStartDate"], 9, 1) : substr($rDataEvent["eventStartDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventStartDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventStartDate"], 0, 4) + 543)) . "\n";
        }else{
            $sMessage .= "- วันเริ่มต้น : " . ((substr($rDataEvent["eventStartDate"], 8, 1) == 0 ? substr($rDataEvent["eventStartDate"], 9, 1) : substr($rDataEvent["eventStartDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventStartDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventStartDate"], 0, 4) + 543));
            $sMessage .= " - " . ((substr($rDataEvent["eventEndDate"], 8, 1) == 0 ? substr($rDataEvent["eventEndDate"], 9, 1) : substr($rDataEvent["eventEndDate"], 8, 2)) . ' ' . Check_Month(substr($rDataEvent["eventEndDate"], 5, 2)) . ' ' . (substr($rDataEvent["eventEndDate"], 0, 4) + 543)) . "\n";
        }

        if($rDataEvent["eventStartTime"] != "00:00:00"){
            $sMessage .= "- เวลา : " . substr($rDataEvent["eventStartTime"], 0, 2) . ':' . substr($rDataEvent["eventStartTime"], 3, 2);
            $sMessage .= " - " . substr($rDataEvent["eventEndTime"], 0, 2) . ':' . substr($rDataEvent["eventEndTime"], 3, 2) . " น.\n";
        }

        if($rDataEvent["eventDetails"] != ""){
            $sMessage .= "- รายละเอียด : " . $rDataEvent["eventDetails"] . "\n";
        }
    }

	

	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 

	//Result error 
    if (curl_error($chOne)) {
        echo 'error:' . curl_error($chOne); 
        $validate = [
            'auth' => false,
            "message"=> "Notify Unsuccessful",
        ];  
    } else {
        $result_ = json_decode($result, true); 
		echo "status : ".$result_['status']; echo "message : ". $result_['message'];
        $validate = [
            'auth' => true,
            'authen_id'=> $rCheckPassword["id"],
            "message"=> "Notify Successfull",
        ]; 
    }
    echo json_encode($validate);
	curl_close( $chOne );   
?>
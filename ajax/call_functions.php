<?php
session_start();
error_reporting(0);
// Include configuration.php
include_once ('../api/configuration.php');
// echo "hello";
// echo $call_function.'madhu';

extract($_REQUEST);

// $_SESSION.$uname = $_POST["txt_username"];
// $_SESSION.$password = $_POST['txt_password'];
// echo $uname;
// print_r($_FILES); exit;

$current_date = date("Y-m-d H:i:s");



// echo $call_function.'madhu';
// Index Page Signin - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "signin") {
	// echo "welcome";
	// Get data
	$uname = htmlspecialchars(strip_tags(isset($_REQUEST['txt_username']) ? $conn->real_escape_string($_REQUEST['txt_username']) : ""));
	// echo $uname;
	$password = htmlspecialchars(strip_tags(isset($_REQUEST['txt_password']) ? $conn->real_escape_string($_REQUEST['txt_password']) : ""));
	// $upass = md5($password);
	site_log_generate("Index Page : Username => " . $uname . " trying to login on " . date("Y-m-d H:i:s"), '../');


	$curl = curl_init();
	$replace_txt = '{
    "txt_username" : "' . $uname . '",
    "txt_password" : "' . $password . '",
    "request_id" : "' . rand(1000000000, 9999999999) . '"
  }';
	
  site_log_generate("Loginpage : " . $_SESSION['yjtsms_user_name'] . " Execute the service [$replace_txt] on " .date("Y-m-d H:i:s"), '../');
//send api response
	curl_setopt_array($curl, array(
		CURLOPT_URL => $api_url.'/login',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $replace_txt,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	)
	);

	$response = curl_exec($curl); //save response
	// echo($response);
	if (curl_errno($curl) == CURLE_OPERATION_TIMEDOUT) {
		$json = array("status" => 0, "msg" => "Service not running, Kindly check the service!!");
	 } elseif (curl_errno($curl)) {
		$json = array("status" => 0, "msg" => "Service not running, Kindly check the service!!");
	 }
	curl_close($curl);
	

$qur = json_decode($response); //seperate response
/*if ($response == '') { ?>
	<script>window.location = "logout"</script>
<? }*/
if ($qur->response_status == 403) { ?>
	<script>window.location = "logout"</script>
<? }

//status==200 allow to proceed  otherwise show error
	if ($qur->response_status == 200) {

		$_SESSION['yjtsms_user_id'] = $qur->user_id;
		$_SESSION['yjtsms_user_master_id'] = $qur->user_master_id;
		$_SESSION['yjtsms_user_name'] = $qur->user_name;
		$_SESSION['yjtsms_user_bearer_token'] = $qur->user_bearer_token;
		
		$result[] = array("usr_id" => $qur->user_id, "usr_name" => $qur->user_name, "user_master_id" => $qur->user_master_id, "user_bearer_token" => $qur->user_bearer_token);
		$json = array("status" => 1, "info" => $result);
		site_log_generate("Index Page : " . $uname . " logged in successfully on " . date("Y-m-d H:i:s"), '../');

	} 
	else if ($qur->response_status == ''){
		$json = array("status" => 0, "msg" => "Kindly confirm your service is running" );

	}else {
		site_log_generate("Index Page : " . $uname . " logged in failed [Invalid Password] on " . date("Y-m-d H:i:s"), '../');
		$json = array("status" => 0, "msg" => "Kindly try again with the valid details!");
	}
}
// Index Page Signin - End

// User Profile Page change_pwd - Start

if ($_SERVER['REQUEST_METHOD'] == "POST" and $pwd_call_function == "change_pwd") {
	// echo "madhu";
	// site_log_generate("Edit Profile - Change Password Page : User : " . $_SESSION['yjtsms_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../');
	// Get data
	$ex_password = htmlspecialchars(strip_tags(isset($_REQUEST['txt_ex_password']) ? $_REQUEST['txt_ex_password'] : ""));
	$new_password = htmlspecialchars(strip_tags(isset($_REQUEST['txt_new_password']) ? $_REQUEST['txt_new_password'] : ""));
	// $ex_pass = md5($ex_password);
	// $upass = md5($new_password);


	$curl = curl_init();
	$replace_txt = '{
    "ex_password" : "' . $ex_password . '",
    "new_password" : "' . $new_password . '",
    "request_id" : "' . rand(1000000000, 9999999999) . '"
  }';
	$bearer_token = 'Authorization: '.$_SESSION['yjtsms_user_bearer_token'].'';
	site_log_generate("Edit Profile - Change Password Page : User : " . $_SESSION['yjtsms_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../' . $replace_txt);

	curl_setopt_array($curl, array(
		CURLOPT_URL => $api_url.'/user/change_password',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$replace_txt,
		CURLOPT_HTTPHEADER => array(
			$bearer_token,
			'Content-Type: application/json'
		),
	));
	

	
	$response = curl_exec($curl); 
	
	curl_close($curl);
	// echo $response;
	$response_data = json_decode($response, true); //store response
		
	if ($response == '') { ?>
		<script>window.location = "logout"</script>
	<? }else if ($response_data['response_status'] == 403) { ?>
		<script>window.location = "logout"</script>
	<? }

	// Check if password change was successful
	if (isset($response_data['response_code']) && $response_data['response_code'] == 1 && $response_data['response_status'] == 200) {
			// Redirect to login page upon successful password change
			// header("Location: " . $site_url . '/logout');
			$json = array("status" => 1, "msg" => "Success");
			
	} else {
			// Handle other response scenarios (optional)
			// echo $response; // Output the response for debugging or error handling
			$json = array("status" => 0, "msg" => $response_data['response_msg']);
			// $msg = isset($response_data['response_msg']) ? $response_data['response_msg'] : 'Unknown error';
			// $json = array("status" => 0, "msg" => $msg);
	}

	}

// User Profile Page change_pwd - End

// send otp - start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $otp_call_function == "mobile_otp") {

	site_log_generate("Mobile OTP - Mobile number OTP Page : User : " . $_SESSION['yjtsms_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../');
	$user_number = $_POST['txt_user_mobile'];
	//    exit;
// echo $user_number ;
	$otp = rand(100000, 999999);
	// echo $_SESSION['otp'];
	$_SESSION['otp'] = $otp;
	$message = "Your OTP is " . $otp . "";
	$campaign_name = "testcmp";
	//$api_adminpswd = 'SMS_api!@3';
//$api_adminuser = 'user_1';
	$api_adminpswd = 'YJt@123';
	$api_adminuser = 'celebmedia';


	// echo 'http://115.243.200.60/sms_api/api/smsapi?process=compose_send_sms&username='.$api_adminuser.'&password='.$api_adminpswd.'&campaign_name='.$campaign_name.'&number='.$user_number.'&message=Your%20OTP%20is%20'.$otp;
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $message_url . 'process=compose_send_sms&username=' . $api_adminuser . '&password=' . $api_adminpswd . '&campaign_name=' . $campaign_name . '&number=' . $user_number . '&message=Your%20OTP%20is%20' . $otp,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_HTTPHEADER => array(
			'Cookie: PHPSESSID=hp9jr2b7q5re7tt3qba2oipn6h'
		),
	)
	);

	$response = curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($http_code == 403) {
	echo '<script>window.location = "logout"</script>';
	exit();
}

	curl_close($curl);
	// echo $response;

	site_log_generate("Mobile OTP - Mobile number OTP Page : User : " . $_SESSION['yjtsms_user_name'] . " OTP Updated ( http://14.99.201.14/sms_api/api/smsapi?process=compose_send_sms&username='.$api_adminuser.'&password='.$api_adminpswd.'&campaign_name='.$campaign_name.'&number='.$user_number.'&message=Your%20OTP%20is%20'.$otp) successfully on " . date("Y-m-d H:i:s"), '../');

	if ($response) {
		site_log_generate("Mobile OTP - Mobile number OTP Page : User : " . $_SESSION['yjtsms_user_name'] . " OTP Updated ($response) successfully on " . date("Y-m-d H:i:s"), '../');
		$json = array("status" => 1, "msg" => "Success");
	}
	// if ($response->errno) {
// 	site_log_generate("Mobile OTP - Mobile number OTP Page : User : ".$_SESSION['yjtsms_user_name']." OTP Update failed [Invalid Inputs] on ".date("Y-m-d H:i:s"), '../');
// $json = array("status" => 0, "msg" => "Invalid otp. Kindly try again with the correct Inputs!");
// }
}
// send otp - END

// Mobile OTP Check Page - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $otp_check_call_function == "mobile_check_otp") {
	// echo "*******************";
	site_log_generate("Mobile OTP - OTP Check Page : User : " . $_SESSION['yjtsms_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../');
	// exit;
	$user_otp = $_POST['txt_user_otp'];

	if ($_SESSION['otp'] == $user_otp) {
		$_SESSION['otp_status'] = 'Y';
		// echo "*******************";
		echo "Otp is valid";
		$json = array("status" => 1, "msg" => "Success");
		// exit;
	} else {
		$_SESSION['otp_status'] = 'N';
		$json = array("status" => 0, "msg" => "Invalid otp. Enter a correct OTP!");
		echo "Otp is not valid";
		// exit;
	}

}




// Register Page - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "register") {

	// Get data
	$user_name = htmlspecialchars(strip_tags(isset($_REQUEST['txt_user_name']) ? $_REQUEST['txt_user_name'] : ""));
	$user_mobile = htmlspecialchars(strip_tags(isset($_REQUEST['txt_user_mobile']) ? $_REQUEST['txt_user_mobile'] : ""));
	$user_email = htmlspecialchars(strip_tags(isset($_REQUEST['txt_user_email']) ? $_REQUEST['txt_user_email'] : ""));
	$otp = htmlspecialchars(strip_tags(isset($_REQUEST['txt_user_otp']) ? $_REQUEST['txt_user_otp'] : ""));
	$imageid = htmlspecialchars(strip_tags(isset($_REQUEST['imageid']) ? $_REQUEST['imageid'] : ""));
	site_log_generate("Index Page : " . $user_name . " trying to create a new account in our site on " . date("Y-m-d H:i:s"), '../');

	$interest_list = "";
	if (isset($_POST['interests']) && is_array($_POST['interests'])) {
			$interest_list_array = $_POST['interests'];
			$sanitized_interest_list_array = array_map(function($item) {
					return htmlspecialchars(strip_tags($item));
			}, $interest_list_array);
			$interest_list = implode(",", $sanitized_interest_list_array);
	}

	$curl = curl_init();
	$bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];

	$postData = json_encode(array(
			"image" => $imageid,
			"customer_interest" => $interest_list,
			"customer_mobile" => $user_mobile,
			"customer_name" => $user_name,
			"camera_id" => "1",
			"otp_verify" => "Y",
			"customer_email" => $user_email,
			"request_id" => rand(1000000000, 9999999999)
	));
	



	curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://localhost:10017/user/user_registration',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => array(
					$bearer_token,
					'Content-Type: application/json'
			),
	));

	$response = curl_exec($curl);

	if ($response == '') { ?>
		<script>window.location = "logout"</script>
<? }
else if ($response['response_status'] == 403) { ?>
		<script>window.location = "logout"</script>
<? }
	
	// echo($response);

	// if (curl_errno($curl)) {
	// 		echo 'Error:' . curl_error($curl);
	// } else {
	// 		echo $response;
	// }

	curl_close($curl);

	$log1 = json_encode(array(
			"customer_mobile" => $user_mobile,
			"customer_name" => $user_name,
			"customer_interest" => $interest_list,
			"camera_id" => "1",
			"image" => $imageid,
			"otp_verify" => $_SESSION['otp_status'],
			"customer_email" => $user_email
	));

	site_log_generate("Index Page : " . $user_name . " trying to create a new account request is " . $log1 . " " . date("Y-m-d H:i:s"), '../');
	site_log_generate("Index Page : " . $user_name . " trying to create a new account response is " . $response . " " . date("Y-m-d H:i:s"), '../');

	$obj = json_decode($response);


	if ($obj && $obj->response_code == 0) {
			$json = array("status" => 1, "msg" => $obj->response_msg);
	} elseif ($obj && $obj->response_code == 1) {
			$json = array("status" => 0, "msg" => $obj->response_msg);
	} else {
			$json = array("status" => 1, "msg" => "Unexpected response format");
	}

	// echo json_encode($json);
}

//registration end


// echo "*******";
// camera Page - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "camera") {
	// echo "*******";

	// Get data
	$camera_name = htmlspecialchars(strip_tags(isset($_REQUEST['camera_position']) ? $_REQUEST['camera_position'] : ""));
	$ip_address = htmlspecialchars(strip_tags(isset($_REQUEST['ip_address']) ? $_REQUEST['ip_address'] : ""));
	$camera_details = htmlspecialchars(strip_tags(isset($_REQUEST['txt_camera_details']) ? $_REQUEST['txt_camera_details'] : ""));
	$cameraradio = htmlspecialchars(strip_tags(isset($_REQUEST['cameraradio']) ? $_REQUEST['cameraradio'] : ""));
	// $interest_list		= htmlspecialchars(strip_tags(isset($_REQUEST['list_items']) ? $_REQUEST['list_items'] : ""));
// echo $cameraradio;
	site_log_generate("Index Page : " . $user_name . " trying to create a new account in our site on " . date("Y-m-d H:i:s"), '../');
	// print_r($list_items);
	// foreach ($list_items as $list){ 
	// 	// echo $list."<br/>";
	// 	$interest_list .= $list.",";
	// }
	// echo $list	;

	if ($cameraradio == 'start') {
		$cameraradio1 = 'Y';
	}
	if ($cameraradio == 'stop') {
		$cameraradio1 = 'N';
	}
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $api_url . '/select_query',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
"query":"SELECT * FROM camera_details where camera_position = \'' . $camera_name . '\'"
}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	)
	);
	$response = curl_exec($curl);
	curl_close($curl);
	// echo $response;
	$obj = json_decode($response);
	// print_r($obj);
	if ($obj->num_of_rows > 0) {
		site_log_generate("Index Page : " . $camera_name . " Register Creation Failed [mobile Number already used] on " . date("Y-m-d H:i:s"), '../');
	}
	$json = array("status" => 0, "msg" => "User Registeration Updated!!");

	// Insert data into data base

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $api_url . '/insert_query',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '{
	 "table_name": "camera_details",
	"variables":"camera_id,store_id, camera_position, ip_address,camera_details,camera_status,camera_entry_date",
	"values": ",Null\'' . $list . '\', \'' . $camera_name . '\', \'' . $ip_address . '\',\'' . $camera_details . '\',\'' . $cameraradio1 . '\',Now()"
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	)
	);
	$response = curl_exec($curl);
	curl_close($curl);
	// echo $response;
	$sql = json_decode($response);

	site_log_generate("Index Page : " . $user_name . " executed the query ($response) on " . date("Y-m-d H:i:s"), '../');
	if ($response) {
		// Get last insert id 
		$lastid = $sql->insert_id;
		// echo $sql->insert_id;
		site_log_generate("Register Page : " . $user_name . " User Inserted on " . date("Y-m-d H:i:s"), '../');
	}


}
// $json = array("status" => 0, "msg" => " Kindly try again with the Valid mobile Number!");


// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header('Content-type: application/json');
echo json_encode($json);


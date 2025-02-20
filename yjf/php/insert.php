
<?php
require 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json);
$user_name=$data->attn_name;
$user_number=$data->attn_number;
$user_face_id=$data->attn_face_id;
$user_image=$data->attn_image;
$current_date = date("Y-m-d H:i:s");
$res = array();

$message = "Dear ".$user_name.", Welcome to Celebmedia.";
//$message = "welcome";
$process = "compose_send_sms";
$campaign_name = "testcmp";
$number = $user_number;
$api_adminpswd = 'SMS_api!@3';
$api_adminuser = 'user_1';

$select_query= "SELECT user_name FROM user_daily_attendance WHERE face_id='".$user_face_id."' AND DATE_FORMAT(attn_entry_date,'%Y-%m-%d')='".date('Y-m-d')."' ";
$result = mysqli_query($conn,$select_query);

if(mysqli_num_rows($result) == 0){
$insert_query="INSERT INTO user_daily_attendance (face_id,user_name, user_image, attendance_status, attn_entry_date,attn_update_date ,attn_flag) VALUES ('$user_face_id','$user_name', '$user_image', 'Y', '$current_date','$current_date', 0)";
//echo $insert_query;
$sql= mysqli_query($conn,$insert_query);
 // $message = "Dear ".$user_name.", Welcome to Yeejai Technologies.";
//$message = "welcome";
//$process = "compose_send_sms";
//$campaign_name = "testcmp";
//$number = $user_number;
//$api_adminpswd = 'SMS_api!@3';
//$api_adminuser = 'user_1';

 $api_keyurl="http://115.243.200.60/sms_api/api/smsapi?process=".$process."&username=".$api_adminuser."&password=".$api_adminpswd."&campaign_name=".$campaign_name."&number=".$number."&message=".$message."";
//echo $api_keyurl."<br>";
$url = str_replace(" ","%20",$api_keyurl);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => 'UTF-8',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Cookie: PHPSESSID=2m8v35c97quptqqp99peo4mdf3'
  ),
));

$response = curl_exec($curl);
//print_r($response);
curl_close($curl);

/*    $url = str_replace(" ","%20",$api_keyurl);
	echo $api_keyurl;
    $curl = curl_init($api_keyurl);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json; charset=utf-8', 'Expect:'));
//     curl_setopt($curl, CURLOPT_POSTFIELDS, $send_data);
    $response = curl_exec($curl);
//	echo "&&&&&".$response;
  //   print_r($response);
//     echo curl_getinfo($curl) . '<br/>';
//   print_r(curl_getinfo($curl));
//     echo curl_errno($curl) . '<br/>';
//     echo curl_error($curl) . '<br/>';
    curl_close($curl); */
$update_query="UPDATE user_daily_attendance SET attn_flag=1,attn_update_date='".date('Y-m-d H:i:s')."' WHERE face_id='".$user_face_id."' AND DATE_FORMAT(attn_entry_date,'%Y-%m-%d')='".date('Y-m-d')."'";
//echo $update_query;
$result_update=mysqli_query($conn,$update_query);
 $res=array("status"=> 0,"msg"=>"success");
}
else{
  $select_query_timeline= "SELECT user_name FROM user_daily_attendance WHERE face_id='".$user_face_id."' AND attn_update_date >= DATE_SUB(NOW(),INTERVAL 15 MINUTE) ";
//  echo $select_query_timeline;

$result_timeline = mysqli_query($conn,$select_query_timeline);
if(mysqli_num_rows($result_timeline) == 0){
    
    $api_keyurl_timeline="http://115.243.200.60/sms_api/api/smsapi?process=".$process."&username=".$api_adminuser."&password=".$api_adminpswd."&campaign_name=".$campaign_name."&number=".$number."&message=".$message."";
//echo $api_keyurl."<br>";
$url_timeline = str_replace(" ","%20",$api_keyurl_timeline);
$curl_timeline = curl_init();

curl_setopt_array($curl_timeline, array(
  CURLOPT_URL => $url_timeline,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => 'UTF-8',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Cookie: PHPSESSID=2m8v35c97quptqqp99peo4mdf3'
  ),
));

$response_timeline = curl_exec($curl_timeline);
//print_r($response);
curl_close($curl_timeline);
$update_query_timeline="UPDATE user_daily_attendance SET attn_update_date='".date('Y-m-d H:i:s')."' WHERE face_id='".$user_face_id."' AND DATE_FORMAT(attn_entry_date,'%Y-%m-%d')='".date('Y-m-d')."'";
//echo $update_query;
$result_update_timeline=mysqli_query($conn,$update_query_timeline);
 $res=array("status"=> 0,"msg"=>"success");

}
else{
    $res=array("status"=> 1,"msg"=>"Already updated");
}
//     $res=array("status"=> 1,"msg"=>"Already updated");
 }
header('Content-type: application/json');
echo json_encode($res);

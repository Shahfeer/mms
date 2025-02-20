<?php
require 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json);
$user_name=$data->user_name;
$user_number=$data->user_number;
$res = array();
//echo "&&&&&&&&&";
//echo $user_name;
$message = "Welcome to Celeb Mall.";
$process = "compose_send_sms";
$campaign_name = "WelcomeCampaign";
$number = $user_number;
$api_adminpswd = 'SMS_api!@3';
$api_adminuser = 'super_admin';
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
/* $api_keyurl="http://27.7.41.196/sms_api/api/smsapi?process=".$process."&username=".$api_adminuser."&password=".$api_adminpswd."&campaign_name=".$campaign_name."&number=".$number."&message=".$message."";
echo $api_keyurl;
    $url = str_replace(" ","%20",$api_keyurl);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json; charset=utf-8', 'Expect:'));
//     curl_setopt($curl, CURLOPT_POSTFIELDS, $send_data);
    $response = curl_exec($curl);
//     print_r($response);
//     echo curl_getinfo($curl) . '<br/>';
//   print_r(curl_getinfo($curl));
//     echo curl_errno($curl) . '<br/>';
//     echo curl_error($curl) . '<br/>';
    curl_close($curl);*/
 $res=array("status"=> 0,"msg"=>"success");

header('Content-type: application/json');
echo json_encode($res);





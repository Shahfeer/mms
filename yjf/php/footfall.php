<?php

$json = file_get_contents('php://input');
$data = json_decode($json);
$face_id=$data->face_id;
$gender=$data->gender;
$gender_confidence=$data->gender_confidence;
$max_age=$data->max_age;
$min_age = $data->min_age;
$cam_id = $data->cam_id;
$res = array();

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:10017/update_table',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "face_id":"'.$face_id.'",
    "gender":"'.$gender.'",
    "gender_confidence":'.$gender_confidence.',
    "max_age":'.$max_age.',
"min_age":'.$min_age.',
"cam_id":'.$cam_id.'

}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;

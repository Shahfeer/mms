<?php

$json = file_get_contents('php://input');
$data = json_decode($json);
$res = array();
$face_id=$data->face_id;

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:10004/remove_user',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "face_id":"'.$face_id.'"

}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;


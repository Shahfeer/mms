<?php
// session_start();
// error_reporting(E_ALL);
// // Include configuration.php
// include_once('../api/configuration.php');

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:10010/update_table',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "face_id":"db2d118c-e3a8-4876-81a8-116bac2c0f04",
    "gender":"Male",
    "gender_confidence":86.4857406616211,
    "max_age":28,
"min_age":20

}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;




return true;

<?php
// require 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json);
$url_name=$data->url_name;
$process = $data->process;

if($process == 'start'){
    $curl_start = curl_init();
curl_setopt_array($curl_start, array(
  CURLOPT_URL => 'http://localhost:10017/user/start_url',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
     "url_name":"'.$url_name.'"

}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
$response_start = curl_exec($curl_start);
curl_close($curl_start);
echo $response_start;


}

if($process == 'stop'){
  $curl_start = curl_init();
  curl_setopt_array($curl_start, array(
    CURLOPT_URL => 'http://localhost:10017/user/stop_url',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
      "url_name":"'.$url_name.'"
  
  }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  $response_start = curl_exec($curl_start);
  curl_close($curl_start);
  echo $response_start; 
}


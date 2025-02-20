<?php
 date_default_timezone_set("Asia/Kolkata");
//  header("Access-Control-Allow-Origin: *");
//  header("Access-Control-Allow-Headers: *");
// if (isset($_SERVER['HTTP_ORIGIN'])) {
//   header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//   header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Max-Age: 86400');    // cache for 1 day
// }

$servername = "mysql-container";
$username = "root";
$password = "";
$dbname = "face_detect";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_error()) {
  echo("Connection test: " . mysqli_connect_error());
}
// else{
// echo "connected successfully";
// }

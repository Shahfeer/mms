<?php
set_time_limit(0);
// Cross-Origin Resource Sharing Header
header('Access-Control-Allow-Origin: http://localhost/mms/');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');

 // Test server Credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mms_service"; 

/*// Live server Credentials
$servername = "192.168.1.27";
$username = "admin";
$password = "Password@123";
$dbname = "mms_service";*/

$site_title     = "MMS";
$site_url       = "http://192.168.1.24/mms/";
$api_url        = "http://localhost:3019";
// $api_url        = "http://localhost:3000";

$site_api_url   = "http://localhost:3004";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
date_default_timezone_set("Asia/Kolkata");

include_once('ajax/site_common_functions.php');

// Log File Generation with Current URL
$log_base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
$log_url = $log_base_url . $_SERVER["REQUEST_URI"]." : IP Address : ".$_SERVER['SERVER_ADDR']." ==> ";

function site_log_generate($log_msg, $location = '')
{
    // $max_size = 1048576; // 1 MB
    $max_size = 10485760; // 10 MB
    // $max_size = 209715200; // 20 MB
    // $max_size = 52428800; // 50 MB

    $log_filename = "site_log";
    if (!file_exists($location."log/".$log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($location."log/".$log_filename, 0777, true);
    }
    $log_file_data1 = $location."log/".$log_filename.'/log_'.date('d-M-Y');
    $log_file_data  = $log_file_data1.'.log';

    clearstatcache();
    $size = filesize($log_file_data);

    // echo "++".$size."++".$max_size."++";
    if($size > $max_size)
    {
        shell_exec("mv ".$log_file_data." ".$log_file_data1."-".date('YmdHis').".log");
    }

    file_put_contents($log_file_data, $log_url.$log_msg . "\n", FILE_APPEND);
}
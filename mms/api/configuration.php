<?php
set_time_limit(0);

// Cross-Origin Resource Sharing (CORS) Headers
header('Access-Control-Allow-Origin: http://localhost/mms/');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');

// Database Credentials
$servername = "mysql-container";
$username = "admin";
$password = "Password@123";
$dbname = "mms"; 
$backup_dbname  = "mms_service_backup";

$GLOBALS['backup_dbname'] = $backup_dbname;

$site_title = "MMS";
$site_url = "http://localhost/mms/";
$api_url = "http://localhost:10017";
//$message_url = "http://27.7.41.11/sms_api/api/smsapi?";  // Commented out on 01-Mar-2024
$message_url = "";

// Create primary database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create backup database connection
$conn1 = new mysqli($servername, $username, $password, $backup_dbname);
if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);
}

// Set MySQL session modes
mysqli_query($conn, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
mysqli_query($conn1, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

// Set the default timezone
date_default_timezone_set("Asia/Kolkata");

include_once('ajax/site_common_functions.php');
#include_once(__DIR__ . '/../ajax/site_common_functions.php');

// Log File Generation with Current URL
$log_base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$log_url = $log_base_url . $_SERVER["REQUEST_URI"] . " : IP Address : " . $_SERVER['SERVER_ADDR'] . " ==> ";

function site_log_generate($log_msg, $location = '')
{
    global $log_url;

    // Maximum log file size
    $max_size = 10485760; // 10 MB

    $log_filename = "site_log";
    $log_directory = $location . "log/" . $log_filename;
    
    if (!file_exists($log_directory)) {
        // Create directory/folder if it doesn't exist
        mkdir($log_directory, 0777, true);
    }

    $log_file_data1 = $log_directory . '/log_' . date('d-M-Y');
    $log_file_data = $log_file_data1 . '.log';

    clearstatcache();
    $size = file_exists($log_file_data) ? filesize($log_file_data) : 0;

    if ($size > $max_size) {
        rename($log_file_data, $log_file_data1 . '-' . date('YmdHis') . '.log');
    }

    file_put_contents($log_file_data, $log_url . $log_msg . "\n", FILE_APPEND);
}

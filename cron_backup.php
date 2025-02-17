<?php
// Database connection parameters
$servername = "localhost";
$username = "admin";
$password = "Password@123";
$dbname_backup = "mms_service_backup";
$dbname_main = "mms";

try {
    // Connect to MySQL databases
    $conn_backup = new PDO("mysql:host=$servername;dbname=$dbname_backup", $username, $password);
    $conn_backup->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn_main = new PDO("mysql:host=$servername;dbname=$dbname_main", $username, $password);
    $conn_main->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Variables for table names and queries
    $yesterday_date = date('dmY', strtotime('-1 day'));
    echo($yesterday_date);
    $new_table_name = "visitor_list_" . $yesterday_date;

    // Create a new table in the backup database with yesterday's date in the name
    $create_table_sql = "CREATE TABLE IF NOT EXISTS $dbname_backup.$new_table_name LIKE $dbname_main.visitor_list";
    $conn_backup->exec($create_table_sql);
    $conn_backup->exec($create_table_sql);
    echo "Table $new_table_name created successfully\n";

    // Insert records from visitor_list in the main database to the new table in the backup database
    $insert_into_table_sql = "INSERT INTO $dbname_backup.$new_table_name SELECT * FROM $dbname_main.visitor_list";
    $conn_backup->exec($insert_into_table_sql);
    echo "Records copied to $new_table_name successfully\n";

    // Optionally: Truncate the original visitor_list table in the main database
    $truncate_table_sql = "TRUNCATE TABLE $dbname_main.visitor_list";
    $conn_main->exec($truncate_table_sql);
    echo "Original table visitor_list truncated successfully\n";

    // Close connections
    $conn_backup = null;
    $conn_main = null;
} 
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

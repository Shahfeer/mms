<?php
header('Content-type: application/json; charset=utf-8');
session_start();
error_reporting(E_ALL);

// Include configuration.php
include_once('configuration.php');
extract($_REQUEST);

$current_date = date("Y-m-d H:i:s");
site_log_generate("Data Backup API Page : User : '".$_REQUEST['username']."' access this page on ".date("Y-m-d H:i:s"), '../');

// Data Backup API table_daily_backup - Start // Daily Morning - 12.01 AM
if($_SERVER['REQUEST_METHOD'] == "GET" and $process == "table_daily_backup") {
	$backup_date 	= date("dmY", strtotime("-1 days"));
	$current_ymd  = date("Y-m-d", strtotime("-1 days"));
	site_log_generate("table_daily_backup Page : backup_date:[$backup_date] on ".date("Y-m-d H:i:s"), '../');

	$exp_result = 0;
	$live_table    = 'visitor_list';
	$backup_table  = 'visitor_list_'.$backup_date;
	
	site_log_generate("table_daily_backup Page : exp_result1:[CREATE TABLE ".$backup_dbname.".".$backup_table." like ".$dbname.".".$live_table."] on ".date("Y-m-d H:i:s"), '../');
	$exp_result1   = mysqli_query($conn1, "CREATE TABLE ".$backup_dbname.".".$backup_table." like ".$dbname.".".$live_table);
	sleep(1);
	if ($exp_result1 > 0)
	{
		site_log_generate("table_daily_backup Page : exp_result2:[insert into ".$backup_dbname.".".$backup_table." select * from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'"."] on ".date("Y-m-d H:i:s"), '../');
		$exp_result2   = mysqli_query($conn1, "insert into ".$backup_dbname.".".$backup_table." select * from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'");
		sleep(1);
		if ($exp_result2 > 0)
		{
			site_log_generate("table_daily_backup Page : exp_result3:[delete from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'] on ".date("Y-m-d H:i:s"), '../');
			$exp_result3 = mysqli_query($conn, "delete from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'");
			sleep(1);
			if ($exp_result3 > 0)
			{
				site_log_generate("table_daily_backup Page : exp_result3:[delete from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'] Success on ".date("Y-m-d H:i:s"), '../');
			} else {
				site_log_generate("table_daily_backup Page : exp_result3:[delete from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'] Failed on ".date("Y-m-d H:i:s"), '../');
			}
		} else {
			site_log_generate("table_daily_backup Page : exp_result2:[insert into ".$backup_dbname.".".$backup_table." select * from ".$dbname.".".$live_table." where date(visitor_list_entdate) = '".$current_ymd."'] Failed on ".date("Y-m-d H:i:s"), '../');
		}
	} else {
		site_log_generate("table_daily_backup Page : exp_result1:[CREATE TABLE ".$backup_dbname.".".$backup_table." like ".$dbname.".".$live_table."] Failed on ".date("Y-m-d H:i:s"), '../');
	}
}
// Data Backup API table_daily_backup - End // Daily Morning - 12.01 AM

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);


<?php
require 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json);
$user_gender=$data->user_gender;
$user_min_age=$data->user_min_age;
$user_max_age=$data->user_max_age;
$user_face_id=$data->user_face_id;

$current_date = date("Y-m-d H:i:s");
$res = array();

$select_query= "SELECT dt_id FROM user_detection WHERE user_face_id='".$user_face_id."' AND DATE_FORMAT(user_entry_date,'%Y-%m-%d')='".date('Y-m-d')."' ";

$result = mysqli_query($conn,$select_query);
if(mysqli_num_rows($result) == 0){

$insert_query="INSERT INTO user_detection (user_face_id,user_gender, user_min_age, user_max_age , is_registered,user_entry_date ,user_last_detected_date) VALUES ('$user_face_id','$user_gender', '$user_min_age', '$user_max_age', 'Y', '$current_date','$current_date')";
//echo $insert_query;
$sql= mysqli_query($conn,$insert_query);
$update_query="UPDATE user_detection SET user_last_detected_date='".date('Y-m-d H:i:s')."' WHERE face_id='".$user_face_id."' AND DATE_FORMAT(user_entry_date,'%Y-%m-%d')='".date('Y-m-d')."'";
//echo $update_query;
$result_update=mysqli_query($conn,$update_query);
 $res=array("status"=> 0,"msg"=>"success");
}
else{
    $select_query_timeline= "SELECT dt_id FROM user_detection WHERE user_face_id='".$user_face_id."' AND user_last_detected_date >= DATE_SUB(NOW(),INTERVAL 15 MINUTE) ";
    //  echo $select_query_timeline;
    
    $result_timeline = mysqli_query($conn,$select_query_timeline);
if(mysqli_num_rows($result_timeline) == 0){
    $insert_query_again="INSERT INTO user_detection (user_face_id,user_gender, user_min_age, user_max_age , is_registered,user_entry_date ,user_last_detected_date) VALUES ('$user_face_id','$user_gender', '$user_min_age', '$user_max_age', 'Y', '$current_date','$current_date')";
    //echo $insert_query;
    $sql_again= mysqli_query($conn,$insert_query_again);
    $update_query_timeline="UPDATE user_detection SET user_last_detected_date='".date('Y-m-d H:i:s')."' WHERE face_id='".$user_face_id."' AND DATE_FORMAT(user_entry_date,'%Y-%m-%d')='".date('Y-m-d')."'";
//echo $update_query;
$result_update_timeline=mysqli_query($conn,$update_query_timeline);
$res=array("status"=> 0,"msg"=>"success");

}
else{
    $res=array("status"=> 1,"msg"=>"Already updated");

}
}
header('Content-type: application/json');
echo json_encode($res);

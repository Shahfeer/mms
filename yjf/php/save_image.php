<?php
//include("config.php");

$curr_date = date("d");
$curr_month = date("m");
$curr_year = date("Y");

$current_date = date("Y-m-d H:i:s");
$json = file_get_contents('php://input');
$data = json_decode($json);
$user_name=$data->user_name;
$user_image=$data->user_image;

if(!file_exists('../../mms/uploads/visitor_list_'.$curr_year.''.$curr_month.''.$curr_date)){
            $folder_name = '../../mms/uploads/visitor_list_'.$curr_year.''.$curr_month.''.$curr_date;
            mkdir($folder_name, 0777,true);

}

if(!file_exists('../../mms/uploads/visitor_list_runtime/'.$user_name.'.jpg')){
            $structure = '../../mms/uploads/visitor_list_runtime/'.$user_name;
           // mkdir($structure, 0777,true);

 
        // echo $user_image;
        $data = str_replace('data:image/png;base64,', '', $user_image);

        $data = str_replace(' ', '+', $data);

        $data = base64_decode($data);

        $file = $structure.'.jpg';

        $success = file_put_contents($file, $data);

}
        $res=array("status"=> 0,"msg"=>"Success");

 header('Content-type: application/json');
  echo json_encode($res);
 

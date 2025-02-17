<?php
session_start();
error_reporting(0);
include_once('../api/configuration.php');
extract($_REQUEST);

if (isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	switch ($action) {
			case 'getEMP' :
					getEMP($conn);
					break;
			case 'getRTHR' :
          getRTHR($conn);
					break;
			case 'getRTHRS' :
          getRTHRS($conn);
					break;
			case 'getMIS' :
          getMIS($conn);
					break;
			case 'getCamera' :
          getCamera($conn);
					break;

			default :
					getEMP($conn);
					break;
			// ...etc...
	}
}
//userlist start
function getEMP($conn)
{
		// Storing request (ie, get/post) global array to a variable
    $requestData = $_REQUEST;
    $columns = array(
        // datatable column index  => database column name
        0 => 'visitor_id',
        1 => 'aws_faceid',
        2 => 'customer_name',
        3 => 'customer_mobile',
        4 => 'customer_gender',
        5 => 'min_age',
        6 => 'max_age',
        7 => 'age_category',
        8 => 'interest_list_master'
    );

    // Initialize cURL session
    $curl = curl_init();
    $api_url='http://localhost:10017/report/userlist_report';
    $bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER =>  array(
          $bearer_token,
          'Content-Type: application/json'
        ),
    ));

    // Execute cURL request and get response
    $response = curl_exec($curl);
    // echo($response);

    // Close cURL session
    curl_close($curl);
   
    
    // Decode JSON response
    $responseData = json_decode($response, true); //storing response
   
    // Check if the response is valid and contains the expected data
    if ($responseData && $responseData['response_code'] == 1 && isset($responseData['data']['report'])) {
        $report = $responseData['data']['report'];

         // Search filter
        if (!empty($requestData['search']['value'])) {
          $searchValue = strtolower($requestData['search']['value']);
          $report = array_filter($report, function($row) use ($searchValue) {
              return strpos(strtolower($row['visitor_id']), $searchValue) !== false
                  || strpos(strtolower($row['aws_faceid']), $searchValue) !== false
                  || strpos(strtolower($row['customer_name']), $searchValue) !== false
                  || strpos(strtolower($row['customer_mobile']), $searchValue) !== false
                  || strpos(strtolower($row['customer_gender']), $searchValue) !== false
                  || strpos(strtolower($row['min_age']), $searchValue) !== false
                  || strpos(strtolower($row['max_age']), $searchValue) !== false
                  || strpos(strtolower($row['age_category']), $searchValue) !== false
                  || strpos(strtolower($row['interest_list_master']), $searchValue) !== false;
          });
      }

        // Prepare data for DataTables
        $data = array();
        $ii = $requestData['start'];

        foreach ($report as $row) {
            $ii++;
            $nestedData = array();
            $nestedData[] = $ii;
            $nestedData[] = $row["visitor_id"];
            $nestedData[] = $row["aws_faceid"];

            if ($row["customer_name"] == '') {
                if (file_exists('../uploads/register/' . $row["aws_faceid"] . '.jpg')) {
                    $nestedData[] = "<a href='uploads/register/" . $row["aws_faceid"] . ".jpg' data-lightbox='mygallery_$ii'><img src='uploads/register/" . $row["aws_faceid"] . ".jpg' style='width: 100px; height: auto; max-height: 100px;'></a><br>-- NO --";
                } else {
                    $nestedData[] = "<a href='../uploads/register/" . $row["aws_faceid"] . ".jpg' data-lightbox='mygallery_$ii'><img src='uploads/register/" . $row["aws_faceid"] . ".jpg' style='width: 100px; height: auto; max-height: 100px;'></a><br>-- NO --";
                }
            } else {
                if (file_exists('../uploads/register/' . $row["aws_faceid"] . '.jpg')) {
                    $nestedData[] = "<a href='uploads/register/" . $row["aws_faceid"] . ".jpg' data-lightbox='mygallery_$ii'><img src='uploads/register/" . $row["aws_faceid"] . ".jpg' style='width: 100px; height: auto; max-height: 100px;'></a><br>" . $row["customer_name"];
                } else {
                    $nestedData[] = "<a href='../uploads/register/" . $row["aws_faceid"] . ".jpg' data-lightbox='mygallery_$ii'><img src='uploads/register/" . $row["aws_faceid"] . ".jpg' style='width: 100px; height: auto; max-height: 100px;'></a><br>" . $row["customer_name"];
                }
            }

            $nestedData[] = $row["customer_mobile"];
            $nestedData[] = ($row["customer_gender"] == 'M') ? 'MALE' : (($row["customer_gender"] == 'F') ? 'FEMALE' : 'OTHERS');
            $nestedData[] = $row["min_age"] . " - " . $row["max_age"];
            $nestedData[] = $row["age_category"];
            $nestedData[] = $row["interest_list_master"];
            $data[] = $nestedData;
        }

        // Prepare the final JSON data for DataTables
        $json_data = array(
            "draw" => intval($requestData['draw']),   // Draw counter
            "recordsTotal" => intval(count($report)),  // Total number of records
            "recordsFiltered" => intval(count($report)), // Total number of filtered records
            "data" => $data   // Data array
        );

        // Return the JSON data
        echo json_encode($json_data);
    } elseif($responseData['response_status']==403) {
        // Handle error in API response
        echo json_encode(array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => 0,
            "recordsFiltered" => 403,
            "data" => array(),
            "error" => "Error in API response."
        ));
    }

}
//userlist end

//today report function start
function getRTHR($conn)
{
 
   session_start();
  $requestData = $_REQUEST;
  
  $api_url = 'http://localhost:10017/report/today_report';
  $bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];
  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
      $bearer_token,
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);
  
 $responseData = json_decode($response, true);  //storing response
  // echo('****'.$responseData['response_status'].'&&&&');

  // Initialize variables for DataTables
  $data = array();
  $ii = 0;
  $curntdt = date("Ymd");
  
  if ($responseData['response_code'] == 1 && !empty($responseData['data']['report'])) {
    $data = array();
    $ii = 0;
    foreach ($responseData['data']['report'] as $row) {  //seperate response
        $ii++;
        $nestedData = array();
        $nestedData['id'] = $ii;
        $nestedData['store_name'] = $row["store_name"] ?? '-- NO --';
        $nestedData['camera_position'] = $row["camera_position"] ?? '-- NO --';
        $nestedData['ip_address'] = $row["ip_address"] ?? '-- NO --';
        $nestedData['camera_details'] = $row["camera_details"] ?? '-- NO --';
        $nestedData['customer_name'] = $row["customer_name"] ?? '-- NO --';
        $nestedData['customer_mobile'] = $row["customer_mobile"] ?? '-';
        $nestedData['visitor_id'] = $row["visitor_id"];
        $nestedData['aws_faceid'] = $row["aws_faceid"];
        $nestedData['visitor_gender'] = $row["visitor_gender"];
        $nestedData['min_age'] = $row["min_age"];
        $nestedData['max_age'] = $row["max_age"];
        $nestedData['age_category'] = $row["age_category"];
        $nestedData['visitor_list_entdate'] = date("Y-m-d", strtotime($row['visitor_list_entdate'])); // Date
        $imgSrc = file_exists("../uploads/visitor_list_{$curntdt}/{$row['aws_faceid']}.jpg") ? 
                    "uploads/visitor_list_{$curntdt}/{$row['aws_faceid']}.jpg" : 
                    "https://yjtec.in/sms_portal/libraries/assets/images/people.png";
        $nestedData['img_path'] = $imgSrc;
        $data[] = $nestedData;
    }
    
    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval(count($responseData['data']['report'])),
        "recordsFiltered" => intval(count($responseData['data']['report'])),
        "data" => $data
    );

    echo json_encode($json_data);
} 
else if($responseData['response_status'] == 403){
  $json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => 0,
    "recordsFiltered" => 403,
    "data" => []
    // "error" => "403"
);
echo json_encode($json_data);
}
else{
    // If response_code is not 1 or report data is empty, return an empty dataset
    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    );

    echo json_encode($json_data);
}

}
//today report end


//today_summary report page start
function getRTHRS($conn) {
  // Ensure session is started if not already started
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  $requestData = $_REQUEST;
  $columns = array(
      // datatable column index  => database column name
      0 => 'serial_number',
      1 => 'visitor_list_entdate',
      2 => 'hour_time',
      3 => 'visitor_count',
      4 => 'male_cnt',
      5 => 'female_cnt',
      6 => 'others_cnt',
      7 => 'cnt_registration'
  );

  // Check if bearer token exists in session
  if (!isset($_SESSION['yjtsms_user_bearer_token'])) {
      http_response_code(401); // Unauthorized
      echo json_encode(array('error' => 'Bearer token not found in session'));
      return;
  }

  // Get API response
  $curl = curl_init();
  $api_url = 'http://localhost:10017/report/todaysummary_report';
  $bearer_token = 'Authorization: '.$_SESSION['yjtsms_user_bearer_token'];

  curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
          $bearer_token,
          'Content-Type: application/json'
      ),
  ));

  $response = curl_exec($curl);

  if ($response === false) {
      // Curl error occurred
      $error_msg = curl_error($curl);
      curl_close($curl);
      http_response_code(500); // Internal Server Error
      echo json_encode(array('error' => 'Curl error: ' . $error_msg));
      return;
  }

  curl_close($curl);

  // Decode JSON response
  $response_data = json_decode($response, true); // storing response
  
  // Check if decoding was successful and if report data exists
  if (!$response_data || !isset($response_data['data']['report'])) {
      // Return empty dataset
      $json_data = array(
          "draw" => intval($requestData['draw']),
          "recordsTotal" => 0,
          "recordsFiltered" => 403,
          "data" => []
      );

      // Output JSON response
      header('Content-Type: application/json');
      echo json_encode($json_data);
      return;
  }

  // Check for 403 response status
  if ($response_data['response_status'] == 403) {
      $json_data = array(
          "draw" => intval($requestData['draw']),
          "recordsFiltered" => 403,
          "data" => []
      );
      header('Content-Type: application/json');
      echo json_encode($json_data);
      return;
  }

  // Process report data into DataTables format
  $reports = $response_data['data']['report'];
  $data = array();
  $ii = 0; // Initialize index

  foreach ($reports as $report) {
      $ii++;
      $nestedData = array(
          'serial_number' => $ii, // Serial number
          'visitor_list_entdate' => date("Y-m-d", strtotime($report['visitor_list_entdate'])), // Date
          'hour_time' => $report['hour_time'] . " - " . ($report['hour_time'] + 1), // Hour time range
          'visitor_count' => $report['visitor_count'], // Visitor count
          'male_cnt' => $report['male_cnt'], // Male count
          'female_cnt' => $report['female_cnt'], // Female count
          'others_cnt' => $report['others_cnt'], // Others count
          'cnt_registration' => $report['cnt_registration'] // Registration count
      );

      $data[] = $nestedData;
  }

  // Prepare DataTables response
  $json_data = array(
      'draw' => intval($requestData['draw']), // Set appropriate draw value
      'recordsTotal' => count($data), // Total records in the data array
      'recordsFiltered' => count($data), // Since no filtering is applied initially
      'data' => $data // Data to be displayed
  );

  // Output JSON response
  header('Content-Type: application/json');
  echo json_encode($json_data);
}


//summary_report end

function getMIS($conn)
{
		// storing  request (ie, get/post) global array to a variable
    $requestData = $_REQUEST;
    $columns = array(
		// datatable column index  => database column name
        0 => 'st.store_name',
        1 => 'ct.camera_position',
        2 => 'cm.customer_name',
        3 => 'vl.aws_faceid',
        4 => 'vl.visitor_gender',
        5 => 'vl.min_age',
        6 => 'vl.max_age',
        7 => 'vl.age_category',
        8 => 'vl.visitor_list_entdate'
    );
    
		// getting total number records without any search
    $sql = "SELECT vl.store_id, st.store_name, vl.camera_id, ct.camera_position, ct.ip_address, ct.camera_details, cm.customer_id, cm.customer_name, vl.visitor_gender, vl.min_age, vl.max_age, vl.age_category, vl.aws_faceid, vl.visitor_id, vl.visitor_list_entdate ";
    $sql .= "FROM visitor_list vl
    left join store_details st on vl.store_id = st.store_id
    left join camera_details ct on vl.camera_id = ct.camera_id
    left join customer_management cm on cm.customer_id = vl.customer_id ";
    $sql .= "where vl.visitor_list_status = 'Y' and DATE(vl.visitor_list_entdate) = '".date("Y-m-d")."'";
    $query = mysqli_query($conn, $sql);
    $totalData = mysqli_num_rows($query);
    $totalFiltered = $totalData; // when there is no search parameter then total number rows = total number filtered rows.

    $sql = "SELECT vl.store_id, st.store_name, vl.camera_id, ct.camera_position, ct.ip_address, ct.camera_details, cm.customer_id, cm.customer_name, vl.visitor_gender, vl.min_age, vl.max_age, vl.age_category, vl.aws_faceid, vl.visitor_id, vl.visitor_list_entdate 
    FROM visitor_list vl
    left join store_details st on vl.store_id = st.store_id
    left join camera_details ct on vl.camera_id = ct.camera_id
    left join customer_management cm on cm.customer_id = vl.customer_id
    where vl.visitor_list_status = 'Y' and DATE(vl.visitor_list_entdate) = '".date("Y-m-d")."' and 1=1 ";
   if (!empty($requestData['search']['value'])) { // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $searchValue = $requestData['search']['value'];
    $sql .= " AND ( st.store_name LIKE '%" . $searchValue . "%' ";
    $sql .= " OR ct.camera_position LIKE '%" . $searchValue . "%' ";
    $sql .= " OR cm.customer_name LIKE '%" . $searchValue . "%' ";
    $sql .= " OR vl.aws_faceid LIKE '%" . $searchValue . "%' ";
    $sql .= " OR vl.visitor_gender LIKE '%" . $searchValue . "%' ";
    $sql .= " OR CONCAT(vl.min_age, ' ', vl.max_age) LIKE '%" . $searchValue . "%' ";
    $sql .= " OR CONCAT(vl.min_age, ' ', vl.max_age, ' ', vl.visitor_list_entdate) LIKE '%" . $searchValue . "%' ";
    $sql .= " OR vl.age_category LIKE '%" . $searchValue . "%' ";
    $sql .= " OR vl.visitor_list_entdate LIKE '%" . $searchValue . "%' )";
}

    $query = mysqli_query($conn, $sql);

    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
		if($requestData['length'] == -1) {
			$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " ";
		} else {
    	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
		}

    /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length. */
    $query = mysqli_query($conn, $sql) or die("Mysql Mysql Error in getting : get products");
    $data = array();
    while ($row = mysqli_fetch_array($query)) {  // preparing an array
        $nestedData = array();
        $nestedData[] = $row["store_name"];
        $nestedData[] = $row["camera_position"]." - ".$row["ip_address"]."<br>".$row["camera_details"];
        $nestedData[] = $row["customer_name"];
        $nestedData[] = $row["aws_faceid"];
        switch ($row["visitor_gender"]) {
          case 'M':
            $nestedData[] = 'MALE';
            break;
          
          case 'F':
            $nestedData[] = 'FEMALE';
            break;
          
          default:
            $nestedData[] = 'OTHERS';
            break;
        }
        
        $nestedData[] = $row["min_age"]." - ".$row["max_age"];
        $nestedData[] = $row["age_category"];
        $nestedData[] = $row["visitor_list_entdate"];
        $data[] = $nestedData;
    }

    $json_data = array(
        "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
        "recordsTotal" => intval($totalData),  // total number of records
        "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data" => $data   // total data array
    );

    echo json_encode($json_data); // send data as json format
}

function getCamera($conn)
{
  // storing  request (ie, get/post) global array to a variable
  $requestData = $_REQUEST;
  $columns = array(
  // datatable column index  => database column name
      0 => 'sd.store_name',
      1 => 'cd.camera_position',
      2 => 'cd.ip_address',
      3 => 'cd.camera_details',
      4 => 'cd.video_url',
      5 => 'cd.start_stop_action',
      6 => 'cd.camera_status',
      7 => 'cd.camera_entry_date'
  );
  
  // getting total number records without any search
  $sql = "SELECT sd.store_name, cd.camera_position, cd.ip_address, cd.camera_details, cd.video_url, cd.start_stop_action, cd.camera_status, cd.camera_entry_date FROM camera_details cd left join store_details sd on cd.store_id = sd.store_id";

  $query = mysqli_query($conn, $sql);
  $totalData = mysqli_num_rows($query);
  $totalFiltered = $totalData; // when there is no search parameter then total number rows = total number filtered rows.

  $sql = "SELECT sd.store_name, cd.camera_position, cd.ip_address, cd.camera_details, cd.video_url, cd.start_stop_action, cd.camera_status, cd.camera_entry_date FROM camera_details cd left join store_details sd on cd.store_id = sd.store_id where 1=1 ";
  if (!empty($requestData['search']['value'])) { // if there is a search parameter, $requestData['search']['value'] contains search parameter
      $sql .= " AND ( sd.store_name LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.camera_position LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.ip_address LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.camera_details LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.video_url LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.start_stop_action LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.camera_status LIKE '%" . $requestData['search']['value'] . "%' ";
      $sql .= " OR cd.camera_entry_date LIKE '%" . $requestData['search']['value'] . "%' )";
  }
  $query = mysqli_query($conn, $sql);

  $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
  if($requestData['length'] == -1) {
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " ";
  } else {
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
  }

  /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length. */
  $query = mysqli_query($conn, $sql) or die("Mysql Mysql Error in getting : get products");
  $data = array();
  $i = 0;
  while ($row = mysqli_fetch_array($query)) {  // preparing an array
      $i++;
      $nestedData = array();
      $nestedData[] = $row["store_name"];
      $nestedData[] = $row["camera_position"];
      $nestedData[] = $row["ip_address"];
      $nestedData[] = $row["camera_details"];
      $nestedData[] = $row["video_url"];
      switch ($row["start_stop_action"]) {
        case 'Y':
          $nestedData[] = 'STARTED <br> <input type="radio" name="contact_status_'.$i.'[]" id="contact_status_'.$i.'" tabindex="1" value="Y" checked="checked"> Started <input type="radio" name="contact_status_'.$i.'[]" id="contact_status_'.$i.'" tabindex="1" value="N"> Stop';
          break;
        
        default:
          $nestedData[] = 'STOPPED <br> <input type="radio" name="contact_status_'.$i.'[]" id="contact_status_'.$i.'" tabindex="1" value="Y"> Start <input type="radio" name="contact_status_'.$i.'[]" id="contact_status_'.$i.'" tabindex="1" value="N" checked="checked"> Stopped';
          break;
      }
      switch ($row["camera_status"]) {
        case 'Y':
          $nestedData[] = 'ACTIVE';
          break;
        
        default:
          $nestedData[] = 'INACTIVE';
          break;
      }
      $nestedData[] = $row["camera_entry_date"];
      $data[] = $nestedData;
  }

  $json_data = array(
      "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
      "recordsTotal" => intval($totalData),  // total number of records
      "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
      "data" => $data   // total data array
  );

  echo json_encode($json_data); // send data as json format
}




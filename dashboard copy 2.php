<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);

/* if($_SESSION['yjtmms_user_id'] == ""){ ?>
		<script>window.location="index";</script>
<?php exit();
} */

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Dashboard Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));
$today_date = date("Y-m-d");
$yesterday_date = date('Y-m-d', strtotime("-1 days"));
$yesterday_date_db = date('dmY', strtotime("-1 days"));

$curl = curl_init();
$bearer_token = 'Authorization: '.$_SESSION['yjtsms_user_bearer_token'].'';
curl_setopt_array($curl, array(
	CURLOPT_URL => $api_url.'/user/dashboard_change',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	// CURLOPT_POSTFIELDS =>
	CURLOPT_HTTPHEADER => array(
    $bearer_token,
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
// echo $response;
curl_close($curl);
// print_r($response);
site_log_generate("Dashboard Page : User : ".$_SESSION['yjtmms_user_name']." executed the Query ($response) on ".date("Y-m-d H:i:s"));
$res_dashboard = json_decode($response);
$cnt_visitor = 0; $cnt_male = 0; $cnt_female = 0; $cnt_unique_male = 0; $cnt_unique_female = 0; $cnt_others = 0; $cnt_unique_others = 0; $cnt_registration = 0;

if ($res_dashboard->num_of_rows > 0) {
	for($dash_indicator = 0; $dash_indicator < $res_dashboard->num_of_rows; $dash_indicator++){
 $cnt_visitor_id = $res_dashboard->result[$dash_indicator]->cnt_visitor_id; 
		$cnt_visitor        = $res_dashboard->result[$dash_indicator]->cnt_visitor;
		$cnt_male           = $res_dashboard->result[$dash_indicator]->cnt_male;
		$cnt_unique_male    = $res_dashboard->result[$dash_indicator]->cnt_unique_male;
		$cnt_female         = $res_dashboard->result[$dash_indicator]->cnt_female;
		$cnt_unique_female  = $res_dashboard->result[$dash_indicator]->cnt_unique_female;
    $cnt_others         = $res_dashboard->result[$dash_indicator]->cnt_others;
    $cnt_unique_others  = $res_dashboard->result[$dash_indicator]->cnt_unique_others;
    $cnt_adult          = $res_dashboard->result[$dash_indicator]->cnt_adult;
    $cnt_kids           = $res_dashboard->result[$dash_indicator]->cnt_kids;
		$cnt_registration   = $res_dashboard->result[$dash_indicator]->cnt_registration;
  }
}

// echo 'select hour(visitor_list_entdate) hour_time, count(visitor_list_id) visitor_count from visitor_list where (date(visitor_list_entdate) BETWEEN \''.$today_date.'\' AND \''.$today_date.'\') group by hour(visitor_list_entdate) order by visitor_list_entdate asc';
$curl1 = curl_init();
curl_setopt_array($curl1, array(
	CURLOPT_URL => $api_url.'/user/dashboard_change',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	// CURLOPT_POSTFIELDS =>'',
	CURLOPT_HTTPHEADER => array(
    $bearer_token,
    'Content-Type: application/json'
  ),
));
$response1 = curl_exec($curl1);
curl_close($curl1);

site_log_generate("Dashboard Page : User : ".$_SESSION['yjtmms_user_name']." executed the Query ($response1) on ".date("Y-m-d H:i:s"));
$res_dashboard1 = json_decode($response1);
$visitor_count_graph = '';

if ($res_dashboard1->num_of_rows > 0) {
	for($dash_indicator1 = 0; $dash_indicator1 < $res_dashboard1->num_of_rows; $dash_indicator1++){ 
		$visitor_count_graph    .= "{ x: ".$res_dashboard1->result[$dash_indicator1]->hour_time.", y: ".$res_dashboard1->result[$dash_indicator1]->visitor_count." },";
  }
}
$visitor_count_graph = rtrim($visitor_count_graph, ", ");


/* $curl2 = curl_init();
curl_setopt_array($curl2, array(
	CURLOPT_URL => $api_url.'/select_query',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
 		"query" : "select hour(visitor_list_entdate) hour_time, count(visitor_list_id) visitor_count from `visitor_list_\''.$yesterday_date_db.'\'` where (date(visitor_list_entdate) BETWEEN \''.$yesterday_date.'\' AND \''.$yesterday_date.'\') group by hour(visitor_list_entdate) order by visitor_list_entdate asc"
	}',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	),
));
$response2 = curl_exec($curl2);
curl_close($curl2);

site_log_generate("Dashboard Page : User : ".$_SESSION['yjtmms_user_name']." executed the Query ($response2) on ".date("Y-m-d H:i:s"));
$res_dashboard2 = json_decode($response2);
$yesterday_visitor_count_graph = '';

if ($res_dashboard2->num_of_rows > 0) {
	for($dash_indicator2 = 0; $dash_indicator2 < $res_dashboard2->num_of_rows; $dash_indicator2++){ 
		$yesterday_visitor_count_graph    .= "{ x: ".$res_dashboard2->result[$dash_indicator2]->hour_time.", y: ".$res_dashboard2->result[$dash_indicator2]->visitor_count." },";
  }
}
$yesterday_visitor_count_graph = rtrim($yesterday_visitor_count_graph, ", "); */

$create_query = 'select hour(visitor_list_entdate) hour_time, count(visitor_list_id) visitor_count from `visitor_list_'.$yesterday_date_db.'` where (date(visitor_list_entdate) BETWEEN \''.$yesterday_date.'\' AND \''.$yesterday_date.'\') group by hour(visitor_list_entdate) order by visitor_list_entdate asc';
$yesterday_visitor_count_graph = '';

$result = $conn1->query($create_query);
if ($result->num_rows>0){
	while ($response = $result->fetch_object()) {
    $yesterday_visitor_count_graph    .= "{ x: ".$response->hour_time.", y: ".$response->visitor_count." },";
  }
}
$yesterday_visitor_count_graph = rtrim($yesterday_visitor_count_graph, ", ");
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
<meta http-equiv="refresh" content="120">
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard : <?=$site_title?></title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
      .text-right {
        text-align: right !important;
      }
    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <? include("libraries/site_menu.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <? include("libraries/site_header.php"); ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Congratulations Super Admin! 🎉</h5>
                          <p class="mb-4">
                            Welcome to Mall Monitoring System 
                          </p>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                  <div class="card h-100">
                    <div class="card-body">

                    <div class="row" style="width: 100%; margin:0;">
                        <div class="col-6">
                          <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Total Footfalls</h5>
                            <small class="text-muted"></small>
                          </div>
                        </div>
                        <div class="col-6" style="padding: 0;">
                          <h2 class="mb-2 text-right"><?=indian_money_format($cnt_visitor)?></h2>
                        </div>
                      </div>

                      <div style="clear: both;"></div>
                      <div class="row" style="width: 100%; margin:0;">
                        <div class="col-6">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2">Total Visitors</h5>
                          <small class="text-muted"></small>
                        </div>
                        </div>
                        <div class="col-6" style="padding: 0;">
                          <h2 class="mb-2 text-right"><?=indian_money_format($cnt_visitor_id)?></h2>
                        </div>
                      </div>

                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-primary"
                              ><i class="bx bx-male"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Male</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
				<small class="fw-semibold"><span title="Footfalls count"><?=indian_money_format($cnt_male)?></span> / <span title="Visitors count"><?=indian_money_format($cnt_unique_male)?></span></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-success"><i class="bx bx-female"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Female</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
				<small class="fw-semibold"><span title="Footfalls count"><?=indian_money_format($cnt_female)?></span> / <span title="Visitors count"><?=indian_money_format($cnt_unique_female)?></span></small>
                            </div>
                          </div>
                        </li>
 <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-danger"><i class="bx bx-body"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Others</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
				<small class="fw-semibold"><span title="Footfalls count"><?=indian_money_format($cnt_others)?></span> / <span title="Visitors count"><?=indian_money_format($cnt_unique_others)?></span></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-info"><i class="bx bx-group"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Footfalls</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?=indian_money_format($cnt_visitor)?></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-secondary"
                              ><i class="bx bx-user-check"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Registration</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?=indian_money_format($cnt_registration)?></small>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

                <!-- Expense Overview -->
                <div class="col-md-6 col-lg-6 order-1 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Current Visitors</h5>
                        <small class="text-muted"></small>
                      </div>
                    </div>
                    <div class="card-body px-0">
                      <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                          <div id="incomeChart"></div>
                          <div class="d-flex justify-content-center pt-4 gap-2">
                              <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Expense Overview -->
              </div>
 <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Age Category</h5>
                        <small class="text-muted"></small>
                      </div>
                    </div>
                    <div class="card-body">

                    <div class="d-flex align-items-end mb-3" style="float: right;">
                      <div class="d-flex flex-column align-items-end gap-1">
                        <h2 class="mb-2"><?=indian_money_format($cnt_visitor)?></h2>
                      </div>
                    </div>

                    <div style="clear: both;"></div>
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-primary"
                              ><i class="bx bx-male"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Kids</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?=indian_money_format($cnt_kids)?></small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-5">
                            <span class="avatar-initial rounded btn-xl bg-label-success"><i class="bx bxs-group"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Adult</h6>
                              <small class="text-muted"></small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold"><?=indian_money_format($cnt_adult)?></small>
                            </div>
                          </div>
                        </li>
 </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

            </div>
            <!-- / Content -->

            <!-- Footer -->
            <? include("libraries/site_footer.php"); ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

    <script>
      window.onload = function () {
        //Better to construct options first and then pass it as a parameter
        var options = {
          title: {
            text: ""
          },
          animationEnabled: true,
          exportEnabled: true,
	  data: [
          {
            type: "spline", //change it to line, area, column, pie, etc
            showInLegend: true,
            yValueFormatString: "##.00mn",
            name: "Today",
            dataPoints: [
              <?=$visitor_count_graph?>
            ]
          },
          {
            type: "spline", //change it to line, area, column, pie, etc
            showInLegend: true,
            yValueFormatString: "##.00mn",
            name: "Yesterday",
            dataPoints: [
              <?=$yesterday_visitor_count_graph?>
            ]
          }
          ]
        };
        $("#chartContainer").CanvasJSChart(options);
      }
    </script>
  </body>
</html>


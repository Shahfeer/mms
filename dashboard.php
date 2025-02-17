<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once('api/configuration.php');
extract($_REQUEST);

if ($_SESSION['yjtsms_user_id'] == "") { ?>
    <script>window.location = "index";</script>
    <?php exit();
  }
// if (empty($_SESSION['yjtmms_user_id'])) {
//     echo "<script>window.location='index';</script>";
//     exit();
// }

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
// site_log_generate("Dashboard Page: User: " . $_SESSION['yjtmms_user_name'] . " accessed the page on " . date("Y-m-d H:i:s"));

$today_date = date("Y-m-d");
$yesterday_date = date('Y-m-d', strtotime("-1 days"));
$yesterday_date_db = date('dmY', strtotime("-1 days"));

$bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url . '/user/dashboard_change',
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
// echo($response);
curl_close($curl);

if ($response === false) {
    echo '<script>window.location = "index";</script>';
}

// site_log_generate("Dashboard Page: User: " . $_SESSION['yjtmms_user_name'] . " executed the Query ($response) on " . date("Y-m-d H:i:s"));

$dashboard_data = json_decode($response);
// print_r($dashboard_data);
if ($response == '') { ?>
    <script>window.location = "logout"</script>
  <? }else if ($dashboard_data->response_status == 403) { ?>
    <script>window.location = "logout"</script>
  <? }

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding JSON response: " . json_last_error_msg());
}

$cnt_visitor_id = $cnt_visitor = $cnt_male = $cnt_unique_male = 0;
$cnt_female = $cnt_unique_female = $cnt_others = $cnt_unique_others = 0;
$cnt_kids = $cnt_adult = $cnt_registration = 0;

if (!empty($dashboard_data->data->report)) {
  
    foreach ($dashboard_data->data->report as $report) {
    
        $cnt_visitor_id = $report->cnt_visitor_id;
        // echo($cnt_visitor_id);
        $cnt_visitor = $report->cnt_visitor;
        // echo($cnt_visitor);_co
        $cnt_male = $report->cnt_male;
        // echo($cnt_male);
        $cnt_unique_male = $report->cnt_unique_male;
        // echo($cnt_unique_male);
        $cnt_female = $report->cnt_female;
        $cnt_unique_female = $report->cnt_unique_female;
        $cnt_others = $report->cnt_others;
        $cnt_unique_others = $report->cnt_unique_others;
        $cnt_kids = $report->cnt_kids;
        $cnt_adult = $report->cnt_adult;
        $cnt_registration = $report->cnt_registration;
    }
}

// $visitor_count_graph = '';
// print_r($dashboard_data->data->graph);
// if (!empty($dashboard_data->data->today_graph)) {
//   // echo("hi");
//     foreach ($dashboard_data->data->graph as $graph_data) {
//         $visitor_count_graph .= "{ x: " . $graph_data->hour_time . ", y: " . $graph_data->visitor_count . " },";
//     }
// }



// Print debug information to inspect the structure
// print_r($dashboard_data->data->today_graph);

// Initialize an empty string to store graph data
$today_graph = '';

// Check if today_graph data exists and loop through it
if (!empty($dashboard_data->data->today_graph)) {
    foreach ($dashboard_data->data->today_graph as $graph_data) {
        // Append each data point in the format expected by CanvasJS
        $today_graph .= "{ x: " . $graph_data->hour_time . ", y: " . $graph_data->visitor_count . " },";
    }
}

// Remove trailing comma and space
$today_graph = rtrim($today_graph, ", ");




// Initialize an empty string to store graph data
$yesterday_visitor_count_graph = '';

// Check if yesterday_graph data exists and loop through it
if (!empty($dashboard_data->data->yesterday_graph)) {
    foreach ($dashboard_data->data->yesterday_graph as $graph_data) {
        // Append each data point in the format expected by CanvasJS
        $yesterday_visitor_count_graph .= "{ x: " . $graph_data->hour_time . ", y: " . $graph_data->visitor_count . " },";
    }
}

// Remove trailing comma and space
$yesterday_visitor_count_graph = rtrim($yesterday_visitor_count_graph, ", ");


?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
    <meta http-equiv="refresh" content="120">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard : <?=$site_title?></title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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
            <?php include("libraries/site_menu.php"); ?>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include("libraries/site_header.php"); ?>
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
                                                <p class="mb-4">Welcome to Mall Monitoring System</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-4">
                                                <img src="assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
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
                                                    <span class="avatar-initial rounded btn-xl bg-label-primary"><i class="bx bx-male"></i></span>
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
                                                    <span class="avatar-initial rounded btn-xl bg-label-primary"><i class="bx bx-male"></i></span>
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
                    </div>
                    <!-- / Content -->
                    <!-- Footer -->
                    <?php include("libraries/site_footer.php"); ?>
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
            var options = {
                title: {
                    text: ""
                },
                animationEnabled: true,
                exportEnabled: true,
                data: [
                    {
                        type: "spline",
                        showInLegend: true,
                        yValueFormatString: "##.00mn",
                        name: "Today",
                        dataPoints: [
                            <?=$today_graph?>
                        ]
                    },
                    {
                        type: "spline",
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

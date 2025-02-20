<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);

 if($_SESSION['yjtsms_user_id'] == ""){ ?>
<script>
window.location = "index";
</script>
<?php exit();
} 

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("LMTD Report Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));

$lastmonth = date('Y-m', strtotime(date('Y-m-d'). ' -1 months'));
$lastmonthdt = date('d', strtotime(date('Y-m-d'). ' -1 days'));
$thismonth_startdate 	= date($lastmonth."-01");
$thismonth_yesterday	= date($lastmonth.'-'.$lastmonthdt);
// echo "==".$lastmonth."==".$thismonth_startdate."==".$thismonth_yesterday."==";

function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {
	$dates = array();
	$current = strtotime($first);
	$last = strtotime($last);
	while( $current <= $last ) {

			$dates[] = date($output_format, $current);
			$current = strtotime($step, $current);
	}
	return $dates;
}
$date = date_range($thismonth_startdate, $thismonth_yesterday);

$tblnames 							= []; 
$entry_date_array				= [];
$male_cnt_array   			= []; 
$female_cnt_array 			= []; 
$others_cnt_array 			= []; 
$visitor_cnt_array 			= []; 
$registration_cnt_array = []; 
$lable_value = '';
// $create_query = '';

//send api request
$curl = curl_init();
$bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];
$api_url='http://localhost:10017/report/lmtd_report';

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

$response = curl_exec($curl); //save response
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// echo $response;
if ($response === false || $http_code == 403) {
	echo '<script>window.location = "logout"</script>';
}
// $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
// if ($http_code == 403) {
// 	echo '<script>window.location = "logout"</script>';
// 	exit();
// }


$data = json_decode($response, true); //seperate curl response and storing in array
// print_r($response);

if ($response == '') { ?>
	<script>window.location = "logout"</script>
<? }else if ($data->response_status == 403) { ?>
	<script>window.location = "logout"</script>
<? }



if (isset($data['lmtd_result']) && is_array($data['lmtd_result'])) {
	// Initialize arrays to store separated response data
	$entry_date_array = [];
	$visitor_cnt_array = [];
	$male_cnt_array = [];
	$female_cnt_array = [];
	$others_cnt_array = [];
	$registration_cnt_array = [];

	// Iterate through each item in the 'mtd_result' array
	foreach ($data['lmtd_result'] as $report) {
			// Separate response data
			$entry_date_array[] = date('Y-m-d', strtotime($report['visitor_list_entdate']));
			$visitor_cnt_array[] = $report['cnt_visitor'];
			$male_cnt_array[] = $report['cnt_male'];
			$female_cnt_array[] = $report['cnt_female'];
			$others_cnt_array[] = $report['cnt_others'];
			$registration_cnt_array[] = $report['cnt_registration'];
	}
} else {
	// Initialize empty arrays if 'mtd_result' is not set or is not an array
	$entry_date_array = [];
	$visitor_cnt_array = [];
	$male_cnt_array = [];
	$female_cnt_array = [];
	$others_cnt_array = [];
	$registration_cnt_array = [];
}


$male_cnt = rtrim($male_cnt, ', ');
$female_cnt = rtrim($female_cnt, ', ');
$others_cnt = rtrim($others_cnt, ', ');
$visitor_cnt = rtrim($visitor_cnt, ', ');
$registration_cnt = rtrim($registration_cnt, ', ');


?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
	data-template="vertical-menu-template-free">

<head>
	<meta http-equiv="refresh" content="120">
	<meta charset="utf-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	<title>LMTD Report : <?=$site_title?></title>
	<meta name="description" content="" />

	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link
		href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
		rel="stylesheet" />

	<!-- Icons. Uncomment required icon fonts -->
	<link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

	<!-- Core CSS -->
	<link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
	<link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
	<link rel="stylesheet" href="assets/css/demo.css" />

	<!-- Vendors CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

	<!-- Page CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet" />

	<link rel="stylesheet" href="assets/css/bar_chart_app.css">
	<script src="assets/js/bar_chart_app.js"></script>

	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>

	<script src="assets/js/config.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
	<style>
	.container {
		width: 100% !important;
	}
	.dropdown-item {
			line-height: 1.54;
    display: block;
    width: 100%;
    padding: 0.532rem 1.25rem;
     margin-top: 0.5rem;
    clear: both;
    font-weight: 400;
    color: #697a8d;
    text-align: inherit;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
		line-height: 1.54;
		/* border-radius: 0.375rem; */
		
}
.dropdown-menu.dropdown-menu-end.show {
    --bs-dropdown-spacer: 0.95rem;
		border-radius: 0.375rem;
}

        @media (min-width: 1200px) {
            .navbar-expand-xl .navbar-nav .dropdown-menu {
                position: absolute;
            }
        }
.navbar .avatar {
    margin-bottom: -15px;
    /* margin-top: -15px; */
}
.fw-semibold {
    font-weight: 600 !important;
}
.avatar img {
            width: 100%;
            height: 100%;
            /* margin-top: 10px; Adjust this value to move the image down */
        }
				.dropdown-menu .avatar.avatar img {
            margin-top: 10px; /* Adjust this value to move the image down */
        }
				.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #aaa;
    border-radius: 3px;
    padding: 5px;
    background-color: transparent;
    margin-left: 3px;
		margin-bottom:8px;
}
#employee-grid tbody tr:hover {
        background-color: #f5f5f5; /* Light gray background color on hover */
        cursor: pointer; /* Change cursor to pointer */
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
						<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> LMTD Report</h4>

						<div class="row">
							<div class="col-md-12">
								<div class="card mb-4">
									<!-- Account -->

									<div class="card-body">
										<form action="#" method="post" class="j-pro" id="j-pro" novalidate=""
											style="border:0px !important;">
											<!-- Responsive Table -->
											<div class="table-responsive dt-responsive">

												<div class="container">
													<div style="text-align: center; font-size: 1.4rem; font-weight: bold;">LMTD Report Duration : <?=$thismonth_startdate?> - <?=$thismonth_yesterday?></div>
													<div style="height: 400px !important; width: 100% !important;">
														<script>
																const labels = <?= json_encode($entry_date_array) ?>;
															const maleData = <?= json_encode($male_cnt_array) ?>;
															const femaleData = <?= json_encode($female_cnt_array) ?>;
															const othersData = <?= json_encode($others_cnt_array) ?>;
															const visitorData = <?= json_encode($visitor_cnt_array) ?>;
															const registrationData = <?= json_encode($registration_cnt_array) ?>;
															var data = {
																labels: labels,
																datasets: [{
																	label: "Male",
																	backgroundColor: window.theme.primary,
																	borderColor: window.theme.primary,
																	hoverBackgroundColor: window.theme.primary,
																	hoverBorderColor: window.theme.primary,
																	data: maleData,
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Female",
																	backgroundColor: "#FFC75F",
																	borderColor: "#FFC75F",
																	hoverBackgroundColor: "#FFC75F",
																	hoverBorderColor: "#FFC75F",
																	data:femaleData,
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Others",
																	backgroundColor: "#88933a",
																	borderColor: "#88933a",
																	hoverBackgroundColor: "#88933a",
																	hoverBorderColor: "#88933a",
																	data: othersData,
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Footfalls",
																	backgroundColor: "#C34A36",
																	borderColor: "#C34A36",
																	hoverBackgroundColor: "#C34A36",
																	hoverBorderColor: "#C34A36",
																	data:visitorData,
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Registration",
																	backgroundColor: "#845EC2",
																	borderColor: "#845EC2",
																	hoverBackgroundColor: "#845EC2",
																	hoverBorderColor: "#845EC2",
																	data:registrationData,
																	barPercentage: .75,
																	categoryPercentage: .5
																}]
															};
														</script>

														<canvas id="chartjs-bar"></canvas>
													</div>
												</div>

											</div>
											<!--/ Responsive Table -->

										</form>
									</div>


									<div class="mb-5">
											<form action="#" method="post" class="j-pro" id="j-pro1" novalidate="" style="border:0px !important;">
                        <!-- Responsive Table -->
                          <div class="table-responsive dt-responsive">

                            <div class="container">
                              <table id="employee-grid" class="table table-striped table-bordered" style="width:100%; text-align: center;">
                                  <thead>
                                  <tr>
																		<th>#</th>
																		<th>Date</th>
																		<th>Total Footfall Count</th>
																		<th>Total Male Count</th>
																		<th>Total Female Count</th>
																		<th>Total Others Count</th>
																		<th>Total Registered Count</th>
                                  </tr>
                                  </thead>
																	<? $ii = 0;
																	if(count($visitor_cnt_array) > 0) { 
																		for($i = 0; $i < count($visitor_cnt_array); $i++) { $ii++; ?>
																			<tr>
																				<td><?=$ii?></td>
																				<td><?=$entry_date_array[$i]?></td>
																				<td><?=$visitor_cnt_array[$i]?></td>
																				<td><?=$male_cnt_array[$i]?></td>
																				<td><?=$female_cnt_array[$i]?></td>
																				<td><?=$others_cnt_array[$i]?></td>
																				<td><?=$registration_cnt_array[$i]?></td>
																			</tr>
																		<? } 
																	} else { ?>
																	<tr>
																		<td colspan="7">-- No Records Found --</td>
																	</tr>
																<? } ?>
                              </table>
                            </div>

                          </div>
                        <!--/ Responsive Table -->
                      </form>
									</div>

									<!-- /Account -->
								</div>

							</div>
						</div>
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

	<!-- build:js assets/vendor/js/core.js -->
	<script src="assets/vendor/libs/jquery/jquery.js"></script>
	<script src="assets/vendor/libs/popper/popper.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
	<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

	<script src="assets/vendor/js/menu.js"></script>
	<!-- endbuild -->

	<!-- Main JS -->
	<script src="assets/js/main.js"></script>

	<!-- Custom js -->
	<script>
	document.addEventListener("DOMContentLoaded", function() {
		var ctx = document.getElementById("chartjs-bar");
		// debugger;
		var myChart = new Chart(ctx, {
			// type: 'horizontalBar',
			type: 'bar',
			data: data,
			options: {
				"hover": {
					"animationDuration": 0
				},
				responsive: true,
				maintainAspectRatio: false,
				"animation": {
					"duration": 1,
					"onComplete": function() {
						var chartInstance = this.chart,
							ctx = chartInstance.ctx;

						ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
						ctx.textAlign = 'center';
						ctx.textBaseline = 'bottom';

						this.data.datasets.forEach(function(dataset, i) {
							var meta = chartInstance.controller.getDatasetMeta(i);
							meta.data.forEach(function(bar, index) {
								var data = dataset.data[index];
								ctx.fillText(data, bar._model.x, bar._model.y);
							});
						});
					}
				},
				legend: {
					"display": true
				},
				tooltips: {
					"enabled": true
				},
				scales: {
					xAxes: [{
						stacked: false,
						gridLines: {
							color: "transparent"
						}
					}],
					yAxes: [{
						gridLines: {
							display: false
						},
						stacked: false,
					}]
				}
			}
		});
	});
	</script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

	 <script>
        $(document).ready(function() {
            $('#employee-grid').DataTable({
                "paging": true,  // Enable pagination
                "lengthMenu": [5, 10, 25, 50, 100],  // Options for the number of rows per page
                "pageLength": 10,  // Default number of rows per page
								"hover":true
            });
        });
    </script>
</body>
</html>


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
site_log_generate("LTD Report Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));

$thismonth_startdate 	= date('Y-m-d', strtotime("-1 days"));
$thismonth_yesterday	= date('Y-m-d', strtotime("-1 days"));

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
// echo "<pre>";
// print_r($date);
// exit;

$tblnames 							= []; 
$entry_date_array				= [];
$male_cnt_array   			= []; 
$female_cnt_array 			= []; 
$others_cnt_array 			= []; 
$visitor_cnt_array 			= []; 
$registration_cnt_array = []; 
$lable_value = '';
$create_query = '';
for($ij = 0; $ij < count($date); $ij++) {

	// echo "<br>**".$table_name = "visitor_list_".$date[$ij];
	$table_name = "visitor_list_".date("dmY", strtotime($date[$ij]));
	$sql_query = "SELECT * FROM information_schema.tables WHERE table_schema = '".$backup_dbname."' AND table_name = '".$table_name."' LIMIT 1";

	$result = $conn->query($sql_query);
	if ($result->num_rows>0){
		$tblnames[] = $table_name;
		$this_date 	= date("Y-m-d", strtotime($date[$ij]));

		$create_query .= "SELECT distinct date(visitor_list_entdate) visitor_list_entdate, count(visitor_list_id) cnt_visitor, (select count(visitor_list_id) from ".$table_name." where (date(visitor_list_entdate) BETWEEN '".$this_date."' AND '".$this_date."') and visitor_gender = 'M') cnt_male, (select count(visitor_list_id) from ".$table_name." where (date(visitor_list_entdate) BETWEEN '".$this_date."' AND '".$this_date."') and visitor_gender = 'F') cnt_female, (select count(visitor_list_id) from ".$table_name." where (date(visitor_list_entdate) BETWEEN '".$this_date."' AND '".$this_date."') and visitor_gender = 'O') cnt_others, (select count(customer_id) from ".$dbname.".customer_management where (date(cus_mgt_entry_date) BETWEEN '".$this_date."' AND '".$this_date."')) cnt_registration FROM ".$table_name." where (date(visitor_list_entdate) BETWEEN '".$this_date."' AND '".$this_date."') union ";
		$lable_value .= '"'.$this_date.'", ';
	}

	/* $curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $api_url.'/select_query',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
			"query" : '.$sql_query.'
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	print_r($response);

	$trans1 = json_decode($response);
	site_log_generate("LTD Report Page : User : ".$_SESSION['yjtsms_user_name']." executed the Query ($response) on ".date("Y-m-d H:i:s"));

	$transactions = $trans1->num_of_rows;
	if($trans1->num_of_rows > 0) {
		$tblnames[] = $table_name;
	} */
	
	$table_name = '';
}

$create_query = rtrim($create_query, ' union ');
$create_query .= " order by visitor_list_entdate ";
$lable_value = rtrim($lable_value, ', ');
// echo $create_query;

$male_cnt = 0; $female_cnt = 0; $others_cnt = 0; $visitor_cnt = 0; $registration_cnt = 0; 
$result = $conn1->query($create_query);
if ($result->num_rows>0){
		while ($response = $result->fetch_object()) {
				$male_cnt 				.= $response->cnt_male.", ";
				$female_cnt 			.= $response->cnt_female.", ";
				$others_cnt 			.= $response->cnt_others.", ";
				$visitor_cnt 			.= $response->cnt_visitor.", ";
				$registration_cnt .= $response->cnt_registration.", ";

				$entry_date_array[] 			= $response->visitor_list_entdate;
				$male_cnt_array[] 				= $response->cnt_male;
				$female_cnt_array[] 			= $response->cnt_female;
				$others_cnt_array[] 			= $response->cnt_others;
				$visitor_cnt_array[] 			= $response->cnt_visitor;
				$registration_cnt_array[] = $response->cnt_registration;
		}
}

$male_cnt = rtrim($male_cnt, ', ');
$female_cnt = rtrim($female_cnt, ', ');
$others_cnt = rtrim($others_cnt, ', ');
$visitor_cnt = rtrim($visitor_cnt, ', ');
$registration_cnt = rtrim($registration_cnt, ', ');

// echo "==".$create_query."==".$female_cnt."==".$visitor_cnt."==".$registration_cnt."=="; exit;
// echo "==".$create_query."==".$male_cnt."==".$female_cnt."==".$others_cnt."==".$visitor_cnt."==".$registration_cnt."=="; exit;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
	data-template="vertical-menu-template-free">

<head>
	<meta http-equiv="refresh" content="120">
	<meta charset="utf-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	<title>LTD Report : <?=$site_title?></title>
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
	<style>
	.container {
		width: 100% !important;
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
						<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> LTD Report</h4>

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
													<div style="text-align: center; font-size: 1.4rem; font-weight: bold;">LTD Report Duration : <?=$thismonth_startdate?> - <?=$thismonth_yesterday?></div>
													<div style="height: 400px !important; width: 100% !important;">
														<script>
															var data = {
																labels: [<?=$lable_value?>],
																datasets: [{
																	label: "Male",
																	backgroundColor: window.theme.primary,
																	borderColor: window.theme.primary,
																	hoverBackgroundColor: window.theme.primary,
																	hoverBorderColor: window.theme.primary,
																	data: ["<?=$male_cnt?>"],
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Female",
																	backgroundColor: "#FFC75F",
																	borderColor: "#FFC75F",
																	hoverBackgroundColor: "#FFC75F",
																	hoverBorderColor: "#FFC75F",
																	data: ["<?=$female_cnt?>"],
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Others",
																	backgroundColor: "#88933a",
																	borderColor: "#88933a",
																	hoverBackgroundColor: "#88933a",
																	hoverBorderColor: "#88933a",
																	data: ["<?=$others_cnt?>"],
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Footfalls",
																	backgroundColor: "#C34A36",
																	borderColor: "#C34A36",
																	hoverBackgroundColor: "#C34A36",
																	hoverBorderColor: "#C34A36",
																	data: ["<?=$visitor_cnt?>"],
																	barPercentage: .75,
																	categoryPercentage: .5
																}, {
																	label: "Registration",
																	backgroundColor: "#845EC2",
																	borderColor: "#845EC2",
																	hoverBackgroundColor: "#845EC2",
																	hoverBorderColor: "#845EC2",
																	data: ["<?=$registration_cnt?>"],
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
</body>
</html>

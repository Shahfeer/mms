<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);
$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Dashword Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Dashboard : <?=$site_title?></title>
    <meta name="description" content="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
   <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>                 
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <!-- Icons -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <!-- Style.css -->
	<!-- <link rel="stylesheet" type="text/css" href="assets\css\styl.css">
	<link rel="stylesheet" type="text/css" href="assets\css\jquery.CustomScrollbar.css"> -->
	<style>
	th,
	td {
		white-space: inherit;
	}
 .card card-block-small{
    padding: 15px 20px;
    background
    border-bottom: 1px solid #4a5a77
  }
  .widget-card-1 {
    margin-top: 20px;
    text-align: center
}

.widget-card-1 .card1-icon {
    width: 60px;
    height: 60px;
    position: absolute;
    top: -15px;
    font-size: 35px;
    border-radius: 8px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    color: #fff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out
}
.widget-card-1:hover .card1-icon {
    top: -25px
}
	/* .icons-alert:before {
		color: #fff;
		content: "\e81b";
		font-family: 'feather' !important;
		font-size: 26px;
		left: -37px;
		position: absolute;
		top: 5px;
	}

	.typewrite {
		font-size: 14px;
		font-weight: bold;
	} */
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
 <!-- / Row Content end -->
 <!-- statustic-card  start -->
 <?php

$curl = curl_init();

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
"query" : "select * from (select COUNT(visitor_gender) as male_count from visitor_list where visitor_gender = \'M\') as f_count , (select COUNT(visitor_gender) as female_count from visitor_list where visitor_gender = \'F\' ) as m_count , (select COUNT(visitor_list_id) as total_visitor_count from visitor_list ) as tvc , (select COUNT(customer_id) as total_customer_count from customer_management ) as total_gen"

}
',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$list = json_decode( $response);
for($indicator = 0; $indicator < $list->num_of_rows; $indicator++){
  $male_count = $list->result[$indicator]->male_count;
  $female_count = $list->result[$indicator]->female_count;
  $total_visitor_count = $list->result[$indicator]->total_visitor_count;
  $total_customer_count = $list->result[$indicator]->total_customer_count;
}
?>
 <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                    <div class="col-md-3 col-xl-3" style='background-color:#b5b7ef;'>
												<div class="card widget-card-1">
													<div class="card-block-small"><br>
                          <i class="fa fa-registered" style="font-size:36px"></i>
														<!-- <i class="icofont icofont-cur-rupee-minus bg-c-pink card1-icon"></i> -->
														<span class="text-c-pink f-w-600" style='color: #696cff'>Total Registeration Count</span>
														<h4><?=$total_customer_count?></h4>
														<div><br>
															<span class="f-left m-t-10 text-muted">
																<i class="text-c-pink f-16 feather icon-watch m-r-10"></i></i><p style='color: #696cff'>Total Registeration Count For Today</p>
															</span>
														</div>
													</div>
												</div><br>
											</div>
 <div class="col-md-3 col-xl-3" style='background-color:#b5b7ef;'>
												<div class="card widget-card-1">
													<div class="card-block-small" ><br>
                          <i class='fas fa-users' style='font-size:36px'></i>

														<!-- <i class="icofont icofont-ui-calendar bg-c-blue card1-icon"></i> -->
														<span class="text-c-blue f-w-600" style='color: #696cff'>Total Gender Count</span>
														<h4><?=$total_visitor_count?></h4>
														<div><br>
															<span class="f-left m-t-10 text-muted">
																<i class="text-c-blue f-16 feather icon-alert-triangle m-r-10"></i><p style='color: #696cff'>Total Count For Today</p>
															</span>
														</div>
													</div>
												</div><br>
											</div>
											<div class="col-md-3 col-xl-3" style='background-color:#b5b7ef;'>
												<div class="card widget-card-1">
													<div class="card-block-small"><br>
                          <i class='fas fa-female' style='font-size:36px'></i>
														<!-- <i class="icofont icofont-deal    card1-icon"></i> -->
														<span class="text-c-green f-w-600" style='color: #696cff'>Total Female Count</span>
														<h4><?=$female_count?></h4>
														<div><br>
															<span class="f-left m-t-10 text-muted">
																<i class="text-c-green f-16 feather icon-calendar m-r-10"></i><p style='color: #696cff'>Total Female Count For Today</p>
															</span>
														</div>
													</div>
												</div><br>
											</div>
											<div class="col-md-3 col-xl-3" style='background-color:#b5b7ef;'>
												<div class="card widget-card-1">
													<div class="card-block-small"><br>
                          <i class='fas fa-male' style='font-size:36px'></i>
														<!-- <i class="icofont icofont-cur-rupee bg-c-yellow card1-icon"></i> -->
														<span class="text-c-yellow f-w-600" style='color: #696cff'>Total male Count</span>
														<h4><?=$male_count?></h4>
														<div><br>
															<span class="f-left m-t-10 text-muted">
																<i class="text-c-yellow f-16 feather icon-tag m-r-10"></i><p style='color: #696cff'>Total Male Count For Today</p>
															</span>
														</div>
													</div>
												</div><br>
											</div>
											
</div></div></div></div>
											<!-- statustic-card  end -->
                      <br><br>
                      <!-- visitor start -->
<div class="row">
<div class="col-xl-8 col-md-12">
												<div class="card">
													<div class="card-header">
														<h5>SMS Count</h5>
														<span class="text-muted"></span>
														<div class="card-header-right">
														</div>
													</div>
													<div class="card-block">
														<div id="chart_sms_count" style="height:300px"></div>
														<input type="hidden" id='txt_curdate' value='<?=date("d-m-Y")?>'>
														<input type="hidden" id='txt_smstxt' value='<?=$sms_text?>'>
														<input type="hidden" id='txt_unicod' value='<?=$unicode_sms?>'>
													</div>
												</div>
											</div>
											<div class="col-xl-4 col-md-12">
												<div class="card">
													<div class="card-block bg-c-green">
														<div id="chart_sms_count_list" style="height: 355px"></div>
													</div>
												</div>
											</div>
                                </div><br>  
											<!-- visitor end -->

											<!-- Table Start -->
											
              <div class="col-md-12 col-lg-12 card card-block user-card f-left">
																<div class="table-responsive dt-responsive">
																	<table id="basic-btn" class="table table-striped table-bordered">
																		<thead>
																			<tr>
																				<th>#</th>
																				<th>Gender Count</th>
																				<th>Gender Name</th>
																				<th>Date</th>
																			</tr>
																		</thead>
																		<tbody>

																			<?  
																		$curl = curl_init();

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
                                    "query" : "SELECT COUNT(visitor_gender) as \'gender_count\',visitor_gender,visitor_list_entdate FROM `visitor_list` GROUP BY visitor_gender;"
                                    
                                    }
                                    ',
                                      CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                      ),
                                    ));
                                    
                                    $response = curl_exec($curl);
                                    
                                    curl_close($curl);
                                    // echo $response;
																		$gender = json_decode( $response);
																		site_log_generate("Home Page : User : ".$_SESSION['yjtsms_user_name']." executed the latest 3 campaigns query ($response) on ".date("Y-m-d H:i:s"));

																		$increment = 0;
																		if ($gender->num_of_rows > 0) {
																				for($indicator = 0; $indicator < $gender->num_of_rows; $indicator++){
                                          $visitor_gender = $gender->result[$indicator]->visitor_gender;
                                          $visitor_list_entdate = date( 'd-m-Y H:i:s A', strtotime( $gender->result[$indicator]->visitor_list_entdate ) );
																				$increment++;?>
                                        <tr>
																				<td><?=$increment?></td>
																				<td><?=$gender->result[$indicator]->gender_count?></td>
																				<td><?if($visitor_gender == 'F'){?><label>FEMALE</label><?}elseif($visitor_gender == 'M') { ?><label
																				>MALE</label>
																			<? } ?>
                                      </td>
                                      <td><?=$visitor_list_entdate?></td>
																
																	<?	/*		$refund_date = date( 'd-m-Y H:i:s A', strtotime( $gender->result[$indicator]->sms_entry_date ) );
																				$disp_stat = '';
																				switch ($gender->result[$indicator]->sms_status) {
																						case 'R':
																								$disp_stat = 'Refund';
																								break;
																						case 'P':
																								$disp_stat = 'Partial Sent';
																								break;
																						
																						default:
																								$disp_stat = 'SMS Sent';
																								break;
																				}

																				if($gender->result[$indicator]->sms_route_id == 4 or $gender->result[$indicator]->sms_route_id == 5) 
																				{
																						$sndrtitle = $gender->result[$indicator]->new_hdsndrid;
																						$cntmlname = $gender->result[$indicator]->new_cntmplid;
																				} else {
																						$sndrtitle = $gender->result[$indicator]->sender_title;
																						$cntmlname = $gender->result[$indicator]->cn_template_name;
																				}
																				
																				// $mobno = base64_encode($gender->result[$indicator]->mobile_nos->type);
																				?>`
																			<tr>
																				<td><?=$increment?></td>
																				<td>Message Type :
																					<?=$gender->result[$indicator]->sms_route_title." (".$gender->result[$indicator]->sms_route_desc.") "?>
																					<br>Sender : <?=$sndrtitle?>
																					<br>Content : <?=$cntmlname?>
																				</td>
																				<td>SMS Type : <?=strtoupper($gender->result[$indicator]->sms_type)?><br>SMS
																					Content : <?=$gender->result[$indicator]->sms_file?></td>

																				<td><label class="label label-warning"><?=$disp_stat?></label>
																					<br><?=$refund_date?>
																				</td>
																			</tr>*/
																			
																		}
																} 
                                else { ?>
																			<tr>
																				<td colspan="4">No Records Found..</td>
																			</tr>
																<? } ?>
																		</tbody>
																		<tfoot>
																			<tr>
																				<th>#</th>
																				<th>Gender Count</th>
																				<th>Gender Name</th>
																				<th>Date</th>
																			</tr>
																		</tfoot>
																	</table>
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
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    	<!-- google chart -->
	<script type="text/javascript" src="libraries/assets/js/loader.js"></script>
	<!-- Chart js -->
	<script type="text/javascript" src="libraries\bower_components\chart.js\js\Chart.js"></script>
	<!-- amchart js -->
	<script src="libraries\assets\pages\widget\amchart\amcharts.js"></script>
	<script src="libraries\assets\pages\widget\amchart\serial.js"></script>
	<script src="libraries\assets\pages\widget\amchart\light.js"></script>
   
	<script type="text/javascript">
	var TxtType = function(el, toRotate, period) {
		this.toRotate = toRotate;
		this.el = el;
		this.loopNum = 0;
		this.period = parseInt(period, 10) || 1000;
		this.txt = '';
		this.tick();
		this.isDeleting = false;
	};

	TxtType.prototype.tick = function() {
		var i = this.loopNum % this.toRotate.length;
		var fullTxt = this.toRotate[i];

		if (this.isDeleting) {
			this.txt = fullTxt.substring(0, this.txt.length - 1);
		} else {
			this.txt = fullTxt.substring(0, this.txt.length + 1);
		}

		this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

		var that = this;
		var delta = 100 - Math.random() * 100;

		if (this.isDeleting) {
			delta /= 2;
		}

		if (!this.isDeleting && this.txt === fullTxt) {
			delta = this.period;
			this.isDeleting = true;
		} else if (this.isDeleting && this.txt === '') {
			this.isDeleting = false;
			this.loopNum++;
			delta = 500;
		}

		setTimeout(function() {
			that.tick();
		}, delta);
	};

	window.onload = function() {
		var elements = document.getElementsByClassName('typewrite');
		for (var i = 0; i < elements.length; i++) {
			var toRotate = elements[i].getAttribute('data-type');
			var period = elements[i].getAttribute('data-period');
			if (toRotate) {
				new TxtType(elements[i], JSON.parse(toRotate), period);
			}
		}
		// INJECT CSS
		var css = document.createElement("style");
		css.type = "text/css";
		css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
		document.body.appendChild(css);
	};

	$(document).ready(function() {
		var txt_curdat = $("#txt_curdate").val();
		var txt_smstxt = $("#txt_smstxt").val();
		var txt_unicod = $("#txt_unicod").val();

		//Combo chart
		google.charts.load('current', {
			'packages': ['corechart']
		});
		google.charts.setOnLoadCallback(drawVisualization_smscount);

		function drawVisualization_smscount() {
			var data = google.visualization.arrayToDataTable([
				['Overall', 'Text SMS', 'UNICODE SMS'],
				['Overall', parseInt(txt_smstxt), parseInt(txt_unicod)]
			]);

			var options = {
				title: '',
				vAxis: {
					title: 'Count'
				},
				hAxis: {
					title: 'Type'
				},
				seriesType: 'bars',
				colors: ['#93BE52', '#69CEC6', '#FE8A7D']
			};

			var chart = new google.visualization.ComboChart(document.getElementById('chart_sms_count'));
			chart.draw(data, options);
		}

		//Slice Visibility Threshold
		google.charts.load('current', {
			'packages': ['corechart']
		});
		google.charts.setOnLoadCallback(drawChartThreshold);
		var txt_unicod1 = $("#txt_unicod").val();

		function drawChartThreshold() {
			console.log("==" + parseInt(txt_smstxt) + "==" + parseInt(txt_unicod) + "==" + txt_smstxt + "==" + txt_unicod +
				"==");
			var dataThreshold = new google.visualization.DataTable();
			dataThreshold.addColumn('string', 'Type');
			dataThreshold.addColumn('number', 'Count');
			dataThreshold.addRows([
				['Text SMS', parseInt(txt_smstxt)],
				['Unicode SMS', parseInt(txt_unicod)]
			]);

			var optionsThreshold = {
				title: 'SMS Count',
				sliceVisibilityThreshold: .2,
				colors: ['#4680ff', '#FE8A7D']
			};

			var chart = new google.visualization.PieChart(document.getElementById('chart_sms_count_list'));
			chart.draw(dataThreshold, optionsThreshold);
		}

		typeWriter();
	});
	</script>

     
  </body>
</html>

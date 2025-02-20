<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);
$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Change Password Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));

if($_SESSION['yjtsms_user_id'] == ""){ ?>
<script>
window.location = "index";
</script>
<?php exit();
}?>
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
    <title>Change Password : <?=$site_title?></title>
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
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
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
                          <!-- <h5 class="card-title text-primary">Congratulations Super Admin! 🎉</h5>-->
                          <p class="mb-4">
                           Change Password
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
               <!-- /Sign in -->
              
               <div class="col-lg-12">
               <div class="row">
               <div class="col-3"></div>
				<div class="col-6">
               <form id="frmid_change_pwd" class="mb-3" action="#" method="post" name="frmid_change_pwd">
               <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Existing Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="txt_ex_password"
                      autocomplete="on"
                      class="form-control"
                      name="txt_ex_password"
                      placeholder="Existing Password"
                      aria-describedby="password"
                    
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">New Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="txt_new_password"
                      autocomplete="on"
                      class="form-control"
                      name="txt_new_password"
                     
                      placeholder="New Password : [Atleast 8 characters and Must Contains Numeric, Capital Letters and Special characters]"
                      aria-describedby="password" onblur="return checkPasswordStrength()"
                    /> 
                    <div id='idtxt_new_password' class='text-danger'></div>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Conform Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      autocomplete="on"
                      id="txt_confirm_password"
                      class="form-control"
                   
                      name="txt_confirm_password"
                      placeholder="Your Confirm Password"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                

                <div class="mb-3">
                <span class="error_display" id='pwd_id_error_display'></span>
                <input type="hidden" class="form-control" name='pwd_call_function' id='pwd_call_function'
																	value='change_pwd' />
                                  <input type="submit" name="pwd_submit" id="pwd_submit" tabindex="3" value="Change Password" class="btn btn-primary d-grid w-100">
                  <!-- <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button> -->
</div>

              </form></div>
              <div class="col-3"></div>
<!-- </div> -->
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
    <script>
        	function checkPasswordStrength() {
		var number = /([0-9])/;
		var alphabets = /([a-zA-Z])/;
		var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
		if ($('#txt_new_password').val().length < 8) {
			$('#idtxt_new_password').html("Weak (should be atleast 8 characters.)");
			$('#txt_new_password').css('border-color', 'red');
			return false;
		} else {
			if ($('#txt_new_password').val().match(number) && $('#txt_new_password').val().match(alphabets) && $(
					'#txt_new_password').val().match(special_characters)) {
				$('#txt_new_password').css('border-color', '#a0a0a0');
				return true;
			} else {
				$('#idtxt_new_password').html("Medium (should include alphabets, numbers and special characters.)");
				$('#txt_new_password').css('border-color', 'red');
				return false;
			}
		}
	}
    $("#pwd_submit").click(function(e) {
        
		$("#pwd_id_error_display").html("");

		//get input field values
		var ex_password = $('#txt_ex_password').val();
		var new_password = $('#txt_new_password').val();
		var confirm_password = $('#txt_confirm_password').val();
//  console.log(ex_password);
//          console.log(new_password);
//          console.log(confirm_password);
		var flag = true;
		/********validate all our form fields***********/
		/* password field validation  */
		if (ex_password == "") {
			$('#txt_ex_password').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}
		if (new_password == "") {
			$('#txt_new_password').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}

		/* confirm_password field validation  */
		if (confirm_password == "") {
			$('#txt_confirm_password').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}

		/* ex password, new password, confirm_password field validation  */
		if (new_password == ex_password) {
			$('#txt_new_password').css('border-color', 'red');
			$("#pwd_id_error_display").html("New Password cannot same with Existing Password");
			flag = false;
			e.preventDefault();
		}

		if (confirm_password != "" && new_password != "" && confirm_password != new_password) {
			$('#txt_confirm_password').css('border-color', 'red');
			$("#pwd_id_error_display").html("Confirm Password mismatch with New Password");
			flag = false;
			e.preventDefault();
		}
		/********Validation end here ****/

		/* If all are ok then we send ajax request to ajax/call_functions.php *******/
        // console.log(flag);
        // e.preventDefault();
		if (flag) {
            // console.log(flag);
			var data_serialize = $("#frmid_change_pwd").serialize();
            // console.log(data_serialize);
			$.ajax({
				type: 'post',
				url: "ajax/call_functions.php",
				dataType: 'json',
				data: data_serialize,
				beforeSend: function() {
					$('#submit').attr('disabled', true);
					$('#load_page').show();
				},
				complete: function() {
					$('#submit').attr('disabled', false);
					$('#load_page').hide();
				},
				success: function(response) {
					if (response.status == '0') {
						$('#txt_ex_password').val('');
						$('#txt_new_password').val('');
						$('#txt_confirm_password').val('');
						$('#submit').attr('disabled', false);

						$("#pwd_id_error_display").html(response.msg);
					} else if (response.status == 1) {
						$('#submit').attr('disabled', false);
						$("#pwd_id_error_display").html("Password Changed...");
						window.location = 'index';
					}
					$('#load_page').hide();
				},
				error: function(response, status, error) {
					$('#txt_ex_password').val('');
					$('#txt_new_password').val('');
					$('#txt_confirm_password').val('');
					$('#submit').attr('disabled', false);

					$("#pwd_id_error_display").html(response.msg);
				}
			});
		}
	});
    </script>
  </body>
</html>

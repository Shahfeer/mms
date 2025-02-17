<?php
session_start();
error_reporting(0);

// Include configuration.php before using $site_url
include_once('api/configuration.php');

// Ensure HTTP_REFERER is set before accessing it
$http_referer = $_SERVER['HTTP_REFERER'] ?? ''; 

if ($http_referer == '' || $newPageName == 'index' || $newPageName == 'logout' || $newPageName == 'dashboard') {
    $server_http_referer = $site_url . "dashboard";
} elseif ($http_referer) {
    $server_http_referer = $site_url . "dashboard";
} else {
    $server_http_referer = $http_referer;
}
?>




<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
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

    <title>Login : <?=$site_title?></title>
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

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />yl


    <style>
      #id_error_display_signin{
        color: red;
      }
    </style>
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body style=" margin: 0 auto; background: url('https://recfaces.com/wp-content/uploads/2020/11/variant-4.jpg') no-repeat; background-size: cover; min-height: 100vh;">
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.php" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                  </span>
                  <span class="app-brand-text demo text-body fw-bolder">MMS</span>
                </a>
              </div>
              <!-- /Sign in -->
              <form id="formAuthentication" class="mb-3" action="#" method="post" name="formAuthentication">
                <div class="mb-3">
                  <label for="txt_username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="txt_username"
                    name="txt_username"
                    
                    placeholder="Enter your username"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input 
                      type="password"
                      id="txt_password"
                      class="form-control"
                      name="txt_password"
                      autocomplete="on"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                

                <div class="mb-3">
                <input type="hidden" class="form-control" name='call_function' id='call_function'
																	value='signin' />
                                  <input type="submit" name="submit" id="submit" tabindex="3" value="Sign in"
																	class="btn btn-primary d-grid w-100">
                  <!-- <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button> -->

                </div>
                <div class="mb-3">
															<div class="col-md-12">
																<span class="error_display" id='id_error_display_signin'></span>
															</div>
															<div class="col-md-4"></div>
														</div>
              </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <!-- / Content -->
 

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
    $("#submit").click(function(e) {
		$("#id_error_display_signin").html("");
		var uname = $('#txt_username').val();
    // console.log(uname);
		var password = $('#txt_password').val();
    
    // console.log(password);
		var flag = true;
		/********validate all our form fields***********/
		/* Name field validation  */
		if (uname == "") {
			$('#txt_username').css('border-color', 'red');
			flag = false;
// console.log(flag);
			e.preventDefault();
		}
		/* password field validation  */
		if (password == "") {
// console.log(flag);
			$('#txt_password').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		} else {
			/* if(checkPasswordStrength() == false) {
					flag = false;
					e.preventDefault();
			} */
		}
		/********Validation end here ****/
// console.log(flag);
		/* If all are ok then we send ajax request to process_connect.php *******/
		if (flag) {
// console.log(flag);
var data_serialize = $("#formAuthentication").serialize();
      // console.log(data_serialize);
			$.ajax({
				type: 'post',
				url: "ajax/call_functions.php",
				dataType: 'json',
				data: data_serialize,
				beforeSend: function() {
// console.log('hello');

					$('#submit').attr('disabled', true);
					$('#load_page').show();
				},
				complete: function() {
// console.log('hello');

					$('#submit').attr('disabled', false);
					$('#load_page').hide();
				},
				success: function(response) {
// console.log('hello');
// alert(response);
					if (response.status == '0') {

						$('#txt_password').val('');
						$('#submit').attr('disabled', false);
						$("#id_error_display_signin").html(response.msg);
					} else if (response.status == 1) {
						$('#submit').attr('disabled', false);
						var hid_sendurl = 'dashboard';
						window.location = hid_sendurl;
					}
				},
				error: function(response, status, error) {
					$('#txt_password').val('');
					$('#submit').attr('disabled', false);
					$("#id_error_display_signin").html(response.msg);
				}
			});
		}
	});
  </script>
  </body>
</html>

<?php
session_start();
error_reporting(0);
include_once ('api/configuration.php');
extract($_REQUEST);

if ($_SESSION['yjtsms_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("User Registration Page : User : " . $_SESSION['yjtmms_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Customer Registration : <?= $site_title ?></title>
  <meta name="description" content="" />

  <!-- WEBCAM  -->
  <!-- <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> -->
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
  <!--  -->

  <!-- Helpers -->
  <script src="assets/vendor/js/helpers.js"></script>

  <script src="assets/js/config.js"></script>
  <!-- Checkbox-->

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->


</head>
<!-- Multiselect option style -->
<style>
  .container {
    width: 100% !important;
  }

  .multiselect {
    width: 100%;
  }

  .selectBox {
    position: relative;
  }

  .selectBox select {
    width: 100%;
  }

  .overSelect {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
  }

  #mySelectOptions {
    display: none;
    border: 0.5px #7c7c7c solid;
    background-color: #ffffff;
    max-height: 150px;
    overflow-y: scroll;
  }

  #mySelectOptions label {
    display: block;
    font-weight: normal;
    display: block;
    white-space: nowrap;
    min-height: 1.2em;
    background-color: #ffffff00;
    padding: 0 2.25rem 0 .75rem;
    /* padding: .375rem 2.25rem .375rem .75rem; */
  }

  #mySelectOptions label:hover {
    background-color: #1e90ff;
  }
</style>
<!-- WEBCAM -->
<style type="text/css">
  #results {
    padding: 20px;
    border: 1px solid;
    background: #ccc;
  }

  /* tooltip */
  .pcoded[layout-type="dark"] .modal-content,
  body.dark .modal-content {
    background-color: #4e5f7d
  }

  .docs-cropped .modal-body {
    text-align: center
  }

  .docs-cropped .modal-body>img,
  .docs-cropped .modal-body>canvas {
    max-width: 100%
  }

  .pcoded[layout-type="dark"] .modal-content .modal-header,
  body.dark .modal-content .modal-header {
    border-bottom: 1px solid #404E67
  }

  .pcoded[layout-type="dark"] .modal-content .modal-footer,
  body.dark .modal-content .modal-footer {
    border-top: 1px solid #404E67
  }

  .btn-outline-info {
    color: #19a7ba;
    background-color: #fff;
    background-color: transparent
  }

  body {
    /* background-color: #94C3EC; */
    font-size: 1em !important;
    overflow-x: hidden;
    color: #353c4e;
    font-family: "FiraSansExtraCondensed", sans-serif;
    background-attachment: fixed;
  }

  textarea {
    overflow-y: scroll;
    height: 50px;
    width: 650px;
    resize: none;
    /* Remove this if you want the user to resize the textarea */
  }
  #id_error_display_submit {
  color:red;
}
</style>


<body>

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <? include ("libraries/site_menu.php"); ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <? include ("libraries/site_header.php"); ?>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Customer Registration</h4>

            <div class="row">
              <div class="col-md-12">
                <div class="card mb-4">
                  <!-- Account -->

                  <div class="card-body">
                    <form id="formAccountSettings" method="POST" name="formAccountSettings">
                      <div class="row">
                        <div class="mb-4" style='visibility: hidden; display: none;'>
                          <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                              <div class="button-wrapper width_100percent">
                                <!-- WEBCAM-->

                                <div class="row">
                                  <div class="col-md-6">
                                    <div id="my_camera"></div>
                                    <br />
                                    <!-- <input type=button value="Take Snapshot" onClick="take_snapshot()"> -->
                                    <input type="hidden" name="imageid" id="imageid" class="image-tag" onchange="imageUpload();">
                                    <!-- onchange="imageUpload();" -->
                                  </div>
                                  <!-- <div class="col-md-6">
                <div id="results">Your captured image will appear here...</div>
            </div> -->
                                  <!-- <div class="col-md-12 text-center">
                 <br/>
                <button class="btn btn-success">Submit</button> 
            </div> -->
                                </div>



                                <!-- <img src="assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded" id="uploadedAvatar" name ="uploadedAvatar" style="max-width: 100%; width: 100%;" /> -->
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="mb-4 col-lg-6 offset-lg-3">
                          <div class="row">
                            <div class="mb-3 col-md-3">
                              <label for="txt_user_name" class="form-label">Name *</label>
                            </div>

                            <div class="mb-9 col-md-9">
                              <input class="form-control" type="text" id="txt_user_name" name="txt_user_name" value=""
                                placeholder="Name" title="Name" autofocus tabindex="1"
                                onkeypress="return clsAlphaNoOnly(event)" pattern="[a-zA-Z0-9 -_]+" required />

                            </div>
                          </div>

                          <div class="row">
                            <div class="mb-3 col-md-3">
                              <label for="txt_user_mobile" class="form-label">Mobile No *</label>

                            </div>

                            <div class="mb-9 col-md-9">

                              <input class="form-control" type="text" id="txt_user_mobile" name="txt_user_mobile"
                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                                onblur="return call_validate_mobileno()" maxlength="10" value="" tabindex="2"
                                data-toggle="tooltip" placeholder="Mobile No" title="Mobile No" required />
                            </div>
                          </div>



                          <div class="row">
                            <div class="mb-3 col-md-3">
                              <label for="txt_user_otp" class="form-label">OTP *</label>

                            </div>
                            <div class="mb-9 col-md-9">
                              <span class="error_display" id='otp_id_error_display'></span>
                              <input type="hidden" class="form-control" name='otp_call_function' id='otp_call_function'
                                value='' />
                              <input class="form-control" maxlength="6" type="text" id="txt_user_otp"
                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                                name="txt_user_otp" tabindex="3" value="" onblur="return otp_validate_mobileno()"
                                placeholder="OTP" title="OTP" required />
                            </div>

                          </div>
                          <div class="row">
                            <div class="mb-3 col-md-3">
                              <label for="txt_user_name" class="form-label">Email </label>
                            </div>

                            <div class="mb-9 col-md-9">

                              <input class="form-control" type="email" id="txt_user_email" name="txt_user_email"
                                value="" placeholder="Email Id" data-placement="top" tabindex="4" title="Email"
                                required />

                            </div>
                          </div>
                          <div class="row">
                            <div class="mb-3 col-md-3">

                              <label for="interest" class="form-label">Interest *</label>

                            </div>


                            <!-- <div class="col-md-4"> -->
                            <div class="mb-9 col-md-9">

                              <?php
                              $curl = curl_init();
                              $api_url = 'http://localhost:10017/report/interest_list';
                              $bearer_token = 'Authorization: ' . $_SESSION['yjtsms_user_bearer_token'];
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
                              )
                              );

                              $response = curl_exec($curl);

                              curl_close($curl);

                              // Decode JSON response
                              $response_data = json_decode($response, true);

                              if ($response == '') { ?>
                                <script>window.location = "logout"</script>
                            <? }
                            else if ($response_data['response_status'] == 403) { ?>
                                <script>window.location = "logout"</script>
                            <? }

                              // Check if the response is valid and contains the expected data
                              if ($response_data && isset($response_data['data']['report'])) {
                                // Start the HTML form
                              
                                echo '<div style="display: flex; flex-wrap: wrap;">';

                                // Display checkboxes for each interest
                                foreach ($response_data['data']['report'] as $key => $interest) {
                                  echo '<div style="width: 50%; box-sizing: border-box; padding: 0 10px; margin-bottom: 10px;">'; // Half-width for two items per row
                                  echo '<label>';
                                  // echo '<input type="checkbox" class="interestCheckbox" name="interests[]" value="' . htmlspecialchars($interest['visitor_interest_title']) . '" style="margin-right: 5px;">'; // Added margin-right to the checkbox
                                  echo '<input type="checkbox" class="interestCheckbox" id="interest_list"  name="interests[]" value="' . htmlspecialchars($interest['visitor_interest_id']) . '" style="margin-right: 5px;">'; // Checkbox value is set to visitor_id
                                  echo htmlspecialchars($interest['visitor_interest_title']);
                                  echo '</label>';
                                  echo '</div>';
                                }

                                echo '</div>'; // Close the flex container
                              
                                // Add a submit button
                                // echo '<input type="submit" value="Submit">';
                                echo '</form>';
                              } else {
                                // Handle error in response
                                echo "Error in API response.";
                              }
                              ?>




                              <div class="main">
                                <table style="width: 100%;">
                                  <? for ($api_respobj_indicator = 0; $api_respobj_indicator < $api_respobj->num_of_rows; $api_respobj_indicator++) {
                                    if ($api_respobj_indicator % 2 == 0) { ?>
                                      <tr>
                                      <? } ?>
                                      <td>
                                        <input type="checkbox" id="list_items" name="list_items[]" tabindex="5"
                                          value="<?= $api_respobj->result[$api_respobj_indicator]->visitor_interest_id ?>">
                                        <label class="form-label">
                                          <?= $api_respobj->result[$api_respobj_indicator]->visitor_interest_title ?></label>
                                      </td>
                                      <?
                                      if ($api_respobj_indicator % 2 == 1) { ?>
                                      </tr>
                                    <? }

                                  }
                                  ?>
                                </table>
                              </div>

                              <? /*  <div class="main">
                         <? for($api_respobj_indicator = 0; $api_respobj_indicator < $api_respobj->num_of_rows; $api_respobj_indicator++) {
                            if($api_respobj_indicator % 2 == 0) { ?>
                              <div>
                            <? } ?>
  <input type="checkbox" id="list_items"  name= "list_items[]" tabindex="5" value="<?=$api_respobj->result[$api_respobj_indicator]->visitor_interest_id?>"> <label class="form-label"> <?=$api_respobj->result[$api_respobj_indicator]->visitor_interest_title?></label>
                              <? 
                              if($api_respobj_indicator % 2 == 0) { ?>
                                </div>
                              <? }
                              
                              }
                              ?></div><br> */ ?>


                            </div>
                          </div>
                          <!-- </div>

</div>  -->


                          <!-- </div>  -->
                          <div class="row"
                            style='font-family: "FiraSansExtraCondensed", sans-serif !important; margin-bottom: 0.5rem; font-size: 0.90rem; font-weight: 500; color: #566a7f;'>
                            <!-- <div class="mb-3 col-md-3"> -->
                            <div class="checkbox-fade fade-in-primary">
                              <label>
                                <input type="checkbox" name="chk_terms" id="chk_terms" value="" tabindex="6">
                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                <span class="text-inverse" style="color:#FF0000 !important">I read and accept the
                                  <!-- <a
                                        href="#" style="color:#FF0000 !important" data-toggle="modal" data-target="#myModal">Terms &amp; Conditions.</a></span> -->
                              </label>
                              Terms & Conditions
                              <textarea readonly
                                style='font-family: "FiraSansExtraCondensed", sans-serif !important; width: 100%; margin-bottom: 0.5rem; font-size: 0.90rem; font-weight: 500; color: #566a7f;'>The contents of this email and any attachments are confidential. They are intended for the named recipient(s) only. If you have received this email by mistake, please notify the sender immediately and do not disclose the contents to anyone or make copies thereof.The contents of this email and any attachments are confidential. They are intended for the named recipient(s) only. If you have received this email by mistake, please notify the sender immediately and do not disclose the contents to anyone or make copies thereof.The contents of this email and any attachments are confidential. They are intended for the named recipient(s) only. If you have received this email by mistake, please notify the sender immediately and do not disclose the contents to anyone or make copies thereof.</textarea><br>

                            </div>
                          </div>


                          <div class="row">
                            <div class="mb-9 col-md-9">
                              <span class="error_display" id='id_error_display_submit'></span>
                            </div>
                          </div>

                        </div>

                        <div class="mb-4 col-lg-3">
                        </div>

                        <!-- class= "mb-6 col-md-6"-->

                        <!-- </div> -->
                      </div>


                      <div class="row">
                        <input type="hidden" class="form-control" name='call_function' id='call_function'
                          value='register' />
                        <!-- url: "ajax/call_functions.php?call_function=register" -->
                        <input type="hidden" class="form-control" name='hid_sendurl' id='hid_sendurl'
                          value='<?= $server_http_referer ?>' />
                      </div>
                      <div class="mt-2 right_align">
                        <button type="submit" id="submit" name="submit" tabindex="6"
                          class="btn btn-primary me-2">Submit</button>
                        <button type="reset" class="btn btn-default" tabindex="7">Cancel</button>
                      </div>
                  </div>
                  </form>
                </div>
                <!-- /Account -->
              </div>

            </div>
          </div>
        </div>
        <!-- / Content -->

        <!-- Modal content-->
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Terms and Conditions</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

              </div>
              <div class="modal-body">
                <dl class="last">
                  <dt><span class="order">1. </span>Terms and Conditions</dt><br>
                  <p>
                    IMPORTANT: The contents of this email and any attachments are confidential. They are intended for
                    the named recipient(s) only. If you have received this email by mistake, please notify the sender
                    immediately and do not disclose the contents to anyone or make copies thereof.
                  </p><br>
                </dl>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-outline-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <? include ("libraries/site_footer.php"); ?>
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
  <!-- <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script> -->
  <!-- <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script> -->

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="assets/js/pages-account-settings-account.js"></script>



  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <!-- Configure a few settings and attach camera -->
  <script language="JavaScript">
    Webcam.set({
      width: 490,
      height: 390,
      image_format: 'jpeg',
      jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

  </script>


  <script id="rendered-js">
    function clsAlphaNoOnly(e) { // Accept only alpha numerics, no special characters 
      var key = e.keyCode;
      if ((key >= 65 && key <= 90) || (key >= 97 && key <= 122) || (key >= 48 && key <= 57) || key == 32 || key == 95) {
        return true;
      }
      return false;
    }


    // <button id="check">Check</button>
    // <script>
    $('#submit').click(function (event) {
      var atLeastOneChecked = false;

      // Check each checkbox with class 'interestCheckbox' within the form with ID 'formAccountSettings'
      $('#formAccountSettings .interestCheckbox').each(function () {
        if ($(this).prop('checked')) {
          atLeastOneChecked = true;
          return false; // Exit loop early if a checkbox is checked
        }
      });

      if (!atLeastOneChecked) {
        alert('Please check at least one option!');
        // Optionally prevent form submission
        // return false;
      }
      //  $('#formAccountSettings').prop('checked', false);

    });

    document.getElementById('#formAccountSettings').addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent default form submission

      // Reset the form using JavaScript
      this.reset(); // 'this' refers to the form element

      // Proceed with form submission or other actions
    });



  </script>
  <!--submit the form -->
  <script>
    $("#submit").click(function (e) {
      $("#id_error_display_submit").html("");
      e.preventDefault();

      //get input field values
      var user_name = $('#txt_user_name').val();
      var user_mobile = $('#txt_user_mobile').val();
      var otp = $('#txt_user_otp').val();
      // let checkbox =$('#list_items').val();
      var list_items = $('#list_items').val();
      // console.log(list_items);
      var email = $('#txt_user_email').val();
      var imageid = $('#imageid').val();

      if (email == '') {

      }
      else {
        var rea = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        var Email = $("#txt_user_email").val();
        var x = rea.test(Email);
        if (!x) {
          $('#txt_user_email').css('border-color', 'red');

          // alert('Type Your valid Email');
          // $("#id_error_display_submit").html("Type Your valid Email!");
          return false;
        }

      }
      // console.log(image);
      var flag = true;
      /********validate all our form fields***********/
      /* Name field validation  */


      if ((user_name == "") && (otp == "") && (user_mobile == "") && (list_items == "") && (!x == "")) {
        $("#id_error_display_submit").html("Please Enter the Text Field!");
        flag = false;
        e.preventDefault();
      } else {
        Webcam.snap(function (data_uri) {
          $(".image-tag").val(data_uri);
          // console.log(data_uri);
          // alert(data_uri);
          $("#imageid").val(data_uri);
          document.getElementById('my_camera').innerHTML = '<img src="' + data_uri + '"/>';

          // document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        });

      }
      if ((user_name == "") || (otp == "") || (user_mobile == "")) {
        $("#id_error_display_submit").html("Please Enter the Text Field!");
        flag = false;
        e.preventDefault();
      }
      if (user_name == "") {
        $('#txt_user_name').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      }

      /* OTP field validation  */
      if (otp == "") {
        $('#txt_user_otp').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      }

      /* Mobile field validation  */
      if (user_mobile == "") {
        $('#txt_user_mobile').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      } else if(user_mobile.length <=9){
        $('#txt_user_mobile').css('border-color', 'red');
        flag = false;
      }

      /* email field validation  */

      if (email == "") {
        $('#txt_user_email').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      }

      /* Terms and condition field validation  */
      if ($("#chk_terms").prop('checked') == false) {
        $('#chk_terms').css('border-color', 'red');
        $("#id_error_display_submit").html("Must read the Terms and Select!");
        flag = false;
        e.preventDefault();
      }

      function validate() {
        if ($("#checkboxvar").is(':checked')) {
          alert("actived");
        } else {
          alert("No actived");
        }
      }
      /********Validation end here ****/

      /* If all are ok then we send ajax request to ajax/call_functions.php *******/
      if (flag) {
        var data_serialize = $("#formAccountSettings").serialize();
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php",
          url: "ajax/call_functions.php?call_function=register", // call register function
          dataType: 'json',
          data: data_serialize,
          beforeSend: function () {
            $('#submit').attr('disabled', true);
            $('#load_page').show();
          },
          complete: function () {
            $('#submit').attr('disabled', false);
            $('#load_page').hide();
            //           location.reload();
            //  return false;
          },
          success: function (response) {
            if (response.status == '0') {
              $('#txt_user_name').val('');
              $('#txt_user_otp').val('');
              $('#txt_user_mobile').val('');
              $('#list_items').val('');
              $('#imageid').val('');
              $('#txt_user_email').val('');
              $("#chk_terms").prop('checked', false);
              $('.interestCheckbox').prop('checked', false);
              $('#submit').attr('disabled', false);

              $("#id_error_display_submit").html(response.msg);
              
            } else if (response.status == '1') {
              $('#txt_user_name').val('');
              $('#txt_user_otp').val('');
              $('#txt_user_mobile').val('');
              $('#list_items').val('');
              $('#imageid').val('');
              $('#txt_user_email').val('');
              $("#chk_terms").prop('checked', false);
              $('#submit').attr('disabled', false);
              $('.interestCheckbox').prop('checked', false);
              $("#id_error_display_submit").html(response.msg);
              window.location= 'user_list.php';
              // $("#id_error_display_signin").html("Registered Successfully");
              // func_open_tab('getEMP')
            }
          },
          error: function (response, status, error) {
            $('#txt_user_name').val('');
            $('#txt_user_otp').val('');
            $('#txt_user_mobile').val('');
            $('#list_items').val('');
            $('#imageid').val('');
            $('#txt_user_email').val('');
            $("#chk_terms").prop('checked', false);
            $('#submit').attr('disabled', false);
            $('.interestCheckbox').prop('checked', false);
            $("#id_error_display_submit").html(response.msg);
          }
        });
      }
    });
    function call_validate_mobileno() {
      //  console.log('***********');
      var txt_user_mobile = $("#txt_user_mobile").val();
      var stt = -1;

     
       if (txt_user_mobile.length > 9) {
        // console.log('***********');

        var letter = txt_user_mobile.charAt(0);
        if (letter == 0 || letter == 1 || letter == 2 || letter == 3 || letter == 4 || letter == 5) {
          stt = 0;
        } else {
          stt = 1;
        }
        if (stt == 0)
          $('#txt_user_mobile').css('border-color', 'red');
        else
          $('#txt_user_mobile').css('border-color', '#ccc');
      }

      if (txt_user_mobile.length == '10') {
        var flag = true;
        var txt_user_otp = $('#txt_user_otp').val();
        var data_serialize = $("#txt_user_mobile").serialize();
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php?otp_call_function=mobile_otp",
          dataType: 'json',
          data: data_serialize,
          beforeSend: function () {

          },
          complete: function () {

          },
        })
      }
      return stt;

    }

    function otp_validate_mobileno() {
      var flag = true;
      var txt_user_otp = $('#txt_user_otp').val();

      if (txt_user_otp.length == '6') {
        var data_serialize = $("#txt_user_otp");
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php?otp_check_call_function=mobile_check_otp",
          dataType: 'json',
          data: data_serialize,
          beforeSend: function () {

          },
          complete: function () {
            $("#id_error_display_submit").html("");

          },
          error: function () {
            $("#id_error_display_submit").html("Enter a correct otp");

          },

        })
      }

    }

    function imageUpload() {
    		var imageid = $("#imageid").val();
    		$.ajax({
    			type: 'post',
    			url: "ajax/call_functions.php",
    			data: {
    				imageUpload: 'imageUpload',
    				imageid: imageid
    			},
    			success: function(response) {
    				$("#imageid").html(response.msg);
    			},
    			error: function(response, status, error) {}
    		});
    	}
  </script>




</body>

</html>
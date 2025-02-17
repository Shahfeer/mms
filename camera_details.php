<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);

 if($_SESSION['yjtsms_user_id'] == ""){ ?>
		<script>window.location="index";</script>
<?php exit();
} 

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("User Registration Page : User : ".$_SESSION['yjtmms_user_name']." access the page on ".date("Y-m-d H:i:s"));
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
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Camera Details : <?=$site_title?></title>
    <meta name="description" content="" />

     <!-- WEBCAM  -->
     <!-- <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
     <!-- Multiselect option -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>  

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <script src="assets/js/config.js"></script>
  </head>
   <!-- Multiselect option style -->
  <style>
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

#cameraForm{
   padding-left:20px;
} 
#cameraForm1{
   padding-left:20px;
   /* width:50px; */
} 
#cameraradio{
    padding-left:20px;
}

</style>



  <script>
  window.console = window.console || function(t) {};
</script>

<script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Camera Details</h4>

              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-4">
                    <!-- Account -->
                   
                    <div class="card-body">
                        
                    <div class="mb-3 col-md-3"></div>
                    <div class="mb-6 col-md-6">
                      <form id="formAccountSettings" method="POST" onsubmit="return false" name="formAccountSettings">
                        
                          
                            <div class="row">
                              <div class="mb-3 col-md-3">
                                <label for="camera_position" class="form-label">Camera Position *</label>
                              </div>
                              
                              <div class="mb-9 col-md-9">
                                <input class="form-control" type="text" id="camera_position" name="camera_position" value="" placeholder="Camera Position" title="Name" required autofocus />
                                
                              </div>
                            </div>

                            <div class="row">
                              <div class="mb-3 col-md-3">
                                <label for="ip_address" class="form-label">Ip Address *</label>
                                
                              </div>
                              
                              <div class="mb-9 col-md-9">
                              
                                <input class="form-control" type="text" id="ip_address" name="ip_address"   placeholder="Ip Address" title="Ip Address" required />
                              </div>
                            </div>
                            
                                            
                         
                            <div class="row">
                              <div class="mb-3 col-md-3">
                                <label for="txt_camera_details" class="form-label">Camera Details *</label>
                                
                              </div>
                              <div class="mb-9 col-md-9">
                              <span class="error_display" id='otp_id_error_display'></span>
                <!-- <input type="hidden" class="form-control" name='otp_call_function' id='otp_call_function'
																	value='' /> -->
                                <input class="form-control" type="text" id="txt_camera_details" name="txt_camera_details" value= "" placeholder="Camera Details" title="Camera Details" required />
                              </div>
                             
                            </div>
                           
                            <div class="row">
                              <div class="mb-3 col-md-3">
                             
                                <label for="interest" class="form-label">Store Name *</label>
                              </div>
                              
                           
                            <!-- <div class="container">   -->
  <!-- <div class="col-md-4"> -->
    <div class="mb-9 col-md-9">

     

<?php
        $sql_query = "SELECT store_id,store_name FROM `store_details`";
                                    $api_curl_query = curl_init();
                                    curl_setopt_array($api_curl_query, array(
                                      CURLOPT_URL => $api_url."/select_query",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => '',
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 0,
                                      CURLOPT_FOLLOWLOCATION => true,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => 'POST',
                                      CURLOPT_POSTFIELDS =>'{
                                        "query": "'.$sql_query.'"
                                      }',
                                      CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                      ),
                                    ));
                                  
                                    $api_response = curl_exec($api_curl_query);
                                    curl_close($api_curl_query);
                                    $api_respobj = json_decode($api_response);?>
                                   <select id="ddlViewBy" name="ddlViewBy">
                                 <? for($api_respobj_indicator = 0; $api_respobj_indicator < $api_respobj->num_of_rows; $api_respobj_indicator++) {?>
                                        <option value="<?=$api_respobj->result[$api_respobj_indicator]->store_name?>"> <?=$api_respobj->result[$api_respobj_indicator]->store_name?></option><br>
                                      <? }


                                     ?>
                                    
                                    </select>                          
                                    
    </div>
  </div>

<!-- </div> -->
                            <div class="row">
                              <div class="mb-3 col-md-3">
                             
                                <label for="interest" class="form-label">Start / Stop Action *</label>
                              </div>
                              
                          
    <div class="mb-9 col-md-9">
    <div id="cameraForm" name="cameraForm" > 
    <input type="radio" name="cameraradio" value="start" checked/>  Start
    </div>                      
    <div id="cameraForm1" name="cameraForm1" > 

    <input type="radio" name="cameraradio" value="stop" />  Stop
   
 </div> 
<br>

   
</div>
  
                                  <!-- </div>  -->
                                         <!-- <div class="row" style ="text-align: center !important;" >
                              <div class="mb-3 col-md-3"> 
																<div class="checkbox-fade fade-in-primary">
																	<label>
																		<input type="checkbox" name="chk_terms" id="chk_terms" value="" tabindex="12">
																		<span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
																		<span class="text-inverse" style="color:#FF0000 !important">I read and accept the <a
																				href="#" style="color:#FF0000 !important" data-toggle="tooltip"
																				data-placement="top" title="" data-original-title="Terms & Conditions."
																				class="alert-ajax btn-outline-info">Terms &amp; Conditions.</a></span>
																	</label>
																
														</div> -->
                            
                              <div class="row">
															<div class="mb-9 col-md-9">
																<span class="error_display" id='id_error_display_submit'></span>
															</div>
														</div>
                           
                            <!-- class= "mb-6 col-md-6"-->
														
                          <!-- </div> -->
                          <div class="mb-3 col-md-3"></div>
                        </div>
                        
                        
                            <div class="row">
																<input type="hidden" class="form-control" name='call_function' id='call_function'
																	value='camera' />
																<input type="hidden" class="form-control" name='hid_sendurl' id='hid_sendurl'
																	value='<?=$server_http_referer?>' />
                                  </div>
                        <div class="mt-2 right_align">
                          <button type="submit" id="submit"  name="submit" class="btn btn-primary me-2">Submit</button>
                          <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                      </form>
                    </div>
                    </div> </div>
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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>
    <!-- <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script> -->
    
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
 
    <!-- Page JS -->
    <script src="assets/js/pages-account-settings-account.js"></script>
    
  

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    
</script>
 <!-- radio option -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
      var cameravalue= $("input:radio[name=cameraradio]:checked").val();
$("#submit").click(function(){
var cameravalue=$("input:radio[name=cameraradio]:checked").val();
// alert(value);
// console.log(cameravalue);
})  


</script>

    <!--radio option end  -->
   
  
 <!--submit the form -->
 <script>
      $("#submit").click(function(e) {
		$("#id_error_display_submit").html("");
		e.preventDefault();

		//get input field values
		var camera_name = $('#camera_position').val();
		var ip_address = $('#ip_address').val();
		var camera_details = $('#txt_camera_details').val();
var cameraradio = $('#cameraradio').val();
// var ddlViewBy = $('#ddlViewBy').val();
//select option
  var e = document.getElementById("ddlViewBy");
  var text = e.options[e.selectedIndex].text;
  var value = e.value;
 
// console.log(value, text);
  

		var flag = true;
		/********validate all our form fields***********/



    if((camera_name == "") && (camera_details == "") && (ip_address == "")){
    $("#id_error_display_submit").html("Please Enter the Text Field!");
          flag = false;
          e.preventDefault();
  }
//   console.log(flag);

  if((camera_name == "")||(camera_details == "")||(ip_address == "")){
    $("#id_error_display_submit").html("Please Enter the Text Field!");
          flag = false;
          e.preventDefault();
  }
//   console.log(flag);

		if (camera_name == "") {
			$('#camera_position').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}
        // console.log(flag);

		/* Email field validation  */
		if (camera_details == "") {
			$('#txt_camera_details').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}
        // console.log(flag);

		/* Mobile field validation  */
		if (ip_address == "") {
			$('#ip_address').css('border-color', 'red');
			flag = false;
			e.preventDefault();
		}
        // console.log(flag);

        

        if ($('#ddlViewBy:selected').length < 0) {
    // console.log('****');
      $("#id_error_display_submit").html("Interest is required field!");
//its checked

} 
else {
//not checked
}
 
/* Terms and condition field validation  */

   
/********Validation end here ****/

		/* If all are ok then we send ajax request to ajax/call_functions.php *******/
		if (flag) {
    // console.log('****');

			var data_serialize = $("#formAccountSettings").serialize();
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
          location.reload();
 return false;
				},
				success: function(response) {
					if (response.status == '0') {
						$('#camera_position').val('');
						$('#txt_camera_details').val('');
						$('#ip_address').val('');
            $('#text').val('');

                        $('#camera').val('');
            // $('#ddlViewBy').val('');
            // $('#imageid').val('');
						// $("#chk_terms").prop('checked', false);
						$('#submit').attr('disabled', false);

						$("#id_error_display_submit").html(response.msg);
					} else if (response.status == '1') {
						$('#camera_position').val('');
						$('#txt_camera_details').val('');
						$('#ip_address').val('');
            $('#ddlViewBy').val('');

						$('#camera').val('');

            // $('#ddlViewBy').val('');
            // $('#imageid').val('');
						// $("#chk_terms").prop('checked', false);
						$('#submit').attr('disabled', false);
						$("#id_error_display_submit").html("");
						// $("#id_error_display_signin").html("Registered Successfully");
						// func_open_tab('signin')
					}
				},
				error: function(response, status, error) {
					$('#camera_position').val('');
					$('#txt_camera_details').val('');
					$('#ip_address').val('');
						$('#camera').val('');
            $('#ddlViewBy').val('');


        //   $('#ddlViewBy').val('');
        //   $('#imageid').val('');
					// $("#chk_terms").prop('checked', false);
					$('#submit').attr('disabled', false);

					$("#id_error_display_submit").html(response.msg);
				}
			});
		}
	});

      </script>




  </body>
</html>

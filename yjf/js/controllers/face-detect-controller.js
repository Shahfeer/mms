function FaceDetectController($scope, $state, $interval, $http, config, $timeout) {

  // Load config
  var cfg = config.prod;
  var bucketName = cfg.upload_bucket_name;
  var face_collection = cfg.face_collection;
  var table = cfg.ddb_table;
  // Define AWS Resources
  var region = cfg.region;
  var creds = new AWS.CognitoIdentityCredentials({
    IdentityPoolId: cfg.identity_pool_id,
  });
  $scope.inProgress = false;

  AWS.config.update({
    region: region,
    credentials: creds
  });

  var rekognition = new AWS.Rekognition({ apiVersion: '2016-6-27' });
  var docClient = new AWS.DynamoDB.DocumentClient();

  var identityId = AWS.config.credentials.identityId;

  var s3 = new AWS.S3({
    apiVersion: '2006-03-01',
    params: { Bucket: bucketName }
  });


  $scope.front = true;
  //document.getElementById('flip-button').onclick = function() { front = !front; };


  // scope Variable

  $scope.bucket_images = null;

  $scope.faces_collection = null;
  $scope.isSearching = false;
  $scope.isTrackingEmotion = false;
  $scope.faces_emotion = null;
  $scope.name = null;
  $scope.metadata = null;
  var mytimeout, emotionTracker;
  var context, dataUrl, blobData;
  var interval = 1000;
  var interval_emotion = 1000;

  function getBinary(encodedFile) {
    var base64Image = encodedFile.split("data:image/jpeg;base64,")[1];
    var binaryImg = atob(base64Image);
    var length = binaryImg.length;
    var ab = new ArrayBuffer(length);
    var ua = new Uint8Array(ab);

    for (var i = 0; i < length; i++) {
      ua[i] = binaryImg.charCodeAt(i);
    }

    var blob = new Blob([ab], {
      type: "image/jpeg"
    });

    return ab;
  }

  function toggleBtn(btn, action) {
    if (action == "loading") {
      btn.html(btn.data("loading-text"));
      //btn.attr("disabled", "disabled");
    }
    else if (action == "reset") {
      btn.html(btn.data("original-text"));
      //btn.removeAttr("disabled");
    }
  }
  $scope.toggle_search = function () {
    if (!$scope.isSearching) {
      toggleBtn($("#btn_start"), "loading");
      $scope.isSearching = !$scope.isSearching;
      $scope.faces_collection = null;
      mytimeout = $timeout(onTimeout, interval, true);
    }
    else {
      $scope.isSearching = !$scope.isSearching;
      toggleBtn($("#btn_start"), "reset");
    }
  };

  $scope.toggle_tracking = function () {
    if (!$scope.isTrackingEmotion) {
      toggleBtn($("#btn_tracking"), "loading");
      $scope.isTrackingEmotion = !$scope.isTrackingEmotion;
      $scope.faces_emotion = null;
      emotionTracker = $timeout(trackEmotions, interval_emotion);
    }
    else {
      $scope.isTrackingEmotion = !$scope.isTrackingEmotion;
      toggleBtn($("#btn_tracking"), "reset");
    }
  };


  var onTimeout = function () {

    if (!$scope.isSearching) return;

    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);
    //   target = 'face-collection/*';
    var params = {
      TableName: table
    };

    docClient.scan(params, function (err, data) {
      if (err) console.log(err, err.stack); // an error occurred
      else {
        //        console.log(data);           // successful response
        for (i = 0; i < data.Items.length; i++) {
          var target = 'face-collection/' + data.Items[i].faceId + '.jpg';
          var params = {
            SourceImage: {
              Bytes: blobData
            },
            TargetImage: {
              //     Bytes:blobData
              S3Object: {
                Bucket: bucketName,
                Name: target,
              },
            },
            SimilarityThreshold: 70
          }
          rekognition.compareFaces(params, function (err, response) {
            if (err) {
              console.log(err, err.stack); // an error occurred
            } else {
              console.log(response)
              response.FaceMatches.forEach(data => {
                let position = data.Face.BoundingBox
                let similarity = data.Similarity
                console.log(position)
                console.log(`${i}The face at: ${position.Left}, ${position.Top} matches with ${similarity} % confidence`)
              }) // for response.faceDetails
            } // if
          });
        }
      }
    });
    //    var params1 = {
    //      CollectionId: face_collection,
    //      FaceId:"cec09d17-59c9-4d6e-947a-dddfb380cafc",
    //      MaxFaces: 10
    //    };
    //    rekognition.listCollection(function(err, response){
    //    if(err){
    //    console.log(err, err.stack);
    //    }
    //    else{
    //    console.log(response)
    //    }

    //fill_metadata(FaceMatches);
    //console.log(data)

    //    rekognition.searchFaces(params, function (err, data) {
    //      if (err) {
    //        //$scope.face_collection=null;
    //        console.log(err, err.stack); // an error occurred
    //        mytimeout = $timeout(onTimeout, interval);
    //      }
    //      else {
    //        console.log(data);
    //       console.log(data.FaceMatches);           // successful response// successful response
    //        if (data && data.FaceMatches && data.FaceMatches.length) {
    //          console.log(data.FaceMatches[0].Face);
    ////                    console.log(data.FaceMatches[1].Face);
    ////          console.log(data.FaceMatches[2].Face);
    //
    //          $scope.faces_collection = data;
    //          $scope.$apply(function () { // wrapping using $scope.$apply
    //            $scope.faces_collection = fill_metadata(data.FaceMatches);
    ////            console.log($scope.faces_collection);
    //          });
    //        }
    //        else {
    //          $scope.faces_collection = null;
    //          console.log("error")
    //        }
    //        $scope.$apply();
    //        mytimeout = $timeout(onTimeout, interval);
    //      }
    //    });

    rekognition.searchFacesByImage(params, function (err, data) {
      if (err) {
        //$scope.face_collection=null;
        //console.log(err, err.stack); // an error occurred
        mytimeout = $timeout(onTimeout, interval);
      }
      else {
        //        console.log(data);           // successful response
        if (data && data.FaceMatches && data.FaceMatches.length) {
          console.log(data.FaceMatches[0].Face);
          //                    console.log(data.FaceMatches[1].Face);
          //          console.log(data.FaceMatches[2].Face);

          $scope.faces_collection = data;
          $scope.$apply(function () { // wrapping using $scope.$apply
            $scope.faces_collection = fill_metadata(data.FaceMatches);
            //            console.log($scope.faces_collection);
          });
        }
        else {
          $scope.faces_collection = null;
        }
        $scope.$apply();
        mytimeout = $timeout(onTimeout, interval);
      }
    });
  };


  var trackEmotions = function () {
    if (!$scope.isTrackingEmotion) return;

    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);

    var params = {
      Attributes: ["ALL"],
      Image: {
        Bytes: blobData
      }
    };
    rekognition.detectFaces(params, function (err, data) {
      if (err) {

        emotionTracker = $timeout(trackEmotions, interval_emotion);
      }
      else {
        //console.log($scope.faces_emotion);           // successful response

        if (data && data.FaceDetails && data.FaceDetails.length) {
          // console.log(data.FaceDetails[0].Emotions);
          $scope.faces_emotion = data;
          // console.log($scope.faces_emotion);           // successful response
        }
        else {
          $scope.faces_emotion = null;
        }
        $scope.$apply();
        emotionTracker = $timeout(trackEmotions, interval_emotion);
      }
    });

  };

  var fill_metadata = function (FaceMatches) {
    var temp_faces = [];
    var i;
    for (i = 0; i < FaceMatches.length; i++) {
      //if exists cached metadata
      var face = FaceMatches[i];
      var newArray = $scope.metadata ? $scope.metadata.filter(function (el) {
        return el.faceId == FaceMatches[i].Face.FaceId;
      }) : [];

      if (newArray && newArray.length > 0) {
        //console.log("cache hit!");
        temp_faces.push({ face: face, metadata: newArray[0] });
      }
      else {
        //or NOT exists cached metadata, get data from ddb

        var params = {
          TableName: table,
          Key: {
            faceId: FaceMatches[i].Face.FaceId
          }
        };
        docClient.get(params, function (err, data) {
          if (err) console.log(err, err.stack); // an error occurred
          else {

            //console.log(data);           // successful response
            if (data && data.Item) {
              console.log("no cached data");

              console.log(data.Item);
              temp_faces.push({ face: this.face, metadata: data.Item });
              $scope.metadata.push(data.Item);
            }
          }
        }.bind({ face: face }));
      }
    }
    return temp_faces;
  };

  // OLD Code...

  $scope.change_filename = function () {
    var files = document.getElementById('image_file').files;
    if (!(files != null && files.length > 0)) {
      $("#custom-file-control").attr("data-content", "Choose file...");
    }
    else $("#custom-file-control").attr("data-content", files[0].name);
  };

  $scope.upload_photo = function () {
    toggleBtn($("#btn_upload"), "loading");

    var files = document.getElementById('image_file').files;
    if (!(files != null && files.length > 0)) {
      toastr.error('There was no file selected.');
      toggleBtn($("#btn_upload"), "reset");
      return;
    }
    var file = files[0];
    console.log(AWS.config.credentials.identityId);
    var path_prefix = 'usercontent/' + AWS.config.credentials.identityId + "/";
    var random_number = Math.floor(Math.random() * 9000000000);
    var file_key = path_prefix + random_number + "_" + file.name;

    s3.upload({
      Key: file_key,
      Body: file,
      ACL: 'public-read'
    }, function (err, data) {
      if (err) {
        toggleBtn($("#btn_upload"), "reset");
        $scope.$apply();
        return toastr.error('Error occurred');
      }
      toastr.success('Successfully Uploaded.');
      toggleBtn($("#btn_upload"), "reset");

      //var Obj = { Key: file_key, URL: data.Location };
      //$scope.bucket_images.push(Obj);
      refreshGallery();
      $scope.$apply();
    });
    //clear file input
    document.getElementById('image_file').value = "";
    $("#custom-file-control").attr("data-content", "Choose file...");
  };

  $scope.delete_photo = function (photoKey, FaceId) {

    keys = [];
    keys.push({ Key: photoKey });
    var params = {
      Delete: { Objects: keys }
    };
    s3.deleteObjects(params, function (err, data) {
      if (err) {
        console.log(err, err.stack);
       // return toastr.error('Error Occurred ');
      }
      //toastr.success('Successfully deleted.');
      var params = {
        CollectionId: face_collection,
        FaceIds: [
          FaceId
        ]
      };
      rekognition.deleteFaces(params, function (err, data) {
        if (err) {
          console.log(err, err.stack); // an error occurred
        //  return toastr.error('Error occurred ');
        }
        else {
          //console.log(data);           // successful response
          //toastr.success('Successfully deleted');

          var params = {
            TableName: table,
            Key: {
              "faceId": FaceId
            }
          };

          docClient.delete(params, function (err, data) {
            if (err) {

              console.error("Unable to delete item. Error JSON:", JSON.stringify(err, null, 2));
              return toastr.error('Error occurred');
            } else {
              toastr.success('Successfully deleted');
              refreshGallery();
            }
          });
        }
      });
    });
  };

  refreshGallery = function () {
    var params = {
      TableName: table
    };

    docClient.scan(params, function (err, data) {
      if (err) console.log(err, err.stack); // an error occurred
      else {
        //        console.log(data);           // successful response
        if (data && data.Items) {
          $scope.metadata = data.Items;
          $scope.$apply();
        }
      }
    });
  };

  //Rekogniton API example : detectLabels
  $scope.detect_labels = function (photoKey, index) {
    if ($scope.bucket_images[index].Item) return;

    var params = {
      Image: {
        S3Object: {
          Bucket: bucketName,
          Name: photoKey
        }
      },
      MaxLabels: 10,
      MinConfidence: 0
    };

    rekognition.detectLabels(params, function (err, data) {
      if (err) {
        toastr.error('Error occurred ');
      }
      else {
        $scope.bucket_images[index].Item = data.Labels;
        //$scope.bucket_images[index].time = (response.config.responseTimestamp - response.config.requestTimestamp) / 1000;
        $scope.bucket_images[index].time = 0;
      }
      $scope.$apply();
    });
  };

  $scope.indexFaces = function ( /*filename, url*/) {
       $scope.inProgress = true;
    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);
    var image_url = null;
    var temp_face_id = null;
    var temp_name = $scope.name;
    var temp_num = $scope.mobnum;
var terms_check = $scope.checkbox_terms;
    var temp_data = $scope.data;
    var getNumber = [];
    var getName = [];
    var result;
    var user_age;
   var user_gender;
    var rek_face;
    var params = {
      TableName: table
    };
    function IsMobnum(temp_num) {
      var pattern = /[6789][0-9]{9}/;
      if (!pattern.test(temp_num)) {
        return false;
      }
      else {
        return true;
      }
    }

    docClient.scan(params, function (err, data) {
      if (err) console.log(err, err.stack); // an error occurred
      else {
        for (i = 0; i < data.Items.length; i++) {
          if (data.Items[i].number == temp_num) {
            getNumber.push(data.Items[i].number);
          }
        }
        result = getNumber.length

        var params_rek = {
          CollectionId: face_collection,
          FaceMatchThreshold: 80,
          Image: {
            Bytes: blobData
          },
          MaxFaces: 10
        };

        rekognition.searchFacesByImage(params_rek, function (err, data) {
          if (err) {
            console.log(err);
            //$scope.face_collection=null;
            //console.log(err, err.stack); // an error occurred
            mytimeout = $timeout(onTimeout, interval);
          }
          else {
            console.log(data);
            if (data && data.FaceMatches && data.FaceMatches.length) {
              console.log("If successs");
              $scope.faces_collection = data;
              $scope.$apply(function () { // wrapping using $scope.$apply
                $scope.faces_collection = fill_metadata(data.FaceMatches);
              })
            }
            else {
              $scope.faces_collection = [];
            }
            $scope.$apply();
            mytimeout = $timeout(onTimeout, interval);
          }
          console.log($scope)
          if ($scope.faces_collection.length == 0) {
            if ( temp_name == undefined || temp_num == undefined) {
              toastr.error('Kindly fill all fields.');
              $scope.inProgress = false;

            }
            else if (IsMobnum(temp_num) == false) {
              toastr.error('Kindly enter a valid mobile number.');
              $scope.inProgress = false;

            }
 else if( terms_check == undefined || terms_check == false){
              toastr.error('Kindly select the terms and conditions');
              $scope.inProgress = false;

            }
            else if (result == 0) {

              var temp_key = null;
              var params = {
                CollectionId: face_collection,
                Image: {
                  Bytes: blobData
                }
              };

              rekognition.indexFaces(params, function (err, data) {

                if (err) console.log(err, err.stack); // an error occurred
                else {
                  console.log(data);
                  if (data.FaceRecords.length >= 1) {
                    console.log("filename to write :" + data.FaceRecords[0].Face.FaceId);
                    temp_face_id = data.FaceRecords[0].Face.FaceId;
                    temp_key = "face-collection/" + data.FaceRecords[0].Face.FaceId + ".jpg";
                    s3.upload({
                      Key: temp_key,
                      ContentType: 'image/jpeg',
                      Body: blobData,
                      ACL: 'public-read'
                    }, function (err, data) {
                      if (err) {
            //            toastr.error('Error occurred ');
                      }
                      image_url = data.Location;
                   //   toastr.success('Successfully uploaded');
                     if(temp_data == undefined){
                 user_age = '';
	         user_gender = '';
               }
             else if(temp_data.age == undefined && temp_data.gender != undefined){
  user_age = '';
  user_gender = temp_data.gender;
}
else if(temp_data.age != undefined && temp_data.gender == undefined){
    user_gender = '';
    user_age = temp_data.age;
  }
  else{
    user_gender = temp_data.gender;
    user_age = temp_data.age;
  }
              
                      var params = {
                        TableName: table,
                        Item: {
                          "faceId": temp_face_id,
                          "name": temp_name,
                          "number": temp_num,
                          "age": user_age,
                          "gender": user_gender,
                          "image": image_url,
                          "key": temp_key,

                        }
                      };

                      docClient.put(params, function (err, data) {
                        if (err) {
                         toastr.error('Error occurred ');
                        }
                        else {
                      /*    toastr.success('Successfully Registered');
                          refreshGallery();
                          $scope.name = '';
                          $scope.mobnum = '';
                          $scope.data.age = '';
                          $scope.data.gender = '';
                          $scope.checkbox_terms='';
                          $scope.inProgress = false;*/
			   $http({
                            method: 'POST',
                            url: 'php/register.php',
                            headers: {'Content-Type': 'application/json'},
                              data: JSON.stringify({
                                  user_name:temp_name,
                                  user_number:temp_num,
                              })
                        }).then(function (response) {
                            console.log(response.data.status)
                            if(response.data.status==0){
                              toastr.success('Successfully Registered');
            $scope.inProgress = false;

                              refreshGallery();
                              $scope.name = '';
                              $scope.mobnum = '';
                      //        $scope.data.age = '';
                        //      $scope.data.gender = '';
                              $scope.checkbox_terms='';
//                              $scope.inProgress = false;
                                  if(temp_data == undefined){
}
else if(temp_data.age == undefined && temp_data.gender != undefined){
  $scope.data.gender = '';
}
else if(temp_data.age != undefined && temp_data.gender == undefined){

  $scope.data.age = '';
  }
  else{
  $scope.data.gender = '';
  $scope.data.age = '';
    
  }
                            }
                        });

                        }
                      });

                    });
                    //toastr.success('Successfully recognize your face.');
                  }
                  else {

                          $scope.inProgress = false;
                    toastr.error('Server resource Busy. Kindly try again after some time.');
                  }
                }
              });

            }
            else {
              toastr.error('Mobile Number already exists. Kindly try again with some other mobile number.');
                          $scope.inProgress = false;
 refreshGallery();
              $scope.mobnum = '';
            }
          }
          else {
            toastr.error('User already exists.');
	$scope.inProgress = false;
refreshGallery();
            $scope.name = '';
            $scope.mobnum = '';
          //  $scope.data.age = '';
           // $scope.data.gender = '';
            $scope.checkbox_terms='';
if(temp_data == undefined){
}
else if(temp_data.age == undefined && temp_data.gender != undefined){
  $scope.data.gender = '';
}
else if(temp_data.age != undefined && temp_data.gender == undefined){

  $scope.data.age = '';
  }
  else{
  $scope.data.gender = '';
  $scope.data.age = '';
    
  }
          }
        })

      }
    });
    // var temp_key = null;
    //           var params = {
    //             CollectionId: face_collection,
    //             Image: {
    //               Bytes: blobData
    //             }
    //           };

    //           rekognition.indexFaces(params, function (err, data) {

    //             if (err) console.log(err, err.stack); // an error occurred
    //             else {
    //               console.log(data);
    //               if (data.FaceRecords.length == 1) {
    //                 console.log("filename to write :" + data.FaceRecords[0].Face.FaceId);
    //                 temp_face_id = data.FaceRecords[0].Face.FaceId;
    //                 temp_key = "face-collection/" + data.FaceRecords[0].Face.FaceId + ".jpg";
    //                 s3.upload({
    //                   Key: temp_key,
    //                   ContentType: 'image/jpeg',
    //                   Body: blobData,
    //                   // ACL: 'public-read'
    //                 }, function (err, data) {
    //                   if (err) {
    //                     toastr.error('There was an error uploading your photo : ', err.message);
    //                   }
    //                   image_url = data.Location;
    //                   toastr.success('Successfully upload your face on S3.');

    //                   var params = {
    //                     TableName: table,
    //                     Item: {
    //                       "faceId": temp_face_id,
    //                       "name": temp_name,
    //                       "number": temp_num,
    //                       "age": temp_age,
    //                       "gender": temp_gender,
    //                       "image": image_url,
    //                       "key": temp_key,

    //                     }
    //                   };

    //                   docClient.put(params, function (err, data) {
    //                     if (err) {
    //                       toastr.error('There was an error when put metadata on DynamoDB : ', err.message);
    //                     }
    //                     else {
    //                       toastr.success('Successfully saved metadata on DynamoDB');
    //                       refreshGallery();
    //                       $scope.name = '';
    //                       $scope.mobnum = '';
    //                       $scope.data.age = '';
    //                       $scope.data.gender = '';
    //                     }
    //                   });

    //                 });
    //                 toastr.success('Successfully recognize your face.');
    //               }
    //               else {
    //                 toastr.error('Server resource Busy. Kindly try again after some time.');
    //               }
    //             }
    //           });
  };
  $scope.initCamera = function () {
    $scope.front = !$scope.front;
    var constraints = { video: { facingMode: { exact: ($scope.front ? "user" : "environment") } } };
    // Get access to the camera!
    console.log(constraints);
    video = document.querySelector('video');
    canvas = document.querySelector("canvas");
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {

      navigator.mediaDevices.getUserMedia({ video: constraints }).then
        (function (stream) {
          if ("srcObject" in video) {
            video.srcObject = stream;
          } else {
            // Avoid using this in new browsers, as it is going away.
            video.src = window.URL.createObjectURL(stream);
          }
          video.onloadedmetadata = function (e) {
            video.play();
          };

        })
        .catch(function (err) {
          console.log('Currently cannot access to your web cam.');
          //toastr.error('Currently cannot access to your web cam.');
        });
    }
  }
  angular.element(window.document.body).ready(function () {

    $scope.initCamera();
    refreshGallery();
  });
}

angular.module('face-detect-controller', []).controller('FaceDetectController', FaceDetectController);


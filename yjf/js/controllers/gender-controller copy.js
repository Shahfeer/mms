
function GenderController($scope, $state, $interval, $http, config, $timeout,$location) {

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
 $scope.isStart = false;
  $scope.isStop = true;
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

//  $scope.toggle_tracking = function () {
  //  if (!$scope.isTrackingEmotion) {
    //  toggleBtn($("#btn_tracking"), "loading");
    //  $scope.isTrackingEmotion = !$scope.isTrackingEmotion;
    //  $scope.faces_emotion = null;
//      emotionTracker = $timeout(trackEmotions(), interval_emotion);
//	emotionTracker = trackEmotions();  
  //}
   // else {
    //  $scope.isTrackingEmotion = !$scope.isTrackingEmotion;
     // toggleBtn($("#btn_tracking"), "reset");
   // }
 // };

/* $scope.toggle_tracking = function () {

      $scope.isStart = !$scope.isStart;
      $scope.isStop = !$scope.isStop;

      emotionTracker = trackEmotions();
  };
  
  $scope.toggle_stop_tracking = function () {

      $scope.isStop = !$scope.isStop;
      $scope.isStart = !$scope.isStart;

    //  emotionTracker = trackEmotions();
  }; */


  $scope.toggle_tracking = async function () {
 $scope.isStart = true;
        $scope.isStop = false;    
await $http({
      method: 'POST',
      url: 'php/url_maintain.php',
      headers: { 'Content-Type': 'application/json' },
      data: JSON.stringify({
        process: 'start',
        url_name: $location.absUrl()
      })
    }).then(async function (response) {
      if (response.data.response_code == 0) {
     //   $scope.isStart = true;
      //  $scope.isStop = false;

        emotionTracker = trackEmotions();

      }
      else {
 $scope.isStart = false;
        $scope.isStop = true;
        toastr.error(response.data.response_msg);
      }
    })

  };

  $scope.toggle_stop_tracking = async function () {

 $scope.isStop = true;
        $scope.isStart = false;
    await $http({
      method: 'POST',
      url: 'php/url_maintain.php',
      headers: { 'Content-Type': 'application/json' },
      data: JSON.stringify({
        process: 'stop',
        url_name: $location.absUrl()
      })
    }).then(async function (response) {
      if (response.data.response_code == 0) {

       // $scope.isStop = true;
       // $scope.isStart = false;

      }
      else {
 $scope.isStop = false;
        $scope.isStart = true;
        toastr.error(response.data.response_msg);
      }
    })
  };

  var onTimeout = function () {

    if (!$scope.isSearching) return;

    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);
    var attn_name;

    var params = {
      CollectionId: face_collection,
      FaceMatchThreshold: 80,
      Image: {
        Bytes: blobData
      },
      MaxFaces: 10
    };

    rekognition.searchFacesByImage(params, function (err, data) {
      if (err) {
        //$scope.face_collection=null;
        //console.log(err, err.stack); // an error occurred
        mytimeout = $timeout(onTimeout, interval);
      }
      else {
        //        console.log(data);           // successful response
        if (data && data.FaceMatches && data.FaceMatches.length) {
          console.log("*********");
          console.log(data);
          $scope.faces_collection = data;
          $scope.$apply(function () { // wrapping using $scope.$apply
            $scope.faces_collection = fill_metadata(data.FaceMatches);
            console.log($scope.faces_collection)
            console.log($scope.faces_collection[0].metadata.name);
      
          });
        }
        else {
          console.log("%%%%%%%%%");
          console.log(data);
          $scope.faces_collection = null;
        }
        $scope.$apply();
        mytimeout = $timeout(onTimeout, interval);
      }
    });
  };

 /*  var trackEmotions = function () {
    if (!$scope.isTrackingEmotion) return;

 var camera_id = $location.absUrl().split('f-gen')[1];
    console.log(camera_id);

    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);
    var deleteFace_id;
    var params = {
      DetectionAttributes: ["ALL"],
      CollectionId: face_collection,
      Image: {
        Bytes: blobData
      }
    };

    rekognition.indexFaces(params, async function (err, data) {

      if (err) console.log(err, err.stack); // an error occurred
      else {
        console.log(data);

        for (var i = 0; i < data.FaceRecords.length; i++) {
          console.log("***************");
          console.log(data.FaceRecords[i].Face.FaceId);
          deleteFace_id = data.FaceRecords[i].Face.FaceId;
         await $http({
            method: 'POST',
            url: 'php/footfall.php',
            headers: { 'Content-Type': 'application/json' },
            data: JSON.stringify({
              face_id: data.FaceRecords[i].Face.FaceId,
              gender: data.FaceRecords[i].FaceDetail.Gender.Value,
              min_age: data.FaceRecords[i].FaceDetail.AgeRange.Low,
              max_age: data.FaceRecords[i].FaceDetail.AgeRange.High,
              gender_confidence : data.FaceRecords[i].FaceDetail.Gender.Confidence,
              cam_id:camera_id
            })
          }).then(async function (response) {
            if (response.data.response_code == 0) {
              toastr.success('Success');

            }
            else {
              console.log(data.FaceRecords[i].Face.FaceId);
              var params2 = {
                CollectionId: face_collection,
                // FaceIds: [face_id_delete],
                FaceIds: [data.FaceRecords[i].Face.FaceId]
              };
              const deleteFacePromise = new Promise(function (resolve, reject) {

                rekognition.deleteFaces(params2, async function (err, data) {
                  if (err) {
                    //$scope.face_collection=null;
                    console.log(err, err.stack);
                    reject();// an error occurred
                    // mytimeout = $timeout(onTimeout, interval);
                  }
                  else {
                    resolve(data);
                  }
                });
              });
              await deleteFacePromise
                .then(async function (data) {
                  console.log("Delete successful");
                  console.log(data);

                })
                .catch(function () {
                  console.log("Delete unsuccessful");

                });
                console.log("error occurred");
                toastr.success('Error occurred.');

            }
          });

        
        }
      }
      emotionTracker = $timeout(trackEmotions, interval_emotion);

    });
  }; */


  var trackEmotions = async function () {
    console.log($scope.isStart);
console.log($scope.isStop);
	if (!$scope.isStart) return;

    var camera_id = $location.absUrl().split('entry')[1];
    console.log(camera_id);
if(camera_id == '' || camera_id == undefined || camera_id == null){
        camera_id = 9
}

    context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 640, 480);
    dataUrl = canvas.toDataURL("image/jpeg");
    blobData = getBinary(dataUrl);

    // const myCanvas = canvas.createCanvas(200, 200)
    const myCanvas = document.querySelector('video')
    const canvas1 = faceapi.createCanvasFromMedia(myCanvas)

    var deleteFace_id;

    let faceDescriptions = await faceapi.detectAllFaces(canvas1).withFaceLandmarks()
    console.log(faceDescriptions);
    if (faceDescriptions.length != 0) {

      var params = {
        DetectionAttributes: ["ALL"],
        CollectionId: face_collection,
        Image: {
          Bytes: blobData
        }
      };
      rekognition.indexFaces(params, async function (err, data) {

        if (err) console.log(err, err.stack); // an error occurred
        else {
          console.log(data);

          for (var i = 0; i < data.FaceRecords.length; i++) {
            console.log("***************");
	var y = data.FaceRecords[i].Face.BoundingBox.Top * 480;
 var x =data.FaceRecords[i].Face.BoundingBox.Left * 640;
 var w = (data.FaceRecords[i].Face.BoundingBox.Width) * 640;
var h =(data.FaceRecords[i].Face.BoundingBox.Height) * 480;

            console.log(data.FaceRecords[i].Face.FaceId);
            deleteFace_id = data.FaceRecords[i].Face.FaceId;
            await $http({
              method: 'POST',
              url: 'php/footfall.php',
              headers: { 'Content-Type': 'application/json' },
              data: JSON.stringify({
                face_id: data.FaceRecords[i].Face.FaceId,
                gender: data.FaceRecords[i].FaceDetail.Gender.Value,
                min_age: data.FaceRecords[i].FaceDetail.AgeRange.Low,
                max_age: data.FaceRecords[i].FaceDetail.AgeRange.High,
                gender_confidence: data.FaceRecords[i].FaceDetail.Gender.Confidence,
                cam_id: camera_id
              })
            }).then(async function (response) {
              if (response.data.response_code == 0) {
               toastr.success('Success');

 /*	var ccanvas = document.createElement("canvas"),
    cctx = ccanvas.getContext("2d");

ccanvas.width = 100;
ccanvas.height = 100;

cctx.drawImage(canvas, x*w,y*h,(y+w)*w,(x+h)*h);
 var dataUrlCanvas = ccanvas.toDataURL("image/jpeg");
console.log(dataUrlCanvas) */

//const regionsToExtract = [new faceapi.Rect(faceDescriptions[i].detection.box.x, faceDescriptions[i].detection.box.y , faceDescriptions[i].detection.box.width , faceDescriptions[i].detection.box.height)]
       const regionsToExtract = [new faceapi.Rect(x, y , w ,h)]
        let faceImages = await faceapi.extractFaces(canvas, regionsToExtract)

		$http({
              method: 'POST',
              url: 'php/save_image.php',
              headers: { 'Content-Type': 'application/json' },
              data: JSON.stringify({
                user_name: data.FaceRecords[i].Face.FaceId,
                user_image: faceImages[0].toDataURL()
              })
            }).then(async function (response) {
		console.log('response',response.data);
              
    if (response.data.status == 0) {
                toastr.success('Success');

              	}
		else{
		  toastr.success('Error Occured');
		}
 		});
              }
              else {
                console.log(data.FaceRecords[i].Face.FaceId);
                var params2 = {
                  CollectionId: face_collection,
                  // FaceIds: [face_id_delete],
                  FaceIds: [data.FaceRecords[i].Face.FaceId]
                };
                const deleteFacePromise = new Promise(function (resolve, reject) {

                  rekognition.deleteFaces(params2, async function (err, data) {
                    if (err) {
                      //$scope.face_collection=null;
                      console.log(err, err.stack);
                      reject();// an error occurred
                      // mytimeout = $timeout(onTimeout, interval);
                    }
                    else {
                      resolve(data);
                    }
                  });
                });
                await deleteFacePromise
                  .then(async function (data) {
                    console.log("Delete successful");
                    console.log(data);

                  })
                  .catch(function () {
                    console.log("Delete unsuccessful");

                  });
                // console.log("error occurred");
                // toastr.success('Error occurred.');

              }
            });


          }
        }
        emotionTracker = $timeout(trackEmotions, interval_emotion);

      });
    }
    else{
    console.log("&&&&&&&&&&&&&");
      emotionTracker = $timeout(trackEmotions, interval_emotion);
    }

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

//    $scope.initCamera();
  
const MODEL_URL = 'models'

    Promise.all([
      faceapi.loadSsdMobilenetv1Model(MODEL_URL),
      faceapi.loadFaceLandmarkModel(MODEL_URL),
      faceapi.loadFaceRecognitionModel(MODEL_URL),
      faceapi.loadFaceExpressionModel(MODEL_URL)
    ])
      .then(async () => {
        console.log("Loadded")
        $scope.initCamera();
      })
      .catch(async (e) => {
        console.log(e)
      })

  refreshGallery();
  });

window.addEventListener('beforeunload', (event) => {
//toastr.success('%%%%%%%%%%%%%%5');  

if($scope.isStart){
   $scope.toggle_stop_tracking()
}
//event.returnValue = false;
});
}

angular.module('gender-controller', []).controller('GenderController', GenderController);


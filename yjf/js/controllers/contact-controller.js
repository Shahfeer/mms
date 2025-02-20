function ContactController($scope, $state, $interval, $http, config) {

    // Load config
    var cfg = config.prod;
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

  
    $scope.front = true;
    $scope.bucket_images = null;
  
    $scope.name = null;
    $scope.metadata = null;
      $scope.isMetadata = false;
    $scope.isRemove = false;

    $scope.delete_photo = async function ( FaceId) {
      $scope.isRemove = true;

  await $http({
                  method: 'POST',
                  url: 'php/remove_user.php',
                  headers: { 'Content-Type': 'application/json' },
                  data: JSON.stringify({
                    face_id: FaceId
                  })
                }).then(async function (response) {
                  if (response.data.response_status == 200) {
                    toastr.success('Removed successfully')
                       refreshGallery();
                  }
                  else{
                    toastr.error('Something went wrong.')
                  }
      $scope.isRemove = false;

                })
    };
  
    refreshGallery = async function () {
      await $http({
                  method: 'POST',
                  url: 'php/select_query.php',
                  headers: { 'Content-Type': 'application/json' }
                }).then(async function (response) {
                  if (response.data.response_status == 200) {
                   $scope.metadata = response.data.result;
		if(response.data.result.length == 0){
                     $scope.isMetadata = true;

                   }
                  $scope.$apply();
                  }
                  else{
                    toastr.error('Could not load data.')
                  }
                })
    };

    angular.element(window.document.body).ready(function () {
  
      refreshGallery();
    });
  }
  
  angular.module('contact-controller', []).controller('ContactController', ContactController);


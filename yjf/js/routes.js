angular
  .module('app')
  .config(['$stateProvider', '$urlRouterProvider','$locationProvider','$ocLazyLoadProvider', function ($stateProvider, $urlRouterProvider,$locationProvider, $ocLazyLoadProvider) {
    $urlRouterProvider.otherwise('/entry1');
$locationProvider.html5Mode({
      enabled: true,
    });
    $ocLazyLoadProvider.config({
      debug: false
    });
    $stateProvider
      .state('app', {
        abstract: true,
        templateUrl: 'views/common/layouts/full.html',
        resolve: {
          loadCSS: ['$ocLazyLoad', function ($ocLazyLoad) {
            // you can lazy load CSS files
            return $ocLazyLoad.load([{
              serie: true,
              name: 'Font Awesome',
              files: ['css/font-awesome.min.css']
            }, {
              serie: true,
              name: 'Simple Line Icons',
              files: ['css/simple-line-icons.css']
            }]);
          }],
        }
      })
      .state('app.object', {
        url: '/object',
        templateUrl: 'views/main.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'ObjectDetectController as ctr'
      })
      .state('app.face', {
        url: '/f-reg',
        templateUrl: 'views/search.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'FaceDetectController as ctr'
      })
      .state('app.faceRecognize', {
        url: '/f-rec',
        templateUrl: 'views/recognize.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'FaceRecognizeController as ctr'
      })
.state('app.contact', {
        url: '/f-con',
        templateUrl: 'views/contact.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'ContactController as ctr'
      })
      .state('app.gender', {
        url: '/entry1',
        templateUrl: 'views/gender.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'GenderController as ctr'
      }) 
      .state('app.gender2', {
        url: '/entry2',
        templateUrl: 'views/gender.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'GenderController as ctr'
      })
       .state('app.gender3', {
        url: '/entry3',
        templateUrl: 'views/gender.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'GenderController as ctr'
      })
       .state('app.gender4', {
        url: '/entry4',
        templateUrl: 'views/gender.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'GenderController as ctr'
      })
      .state('app.gender5', {
        url: '/entry',
        templateUrl: 'views/gender.html',
        params: { subtitle: 'This is a Serverless Rekognition Client.' },
        controller: 'GenderController as ctr'
      });
  }]);

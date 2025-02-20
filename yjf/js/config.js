// angular.module('config', [])
//   .constant('config',
//   {
//     prod: {
//       region: 'ap-south-1',
//       upload_bucket_name: 'lakshmikanthan',
//       identity_pool_id: 'ap-south-1:18afae01-bfa9-4dc3-9958-99dde2c7aacb',
//       face_collection: 'rekognition-demo-go',
//       ddb_table: 'rekognition-demo-go'

//     }
//   }
//   );
angular.module('config', [])
  .constant('config',
  {
    prod: {
      region: 'ap-south-1',
      upload_bucket_name: 'yeejai-face-recognition',
      identity_pool_id: 'ap-south-1:12b5facb-f27d-4b9f-832d-de22002773e3',
      face_collection: 'face_recognition',
      ddb_table: 'face_recognition'

    }
  }
  );

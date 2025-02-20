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


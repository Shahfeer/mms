
//Use route.js instead of this file. 


// const md5 = require("md5");
// const db = require("../../db_connect/connect");
// const { $_super } = require("../../validation/user_registration.js");
// const moment = require("moment");
// require("dotenv").config();
// const dotenv = require('dotenv');
// var AWS = require('aws-sdk');
// dotenv.config();
// const env = process.env

// var rekognition = new AWS.Rekognition({ region: env.REGION });
// var bucketName = env.UPLOAD_BUCKET_NAME;
// var face_collection = env.FACE_COLLECTION;
// var region = env.REGION;
// var creds = new AWS.CognitoIdentityCredentials({
//   IdentityPoolId: env.IDENTITY_POOL_ID,
// });

// AWS.config.update({
//   region: region,
//   credentials: creds
// });

// var rekognition = new AWS.Rekognition({ apiVersion: '2016-6-27' });


// var s3 = new AWS.S3({
//   apiVersion: '2006-03-01',
//   params: { Bucket: bucketName }
// });


// function getBinary(encodedFile) {
//     if (!encodedFile || !encodedFile.startsWith("data:image/jpeg;base64,")) {
//         // Handle the case where encodedFile is undefined or not in expected format
//         console.log("i am naga 001");
//         return null; // or throw an error, log a message, etc.
//     }

//     var base64Image = encodedFile.split("data:image/jpeg;base64,")[1];
//     var binaryImg = atob(base64Image);
//     var length = binaryImg.length;
//     var ab = new ArrayBuffer(length);
//     var ua = new Uint8Array(ab);
//     console.log(base64Image);
//     for (var i = 0; i < length; i++) {
//         ua[i] = binaryImg.charCodeAt(i);
//     }

//     return ab;
// }

// async function userRegistration(req, res) {
//     try {
//         var day = new Date();
//         var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
//         var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
//         var current_date = today_date + ' ' + today_time;

//         var image = req.body.image;
//         // var raw_image = req.body.raw_image; 
//         var customer_name = req.body.customer_name;
//         //  console.log(customer_name)
//         var customer_mobile = req.body.customer_mobile;
//         var customer_interest = req.body.customer_interest;
//         var camera_id = req.body.camera_id;
//         var image_file = await getBinary(image);
//         var otp_verify = req.body.otp_verify;
//         var checked_email = req.body.customer_email;
//         console.log(current_date + " [registration query parameters] : " + customer_name + " , " + customer_mobile + " , " + customer_interest + " , " + camera_id + " , " + otp_verify + " , " + checked_email);
      
//         const registerFacePromise = new Promise(function (resolve, reject) {
//             var index_params = {
//                 DetectionAttributes: ["ALL"],
//                 CollectionId: face_collection,
//                 Image: {
//                     Bytes: image_file
//                 }
//             };
//             console.log(current_date + " [indexFaces parameters] : " + JSON.stringify(index_params));
//             rekognition.indexFaces(index_params, async function (err, data) {
//                 if (err) {
//                     reject(err);// an error occurred
//                 }
//                 else {
//                     resolve(data);
//                 }
//             });
//         });
        
//        const newFacePromise=new Promise(async function (data) {
//                 console.log(current_date + " [indexFaces success response] : " + JSON.stringify(data))
//                 if (data.FaceRecords.length != 0) {

//                     var y_axis = data.FaceRecords[0].Face.BoundingBox.Top * 480;
//                     var x_axis = data.FaceRecords[0].Face.BoundingBox.Left * 640;
//                     var w_axis = (data.FaceRecords[0].Face.BoundingBox.Width) * 640;
//                     var h_axis = (data.FaceRecords[0].Face.BoundingBox.Height) * 480;

//                     Date.prototype.julianDate = function () {
//                         var j = parseInt((this.getTime() - new Date('Dec 30,' + (this.getFullYear() - 1) + ' 23:00:00').getTime()) / 86400000).toString(),
//                             i = 3 - j.length;
//                         while (i-- > 0) j = 0 + j;
//                         return j
//                     };
//                     let checked_hour = moment(Date.now()).format("HH");
//                     let checked_min = moment(Date.now()).format("mm");
//                     let checked_sec = moment(Date.now()).format("ss");
//                     let random_id = Math.floor(Math.random() * 1000);

//                     var checked_gender;
//                     var checked_age_category;
//                     var checked_min_age = data.FaceRecords[0].FaceDetail.AgeRange.Low;
//                     var checked_max_age = data.FaceRecords[0].FaceDetail.AgeRange.High;
//                     var face_id = data.FaceRecords[0].Face.FaceId;
//                     var unique_user_id = `AA${camera_id}${new Date().toLocaleDateString('en', { year: '2-digit' })}${new Date().julianDate()}-${checked_hour}${checked_min}${checked_sec}-${random_id}`;

//                     var user_age = (parseInt(checked_min_age) + parseInt(checked_max_age)) / 2;
//                     if (data.FaceRecords[0].FaceDetail.Gender.Confidence >= 75) {
//                         if (data.FaceRecords[0].FaceDetail.Gender.Value == "Male") {
//                             checked_gender = 'M'
//                         }
//                         else {
//                             checked_gender = 'F'
//                         }
//                     }
//                     else {
//                         checked_gender = 'O'
//                     }
//                     if (user_age > 0 && user_age <= 12) {
//                         checked_age_category = 'KIDS'
//                     }
//                     else if (user_age > 12 && user_age <= 19) {
//                         checked_age_category = 'TEEN'
//                     }
//                     else if (user_age > 19 && user_age <= 39) {
//                         checked_age_category = 'ADULT'
//                     }
//                     else if (user_age > 39 && user_age <= 59) {
//                         checked_age_category = 'MIDDLE AGE'
//                     }
//                     else {
//                         checked_age_category = 'OLD'
//                     }

//                     const interest_list = customer_interest.split(",");
//                     const searchFacePromise = new Promise(function (resolve, reject) {
                        
//                         var params1 = {
//                             CollectionId: face_collection,
//                             FaceId: face_id,
//                             // FaceId:'0af0b31f-d0d2-4985-8608-2d5dc6f8d845',
//                             MaxFaces: 20
//                         };
//                         console.log(current_date + " [searchFaces parameters] : " + JSON.stringify(params1));

//                         rekognition.searchFaces(params1, async function (err, data) {
//                             if (err) {
//                                 //$scope.face_collection=null;
//                                 reject(err);// an error occurred
//                                 // mytimeout = $timeout(onTimeout, interval);
//                             }
//                             else {
//                                 resolve(data);
//                             }
//                         });
//                     });
                

//                     await searchFacePromise
//                         .then(async function (data) {
//                             console.log(current_date + " [searchFaces success response] : " + JSON.stringify(data))

//                             if (data.FaceMatches.length == 0) {
//                                 var current_user_id = face_id;
//                                 console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${face_id}' AND cus_mgt_status = 'Y'`)
//                                 const select_from_customer_one = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${face_id}' AND cus_mgt_status = 'Y'`);
//                                 console.log(current_date + " [select query response] : " + JSON.stringify(select_from_customer_one))
//                                    console.log(select_from_customer_one);
//                                 if (select_from_customer_one.length == 0) {
//                                     console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${face_id}'`)
//                                     const select_from_customer_all_one = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${face_id}'`);
//                                     console.log(current_date + " [select query response] : " + JSON.stringify(select_from_customer_all_one))

//                                     console.log(current_date + " [select query request] : " + `SELECT * from unique_visitors WHERE aws_faceid = '${face_id}' AND unique_visitors_status='Y'`)
//                                     const select_from_unique_one = await db.query(`SELECT * from unique_visitors WHERE aws_faceid = '${face_id}' AND unique_visitors_status='Y'`);
//                                     console.log(current_date + " [select query response] : " + JSON.stringify(select_from_unique_one))
                                  


//                                     if (select_from_customer_all_one.length == 0) {

//                                         if (select_from_unique_one.length != 0) {
//                                             console.log("....................")
//                                             console.log(current_date + " [insert query request] : " + `INSERT INTO customer_management VALUES(NULL,1,'${select_from_unique_one[0].aws_faceid}','${select_from_unique_one[0].visitor_id}','${customer_name}','${customer_mobile}','${checked_email}','${select_from_unique_one[0].visitor_gender}','${select_from_unique_one[0].min_age}','${select_from_unique_one[0].max_age}','${select_from_unique_one[0].age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                             const register_user = await db.query(`INSERT INTO customer_management VALUES(NULL,1,'${select_from_unique_one[0].aws_faceid}','${select_from_unique_one[0].visitor_id}','${customer_name}','${customer_mobile}','${checked_email}','${select_from_unique_one[0].visitor_gender}','${select_from_unique_one[0].min_age}','${select_from_unique_one[0].max_age}','${select_from_unique_one[0].age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                             console.log(current_date + " [insert query response] : " + JSON.stringify(register_user))
//                                             console.log(visitor_id);
//                                             console.log(35789253);
//                                             for (var k = 0; k < interest_list.length - 1; k++) {
//                                                 console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`)
//                                                 const insert_interest = await db.query(`INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`);
//                                                 console.log(current_date + " [insert query response] : " + JSON.stringify(insert_interest))
//                                             }
//                                             if (otp_verify == 'Y') {

//                                                 /* var temp_key = "face-collection/" + select_from_unique_one[0].aws_faceid + ".jpg";
                                              
//                                                 s3.upload({
//                                                     Key: temp_key,
//                                                     ContentType: 'image/jpeg',
//                                                     Body: image_file,
//                                                     ACL: 'public-read'
//                                                   }, function (err, data) {
//                                                     if (err) {
//                                                       console.log(current_date + " [S3 upload failed] : " +select_from_unique_one[0].aws_faceid )
//                                                     }
//                                                     console.log(current_date + " [S3 upload successful] : " +select_from_unique_one[0].aws_faceid )  
//                                                 }); */

//                                                 var send_sms = {
//                                                     method: 'post',
//                                                     url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                     headers: {
//                                                         'Cookie': php_cookie
//                                                     }
//                                                 };

//                                                 console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                 await axios(send_sms)
//                                                     .then(function (response) {
//                                                         console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                         if (response.data.status_code == 200) {
//                                                             console.log("i am naga 1");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 2");
//                                                             return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                         }
//                                                     })
//                                                     .catch(function (error) {
//                                                         console.log(current_date + " [send SMS failed response] : " + error);
//                                                         console.log("i am naga 3");
//                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                     });
//                                             }
//                                             else {
//                                                 console.log("i am naga 100");
//                                                 return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                             }

//                                         }
//                                         else {
//                                             console.log(current_date + " [insert query request] : " + `INSERT INTO customer_management VALUES(NULL,1,'${current_user_id}','${unique_user_id}','${customer_name}','${customer_mobile}','${checked_email}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                             const register_first = await db.query(`INSERT INTO customer_management VALUES(NULL,1,'${current_user_id}','${unique_user_id}','${customer_name}','${customer_mobile}','${checked_email}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                             console.log(current_date + " [insert query response] : " + JSON.stringify(register_first))

//                                             console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`)
//                                             const unique_insert_register_first = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`);
//                                             console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert_register_first))

//                                             for (var k = 0; k < interest_list.length - 1; k++) {
//                                                 console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${register_first.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`)
//                                                 const insert_interest_first = await db.query(`INSERT INTO customer_interest VALUES(NULL,${register_first.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`);
//                                                 console.log(current_date + " [insert query response] : " + JSON.stringify(insert_interest_first))
//                                             }
//                                             await save_image(image, current_user_id);

//                                             if (otp_verify == 'Y') {

//                                                 /* var temp_key1 = "face-collection/" + current_user_id + ".jpg";
                                              
//                                                 s3.upload({
//                                                     Key: temp_key1,
//                                                     ContentType: 'image/jpeg',
//                                                     Body: image_file,
//                                                     ACL: 'public-read'
//                                                   }, function (err, data) {
//                                                     if (err) {
//                                                       console.log(current_date + " [S3 upload failed] : " +current_user_id )
//                                                     }
//                                                     console.log(current_date + " [S3 upload successful] : " +current_user_id )
//                                                 }); */

//                                                 var send_sms = {
//                                                     method: 'post',
//                                                     url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                     headers: {
//                                                         'Cookie': php_cookie
//                                                     }
//                                                 };

//                                                 console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                 await axios(send_sms)
//                                                     .then(function (response) {
//                                                         console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                         if (response.data.status_code == 200) {
//                                                             console.log("i am naga 4");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 166");
//                                                             return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                         }
//                                                     })
//                                                     .catch(function (error) {
//                                                         console.log(current_date + " [send SMS failed response] : " + error);
//                                                         console.log("i am naga 177");
//                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                     });
//                                             }
//                                             else {
//                                                 console.log("i am naga 5");
//                                                 return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                             }

//                                         }
//                                         // await save_image(image,current_user_id);
//                                         //  await save_image(raw_image, current_user_id, x_axis,y_axis,w_axis,h_axis);

//                                     }
//                                     else {
//                                         console.log(current_date + " [update query request] : " + `UPDATE customer_management SET customer_name = '${customer_name}', customer_mobile = '${customer_mobile}',customer_email = '${checked_email}', customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category} , customer_interest = '${customer_interest}',cus_mgt_status = '${otp_verify}', cus_mgt_reg_date = CURRENT_TIMESTAMP WHERE aws_faceid = '${current_user_id}'`)
//                                         const update_register_user = await db.query(`UPDATE customer_management SET customer_name = '${customer_name}', customer_mobile = '${customer_mobile}', customer_email = '${checked_email}',customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}', customer_interest = '${customer_interest}',cus_mgt_status = '${otp_verify}', cus_mgt_reg_date = CURRENT_TIMESTAMP WHERE aws_faceid = '${current_user_id}'`)
//                                         console.log(current_date + " [update query response] : " + JSON.stringify(update_register_user))

//                                         const interest_of_customer = select_from_customer_all_one[0].customer_interest.split(",");
//                                         console.log(interest_of_customer);
//                                         console.log(63529786);
//                                         for (var c = 0; c < interest_of_customer.length - 1; c++) {
//                                             console.log(current_date + " [update query request] : " + `UPDATE customer_interest SET cus_int_status = 'N' WHERE customer_id ='${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_of_customer[c]}'`)
//                                             const update_interest = await db.query(`UPDATE customer_interest SET cus_int_status = 'N' WHERE customer_id ='${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_of_customer[c]}'`)
//                                             console.log(current_date + " [update query response] : " + JSON.stringify(update_interest))

//                                         }
//                                         for (var r = 0; r < interest_list.length - 1; r++) {

//                                             console.log(current_date + " [select query request] : " + `SELECT * from customer_interest WHERE customer_id = '${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`)
//                                             const select_interest = await db.query(`SELECT * from customer_interest WHERE customer_id = '${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                             console.log(current_date + " [select query response] : " + JSON.stringify(select_interest))

//                                             if (select_interest.length == 0) {

//                                                 console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${select_from_customer_all_one[0].customer_id},${interest_list[r]},'Y',CURRENT_TIMESTAMP)`)
//                                                 const insert_interests_user = await db.query(`INSERT INTO customer_interest VALUES(NULL,${select_from_customer_all_one[0].customer_id},${interest_list[r]},'Y',CURRENT_TIMESTAMP)`);
//                                                 console.log(current_date + " [insert query response] : " + JSON.stringify(insert_interests_user))
//                                             }
//                                             else {
//                                                 console.log(current_date + " [update query request] : " + `UPDATE customer_interest SET cus_int_status = 'Y' WHERE customer_id ='${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                                 const update_interest_2 = await db.query(`UPDATE customer_interest SET cus_int_status = 'Y' WHERE customer_id ='${select_from_customer_all_one[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                                 console.log(current_date + " [update query response] : " + JSON.stringify(update_interest_2))

//                                             }
//                                         }

//                                         if (select_from_unique_one.length != 0) {

//                                             console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${current_user_id}'`)
//                                             const update_unique_user = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid ='${current_user_id}'`)
//                                             console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user))
//                                             // return { response_code: 0, response_status: 200, response_msg: 'Success' };


//                                             if (otp_verify == 'Y') {
//                                                 var send_sms = {
//                                                     method: 'post',
//                                                     url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                     headers: {
//                                                         'Cookie': php_cookie
//                                                     }
//                                                 };

//                                                 console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                 await axios(send_sms)
//                                                     .then(function (response) {
//                                                         console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                         if (response.data.status_code == 200) {
//                                                             console.log("i am naga 7");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 188");
//                                                             return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                         }
//                                                     })
//                                                     .catch(function (error) {
//                                                         console.log(current_date + " [send SMS failed response] : " + error);
//                                                         console.log("i am naga 8");
//                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                     });
//                                             }
//                                             else {
//                                                 console.log("i am naga 199");
//                                                 return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                             }

//                                         }
//                                         else {
//                                             console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`)
//                                             const unique_insert_register_first = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`);
//                                             console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert_register_first))
//                                             // return { response_code: 0, response_status: 200, response_msg: 'Success' };

//                                             if (otp_verify == 'Y') {
//                                                 var send_sms = {
//                                                     method: 'post',
//                                                     url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                     headers: {
//                                                         'Cookie': php_cookie
//                                                     }
//                                                 };

//                                                 console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                 await axios(send_sms)
//                                                     .then(function (response) {
//                                                         console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                         if (response.data.status_code == 200) {
//                                                             console.log("i am naga 201");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 202");
//                                                             return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                         }
//                                                     })
//                                                     .catch(function (error) {
//                                                         console.log(current_date + " [send SMS failed response] : " + error);
//                                                         console.log("i am naga 203");
//                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                     });
//                                             }
//                                             else {
//                                                 console.log("i am naga 204");
//                                                 return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                             }

//                                         }

//                                     }


//                                 }
//                                 else {
//                                     console.log("i am naga 205");
//                                     var test = "okk"
//                                     return test;
//                                   return { response_code: 1, response_status: 201, response_msg: 'User Already exists' };

//                                 }
//                             }
//                             else {
//                                 //  for (var k = 0; k < data.FaceMatches.length; k++) {
//                                 var current_user_id = data.FaceMatches[0].Face.FaceId;
//                                 console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}' AND cus_mgt_status = 'Y'`)
//                                 const select_from_customer = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}' AND cus_mgt_status = 'Y'`);
//                                 console.log(current_date + " [select query response] : " + JSON.stringify(select_from_customer))

//                                 if (select_from_customer.length == 0) {

//                                     console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}'`)
//                                     const select_from_customer_all = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}'`);
//                                     console.log(current_date + " [select query response] : " + JSON.stringify(select_from_customer_all))

//                                     console.log(current_date + " [select query request] : " + `SELECT * from unique_visitors WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}' AND unique_visitors_status='Y'`)
//                                     const select_from_unique = await db.query(`SELECT * from unique_visitors WHERE aws_faceid = '${data.FaceMatches[0].Face.FaceId}' AND unique_visitors_status='Y'`);
//                                     console.log(current_date + " [select query response] : " + JSON.stringify(select_from_unique))

//                                     // if (select_from_unique.length != 0) {
//                                     var params5 = {
//                                         CollectionId: face_collection,
//                                         // FaceIds: [face_id_delete],
//                                         FaceIds: [face_id]
//                                     };
//                                     try {
//                                         const deleteFacePromise = new Promise(function (resolve, reject) {
//                                             console.log(current_date + " [deleteFaces parameters] : " + JSON.stringify(params5));

//                                             rekognition.deleteFaces(params5, async function (err, data) {
//                                                 if (err) {
//                                                     //$scope.face_collection=null;
//                                                     reject(err);// an error occurred
//                                                     // mytimeout = $timeout(onTimeout, interval);
//                                                 }
//                                                 else {
//                                                     resolve(data);
//                                                 }
//                                             });
//                                         });
//                                         await deleteFacePromise
//                                             .then(async function (data) {
//                                                 console.log(current_date + " [deleteFaces success response] : " + JSON.stringify(data))
//                                                 if (select_from_customer_all.length == 0) {

//                                                     if (select_from_unique.length != 0) {
//                                                         console.log(current_date + " [insert query request] : " + `INSERT INTO customer_management VALUES(NULL,1,'${select_from_unique[0].aws_faceid}','${select_from_unique[0].visitor_id}','${customer_name}','${customer_mobile}','${checked_email}','${select_from_unique[0].visitor_gender}','${select_from_unique[0].min_age}','${select_from_unique[0].max_age}','${select_from_unique[0].age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                                         const register_user = await db.query(`INSERT INTO customer_management VALUES(NULL,1,'${select_from_unique[0].aws_faceid}','${select_from_unique[0].visitor_id}','${customer_name}','${customer_mobile}','${checked_email}','${select_from_unique[0].visitor_gender}','${select_from_unique[0].min_age}','${select_from_unique[0].max_age}','${select_from_unique[0].age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                                         console.log(current_date + " [insert query response] : " + JSON.stringify(register_user))

//                                                         for (var k = 0; k < interest_list.length - 1; k++) {
//                                                             console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`)
//                                                             const interest_user = await db.query(`INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`);
//                                                             console.log(current_date + " [insert query response] : " + JSON.stringify(interest_user))
//                                                         }


//                                                         if (otp_verify == 'Y') {
//                                                             var send_sms = {
//                                                                 method: 'post',
//                                                                 url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                                 headers: {
//                                                                     'Cookie': php_cookie
//                                                                 }
//                                                             };

//                                                             console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                             await axios(send_sms)
//                                                                 .then(function (response) {
//                                                                     console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                                     if (response.data.status_code == 200) {
//                                                                         console.log("i am naga 9");
//                                                                         return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                                     }
//                                                                     else {
//                                                                         console.log("i am naga 10");
//                                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                                     }
//                                                                 })
//                                                                 .catch(function (error) {
//                                                                     console.log(current_date + " [send SMS failed response] : " + error);
//                                                                     console.log("i am naga 11");
//                                                                     return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                                 });
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 12");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }

//                                                     }
//                                                     else {
//                                                         console.log(current_date + " [insert query request] : " + `INSERT INTO customer_management VALUES(NULL,1,'${current_user_id}','${unique_user_id}','${customer_name}','${customer_mobile}','${checked_email}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                                         const register_first = await db.query(`INSERT INTO customer_management VALUES(NULL,1,'${current_user_id}','${unique_user_id}','${customer_name}','${customer_mobile}','${checked_email}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${customer_interest}','${otp_verify}',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)`)
//                                                         console.log(current_date + " [insert query response] : " + JSON.stringify(register_first))

//                                                         console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`)
//                                                         const unique_insert_register_first = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`);
//                                                         console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert_register_first))

//                                                         for (var k = 0; k < interest_list.length - 1; k++) {
//                                                             console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${register_first.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`)
//                                                             const interest_user_2 = await db.query(`INSERT INTO customer_interest VALUES(NULL,${register_first.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`);
//                                                             console.log(current_date + " [insert query response] : " + JSON.stringify(interest_user_2))
//                                                         }
//                                                         await save_image(image, current_user_id);
//                                                         if (otp_verify == 'Y') {
//                                                             var send_sms = {
//                                                                 method: 'post',
//                                                                 url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                                 headers: {
//                                                                     'Cookie': php_cookie
//                                                                 }
//                                                             };

//                                                             console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                             await axios(send_sms)
//                                                                 .then(function (response) {
//                                                                     console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                                     if (response.data.status_code == 200) {
//                                                                         console.log("i am naga 13");
//                                                                         return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                                     }
//                                                                     else {
//                                                                         console.log("i am naga 14");
//                                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                                     }
//                                                                 })
//                                                                 .catch(function (error) {
//                                                                     console.log(current_date + " [send SMS failed response] : " + error);
//                                                                     console.log("i am naga 15");
//                                                                     return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                                 });
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 16");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }

//                                                     }
//                                                     //await save_image(raw_image, current_user_id, x_axis,y_axis,w_axis,h_axis);

//                                                     //await save_image(image,current_user_id);
//                                                 }
//                                                 else {
//                                                     console.log(current_date + " [update query request] : " + `UPDATE customer_management SET customer_name = '${customer_name}', customer_mobile = '${customer_mobile}',customer_email = '${checked_email}', customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category} , customer_interest = '${customer_interest}',cus_mgt_status = '${otp_verify}', cus_mgt_reg_date = CURRENT_TIMESTAMP WHERE aws_faceid = '${current_user_id}'`)
//                                                     const update_register_user = await db.query(`UPDATE customer_management SET customer_name = '${customer_name}', customer_mobile = '${customer_mobile}', customer_email = '${checked_email}',customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}', customer_interest = '${customer_interest}',cus_mgt_status = '${otp_verify}', cus_mgt_reg_date = CURRENT_TIMESTAMP WHERE aws_faceid = '${current_user_id}'`)
//                                                     console.log(current_date + " [update query response] : " + JSON.stringify(update_register_user))

//                                                     /* for(var k=0; k<interest_list.length-1;k++){
//                                                                         console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`)
//                                                                     const interest_user = await db.query(`INSERT INTO customer_interest VALUES(NULL,${register_user.insertId},${interest_list[k]},'Y',CURRENT_TIMESTAMP)`);
//                                                                     console.log(current_date + " [insert query response] : " + JSON.stringify(interest_user))
//                                                                             } */
//                                                     const interest_of_customer = select_from_customer_all[0].customer_interest.split(",");
                                                    
//                                                     for (var c = 0; c < interest_of_customer.length - 1; c++) {
//                                                         console.log(current_date + " [update query request] : " + `UPDATE customer_interest SET cus_int_status = 'N' WHERE customer_id ='${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_of_customer[c]}'`)
//                                                         const update_interest_5 = await db.query(`UPDATE customer_interest SET cus_int_status = 'N' WHERE customer_id ='${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_of_customer[c]}'`)
//                                                         console.log(current_date + " [update query response] : " + JSON.stringify(update_interest_5))

//                                                     }
//                                                     for (var r = 0; r < interest_list.length - 1; r++) {

//                                                         console.log(current_date + " [select query request] : " + `SELECT * from customer_interest WHERE customer_id = '${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`)
//                                                         const select_interest_2 = await db.query(`SELECT * from customer_interest WHERE customer_id = '${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                                         console.log(current_date + " [select query response] : " + JSON.stringify(select_interest_2))

//                                                         if (select_interest_2.length == 0) {

//                                                             console.log(current_date + " [insert query request] : " + `INSERT INTO customer_interest VALUES(NULL,${select_from_customer_all[0].customer_id},${interest_list[r]},'Y',CURRENT_TIMESTAMP)`)
//                                                             const insert_interests_user_2 = await db.query(`INSERT INTO customer_interest VALUES(NULL,${select_from_customer_all[0].customer_id},${interest_list[r]},'Y',CURRENT_TIMESTAMP)`);
//                                                             console.log(current_date + " [insert query response] : " + JSON.stringify(insert_interests_user_2))
//                                                         }
//                                                         else {
//                                                             console.log(current_date + " [update query request] : " + `UPDATE customer_interest SET cus_int_status = 'Y' WHERE customer_id ='${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                                             const update_interest_6 = await db.query(`UPDATE customer_interest SET cus_int_status = 'Y' WHERE customer_id ='${select_from_customer_all[0].customer_id}' AND visitor_interest_id = '${interest_list[r]}'`);
//                                                             console.log(current_date + " [update query response] : " + JSON.stringify(update_interest_6))

//                                                         }
//                                                     }

//                                                     if (select_from_unique.length != 0) {

//                                                         console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${current_user_id}'`)
//                                                         const update_unique_user = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid ='${current_user_id}'`)
//                                                         console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user))
//                                                         // return { response_code: 0, response_status: 200, response_msg: 'Success' };

//                                                         if (otp_verify == 'Y') {
//                                                             var send_sms = {
//                                                                 method: 'post',
//                                                                 url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                                 headers: {
//                                                                     'Cookie': php_cookie
//                                                                 }
//                                                             };

//                                                             console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                             await axios(send_sms)
//                                                                 .then(function (response) {
//                                                                     console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                                     if (response.data.status_code == 200) {
//                                                                         console.log("i am naga 207");
//                                                                         return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                                     }
//                                                                     else {
//                                                                         console.log("i am naga 208");
//                                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                                     }
//                                                                 })
//                                                                 .catch(function (error) {
//                                                                     console.log(current_date + " [send SMS failed response] : " + error);
//                                                                     console.log("i am naga 209");
//                                                                     return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                                 });
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 210");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }

//                                                     }
//                                                     else {
//                                                         console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`)
//                                                         const unique_insert_register_first = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${current_user_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y',CURRENT_TIMESTAMP)`);
//                                                         console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert_register_first))
//                                                         // return { response_code: 0, response_status: 200, response_msg: 'Success' };

//                                                         if (otp_verify == 'Y') {
//                                                             var send_sms = {
//                                                                 method: 'post',
//                                                                 url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${customer_mobile}&message=${register_msg}`,
//                                                                 headers: {
//                                                                     'Cookie': php_cookie
//                                                                 }
//                                                             };

//                                                             console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))

//                                                             await axios(send_sms)
//                                                                 .then(function (response) {
//                                                                     console.log(current_date + " [send SMS response] : " + JSON.stringify(response.data));
//                                                                     if (response.data.status_code == 200) {
//                                                                         console.log("i am naga 16");
//                                                                         return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                                     }
//                                                                     else {
//                                                                         console.log("i am naga 17");
//                                                                         return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };
//                                                                     }
//                                                                 })
//                                                                 .catch(function (error) {
//                                                                     console.log(current_date + " [send SMS failed response] : " + error);
//                                                                     console.log("i am naga 18");
//                                                                     return { response_code: 1, response_status: 201, response_msg: 'SMS not sent' };

//                                                                 });
//                                                         }
//                                                         else {
//                                                             console.log("i am naga 211");
//                                                             return { response_code: 0, response_status: 200, response_msg: 'Success' };
//                                                         }

//                                                     }

//                                                 }
//                                             })
//                                             .catch(function (err) {
//                                                 console.log(current_date + " [deleteFaces failed response] : " + err)
//                                                 console.log("i am naga 19");
//                                                 return { response_code: 1, response_status: 201, response_msg: 'Error occurred ' };

//                                             });

//                                     }
//                                     catch (e) {
//                                         console.log(current_date + " [deleteFaces failed response] : " + e)
//                                         console.log("i am naga 20");
//                                         return { response_code: 1, response_status: 201, response_msg: 'Error occurred ' };
//                                     }

//                                 }
//                                 else {
//                                     var params7 = {
//                                         CollectionId: face_collection,
//                                         // FaceIds: [face_id_delete],
//                                         FaceIds: [face_id]
//                                     };

//                                     const deleteFacePromise = new Promise(function (resolve, reject) {
//                                         console.log(current_date + " [deleteFaces parameters] : " + JSON.stringify(params7));

//                                         rekognition.deleteFaces(params7, async function (err, data) {
//                                             if (err) {
//                                                 //$scope.face_collection=null;
//                                                 reject(err);// an error occurred
//                                                 // mytimeout = $timeout(onTimeout, interval);
//                                             }
//                                             else {
//                                                 resolve(data);
//                                             }
//                                         });
//                                     });
//                                     await deleteFacePromise
//                                         .then(async function (data) {
//                                             console.log(current_date + " [deleteFaces success response] : " + JSON.stringify(data))
//                                             console.log(current_date + " : User already exists")
//                                             console.log("i am naga 21");
//                                             return { response_code: 1, response_status: 201, response_msg: 'User Already exists' };
//                                         })
//                                         .catch(function (err) {
//                                             console.log(current_date + " [deleteFaces failed response] : " + err)
//                                             console.log("i am naga 22");
//                                             return { response_code: 1, response_status: 201, response_msg: 'Error occurred ' };

//                                         });
//                                 }
//                                 // }
//                             }
//                         })
//                         .catch(function (err) {
//                             console.log(current_date + " [searchFacePromise failed response] : " + err)
//                             console.log("i am naga 23");
//                             return { response_code: 1, response_status: 201, response_msg: 'Error occurred ' };

//                         });
//                 }
//                 else {
//                     console.log(current_date + " [indexFaces response] : No faces found")
//                     console.log("i am naga 24");
//                     return { response_code: 1, response_status: 201, response_msg: 'No faces found.'};

//                 }

//             })
//             .catch(function (error) {
//                 console.log(current_date + " [indexFaces failed response] : " + error)
//                 console.log("i am naga 25");
//                 return { response_code: 1, response_status: 201, response_msg: 'Error occurred'};
//             });
//     }
//     catch (e) {
//         console.log(e)
//         console.log(current_date + " [ failed response] : " + e)
//         console.log("i am naga 26");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred', e };
//     }
// };

// async (req, res) => {
//     getFilesizeInBytes();

//     try {
//         var url_name = req.body.url_name;

//         console.log(current_date + " [select query request] : " + `SELECT * from url_status WHERE url_name = '${url_name}' AND url_status = 'N'`)
//         const select_url = await db.query(`SELECT * from url_status WHERE url_name = '${url_name}' AND url_status = 'N'`);
//         console.log(current_date + " [select query response] : " + JSON.stringify(select_url))

//         if (select_url.length == 0) {
//             console.log(current_date + " [update query request] : " + `UPDATE url_status SET url_status ='N' WHERE url_name = '${url_name}'`)
//             const url_update = await db.query(`UPDATE url_status SET url_status ='N' WHERE url_name = '${url_name}'`)
//             console.log(current_date + " [update query response] : " + JSON.stringify(url_update))

//             console.log(current_date + " [urlActivate success response] : Success " + url_name)
//             console.log("i am naga 111");
//             return { response_code: 0, response_status: 200, response_msg: 'Success' };

//         }
//         else {
//             console.log(current_date + " [urlActivate failed response] : Resource already in use " + url_name)
//             console.log("i am naga 212");
//             return { response_code: 1, response_status: 201, response_msg: 'Resource already in use. ' };

//         }

//     }
//     catch (e) {
//         console.log(current_date + " [urlActivate failed response] : " + e)
//         console.log("i am naga 213");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ' };
//     }
// };

// async (req, res) => {
//     getFilesizeInBytes();

//     try {
//         var url_name = req.body.url_name;

//         // console.log(current_date + " [select query request] : " + `SELECT * from url_status WHERE url_name = '${url_name}' AND url_status = 'Y'`)
//         // const select_url = await db.query(`SELECT * from url_status WHERE url_name = '${url_name}' AND url_status = 'Y'`);
//         // console.log(current_date + " [select query response] : " + JSON.stringify(select_url))

//         // if(select_url.length == 0){
//         console.log(current_date + " [update query request] : " + `UPDATE url_status SET url_status ='Y' WHERE url_name = '${url_name}'`)
//         const url_update_stop = await db.query(`UPDATE url_status SET url_status ='Y' WHERE url_name = '${url_name}'`)
//         console.log(current_date + " [update query response] : " + JSON.stringify(url_update_stop))

//         console.log(current_date + " [urlInActivate success response] : Success " + url_name)
//         console.log("i am naga 214");
//         return { response_code: 0, response_status: 200, response_msg: 'Success' };

//         // }
//         // else{
//         //   console.log(current_date + " [urlInActivate failed response] : URL not available" )
//         //    res.json({ response_code: 1, response_status: 201, response_msg: 'URL not available. ' });

//         // }

//     }
//     catch (e) {
//         console.log(current_date + " [ urlInActivate failed response] : " + e)
//         console.log("i am naga 215");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ' };
//     }
// };

// function getFiles(dir, files_) {
//     files_ = files_ || [];
//     var files = fs.readdirSync(dir);
//     for (var i in files) {
//         var name = dir + '/' + files[i];
//         if (fs.statSync(name).isDirectory()) {
//             getFiles(name, files_);
//         } else {
//             if (name.search(".jpg") != -1) {
//                 files_.push(name);
//             }

//         }
//     }
//     console.log("i am naga 217");
//     return files_;
// }

// async (req, res) => {
//     getFilesizeInBytes();
//     let curr_year = moment(Date.now()).format("YYYY");
//     let curr_month = moment(Date.now()).format("MM");
//     let curr_date = moment(Date.now()).format("DD");

//     var get_folder = `/var/www/html/mms/uploads/visitor_list_${curr_year}${curr_month}${curr_date}`;

//     try {
//         /*    fs.readFile(get_folder, 'utf8', function (err, data) {
//               if (err) {
//                 console.log(err);
//                   console.log(current_date + " [ Get images from local failed response] : " + err)
//                   res.json({ response_code: 1, response_status: 201, response_msg: 'Error occurred. ',err });
      
//               }
//               if(data){
//                 console.log(current_date + " [ Get images from local success response] : " + data)
//                 res.json({ response_code: 0, response_status: 200, response_msg: ' Success. ',data });
//               }
            
//           }); */

//         var files = getFiles(get_folder);
//         // console.log(current_date + " [ Get images from local success response] : " + files)
//         console.log("i am naga 218");
//         return { response_code: 0, response_status: 200, response_msg: ' Success. ', files };

//     }
//     catch (e) {
//         console.log(current_date + " [ Get images from local failed response] : " + e)
//         console.log("i am naga 122");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ' };
//     }
// };

// async (req, res) => {
//     getFilesizeInBytes();
//     var remove_face_id = req.body.face_id;

//     try {
//         console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${remove_face_id}' AND cus_mgt_status = 'Y'`)
//         const select_user = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${remove_face_id}' AND cus_mgt_status = 'Y'`);
//         console.log(current_date + " [select query response] : " + JSON.stringify(select_user))

//         if (select_user.length != 0) {
//             console.log(current_date + " [update query request] : " + `UPDATE customer_management SET cus_mgt_status ='N' WHERE aws_faceid = '${remove_face_id}'`)
//             const update_user = await db.query(`UPDATE customer_management SET cus_mgt_status ='N' WHERE aws_faceid = '${remove_face_id}'`)
//             console.log(current_date + " [update query response] : " + JSON.stringify(update_user))

//             console.log(current_date + " [remove user success response] : Success")
//             console.log("i am naga 133");
//             return { response_code: 0, response_status: 200, response_msg: 'Success' };

//         }
//         else {
//             console.log(current_date + " [remove user failed response] : User not available")
//             console.log("i am naga 219");
//             return { response_code: 1, response_status: 201, response_msg: 'User not available. ' };

//         }
//     }
//     catch (e) {
//         console.log(current_date + " [ remove user failed response] : " + e)
//         console.log("i am naga 220");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ', e };
//     }
// };

// async (req, res) => {
//     getFilesizeInBytes();

//     try {
//         console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE cus_mgt_status = 'Y'`)
//         const select_user = await db.query(`SELECT * from customer_management WHERE cus_mgt_status = 'Y'`);
//         console.log(current_date + " [select query response] : " + JSON.stringify(select_user))
//         console.log("i am naga 144");
//         return { response_code: 0, response_status: 200, response_msg: 'Success', result: select_user };

//     }

//     catch (e) {
//         console.log(current_date + " [ select query failed response] : " + e)
//         console.log("i am naga 155");
//         return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ', e };
//     }
// };

//  async (req, res) => {
//     getFilesizeInBytes();
//   let curr_year = moment(Date.now()).format("YYYY");
//       let curr_month = moment(Date.now()).format("MM");
//       let curr_date = moment(Date.now()).format("DD");
      
//       var get_folder = `/w/html/mms/uploads/visitor_list_${curr_year}${curr_month}${curr_date}`;
  
//       try {
//   /*    fs.readFile(get_folder, 'utf8', function (err, data) {
//         if (err) {
//           console.log(err);
//             console.log(current_date + " [ Get images from local failed response] : " + err)
//             res.json({ response_code: 1, response_status: 201, response_msg: 'Error occurred. ',err });

//         }
//         if(data){
//           console.log(current_date + " [ Get images from local success response] : " + data)
//           res.json({ response_code: 0, response_status: 200, response_msg: ' Success. ',data });
//         }
      
//     }); */

//   var files = getFiles(get_folder);
//         // console.log(current_date + " [ Get images from local success response] : " + files)
//         console.log("i am naga 2111");
//           return{ response_code: 0, response_status: 200, response_msg: ' Success. ',files };
  
//     }
//     catch (e) {
//       console.log(current_date + " [ Get images from local failed response] : " + e)
//       console.log("i am naga 166");
//       return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ' };
//     }
//   };

  
//   async (req, res) => {
//     getFilesizeInBytes();
//     var remove_face_id = req.body.face_id;

//       try {
//         console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${remove_face_id}' AND cus_mgt_status = 'Y'`)
//         const select_user = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${remove_face_id}' AND cus_mgt_status = 'Y'`);
//         console.log(current_date + " [select query response] : " + JSON.stringify(select_user))
    
//         if(select_user.length != 0){
//           console.log(current_date + " [update query request] : " + `UPDATE customer_management SET cus_mgt_status ='N' WHERE aws_faceid = '${remove_face_id}'`)
//           const update_user = await db.query(`UPDATE customer_management SET cus_mgt_status ='N' WHERE aws_faceid = '${remove_face_id}'`)
//           console.log(current_date + " [update query response] : " + JSON.stringify(update_user))
    
//           console.log(current_date + " [remove user success response] : Success" );
//           console.log("i am naga 177");
//           return { response_code: 0, response_status: 200, response_msg: 'Success' };
    
//         }
//         else{
//           console.log(current_date + " [remove user failed response] : User not available" )
//           console.log("i am naga 2223");
//           return { response_code: 1, response_status: 201, response_msg: 'User not available. ' };
    
//         }
//     }
//     catch (e) {
//       console.log(current_date + " [ remove user failed response] : " + e)
//       console.log("i am naga 22068");
//       return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ',e };
//     }
//   };
//    async (req, res) => {
//     getFilesizeInBytes();    
  
//       try {
//         console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE cus_mgt_status = 'Y'`)
//         const select_user = await db.query(`SELECT * from customer_management WHERE cus_mgt_status = 'Y'`);
//         console.log(current_date + " [select query response] : " + JSON.stringify(select_user))
//         return { response_code: 0, response_status: 200, response_msg: 'Success', result : select_user };

//     }
//     catch (e) {
//       console.log(current_date + " [ select query failed response] : " + e)
//       console.log("i am naga 22098");
//       return { response_code: 1, response_status: 201, response_msg: 'Error occurred. ',e };
//     }
//   };

  
// module.exports = {
//     userRegistration
// };

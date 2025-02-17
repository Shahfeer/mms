const https = require("https");
const express = require("express");
const dotenv = require('dotenv');
const dynamic_db = require("./db_connect/dynamic_connect");
const validator = require('./validation/middleware')
const db = require("./db_connect/connect");

dotenv.config();
// const router = express.Router();
var cors = require("cors");
var axios = require('axios');
const nodemailer = require('nodemailer');

const mime = require('mime');
// Database Connections
const app = express();
const port = 10017;


const bodyParser = require('body-parser');
const fs = require('fs');
const log_file = require('./logger')
const logger = log_file.logger;
const logger_all = log_file.logger_all;


const httpServer = https.createServer(app);
const io = require('socket.io')(httpServer, {
	cors: {
		origin: "*",
	},
});

// Process Validations
const Logout = require("./logout/route");
const Login = require("./login/route");
const List = require("./api_request/list/list_route");
const valid_user = require("./validation/valid_user_middleware");
const user = require("./api_request/user/route");
const report=require("./api_request/report/route")
const moment = require('moment');



// Current Date and Time
// var today = new Date().toLocaleString("en-IN", {timeZone: "Asia/Kolkata"});
var day = new Date();
var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
var current_date = today_date + ' ' + today_time;
var current_year = day.getFullYear();

//new collection
var AWS = require('aws-sdk');

const env = process.env;
var face_collection =env.FACE_COLLECTION;
var rekognition = new AWS.Rekognition({ apiVersion: '2016-6-27' });
var rekognition = new AWS.Rekognition({ region: env.REGION });
var intervalTime = env.INTERVALTIME;
var intervalTimeFormat = env.INTERVALTIMEFORMAT;
var intervalMsgTime = env.INTERVALMSGTIME;
var intervalMsgTimeFormat = env.INTERVALMSGTIMEFORMAT

var src_folder = "/var/www/html/mms/uploads/visitor_list_runtime/";
//var dest_folder = `/var/www/html/mms/uploads/visitor_list_/`;
var reg_folder = "/var/www/html/mms/uploads/register/";



// Log file Generation based on the current date
var util = require('util');
var filename = 'logs/' + today_date + '.log';
var exec = require('child_process').exec;


 app.use(cors());
app.use(express.json({ limit: '50mb' }));
app.use(
	express.urlencoded({
		extended: true,
		limit: '50mb'
	})
);

// Allows you to send emits from express
app.use(function (request, response, next) {
	request.io = io;
	next();
});

app.get("/", async (req, res) => {
	console.log(day)
	res.json({ message: "okkkk" });
});
function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }));

// parse application/json
app.use(bodyParser.json());

// API initialzation
app.use("/login", Login);
app.use("/list", List);
app.use("/logout", Logout);
app.use("/user", user);
app.use("/report", report);

function getBinary(encodedFile) {
  if (!encodedFile || !encodedFile.startsWith("data:image/jpeg;base64,")) {
      // Handle the case where encodedFile is undefined or not in expected format
      return null; // or throw an error, log a message, etc.
  }

  var base64Image = encodedFile.split("data:image/jpeg;base64,")[1];
  var binaryImg = atob(base64Image);
  var length = binaryImg.length;
  var ab = new ArrayBuffer(length);
  var ua = new Uint8Array(ab);

  for (var i = 0; i < length; i++) {
      ua[i] = binaryImg.charCodeAt(i);
  }

  return ab;
}

function getFilesizeInBytes() {
	if (fs.existsSync(filename)) {
	  var stats = fs.statSync(filename);
	  var fileSizeInBytes = stats.size;
	  var fileSizeInMegabytes = fileSizeInBytes / (1024 * 1024);
	  if (fileSizeInMegabytes > 20) {
		fs.rename(filename, 'logs/' + current_date + '_old.log', function (err) {
		  if (err) console.log('ERROR: ' + err);
		});
  
		 var log_file = fs.createWriteStream(filename, { flags: 'a' });
		log_stdout = process.stdout;
		console.log = function (d) { //
		  log_file.write(util.format(d) + '\n');
		  log_stdout.write(util.format(d) + '\n');
		};
	  } else {
		// console.log("Below Size : "+fileSizeInMegabytes);
	  }
	} else {
	  log_file = fs.createWriteStream(filename, { flags: 'a' });
	  // log_file = fs.chmodSync(filename, '755');
	  log_stdout = process.stdout;
	  console.log = function (d) { //
		log_file.write(util.format(d) + '\n');
		log_stdout.write(util.format(d) + '\n');
	  };
	}
  }

async function footfallValidation() {

    // check log file size
    getFilesizeInBytes();
    try {
      var day = new Date();
      var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
      var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
      var current_date = today_date + ' ' + today_time;

      var today_date_now = moment(Date.now()).format("YYYY-MM-DD")
    
  let curr_year = moment(Date.now()).format("YYYY");
        let curr_month = moment(Date.now()).format("MM");
        let curr_date = moment(Date.now()).format("DD");

    var dest_folder = `/var/www/html/mms/uploads/visitor_list_${curr_year}${curr_month}${curr_date}/`;


      // get the first data from visitor_list_runtime table
      console.log(current_date + " [select query request] : SELECT * FROM visitor_list_runtime WHERE visitor_list_status = 'Y' limit 1");
      const user_result = await db.query("SELECT * FROM visitor_list_runtime WHERE visitor_list_status = 'Y' limit 1")
      console.log(current_date + " [select query response] : " + JSON.stringify(user_result));

      for (var k = 0; k < user_result.length; k++) {

        // calculate julian date
        Date.prototype.julianDate = function () {
          var j = parseInt((this.getTime() - new Date('Dec 30,' + (this.getFullYear() - 1) + ' 23:00:00').getTime()) / 86400000).toString(),
            i = 3 - j.length;
          while (i-- > 0) j = 0 + j;
          return j
        };

        // convert the date to required date format
        let checked_date = moment(user_result[k].visitor_list_entdate).format("YYYY-MM-DD H:m:s");
        let checked_hour = moment(user_result[k].visitor_list_entdate).format("HH");
        let checked_min = moment(user_result[k].visitor_list_entdate).format("mm");
        let checked_sec = moment(user_result[k].visitor_list_entdate).format("ss");

        // random id to generate unique id
        let random_id = Math.floor(Math.random() * 1000);

        var checked_gender;
        var checked_age_category;
        var user_age;
        var unique_user_id;
        var checked_store_id = user_result[k].store_id;
        var checked_camera_id = user_result[k].camera_id;
        var checked_min_age = user_result[k].min_age;
        var checked_max_age = user_result[k].max_age;
        var checked_face_id = user_result[k].aws_faceid;
        var checked_user_id = user_result[k].vl_runtime_id;

        // generate unique id using date time and random id
        unique_user_id = `AA${checked_camera_id}${new Date().toLocaleDateString('en', { year: '2-digit' })}${new Date().julianDate()}-${checked_hour}${checked_min}${checked_sec}-${random_id}`;

        // calculate gender by gender confidence
        if (user_result[k].visitor_gender_confidence >= 75) {
          if (user_result[k].visitor_gender == "Male") {
            checked_gender = 'M'
          }
          else {
            checked_gender = 'F'
          }
        }
        else {
          checked_gender = 'O'
        }

        // calculate user average age and age category
        user_age = (parseInt(checked_min_age) + parseInt(checked_max_age)) / 2;
        if (user_age > 0 && user_age <= 12) {
          checked_age_category = 'KIDS'
        }
        else if (user_age > 12 && user_age <= 19) {
          checked_age_category = 'TEEN'
        }
        else if (user_age > 19 && user_age <= 39) {
          checked_age_category = 'ADULT'
        }
        else if (user_age > 39 && user_age <= 59) {
          checked_age_category = 'MIDDLE AGE'
        }
        else {
          checked_age_category = 'OLD'
        }

        // promise for search faces by face id
        const searchFacePromise = new Promise(function (resolve, reject) {

          var searchParams = {
            CollectionId: face_collection,
            FaceId: checked_face_id,
            MaxFaces: 20
          };
          console.log(current_date + " [SearchFaces parameters] : " + JSON.stringify(searchParams))
          rekognition.searchFaces(searchParams, async function (err, data) {
            if (err) {
              reject(err);// an error occurred
            }
            else {
              resolve(data);
            }
          });
        });

        // call the promise for search faces by face id
        await searchFacePromise
          .then(async function (data) {
            console.log(current_date + " [SearchFaces success response] : " + JSON.stringify(data))
            var searchedFaces = data;

            var isRegistered;
            var delete_face_array = [];
            var delete_face_array_detail = [];

            // face matches length is zero. it indicates there is no faces and record for this face id
            if (searchedFaces.FaceMatches.length == 0) {
              console.log(current_date + " face matches length : " + searchedFaces.FaceMatches.length)

  console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list WHERE aws_faceid='${checked_face_id}' AND DATE_SUB('${checked_date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`)
                    const search_validation_unique = await db.query(`SELECT * FROM visitor_list WHERE aws_faceid='${checked_face_id}' AND DATE_SUB('${checked_date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`);
                    console.log(current_date + " [select query response] : " + JSON.stringify(search_validation_unique))

              if(search_validation_unique.length == 0){
    console.log(current_date + " [search query request] : " + `SELECT * from unique_visitors WHERE aws_faceid = '${checked_face_id}'`)
              const search_unique = await db.query(`SELECT * from unique_visitors WHERE aws_faceid = '${checked_face_id}'`);
              console.log(current_date + " [search query response] : " + JSON.stringify(search_unique))

              if(search_unique.length != 0){
                console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},NULL,${checked_camera_id},'${search_unique[0].visitor_gender}','${search_unique[0].min_age}','${search_unique[0].max_age}','${search_unique[0].age_category}','${search_unique[0].aws_faceid}','${search_unique[0].visitor_id}','Y','${checked_date}')`)
                const insert_result_unique = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},NULL,${checked_camera_id},'${search_unique[0].visitor_gender}','${search_unique[0].min_age}','${search_unique[0].max_age}','${search_unique[0].age_category}','${search_unique[0].aws_faceid}','${search_unique[0].visitor_id}','Y','${checked_date}')`);
                console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result_unique))
    
              }
              else{

              console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${checked_face_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${checked_date}')`)
              const unique_insert = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${checked_face_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${checked_date}')`);
              console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert))

              console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},NULL,${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${checked_face_id}','${unique_user_id}','Y','${checked_date}')`)
              const insert_result = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},NULL,${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${checked_face_id}','${unique_user_id}','Y','${checked_date}')`);
              console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result))
    }
    }
    console.log(current_date + " [select query request] : " + `SELECT * from visitor_list_runtime WHERE aws_faceid = '${checked_face_id}'`)
              const check_in_runtime = await db.query(`SELECT * from visitor_list_runtime WHERE aws_faceid = '${checked_face_id}'`);
              console.log(current_date + " [select query response] : " + JSON.stringify(check_in_runtime))

    for(var f=0;f<check_in_runtime.length;f++){
              console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${check_in_runtime[f].vl_runtime_id}`)
              const delete_result = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${check_in_runtime[f].vl_runtime_id}`);
              console.log(current_date + " [delete query response] : " + JSON.stringify(delete_result))
    }
    try{
    if (!fs.existsSync(dest_folder+""+checked_face_id+".jpg")) {
                    fs.copyFileSync(src_folder+""+checked_face_id+".jpg", dest_folder+""+checked_face_id+".jpg");
                  //  fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  }
        fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
    console.log(current_date + " [copy image from runtime success] : "+ checked_face_id)
                  }
                  catch(e){
                  console.log(current_date + " [copy image from runtime success] : "+ checked_face_id +" - "+e)
                  }
            }

            else if (searchedFaces.FaceMatches.length == 1) {
              console.log(current_date + " face matches length : " + searchedFaces.FaceMatches.length)

              var deleteParams = {
                CollectionId: face_collection,
                FaceIds: [checked_face_id],
              };
              const deleteFacePromise = new Promise(function (resolve, reject) {
                console.log(current_date + " [deleteFaces parameters] : " + JSON.stringify(deleteParams))

                rekognition.deleteFaces(deleteParams, async function (err, data) {
                  if (err) {
                    reject(err);// an error occurred
                  }
                  else {
                    resolve(data);
                  }
                });
              });
              await deleteFacePromise
                .then(async function (data) {
                  console.log(current_date + " [deleteFaces success response] : " + JSON.stringify(data))
    
    /*	try{
              fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
              console.log(current_date + " [delete image from runtime success] : "+ checked_face_id)
                  }
                  catch(e){
                    console.log(current_date + " [delete image from runtime failed] : "+ checked_face_id+" - "+e)
                  } */

                  console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}' AND cus_mgt_status = 'Y'`)
                  const check_in_customer_management = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}' AND cus_mgt_status = 'Y'`);
                  console.log(current_date + " [select query response] : " + JSON.stringify(check_in_customer_management))

                  // if check_in_customer_management length is zero then there is no registered member for this face id
                  if (check_in_customer_management.length == 0) {
                    isRegistered = 'NULL';
                  }
                  else {
                    isRegistered = check_in_customer_management[0].customer_id;
        
      if(check_in_customer_management[0].customer_gender != 'O'){
                    checked_age_category = check_in_customer_management[0].age_category;
                    checked_gender = check_in_customer_management[0].customer_gender;
                    checked_min_age = check_in_customer_management[0].min_age;
                    checked_max_age = check_in_customer_management[0].max_age;
      }

                  }

                  console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list_runtime WHERE aws_faceid='${checked_face_id}'`)
                  const selct_all_face_id = await db.query(`SELECT * FROM visitor_list_runtime WHERE aws_faceid='${checked_face_id}'`);
                  console.log(current_date + " [select query response] : " + JSON.stringify(selct_all_face_id))

                  for (var c = 0; c < selct_all_face_id.length; c++) {
                    let repeatedDate = moment(selct_all_face_id[c].visitor_list_entdate).format("YYYY-MM-DD H:m:s");

                    // check if the entered date is after the interval time
                    console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' AND DATE_SUB('${repeatedDate}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`)
                    const search_query_validation_one = await db.query(`SELECT * FROM visitor_list WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' AND DATE_SUB('${repeatedDate}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`);
                    console.log(current_date + " [select query response] : " + JSON.stringify(search_query_validation_one))

                    if (search_query_validation_one.length == 0) {
                      if (check_in_customer_management.length == 0) {

                        console.log(current_date + " [select query request] : " + `SELECT * from unique_visitors WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' limit 1`)
                        const get_details = await db.query(`SELECT * from unique_visitors WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' AND unique_visitors_status = 'Y' limit 1`)
                        console.log(current_date + " [select query response] : " + JSON.stringify(get_details))

                        if (get_details.length != 0) {

        if(get_details[0].visitor_gender == 'O'){
          
        console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                        const update_unique_user = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                        console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user))
            
        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${get_details[0].visitor_id}','Y','${repeatedDate}')`)
                      const insert_result_updated = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${get_details[0].visitor_id}','Y','${repeatedDate}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result_updated))

        }
        else{
                          console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details[0].visitor_gender}','${get_details[0].min_age}','${get_details[0].max_age}','${get_details[0].age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${get_details[0].visitor_id}','Y','${repeatedDate}')`)
                          const insert_result1 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details[0].visitor_gender}','${get_details[0].min_age}','${get_details[0].max_age}','${get_details[0].age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${get_details[0].visitor_id}','Y','${repeatedDate}')`);
                          console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result1))
        }

                        }
                        else {
                          console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${searchedFaces.FaceMatches[0].Face.FaceId}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${repeatedDate}')`)
                          const unique_insert1 = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${searchedFaces.FaceMatches[0].Face.FaceId}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${repeatedDate}')`);
                          console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert1))

                          console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${unique_user_id}','Y','${repeatedDate}')`)
                          const insert_result2 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${unique_user_id}','Y','${repeatedDate}')`);
                          console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result2))
      /* try{	
      if (!fs.existsSync(dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg")) {
                    fs.copyFileSync(src_folder+""+checked_face_id+".jpg", dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg");
                    fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
      }
      console.log(current_date + " [copy image from runtime success] : "+ searchedFaces.FaceMatches[0].Face.FaceId)
                  }
                  catch(e){
                  console.log(current_date + " [copy image from runtime success] : "+ searchedFaces.FaceMatches[0].Face.FaceId +" - "+e)
                  } */
                        }
                      }
                      else {

        if(check_in_customer_management[0].customer_gender =='O'){
        console.log(current_date + " [update query request] : " + `UPDATE customer_management SET customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category} WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                      const update_user_gender = await db.query(`UPDATE customer_management SET customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                      console.log(current_date + " [update query response] : " + JSON.stringify(update_user_gender))

        console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                        const update_unique_user = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${searchedFaces.FaceMatches[0].Face.FaceId}'`)
                        console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user))
            
        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${check_in_customer_management[0].visitor_id}','Y','${repeatedDate}')`)
                    const insert_result3_update = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${check_in_customer_management[0].visitor_id}','Y','${repeatedDate}')`);
                    console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result3_update))

        }
        else{
                        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${check_in_customer_management[0].visitor_id}','Y','${repeatedDate}')`)
                        const insert_result3 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${searchedFaces.FaceMatches[0].Face.FaceId}','${check_in_customer_management[0].visitor_id}','Y','${repeatedDate}')`);
                        console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result3))
        }

      console.log(current_date + " [select query request] : " + `SELECT * FROM sms_updates WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`)
                const msg_check = await db.query(`SELECT * FROM sms_updates WHERE aws_faceid='${searchedFaces.FaceMatches[0].Face.FaceId}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`);
                console.log(current_date + " [select query response] : " + JSON.stringify(msg_check))

        if(msg_check.length == 0 && check_in_customer_management[0].cus_mgt_status == 'Y'){
                        var footfall_msg = "Dear "+check_in_customer_management[0].customer_name +", Welcome to Celeb Mall."

                        var send_sms = {
                          method: 'post',
                          url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${check_in_customer_management[0].customer_mobile}&message=${footfall_msg}`,
                          headers: {
                            'Cookie': php_cookie
                          }
                        };
            
                        console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))
            
                        await axios(send_sms)
                          .then(async function (response) {
                            console.log(current_date + " [send SMS response] : " +JSON.stringify(response.data));
        if(response.data.status_code == 200){
          console.log(current_date + " [insert query request] : " + `INSERT INTO sms_updates VALUES(NULL,'${searchedFaces.FaceMatches[0].Face.FaceId}','Y','${checked_date}')`)
                      const sms_insert1 = await db.query(`INSERT INTO sms_updates VALUES(NULL,'${searchedFaces.FaceMatches[0].Face.FaceId}','Y','${checked_date}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(sms_insert1))
        }     
                          })
                          .catch(function (error) {
                            console.log(current_date + " [send SMS failed response] : " +error);
                          });
        }
                      }

                    }
                    console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${selct_all_face_id[c].vl_runtime_id}`)
                    const delete_result1 = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${selct_all_face_id[c].vl_runtime_id}`);
                    console.log(current_date + " [delete query response] : " + JSON.stringify(delete_result1))

      /*if (!fs.existsSync(dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg")) {
          fs.copyFileSync(src_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg", dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg");
      }*/
                  }
            
                })
                .catch(function (err) {
                  console.log(current_date + " [deleteFaces success response] : " + err)
                });

              try{
                  if (!fs.existsSync(dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg")) {
                    fs.copyFileSync(src_folder+""+checked_face_id+".jpg", dest_folder+""+searchedFaces.FaceMatches[0].Face.FaceId+".jpg");
                  //  fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  }
      fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  console.log(current_date + " [copy image from runtime success] : "+ searchedFaces.FaceMatches[0].Face.FaceId)
                  }
                  catch(e){
                  console.log(current_date + " [copy image from runtime success] : "+ searchedFaces.FaceMatches[0].Face.FaceId +" - "+e)
                  }

      
            }
            else {
              console.log(current_date + " face matches length : " + searchedFaces.FaceMatches.length)

              var unique_face_id;

              console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list_runtime WHERE aws_faceid='${checked_face_id}'`)
              const select_duplicate = await db.query(`SELECT * FROM visitor_list_runtime WHERE aws_faceid='${checked_face_id}'`);
              console.log(current_date + " [select query response] : " + JSON.stringify(select_duplicate))

              for (var i = 0; i < select_duplicate.length; i++) {
                let deleteDate = moment(select_duplicate[i].visitor_list_entdate).format("YYYY-MM-DD H:m:s");
                delete_face_array_detail.push({ "id": `${select_duplicate[i].vl_runtime_id}`, 'date': `${deleteDate}` })
      
              }
              for (var i = 0; i < searchedFaces.FaceMatches.length; i++) {
                console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list_runtime WHERE aws_faceid='${searchedFaces.FaceMatches[i].Face.FaceId}'`)
                const select_duplicate2 = await db.query(`SELECT * FROM visitor_list_runtime WHERE aws_faceid='${searchedFaces.FaceMatches[i].Face.FaceId}'`);
                console.log(current_date + " [select query response] : " + JSON.stringify(select_duplicate2))

                if (select_duplicate2.length == 0) {
                  unique_face_id = searchedFaces.FaceMatches[i].Face.FaceId;
                }
                else {
                  for (var j = 0; j < select_duplicate2.length; j++) {
                    // const search_validation = await db.query(`SELECT * FROM visitor_list WHERE aws_faceid='${searchedFaces.FaceMatches[i].Face.FaceId}' AND DATE_SUB('${select_duplicate[j].visitor_list_entdate}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`);
                    //            if(search_validation != 0){
                    let deleteDate = moment(select_duplicate2[j].visitor_list_entdate).format("YYYY-MM-DD H:m:s");

                    delete_face_array.push(searchedFaces.FaceMatches[i].Face.FaceId)
                    delete_face_array_detail.push({ "id": `${select_duplicate2[j].vl_runtime_id}`, 'date': `${deleteDate}` })
                  }
                  //	}

                }
              }
              if (unique_face_id == null) {
                unique_face_id = checked_face_id;
              }
              else {
                delete_face_array.push(checked_face_id)
              }
              var deleteParam2 = {
                CollectionId: face_collection,
                FaceIds: delete_face_array,
                // FaceIds: [face_id]
              };
              const deleteFacePromise1 = new Promise(function (resolve, reject) {
                console.log(current_date + " [deleteFaces parameters] : " + JSON.stringify(deleteParam2))
                rekognition.deleteFaces(deleteParam2, async function (err, data) {
                  if (err) {
                    reject(err);// an error occurred
                  }
                  else {
                    resolve(data);
                  }
                });
              });

              await deleteFacePromise1
                .then(async function (data) {

                  /* for(var d=0;d<delete_face_array.length;d++){
      try{ 
      if (fs.existsSync(src_folder+""+delete_face_array[d]+".jpg")) {
                  // fs.copyFileSync(src_folder+""+unique_face_id+".jpg", dest_folder+""+unique_face_id+".jpg");
                    fs.unlinkSync(src_folder+""+delete_face_array[d]+".jpg");
                  }
      console.log(current_date + " [delete image from runtime success] : "+ delete_face_array[d])
      }
      catch(e){
        console.log(current_date + " [delete image from runtime failed] : "+ delete_face_array[d]+" - "+e)
      }
      } */
    
            console.log(current_date + " [deleteFaces success response] : " + JSON.stringify(data))

                  console.log(current_date + " [select query request] : " + `SELECT * from customer_management WHERE aws_faceid = '${unique_face_id}' AND cus_mgt_status = 'Y'`)
                  const select_query2 = await db.query(`SELECT * from customer_management WHERE aws_faceid = '${unique_face_id}' AND cus_mgt_status = 'Y'`);
                  console.log(current_date + " [select query response] : " + JSON.stringify(select_query2))

                  if (select_query2.length == 0) {
                    isRegistered = 'NULL';
                  }
                  else {
                    isRegistered = select_query2[0].customer_id
        if(select_query2[0].customer_gender != 'O'){
                    checked_age_category = select_query2[0].age_category;
                    checked_gender = select_query2[0].customer_gender;
                    checked_min_age = select_query2[0].min_age;
                    checked_max_age = select_query2[0].max_age;
      }
                  }

                  console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list WHERE aws_faceid='${unique_face_id}' AND DATE_SUB('${checked_date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`)
                  const search_query_validation = await db.query(`SELECT * FROM visitor_list WHERE aws_faceid='${unique_face_id}' AND DATE_SUB('${checked_date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`);
                  console.log(current_date + " [select query response] : " + JSON.stringify(search_query_validation))

                  if (search_query_validation.length == 0) {
                    if (select_query2.length == 0) {

                      console.log(current_date + " [select query request] : " + `SELECT * from unique_visitors WHERE aws_faceid='${unique_face_id}' limit 1`)
                      const get_details1 = await db.query(`SELECT * from unique_visitors WHERE aws_faceid='${unique_face_id}' limit 1`)
                      console.log(current_date + " [select query response] : " + JSON.stringify(get_details1))

                      if (get_details1.length != 0) {
        
        if(get_details1[0].visitor_gender == 'O'){
              console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${unique_face_id}'`)
                        const update_unique_user_change = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${unique_face_id}'`)
                        console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user_change))                 
      
        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${checked_date}')`)
                      const insert_result4_change = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${checked_date}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result4_change))	
        }
      else{
        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details1[0].visitor_gender}','${get_details1[0].min_age}','${get_details1[0].max_age}','${get_details1[0].age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${checked_date}')`)
                        const insert_result4 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details1[0].visitor_gender}','${get_details1[0].min_age}','${get_details1[0].max_age}','${get_details1[0].age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${checked_date}')`);
                        console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result4))
      }	
                      }
                      else {
                        console.log(current_date + " [insert query request] : " + `INSERT INTO unique_visitors VALUES(NULL,'${unique_face_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${checked_date}')`)
                        const unique_insert1 = await db.query(`INSERT INTO unique_visitors VALUES(NULL,'${unique_face_id}','${unique_user_id}','${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','AWS new user','Y','${checked_date}')`);
                        console.log(current_date + " [insert query response] : " + JSON.stringify(unique_insert1))

                        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${unique_user_id}','Y','${checked_date}')`)
                        const insert_result5 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${unique_user_id}','Y','${checked_date}')`);
                        console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result5))
      /*	try{
        if (!fs.existsSync(dest_folder+""+unique_face_id+".jpg")) {
                    fs.copyFileSync(src_folder+""+checked_face_id+".jpg", dest_folder+""+unique_face_id+".jpg");
                    fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  }
      console.log(current_date + " [copy image from runtime success] : "+ unique_face_id)
      }
      catch(e){
      console.log(current_date + " [copy image from runtime success] : "+ unique_face_id +" - "+e)
      } */
                      }
                    }
                    else {
                  
      if(select_query2[0].customer_gender == 'O'){
          console.log(current_date + " [update query request] : " + `UPDATE customer_management SET customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category} WHERE aws_faceid = '${unique_face_id}'`)
                      const update_register_user_gender = await db.query(`UPDATE customer_management SET customer_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${unique_face_id}'`)
                      console.log(current_date + " [update query response] : " + JSON.stringify(update_register_user_gender))
        
        console.log(current_date + " [update query request] : " + `UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${unique_face_id}'`)
                        const update_unique_user_gender = await db.query(`UPDATE unique_visitors SET visitor_gender = '${checked_gender}', min_age = '${checked_min_age}', max_age = '${checked_max_age}',age_category = '${checked_age_category}' WHERE aws_faceid = '${unique_face_id}'`)
                        console.log(current_date + " [update query response] : " + JSON.stringify(update_unique_user_gender))
      }

        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${select_query2[0].visitor_id}','Y','${checked_date}')`)
                      const insert_result6 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${select_query2[0].visitor_id}','Y','${checked_date}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result6))

        console.log(current_date + " [select query request] : " + `SELECT * FROM sms_updates WHERE aws_faceid='${unique_face_id}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`)
                const message_validation = await db.query(`SELECT * FROM sms_updates WHERE aws_faceid='${unique_face_id}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`);
                console.log(current_date + " [select query response] : " + JSON.stringify(message_validation))

      if(message_validation.length == 0){
      var footfall_msg = "Dear "+select_query2[0].customer_name +", Welcome to Celeb Mall."
      var send_sms = {
                          method: 'post',
                          url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${select_query2[0].customer_mobile}&message=${footfall_msg}`,
                          headers: {
                            'Cookie': php_cookie
                          }
                        };
            
                        console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))
            
                        await axios(send_sms)
                          .then(async function (response) {
                            console.log(current_date + " [send SMS response] : " +JSON.stringify(response.data));     
      if(response.data.status_code == 200){
                                  console.log(current_date + " [insert query request] : " + `INSERT INTO sms_updates VALUES(NULL,'${unique_face_id}','Y','${checked_date}')`)
                      const sms_insert2 = await db.query(`INSERT INTO sms_updates VALUES(NULL,'${unique_face_id}','Y','${checked_date}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(sms_insert2))
                          }
                          })
                          .catch(function (error) {
                            console.log(current_date + " [send SMS failed response] : " +error);
                          });

                    }
      }
                  }
                  for (var i = 0; i < delete_face_array_detail.length; i++) {
                  
        if (delete_face_array_detail[i].id != checked_user_id) {
      
                      console.log(current_date + " [select query request] : " + `SELECT * FROM visitor_list WHERE aws_faceid='${unique_face_id}' AND DATE_SUB('${delete_face_array_detail[i].date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`)
                      const search_query_validation5 = await db.query(`SELECT * FROM visitor_list WHERE aws_faceid='${unique_face_id}' AND DATE_SUB('${delete_face_array_detail[i].date}', INTERVAL ${intervalTime} ${intervalTimeFormat}) < visitor_list_entdate`);
                      console.log(current_date + " [select query response] : " + JSON.stringify(search_query_validation5))


                      if (search_query_validation5.length != 0) {
                        console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${delete_face_array_detail[i].id}`)
                        const delete_result3 = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${delete_face_array_detail[i].id}`);
                        console.log(current_date + " [delete query response] : " + JSON.stringify(delete_result3))

                      }
                      else {

                        console.log(current_date + " [select query request] : " + `SELECT * from visitor_list_runtime WHERE vl_runtime_id=${delete_face_array_detail[i].id}`)
                        const get_date = await db.query(`SELECT * from visitor_list_runtime WHERE vl_runtime_id=${delete_face_array_detail[i].id}`);
                        console.log(current_date + " [select query response] : " + JSON.stringify(get_date))

                        let updateDate = moment(get_date[0].visitor_list_entdate).format("YYYY-MM-DD H:m:s");
                        if (select_query2.length == 0) {

                          console.log(current_date + " [select query request] : " + `SELECT * from unique_visitors WHERE aws_faceid='${unique_face_id}' limit 1`)
                          const get_details1 = await db.query(`SELECT * from unique_visitors WHERE aws_faceid='${unique_face_id}' limit 1`)
                          console.log(current_date + " [select query response] : " + JSON.stringify(get_details1))

                          if (get_details1.length != 0) {

        if(get_details1[0].visitor_gender == 'O'){
        console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${updateDate}')`)
                          const insert_result7_change = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${updateDate}')`);
                          console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result7_change))

        }
        else{
                            console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details1[0].visitor_gender}','${get_details1[0].min_age}','${get_details1[0].max_age}','${get_details1[0].age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${updateDate}')`)
                            const insert_result7 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${get_details1[0].visitor_gender}','${get_details1[0].min_age}','${get_details1[0].max_age}','${get_details1[0].age_category}','${unique_face_id}','${get_details1[0].visitor_id}','Y','${updateDate}')`);
                            console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result7))
        }

                          }
                          else {
                            console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${unique_user_id}','Y','${updateDate}')`)
                            const insert_result8 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${unique_user_id}','Y','${updateDate}')`);
                            console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result8))

                          }
                        }
                        else {
                          console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${select_query2[0].visitor_id}','Y','${updateDate}')`)
                          const insert_result9 = await db.query(`INSERT INTO visitor_list VALUES(NULL,${checked_store_id},${isRegistered},${checked_camera_id},'${checked_gender}','${checked_min_age}','${checked_max_age}','${checked_age_category}','${unique_face_id}','${select_query2[0].visitor_id}','Y','${updateDate}')`);
                          console.log(current_date + " [insert query response] : " + JSON.stringify(insert_result9))

        console.log(current_date + " [select query request] : " + `SELECT * FROM sms_updates WHERE aws_faceid='${unique_face_id}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`)
                const msg_validation = await db.query(`SELECT * FROM sms_updates WHERE aws_faceid='${unique_face_id}' AND DATE_FORMAT(sms_entry_date,"%Y-%m-%d") = '${today_date_now}'`);
                console.log(current_date + " [select query response] : " + JSON.stringify(msg_validation))

    if(msg_validation.length == 0 && select_query2[0].cus_mgt_status == 'Y'){
    var footfall_msg = "Dear "+select_query2[0].customer_name +", Welcome to Celeb Mall."
        var send_sms = {
                          method: 'post',
                          url: `${send_sms_url}process=${sms_process}&username=${sms_user_name}&password=${sms_password}&campaign_name=${sms_campaign_name}&number=${select_query2[0].customer_mobile}&message=${footfall_msg}`,
                          headers: {
                            'Cookie': php_cookie
                          }
                        };
            
                        console.log(current_date + " [send SMS request] : " + JSON.stringify(send_sms))
            
                        await axios(send_sms)
                          .then(async function (response) {
                            console.log(current_date + " [send SMS response] : " +JSON.stringify(response.data));     
                          if(response.data.status_code == 200){
                                  console.log(current_date + " [insert query request] : " + `INSERT INTO sms_updates VALUES(NULL,'${unique_face_id}','Y','${checked_date}')`)
                      const sms_insert2 = await db.query(`INSERT INTO sms_updates VALUES(NULL,'${unique_face_id}','Y','${checked_date}')`);
                      console.log(current_date + " [insert query response] : " + JSON.stringify(sms_insert2))
                          }
        })
                          .catch(function (error) {
                            console.log(current_date + " [send SMS failed response] : " +error);
                          });
        }
                        }
                        console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${delete_face_array_detail[i].id}`)
                        const delete_result4 = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${delete_face_array_detail[i].id}`);
                        console.log(current_date + " [delete query response] : " + JSON.stringify(delete_result4))

                      }
                    }
                    else {
                      console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${checked_user_id}`)
                      const delete_result2 = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = ${checked_user_id}`);
                      console.log(current_date + " [delete query response] : " + JSON.stringify(delete_result2))

                    }
                  }

                })
                .catch(function (err) {
                  console.log(current_date + " [deleteFaces failed response] : " + err)
                });

              try{
                          if (!fs.existsSync(dest_folder+""+unique_face_id+".jpg")) {
                    fs.copyFileSync(src_folder+""+checked_face_id+".jpg", dest_folder+""+unique_face_id+".jpg");
                  //  fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  }
                fs.unlinkSync(src_folder+""+checked_face_id+".jpg");
                  console.log(current_date + " [copy image from runtime success] : "+ unique_face_id)
                  }
                  catch(e){
                  console.log(current_date + " [copy image from runtime success] : "+ unique_face_id +" - "+e)
                  }

              for(var d=0;d<delete_face_array.length;d++){
                  try{
                  if (fs.existsSync(src_folder+""+delete_face_array[d]+".jpg")) {
                  // fs.copyFileSync(src_folder+""+unique_face_id+".jpg", dest_folder+""+unique_face_id+".jpg");
                    fs.unlinkSync(src_folder+""+delete_face_array[d]+".jpg");
                  }
                  console.log(current_date + " [delete image from runtime success] : "+ delete_face_array[d])
                  }
                  catch(e){
                    console.log(current_date + " [delete image from runtime failed] : "+ delete_face_array[d]+" - "+e)
                  }
                  }

            }
          })
          .catch(async function (error) {
            console.log(current_date + " [SearchFaces failed response] : " + error)

            console.log(current_date + " [select query request] : " + `SELECT vl_runtime_id FROM visitor_list_runtime WHERE aws_faceid = '${checked_face_id}'`)
            const select_duplicate_delete = await db.query(`SELECT vl_runtime_id FROM visitor_list_runtime WHERE aws_faceid = '${checked_face_id}'`);
            console.log(current_date + " [select query response] : " + JSON.stringify(select_duplicate_delete))

            for (var d = 0; d < select_duplicate_delete.length; d++) {
              console.log(current_date + " [delete query request] : " + `DELETE FROM visitor_list_runtime WHERE vl_runtime_id = '${select_duplicate_delete[d].vl_runtime_id}'`)
              const dlete_duplicate5 = await db.query(`DELETE FROM visitor_list_runtime WHERE vl_runtime_id = '${select_duplicate_delete[d].vl_runtime_id}'`);
              console.log(current_date + " [delete query response] : " + JSON.stringify(dlete_duplicate5))
            }
          });
      }
    }
    catch (e) {
      console.log(current_date + " [ failed response] : " + e)
    }
  }

  setInterval(footfallValidation, 60 * 1000);

  
  // function getBinary(encodedFile) {
  //   var base64Image = encodedFile.split("data:image/jpeg;base64,")[1];
  //   var binaryImg = atob(base64Image);
  //   var length = binaryImg.length;
  //   var ab = new ArrayBuffer(length);
  //   var ua = new Uint8Array(ab);

  //   for (var i = 0; i < length; i++) {
  //     ua[i] = binaryImg.charCodeAt(i);
  //   }

  //   return ab;
  // }

  // function save_image(image_file,id){
    
  // try{
  // var data = image_file.replace(/^data:image\/\w+;base64,/, "");
  // var buf = Buffer.from(data, 'base64');
  // var file = reg_folder+""+id+".jpg";
  // if (!fs.existsSync(file)) {

  // fs.writeFile(file, buf, function(){
  //   //console.log(current_date + " [image save success] : "+ id)
  // });
  // }
  // console.log(current_date + " [image save success] : " + id )
  // }
  // catch(e){
  //   console.log(current_date + " [image save failed] : " + id + " - "+ e)
  // }

  // } 
  

  
  app.post("/update_table", async (req, res) => {
    getFilesizeInBytes();
  
    var day = new Date();
    var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
    var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
    var current_date = today_date + ' ' + today_time;
  
    var face_id = req.body.face_id;
    var min_age = req.body.min_age;
    var max_age = req.body.max_age;
    var gender = req.body.gender;
    var gender_confidence = req.body.gender_confidence;
    var cam_id = req.body.cam_id;
    console.log(current_date + " [insert query parameters] : " + face_id + " , " + min_age + " , " + max_age + " , " + gender + " , " + gender_confidence + " , " + cam_id)
    try {
      console.log(current_date + " [insert query request] : " + `INSERT INTO visitor_list_runtime VALUES(NULL,1,'${cam_id}','${gender}',${gender_confidence},'${min_age}','${max_age}','${face_id}','Y',CURRENT_TIMESTAMP)`)
      var insert_face = await db.query(`INSERT INTO visitor_list_runtime VALUES(NULL,1,'${cam_id}','${gender}',${gender_confidence},'${min_age}','${max_age}','${face_id}','Y',CURRENT_TIMESTAMP)`);
      console.log(current_date + " [insert query success response] : " + JSON.stringify(insert_face))
  
      res.json({ response_code: 0, response_status: 200, response_msg: 'Success' });
  
    }
    catch (e) {
      console.log(current_date + " [insert query failed response] : " + e)
      res.json({ response_code: 1, response_status: 201, response_msg: 'Failed' });
  
    }
  
  });

// to listen the port in using the localhost
app.listen(port, () => {
	getFilesizeInBytes()
	logger_all.info(`App started listening at http://localhost:${port}`);
});

// module.exports.logger = logger;

//  to listen the port in using the server

// httpServer.listen(port, function (req, res) {
// 	logger_all.info("Server started at port " + port);
// });

// Log file Generation function - While its size is more than 20 MB, it will rename the existing file and generate the new file





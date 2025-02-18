/*
API that allows your frontend to communicate with your backend server (Node.js) for processing and retrieving data.
To access a MySQL database with Node.js and can be use it.
This page is used in login functions which is used in login.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const db = require("../db_connect/connect");
const md5 = require("md5")
const main = require('../logger')
require("dotenv").config();
const dynamic_db = require("../db_connect/dynamic_connect");
const jwt = require('jsonwebtoken');
const { response } = require("express");
// const { from } = require("json2csv/JSON2CSVTransform");

// portal login Function - Start
async function login(req) {
  console.log("...................................")

  var logger = main.logger
  var logger_all = main.logger_all

  // To get current Date and Time
  var day = new Date();
  var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
  var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
  var current_date = today_date + ' ' + today_time;

  // declare the variables
  var user_id;
  // get all the req data
  let txt_username = req.body.txt_username;
  let txt_password = md5(req.body.txt_password);
  var header_json = req.headers;
  let ip_address = header_json['x-forwarded-for'];

  logger.info("[API REQUEST] " + req.originalUrl + " - " + JSON.stringify(req.body) + " - " + JSON.stringify(req.headers) + " - " + ip_address)
  logger_all.info("[API REQUEST] " + req.originalUrl + " - " + JSON.stringify(req.body) + " - " + JSON.stringify(req.headers) + " - " + ip_address)

  try { // To check the login_id if the user_management table already exists are not
    // logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'W') ORDER BY user_id ASC");
    //const sql_stat = await db.query(
    //"SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'W') ORDER BY user_id ASC"
    //);
    // If sql_stat any length are available to send the error message in Inactive or Not Approved User. Kindly contact your admin!
    //if (sql_stat.length > 0) {
    // Failed [Inactive or Not Approved User] - call_index_signin Sign in function
    //logger_all.info(": [call_index_signin] Failed - Inactive or Not Approved User. Kindly contact your admin!");
    //return { response_code: 0, response_status: 201, response_msg: "Inactive or Not Approved User. Kindly contact your admin!" };
    //} else { // Otherwise To check the where Login id and Usr_mgt_status
    logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC")
    const sql_login = await db.query(
      "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC"
    );
    // If any sql_login length are availble to send the error message Invalid User. Kindly try again with the valid User!
    if (sql_login.length <= 0) {
      // Failed [Invalid User] - call_index_signin Sign in function
      logger_all.info(": [call_index_signin] Failed - Invalid User. Kindly try again with the valid User!");
      return { response_code: 0, response_status: 201, response_msg: "Invalid User. Kindly try again with the valid User!" };
    } else { // Otherwise to check login_id and login_password and usr_mgt_status
      logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and login_password = '" + txt_password + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC");
      const response_result = await db.query(
        "SELECT * FROM user_management where login_id = '" + txt_username + "' and login_password = '" + txt_password + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC"
      );
      // If response_result length are available to send the message in Invalid Password. Kindly try again with the valid details!
      if (response_result.length <= 0) {
        // Failed [Invalid Password] - call_index_signin Sign in function
        logger_all.info(": [call_index_signin] Failed  - Invalid Password. Kindly try again with the valid details!");
        return { response_code: 0, response_status: 201, response_msg: "Invalid Password. Kindly try again with the valid details!" };
      } else {
        // Otherwise the process are continue to create the JWT token
        user_id = response_result[0].user_id;
        const user =
        {
          username: req.body.txt_username,
          user_password: req.body.txt_password,
        }
        // Sign the jwt 
        const accessToken_1 = jwt.sign(user, process.env.ACCESS_TOKEN_SECRET, {
          expiresIn: process.env.ONEWEEK

        });
        process.env['TOKEN_SECRET'] = "Bearer " + accessToken_1;
        var user_bearer_token = process.env.TOKEN_SECRET;
        // To update the bearer token in the usermanagement table
        logger_all.info("[Update query request] : " + `UPDATE user_management SET user_bearer_token = '${user_bearer_token}' WHERE user_id = '${user_id}'`);
        var token = await db.query(`UPDATE user_management SET user_bearer_token = '${user_bearer_token}' WHERE  user_id = '${user_id}'`);
        logger_all.info("[Update query Response] : " + JSON.stringify(token));
        // To Login Success - If the user_log_status is 'I' and login date are already exists are not.
        logger_all.info("[select query request] : " + `SELECT user_id,user_log_status,login_date FROM user_log where user_id ='${response_result[0].user_id}' and user_log_status = 'I' and date(login_date) ='${today_date}'`);
        const check_login = await db.query(`SELECT user_id,user_log_status,login_date FROM user_log where user_id ='${response_result[0].user_id}' and user_log_status = 'I' and date(login_date) ='${today_date}'`);
        
        // logger_all.info("[select query request] : " +"SELECT from user_management "$user_id, user_master_id, parent_id, username, apikey, loginid, login_password, user_email, user_mobile, usr_mgt_status, usr_mgt, entry_date, user_bearer_token FROM user_management)"";



        if (check_login.length == 0) {
          // check login length is 0 .the process was continue.To insert the user_log table in the values
          logger_all.info("[insert query request] : " + `INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          const insert_login = await db.query(`INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          // Json Push
          response_result[0]['user_bearer_token'] = user_bearer_token;
          // To send the Success message and user details in the user
          logger_all.info(": [call_index_signin] Success ");
          return { response_code: 1, response_status: 200, response_msg: "Success", login_time: current_date, response_result,user_id:response_result[0].user_id,user_name:response_result[0].user_id,user_master_id:response_result[0].user_id };

        }
        else { // Otherwise to update the userlog table to set the value is user_log_status = 'O' and login date
          logger_all.info("[update query request] : " + `UPDATE user_log SET user_log_status = 'O', logout_time = '${current_date}' WHERE user_id = '${response_result[0].user_id}' AND user_log_status = 'I' AND login_date = '${today_date}'`);
          const update_logout = await db.query(`UPDATE user_log SET user_log_status = 'O', logout_time = '${current_date}' WHERE user_id = '${response_result[0].user_id}' AND user_log_status = 'I' AND login_date = '${today_date}'`);
          // And user_log table to insert the details.
          logger_all.info("[insert query request] : " + `INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          const insert_login = await db.query(`INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          // Json Push
          response_result[0]['user_bearer_token'] = user_bearer_token;
          // To send the Success message and user details in the user
          logger_all.info(": [call_index_signin] Success ");
          return { response_code: 1, response_status: 200, response_msg: "Success", login_time: current_date, response_result,user_id:response_result[0].user_id,user_name:response_result[0].user_id,user_master_id:response_result[0].user_id,parent_id:response_result[0].user_id };
        }

      }
    }
  }
  //} 
  catch (err) {
    // Failed - call_index_signin Sign in function
    logger_all.info(": [call_index_signin] Failed - " + err);
    return { response_code: 0, response_status: 201, response_msg: 'Error Occurred.' };
  }
}
//login Function - end

//api login Function - Start
async function api_login(req) {
  console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>")
  var logger = main.logger
  var logger_all = main.logger_all

  // get current Date and time
  var day = new Date();
  var today_date = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' + day.getDate();
  var today_time = day.getHours() + ":" + day.getMinutes() + ":" + day.getSeconds();
  var current_date = today_date + ' ' + today_time;

  // let query = " ";
  // get all the req data
  let txt_username = req.body.txt_username;
  let txt_password = md5(req.body.txt_password);
  var header_json = req.headers;
  let ip_address = header_json['x-forwarded-for'];
  logger.info("[API REQUEST] " + req.originalUrl + " - " + JSON.stringify(req.body) + " - " + JSON.stringify(req.headers) + " - " + ip_address)
  logger_all.info("[API REQUEST] " + req.originalUrl + " - " + JSON.stringify(req.body) + " - " + JSON.stringify(req.headers) + " - " + ip_address)

  try { // To check the login_id if the user_management table already exists are not
    //logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'W') ORDER BY user_id ASC");
    //const sql_stat = await db.query(
    //"SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'W') ORDER BY user_id ASC"
    //);
    // If sql_stat any length are available to send the error message in Inactive or Not Approved User. Kindly contact your admin!
    //if (sql_stat.length > 0) {
    // Failed [Inactive or Not Approved User] - call_index_signin Sign in function
    //logger_all.info(": [call_index_signin] Failed - Inactive or Not Approved User. Kindly contact your admin!");

    //return { response_code: 0, response_status: 201, response_msg: "Inactive or Not Approved User. Kindly contact your admin!" };
    //} else { // Otherwise To check the where Login id and Usr_mgt_status

    logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC")
    const sql_login = await db.query(
      "SELECT * FROM user_management where login_id = '" + txt_username + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC"
    );
    // If any sql_login length are availble to send the error message Invalid User. Kindly try again with the valid User!
    if (sql_login.length <= 0) {
      // Failed [Invalid User] - call_index_signin Sign in function
      logger_all.info(": [call_index_signin] Failed - Invalid User. Kindly try again with the valid User!");
      return { response_code: 0, response_status: 201, response_msg: "Invalid User. Kindly try again with the valid User!" };
    } else {  // Otherwise to check login_id and login_password and usr_mgt_status
      logger_all.info("[select query request] : " + "SELECT * FROM user_management where login_id = '" + txt_username + "' and login_password = '" + txt_password + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC");
      const response_result = await db.query(
        "SELECT * FROM user_management where login_id = '" + txt_username + "' and login_password = '" + txt_password + "' and usr_mgt_status in ('N', 'Y', 'R') ORDER BY user_id ASC"
      );
      // If response_result length are available to send the message in Invalid Password. Kindly try again with the valid details!
      if (response_result.length <= 0) {
        // Failed [Invalid Password] - call_index_signin Sign in function
        logger_all.info(": [call_index_signin] Failed  - Invalid Password. Kindly try again with the valid details!");
        return { response_code: 0, response_status: 201, response_msg: "Invalid Password. Kindly try again with the valid details!" };
      } else {
        // Otherwise the process are continue to create the JWT token
        user_id = response_result[0].user_id;
        const user =
        {
          username: req.body.txt_username,
          user_password: req.body.txt_password,
        }
        // Sign the jwt 
        const accessToken_1 = jwt.sign(user, process.env.ACCESS_TOKEN_SECRET, {
          expiresIn: process.env.ONEWEEK
          // expiresIn:"18s"

        });

        var user_bearer_token = "Bearer " + accessToken_1;
        // To update the bearer token in the usermanagement table
        logger_all.info("[Update query request] : " + `UPDATE user_management  SET user_bearer_token = '${user_bearer_token}' WHERE user_id = '${user_id}'`);
        var token = await db.query(`UPDATE user_management SET user_bearer_token = '${user_bearer_token}' WHERE user_id = '${user_id}'`);
        logger_all.info("[Update query Response] : " + JSON.stringify(token));

        // To Login Success - If the user_log_status is 'I' and login date are already exists are not.
        logger_all.info("[select query request] : " + `SELECT user_id,user_log_status,login_date FROM user_log where user_id ='${response_result[0].user_id}' and user_log_status = 'I' and date(login_date) ='${today_date}'`);
        const check_login = await db.query(`SELECT user_id,user_log_status,login_date FROM user_log where user_id ='${response_result[0].user_id}' and user_log_status = 'I' and date(login_date) ='${today_date}'`);

        if (check_login.length == 0) {  // check login length is 0 .the process was continue.To insert the user_log table in the values
          logger_all.info("[insert query request] : " + `INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          const insert_login = await db.query(`INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          // To send the Success message and user details in the user
          logger_all.info(": [call_index_signin] Success ");
          return { response_code: 1, response_status: 200, response_msg: "Success", user_bearer_token, user_short_name: response_result[0].user_short_name,user_id:response_result[0].user_id };

        }
        else { // Otherwise to update the userlog table to set the value is user_log_status = 'O' and login date
          logger_all.info("[update query request] : " + `UPDATE user_log SET user_log_status = 'O',logout_time = '${current_date}' WHERE user_id = '${response_result[0].user_id}' AND user_log_status = 'I' AND login_date = '${today_date}'`);
          const update_logout = await db.query(`UPDATE user_log SET user_log_status = 'O', logout_time = '${current_date}' WHERE user_id = '${response_result[0].user_id}' AND user_log_status = 'I' AND login_date = '${today_date}'`);
          // And user_log table to insert the details.
          logger_all.info("[insert query request] : " + `INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          const insert_login = await db.query(`INSERT INTO user_log VALUES(NULL, '${response_result[0].user_id}', '${ip_address}', '${current_date}', '${current_date}', NULL, 'I', '${current_date}')`);
          // To send the Success message and user details in the user
          logger_all.info(": [call_index_signin] Success ");
          return { response_code: 1, response_status: 200, response_msg: "Success", user_bearer_token, user_short_name: response_result[0].user_short_name,user_id:response_result[0].user_id  };
        }

      }
    }
  }
  //}
  catch (err) {
    // Failed - call_index_signin Sign in function error
    logger_all.info(": [call_index_signin] Failed - " + err);
    return { response_code: 0, response_status: 201, response_msg: 'Error Occurred.' };
  }
}

//login Function - End 

//signup Function - start 
async function Signup(req) {
  var logger_all = main.logger_all

  try {
    // let query = " ";
    let user_type = req.body.user_type;
    let user_name = req.body.user_name;
    let user_email = req.body.user_email;
    let user_mobile = req.body.user_mobile;
    let login_shortname = req.body.login_shortname;
    let user_password = md5(req.body.user_password);
    let user_permission = req.body.user_permission;
    var insert_user;

    // Get today's date
    let today = new Date();

    // Add one year to today's date
    let oneYearValidity = new Date(today);
    oneYearValidity.setFullYear(oneYearValidity.getFullYear() + 1);

    // Format today's date
    let formattedToday = today.toISOString().replace('T', ' ').replace(/\.\d+Z$/, '');
    // Format one year validity date
    let formattedValidity = oneYearValidity.toISOString().replace('T', ' ').replace(/\.\d+Z$/, '');
    console.log("Today's Date:", formattedToday);
    console.log("One Year Validity:", formattedValidity);

    // To check the login_id and user_email already exists are not.if already exists to send the error message
    logger_all.info(" [signup - select query request] : " + `SELECT * FROM user_management where login_id = '${user_name}' or user_email = '${user_email}'`);
    const sql_stat = await db.query(`SELECT * FROM user_management where login_id = '${user_name}'`);
    if (sql_stat.length > 0) {
      // Failed [Inactive or Not Approved User] - call_index_signin Sign in function
      logger_all.info(" : [signup] Failed - Client Name already used. Kindly try with some others!!");
      return {
        response_code: 0,
        response_status: 201,
        response_msg: "Client Name already used. Kindly try with some others!!"
      };
    } else {

      // To generate the random characters in the apikey
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let length = 32;
      let result = ' ';
      const charactersLength = characters.length;
      // loop the length To create the random number in the apikey
      for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }

      string = result.substring(0, 15);
      apikey = string.toUpperCase();

      // Otherwise the process was continued.to check the user type
      switch (user_type) {
        case "2": // Super Admin
          user_type = 2;
          parent_id = 1;
          // To insert the user_management table from the request values
          logger_all.info(" [signup - insert query request] : " + `INSERT INTO user_management VALUES (NULL, '${user_type}', '1', '${user_name}', '${login_shortname}', '${apikey}', '${user_name}', '${user_password}', '${user_email}', '${user_mobile}', NULL, NULL, NULL, NULL, '${user_permission}', 'Y', CURRENT_TIMESTAMP,'-')`)

          insert_user = await db.query(`INSERT INTO user_management VALUES (NULL, '${user_type}', '1', '${user_name}', '${login_shortname}', '${apikey}', '${user_name}', '${user_password}', '${user_email}', '${user_mobile}', NULL, NULL, NULL, NULL, '${user_permission}', 'Y', CURRENT_TIMESTAMP,'-')`);
          logger_all.info(" [signup - insert query response] : " + JSON.stringify(insert_user))
          var lastid = insert_user.insertId;
          break;
        case "3": // Dept Admin
          var explod1 = slt_super_admin.split("~~");
          parent_id = explod1[0];
          user_type = 3;
          // To insert the user_management table from the request values
          logger_all.info(" [signup - insert query request] : " + `INSERT INTO user_management VALUES (NULL, '${user_type}', '${parent_id}', '${user_name}', '${login_shortname}', '${apikey}', '${user_name}', '${user_password}', '${user_email}', '${user_mobile}', NULL, NULL, NULL, NULL, '${user_permission}', 'Y', CURRENT_TIMESTAMP,'-')`)
          insert_user = await db.query(`INSERT INTO user_management VALUES (NULL, '${user_type}', '${parent_id}', '${user_name}', '${login_shortname}', '${apikey}', '${user_name}', '${user_password}', '${user_email}', '${user_mobile}', NULL, NULL, NULL, NULL, '${user_permission}', 'Y', CURRENT_TIMESTAMP,'-')`);
          logger_all.info(" [signup - insert query response] : " + JSON.stringify(insert_user))
          var lastid = insert_user.insertId; break;
        case "4": // Agent
          var explod2 = slt_dept_admin.split("~~");
          parent_id = explod2[0];
          user_type = 4;
          break;
        default:
          // default
          break;
      }


      // user Details insert values
      logger_all.info(" [signup - insert query request] : " + `
            INSERT INTO user_details (user_details_id, user_id, cont_person, cont_designation, cont_mobile_no, cont_email, billing_address, company_name, company_website, parent_company_name, company_display_name, description_business, profile_image, business_category, sender,sender2, sender_1, sender_2, message_type, opt_process, enquiry_approval, privacy_terms, terms_conditions, document_proof, proof_doc_name, volume_day_expected, updated_date) 
            VALUES (NULL,'${lastid}', NULL,NULL, NULL, NULL, NULL, NULL,NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,NULL,NULL,CURRENT_TIMESTAMP) `);
      const insert_user_details = await db.query(` INSERT INTO user_details (user_details_id, user_id, cont_person, cont_designation, cont_mobile_no, cont_email, billing_address, company_name, company_website, parent_company_name, company_display_name, description_business, profile_image, business_category, sender,sender2, sender_1, sender_2, message_type, opt_process, enquiry_approval, privacy_terms, terms_conditions, document_proof, proof_doc_name, volume_day_expected, updated_date) 
            VALUES (NULL,'${lastid}', NULL,NULL, NULL, NULL, NULL, NULL,NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,NULL,NULL,CURRENT_TIMESTAMP)
            `);
      logger_all.info(" [signup - insert query response] : " + JSON.stringify(insert_user_details));

      // To create the new DB for the sign the new user --
      let exp_result1 = await db.query(`CREATE DATABASE IF NOT EXISTS whatsapp_report_${lastid} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci`);

      logger_all.info(" [signup - create DB response] : " + JSON.stringify(exp_result1))
      var db_name = `whatsapp_report_${lastid}`;


      // The process is continued.To create the new table in the new database
      let exp_result4 = await dynamic_db.query(`CREATE TABLE IF NOT EXISTS compose_whatsapp_status_tmpl_${lastid}(
        comwtap_status_id int NOT NULL ,
        compose_whatsapp_id int NOT NULL,
        country_code int DEFAULT NULL,
        mobile_no varchar(13) NOT NULL,
        report_group varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        comments varchar(100) NOT NULL,
        comwtap_status char(1) NOT NULL,
        comwtap_entry_date timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        response_status char(1) DEFAULT NULL,
        response_message varchar(100) DEFAULT NULL,
        response_id varchar(100) DEFAULT NULL,
        response_date timestamp NULL DEFAULT '0000-00-00 00:00:00',
        delivery_status varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        delivery_date timestamp NULL DEFAULT NULL,
        read_date timestamp NULL DEFAULT NULL,
        read_status char(1) DEFAULT NULL,
        campaign_status char(1) NOT NULL
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3`, null, `${db_name}`);

      logger_all.info(` [signup - create table compose_whatsapp_status_tmpl_ - 1] : ` + JSON.stringify(exp_result4))

      let alter_11 = await dynamic_db.query(`ALTER TABLE compose_whatsapp_status_tmpl_${lastid}
      MODIFY comwtap_status_id INT NOT NULL AUTO_INCREMENT,
      ADD PRIMARY KEY (comwtap_status_id),
      ADD KEY compose_whatsapp_id (compose_whatsapp_id),
      ADD KEY mobile_no (mobile_no),
      ADD KEY report_group (report_group)`, null, `${db_name}`);

      logger_all.info(`[signup - Alter compose_whatsapp_status_tmpl_${lastid} response - 2]: ` + JSON.stringify(alter_11));

      // let alter_22 = await dynamic_db.query(`ALTER TABLE compose_whatsapp_status_tmpl_${lastid}
      // MODIFY comwtap_status_id int NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1)`, null, `${db_name}`);
      // logger_all.info(`[signup - Alter compose_whatsapp_tmpl_${lastid} response - 3]: ` + JSON.stringify(alter_22));

      let exp_result12 = await dynamic_db.query(`
      CREATE TABLE IF NOT EXISTS compose_whatsapp_tmpl_${lastid} (
        compose_whatsapp_id int NOT NULL,
        user_id int NOT NULL,
        store_id int NOT NULL,
        whatspp_config_id int NOT NULL,
        mobile_nos longblob NOT NULL,
        sender_mobile_nos longblob NOT NULL,
        variable_values longblob,
	media_values longblob,
        whatsapp_content varchar(1000) NOT NULL,
        message_type varchar(50) NOT NULL,
        total_mobileno_count int DEFAULT NULL,
        content_char_count int NOT NULL,
        content_message_count int NOT NULL,
        campaign_name varchar(30) DEFAULT NULL,
        campaign_id varchar(10) DEFAULT NULL,
        mobile_no_type varchar(1) DEFAULT NULL,
        unique_template_id varchar(30) NOT NULL,
        template_id varchar(10) DEFAULT NULL,
        whatsapp_status char(1) NOT NULL,
        whatsapp_entry_date timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        media_url varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
      ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3`, null, `${db_name}`);

      logger_all.info(`[signup - create table compose_whatsapp_status_tmpl_${lastid} response - 4]: ` + JSON.stringify(exp_result12));

      let alter_1 = await dynamic_db.query(`ALTER TABLE compose_whatsapp_tmpl_${lastid}  MODIFY compose_whatsapp_id INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (compose_whatsapp_id),ADD KEY user_id (user_id,store_id,whatspp_config_id)`, null, `${db_name}`);

      logger_all.info(`[signup - Alter compose_whatsapp_tmpl_${lastid} response - 5]: ` + JSON.stringify(alter_1));

      let alter_2 = await dynamic_db.query(`ALTER TABLE compose_whatsapp_tmpl_${lastid} ADD tg_base VARCHAR(50) NULL AFTER media_url, ADD cg_base VARCHAR(50) NULL AFTER tg_base, ADD reject_reason VARCHAR(50) NULL AFTER cg_base, ADD receiver_nos_path VARCHAR(100) NULL AFTER reject_reason`, null, `${db_name}`);
      logger_all.info(`[signup - Alter compose_whatsapp_tmpl_${lastid} response - 5]: ` + JSON.stringify(alter_2));


      // To insert the message_limit values
      logger_all.info(" [signup message_limit - insert query request] : " + `INSERT INTO message_limit VALUES (NULL, '${lastid}', 1000, 1000, 'Y', CURRENT_TIMESTAMP, '${formattedValidity}')`)
      const insert_msglimit = await db.query(`INSERT INTO message_limit VALUES (NULL, '${lastid}', 1000, 1000, 'Y', CURRENT_TIMESTAMP, '${formattedValidity}')`);
      logger_all.info(` [signup message_limit - insert query response] : ` + JSON.stringify(insert_msglimit))
      // insert_msglimit length is '0' to through the no data available message.

      if (insert_msglimit) {
        return {
          response_code: 1,
          response_status: 200,
          num_of_rows: 1,
          response_msg: 'Success'
        };
      }
    }
  } catch (err) {
    // any error occurres send error response to client
    logger_all.info(" : [signup] Failed - " + err);
    return {
      response_code: 0,
      response_status: 201,
      response_msg: err.message
    };
  }
}
//signup Function - end 


// using for module exporting
module.exports = {
  login,
  api_login,
  Signup
};

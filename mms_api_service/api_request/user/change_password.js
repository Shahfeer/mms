const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/change_password");
require("dotenv").config();
// const main = require('../../logger');
// change pass - start
async function changePassword(req) {
	// var logger_all = main.logger_all
	// var logger = main.logger
	try {
		// query parameters
		console.log("[change pass query parameters] : " + JSON.stringify(req.body));

        var user_id = req.body.user_id;
        var exis_pass = req.body.ex_password
        var new_pass = req.body.new_password

		// // to get the master_countries details
		// // console.log("[select query request] : " + `SELECT * from user_management where user_id='${user_id}'`);
		// const get_user = await db.query(`SELECT * from user_management where user_id='${user_id}'`);
		// console.log("[select query response] : " + JSON.stringify(get_user))
		// if the master_countries length is coming to get the master_countries details.otherwise to send the no data available message.
		// if (get_user.length == 0) {
		// 	return { response_code: 0, response_status: 201, response_msg: 'User not exists.' };
		// }
		// else {
            // console.log(".................");
            // console.log(user_id);
            // console.log(exis_pass);

            console.log("[select query request] : " + `SELECT * from user_management where user_id='${user_id}' and login_password = '${md5(exis_pass)}'`);
		const check_pass = await db.query(`SELECT * from user_management where user_id='${user_id}' and login_password = '${md5(exis_pass)}'`);
		console.log("[select query response] : " + JSON.stringify(check_pass))
		
        if(check_pass.length ==0){
            return { response_code: 0, response_status: 201, response_msg: 'User old password is wrong.' };
        }
        else{
            console.log("[update query request] : " + `Update user_management set login_password = '${md5(new_pass)}' where user_id='${user_id}'`);
            const check_pass = await db.query(`Update user_management set login_password = '${md5(new_pass)}' where user_id='${user_id}'`);
            console.log("[update query response] : " + JSON.stringify(check_pass))
            return { response_code: 1, response_status: 200, response_msg: 'Success' };

        }
		// }
	}
	catch (e) {// any error occurres send error response to client
		console.log("[change pass failed response] : " + e)
		return { response_code: 0, response_status: 201, response_msg: 'Error occured' };
	}
}
// 	change pass - end

// using for module exporting
module.exports = {
	changePassword
}
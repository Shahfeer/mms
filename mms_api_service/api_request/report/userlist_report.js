const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/userlist_report");
require("dotenv").config();

async function userlistReport(req) {
    try {
        console.log("[Change userlist query parameters] : " + JSON.stringify(req.body));

        // Get today's date in the format 'YYYY-MM-DD'
        const today = new Date().toISOString().split('T')[0];
        console.log("[Current Date] : " + today);

        // Prepare the SQL query
        const userlist_report_query = `
            SELECT 
                customer_name, 
                visitor_id, 
                aws_faceid, 
                customer_mobile, 
                customer_gender, 
                min_age, 
                max_age, 
                age_category, 
                (SELECT GROUP_CONCAT(visitor_interest_title) FROM interest_list WHERE FIND_IN_SET(visitor_interest_id, cm.customer_interest)) AS interest_list_master
            FROM 
                customer_management cm
            WHERE 1=1
        `;

        console.log("[userlist report query request] : " + userlist_report_query);

        // Execute the SQL query with today's date parameters
        const today_result = await db.query(userlist_report_query);
        console.log("[userlist report query response] : " + JSON.stringify(today_result));

        // Check if there are results
        if (today_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'Userlist report does not exist for today.' };
        } else {
            return {
                response_code: 1,
                response_status: 200,
                response_msg: 'Success',
                data: {
                    report: today_result,
                    // Additional data or graphs if needed
                }
            };
        }
    } catch (e) {
        console.log("[Change userlist report failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    userlistReport
};

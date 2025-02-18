const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/todaysummary_report");
require("dotenv").config();

async function todaysummaryReport(req) {
    try {
        console.log("[Change report query parameters] : " + JSON.stringify(req.body));

        // Get today's date in the format 'YYYY-MM-DD'
        const today = new Date().toISOString().split('T')[0];
        console.log("[Current Date] : " + today);

        // Prepare the SQL query
        const todaysummar_report = `
            SELECT
                DATE(visitor_list_entdate) AS visitor_list_entdate,
                HOUR(visitor_list_entdate) AS hour_time,
                COUNT(visitor_list_id) AS visitor_count,
                SUM(visitor_gender = 'M') AS male_cnt,
                SUM(visitor_gender = 'F') AS female_cnt,
                SUM(visitor_gender = 'O') AS others_cnt,
                (SELECT COUNT(customer_id) 
                 FROM customer_management
                 WHERE DATE(cus_mgt_entry_date) = ? AND HOUR(cus_mgt_entry_date) = HOUR(visitor_list_entdate)
                ) AS cnt_registration
            FROM
                visitor_list
            WHERE
                DATE(visitor_list_entdate) = ?
            GROUP BY
                HOUR(visitor_list_entdate)
        `;

        console.log("[todaysummary report query request] : " + todaysummar_report);
        const today_result = await db.query(todaysummar_report, [today, today]);
        console.log("[todaysummary report query response] : " + JSON.stringify(today_result));

        if (today_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'Summary report does not exist.' };
        } else {
            return {
                response_code: 1,
                response_status: 200,
                response_msg: 'Success',
                data: {
                    report: today_result,
                    // graph: today_result1
                }
            };
        }
    } catch (e) {
        console.log("[Change todaysummary report failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    todaysummaryReport
};

const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/interest_list");
require("dotenv").config();

async function interestlistReport(req) {
    try {
        console.log("[Change report query parameters] : " + JSON.stringify(req.body));

        // Get today's date in the format 'YYYY-MM-DD'
        const today = new Date().toISOString().split('T')[0];
        console.log("[Current Date] : " + today);

        // Prepare the SQL query
        const interestTable = process.env.INTEREST_TABLE;
        if (!interestTable) {
            throw new Error("INTEREST_TABLE is not defined in the environment variables.");
        }

        const todaysummar_report = `SELECT visitor_interest_title, visitor_interest_id FROM ${interestTable} WHERE visitor_interest_status = 'Y' ORDER BY visitor_interest_title ASC`;

        console.log("[interest report query request] : " + todaysummar_report);
        const interest_result = await db.query(todaysummar_report);
        console.log("[interest report query response] : " + JSON.stringify(interest_result));

        if (interest_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'Summary report does not exist.' };
        } else {
            return {
                response_code: 1,
                response_status: 200,
                response_msg: 'Success',
                data: {
                    report: interest_result,
                    // graph: interest_result1
                }
            };
        }
    } catch (e) {
        console.log("[Change todaysummary report failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    interestlistReport
};

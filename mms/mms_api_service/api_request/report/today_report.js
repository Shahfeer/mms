const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/today_report");
require("dotenv").config();

async function todayReport(req) {
    try {
        console.log("[change report query parameters] : " + JSON.stringify(req.body));

        // Get today's date in the format 'YYYY-MM-DD'
        const today = new Date().toISOString().split('T')[0];
        console.log("[Current Date] : " + today);
        
        // Prepare the SQL queries
        const today_report = `
            SELECT vl.store_id, st.store_name, vl.camera_id, ct.camera_position, ct.ip_address, ct.camera_details, cm.customer_id, cm.customer_name, cm.customer_mobile, vl.visitor_gender, vl.min_age, vl.max_age, vl.age_category, vl.aws_faceid, vl.visitor_id, vl.visitor_list_entdate
            FROM visitor_list vl
            LEFT JOIN store_details st ON vl.store_id = st.store_id
            LEFT JOIN camera_details ct ON vl.camera_id = ct.camera_id
            LEFT JOIN customer_management cm ON cm.customer_id = vl.customer_id
            WHERE vl.visitor_list_status = 'Y' AND DATE(vl.visitor_list_entdate) = ?
        `;

        // const today_report1 = `
        //     SELECT vl.store_id, st.store_name, vl.camera_id, ct.camera_position, ct.ip_address, ct.camera_details, cm.customer_id, cm.customer_name, cm.customer_mobile, vl.visitor_gender, vl.min_age, vl.max_age, vl.age_category, vl.aws_faceid, vl.visitor_id, vl.visitor_list_entdate
        //     FROM visitor_list vl
        //     LEFT JOIN store_details st ON vl.store_id = st.store_id
        //     LEFT JOIN camera_details ct ON vl.camera_id = ct.camera_id
        //     LEFT JOIN customer_management cm ON cm.customer_id = vl.customer_id
        //     WHERE vl.visitor_list_status = 'Y' AND DATE(vl.visitor_list_entdate) = ? AND 1=1
        // `;

        console.log("[today report query request] : " + today_report);
        const today_result = await db.query(today_report, [today]);
        console.log("[today report query response] : " + JSON.stringify(today_result));

        // console.log("[today report query request] : " + today_report1);
        // const today_result1 = await db.query(today_report1, [today]);
        // console.log("[today report query response] : " + JSON.stringify(today_result1));

        if (today_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'Report does not exist.' };
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
        console.log("[change today report failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    todayReport
};

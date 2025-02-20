// const md5 = require("md5");
// const db = require("../../db_connect/connect");
// const { $_super } = require("../../validation/camera_check");
// const { result } = require("@hapi/joi/lib/base");
// const { query } = require("express");
// require("dotenv").config();

// async function dashboardChange(req) {
//     try {
//         const today_date = req.body.today_date; // Assuming the date is passed in the request body
//         console.log("[change dash query parameters] : " + JSON.stringify(req.body));

//         console.log('today_date', today_date);
//         const query  = `
//             SELECT count(DISTINCT visitor_id) cnt_visitor_id, count(visitor_list_id) cnt_visitor,
//             (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "M") cnt_male,
//             (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "M") cnt_unique_male,
//             (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "F") cnt_female,
//             (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "F") cnt_unique_female,
//             (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "O") cnt_others,
//             (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and visitor_gender = "O") cnt_unique_others,
//             (select count(vl.visitor_list_id) from visitor_list vl where (date(vl.visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and (SELECT round(((sum(min_age) + sum(max_age)) / 2)) FROM visitor_list svl WHERE svl.visitor_list_id = vl.visitor_list_id) < 13) cnt_kids,
//             (select count(vl.visitor_list_id) from visitor_list vl where (date(vl.visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}') and (SELECT round(((sum(min_age) + sum(max_age)) / 2)) FROM visitor_list svl WHERE svl.visitor_list_id = vl.visitor_list_id) > 13) cnt_adult,
//             (select count(customer_id) from customer_management where (date(cus_mgt_entry_date) BETWEEN '${today_date}' AND '${today_date}')) cnt_registration
//             FROM visitor_list where (date(visitor_list_entdate) BETWEEN '${today_date}' AND '${today_date}')
//         `;
//         console.log("[select query request] : " + query);

//         const dashbord_report = await db.query(query);

//         console.log("[select query response] : " + JSON.stringify(dashbord_report));

//         if (dashbord_report.length == 0) {
//             return { response_code: 0, response_status: 201, response_msg: 'Register does not exist.' };
//         } else {
//             return { response_code: 1, response_status: 200, response_msg: 'Success',"fdfvgg":dashbord_report };
//         }
//     } catch (e) {
//         console.log("[change cam failed response] : " + e);
//         return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
//     }
// }

// module.exports = {
//     dashboardChange
// };

const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/dashbord_change");
require("dotenv").config();

async function dashboardChange(req) {
    try {
        const moment = require('moment');

const yesterday_date = moment().subtract(1, 'days').format('YYYY-MM-DD');
const yesterday_date_db = moment().subtract(1, 'days').format('DDMMYYYY');

console.log('Yesterday\'s date in YYYY-MM-DD format:', yesterday_date);
console.log('Yesterday\'s date in DDMMYYYY format:', yesterday_date_db);
        // const today_date = req.body.today_date; // Assuming the date is passed in the request body
        const today = new Date();
        const current_date = today.toISOString().split('T')[0];
        console.log("[change dash query parameters] : " + JSON.stringify(req.body));

        console.log('today_date', current_date);
        
        const dashbord_report = `
            SELECT count(DISTINCT visitor_id) cnt_visitor_id, count(visitor_list_id) cnt_visitor,
            (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "M") cnt_male,
            (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "M") cnt_unique_male,
            (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "F") cnt_female,
            (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "F") cnt_unique_female,
            (select count(visitor_list_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "O") cnt_others,
            (select count(DISTINCT visitor_id) from visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and visitor_gender = "O") cnt_unique_others,
            (select count(vl.visitor_list_id) from visitor_list vl where (date(vl.visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and (SELECT round(((sum(min_age) + sum(max_age)) / 2)) FROM visitor_list svl WHERE svl.visitor_list_id = vl.visitor_list_id) < 13) cnt_kids,
            (select count(vl.visitor_list_id) from visitor_list vl where (date(vl.visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}') and (SELECT round(((sum(min_age) + sum(max_age)) / 2)) FROM visitor_list svl WHERE svl.visitor_list_id = vl.visitor_list_id) > 13) cnt_adult,
            (select count(customer_id) from customer_management where (date(cus_mgt_entry_date) BETWEEN '${current_date}' AND '${current_date}')) cnt_registration
            FROM visitor_list where (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}')
        `;

        const dashbord_report1 = `
            SELECT hour(visitor_list_entdate) hour_time, count(visitor_list_id) visitor_count
            FROM visitor_list
            WHERE (date(visitor_list_entdate) BETWEEN '${current_date}' AND '${current_date}')
            GROUP BY hour(visitor_list_entdate)
            ORDER BY visitor_list_entdate ASC
        `;
        const dashbord_report2 = `
        select hour(visitor_list_entdate) hour_time, count(visitor_list_id) visitor_count from  mms_service_backup.visitor_list_${yesterday_date_db}  where (date(visitor_list_entdate) BETWEEN '${yesterday_date}' AND '${yesterday_date}') group by hour(visitor_list_entdate) order by visitor_list_entdate asc
    `;

        console.log("[dashbord query request] : " + dashbord_report);
        const dashbord_result = await db.query(dashbord_report);
        console.log("[dashbord query response] : " + JSON.stringify(dashbord_result));

        console.log("[dashbord query request] : " + dashbord_report1);
        const dashbord_result1 = await db.query(dashbord_report1);
        console.log("[dashbord query response] : " + JSON.stringify(dashbord_result1));
        console.log("[dashbord query request] : " + dashbord_report1);
        const dashbord_result2 = await db.query(dashbord_report2);
        console.log("[dashbord query response] : " + JSON.stringify(dashbord_result2));

        if (dashbord_result.length == 0) {
            return { response_code: 0, response_status: 201, response_msg: 'dashbord does not exist.' };
        } else {
            return {
                response_code: 1,
                response_status: 200,
                response_msg: 'Success',
                data: {
                    report: dashbord_result,
                    today_graph: dashbord_result1,
                    yesterday_graph: dashbord_result2
                }
            };
        }
    } catch (e) {
        console.log("[change dashbord failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    dashboardChange
};

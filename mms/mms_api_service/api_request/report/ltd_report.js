const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/ltd_report");
// const { CostExplorer } = require("aws-sdk");
// const { Console } = require("winston/lib/winston/transports");
require("dotenv").config();

async function ltdReport(req) {
    try {

        console.log("[change dash query parameters] : " + JSON.stringify(req.body));

        // Get yesterday's date
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        
        // Format yesterday's date as ddmmyyyy
        const day = String(yesterday.getDate()).padStart(2, '0');
        const month = String(yesterday.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so add 1
        const year = yesterday.getFullYear();
        const formattedYesterday = `${day}${month}${year}`;

        // Format today's date as yyyy-mm-dd
        const formattedToday = formatDate(yesterday);

        const table_name = `visitor_list_${formattedYesterday}`; // Ensure the table name includes the date

        const ltd_report = `
            SELECT * FROM information_schema.tables 
            WHERE table_schema = '${process.env.BACKUP_DB_NAME}' 
            AND table_name = '${table_name}' 
            LIMIT 1
        `;

        const ltd_report1 = `
            SELECT DISTINCT 
                '${formattedToday}' AS visitor_list_entdate,
                COUNT(visitor_list_id) AS cnt_visitor,
                (SELECT COUNT(visitor_list_id) FROM ${process.env.BACKUP_DB_NAME}.${table_name} WHERE visitor_gender = 'M') AS cnt_male,
                (SELECT COUNT(visitor_list_id) FROM ${process.env.BACKUP_DB_NAME}.${table_name} WHERE visitor_gender = 'F') AS cnt_female,
                (SELECT COUNT(visitor_list_id) FROM ${process.env.BACKUP_DB_NAME}.${table_name} WHERE visitor_gender = 'O') AS cnt_others,
                (SELECT COUNT(customer_id) FROM mms.customer_management WHERE DATE(cus_mgt_entry_date) = '${formattedToday}') AS cnt_registration
            FROM ${process.env.BACKUP_DB_NAME}.${table_name}
            WHERE DATE(visitor_list_entdate) = '${formattedToday}'
        `;
        console.log("[ltd query request] : " + ltd_report);
        const ltd_result = await db.query(ltd_report);
        console.log("[ltd query response] : " + JSON.stringify(ltd_result));

        console.log("[ltd query request] : " + ltd_report1);
        const ltd_result1 = await db.query(ltd_report1);
        console.log("[ltd query response] : " + JSON.stringify(ltd_result1));

        if (ltd_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'ltdreport does not exist.' };
        } else {
            return {
                response_code: 1,
                response_status: 200,
                response_msg: 'Success',
                data: {
                    report: ltd_result1
                }
            };
        }
    } catch (e) {
        console.log("[change ltd failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

// Function to format date as yyyy-mm-dd
function formatDate(date) {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so we add 1
    const day = date.getDate().toString().padStart(2, '0');        
    return `${year}-${month}-${day}`;
}

module.exports = {
    ltdReport
};

const { copyFileSync } = require("fs");
const db = require("../../db_connect/connect");
const { changePassword } = require("../user/change_password");
require("dotenv").config();

async function lmtdReport(req) {
    try {
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // January is 0
            const year = date.getFullYear();
            return `${day}${month}${year}`;
        }
        // Function to format date as yyyy-mm-dd (for SQL queries)
        function formatDateQuery(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // January is 0
            const year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Main function to generate monthly report
        const today = new Date();

        // Calculate the start date of the previous month
        const previousMonthStartDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        // One day before the start of the current month
        const oneDayBeforeCurrentMonthStartDate = new Date(today.getFullYear(), today.getMonth(), 0);
        
        // console.log("Previous Month End Date:", previousMonthEndDate);
        // console.log("One Day Before Current Month Start Date:", oneDayBeforeCurrentMonthStartDate);
        
        // Calculate difference in days between the start date and end date of the previous month
        const differenceInDaysPreviousMonth = Math.floor((oneDayBeforeCurrentMonthStartDate - previousMonthStartDate) / (1000 * 60 * 60 * 24));
        
        // Print the results
        console.log(`Previous month start date (${formatDate(previousMonthStartDate)}) to end date last month (${formatDate(oneDayBeforeCurrentMonthStartDate)}): ${differenceInDaysPreviousMonth + 1} days`);
        
        // Array to store union queries
        let unionQueries = [];
        // Iterate through each day of the month
        for (let i = 0; i <= differenceInDaysPreviousMonth; i++) {
            let currentDate = new Date(previousMonthStartDate);
            currentDate.setDate(previousMonthStartDate.getDate() + i);
            let table_name = `visitor_list_${formatDate(currentDate)}`;
            
            // Check if the table exists
            const checkTableQuery = `
                SELECT 1
                FROM information_schema.tables 
                WHERE table_schema = '${process.env.BACKUP_DB_NAME}' 
                AND table_name = '${table_name}' 
                LIMIT 1
            `;

            const tableResult = await db.query(checkTableQuery);
            
            
            if (tableResult.length > 0) {
                // Construct the query for this table
                const lmtd_report_query = `
                   SELECT 
                      '${formatDateQuery(currentDate)}' AS visitor_list_entdate, 
                      COALESCE(COUNT(visitor_list_id), 0) AS cnt_visitor, 
                      COALESCE(SUM(CASE WHEN visitor_gender = 'M' THEN 1 ELSE 0 END), 0) AS cnt_male, 
                      COALESCE(SUM(CASE WHEN visitor_gender = 'F' THEN 1 ELSE 0 END), 0) AS cnt_female, 
                      COALESCE(SUM(CASE WHEN visitor_gender NOT IN ('M', 'F') THEN 1 ELSE 0 END), 0) AS cnt_others, 
                      COALESCE((SELECT COUNT(customer_id) 
                                FROM mms.customer_management 
                                WHERE DATE_FORMAT(cus_mgt_entry_date, '%d-%m-%Y') = '${formatDate(currentDate)}'), 0) AS cnt_registration 
                  FROM 
                      ${process.env.BACKUP_DB_NAME}.${table_name} 
                  WHERE 
                      DATE(visitor_list_entdate) = '${formatDateQuery(currentDate)}'`;
            

                unionQueries.push(lmtd_report_query);
                console.log
            } else {
                // If table does not exist, add placeholder query with zeros
                const emptyQuery = `
                    SELECT 
                        '${formatDateQuery(currentDate)}' AS visitor_list_entdate, 
                        0 AS cnt_visitor, 
                        0 AS cnt_male, 
                        0 AS cnt_female, 
                        0 AS cnt_others, 
                        0 AS cnt_registration
                `;
                unionQueries.push(emptyQuery);
            }
        }

        // Combine unionQueries into a single query
        let fullQuery = unionQueries.join(' UNION ');

        // Log the full combined query for debugging purposes
        console.log("[Full Query]: " + fullQuery);
        // Execute the combined query
        if (unionQueries.length === 0) {
            console.log("This month started today, so can't get data.");
            return [0];
        }
        const lmtd_result = await db.query(fullQuery);

        // Log the response from the database query
        console.log("[lmtd query response]: " + JSON.stringify(lmtd_result));

        // Check if query returned any results
        if (lmtd_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'No data found for the specified period.' };
        }

        // Return success response with the data
        return {
            response_code: 1,
            response_status: 200,
            response_msg: 'Success',
            lmtd_result
        };
    } catch (error) {
        // Handle errors and return error response
        console.error("[lmtdReport] Error: ", error);
        return { response_code: 0, response_status: 500, response_msg: 'Error occurred', error: error.message };
    }
}

module.exports = {
    lmtdReport
};

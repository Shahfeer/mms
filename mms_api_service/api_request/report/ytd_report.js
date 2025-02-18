// Import necessary modules
const { copyFileSync } = require("fs");
const db = require("../../db_connect/connect");
const { changePassword } = require("../user/change_password");
require("dotenv").config();


   // Main function to generate monthly report
   async function ytdReport(req) {
    try {
// Function to format date as ddmmyyyy
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

    
              // Get today's date
       const today = new Date();
       
       // Calculate the start date of the current year
       const currentYearStartDate = new Date(today.getFullYear(), 0, 1);
       
       // Calculate yesterday's date
       const yesterday = new Date(today);
       yesterday.setDate(today.getDate() - 1);
       
       // Calculate difference in days between the start date of the current year and yesterday's date
       const differenceInDays = Math.floor((yesterday - currentYearStartDate) / (1000 * 60 * 60 * 24));
       
       
       // Print the results
       console.log(`Current year start date (${formatDate(currentYearStartDate)}) to yesterday (${formatDate(yesterday)}): ${differenceInDays} days`);
       

        // Array to store union queries
        let unionQueries = [];

        // Iterate through each day of the month
        for (let i = 0; i <= differenceInDays; i++) {
            let currentDate = new Date(currentYearStartDate);
            currentDate.setDate(currentYearStartDate.getDate() + i);
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
            console.log(checkTableQuery);
            console.log(tableResult);
            
            if (tableResult.length > 0) {
                // Construct the query for this table
                const ytd_report_query = `
                   SELECT 
                      '${formatDateQuery(currentDate)}' AS visitor_list_entdate, 
                      COALESCE(COUNT(visitor_list_id), 0) AS cnt_visitor, 
                      COALESCE(SUM(CASE WHEN visitor_gender = 'M' THEN 1 ELSE 0 END), 0) AS cnt_male, 
                      COALESCE(SUM(CASE WHEN visitor_gender = 'F' THEN 1 ELSE 0 END), 0) AS cnt_female, 
                      COALESCE(SUM(CASE WHEN visitor_gender NOT IN ('M', 'F') THEN 1 ELSE 0 END), 0) AS cnt_others, 
                      COALESCE((SELECT COUNT(customer_id) 
                                FROM mms.customer_management 
                                WHERE DATE_FORMAT(cus_mgt_entry_date, '%d%m%Y') = '${formatDate(currentDate)}'), 0) AS cnt_registration 
                  FROM 
                      ${process.env.BACKUP_DB_NAME}.${table_name} 
                  WHERE 
                      DATE(visitor_list_entdate) = '${formatDateQuery(currentDate)}'`;
            

                unionQueries.push(ytd_report_query);
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
        const ytd_result = await db.query(fullQuery);

        // Log the response from the database query
        console.log("[ytd query response]: " + JSON.stringify(ytd_result));

        // Check if query returned any results
        if (ytd_result.length === 0) {
            return { response_code: 0, response_status: 201, response_msg: 'No data found for the specified period.' };
        }

        // Return success response with the data
        return {
            response_code: 1,
            response_status: 200,
            response_msg: 'Success',
            ytd_result
        };
    } catch (error) {
        // Handle errors and return error response
        console.error("[ytdReport] Error: ", error);
        return { response_code: 0, response_status: 500, response_msg: 'Error occurred', error: error.message };
    }
}

module.exports = {
    ytdReport
};

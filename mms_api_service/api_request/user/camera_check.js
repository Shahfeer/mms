const md5 = require("md5");
const db = require("../../db_connect/connect");
const { $_super } = require("../../validation/camera_check");
require("dotenv").config();

async function cameraCheck(req) {
    try {
        console.log("[change cam query parameters] : " + JSON.stringify(req.body));

        var camera_name = req.body.camera_name;
        var list = req.body.list;
        var ip_address = req.body.ip_address;
        var camera_details = req.body.camera_details;
        var cameraradio1 = req.body.cameraradio1;

        console.log(camera_name);
        console.log("[select query request] : " + `SELECT * FROM camera_details where camera_position='${camera_name}'`);

        const get_user = await db.query(`SELECT * FROM camera_details where camera_position=?`, [camera_name]);

        console.log("[select query response] : " + JSON.stringify(get_user));

        if (get_user.length == 0) {
            return { response_code: 0, response_status: 201, response_msg: 'Camera does not exist.' };
        }

        let truncatedStatus = cameraradio1; // default to original value
        const maxStatusLength = 255; // maximum allowed length in the database schema

        if (cameraradio1.length > maxStatusLength) {
            console.log(`[Warning] Camera status exceeds maximum length (${maxStatusLength}). Truncating...`);
            truncatedStatus = cameraradio1.substring(0, maxStatusLength);
        }

        console.log("[insert query request] : " + `INSERT INTO camera_details (camera_id, store_id, camera_position, ip_address, camera_details, Video_url, start_stop_action, camera_status, camera_entry_date) VALUES (NULL, '1', '${camera_name}', '${ip_address}', '${camera_details}', 'NULL', 'N', 'Y', NOW())`);
        
        // Execute the INSERT query for camera_details
        const insertResult = await db.query(`INSERT INTO camera_details (camera_id, store_id, camera_position, ip_address, camera_details, Video_url, start_stop_action, camera_status, camera_entry_date) VALUES (NULL, '1', '${camera_name}', '${ip_address}', '${camera_details}', 'NULL', 'N', 'Y', NOW())`);

        console.log("[insert query response] : " + JSON.stringify(insertResult));
        
        return { response_code: 1, response_status: 200, response_msg: 'Success' };
    } catch (e) {
        console.log("[change cam failed response] : " + e);
        return { response_code: 0, response_status: 201, response_msg: 'Error occurred' };
    }
}

module.exports = {
    cameraCheck
};

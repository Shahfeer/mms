const express = require("express");
const router = express.Router();
// Import the functions page
const today_report= require("./today_report");
const todaysummary_report= require("./todaysummary_report");
const ltd_report = require("./ltd_report");
const mtd_report = require("./mtd_report");
const lmtd_report = require("./lmtd_report");
const ytd_report = require("./ytd_report");
const userlist_report = require("./userlist_report");
const interest_list = require("./interest_list");

// Import the validation page
// // Import the default validation middleware
const validator = require('../../validation/middleware');
const ltdReportValidation = require("../../validation/ltd_report")
const mtdReportValidation = require("../../validation/mtd_report")
const lmtdReportValidation = require("../../validation/lmtd_report")
const ytdReportValidation = require("../../validation/ytd_report")
const todayReportValidation = require("../../validation/today_report")
const todaysummaryReportValidation = require("../../validation/todaysummary_report")
const userlistReportValidation = require("../../validation/userlist_report")
const interestListReportValidation = require("../../validation/interest_list")

// const valid_user = require("../../validation/valid_user_middleware_reqID");
const valid_list = require("../../validation/valid_user_middleware");


// const main = require('../../logger');


// today report api -start
router.post(
    "/today_report",
    validator.body(todayReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await today_report.todayReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// today report api end

// todaysummary report api -start
router.post(
    "/todaysummary_report",
    validator.body(todaysummaryReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await todaysummary_report.todaysummaryReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// todaysummary report api end

// ltd report api -start
router.post(
    "/ltd_report",
    validator.body(ltdReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await ltd_report.ltdReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //ltd report api end

// mtd report api -start
router.post(
    "/mtd_report",
    validator.body(mtdReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await mtd_report.mtdReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //mtd report api end


// lmtd report api -start
router.post(
    "/lmtd_report",
    validator.body(lmtdReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await lmtd_report.lmtdReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //lmtd report api end


// ytd report api -start
router.post(
    "/ytd_report",
    validator.body(ytdReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await ytd_report.ytdReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //ytd report api end

// userlist report api -start
router.post(
    "/userlist_report",
    validator.body(userlistReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await userlist_report.userlistReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //userlist report api end


// interest_list report api -start
router.post(
    "/interest_list",
    validator.body(interestListReportValidation),
    valid_list,
    async function (req, res, next) {
        try { // access the getNumbers function

            var result = await interest_list.interestlistReport(req);

            console.log("[API RESPONSE] " + JSON.stringify(result))

            res.json(result);
        } catch (err) { // any error occurres send error response to client
            console.error(`Error while getting data`, err.message);
            next(err);
        }
    }
);
// //userlist report api end

module.exports = router;
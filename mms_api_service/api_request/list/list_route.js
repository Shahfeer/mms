/*
This api has dashboard API functions which is used to routing the dashboard.
This page is used to create the url for dashboard API functions .
It will be used to run the dashboard process to check and the connect database to get the response for this function.
After get the response from API, send it back to callfunctions.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const express = require("express");
const router = express.Router();

require("dotenv").config();
const fs = require('fs');
const db = require("../../db_connect/connect");
const path = require('path');

// Import the list functions page
const country_list = require("./country_list");
const masterlanguage = require("./masterlanguage");

// Import the validation page
const CountryListValidation = require("../../validation/country_list");
const MasterLanguageValidation = require("../../validation/master_language");

// Import the default validation middleware
const validator = require('../../validation/middleware')
const valid_user = require("../../validation/valid_user_middleware");
const main = require('../../logger');

// country_list -start
router.post(
  "/country_list",
  validator.body(CountryListValidation),
  valid_user,
  async function (req, res, next) {
    try {// access the CountryList function
      var logger = main.logger
      var result = await country_list.CountryList(req);

      logger.info("[API RESPONSE] " + JSON.stringify(result))

      res.json(result);
    } catch (err) {// any error occurres send error response to client
      console.error(`Error while getting data`, err.message);
      next(err);
    }
  }
);
// country_list -end


// whatsapp_senderid -start
router.post(
  "/master_language",
  validator.body(MasterLanguageValidation),
  valid_user,
  async function (req, res, next) {
    try {// access the MasterLanguage function
      var logger = main.logger

      var result = await masterlanguage.MasterLanguage(req);

      logger.info("[API RESPONSE] " + JSON.stringify(result))

      res.json(result);
    } catch (err) { // any error occurres send error response to client
      console.error(`Error while getting data`, err.message);
      next(err);
    }
  }
);
// whatsapp_senderid -end

module.exports = router;

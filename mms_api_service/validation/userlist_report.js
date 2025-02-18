/*
It is used to one of which is user input validation.
userlistReport function to validate the user.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const Joi = require("@hapi/joi");
// To declare userlistReport object 
const userlistReport = Joi.object().keys({
  // Object Properties are define
  

}).options({ abortEarly: false });
// To exports the userlistReport module
module.exports =userlistReport

